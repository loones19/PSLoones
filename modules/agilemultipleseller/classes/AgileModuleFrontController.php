<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
class AgileModuleFrontControllerCore extends ModuleFrontController
{
	public $auth = true;
	public $ssl = true;
	protected $sellerinfo;
	protected $isSeller;
	protected $seller;
	protected $public_pages = array("sellersummary.php","sellersignup.php","agilesellers.php","sellerlocation.php","showcaseform.php","showcaselist.php","showcaseview.php");

		public $display_column_left = false;  
	public $display_column_right = false;
	
	public function init()
	{
		parent::init();

		if(!Module::isInstalled('agilemultipleseller'))return;
			
		include_once(_PS_ROOT_DIR_ . '/modules/agilemultipleseller/agilemultipleseller.php');
		include_once(_PS_ROOT_DIR_ . '/modules/agilemultipleseller/SellerInfo.php');

		$this->sellerinfo = new SellerInfo(SellerInfo::getIdByCustomerId(self::$cookie->id_customer));
		
		$this->seller = new Employee($this->sellerinfo->id_seller);
		$seller_exists = Validate::isLoadedObject($this->seller);
		$this->isSeller =  ($seller_exists AND $this->seller->active && $this->seller->id_profile == (int)Configuration::get('AGILE_MS_PROFILE_ID'));
		$pagename = AgileHelper::getPageName();
		if(!$seller_exists && !in_array($pagename, $this->public_pages))
		{
			$this->errors[] = Tools::displayError('You do not have permission to access this page. Please conatct store administrator.');
		}

		if(Module::isInstalled('agilesellerlistoptions'))
		{
			include_once(_PS_ROOT_DIR_ . '/modules/agilesellerlistoptions/agilesellerlistoptions.php');
			$aslmodule = new AgileSellerListOptions();
			self::$smarty->assign(array(
				'pay_options_link' =>$aslmodule->getPayOptionLink($this->sellerinfo->id_seller)
				));
		}


				self::$smarty->assign(array(
			'isSeller' => $this->isSeller
			,'seller_exists' => $seller_exists
			,'agilemultipleseller_views' => _PS_ROOT_DIR_  . "/modules/agilemultipleseller/views/"
			,'agilemultipleseller_custom' => _PS_ROOT_DIR_  . "/modules/agilemultipleseller/custom/"
			,'sellerinfo' => $this->sellerinfo
			,'seller' => $this->seller
			,'seller_back_office' => (int)Configuration::get('AGILE_MS_SELLER_BACK_OFFICE')
			,'is_seller_shipping_installed' => Module::isInstalled('agilesellershipping')
			,'is_seller_commission_installed' => Module::isInstalled('agilesellercommission')
			,'is_seller_messenger_installed' => Module::isInstalled('agilesellermessenger')
			,'is_seller_ratings_installed' => Module::isInstalled('agilesellerratings')
			,'is_multiple_shop_installed' => Module::isInstalled('agilemultipleshop')
			,'is_seller_listoptions_installed' => Module::isInstalled('agilesellerlistoptions')
			,'is_agileprepaidcredit_installed' => Module::isInstalled('agileprepaidcredit')
			,'is_seller_tools_installed' => Module::isInstalled('agilesellertools')
			,'sellertoken' => (Tools::encrypt('ams_seller') . ($this->isSeller?$this->seller->passwd:''))
			,'admin_folder_name' => Configuration::get('AGILE_MS_ADMIN_FOLDER_NAME')
			,'selleremail' => ($this->seller?$this->seller->email:'')
			,'ajaxurl' => _MODULE_DIR_
			,'seller_palenl_withleft' => (int)Configuration::get('AGILE_MS_SELLER_PANEL_WITHLEFT')
			,'seller_palenl_withright' => (int)Configuration::get('AGILE_MS_SELLER_PANEL_WITHRIGHT')
			,'is_seller_pickupcenter_installed' => Module::isInstalled('agilepickupcenter')
			));
	}
	
	public function setMedia()
	{
		if (Tools::getValue('ajax') != 'true')
		{
			parent::setMedia();
		}
		
		$this->addJqueryPlugin(array('idTabs'));
	}
	
	
}
