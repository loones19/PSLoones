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
	
	public function init()
	{
		parent::init();

		if(isset($_GET['vkey']) && !empty($_GET['vkey']))
		{
			$data = explode("|",base64_decode($_GET['vkey']));
			if(count($data)>1)
			{
				$secure_key = $data[0];
				$email = $data[1];
				$cobj = new Customer();
				$customer = $cobj->getByEmail($email);
				if(Validate::isLoadedObject($customer))
				{
					$customer->active = 1;
					$customer->save();
					$this->context->smarty->assign(array('vconfirm' => '1'));
				}
			}
		}
		
	}
	
	protected function sendConfirmationMail(Customer $customer)
	{
		if (!Configuration::get('PS_CUSTOMER_CREATION_EMAIL')) {
			return true;
		}
		
		$customer->active = 0 ;
		$customer->save();
		
		$vlink = $this->context->link->getPageLink("authentication") . "?vkey=" . base64_encode($customer->secure_key . "|" . $customer->email);

		return Mail::Send(
			$this->context->language->id,
			'account',
			Mail::l('Welcome!'),
			array(
				'{vlink}' => $vlink,
				'{firstname}' => $customer->firstname,
				'{lastname}' => $customer->lastname,
				'{email}' => $customer->email,
				'{passwd}' => Tools::getValue('passwd')),
			$customer->email,
			$customer->firstname.' '.$customer->lastname
			);
	}
	
}
