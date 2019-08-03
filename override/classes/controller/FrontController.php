<?php
class FrontController extends FrontControllerCore
{
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:20
    * version: 3.7.3.2
    */
    public function init()
	{
		parent::init();
		if (session_status() == PHP_SESSION_NONE)@session_start();
		
		$_SESSION['id_customer'] = Context::getContext()->customer->id;
	}
		
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:20
    * version: 3.7.3.2
    */
    public function initHeader()
	{
		parent::initHeader();
		
		if(Module::isInstalled('agilemultipleshop'))
		{
			include_once(_PS_ROOT_DIR_  . "/modules/agilemultipleshop/agilemultipleshop.php");
			AgileMultipleShop::init_shop_header();
		}
	}
		
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:20
    * version: 3.7.3.2
    */
    public function initLogoAndFavicon()
	{
		if(Shop::$id_shop_owner ==0)return parent::initLogoAndFavicon();
		$mobile_device = $this->context->getMobileDevice();
		
		$seller_shop = new Shop(Shop::$id_shop_owner);
		if ($mobile_device && Configuration::get('PS_LOGO_MOBILE')) {
			$logo = $this->context->link->getMediaLink(_PS_IMG_.$this->get_logo_data_for_theme('PS_LOGO_MOBILE', $seller_shop->theme_name).'?'.Configuration::get('PS_IMG_UPDATE_TIME'));
		} else {
			$logo = $this->context->link->getMediaLink(_PS_IMG_.$this->get_logo_data_for_theme('PS_LOGO', $seller_shop->theme_name));
		}
		return array(
			'favicon_url'       => _PS_IMG_.Configuration::get('PS_FAVICON'),
			'logo_image_width'  => ($mobile_device == false ? $this->get_logo_data_for_theme('SHOP_LOGO_WIDTH', $seller_shop->theme_name)  : $this->get_logo_data_for_theme('SHOP_LOGO_MOBILE_WIDTH', $seller_shop->theme_name)),
			'logo_image_height' => ($mobile_device == false ? $this->get_logo_data_for_theme('SHOP_LOGO_HEIGHT', $seller_shop->theme_name) : $this->get_logo_data_for_theme('SHOP_LOGO_MOBILE_HEIGHT', $seller_shop->theme_name)),
			'logo_url'          => $logo
			);
	}
	
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:20
    * version: 3.7.3.2
    */
    private function get_logo_data_for_theme($default_key, $theme_name)
	{
		$logo = Configuration::get($default_key . '_' . $theme_name);
		if(!empty($logo))return $logo;
		return Configuration::get($default_key);		
	}
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:20
    * version: 3.7.3.2
    */
    protected function getBreadcrumbLinks()
	{
		if(!Module::isInstalled('agilemultipleseller') || !Module::isInstalled('agilemultipleshop') || Shop::$id_shop_owner ==0)
			return parent::getBreadcrumbLinks();
		
		include_once(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/SellerInfo.php");
		$main_shop = new Shop(Configuration::get('PS_SHOP_DEFAULT'));
		$breadcrumb = array();
		$breadcrumb['links'][] = array(
			'title' => $this->getTranslator()->trans('Main Store', array(), 'Shop.Theme'),
			'url' => $main_shop->getBaseURL(),
			);
		$sellerinfo = new SellerInfo(SellerInfo::getIdBSellerId(Shop::$id_shop_owner), Context::getContext()->language->id);
		$breadcrumb['links'][] = array(
			'title' => $sellerinfo->company,
			'url' => $this->context->link->getAgileSellerLink($sellerinfo->id_seller),
			);
		return $breadcrumb;
	}
}
