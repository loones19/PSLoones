<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.

class AgileMultipleSellerSellerProductModuleFrontController extends AgileModuleFrontController
{
	protected $product_menus = array();
	protected $max_image_size = null;

	public $auth = true;
	public $ssl = true;
	protected $languages;
	protected $id_language;
	protected $product_menu;
	protected $object;
	
	public function __construct()
	{
		parent::__construct();
		
        $this->table = 'product';
        $this->identifier = 'id_product';
        $this->className = 'Product';
    }
	
	public function initContent()
	{
		parent::initContent();
		
		self::$smarty->assign(array(
			'seller_tab_id' => 3
			,'id_product'=> Tools::getValue("id_product")
			));
	
		
		$this->setTemplate('module:agilemultipleseller/views/templates/front/sellerproduct.tpl');
	}
	
}

