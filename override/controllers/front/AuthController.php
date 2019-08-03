<?php
class AuthController extends AuthControllerCore
{	
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:41
    * version: 3.7.3.2
    */
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
