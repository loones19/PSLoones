<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
if (version_compare(phpversion(), '5.6.1', '>='))require_once(dirname(__FILE__) . "/validator56higher.php");
else require_once(dirname(__FILE__) . "/validator56lower.php");

class	SellerInfo extends ObjectModel
{
	const		MAX_LOGO_FILE_SIZE_IN_BYTES = 4096000;
	const		MAX_BANNER_FILE_SIZE_IN_BYTES = 4096000;

	public		$id_sellerinfo;
	public		$id_seller; 
	public      $id_shop;
	public      $id_category_default;
    public      $company;
	
	public		$id_sellertype1;
	public		$id_sellertype2;
	public		$id_country;
	public		$country;
	public      $id_customer;
	public      $id_manufacturer;
	public      $id_supplier;
	public      $theme_name;
	public      $payment_collection;

	public		$id_state;
	public      $state;
	public		$service_zipcodes;
	public		$service_distance;

		public 		$address1;

		public 		$address2;

		public 		$postcode;

		public 		$city;

		public 		$description;

				
		public 		$phone;

		public 		$fax;
	
	public 		$dni;

    public $latitude;
    public $longitude;

		public 		$date_add;

		public 		$date_upd;
	
	public      $customer_info_text;

	public      $ams_custom_text1;
	public      $ams_custom_text2;
	public      $ams_custom_text3;
	public      $ams_custom_text4;
	public      $ams_custom_text5;
	public      $ams_custom_text6;
	public      $ams_custom_text7;
	public      $ams_custom_text8;
	public      $ams_custom_text9;
	public      $ams_custom_text10;
	
	public      $ams_custom_html1;
	public      $ams_custom_html2;

	public      $ams_custom_number1;
	public      $ams_custom_number2;
	public      $ams_custom_number3;
	public      $ams_custom_number4;
	public      $ams_custom_number5;
	public      $ams_custom_number6;
	public      $ams_custom_number7;
	public      $ams_custom_number8;
	public      $ams_custom_number9;
	public      $ams_custom_number10;

	public      $ams_custom_date1;
	public      $ams_custom_date2;
	public      $ams_custom_date3;
	public      $ams_custom_date4;
	public      $ams_custom_date5;

	public      $ams_custom_string1;
	public      $ams_custom_string2;
	public      $ams_custom_string3;
	public      $ams_custom_string4;
	public      $ams_custom_string5;
	public      $ams_custom_string6;
	public      $ams_custom_string7;
	public      $ams_custom_string8;
	public      $ams_custom_string9;
	public      $ams_custom_string10;
	public      $ams_custom_string11;
	public      $ams_custom_string12;
	public      $ams_custom_string13;
	public      $ams_custom_string14;
	public      $ams_custom_string15;


	private static $_idZones = array();
	private static $_idCountries = array();

