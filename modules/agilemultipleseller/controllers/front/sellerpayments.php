<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
class AgileMultipleSellerSellerPaymentsModuleFrontController extends AgileModuleFrontController
{
	private $_integratedModules = array();
	private $_sellerinfo;
	
	public function __construct()
	{
		parent::__construct();
		
		require_once(_PS_ROOT_DIR_  . "/modules/agilemultipleseller/SellerInfo.php");
		require_once(_PS_ROOT_DIR_  . "/modules/agilemultipleseller/AgileSellerPaymentInfo.php");
		include_once(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/agilemultipleseller.php");
		$this->_sellerinfo = new SellerInfo(SellerInfo::getIdByCustomerId($this->context->cookie->id_customer), $this->context->cookie->id_lang);
		$am = new AgileMultipleSeller();
		$this->_integratedModules = AgileMultipleSeller::RemoveNotWantedModules($am->GetIntegratedPaymentModules(false), array('agileprepaidcredit'));
		
	}
	
	public function setMedia()
	{
		parent::setMedia();
		
						$deflang = new Language(self::$cookie->id_lang);
		$isoTinyMCE = (file_exists(_PS_ROOT_DIR_.'/js/tiny_mce/langs/'.$deflang->iso_code.'.js') ? $deflang->iso_code : 'en');
		
		Media::addJsDef(array(
			'id_language_current' => self::$cookie->id_lang 
			));
		
	
		$this->registerJavascript('js_tools','/js/tools.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('agile_tinymce','/modules/agilemultipleseller/js/agile_tiny_mce.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('agile_filemanager','/modules/agilemultipleseller/filemanager/plugin.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('agile_multilanguage','/modules/agilemultipleseller/js/multi-language.js',['position' => 'bottom', 'priority' => 100]);

		$this->registerJavascript('js_idtabs','/js/jquery/plugins/jquery.idTabs.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('agile_sellerpage','/modules/agilemultipleseller/js/sellerpage.js',['position' => 'bottom', 'priority' => 100]);		
	}
	
	
	public function postProcess()
	{
		if (Tools::isSubmit('submitSellerinfo'))
			$this->processSubmitSellerinfo();
	}
	
	
	public function initContent()
	{
		parent::initContent();

		if(!empty($this->_integratedModules))
		{
			foreach($this->_integratedModules as $mod)
			{
				$sql = 'SELECT id_agile_seller_paymentinfo FROM ' . _DB_PREFIX_ . 'agile_seller_paymentinfo WHERE id_seller=' . (int)$this->_sellerinfo->id_seller . ' AND module_name=\'' . $mod['name'] . '\'';
				$id_agile_seller_paymentinfo = (int)Db::getInstance()->getValue($sql);
				$mod['data'] = new AgileSellerPaymentInfo($id_agile_seller_paymentinfo);
				$this->_integratedModules[$mod['name']] = $mod;
			}
		}
		
		self::$smarty->assign(array(
			'seller_tab_id' => 5
			,'integratedModules' => $this->_integratedModules
			,'is_agilebankwire_installed' => Module::isInstalled('agilebankwire')
			,'is_agilecashondelivery_installed' => Module::isInstalled('agilecashondelivery')
			,'is_agilegooglecheckout_installed' => Module::isInstalled('agilegooglecheckout')
			,'is_agilepaybycheque_installed' => Module::isInstalled('agilepaybycheque')
			));

		$pay_options_link = '';
		if(Module::isInstalled('agilesellerlistoptions'))
		{
			include_once(_PS_ROOT_DIR_ . '/modules/agilesellerlistoptions/agilesellerlistoptions.php');
			$aslo_module = new AgileSellerListOptions();
			$pay_options_link = $aslo_module->getPayOptionLink($this->_sellerinfo->id_seller);
			self::$smarty->assign(array(
				'pay_options_link' =>$pay_options_link
				));
		}

		$this->setTemplate('module:agilemultipleseller/views/templates/front/sellerpayments.tpl');
	}
	
	protected function processSubmitSellerinfo()
	{
		
		$this->errors = array();
		foreach($this->_integratedModules as $mod)
		{
			$id = (int)Tools::getValue('id_agile_seller_paymentinfo_' . $mod['name']);
			$paymentinfo = new AgileSellerPaymentInfo($id); 
			$paymentinfo->id_seller = $this->_sellerinfo->id_seller;
			$paymentinfo->in_use = Tools::getValue('in_use_' . $mod['name']);
			$paymentinfo->module_name = $mod['name'];
			$paymentinfo->info1 = trim(Tools::getValue('info1_' . $mod['name']));
			$paymentinfo->info2 = trim(Tools::getValue('info2_' . $mod['name']));
			$paymentinfo->info3 = trim(Tools::getValue('info3_' . $mod['name']));
			$paymentinfo->info4 = trim(Tools::getValue('info4_' . $mod['name']));
			$paymentinfo->info5 = trim(Tools::getValue('info5_' . $mod['name']));
			$paymentinfo->info6 = trim(Tools::getValue('info6_' . $mod['name']));
			$paymentinfo->info7 = trim(Tools::getValue('info7_' . $mod['name']));
			$paymentinfo->info8 = trim(Tools::getValue('info8_' . $mod['name']));
			
						if($paymentinfo->in_use == 1)
			{
								if($mod['name'] == 'agilepaypal')
				{
					if(!Validate::isEmail($paymentinfo->info1))
					{
						$this->errors[] = $this->l('Paypal Email Address is invalid');
					}
				}
								if(Module::isInstalled($mod['name']))
				{
					$modInstance = Module::getInstanceByName($mod['name']);
					if(method_exists($modInstance, "validatePaymentInfoFields"))
					{
						$this->errors = array_merge($this->errors, $modInstance->validatePaymentInfoFields($paymentinfo));
					}
				}
								if(AgileSellerPaymentInfo::hasDuplicationRecord($paymentinfo, $mod))
				{
					$this->errors[] = $mod['desc'] . Tools::displayError('requires UNIQUE payment info for each seller - duplication data detected');
				}
			}
			if(empty($this->errors))
			{
				$paymentinfo->save();
			}
		}

		if(empty($this->errors))self::$smarty->assign('cfmmsg_flag',1);
	}
	
	
}
