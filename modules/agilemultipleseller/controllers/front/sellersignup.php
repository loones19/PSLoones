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

class AgileMultipleSellerSellerSignupModuleFrontController extends AgileModuleFrontController
{
	public $auth = false; 	
	public function setMedia()
	{
		parent::setMedia();

		$deflang = new Language(self::$cookie->id_lang);
		$isoTinyMCE = (file_exists(_PS_ROOT_DIR_.'/js/tiny_mce/langs/'.$deflang->iso_code.'.js') ? $deflang->iso_code : 'en');

		Media::addJsDef(array(
			'is_multilang_address' => true,
			'has_address' => true,
			'pathCSS' => _THEME_CSS_DIR_,
			'idSelectedCountry' => $this->getSelectedCountry(),
			'idSelectedState' => $this->getSelectedState(),
			'iso' => $isoTinyMCE,
			'ad' => str_replace("\\", "\\\\", dirname($_SERVER["PHP_SELF"])),
			'id_language_current' => self::$cookie->id_lang ,
			'agileCountries' => $this->getCountries()
			));
		

		Media::addJsDefL('sellerbusiness_fileDefaultHtml', $this->l('No file selected'));
		Media::addJsDefL('sellerbusiness_fileButtonHtml', $this->l('Choose File'));		

		$this->registerStylesheet('jquery.ui.datepicker', '/js/jquery/ui/themes/base/jquery.ui.datepicker.css', ['media' => 'all', 'priority' => 100]);
		$this->registerStylesheet('jquery.fancybox', '/js/jquery/plugins/fancybox/jquery.fancybox.css', ['media' => 'all', 'priority' => 100]);
		
		$this->registerJavascript('js_tools','/js/tools.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('agile_tinymce','/modules/agilemultipleseller/js/agile_tiny_mce.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('agile_filemanager','/modules/agilemultipleseller/filemanager/plugin.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('js_typewatch','/js/jquery/plugins/jquery.typewatch.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('js_fancybox','/js/jquery/plugins/fancybox/jquery.fancybox.js',['position' => 'bottom', 'priority' => 100]);
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
	}
	
	
	public function init()
	{
		parent::init();
				
		if((int)Configuration::get('AGILE_MS_CUSTOMER_SELLER')!=1)
		{
			Tools::redirect(__PS_BASE_URI__);
		}

		if($this->context->customer->isLogged())
		{
			Tools::redirect($this->context->link->getModuleLink('agilemultipleseller', 'sellersummary', array(),true));
		}

		$deflang = new Language(self::$cookie->id_lang);
		$isoTinyMCE = (file_exists(_PS_ROOT_DIR_.'/js/tiny_mce/langs/'.$deflang->iso_code.'.js') ? $deflang->iso_code : 'en');
		$ad = str_replace("\\", "\\\\", dirname($_SERVER["PHP_SELF"]));		$languages = Language::getLanguages(false);
		
		$lang_fields = array('company', 'city', 'address1','address2','description');
		foreach($lang_fields as $field)
		{
			$this->sellerinfo->{$field} = array();
			foreach($languages as $lang)
			{
				$id_lang = $lang['id_lang'];
				$this->sellerinfo->{$field}[$id_lang] = Tools::getValue($field . '_' . $id_lang,'');
			}
		}
		
		$cms = new CMS((int)(Configuration::get('AGILE_MS_SELLER_TERMS')), $id_lang);
		$link_terms = $this->context->link->getCMSLink($cms, $cms->link_rewrite, true);
		if (!strpos($link_terms, '?'))
			$link_terms .= '?content_only=1';
		else
			$link_terms .= '&content_only=1';
		
				self::$smarty->assign(array(
			'languages' => $languages
			,'link_terms' => $link_terms
			,'id_language_current' => self::$cookie->id_lang
			,'id_cms_seller_terms' =>  intval(Configuration::get('AGILE_MS_SELLER_TERMS'))
			));
	}
	
	public function initContent()
	{
		parent::initContent();
		
		$this->assignCountries();
		$this->assignVatNumber();
		$this->assignAddressFormat();

		$this->setTemplate('module:agilemultipleseller/views/templates/front/sellersignup.tpl');
	}
	
	public function postProcess()
	{
		if (Tools::isSubmit('submitSellerinfo'))
		{
			$this->processSubmitSellerinfo();
		}
	}
	
	private function getSelectedState()
	{
		$selected_state = 0;
				if (Tools::isSubmit('id_state') && !is_null(Tools::getValue('id_state')) && is_numeric(Tools::getValue('id_state')))
			$selected_state = (int)Tools::getValue('id_state');
		else if (isset($this->sellerinfo) && isset($this->sellerinfo->id_state) && !empty($this->sellerinfo->id_state) && is_numeric($this->sellerinfo->id_state))
			$selected_state = (int)$this->sellerinfo->id_state;

		return (int)$selected_state;		
	}
	
	private function getCountries()
	{
		if (Configuration::get('PS_RESTRICT_DELIVERED_COUNTRIES'))
			$countries = Carrier::getDeliveredCountries($this->context->language->id, true, true);
		else
			$countries = Country::getCountries($this->context->language->id, true);
		
		return $countries;		
	}
	
	private function getSelectedCountry()
	{
				if (Tools::isSubmit('id_country') && !is_null(Tools::getValue('id_country')) && is_numeric(Tools::getValue('id_country')))
			$selected_country = (int)Tools::getValue('id_country');
		else if (isset($this->sellerinfo) && isset($this->sellerinfo->id_country) && !empty($this->sellerinfo->id_country) && is_numeric($this->sellerinfo->id_country))
			$selected_country = (int)$this->sellerinfo->id_country;
		else if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
		{
			$array = preg_split('/,|-/', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
			if (!Validate::isLanguageIsoCode($array[0]) || !($selected_country = Country::getByIso($array[0])))
				$selected_country = (int)Configuration::get('PS_COUNTRY_DEFAULT');
		}
		else
			$selected_country = (int)Configuration::get('PS_COUNTRY_DEFAULT');

		return $selected_country;		
	}
	
		protected function assignCountries()
	{

				$this->context->smarty->assign(array(
			'countries' => $this->getCountries(),
		));
	}

		protected function assignAddressFormat()
	{
		$id_country = is_null($this->sellerinfo)? 0 : (int)$this->sellerinfo->id_country;
		$dlv_adr_fields = AddressFormat::getOrderedAddressFields($id_country, true, true);
		$this->context->smarty->assign('ordered_adr_fields', $dlv_adr_fields);
	}

	
	protected function assignVatNumber()
	{
		$vat_number_exists = file_exists(_PS_MODULE_DIR_.'vatnumber/vatnumber.php');
		$vat_number_management = Configuration::get('VATNUMBER_MANAGEMENT');
		if ($vat_number_management && $vat_number_exists)
			include_once(_PS_MODULE_DIR_.'vatnumber/vatnumber.php');

		if ($vat_number_management && $vat_number_exists && VatNumber::isApplicable(Configuration::get('PS_COUNTRY_DEFAULT')))
			$vat_display = 2;
		else if ($vat_number_management)
			$vat_display = 1;
		else
			$vat_display = 0;

		$this->context->smarty->assign(array(
			'vatnumber_ajax_call' => file_exists(_PS_MODULE_DIR_.'vatnumber/ajax.php'),
			'vat_display' => $vat_display,
			));
	}


	protected function processSubmitSellerinfo()
	{
		AgileMultipleSeller::ensure_date_custom_field();
		$this->sellerinfo->date_add = date('Y-m-d H:i:s');
		
		$firstname = trim(Tools::getValue('firstname'));
		$lastname = trim(Tools::getValue('lastname'));
		$passwd = trim(Tools::getValue('passwd'));
		$email = trim(Tools::getValue('email'));
		
		if(empty($firstname) || !Validate::isName($firstname))
			$this->errors[] = $this->l('First name is invalid.');
		if(empty($lastname)  || !Validate::isName($lastname))
			$this->errors[] =  $this->l('Last name is invalid.');	
		if (empty($email))
			$this->errors[] = $this->l('An email address required.');
		elseif (!Validate::isEmail($email))
			$this->errors[] = $this->l('Invalid email address.');
		elseif (empty($passwd))
			$this->errors[] = $this->l('Password is required.');
		elseif (!Validate::isPasswd($passwd))
			$this->errors[] = $this->l('Invalid password.');

		if(!empty($this->errors))return;

		if (Customer::customerExists($email))
		{
			$this->errors[] = $this->l('An account using this email address has already been registered.', false);
		}

		$this->errors = array_merge($this->errors, $this->sellerinfo->validateController());

		if (!($country = new Country($this->sellerinfo->id_country)) || !Validate::isLoadedObject($country))
			throw new PrestaShopException('Country cannot be loaded with id_country');

		if ((int)$country->contains_states && !(int)$this->sellerinfo->id_state)
			$this->errors[] = $this->l('This country requires a state selection.');

				$zip_code_format = $country->zip_code_format;
		if ($country->need_zip_code)
		{
			if (($postcode = Tools::getValue('postcode')) && $zip_code_format)
			{
				$zip_regexp = '/^'.$zip_code_format.'$/ui';
				$zip_regexp = str_replace(' ', '( |)', $zip_regexp);
				$zip_regexp = str_replace('-', '(-|)', $zip_regexp);
				$zip_regexp = str_replace('N', '[0-9]', $zip_regexp);
				$zip_regexp = str_replace('L', '[a-zA-Z]', $zip_regexp);
				$zip_regexp = str_replace('C', $country->iso_code, $zip_regexp);
				if (!preg_match($zip_regexp, $postcode))
					$this->errors[] = '<strong>'.$this->l('Zip / Postal code').'</strong> '
						.$this->l('is invalid.').'<br />'.$this->l('Must be typed as follows:')
						.' '.str_replace('C', $country->iso_code, str_replace('N', '0', str_replace('L', 'A', $zip_code_format)));
			}
			else if ($zip_code_format)
				$this->errors[] = '<strong>'.$this->l('Zip / Postal code').'</strong> '.$this->l('is required.');
			else if ($postcode && !preg_match('/^[0-9a-zA-Z -]{4,9}$/ui', $postcode))
				$this->errors[] = '<strong>'.$this->l('Zip / Postal code').'</strong> '.$this->l('is invalid.')
					.'<br />'.$this->l('Must be typed as follows:').' '
					.str_replace('C', $country->iso_code, str_replace('N', '0', str_replace('L', 'A', $zip_code_format)));
		}

				if ($country->isNeedDni() && (!Tools::getValue('dni') || !Validate::isDniLite(Tools::getValue('dni'))))
			$this->errors[] = $this->l('Identification number is incorrect or has already been used.');

		$this->sellerinfo->dni = Tools::getValue('dni');
		
		$this->sellerinfo->latitude = Tools::getValue('latitude');
		$this->sellerinfo->longitude = Tools::getValue('longitude');

		$this->sellerinfo->id_sellertype1 = Tools::getValue('id_sellertype1');
		$this->sellerinfo->id_sellertype2 = Tools::getValue('id_sellertype2');

				if (!empty($this->errors))
			return;

		$customer = new Customer();
		$customer->firstname = $firstname;
		$customer->lastname = $lastname;
		$customer->email = $email;
		$customer->is_guest = 0;
		$customer->newsletter = 0;
		$customer->optin = 0;
		$customer->active = 1;
		$customer->passwd = Tools::encrypt($passwd);
		if($customer->add())
		{
			if (!$this->sendConfirmationMail($customer))
			{
				$this->errors[] = $this->l('The email cannot be sent.');
			}
		}
		else
		{
			$this->errors[] = $this->l('Error during create a new customer account.');
			return;
		}		

		$id_lang = (int)Configuration::get('PS_LANG_DEFAULT');
		$address = new Address();
		$address->lastname = $customer->lastname;
		$address->firstname = $customer->firstname;
		$address->id_country = intval(Tools::getValue('id_country'));		
		$address->id_state = intval(Tools::getValue('id_state'));
		$address->postcode = Tools::getValue('postcode');
		$address->phone = Tools::getValue('phone');
		$address->company = Tools::getValue('company_'.$id_lang);
		$address->city = Tools::getValue('city_'.$id_lang);
		$address->address1 = Tools::getValue('address1_'.$id_lang);
		$address->address2 = Tools::getValue('address2_'.$id_lang);
		$address->id_customer = $customer->id;
		$address->alias ="My Home";
		$address->add();

		$this->updateContext($customer);
		Hook::exec('actionCustomerAccountAdd', array(
			'_POST' => $_POST,
			'newCustomer' => $customer
			));
		
		
		if(empty($this->_errors))
		{
			AgileMultipleSeller::createSellerAccount($customer);
			$newsellerinfo = new SellerInfo(SellerInfo::getIdByCustomerId($customer->id));
			$this->sellerinfo->id = $newsellerinfo->id;
			$this->sellerinfo->id_seller = $newsellerinfo->id_seller;
			$this->sellerinfo->id_customer = $newsellerinfo->id_customer;
			$this->sellerinfo->date_add = $newsellerinfo->date_add;
			$this->sellerinfo->id_shop = $newsellerinfo->id_shop;
			$this->sellerinfo->id_supplier = $newsellerinfo->id_supplier;
			$this->sellerinfo->id_manufacturer = $newsellerinfo->id_manufacturer;
			$this->sellerinfo->theme_name = $newsellerinfo->theme_name;
			
			$this->sellerinfo->update();
						$url = $this->context->link->getModuleLink('agilemultipleseller', 'sellerbusiness', array(), true);
			Tools::redirect($url);
		} 
	}

	protected function sendConfirmationMail(Customer $customer)
	{

		//LOONES 
		if (!Configuration::get('PS_CUSTOMER_CREATION_EMAIL'))
			return true;

		 return Mail::Send(
			$this->context->language->id,
			'account',
			Mail::l('Welcome!'),
			array(
				'{firstname}' => $customer->firstname,
				'{lastname}' => $customer->lastname,
				'{email}' => $customer->email,
				'{passwd}' => Tools::getValue('passwd')),
			$customer->email,
			$customer->firstname.' '.$customer->lastname
			); 
	}

	protected function updateContext(Customer $customer)
	{
		$this->context->customer = $customer;
		$this->context->smarty->assign('confirmation', 1);
		$this->context->cookie->id_customer = (int)$customer->id;
		$this->context->cookie->customer_lastname = $customer->lastname;
		$this->context->cookie->customer_firstname = $customer->firstname;
		$this->context->cookie->passwd = $customer->passwd;
		$this->context->cookie->logged = 1;
				if (!Configuration::get('PS_REGISTRATION_PROCESS_TYPE'))
			$this->context->cookie->account_created = 1;
		$customer->logged = 1;
		$this->context->cookie->email = $customer->email;
		$this->context->cookie->is_guest = !Tools::getValue('is_new_customer', 1);
				$this->context->cart->secure_key = $customer->secure_key;
	}

}