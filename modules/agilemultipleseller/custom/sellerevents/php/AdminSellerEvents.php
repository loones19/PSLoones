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
include_once(_PS_ROOT_DIR_ .'/modules/agilemultipleseller/SellerType.php');
include_once(_PS_ROOT_DIR_ .'/modules/agilemultipleseller/SellerEvent.php');

class AdminSellerEvents extends ModuleAdminController{
	protected $position_identifier = 'id_event';
	public $table = 'seller_event';
	private $custom_labels;
	
	protected $link;

	public function __construct(){
		$this->table = 'seller_event';
		$this->identifier = 'id_event';
		$this->className = 'SellerEvent';
		$this->lang = false;
		$this->bulk_actions = array();
		$this->bootstrap = true;
		$module = new AgileMultipleSeller();
		$this->custom_labels = $module->getCustomLabels(':');
		
				$this->addRowAction('edit');
        $this->addRowAction('delete');

        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?'),
                'icon' => 'icon-trash'
            )
        );
        
		parent::__construct();

		$this->fields_list = array(
				'id_event' => array(
					'title' => $this->l('ID(Event)'),
					'align' => 'center',
					'width' => 80,
					'filter_key' => 'a!id_event'
				),
				'id_seller' => array(
					'title' => $this->l('ID(Seller)'),
					'align' => 'center',
					'width' => 80,
					'filter_key' => 'a!id_seller',
					'filter_type' => 'int'
				),
				'company' => array(
					'title' => $this->l('Seller'),
					'align' => 'center',
					'width' => 100,
					'filter_key' => 'sl!company',
				),
				'title' => array(
					'title' => $this->l('Title'),
					'align' => 'center',
					'width' => 100,
					'filter_key' => 'el!title'
				),
				'place' => array(
					'title' => $this->l('Place'),
					'align' => 'center',
					'width' => 100,
					'filter_key' => 'a!place'
				),
				'active' => array(
		            'title' => $this->l('Status'),
		            'active' => 'status',
		            'filter_key' => 'a!active',
		            'align' => 'text-center',
		            'type' => 'bool',
		            'class' => 'fixed-width-sm',
		            'orderby' => false
		        )
			);
		$this->_join = '
			LEFT JOIN `'._DB_PREFIX_.'seller_event_lang` el ON (a.`id_event` = el.`id_event` AND el.id_lang=' . $this->context->language->id . ')
			LEFT JOIN `'._DB_PREFIX_.'sellerinfo` as s ON (s.id_seller = a.id_seller)
			LEFT JOIN `'._DB_PREFIX_.'sellerinfo_lang` as sl ON (sl.id_sellerinfo = s.id_sellerinfo AND sl.id_lang=' . $this->context->language->id . ')
            ';

		$this->_select = ' el.title, el.id_lang, sl.company';
	}

	public function generalinfo()
	{	
		global $cookie;
		$linktoshop = '';
		if(Module::isInstalled('agilemultipleshop'))
		{
			$token_shop = Tools::getAdminToken('AdminShopUrl'.(int)(Tab::getIdFromClassName('AdminShopUrl')).(int)$this->context->cookie->id_employee);
			$linktoshop='./index.php?controller=AdminShopUrl&token=' . $token_shop . '&id_shop=' . $this->object->id_shop;
		}
		$years = Tools::dateYears();
        $months = Tools::dateMonths();
        $days = Tools::dateDays();
		$this->fields_form['0']['form'] = array(
			'tab_name' =>'main_tab',
			'legend' => array(
					'title' => $this->l('Seller Event')
			),
			'input' => array(
					array(
						'type' => 'hidden',
						'label' => false,
						'name' => 'id',
						'size' => 255,
						'required' => true,
						'lang' => false,
					),
					array(
						'type' => 'text',
						'label' => $this->l('Title:'),
						'name' => 'title',
						'size' => 255,
						'required' => true,
						'lang' => true,
						'hint' => $this->l('Forbidden characters:').' 0-9!<>,;?=+()@#"?{}_$%:'
					),	
					array(
	                    'type' => 'text',
	                    'label' => $this->l('Start Date:'),
	                    'name' => 'start_date',
	                    'class' => 'datepicker'
	                ),
	                array(
	                    'type' => 'text',
	                    'label' => $this->l('End Date:'),
	                    'name' => 'end_date',
	                    'class' => 'datepicker'
	                ),
					array(
						'type' => 'text',
						'label' => $this->l('Place:'),
						'name' => 'place',
						'size' => 255,
						'required' => true,
						'lang' => false,
						'hint' => $this->l('Forbidden characters:').' 0-9!<>,;?=+()@#"?{}_$%:'
					),					
				));

		$this->fields_form['0']['form']['input'][] =	array(
						'type'  => 'textarea',
						'label' => $this->l('Description:'),
						'name' => 'description',
						'lang' => true,
						'autoload_rte' => true,
						'required' => true,
						'rows' => 10,
						'cols' => 100,
						'size' => 65500,
						'hint' => $this->l('Invalid characters:').' <>;=#{}'
						);
		$this->fields_form['0']['form']['input'][] = array(
                    'type' => 'switch',
                    'label' => $this->l('Displayed'),
                    'name' => 'active',
                    'required' => false,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                );

		
		$this->fields_form['0']['form']['submit'] = array(
			'title' => $this->l('Save'),
			'class' => 'btn btn-default pull-right'
			);
        
	}

	public function renderForm(){
		global $cookie;

		if (!($obj = $this->loadObject(true)))
			return;

		$iso = $this->context->language->iso_code;
		$isoTinyMCE = (file_exists(_PS_ROOT_DIR_.'/js/tiny_mce/langs/'.$this->context->language->iso_code.'.js') ? $this->context->language->iso_code : 'en');
		$ad = dirname($_SERVER["PHP_SELF"]);
		
        $this->context->smarty->assign(array(
		    'ad' => $ad,
            'isoTinyMCE' => $isoTinyMCE,
			'base_dir' => _PS_BASE_URL_.__PS_BASE_URI__,
			'base_dir_ssl' => _PS_BASE_URL_SSL_.__PS_BASE_URI__,
            'theme_css_dir' => _THEME_CSS_DIR_
            ));
        $this->generalinfo();

        $id_customer = (int)Tools::getValue('id_customer');		if (!$id_customer && Validate::isLoadedObject($this->object))
			$id_customer = $this->object->id_customer;
		if ($id_customer>0)
		{
			$customer = new Customer((int)$id_customer);
			$token_customer = Tools::getAdminToken('AdminCustomers'.(int)(Tab::getIdFromClassName('AdminCustomers')).(int)$this->context->cookie->id_employee);
		}

		$id_seller = (int)Tools::getValue('id_seller');		if (!$id_seller && Validate::isLoadedObject($this->object))
			$id_seller = $this->object->id_seller;
		if ($id_seller>0)
		{
			$seller_employee = new Employee((int)$id_seller);
			if(Validate::isLoadedObject($seller_employee))
				$token_employee = Tools::getAdminToken('AdminEmployees'.(int)(Tab::getIdFromClassName('AdminEmployees')).(int)$this->context->cookie->id_employee);
			else unset($seller_employee);
		}

			if (Configuration::get('VATNUMBER_MANAGEMENT') && file_exists(_PS_MODULE_DIR_.'vatnumber/vatnumber.php'))
			include_once(_PS_MODULE_DIR_.'vatnumber/vatnumber.php');
		if (Configuration::get('VATNUMBER_MANAGEMENT'))
			if (file_exists(_PS_MODULE_DIR_.'vatnumber/vatnumber.php') && VatNumber::isApplicable(Configuration::get('PS_COUNTRY_DEFAULT')))
				$vat = 'is_applicable';
			else
				$vat = 'management';

								
		$this->tpl_form_vars = array(
			'vat' => isset($vat) ? $vat : null,
			'customer' => isset($customer) ? $customer : null,
			'seller_employee' => isset($seller_employee) ? $seller_employee : null,
			'agilemultipleseller_views' => _PS_ROOT_DIR_  . "/modules/agilemultipleseller/views/",
			'base_dir' => _PS_BASE_URL_.__PS_BASE_URI__,
			'base_dir_ssl' => _PS_BASE_URL_SSL_.__PS_BASE_URI__,
			'tokenCustomer' => isset ($token_customer) ? $token_customer : null,
			'tokenEmployee' => isset ($token_employee) ? $token_employee : null,
												'sellerinfo_obj' => $obj
		);
				$this->fields_form = array_values($this->fields_form);
		$this->multiple_fieldsets = true;

		return parent::renderForm();
	}

	public function init(){
		parent::init();
	}
		
	public function processSave(){
		$this->prepareFields();
										if(isset($_POST['id']) and $_POST['id']){
			$this->object = $this->loadObject();
		}else{
			$this->object = new SellerEvent();
			$this->object->create_date = date("Y-m-d");
		}
		if(empty($this->object->id_seller)){
			$this->object->id_seller = 0;
		}
				$this->errors = array_merge($this->errors, $this->object->validateController());
										if(empty($this->errors))
			$this->object->save();
		
		if(!empty($this->errors))
		{
			$this->redirect_after = false;
			return false;
		}
	}	

	private function prepareFields(){
		$fields = array('title','description');
		$languages = Language::getLanguages(false);
		foreach ($fields as $name) {
			# code...
			$first = '';			
			foreach ($languages as $key => $value) {
				# code...
				if(isset($_POST[$name.'_'.$value['id_lang']])){
					if(!empty($_POST[$name.'_'.$value['id_lang']])){
						$first = $_POST[$name.'_'.$value['id_lang']];
						break;
					}
				}
			}
			if(!empty($first)){
				$_POST[$name] = $first;
				foreach ($languages as $key => $l) {
					# code...
					if(isset($_POST[$name.'_'.$l['id_lang']]) and empty($_POST[$name.'_'.$l['id_lang']])){
						$_POST[$name.'_'.$l['id_lang']] = $first;
					}
				}
			}
		}
	}


	private function _debug($var,$exit = false){
		echo "<pre>";
		var_dump($var);
		echo "</pre>";
		if($exit) exit;
	}
}