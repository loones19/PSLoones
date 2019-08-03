<?php
class SearchController extends SearchControllerCore
{
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:44
    * version: 3.7.3.2
    */
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