	public static $definition = array(
		'table' => 'sellerinfo',
		'primary' => 'id_sellerinfo',
		'multilang' => true,
		'multilang_shop' => false,
		'fields' => array(
			'id_seller' => 			array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
			'id_country' => 		array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
			'id_state' => 		    array('type' => self::TYPE_INT, 'validate' => 'isNullOrUnsignedId'),
			'postcode' => 			array('type' => self::TYPE_STRING, 'validate' => 'isPostCode', 'size' => 12),
			'phone' =>	  			array('type' => self::TYPE_STRING, 'validate' => 'isPhoneNumber', 'size' => 16),
			'fax' =>	  			array('type' => self::TYPE_STRING, 'validate' => 'isPhoneNumber', 'size' => 16),
			'latitude' => 			array('type' => self::TYPE_FLOAT, 'validate' => 'isString'),
			'longitude' =>	 		array('type' => self::TYPE_FLOAT, 'validate' => 'isString'),
			'date_add' => 			array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
			'date_upd' => 			array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
			'id_customer' => 		array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
			'id_manufacturer' =>	array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
			'id_supplier' => 		array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
			'dni' => 				array('type' => self::TYPE_STRING, 'validate' => 'isString', 'size' => 128),
			'id_shop' => 			array('type' => self::TYPE_INT),
			'theme_name' => 		array('type' => self::TYPE_STRING),
			'payment_collection' => 		array('type' => self::TYPE_INT),
			'service_zipcodes' =>	array('type' => self::TYPE_STRING, 'validate' => 'isString'),
			'service_distance' =>	array('type' => self::TYPE_FLOAT, 'validate' => 'isString'),
			'id_category_default' =>array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
			'id_sellertype1' => 	array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
			'id_sellertype2' => 	array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
			'ams_custom_number1' => array('type' => self::TYPE_FLOAT, 'validate' => 'isString'),
			'ams_custom_number2' => array('type' => self::TYPE_FLOAT, 'validate' => 'isString'),
			'ams_custom_number3' => array('type' => self::TYPE_FLOAT, 'validate' => 'isString'),
			'ams_custom_number4' => array('type' => self::TYPE_FLOAT, 'validate' => 'isString'),
			'ams_custom_number5' => array('type' => self::TYPE_FLOAT, 'validate' => 'isString'),
			'ams_custom_number6' => array('type' => self::TYPE_FLOAT, 'validate' => 'isString'),
			'ams_custom_number7' => array('type' => self::TYPE_FLOAT, 'validate' => 'isString'),
			'ams_custom_number8' => array('type' => self::TYPE_FLOAT, 'validate' => 'isString'),
			'ams_custom_number9' => array('type' => self::TYPE_FLOAT, 'validate' => 'isString'),
			'ams_custom_number10' =>array('type' => self::TYPE_FLOAT, 'validate' => 'isString'),
			'ams_custom_date1' => 	array('type' => self::TYPE_DATE, 'validate' => 'isString'),
			'ams_custom_date2' => 	array('type' => self::TYPE_DATE, 'validate' => 'isString'),
			'ams_custom_date3' => 	array('type' => self::TYPE_DATE, 'validate' => 'isString'),
			'ams_custom_date4' => 	array('type' => self::TYPE_DATE, 'validate' => 'isString'),
			'ams_custom_date5' => 	array('type' => self::TYPE_DATE, 'validate' => 'isString'),
			'ams_custom_string1' =>array('type' => self::TYPE_STRING, 'validate' => 'isString'),
			'ams_custom_string2' =>array('type' => self::TYPE_STRING, 'validate' => 'isString'),
			'ams_custom_string3' =>array('type' => self::TYPE_STRING, 'validate' => 'isString'),
			'ams_custom_string4' =>array('type' => self::TYPE_STRING, 'validate' => 'isString'),
			'ams_custom_string5' =>array('type' => self::TYPE_STRING, 'validate' => 'isString'),
			'ams_custom_string6' =>array('type' => self::TYPE_STRING, 'validate' => 'isString'),
			'ams_custom_string7' =>array('type' => self::TYPE_STRING, 'validate' => 'isString'),
			'ams_custom_string8' =>array('type' => self::TYPE_STRING, 'validate' => 'isString'),
			'ams_custom_string9' =>array('type' => self::TYPE_STRING, 'validate' => 'isString'),
			'ams_custom_string10' =>array('type' => self::TYPE_STRING, 'validate' => 'isString'),
			'ams_custom_string11' =>array('type' => self::TYPE_STRING, 'validate' => 'isString'),
			'ams_custom_string12' =>array('type' => self::TYPE_STRING, 'validate' => 'isString'),
			'ams_custom_string13' =>array('type' => self::TYPE_STRING, 'validate' => 'isString'),
			'ams_custom_string14' =>array('type' => self::TYPE_STRING, 'validate' => 'isString'),
			'ams_custom_string15' =>array('type' => self::TYPE_STRING, 'validate' => 'isString'),
			
						'company' => 			array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isString', 'required' => true, 'size' => 256),
			'description' => 		array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 6000),
			'address1' => 			array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isAddress', 'size' => 128),
			'address2' => 			array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isAddress', 'size' => 128),
			'city' =>	  			array('type' => self::TYPE_STRING, 'lang' => true,'validate' => 'isCityName', 'size' => 64),
			
									
			'ams_custom_text1' =>	array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isString', 'size' => 4000),
			'ams_custom_text2' =>	array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isString', 'size' => 4000),
			'ams_custom_text3' =>	array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isString', 'size' => 4000),
			'ams_custom_text4' =>	array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isString', 'size' => 4000),
			'ams_custom_text5' =>	array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isString', 'size' => 4000),
			'ams_custom_text6' =>	array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isString', 'size' => 4000),
			'ams_custom_text7' =>	array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isString', 'size' => 4000),
			'ams_custom_text8' =>	array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isString', 'size' => 4000),
			'ams_custom_text9' =>	array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isString', 'size' => 4000),
			'ams_custom_text10' =>	array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isString', 'size' => 4000),
			'ams_custom_html1' =>	array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isString', 'size' => 8000),
			'ams_custom_html2' =>	array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isString', 'size' => 8000),
			),
		);
	
	protected	$_includeContainer = false;

	public	function __construct($id_sellerinfo = NULL, $id_lang = NULL)
	{
		parent::__construct($id_sellerinfo, $id_lang);
		
				if ($this->id)
		{
			$result = Db::getInstance()->getRow('SELECT `name` FROM `'._DB_PREFIX_.'country_lang`
												WHERE `id_country` = '.intval($this->id_country).'
												AND `id_lang` = '.($id_lang ? intval($id_lang) : Configuration::get('PS_LANG_DEFAULT')));
			$this->country = $result['name'];

            if( intval($this->id_state)>0)
            {
			    $result = Db::getInstance()->getRow('SELECT `name` FROM `'._DB_PREFIX_.'state`
												WHERE `id_state` = '.intval($this->id_state));
			    $this->state = $result['name'];
			}
            if( intval($this->id_customer)>0)
            {
                $customer = new Customer($this->id_customer);
                $this->customer_info_text = $customer->firstname . ' ' . $customer->lastname . '(' . $customer->email . ')';
			}
		}
	}
	
	public function fulladdress($id_lang)
	{        
		return AddressFormat::generateAddress(self::getAddressData($this->id, $id_lang));
	}

	public static function getAddressData($id_sellerinfo, $id_lang = null)
	{
		if(!$id_lang)$id_lang = intval(Configuration::get('PS_LANG_DEFAULT'));
		$sellerinfo = new SellerInfo($id_sellerinfo, $id_lang);
		$employee = new Employee($sellerinfo->id_seller);
		$address = new Address();
		$address->id_customer = 0;
		$address->id_manufacturer = 0;
		$address->id_supplier = 0;
		$address->id_warehouse =0;
		$address->id_country = $sellerinfo->id_country;
		$address->id_state = $sellerinfo->id_state;
		$address->company = $sellerinfo->company;
		$address->lastname = $employee->lastname;
		$address->firstname = $employee->firstname;
		$address->address1 = $sellerinfo->address1;
		$address->address2 = $sellerinfo->address2;
		$address->postcode = $sellerinfo->postcode;
		$address->city = $sellerinfo->city;
		$address->phone = $sellerinfo->phone;
		$address->phone_mobile = $sellerinfo->phone;
		$address->dni = $sellerinfo->dni;
		$address->date_add = $sellerinfo->date_add;
		$address->date_upd = $sellerinfo->date_upd;
		
		return $address;
	}

	
	public static function getIdBSellerId($id_seller)
	{
		$sql = 'SELECT id_sellerinfo FROM `'._DB_PREFIX_.'sellerinfo` WHERE id_seller=' . intval($id_seller);
		$res = Db::getInstance()->getRow($sql);
		if(isset($res['id_sellerinfo']) AND intval($res['id_sellerinfo'])>0)return intval($res['id_sellerinfo']);
		return 0;
	}
	
	public static function get_logo_folder()
	{
		$folder =  _PS_IMG_DIR_ . "as" .  DIRECTORY_SEPARATOR ;
		if (!file_exists($folder))mkdir($folder);
		return  $folder;
	}

	public static function getCustomFields()
	{
		$custom_fields = SellerInfo::getCustomMultiLanguageFields();
		for ($i = 1; $i <= 10; $i++) {
			$custom_fields[] = 'ams_custom_number'.$i;
		}
		for ($i = 1; $i <= 5; $i++) {
			$custom_fields[] = 'ams_custom_date'.$i;
		}
		for ($i = 1; $i <= 15; $i++) {
			$custom_fields[] = 'ams_custom_string'.$i;
		}
		return $custom_fields;
	}

	public static function getCustomMultiLanguageFields()
	{
		$custom_multi_lang_fields = array();
		for ($i = 1; $i <= 10; $i++) {
			$custom_multi_lang_fields[] = 'ams_custom_text'.$i;
		}
		for ($i = 1; $i <= 2; $i++) {
			$custom_multi_lang_fields[] = 'ams_custom_html'.$i;
		}

		return $custom_multi_lang_fields;
	}

		public static function getSellerLogoFilePath($id_order)
	{
		$id_seller = intval(AgileSellerManager::getObjectOwnerID('order',$id_order));
		$id_sellerinfo = self::getIdBSellerId($id_seller);
		$sellerinfo = new SellerInfo($id_sellerinfo);
		if(!Validate::isLoadedObject($sellerinfo))return '';
		return self::seller_logo_physical_path( $sellerinfo->id);
	}
	
	public static function seller_logo_physical_path($id_sellerinfo)
	{
		$logo = SellerInfo::get_logo_folder() . $id_sellerinfo . '.jpg';
		if(file_exists($logo))return $logo;
		
				return '';
	}

	public function get_seller_logo_url()
	{
		return self::get_seller_logo_url_static($this->id);
	}

		public static function get_seller_logo_url_static($id_sellerinfo)
	{
		$context = Context::getContext();
		
		$lang = new Language($context->language->id);
		$logofile = self::seller_logo_physical_path($id_sellerinfo);

		if(file_exists($logofile))
			return Tools::getShopDomainSsl(true) . __PS_BASE_URI__ . 'img/as/' . $id_sellerinfo . '.jpg?' . date("YmdHis",filemtime($logofile));
		else
			return Tools::getShopDomainSsl(true) . __PS_BASE_URI__ . 'modules/agilemultipleseller/images/' . $lang->iso_code. '-nologo.png';
	}
		
	public static function getIdByCustomerId($id_customer)
	{
	    $sql = 'SELECT id_sellerinfo FROM `'._DB_PREFIX_.'sellerinfo` WHERE id_customer=' . intval($id_customer);
	    $res = Db::getInstance()->getRow($sql);
	    if(isset($res['id_sellerinfo']) AND intval($res['id_sellerinfo'])>0)return intval($res['id_sellerinfo']);
	    return 0;
	}

	public static function getSellerIdByCustomerId($id_customer)
	{
		$sql = 'SELECT id_seller FROM `'._DB_PREFIX_.'sellerinfo` WHERE id_customer=' . intval($id_customer);
		$id_seller = (int)Db::getInstance()->getValue($sql);
		return $id_seller;
	}
	
	public static function processLogoUpload($sellerInfo)
	{		
		$logo_folder = SellerInfo::get_logo_folder();
		if(!Validate::isLoadedObject($sellerInfo))return false;
		if(!empty($_FILES['logo']['name']))
		{
			$pathinfo = pathinfo($_FILES['logo']['name']);
			if(!in_array($pathinfo['extension'], array('jpg','jpeg','png')))return false;

			$filename = $logo_folder . $sellerInfo->id . ".jpg";
			if(!move_uploaded_file($_FILES['logo']['tmp_name'], $filename)) return false;
	
			self::syncSellerManufacturerSupplierLogo($sellerInfo);
			
			return true;
		}
		return true;
	}

	public static function get_seller_email_by_order_id($id_order)
	{
		$sql = 'SELECT e.email FROM ' . _DB_PREFIX_ . 'order_owner oo INNER JOIN ' . _DB_PREFIX_ . 'employee e ON oo.id_owner = id_employee WHERE oo.id_order = ' . (int)$id_order;
		$email = Db::getInstance()->getValue($sql);
		return $email;		
	}
	
	public function update($null_values = false)
	{
		$this->service_zipcodes = $this->format_zipcode_list($this->service_zipcodes);
		$ret = parent::update($null_values);	
		self::syncSellerManufacturerSupplier($this);
		return $ret;
	}	
	
	public function add($autodate = true, $nullValues = false)
	{
		$this->service_zipcodes = $this->format_zipcode_list($this->service_zipcodes);
		$ret = parent::add($autodate, $nullValues);
		self::syncSellerManufacturerSupplier($this);
		return $ret;
	}
	
	private function format_zipcode_list($zipcodes)
	{
		$search  = array("\r\n", "\n", "\r", " ", ";", "\t");
		return str_replace($search, '', $zipcodes);
	}	
	
		
	public function validateController($htmlentities = true)
	{
		$errors = array();
		$languages = Language::getLanguages(false);
		$default_language = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$class_name = 'SellerInfo';
		$required_fields_database = (isset(self::$fieldsRequiredDatabase[get_class($this)])) ? self::$fieldsRequiredDatabase[get_class($this)] : array();
				foreach ($this->def['fields'] as $field => $data)
		{
			
						if (in_array($field, $required_fields_database))
			{
				$data['required'] = true;
			}
			
						if (isset($data['required']) && $data['required'] && ($value = Tools::getValue($field, $this->{$field})) == false && (string)$value != '0')
			{
				if (!$this->id || $field != 'passwd')
				{
					$errors[] = '<b>'.self::displayFieldName($field, get_class($this), $htmlentities).'</b> '.Tools::displayError('is required.');
				}
			}

						if (isset($data['size']) && ($value = Tools::getValue($field, $this->{$field})) && Tools::strlen($value) > $data['size'])
			{
				$errors[] = sprintf(
					Tools::displayError('%1$s is too long. Maximum length: %2$d'),
					self::displayFieldName($field, get_class($this), $htmlentities),
					$data['size']
					);
			}

												$value = Tools::getValue($field, $this->{$field});
			if (($value || $value =='0') || ($field == 'postcode' && $value == '0'))
			{
				if($field == 'company' || $field == 'description' || $field == 'address1' || $field == 'address2' || $field == 'city' || in_array($field, SellerInfo::getCustomMultiLanguageFields()))
				{
					if (($field == 'company' || $field == 'address1' || $field == 'city') && (($empty = Tools::getValue($field.'_'.$default_language->id)) === false || $empty !== '0' && empty($empty)))
					{
						$errors[] = sprintf(
							Tools::displayError('The field %1$s is required at least in %2$s.'),
							call_user_func(array($class_name, 'displayFieldName'), $field, $class_name),
							$default_language->name
							);
					}
					
										$field_lang_value_default = '';
					foreach ($languages as $language)
					{
						$field_lang_value_default = Tools::getValue($field.'_'.$language['id_lang']);
						if(!empty($field_lang_value_default))break;
					}
					
					foreach ($languages as $language)
					{
						$field_lang_value = Tools::getValue($field.'_'.$language['id_lang']);
						if ($field_lang_value !== false && Tools::strlen($field_lang_value) > $data['size'])
						{
							$errors[] = sprintf(
								Tools::displayError('The field %1$s (%2$s) is too long (%3$d chars max, html chars including).'),
								call_user_func(array($class_name, 'displayFieldName'), $field, $class_name),
								$language['name'],
								$data['size']
								);
						}
						if (isset($data['validate']) && !executeValidation($data['validate'],$field_lang_value) && !empty($field_lang_value))
						{
							$errors[] = sprintf(
								Tools::displayError('The field %1$s (%2$s) Is Invalid.'),
								call_user_func(array($class_name, 'displayFieldName'), $field, $class_name),
								$language['name']
								);
						}

						$this->{$field}[$language['id_lang']] = (empty($field_lang_value)? $field_lang_value_default : $field_lang_value);
					}
				}
				else
				{					
					if (isset($data['validate']) && !executeValidation($data['validate'], $value) && (!empty($value) || (isset($data['required']) && $data['required'])))
					{
						$errors[] = '<b>' . $data['validate'] . ' ' . self::displayFieldName($field, get_class($this), $htmlentities).'</b> '.Tools::displayError('is invalid.');
					}
					else
					{
						if (isset($data['copy_post']) && !$data['copy_post'])continue;
						$this->{$field} = $value;
					}
				}
			}
			else
			{
				if (isset($data['copy_post']) && !$data['copy_post'])continue;
				$this->{$field} = $value;
			}
		}
		return $errors;
	}
	
	public static function synchSellerManufacturer($sellerinfo, $active)
	{
		$id_deflang = (int)Configuration::get('PS_LANG_DEFAULT');
		$address = SellerInfo::getAddressData($sellerinfo->id, $id_deflang);
		
		$languages = Language::getLanguages(false);
				$manufacturer = new Manufacturer($sellerinfo->id_manufacturer);
		if(!Validate::isLoadedObject($manufacturer))
		{
			$sellerinfo->id_manufacturer = 0;
			$manufacturer->id = 0;
		}
		$manufacturer->name = $sellerinfo->company[$id_deflang];
		$manufacturer->active = $active;
		foreach($languages as $lang)
		{
			$manufacturer->description[$lang['id_lang']] = $sellerinfo->description[$lang['id_lang']];
			$manufacturer->short_description[$lang['id_lang']] = "";
			$manufacturer->meta_description[$lang['id_lang']] = "";
			$manufacturer->meta_keywords[$lang['id_lang']] = "";
			$manufacturer->meta_title[$lang['id_lang']] = "";
		}
		$manufacturer->save();

		$id_address = (int)Db::getInstance()->getValue("SELECT id_address FROM " . _DB_PREFIX_  . "address where id_manufacturer=" . (int)$manufacturer->id);
		if($id_address == 0)
		{
			$sql = "INSERT INTO " . _DB_PREFIX_ . "address (`id_country`, `id_state`, `id_customer`, `id_manufacturer`, `id_supplier`, `id_warehouse`, `alias`, `company`, `lastname`, `firstname`, `address1`, `address2`, `postcode`, `city`, `other`, `phone`, `phone_mobile`, `vat_number`, `dni`, `date_add`, `date_upd`, `active`, `deleted`) 
					VALUES (" . (int)$address->id_country. ",". (int)$address->id_state .",0," . (int)$manufacturer->id. ",0,0,'manufacturer','". $address->company ."','manufacturer','manufacturer','" . $address->address1 . "','" . $address->address2 . "','" . $address->postcode. "','" . $address->city . "','','" . $address->phone . "','" . $address->phone_mobile . "','','','" . date("Y-m-d H:i:s") . "','" . date("Y-m-d H:i:s"). "',1,0)";		
		}
		else
		{
			$sql = "UPDATE " . _DB_PREFIX_ . "address SET `id_country`=" . (int)$address->id_country. ", `id_state`=" . (int)$address->id_state . ", `id_manufacturer`=" . (int)$manufacturer->id. ", `company`='". $address->company ."', `address1`='" . $address->address1 . "', `address2`='" . $address->address2 . "', `postcode`='" . $address->postcode . "', `city`='" . $address->city . "', `phone`='" . $address->phone . "', `phone_mobile`='" . $address->phone_mobile . "', `dni`='" . $address->dni . "',`date_upd`='" . date("Y-m-d H:i:s") . "' WHERE id_address=" . (int)$id_address;
		}
		Db::getInstance()->Execute($sql);
		
		return $manufacturer;
	}	

	public static function synchSellerSupplier($sellerinfo, $active)
	{
		$id_deflang = (int)Configuration::get('PS_LANG_DEFAULT');
		$address = SellerInfo::getAddressData($sellerinfo->id, $id_deflang);
		$languages = Language::getLanguages(false);
		$supplier = new Supplier($sellerinfo->id_supplier);
				if(!Validate::isLoadedObject($supplier))
		{
			$sellerinfo->id_supplier = 0;
			$supplier->id = 0;
		}
		
		$supplier->name = $sellerinfo->company[$id_deflang];
		$supplier->active = $active;
		foreach($languages as $lang)
		{
			$supplier->description[$lang['id_lang']] = $sellerinfo->description[$lang['id_lang']];
			$supplier->meta_description[$lang['id_lang']] = "";
			$supplier->meta_keywords[$lang['id_lang']] = "";
			$supplier->meta_title[$lang['id_lang']] = "";
		}
		$supplier->save();
		
		$id_address = (int)Db::getInstance()->getValue("SELECT id_address FROM " . _DB_PREFIX_  . "address where id_supplier=" . (int)$supplier->id);
		if($id_address == 0)
		{
			$sql = "INSERT INTO " . _DB_PREFIX_ . "address (`id_country`, `id_state`, `id_customer`, `id_manufacturer`, `id_supplier`, `id_warehouse`, `alias`, `company`, `lastname`, `firstname`, `address1`, `address2`, `postcode`, `city`, `other`, `phone`, `phone_mobile`, `vat_number`, `dni`, `date_add`, `date_upd`, `active`, `deleted`) 
					VALUES (" . (int)$address->id_country. ",". (int)$address->id_state .",0,0," . (int)$supplier->id. ",0,'supplier','". $address->company ."','supplier','supplier','" . $address->address1 . "','" . $address->address2 . "','" . $address->postcode. "','" . $address->city . "','','" . $address->phone . "','" . $address->phone_mobile . "','','','" . date("Y-m-d H:i:s") . "','" . date("Y-m-d H:i:s"). "',1,0)";		
		}
		else
		{
			$sql = "UPDATE " . _DB_PREFIX_ . "address SET `id_country`=" . (int)$address->id_country. ", `id_state`=" . (int)$address->id_state . ", `id_supplier`=" . (int)$supplier->id. ", `company`='". $address->company ."', `address1`='" . $address->address1 . "', `address2`='" . $address->address2 . "', `postcode`='" . $address->postcode . "', `city`='" . $address->city . "', `phone`='" . $address->phone . "', `phone_mobile`='" . $address->phone_mobile . "', `dni`='" . $address->dni . "',`date_upd`='" . date("Y-m-d H:i:s") . "' WHERE id_address=" . (int)$id_address;
		}	
		Db::getInstance()->Execute($sql);

		return $supplier;
	}

	public static function syncSellerManufacturerSupplier($sellerinfo)
	{	
		if((int)Configuration::get('AGILE_MS_IS_MANUFACTURER') == 1 || (int)Configuration::get('AGILE_MS_IS_SUPPLIER') == 1)
		{
			$updateRequired = false;
			$employee = new Employee($sellerinfo->id_seller);
			if((int)Configuration::get('AGILE_MS_IS_MANUFACTURER') == 1)
			{
				$m = self::synchSellerManufacturer($sellerinfo, $employee->active);
				if((int)$sellerinfo->id_manufacturer ==0 AND $m->id >0)
				{
					$updateRequired = true;
					$sellerinfo->id_manufacturer = $m->id;
				}
			}
			
			if((int)Configuration::get('AGILE_MS_IS_SUPPLIER') == 1)
			{
				$s = self::synchSellerSupplier($sellerinfo, $employee->active);
				if((int)$sellerinfo->id_supplier == 0 && $s->id > 0)
				{
					$updateRequired = true;
					$sellerinfo->id_supplier = $s->id;
				}
			}
			if($updateRequired)$sellerinfo->save();
		}
	}
	
	public static function syncSellerManufacturerSupplierLogo($sellerinfo)
	{
		if((int)Configuration::get('AGILE_MS_IS_MANUFACTURER') == 1 || (int)Configuration::get('AGILE_MS_IS_SUPPLIER') == 1)
		{
			$sellerlogo = self::seller_logo_physical_path($sellerinfo->id);
			
			if((int)Configuration::get('AGILE_MS_IS_MANUFACTURER') == 1)
			{			
				$manufacturerlogo = _PS_IMG_DIR_ . "m" .  DIRECTORY_SEPARATOR . $sellerinfo->id_manufacturer . ".jpg";

				copy($sellerlogo, $manufacturerlogo);
				$images_types = ImageType::getImagesTypes('manufacturers');
				foreach ($images_types as $k => $image_type) 
				{
					ImageManager::resize(_PS_MANU_IMG_DIR_.$sellerinfo->id_manufacturer.'.jpg',_PS_MANU_IMG_DIR_.$sellerinfo->id_manufacturer.'-'.stripslashes($image_type['name']).'.jpg',(int)$image_type['width'],(int)$image_type['height']);
				}
			}
			
			if((int)Configuration::get('AGILE_MS_IS_SUPPLIER') == 1)
			{
				$supplierlogo = _PS_IMG_DIR_ . "su" .  DIRECTORY_SEPARATOR . $sellerinfo->id_supplier . ".jpg";
				copy($sellerlogo, $supplierlogo);
				$images_types = ImageType::getImagesTypes('suppliers');
				foreach ($images_types as $k => $image_type) 
				{
					$file = _PS_SUPP_IMG_DIR_.$sellerinfo->id_supplier.'.jpg';
					ImageManager::resize($file, _PS_SUPP_IMG_DIR_.$sellerinfo->id_supplier.'-'.stripslashes($image_type['name']).'.jpg', (int)$image_type['width'], (int)$image_type['height']);
				}
			}
		}
	}
	
	public static function get_seller_payment_collection($id_seller)
	{
		$sql = "SELECT payment_collection FROM " . _DB_PREFIX_ . "sellerinfo WHERE id_seller=" . (int)$id_seller;
		return  (int)Db::getInstance()->getValue($sql);
	}
	
	
	
	public static function syncProductManufacturerSupplier($product)
	{
		if((int)Configuration::get('AGILE_MS_IS_MANUFACTURER') == 1 || (int)Configuration::get('AGILE_MS_IS_SUPPLIER') == 1)
		{
			$id_seller = AgileSellerManager::getObjectOwnerID('product', $product->id);
			$sellerinfo = new SellerInfo(SellerInfo::getIdBSellerId($id_seller));
			if((int)Configuration::get('AGILE_MS_IS_SUPPLIER') == 1)
			{
				Db::getInstance()->Execute("UPDATE " . _DB_PREFIX_ . "product set id_supplier=" . (int) $sellerinfo->id_supplier . " WHERE id_product=" . $product->id);
			}
			if((int)Configuration::get('AGILE_MS_IS_MANUFACTURER') == 1)
			{
				Db::getInstance()->Execute("UPDATE " . _DB_PREFIX_ . "product set id_manufacturer=" . (int) $sellerinfo->id_manufacturer . " WHERE id_product=" . $product->id);
			}
		}
	}	

	public static function seller_banner_physical_path($id_sellerinfo)
	{
		$banner = SellerInfo::get_logo_folder() . $id_sellerinfo . '_banner.jpg';
		if(file_exists($banner))return $banner;
		
				return '';
	}
	public function get_seller_banner_url()
	{
		return self::get_seller_banner_url_static($this->id);
	}
	
	public static function get_seller_banner_url_static($id_sellerinfo)
	{
		$context = Context::getContext();
		
		$lang = new Language($context->language->id);
		$bannerfile = self::seller_banner_physical_path($id_sellerinfo);

		if(file_exists($bannerfile))
			return Tools::getShopDomainSsl(true) . __PS_BASE_URI__ . 'img/as/' . $id_sellerinfo . '_banner.jpg?' . date("YmdHis",filemtime($bannerfile));
		else
			return '';
	}


	public static function processBannerUpload($sellerInfo)
	{		
		$banner_folder = SellerInfo::get_logo_folder();
		if(!Validate::isLoadedObject($sellerInfo))return false;
		if(!empty($_FILES['banner']['name']))
		{
			$pathinfo = pathinfo($_FILES['banner']['name']);
			if(!in_array($pathinfo['extension'], array('jpg','jpeg','png')))return false;

			$filename = $banner_folder . $sellerInfo->id . "_banner.jpg";
			if(!move_uploaded_file($_FILES['banner']['tmp_name'], $filename)) return false;
			
			return true;
		}
		return true;
	}
	
}

