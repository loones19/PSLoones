<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.

class AuthController extends AuthControllerCore
{	
	protected function processSubmitAccount()
	{
		$PS_REGISTRATION_PROCESS_TYPE = (int)Configuration::get('PS_REGISTRATION_PROCESS_TYPE');
		if(Module::isInstalled('agilemultipleseller') && isset($_POST['seller_account_signup']) && intval($_POST['seller_account_signup'])==1 && $PS_REGISTRATION_PROCESS_TYPE != 1)
		{
			$default_lang = new Language(intval(Configuration::get('PS_LANG_DEFAULT')));
			if(!isset($_POST['company_' . $default_lang->id]) || empty($_POST['company_' . $default_lang->id]))$_POST['company_' . $default_lang->id] = Tools::getValue('company_' . $this->context->language->id);
			$company = Tools::getValue('company_' . $default_lang->id);	
			$id_country = (int)Tools::getValue('id_country');	
			if($company == "")$this->errors[] = Tools::displayError('Company in default language is required');		
			if(!$id_country)$this->errors[] = Tools::displayError('Country is required');	
			
						if(Employee::employeeExists(Tools::getValue('email')))
			{
				$this->errors[] = Tools::displayError('This email address has been used by other employee at back office. You cannot use this email as a seller');
			}
		}
		parent::processSubmitAccount();
	}
	
}

