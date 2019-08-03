<?php
class AdminLoginController extends AdminLoginControllerCore
{
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:36
    * version: 3.7.3.2
    */
    public function init()
	{
		if (session_status() == PHP_SESSION_NONE)@session_start();
		parent::init();
		if(Module::isInstalled('agilemultipleseller') && Tools::getValue("isfrofront")==1)	
		{
			$this->processSellerLogin("");
			die("login success");
		}	
	}
	
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:36
    * version: 3.7.3.2
    */
    public function processLogin()
	{
		if(!Module::isInstalled('agilemultipleseller'))	
		{
			parent::processLogin();
			return;
		}	
		
				if(!(int)Configuration::get('AGILE_MS_SELLER_BACK_OFFICE'))
		{
			$passwd = trim(Tools::getValue('passwd'));
			$passwd = substr($passwd,strlen(Tools::encrypt('ams_seller')));
			$email = trim(Tools::getValue('email'));
			$emp = $this->getSellerByEmail($email, $passwd);
			if($emp AND Tools::isSubmit('ajax') AND $emp->id_profile == intval(Configuration::get('AGILE_MS_PROFILE_ID')))
			{
				$this->errors[] = Tools::displayError('Seller is not allowed to log in from back office.');
				die(Tools::jsonEncode(array('hasErrors' => true, 'errors' => $this->errors)));
			}
		}
		
		if(!Tools::getValue('seller_login'))
		{
			parent::processLogin();
			return;
		}
		else if(!Module::isInstalled('agiledashboard')) 
		{
			$token = Tools::getAdminToken($this->context->cookie->id_employee);
			$redirectUrl = 	"./index.php/product/catalog?token=" . $token;	
			$this->processSellerLogin($redirectUrl);
		}
		
	}
	
	
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:36
    * version: 3.7.3.2
    */
    public function processSellerLogin($redirectUrl)
	{
				$passwd = Tools::getValue('ams_seller_token');
		$email = Tools::getValue('ams_seller_email');
		if (empty($email))
			$this->errors[] = Tools::displayError('E-mail is empty');
		elseif (!Validate::isEmail($email))
			$this->errors[] = Tools::displayError('Invalid e-mail address');
		elseif (empty($passwd))
			$this->errors[] = Tools::displayError('Password is blank');
		else
		{
						$passwd = substr($passwd,strlen(Tools::encrypt('ams_seller')));
			$employee = $this->getSellerByEmail($email, $passwd);
			if (!$employee)
			{
				$this->errors[] = Tools::displayError('Employee does not exist or password is incorrect.');
				$this->context->employee->logout();
			}
		}
		if (!count($this->errors))
		{
						$this->context->employee =$employee;
			$employee_associated_shop = $this->context->employee->getAssociatedShops();
			if (empty($employee_associated_shop) && !$this->context->employee->isSuperAdmin())
			{
				$this->errors[] = Tools::displayError('Employee does not manage any shop anymore (shop has been deleted or permissions have been removed).');
				$this->context->employee->logout();
			}
			else
			{
				$this->context->employee->remote_addr = ip2long(Tools::getRemoteAddr());
								$cookie = Context::getContext()->cookie;
				$cookie->id_employee = $this->context->cookie->id_employee;
				$cookie->email = $this->context->employee->email;
				$cookie->profile = $this->context->cookie->profile;
				$cookie->passwd = $this->context->employee->passwd;
				$cookie->remote_addr = $this->context->employee->remote_addr;
				$cookie->write();
				
				if(!empty($redirectUrl))Tools::redirectAdmin($redirectUrl);
			}
		}
		else
		{
			var_dump($this->errors);
			die();
		}	
	}
	
	
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:36
    * version: 3.7.3.2
    */
    private function getSellerByEmail($email, $passwd)
	{
 		if (!Validate::isEmail($email) OR ($passwd != NULL AND !Validate::isPasswd($passwd)))
 			die(Tools::displayError());
		$sql = '
			SELECT * 
			FROM `'._DB_PREFIX_.'employee`
			WHERE `active` = 1
			AND `email` = \''.pSQL($email).'\'
			'.($passwd ? 'AND `passwd` = \''.$passwd.'\'' : '');
	    
		$result = Db::getInstance()->getRow($sql);
		if (!$result)
			return false;
		$emp = new Employee();
		$emp->id = $result['id_employee'];
		$emp->id_profile = $result['id_profile'];
		foreach ($result AS $key => $value)
		{
			if (key_exists($key, $emp))
				$emp->{$key} = $value;
		}
		return $emp;
	}
}
