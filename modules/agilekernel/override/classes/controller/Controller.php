<?php
///-build_id: 2018051409.414
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
abstract class Controller extends ControllerCore
{
	public function init()
	{
		parent::init();
		
		if($this->controller_type != "admin" && $this->controller_type != "moduleadmin")
		{
			smartyRegisterFunction($this->context->smarty, 'function', 'displayPrice', array('Tools', 'displayPriceSmarty'));
			if(Module::isInstalled('agileproductreviews'))smartyRegisterFunction($this->context->smarty, 'function', 'getProductRatingSummary', array('Product', 'getProductRatingSummary')); 
		}			
		
		$this->context->smarty->assign(array(
			'base_dir_ssl' => $this->context->shop->getBaseURL(true, true)
			,'base_dir' => $this->context->shop->getBaseURL(false, true)
			,'shop_name' => $this->context->shop->name
			,'priceDisplay' => Product::getTaxCalculationMethod((int)$this->context->cookie->id_customer)
			,'navigationPipe' =>(Configuration::get('PS_NAVIGATION_PIPE') ? Configuration::get('PS_NAVIGATION_PIPE') : '>')
			,'link' => $this->context->link
			));
			
		Media::addJsDef(array(
			'base_dir_ssl' => $this->context->shop->getBaseURL(true, true)
			,'base_dir' => $this->context->shop->getBaseURL(false, true)
			,'baseDir' => $this->context->shop->getBaseURL(false, true)
			,'baseAdminDir' => __PS_BASE_URI__.Configuration::get('AGILE_MS_ADMIN_FOLDER_NAME').'/'
		));			
	}
}
