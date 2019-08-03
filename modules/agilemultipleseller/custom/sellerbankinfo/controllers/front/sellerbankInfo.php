<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
include_once(_PS_ROOT_DIR_ .'/modules/agilemultipleseller/SellerInfo.php');
include_once(_PS_ROOT_DIR_ .'/modules/agilemultipleseller/SellerBankInfo.php');

class AgileMultipleSellerSellerBankinfoModuleFrontController extends AgileModuleFrontController
{
	protected $sellerbankinfo;
	public function setMedia()
	{
		parent::setMedia();
	}

	public function init()
	{
		parent::init();

		$this->sellerbankinfo = new SellerBankInfo(SellerBankInfo::getIdBySellerID($this->sellerinfo->id_seller)); 
		if($this->sellerbankinfo->id ==0)
		{
			$seller_shop = new Shop(Configuration::get('PS_SHOP_DEFAULT'));
			if($this->sellerinfo->id_shop > 0)$seller_shop = new Shop($this->sellerinfo->id_shop);
			$this->sellerbankinfo->id_seller = $this->sellerinfo->id_seller;
			$this->sellerbankinfo->shop_name = $seller_shop->name;
			$this->sellerbankinfo->business_name = $this->sellerinfo->company[$this->context->language->id];
			$this->sellerbankinfo->business_address1 = $this->sellerinfo->fulladdress($this->context->language->id);
			$this->sellerbankinfo->business_address2 = $this->sellerinfo->fulladdress($this->context->language->id);
		}
	}

	
	public function initContent()
	{
		parent::initContent();

		self::$smarty->assign(array(
			'seller_tab_id' => 12
			,'sellerbankinfo' => $this->sellerbankinfo
			));
		
		$this->setTemplate('sellerbankinfo.tpl');
	}
	
	public function postProcess()
	{
		if (Tools::isSubmit('submitPassword'))
		{
			$verify_passwd_encrypt = Tools::encrypt(Tools::getValue('verify_passwd'));
			if($this->sellerbankinfo->passwd != $verify_passwd_encrypt)
			{
				$this->errors[] = $this->l('Password you entered does not match. Please try again.');
				self::$smarty->assign('verify_passwd_encrypt', Tools::getValue('verify_passwd_encrypt'));
			}
			else 
			{
				self::$smarty->assign('verify_passwd_encrypt', $verify_passwd_encrypt);
			}
		}
		else
		{
			self::$smarty->assign('verify_passwd_encrypt', Tools::getValue('verify_passwd_encrypt'));
		}
		
		if (Tools::isSubmit('submitSellerBankinfo'))
		{
			$this->processSubmitSellerBankinfo();
		}
		
	}
	
	protected function processSubmitSellerBankinfo()
	{
		$this->errors = $this->sellerbankinfo->validateController();
		if(!empty(Tools::getValue('passwd')))
		{
			if($this->sellerbankinfo->passwd != Tools::encrype(Tools::getValue('passwd_old')))
				$this->errors[] = $this->l('Old Password does not match.');
			
			if(Tools::getValue('passwd') != Tools::getValue('passwd2'))
				$this->errors[] = $this->l('Password and password confirmation does not match.');
		}
		if(empty($this->errors))$this->sellerbankinfo->save();
	}

}