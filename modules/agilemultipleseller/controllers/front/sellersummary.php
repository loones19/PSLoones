<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
class AgileMultipleSellerSellerSummaryModuleFrontController extends AgileModuleFrontController
{
	
	public function setMedia()
	{
		parent::setMedia();
		
		Media::addJsDef(array(
			'id_language_current' => self::$cookie->id_lang 
			));
		
		$this->registerStylesheet('jquery.ui.datepicker', '/js/jquery/ui/themes/base/jquery.ui.datepicker.css', ['media' => 'all', 'priority' => 100]);
		$this->registerStylesheet('jquery.fancybox', '/js/jquery/plugins/fancybox/jquery.fancybox.css', ['media' => 'all', 'priority' => 100]);
		
		$this->registerJavascript('js_tools','/js/tools.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('agile_tinymce','/modules/agilemultipleseller/js/agile_tiny_mce.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('agile_filemanager','/modules/agilemultipleseller/filemanager/plugin.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('js_typewatch','/js/jquery/plugins/jquery.typewatch.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('js_fancybox','/js/jquery/plugins/fancybox/jquery.fancybox.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('js_uicore','/js/jquery/ui/jquery.ui.core.min.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('js_uiwidget','/js/jquery/ui/jquery.ui.widget.min.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('js_uimouse','/js/jquery/ui/jquery.ui.mouse.min.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('js_uislider','/js/jquery/ui/jquery.ui.slider.min.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('js_uidatepicker','/js/jquery/ui/jquery.ui.datepicker.min.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('js_uitimepicker','/js/jquery/plugins/timepicker/jquery-ui-timepicker-addon.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('agile_statememneger','/modules/agilemultipleseller/js/AgileStatesManagement.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('agile_dropdown','/modules/agilemultipleseller/replica/themes/default/js/dropdown.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('agile_multilanguage','/modules/agilemultipleseller/js/multi-language.js',['position' => 'bottom', 'priority' => 100]);

		$this->registerJavascript('js_idtabs','/js/jquery/plugins/jquery.idTabs.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('agile_sellerpage','/modules/agilemultipleseller/js/sellerpage.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('agile_sellersummary','/modules/agilemultipleseller/js/sellersummary.js',['position' => 'bottom', 'priority' => 100]);

	}
	
	public function postProcess()
    {		
		$this->context->smarty->assign(array(
			'cfmmsg_flag' => Tools::getValue('cfmmsg_flag')
			));
	
        if (Tools::isSubmit('submitSellerAccount'))
        {
			AgileMultipleSeller::createSellerAccount(new Customer($this->context->customer->id));
			Tools::redirect($this->context->link->getModuleLink('agilemultipleseller','sellersummary',array('cfmmsg_flag'=>1), true));
        }
		if (Tools::getValue('submitRequest') == "B2T")
        {
			$errmsg = AgileMultipleSeller::convert_balance_to_token($this->context->customer->id
				, (float)Tools::getValue('amount_to_convert')
				, $this->l('CHANGE BALANCE TO TOKENS'));
			if(!empty($errmsg))
			{
				$this->errors[] = $errmsg;
				return;
			}
			Tools::redirect($this->context->link->getModuleLink('agilemultipleseller','sellersummary',array('cfmmsg_flag'=>1), true));
		}

		if (Tools::getValue('submitRequest') == "MPR")
        {
			$errmsg = AgileMultipleSeller::make_fund_request($this->context->customer->id
				, (float)Tools::getValue('amount_to_convert')
				, $this->l('MAKE FUND REQUEST'));
			if(!empty($errmsg))
			{
				$this->errors[] = $errmsg;
				return;
			}
			Tools::redirect($this->context->link->getModuleLink('agilemultipleseller','sellersummary',array('cfmmsg_flag'=>1), true));
		}
		
		if (Tools::getValue('submitRequest') == "T2B")
        {
			$errmsg = AgileMultipleSeller::convert_tokens_to_balance($this->context->customer->id
				, (float)Tools::getValue('tokens_to_convert')
				, $this->l('CHANGE TOKENS TO BALANCE'));
			if(!empty($errmsg))
			{
				$this->errors[] = $errmsg;
				return;
			}
			Tools::redirect($this->context->link->getModuleLink('agilemultipleseller','sellersummary',array('cfmmsg_flag'=>1), true));
		}
    }
    
    
	public function initContent()
	{
		parent::initContent();
		
		$this->context->controller->addJQueryPlugin('fancybox');
		$this->context->controller->addJQueryUI('ui.sortable');
		$this->context->controller->addJQueryUI('ui.draggable');
		$this->context->controller->addJQueryUI('effects.transfer');
		$id_lang = Context::getContext()->cookie->id_lang;

		$cms = new CMS((int)(Configuration::get('AGILE_MS_SELLER_TERMS')), (int)($this->context->language->id));
		$link_terms = $this->context->link->getCMSLink($cms, $cms->link_rewrite, true);
		if (!strpos($link_terms, '?'))
			$link_terms .= '?content_only=1';
		else
			$link_terms .= '&content_only=1';

		$this->context->smarty->assign(array(
			'link_terms' => $link_terms,
			'id_cms_seller_terms' =>  intval(Configuration::get('AGILE_MS_SELLER_TERMS'))
		));

		
        $account_balance = 0;
		$paycommission_url = "";;
        if(Module::isInstalled('agilesellercommission'))
        {
			include_once(_PS_ROOT_DIR_ .'/modules/agilesellercommission/agilesellercommission.php');
			include_once(_PS_ROOT_DIR_ .'/modules/agilesellercommission/SellerCommission.php');
			
			$account_balance = AgileSellerManager::getAccountBalance($this->sellerinfo->id_seller);
			$paydata = array(
				'record_type' => SellerCommission::RECORD_TYPE_SELLER_PAY_STORE,
				'id_seller' => $this->sellerinfo->id_seller,
				'amount_to_pay' => (-$account_balance),
				'retkey' => Tools::getValue('token'),
				'paykey' => Tools::encrypt($this->sellerinfo->id_seller),
				'isfront' => 1					
				);
			$paycommission_url = AgileSellerCommission::get_paycommission_url($paydata);
		}
		$token_balance = 0;
		if(Module::isInstalled('agileprepaidcredit'))
        {
			include_once(_PS_ROOT_DIR_ .'/modules/agileprepaidcredit/agileprepaidcredit.php');
			$token_balance = AgilePrepaidCredit::GetTokenBalance($this->context->customer->id);
		}
		

        $def_id_currency = (int)Configuration::get('PS_CURRENCY_DEFAULT');

		$this->context->smarty->assign(array(
            'seller_tab_id' => 1
			,'num_products' => ($this->isSeller?AgileSellerManager::getNumOfProducts($this->sellerinfo->id_seller):0)
			,'num_orders' => ($this->isSeller?AgileSellerManager::getNumOfOrders($this->sellerinfo->id_seller):0)
			,'totals_sold' => AgileSellerManager::getTotalAmountSold($this->sellerinfo->id_seller)
			,'comcurrency' => new Currency((int)Configuration::get('ASC_COMMISSION_CURRENCY'))
			,'paycommission_url' => $paycommission_url
			,'account_balance' => $account_balance
			,'token_balance' => $token_balance
			,'id_language_current' => self::$cookie->id_lang
			,'membership_module_integrated' =>( (Module::isInstalled('agilemembership') AND intval(Configuration::get('AGILE_MEMBERSHIP_SELLER_INTE'))>0) ? 1 : 0)
			,'request_T2B' => $this->l('CHANGE TOKENS TO BALANCE')
			,'request_B2T' => $this->l('CHANGE BALANCE TO TOKENS')
			,'request_MPR' => $this->l('MAKE A PEYMENT REQUEST')
			,'use_paypal_forsellerpayment' => (int)Configuration::get('ASC_USE_PAYPAL')
			
            ));

		$this->setTemplate('module:agilemultipleseller/views/templates/front/sellersummary.tpl');
	}
	
	
}
