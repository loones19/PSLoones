<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
include_once(_PS_ROOT_DIR_ .'/modules/agilemultipleseller/SellerInfo.php');
include_once(_PS_ROOT_DIR_ .'/modules/agilemultipleseller/SellerType.php');

class AgileMultipleSellerSellerBusinessModuleFrontController extends AgileModuleFrontController
{
		public $needGoogleMap = true;

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
		$this->registerJavascript('agile_sellerbusiness','/modules/agilemultipleseller/js/sellerbusiness.js',['position' => 'bottom', 'priority' => 100]);

	}
	
	
	public function init()
	{
		parent::init();

		$deflang = new Language(self::$cookie->id_lang);
		$isoTinyMCE = (file_exists(_PS_ROOT_DIR_.'/js/tiny_mce/langs/'.$deflang->iso_code.'.js') ? $deflang->iso_code : 'en');
		$ad = str_replace("\\", "\\\\", dirname($_SERVER["PHP_SELF"]));		$languages = Language::getLanguages(true);
		
		$sellermodule = new AgileMultipleSeller();
		$conf = Configuration::getMultiple($sellermodule->getCustomFields());
		$custom_labels = $sellermodule->getCustomLabels(':');
		$custom_hints = $sellermodule->getCustomHints();
		$custom_multi_lang_fields = SellerInfo::getCustomMultiLanguageFields();
		$str_custom_multi_lang_fields = "";
		foreach ($custom_multi_lang_fields as $custom_multi_lang_field)
		{
			$str_custom_multi_lang_fields .= '&curren;'.$custom_multi_lang_field;
		}
			
		$filter_mode = (int)Configuration::get('AGILE_ZIPCODE_FILTER_MODE');

				self::$smarty->assign(array(
			'seller_tab_id' => 2
			,'ad' => $ad
			,'isoTinyMCE' => $isoTinyMCE
			,'theme_css_dir' => _THEME_CSS_DIR_
			,'languages' => $languages
			,'id_language_current' => self::$cookie->id_lang
			,'seller_choose_theme' => (int)Configuration::get('AGILE_MS_SELLER_CHOOSE_THEME')
			,'conf' => $conf
			,'custom_labels' => $custom_labels
			,'custom_hints' => $custom_hints
			,'str_custom_multi_lang_fields' => $str_custom_multi_lang_fields
			,'shop_url_mode' => (int)Configuration::get('ASP_SHOP_URL_MODE')
			,'iso_code'=>$isoTinyMCE
			,'iszipcodefilterinstalled' => (Module::isInstalled('agilezipcodefilter')? 1 : 0)
			,'filter_mode' => $filter_mode
			));
	}

	
	public function initContent()
	{
		parent::initContent();
		
		$default_shop = new Shop( Configuration::get('PS_SHOP_DEFAULT'));
		$seller_shop = new Shop($this->sellerinfo->id_shop); 
		$seller_shopurl = new ShopUrl(Shop::get_main_url_id($seller_shop->id));

		
		self::$smarty->assign(array(		
			'default_shop' => $default_shop
			,'seller_shop'=>$seller_shop
			,'seller_shopurl' => $seller_shopurl 
			,'sellertypes' => SellerType::getSellerTypes($this->context->language->id,  $this->l('Please choose seller type')) 
			));

		$this->assignCountries();
		$this->assignVatNumber();
		$this->assignAddressFormat();

		$this->setTemplate('module:agilemultipleseller/views/templates/front/sellerbusiness.tpl');		
	}
	
	public function postProcess()
	{
		if (Tools::isSubmit('submitSellerinfo'))
			$this->processSubmitSellerinfo();
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

	private function getSelectedState()
	{
		$selected_state = 0;
				if (Tools::isSubmit('id_state') && !is_null(Tools::getValue('id_state')) && is_numeric(Tools::getValue('id_state')))
			$selected_state = (int)Tools::getValue('id_state');
		else if (isset($this->sellerinfo) && isset($this->sellerinfo->id_state) && !empty($this->sellerinfo->id_state) && is_numeric($this->sellerinfo->id_state))
			$selected_state = (int)$this->sellerinfo->id_state;

		return (int)$selected_state;		
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
		
				$shop_name = '';
		if(isset($_POST['shop_name'])) $shop_name = trim($_POST['shop_name']," ");
		if(isset($_POST['virtual_uri']))
			$virtual_uri = Tools::link_rewrite(trim($_POST['virtual_uri']," /")) . "/";

		$this->errors = array_merge($this->errors, $this->sellerinfo->validateController());

		$this->sellerinfo->id_customer = self::$cookie->id_customer;
		if(Module::isInstalled('agilemultipleshop'))
		{
			if(empty($shop_name))
				$this->errors[] = $this->l('The shop name can not be empty.');

			if(empty($_POST['virtual_uri']) AND (int)Configuration::get('ASP_SHOP_URL_MODE') == agilemultipleshop::SHOP_URL_MODE_VIRTUAL)
				$this->errors[] = $this->l('The shop Virtual Uri can not be empty.');

						if($this->sellerinfo->id_shop <=1)$this->sellerinfo->id_shop = 0;
			
			$seller_shop = new Shop($this->sellerinfo->id_shop); 
			if(Shop::shop_name_duplicated($shop_name, $seller_shop->id))
				$this->errors[] = $this->l('The shop name you select has been used by other seller. Please choose a new one.');

			if($this->errors)return;
			if(!Validate::isLoadedObject($seller_shop))
			{
				$vshop = AgileMultipleShop::create_new_shop($this->sellerinfo->id_seller, $shop_name);
				$this->sellerinfo->id_shop = $vshop->id;
				$this->sellerinfo->theme_name = $vshop->theme_name;
				$this->sellerinfo->update();
				$seller_shop = new Shop($this->sellerinfo->id_shop);
			}
			
			$seller_shopurl = new ShopUrl(Shop::get_main_url_id($seller_shop->id));
			$id_found = $seller_shopurl->canAddThisUrl($seller_shopurl->domain,$seller_shopurl->domain_ssl,$seller_shopurl->physical_uri, $virtual_uri);
			if(intval($id_found)>0)
				$this->errors[] = $this->l('The uri you select has been used by other seller. Please choose a new one.');
		}
				if (!($country = new Country($this->sellerinfo->id_country)) || !Validate::isLoadedObject($country))
			throw new PrestaShopException('Country cannot be loaded with address->id_country');

		if ((int)$country->contains_states && !(int)$this->sellerinfo->id_state)
			$this->errors[] = $this->l('This country requires a state selection.');

																																												
				$zip_code_format = $country->zip_code_format;
		if ($country->need_zip_code)
		{
			if(empty($_POST['postcode']))
				$this->errors[] = $this->l('Postcode is required field.');
			
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

		if(!empty($_FILES['logo']['name']))
		{
			if(defined('SellerInfo::MAX_LOGO_FILE_SIZE_IN_BYTES'))
			{
				if(filesize($_FILES['logo']['tmp_name']) > SellerInfo::MAX_LOGO_FILE_SIZE_IN_BYTES)
				{
					$this->errors[] = Tools::displayError('Logo file size is too bigger, maximum logo gile size (bytes) is: ') . SellerInfo::MAX_LOGO_FILE_SIZE_IN_BYTES ;
				}
			}
			if(empty($this->errors))
			{
				if(!SellerInfo::processLogoUpload($this->sellerinfo))
				{
					$this->errors[] = $this->l('The logo upload failed, please make sure you are uploading an image file.');
				}
			}	
		}

		if(!empty($_FILES['banner']['name']))
		{
			if(defined('SellerInfo::MAX_BANNER_FILE_SIZE_IN_BYTES'))
			{
				if(filesize($_FILES['banner']['tmp_name']) > SellerInfo::MAX_BANNER_FILE_SIZE_IN_BYTES)
				{
					$this->errors[] = Tools::displayError('Banner file size is too bigger, maximum banner gile size (bytes) is: ') . SellerInfo::MAX_BANNER_FILE_SIZE_IN_BYTES ;
				}
			}
			if(empty($this->errors))
			{
				if(!SellerInfo::processBannerUpload($this->sellerinfo))
				{
					$this->errors[] = $this->l('The banner upload failed, please make sure you are uploading an image file.');
				}
			}	
		}
		
		$this->errors = array_merge($this->errors, $this->sellerinfo->validateController());
		
				if (!empty($this->errors))
			return;

		$this->sellerinfo->save();
		
		if(Module::isInstalled('agilemultipleshop') AND Validate::isLoadedObject($seller_shop))
		{
			$seller_shop->name = $shop_name;
			$seller_shop->theme_name = $this->sellerinfo->theme_name;
			$seller_shop->save();
			$seller_shopurl->virtual_uri = $virtual_uri;
			$seller_shopurl->save();
			Tools::generateHtaccess();
		}
		
		if(empty($this->errors))self::$smarty->assign('cfmmsg_flag',1);
			}

}