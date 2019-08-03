<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
class FrontController extends FrontControllerCore
{
	public function init()
	{
		parent::init();
		if (session_status() == PHP_SESSION_NONE)@session_start();
		
		$_SESSION['id_customer'] = Context::getContext()->customer->id;

	}
		
	public function initHeader()
	{
		parent::initHeader();
		
		if(Module::isInstalled('agilemultipleshop'))
		{
			include_once(_PS_ROOT_DIR_  . "/modules/agilemultipleshop/agilemultipleshop.php");
			AgileMultipleShop::init_shop_header();
		}
	}
		
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
	
	private function get_logo_data_for_theme($default_key, $theme_name)
	{
		$logo = Configuration::get($default_key . '_' . $theme_name);
		if(!empty($logo))return $logo;
		return Configuration::get($default_key);		
	}

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
