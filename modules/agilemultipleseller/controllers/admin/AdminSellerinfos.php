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

class AdminSellerinfosController extends ModuleAdminController
{
	protected $position_identifier = 'id_sellerinfo';
	public $table = 'sellerinfo';
	private $custom_labels;
	private $custom_hints;
	
	protected $link;

		public $needGoogleMap = true;

	public function __construct()
	{
		$this->php_self  = "adminsellerinfos";
		
		$this->table = 'sellerinfo';
		$this->identifier = 'id_sellerinfo';
		$this->className = 'SellerInfo';
		$this->lang = false;
		$this->bulk_actions = array();
		$this->bootstrap = true;

		parent::__construct();

		$module = new AgileMultipleSeller();
		$this->custom_labels = $module->getCustomLabels(':');
		$this->custom_hints = $module->getCustomHints();
		
				$this->addRowAction('');
		
		
		$this->fields_list = array(
			'id_sellerinfo' => array(
					'title' => $this->l('ID(Info)'),
				'align' => 'center',
				'width' => 60,
				'filter_key' => 'a!id_sellerinfo'
			),
			'id_seller' => array(
				'title' => $this->l('Seller ID'),
				'align' => 'center',
				'width' => 60,
				'filter_key' => 'a!id_seller',
				'filter_type' => 'int'
			),
				
			'shop' => array(
				'title' => $this->l('Shop'),
				'align' => 'center',
				'width' => 70,
				'filter_key' => 's!name'
			),

			'company' => array(
				'title' => $this->l('Company'),
				'align' => 'center',
				'width' => 70,
				'filter_key' => 'a!company'
			),
			'firstname' => array(
				'title' => $this->l('First Name'),
				'align' => 'center',
				'width' => 70,
				'filter_key' => 'c!firstname'
			),
			'lastname' => array(
				'title' => $this->l('Last Name'),
				'align' => 'center',
				'width' => 70,
				'filter_key' => 'c!lastname'
			),
			'email' => array(
				'title' => $this->l('Email Address'),
				'align' => 'center',
				'width' => 150,
				'filter_key' => 'c!email'
			),
			'address1' => array(
				'title' => $this->l('Address1'),
				'align' => 'center',
				'width' => 150,
				'filter_key' => 'sl!address1'
			),
			'city' => array(
				'title' => $this->l('City'),
				'align' => 'center',
				'width' => 70,
				'filter_key' => 'sl!city'
			)
		);


		
		$this->_join = '
			LEFT JOIN `'._DB_PREFIX_.'customer` c ON (a.`id_customer` = c.`id_customer`)
			LEFT JOIN `'._DB_PREFIX_.'shop` s ON (a.`id_shop` = s.`id_shop`)
			LEFT JOIN `'._DB_PREFIX_.'sellerinfo_lang` sl ON (a.`id_sellerinfo` = sl.`id_sellerinfo` AND sl.id_lang=' . $this->context->language->id . ')
            ';

		if($this->is_seller)
		{
			$this->_where = ' AND a.id_seller = ' . $this->context->cookie->id_employee;
		}


		$this->_select = 'sl.company,sl.city,sl.address1,sl.address2,c.firstname, c.lastname, c.email,s.name AS shop';
	}
	
	public function viewAccess($disable = false)
	{
		if(!Module::isInstalled('agilemultipleseller'))return parent::viewAccess($disable);
		
		if($this->is_seller)
		{
			$seller_backoffice_access = (int)Configuration::get('AGILE_MS_SELLER_BACK_OFFICE');
			if(!$seller_backoffice_access)return false;
			$id_sellerinfo = SellerInfo::getIdBSellerId($this->context->cookie->id_employee);
			$editing_id = Tools::getValue('id_sellerinfo');
			if($editing_id > 0 && $id_sellerinfo != $editing_id)return false;
			return true;
		}
		
		return parent::viewAccess($disable);
	}

	
	
	public function init()
	{
				if (Tools::getValue('submitAdd' . $this->table))
			$_POST['submitAdd' . $this->table .'AndStay'] = 1;
		
		parent::init();
	}
	
