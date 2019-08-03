<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
class SearchController extends SearchControllerCore
{

	public function preProcess()
	{		
		if(!Module::isInstalled('agilesearchbyzipcode'))return parent::preProcess();
		if(Tools::getValue('is_zipcode_search') != 1)return parent::preProcess();

		$this->productSort();
		$this->n = abs((int)(Tools::getValue('n', Configuration::get('PS_PRODUCTS_PER_PAGE'))));
		$this->p = abs((int)(Tools::getValue('p', 1)));
        
        $centerLat = Tools::getValue('asbz_centerLat');
        $centerLng = Tools::getValue('asbz_centerLng');
        $search_scope = Configuration::get('ASBZ_SEARCH_SCOPE');
        
        include_once(dirname(__FILE__) . "/../../modules/agilesearchbyzipcode/ProdLocation.php");
        $products = ProdLocation::searchProducts(self::$cookie->id_lang,$centerLat,$centerLng,$search_scope,Tools::getValue('orderby'),Tools::getValue('orderway'));
		$nbProducts = count($products);
		$this->pagination($nbProducts);
		self::$smarty->assign(array(
		'search_products' => $products,
		'nbProducts' => $nbProducts,
		'search_tag' => '',
		'search_query' => Tools::getValue('asbz_zipcode'),
		'ref' =>'',
		'search_zipcode' => Tools::getValue('asbz_zipcode'),
		'homeSize' => Image::getSize('home')));
		self::$smarty->assign('add_prod_display', Configuration::get('PS_ATTRIBUTE_CATEGORY_DISPLAY'));
	}
		
}

