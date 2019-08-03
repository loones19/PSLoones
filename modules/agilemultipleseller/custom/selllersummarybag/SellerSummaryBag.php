<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
define('AMS_CUSTOM_SELLERSUMMARY_BAGS_RECEIVED_FIELD', 'ams_custom_number1');
define('AMS_CUSTOM_SELLERSUMMARY_BAGS_SENT_FIELD', 'ams_custom_number2');
include_once(_PS_ROOT_DIR_ .'/modules/agilemultipleseller/SellerInfo.php');

class SellerSummaryBag extends Module
{
	public function initContent($sellerinfo, $context)
	{
		if(!$context)$context == Context::getContext();
		
				$this->createSellerSignUpFields($sellerinfo);		$context->smarty->assign(array(
			'ams_custom_selllersummarybag' => 1,
			'bags_received' => (int)$sellerinfo->{AMS_CUSTOM_SELLERSUMMARY_BAGS_RECEIVED_FIELD},
			'bags_sent' => (int)$sellerinfo->{AMS_CUSTOM_SELLERSUMMARY_BAGS_SENT_FIELD},
					));
	}

	public function postProcess($context)
	{
		if(!$context)$context == Context::getContext();
		$errs = array();
		if(!$context->customer->id)$errs[] = $this->l('Seller Info not found');

		if (Tools::getValue('submitBagsRequest') == "1")
        {
			$sellerinfo = new SellerInfo(SellerInfo::getIdByCustomerId($context->cookie->id_customer));
			if(!Validate::isLoadedObject($sellerinfo))
			{
				$errs[] = $this->l('Seller info not found.');
				return $errs;
			}
			$sellerinfo->company = Tools::getValue('company');
			$sellerinfo->city = Tools::getValue('city');
			$sellerinfo->address1 = Tools::getValue('address1');
			$sellerinfo->address2 = Tools::getValue('address2');
			$sellerinfo->id_country = intval(Tools::getValue('id_country'));
			if(Country::containsStates($sellerinfo->id_country))
			{
				$sellerinfo->id_state = intval(Tools::getValue('id_state'));
			}
			$sellerinfo->postcode = Tools::getValue('postcode');
			$sellerinfo->phone = Tools::getValue('phone');
			$numberofbags = Tools::getValue('txtBags');

				if(empty($sellerinfo->company))
			{
				$errs[] = Tools::displayError('Company is required.');
			}
			if(empty($sellerinfo->city))
			{
				$errs[] = Tools::displayError('city is required.');
			}
			if(empty($sellerinfo->address1))
			{
				$errs[] = Tools::displayError('Address1 is required.');
			}
			if(empty($sellerinfo->postcode))
			{
				$errs[] = Tools::displayError('Postcode is required.');
			}
			if(empty($sellerinfo->phone))
			{
				$errs[] = Tools::displayError('Phone is required.');
			}
			
			if (count($errs)) return $errs;
			
			SellerSummaryBag::sendBagOrderEmail($sellerinfo, $numberofbags);
		}
		return $errs;
	}
	
	public function getAdditionalSignupFields()
	{
		return $this->display(_PS_ROOT_DIR_."/modules/agilemultipleseller/agilemultipleseller.php", 'custom/sellersummarybag/numberofbags.tpl');
	}
	
	public function createSellerSignUpFields($sellerinfo = null, $addtional_fields_html='')   
	{
		if($sellerinfo == null)
		{
			$sellerinfo = new SellerInfo();
		}
		$languages = Language::getLanguages(false);
		$id_language = $this->context->language->id;
		$db_display_fields = AgileMultipleSeller::get_display_fields('sellerinfo_signin', 0);
		$display_fields = array();
		$countries = Country::getCountries($this->context->language->id, true);
		foreach($db_display_fields as $display_field)
		{
			$display_fields[] = $display_field['field_name'];
		}
				$this->context->smarty->assign(array(
			'sellerinfo' => $sellerinfo,
			'languages' => $languages,
			'id_language' => $id_language,
			'display_fields' => $display_fields,
			'countries' => $countries,
			'addtional_fields_html' => $addtional_fields_html
			));
	}
	
	public static function sendBagOrderEmail($sellerinfo, $numberofbags)
	{
		$configuration = Configuration::getMultiple(array('PS_SHOP_EMAIL', 'PS_SHOP_NAME'));
		$id_lang = $this->context->language->id;     
		$iso = Language::getIsoById((int)($id_lang));
		$employee = new Employee($sellerinfo->id_seller);
		if(!Validate::isLoadedObject($employee))
			return $this->l('Seller informaiton not found.');
		$templateVars = array(
			'{shop_name}' => Configuration::get('PS_SHOP_NAME'),
			'{shop_url}'=>Tools::getShopDomainSsl(true, true).__PS_BASE_URI__,
			'{shop_logo}' => Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.'img/logo.jpg',
			'{firstname}' => $employee->firstname,
			'{lastname}' => $employee->lastname,
			'{company}' => $sellerinfo->company,
			'{fulladdress}' => $sellerinfo->fulladdress($id_lang),
			'{email}' => $employee->email,
			'{phone}' => $sellerinfo->phone,
			'{bags}' => $numberofbags,
			);
		$temp_folder = _PS_ROOT_DIR_.'/modules/agilemultipleseller/custom/selllersummarybag/mails/';

		if ( !file_exists($temp_folder .$iso.'/new_bags_order.txt') OR !file_exists($temp_folder .$iso.'/new_bags_order.html'))
		{
			$id_lang = (int)(Configuration::get('PS_LANG_DEFAULT'));
			$iso = Language::getIsoById($id_lang);
		}
		if (file_exists($temp_folder .$iso.'/new_bags_order.txt') AND file_exists($temp_folder .$iso.'/new_bags_order.html'))
		{
									Mail::Send($id_lang, 'new_bags_order', Mail::l('New bags order from a seller', $id_lang), $templateVars, $configuration['PS_SHOP_EMAIL'], $configuration['PS_SHOP_NAME'], $employee->email,  $employee->firstname . ' ' . $employee->lastname, NULL, NULL, $temp_folder);

			if(!$sellerinfo->{AMS_CUSTOM_SELLERSUMMARY_BAGS_RECEIVED_FIELD})
			{
				$sellerinfo->{AMS_CUSTOM_SELLERSUMMARY_BAGS_RECEIVED_FIELD} = floatval($numberofbags);
			}else
			{
				$sellerinfo->{AMS_CUSTOM_SELLERSUMMARY_BAGS_RECEIVED_FIELD} = floatval($sellerinfo->{AMS_CUSTOM_SELLERSUMMARY_BAGS_RECEIVED_FIELD}) + floatval($numberofbags);
			}
			$sellerinfo->{AMS_CUSTOM_SELLERSUMMARY_BAGS_RECEIVED_FIELD} = (string)($sellerinfo->{AMS_CUSTOM_SELLERSUMMARY_BAGS_RECEIVED_FIELD});
			$sellerinfo->Save();
		}
	}
}