	public function setMedia($isNewTheme = false)
	{
		parent::setMedia();

		$this->context->controller->addCSS(_PS_ROOT_DIR_ .'/modules/agilemultipleseller/css/agileglobal.css', 'all');
		$this->context->controller->addCSS(_PS_ROOT_DIR_ .'/modules/agilemultipleseller/css/agilemultipleseller.css', 'all');

		$deflang = new Language($this->context->language->id);
		$isocode = (file_exists(_PS_JS_DIR_.'jquery/ui/jquery.ui.datepicker-'.$deflang->iso_code.'.js') ? $deflang->iso_code : 'en');

		Context::getContext()->controller->addJs("https://maps.googleapis.com/maps/api/js?key=" . Configuration::get('AK_GMAP_APIKEY'));			
		Context::getContext()->controller->addJs(__PS_BASE_URI__ . "modules/agilemultipleseller/js/sellerbusiness.js");			

		$this->addJS(array(
			_PS_JS_DIR_.'tiny_mce/tinymce.min.js',
			));

		$this->addJS(array(
			_PS_JS_DIR_.'admin/tinymce.inc.js',
			));
	}
	
	
	public function initToolbar()
	{
		parent::initToolbar();
				unset($this->toolbar_btn['new']);
	}
	
	public function processSave()
	{
		$country = new Country((int)Tools::getValue('id_country'));
		if ($country->need_zip_code && Tools::getValue('submitAdd' . $this->table))
		{
			if(empty($_POST['postcode']))
			{
				$this->errors[] = Tools::displayError('Postcode is required field.');
				return false;
			}
		}
		
		AgileMultipleSeller::ensure_date_custom_field();		
		$this->object = $this->loadObject();
		$this->errors = array_merge($this->errors, $this->object->validateController());
		$this->object->save();

		Hook::exec('CategoryUpdate', array()); 		
		if(defined('SellerInfo::MAX_LOGO_FILE_SIZE_IN_BYTES'))
		{
			if(filesize($_FILES['logo']['tmp_name']) > SellerInfo::MAX_LOGO_FILE_SIZE_IN_BYTES)
			{
				$this->errors[] = Tools::displayError('Logo file size is too bigger, maximum logo gile size (bytes) is: ') . SellerInfo::MAX_LOGO_FILE_SIZE_IN_BYTES ;
			}
			if(empty($this->errors))
			{
				SellerInfo::processLogoUpload($this->object);	
			}
		}


		if(defined('SellerInfo::MAX_BANNER_FILE_SIZE_IN_BYTES'))
		{
			if(filesize($_FILES['banner']['tmp_name']) > SellerInfo::MAX_BANNER_FILE_SIZE_IN_BYTES)
			{
				$this->errors[] = Tools::displayError('Banner file size is too bigger, maximum banner gile size (bytes) is: ') . SellerInfo::MAX_BANNER_FILE_SIZE_IN_BYTES ;
			}
			if(empty($this->errors))
			{
				SellerInfo::processBannerUpload($this->object);	
			}
		}

				$country = new Country((int)$_POST['id_country']);
		if(Validate::isLoadedObject($country) && !$country->contains_states)$_POST['id_state']= 0 ;
		
		if (Tools::isSubmit('submitAddsellerinfo'))
		{
			if(!isset($_POST['email']) OR empty($_POST['email']))
				$this->errors[] = Tools::displayError('Front store account email is required.');

			if(!isset($_POST['seller_employee_email']) OR empty($_POST['seller_employee_email']))
				$this->errors[] = Tools::displayError('Back office account email is required.');
		}
		if(!empty($this->errors))
		{
			$this->redirect_after = false;
			return false;
		}
	}	
	
	
	protected function afterAdd($object)
	{
		$this->processLogoBannerImage();
		return empty($this->errors);
	}

	protected function afterUpdate($object)
	{
		$this->processLogoBannerImage();
		return empty($this->errors);
	}

