<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
include_once(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/agilemultipleseller.php");
include_once(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/SellerInfo.php");
include_once(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/AgileSellerPaymentInfo.php");

class AdminSellerPaymentinfosController extends ModuleAdminController
{
	protected $position_identifier = 'id_agile_seller_paymentinfo';
	
	protected $link;
	protected $integratedModules = array();
	
	public function __construct()
	{
		$this->table = 'agile_seller_paymentinfo';
		$this->identifier = 'id_agile_seller_paymentinfo';
		$this->className = 'AgileSellerPaymentInfo';
		$this->lang = false;
		$this->bootstrap = true;

		parent::__construct();
		$this->actions = [];
		if(!$this->is_seller)
		{
			$this->addRowAction('edit');			$this->addRowAction('delete');		}
		$this->fields_list = array(
			'id_agile_seller_paymentinfo' => array(
					'title' => $this->l('ID'),
					'align' => 'center',
					'filter_key' => 'a!id_agile_seller_paymentinfo'
					
					),
				'id_seller' => array(
					'title' => $this->l('Seller ID'),
					'align' => 'center',
					'filter_key' => 'a!id_seller'
					),
				'in_use' => array(
					'title' => $this->l('Use This Module'),
					'align' => 'center',
					'filter_key' => 'a!in_use'
					),
				'module_name' => array(
					'title' => $this->l('Payment Module'),
					'align' => 'center',
					'filter_key' => 'a!module_name'
					),
				'company' => array(
					'title' => $this->l('Company'),
					'align' => 'center',
					'filter_key' => 'sl!company'
					),
				'info1' => array(
					'title' => $this->l('info1'),
					'align' => 'center',
					'filter_key' => 'a!info1'
					),
				'info2' => array(
					'title' => $this->l('info2'),
					'align' => 'center',
					'filter_key' => 'a!info2'
					),
				'info3' => array(
					'title' => $this->l('info3'),
					'align' => 'center',
					'filter_key' => 'a!info3'
					),
				'info4' => array(
					'title' => $this->l('info4'),
					'align' => 'center',
					'filter_key' => 'a!info4'
					),
				'info5' => array(
					'title' => $this->l('info5'),
					'align' => 'center',
					'filter_key' => 'a!info5'
					),
				'info6' => array(
					'title' => $this->l('info6'),
					'align' => 'center',
					'filter_key' => 'a!info6'
					),
				'info7' => array(
					'title' => $this->l('info7'),
					'align' => 'center',
					'filter_key' => 'a!info7'
					),
				'info8' => array(
					'title' => $this->l('info8'),
					'align' => 'center',
					'filter_key' => 'a!info8'
					),
				);


		
		$this->_join = '
			LEFT JOIN `'._DB_PREFIX_.'sellerinfo` s ON (a.`id_seller` = s.`id_seller`)
			LEFT JOIN `'._DB_PREFIX_.'sellerinfo_lang` sl ON (s.`id_sellerinfo` = sl.`id_sellerinfo` AND sl.id_lang=' . $this->context->language->id . ')
            ';


		$this->_select = 'sl.company as company';

		if($this->is_seller)
		{
			$this->_where = ' AND a.id_seller = ' . $this->context->cookie->id_employee;
		}

		
		$am = new AgileMultipleSeller();
		$this->integratedModules = AgileMultipleSeller::RemoveNotWantedModules($am->GetIntegratedPaymentModules(), array('agileprepaidcredit'));
		
	}
	
	public function init()
	{
				if (Tools::getValue('submitAdd' . $this->table))
			$_POST['submitAdd' . $this->table .'AndStay'] = 1;
			
		parent::init();
	}
	
	
	public function viewAccess($disable = false)
	{
		if(!Module::isInstalled('agilemultipleseller'))return parent::viewAccess($disable);
		
		if($this->is_seller)
		{
			$seller_backoffice_access = (int)Configuration::get('AGILE_MS_SELLER_BACK_OFFICE');
			if(!$seller_backoffice_access)return false;
			$id_seller = $this->context->cookie->id_employee;	
			$editing_id = Tools::getValue('id_agile_seller_paymentinfo');
			if($editing_id>0)
			{
				$payinfo = new AgileSellerPaymentInfo($editing_id);	
				if($payinfo->id_seller != $id_seller)return false;
			}
			return true;
		}
		
		return parent::viewAccess($disable);
	}
	
	public function initContent()
	{
		if ($this->action == 'select_delete')
			$this->context->smarty->assign(array(
				'delete_form' => true,
				'url_delete' => htmlentities($_SERVER['REQUEST_URI']),
				'boxes' => $this->boxes,
			));

		parent::initContent();
	}
	
	public function renderForm()
	{
		if (!($obj = $this->loadObject(true)))
			return;
	
		$labels = 'var labels =' . Tools::jsonEncode($this->integratedModules). ';';

		$this->fields_form = array(
			'legend' => array(
				'title' => $this->l('Seller Payment Info')
			),
				'input' => array(
					array(
						'type' => 'select',
						'label' => $this->l('Seller:'),
						'name' => 'id_seller',
						'options' => array(
							'query' => AgileSellerManager::getSellersNV(true, '--'),
							'id' => 'id_seller',
							'name' => 'name',
							),
						'required' => false,
						),
					array(
						'type' => 'select',
						'label' => $this->l('Payment Module:'),
						'name' => 'module_name',
						'options' => array(
							'query' => $this->integratedModules,
							'id' => 'name',
							'name' => 'desc',
							),
						'required' => false,
						),
					array(
						'type' => 'checkbox',
						'name' => 'in_use',
						'values' => array(
							'query' => array(
								array('id'=> 'on', 'name' => $this->l('Use This Module'), 'val' => '1'),
								),
							'id' => 'id',
							'name' => 'name'
							),
						'required' => false
						),
					array(
						'type' => 'textarea',
						'label' => $this->l('Field 1:'),
						'name' => 'info1',
						'rows' => 3,
						'cols' => 100,
						'required' => false
						),
					array(
						'type' => 'textarea',
						'label' => $this->l('Field 2:'),
						'name' => 'info2',
						'rows' => 3,
						'cols' => 100,
						'required' => false
						),
					array(
						'type' => 'textarea',
						'label' => $this->l('Field 3:'),
						'name' => 'info3',
						'rows' => 3,
						'cols' => 100,
						'required' => false
						),
					array(
						'type' => 'textarea',
						'label' => $this->l('Field 4:'),
						'name' => 'info4',
						'size' => 33,
						'required' => false
						),
					array(
						'type' => 'textarea',
						'label' => $this->l('Field 5:'),
						'name' => 'info5',
						'rows' => 3,
						'cols' => 100,
						'required' => false
						),
					array(
						'type' => 'textarea',
						'label' => $this->l('Field 6:'),
						'name' => 'info6',
						'rows' => 3,
						'cols' => 100,
						'required' => false
						),
					array(
						'type' => 'textarea',
						'label' => $this->l('Field 7:'),
						'name' => 'info7',
						'rows' => 3,
						'cols' => 100,
						'required' => false
						),
					array(
						'type' => 'textarea',
						'label' => $this->l('Field 8:'),
						'name' => 'info8',
						'rows' => 3,
						'cols' => 100,
						'required' => false
						),
					array(
						'type' => 'elinks',
						'label' => '',
						'name' => 'elinks',
						'rows' => 3,
						'cols' => 100,
						'required' => false
						)
					)
		);

		if((int)$obj->id >0)
		{
			foreach($this->fields_form['input'] as &$input)
			{
				if($input['name'] == 'id_seller' || $input['name'] == 'module_name') $input['disabled'] = true;
			}
		}
		else
		{
			foreach($this->fields_form['input'] as &$input)
			{
				if($input['name'] == 'id_seller' && $this->is_seller) 
				{
					$input['disabled'] = true;
				}
			}
		}


		$this->fields_form['submit'] = array(
			'title' => $this->l('Save'),
			'class' => 'btn btn-default pull-right'
		);

		$sellerinfo = new SellerInfo(SellerInfo::getIdBSellerId($this->object->id_seller));
		$tokenSellerinfo = Tools::getAdminToken('AdminSellerinfos'.(int)(Tab::getIdFromClassName('AdminSellerinfos')).(int)$this->context->cookie->id_employee);

		$this->tpl_form_vars = array(
            'agilemultipleseller_views' => _PS_ROOT_DIR_  . "/modules/agilemultipleseller/views/",
            'base_dir' => _PS_BASE_URL_.__PS_BASE_URI__,
            'base_dir_ssl' => _PS_BASE_URL_SSL_.__PS_BASE_URI__,
            'sellerinfo' => $sellerinfo,
            'tokenSellerinfo' => $tokenSellerinfo,
			'is_seller' => ($this->context->cookie->profile == (int)Configuration::get('AGILE_MS_PROFILE_ID')? 1 : 0) ,
			'current_id_seller' => $this->context->cookie->id_employee,
			'labels' => $labels 
		);		
		
		$this->fields_value = array(
			'in_use_on' => $this->getFieldValue($obj, 'in_use')
			);
		
		return parent::renderForm();
	}

	private function getPostedEditingModuleName($id)
	{
		$modulename = Tools::getValue('module_name');
				if($id >0)
		{
			$pobj = new AgileSellerPaymentInfo($id);
			if(Validate::isLoadedObject($pobj))
			{
				$modulename = $pobj->module_name;
			}
		}
		return $modulename;
	}

	private function getPostedEditingModuleSellerID($id)
	{
		$id_seller = Tools::getValue('id_seller');
				if($id >0)
		{
			$pobj = new AgileSellerPaymentInfo($id);
			if(Validate::isLoadedObject($pobj))
			{
				$id_seller = $pobj->id_seller;
			}
		}
		return $id_seller;
	}
	
	private function loadPaymentInfoObject($id)
	{
		$pobj = new AgileSellerPaymentInfo($id);
		for($idx =1; $idx <=8 ; $idx++)
		{
			$pobj->{'info'.$idx} = Tools::getValue('info' . $idx);
		}
		if($id == 0)
		{
			$pobj->id_seller = Tools::getValue('id_seller');
			$pobj->module_name = Tools::getValue('module_name');
		}
		
		return $pobj;
	}
	
	public function processSave()
	{
		if($this->is_seller)$_POST['id_seller'] = $this->context->cookie->id_employee;

		for($idx=1;$idx<=8;$idx++)$_POST['info' . $idx] = trim(Tools::getValue('info' .  $idx, ''));
		
		$id = (int)Tools::getValue('id_agile_seller_paymentinfo');
				$modulename = $this->getPostedEditingModuleName($id);
		$id_seller = (int)$this->getPostedEditingModuleSellerID($id);
		if($modulename == "" OR $modulename =="0")
		{	
			$this->errors[] = Tools::displayError('Please seller a payment module - Payment module is required.');
		}
		if($id_seller <=0)
		{
			$this->errors[] = Tools::displayError('Please select a seller - Seller  is required.');
		}
		
				if($id <=0 && AgileSellerPaymentInfo::is_seller_payment_info_existed($modulename, $id_seller))
		{	
			$this->errors[] = Tools::displayError('Seller Payment Info '.$modulename.' module already exsited.');
		}

		if(Module::isInstalled($modulename))
		{
						$modInstance = Module::getInstanceByName($modulename);
			if(method_exists($modInstance, "validatePaymentInfoFields"))
			{
				$this->errors = array_merge($this->errors, $modInstance->validatePaymentInfoFields($_POST));
			}
						if(AgileSellerPaymentInfo::hasDuplicationRecord($this->loadPaymentInfoObject($id), $this->integratedModules[$modulename]))
			{
				$this->errors = $this->integratedModules[$modulename]['desc'] . ' ' .Tools::displayError('requires UNIQUE payment info for each seller - duplication data detected');
			}
		}
		
				if($modulename == 'agilepaypal')
		{
			if(!Validate::isEmail($_POST['info1']))
			{
				$this->errors[] = 'agilepaypal:' . $this->l('Paypal Email Address is invalid');
			}
		}
		
		if(!empty($this->errors))
		{
			$this->redirect_after = false;
			return false;
		}

		$_POST['in_use'] = (int)Tools::getValue('in_use_on');
		
		parent::processSave();
	}
}
