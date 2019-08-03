<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
include_once(_PS_ROOT_DIR_ .'/modules/agilemultipleseller/agilemultipleseller.php');
include_once(_PS_ROOT_DIR_ .'/modules/agilemultipleseller/SellerInfo.php');
include_once(_PS_ROOT_DIR_ .'/modules/agilemultipleseller/SellerBankInfo.php');

class AdminSellerBankInfos extends ModuleAdminController
{
	protected $position_identifier = 'id_sellerbankinfo';
	public $table = 'sellerbankinfo';
	
	protected $link;

	public function __construct()
	{
		$this->table = 'sellerbankinfo';
		$this->identifier = 'id_sellerbankinfo';
		$this->className = 'SellerBankInfo';
		$this->lang = false;
		$this->bulk_actions = array();
		$this->bootstrap = true;
		$module = new AgileMultipleSeller();
		
				$this->list_no_link = true;
		$this->addRowAction('edit');		$this->allow_export = true;

		parent::__construct();
		
		$this->fields_list = array(
			'id_sellerbankinfo' => array(
				'title' => $this->l('ID(Info)'),
				'align' => 'center',
				'width' => 60,
				'filter_key' => 'a!id_sellerbankinfo'
			),
			'id_seller' => array(
				'title' => $this->l('Seller ID'),
				'align' => 'center',
				'width' => 60,
				'filter_key' => 'a!id_seller',
				'filter_type' => 'int'
			),
				
			'shop_name' => array(
				'title' => $this->l('Shop'),
				'align' => 'center',
				'width' => 70,
				'filter_key' => 'a!shop_name'
			),

			'business_name' => array(
				'title' => $this->l('Business Name'),
				'align' => 'center',
				'width' => 70,
				'filter_key' => 'a!business_name'
			),
			'bank_name' => array(
					'title' => $this->l('Bank Name'),
				'align' => 'center',
				'width' => 70,
				'filter_key' => 'a!bank_name'
			),
			'bank_address' => array(
				'title' => $this->l('Bank Address'),
				'align' => 'center',
				'width' => 70,
				'filter_key' => 'a!bank_address'
			),
			'account_name' => array(
				'title' => $this->l('Beneficiary Account Name'),
				'align' => 'center',
				'width' => 150,
				'filter_key' => 'a!account_name'
			),
			'account_number' => array(
				'title' => $this->l('Account No.'),
				'align' => 'center',
				'width' => 150,
				'filter_key' => 'a!account_number'
			)
		);
	
		$this->_join = ' LEFT JOIN `'._DB_PREFIX_.'employee` e ON (a.`id_seller` = e.`id_employee`)
			';
		$this->_where = ' AND e.active = 1
			';
	}
		
	public function init()
	{
				if (Tools::getValue('submitAdd' . $this->table))
			$_POST['submitAdd' . $this->table .'AndStay'] = 1;
		
		parent::init();
	}
	
	public function initToolbar()
	{
		parent::initToolbar();
				unset($this->toolbar_btn['new']);
	}
	
	public function processSave()
	{
		$this->object = $this->loadObject();
		$this->errors = array_merge($this->errors, $this->object->validateController());

		$this->object->save();
	}
	
	public function renderForm()
	{
		if (!($obj = $this->loadObject(true)))
			return;

		$this->fields_form = array(
			'legend' => array(
					'title' => $this->l('Seller Info')
					),
				'input' => array(
					array(
						'type' => 'text',
						'label' => $this->l('Seller ID:'),
						'name' => 'id_seller',
						'readonly' => true,
						'required' => false,
						),

					array(
						'type' => 'text',
						'label' => $this->l('Business Name:'),
						'name' => 'business_name',
						'size' => 33,
						'required' => false
						),

					array(
						'type' => 'text',
						'label' => $this->l('Shop Name:'),
						'name' => 'shop_name',
						'size' => 33,
						'required' => false
						),

					array(
						'type' => 'text',
						'label' => $this->l('Beneficiary Account Name:'),
						'name' => 'account_name',
						'size' => 33,
						'required' => false
						),
					array(
						'type' => 'text',
						'label' => $this->l('Account No:'),
						'name' => 'account_number',
						'size' => 33,
						'required' => false
						),
					array(
						'type' => 'text',
						'label' => $this->l('Bank Name:'),
						'name' => 'bank_name',
						'size' => 33,
						'required' => false
						),
					array(
						'type' => 'text',
						'label' => $this->l('Bank Address:'),
						'name' => 'bank_name',
						'size' => 33,
						'required' => false
						),
					array(
						'type' => 'password',
						'label' => $this->l('Password:'),
						'name' => 'passwd',
						'size' => 33,
						'required' => false
						),
					));
		
		
		$this->fields_form['submit'] = array(
			'title' => $this->l('Save'),
			'class' => 'btn btn-default pull-right'
			);
		
		
		$this->tpl_form_vars = array(
			'sellerbankinfo_obj' => $obj
			);
		
		return parent::renderForm();
	}	
	
}