	private function processLogoBannerImage()
	{
		if(defined('SellerInfo::MAX_LOGO_FILE_SIZE_IN_BYTES'))
		{
			if(filesize($_FILES['logo']['tmp_name']) > SellerInfo::MAX_LOGO_FILE_SIZE_IN_BYTES)
			{
				$this->errors[] = Tools::displayError('Logo file size is too bigger, maximum logo gile size (bytes) is: ') . SellerInfo::MAX_LOGO_FILE_SIZE_IN_BYTES ;
			}
		}

		SellerInfo::processLogoUpload($object);

		if(defined('SellerInfo::MAX_BANNER_FILE_SIZE_IN_BYTES'))
		{
			if(filesize($_FILES['banner']['tmp_name']) > SellerInfo::MAX_BANNER_FILE_SIZE_IN_BYTES)
			{
				$this->errors[] = Tools::displayError('Banner file size is too bigger, maximum banner gile size (bytes) is: ') . SellerInfo::MAX_BANNER_FILE_SIZE_IN_BYTES ;
			}
		}

		SellerInfo::processBannerUpload($object);

	}
	
	
	public function initContent()
	{
				if ($this->action == 'select_delete')
			$this->context->smarty->assign(array(
				'delete_form' => true,
				'url_delete' => htmlentities($_SERVER['REQUEST_URI']),
				'boxes' => $this->boxes,
				));

				if(Module::isInstalled('agilemultipleseller'))
		{
			include_once(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/agilemultipleseller.php");
			$module = new AgileMultipleSeller();
			$this->displayWarning($module->getL('How To Create Seller Hint'));	
		}
		parent::initContent();
	}
		
	public function renderForm()
	{
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
	
		$linktoshop = '';
		if(Module::isInstalled('agilemultipleshop'))
		{
			$token_shop = Tools::getAdminToken('AdminShopUrl'.(int)(Tab::getIdFromClassName('AdminShopUrl')).(int)$this->context->cookie->id_employee);
			$linktoshop='./index.php?controller=AdminShopUrl&token=' . $token_shop . '&id_shop=' . $this->object->id_shop;
		}
		$this->fields_form = array(
			'legend' => array(
					'title' => $this->l('Seller Info')
					),
			'input' => array(
					array(
						'type' => 'text_customer',
						'label' => $this->l('Front Store Account:'),
						'name' => 'id_customer',
						'size' => 33,
						'required' => false,
						),

					array(
						'type' => 'text_seller_employee',
						'label' => $this->l('Back office Account:'),
						'name' => 'id_seller',
						'size' => 33,
						'required' => false
						),


					array(
						'type' => 'select',
						'label' => $this->l('Seller Home Category:'),
						'name' => 'id_category_default',
						'required' => false,
						'default_value' => 0,
						'options' => array(
							'query' => $this->getAllCategories(),
							'id' => 'id_category',
							'name' => 'name',
							)
						),
					
					array(
						'type' => 'select',
						'label' => $this->l('Shop:'),
						'name' => 'id_shop',
						'required' => false,
						'default_value' => 0,
						'disabled' => !Module::isInstalled('agilemultipleshop'),
						'options' => array(
							'query' => $this->getAllShops(false),
							'id' => 'id_shop',
							'name' => 'name',
							),
						'desc'=> $linktoshop == '' ? '': '<a href="' .  $linktoshop .'">' . $this->l('Click here to go shop managenment page') . '</a>'
					),
					
					array(
						'type' => 'text',
						'label' => $this->l('Company:'),
						'name' => 'company',
						'size' => 33,
						'lang' => true,
						'required' => true,
						'hint' => $this->l('Forbidden characters:').' 0-9!<>,;?=+()@#"?{}_$%:'
						),
					
					array(
						'type' => 'select',
						'label' => $this->l('Payment Collection:'),
						'name' => 'payment_collection',
						'options' => array(
							'query' => array(array('id' => 0, 'name' => $this->l('Based on the module configuration')), array('id' =>1, 'name' => $this->l('Always use Store Collects Payment'))),
							'id' => 'id',
							'name' => 'name',
							),
						),
					));
		
																				
																
																		
		$this->fields_form['input'][] = array(
						'type' => 'select',
						'label' => $this->l('Primary Type'),
						'name' => 'id_sellertype1',
						'required' => false,
						'options' => array(
							'query' =>  SellerType::getSellerTypes($this->context->language->id, $this->l('Please choose seller type')),
							'id' => 'id_sellertype',
							'name' => 'name',
							)
						);
					
		$this->fields_form['input'][] = array(
						'type' => 'select',
						'label' => $this->l('Secondary Type'),
						'name' => 'id_sellertype2',
						'required' => false,
						'options' => array(
							'query' => SellerType::getSellerTypes($this->context->language->id, $this->l('Please choose seller type')),
							'id' => 'id_sellertype',
							'name' => 'name',
							)
						);
					
		$this->fields_form['input'][] =	array(
						'type' => 'file',
						'label' => $this->l('Logo:'),
						'name' => 'logo',
						'display_image' => true,
						'col' => 6,
						'desc' => $this->l('Upload seller logo from your computer')
					);

		$this->fields_form['input'][] =	array(
						'type' => 'file',
						'label' => $this->l('Banner:'),
						'name' => 'banner',
						'display_image' => true,
						'col' => 6,
						'desc' => $this->l('Upload seller banner from your computer')
					);
				
		$this->fields_form['input'][] =	array(
						'type'  => 'textarea',
						'label' => $this->l('Description:'),
						'name' => 'description',
						'lang' => true,
						'autoload_rte' => true,
						'rows' => 10,
						'cols' => 100,
						'hint' => $this->l('Invalid characters:').' <>;=#{}'
						);
					
		$this->fields_form['input'][] =		array(
						'type' => 'text',
						'label' => $this->l('Address Line1:'),
						'name' => 'address1',
						'lang' => true,
						'size' => 33,
						'required' => true,
						'hint' => $this->l('Forbidden characters:').' 0-9!<>,;?=+()@#"?{}_$%:'
						);
					
		$this->fields_form['input'][] =		array(
						'type' => 'text',
						'label' => $this->l('Address Line2:'),
						'name' => 'address2',
						'lang' => true,
						'size' => 33,
						'required' => false,
						'hint' => $this->l('Invalid characters:').' 0-9!<>,;?=+()@#"?{}_$%:'
						);
				
		$this->fields_form['input'][] =		array(
						'type' => 'text',
						'label' => $this->l('City:'),
						'name' => 'city',
						'lang' => true,
						'size' => 33,
						'required' => true,
						'hint' => $this->l('Invalid characters:').' 0-9!<>,;?=+()@#"?{}_$%:'
						);
		$this->fields_form['input'][] =		array(
						'type' => 'text',
						'label' => $this->l('Postal Code:'),
						'name' => 'postcode',
						'size' => 33,
						'required' => true
						);
		$this->fields_form['input'][] =			array(
						'type' => 'select',
						'label' => $this->l('State'),
						'name' => 'id_state',
						'required' => false,
						'options' => array(
							'query' => array(),
							'id' => 'id_state',
							'name' => 'name',
							)
						);
		$this->fields_form['input'][] =		array(
						'type' => 'select',
						'label' => $this->l('Country:'),
						'name' => 'id_country',
						'required' => true,
						'default_value' => (int)$this->context->country->id,
						'options' => array(
							'query' => Country::getCountries($this->context->language->id, true),
							'id' => 'id_country',
							'name' => 'name',
							)
						);
		$this->fields_form['input'][] =	array(
						'type' => 'text',
						'label' => $this->l('Phone:'),
						'name' => 'phone',
						'size' => 33,
						'required' => false
						);
		$this->fields_form['input'][] =	array(
						'type' => 'text',
						'label' => $this->l('Fax:'),
						'name' => 'fax',
						'size' => 33,
						'required' => false
						);

		if(Module::isInstalled('agilezipcodefilter'))
		{
			
			$filter_mode = (int)Configuration::get('AGILE_ZIPCODE_FILTER_MODE');

			if($filter_mode == 2)
				$this->fields_form['input'][] =	array(
					'type' => 'text',
					'label' => $this->l('Service Area Restrictions:'),
					'name' => 'service_zipcodes',
					'size' => 100,
					'required' => false,
					'desc'=> $this->l('Please enter Post code list separated by comma for example: 1232,3231, or leave it empty if there is no restrictions') . '</a>'
					);
			else
				$this->fields_form['input'][] =	array(
					'type' => 'text',
					'label' => $this->l('Maximum Service Distance:'),
					'name' => 'service_distance',
					'size' => 100,
					'required' => false,
					'desc'=> $this->l('Please enter maximum distance(KM) that you will serve customers') . '</a>'
					);
			
		}
		
		$this->fields_form['input'][] =	array(
						'type' => 'text',
						'label' => $this->l('Identification:'),
						'name' => 'dni',
						'size' => 33,
						'required' => false
						);
		$this->fields_form['input'][] =	array(
						'type' => 'text',
						'label' => $this->l('Latitude:'),
						'name' => 'latitude',
						'size' => 33,
						'required' => false
						);
		$this->fields_form['input'][] =	array(
						'type' => 'text',
						'label' => $this->l('Longitude:'),
						'name' => 'longitude',
						'size' => 33,
						'required' => false
						);

		$conf = Configuration::getMultiple(array('AGILE_MS_SELLER_TEXT1','AGILE_MS_SELLER_TEXT2','AGILE_MS_SELLER_TEXT3','AGILE_MS_SELLER_TEXT4','AGILE_MS_SELLER_TEXT5','AGILE_MS_SELLER_TEXT6','AGILE_MS_SELLER_TEXT7','AGILE_MS_SELLER_TEXT8','AGILE_MS_SELLER_TEXT9','AGILE_MS_SELLER_TEXT10','AGILE_MS_SELLER_HTML1','AGILE_MS_SELLER_HTML2','AGILE_MS_SELLER_NUMBER1','AGILE_MS_SELLER_NUMBER2','AGILE_MS_SELLER_NUMBER3','AGILE_MS_SELLER_NUMBER4','AGILE_MS_SELLER_NUMBER5','AGILE_MS_SELLER_DATE1','AGILE_MS_SELLER_DATE2','AGILE_MS_SELLER_DATE3','AGILE_MS_SELLER_DATE4','AGILE_MS_SELLER_DATE5','AGILE_MS_SELLER_STRING1','AGILE_MS_SELLER_STRING2','AGILE_MS_SELLER_STRING3','AGILE_MS_SELLER_STRING4','AGILE_MS_SELLER_STRING5','AGILE_MS_SELLER_STRING6','AGILE_MS_SELLER_STRING7','AGILE_MS_SELLER_STRING8','AGILE_MS_SELLER_STRING9','AGILE_MS_SELLER_STRING10','AGILE_MS_SELLER_STRING11','AGILE_MS_SELLER_STRING12','AGILE_MS_SELLER_STRING13','AGILE_MS_SELLER_STRING14','AGILE_MS_SELLER_STRING15'));

		for($idx=1;$idx<=10;$idx++)
		{
			if (isset($conf['AGILE_MS_SELLER_TEXT' .$idx]) AND $conf['AGILE_MS_SELLER_TEXT' . $idx])
			{
				$this->fields_form['input'][] = array(
					'type' => 'textarea',
					'label' => $this->custom_labels['ams_custom_text' . $idx],
					'name' => 'ams_custom_text' . $idx,
					'lang' => true,
					'autoload_rte' => false,
					'rows' => 10,
					'cols' => 100,
					'required' => false,
					'hint' => $this->custom_hints['ams_custom_text' . $idx]
					);
			}
		}
		
		for($idx=1;$idx<=2;$idx++)
		{
			if (isset($conf['AGILE_MS_SELLER_HTML' .$idx]) AND $conf['AGILE_MS_SELLER_HTML' . $idx])
			{
				$this->fields_form['input'][] = array(
					'type' => 'textarea',
					'label' => $this->custom_labels['ams_custom_html' . $idx],
					'name' => 'ams_custom_html' . $idx,
					'lang' => true,
					'autoload_rte' => true,
					'rows' => 10,
					'cols' => 100,
					'required' => false,
					'hint' => $this->custom_hints['ams_custom_html' . $idx]
				);
			}
		}		
		
		for($idx=1;$idx<=10;$idx++)
		{
			if (isset($conf['AGILE_MS_SELLER_NUMBER' .$idx]) AND $conf['AGILE_MS_SELLER_NUMBER' .$idx])
			{
				$this->fields_form['input'][] = array(
					'type' => 'text',
					'label' => $this->custom_labels['ams_custom_number' . $idx],
					'name' => 'ams_custom_number' .$idx,
					'hint' => $this->custom_hints['ams_custom_number' . $idx]
				);
			}
		}

		
		for($idx=1;$idx<=5;$idx++)
		{
			if (isset($conf['AGILE_MS_SELLER_DATE' .$idx]) AND $conf['AGILE_MS_SELLER_DATE' . $idx])
			{
				$this->fields_form['input'][] = array(
					'type' => 'date',
					'label' => $this->custom_labels['ams_custom_date' . $idx],
					'name' => 'ams_custom_date' .$idx,
					'size' => 30,
					'hint' => $this->custom_hints['ams_custom_date' . $idx]
				);
			}
		}

		for($idx=1;$idx<=15;$idx++)
		{
			
			if (isset($conf['AGILE_MS_SELLER_STRING' .$idx]) AND $conf['AGILE_MS_SELLER_STRING' . $idx])
			{
				$this->fields_form['input'][] = array(
					'type' => 'text',
					'label' => $this->custom_labels['ams_custom_string' . $idx],
					'name' => 'ams_custom_string' .$idx,
					'lang' => false,
					'size' => 100,
					'hint' => $this->custom_hints['ams_custom_string' . $idx]
					);
			}
		}
		
		$this->fields_form['input'][] = array(
			'type' => 'google_map',
			'label' => $this->l('Map:'),
			'name' => 'google_map',
			'required' => false
			);

		
		$this->fields_form['submit'] = array(
			'title' => $this->l('Save'),
			'class' => 'btn btn-default pull-right'
			);
		
		
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

		$logo_image = SellerInfo::get_seller_logo_url_static(Tools::getValue('id_sellerinfo'));
		$banner_image = SellerInfo::get_seller_banner_url_static(Tools::getValue('id_sellerinfo'));
		
		$this->tpl_form_vars = array(
			'vat' => isset($vat) ? $vat : null,
			'customer' => isset($customer) ? $customer : null,
			'seller_employee' => isset($seller_employee) ? $seller_employee : null,
			'agilemultipleseller_views' => _PS_ROOT_DIR_  . "/modules/agilemultipleseller/views/",
			'base_dir' => _PS_BASE_URL_.__PS_BASE_URI__,
			'base_dir_ssl' => _PS_BASE_URL_SSL_.__PS_BASE_URI__,
			'tokenCustomer' => isset ($token_customer) ? $token_customer : null,
			'tokenEmployee' => isset ($token_employee) ? $token_employee : null,
			'logo_image_url' =>  ($logo_image ? $logo_image : false),
			'banner_image_url' =>  ($banner_image ? $banner_image : false),
			'sellerinfo_obj' => $obj
		);
		
		return parent::renderForm();
	}
	
	private function getAllCategories()
	{
		$categories = Category::getCategories($this->context->cookie->id_lang, true, false);
		$categories = AgileHelper::getSortedFullnameCategory($categories);
		if(!in_array(2, AgileHelper::retrieve_column_values($categories,"id_category",false)))$categories = array_merge(array(array("id_category"=>2, "name"=>"/Home")), $categories);
		return $categories;
		
	}
	
	
	private function getAllShops($activeonly = true)
	{
		$shops = Shop::getShops(false);
		return $shops;
	}
}
