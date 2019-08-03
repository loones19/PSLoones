<?php
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrder;
use PrestaShop\PrestaShop\Adapter\AgileMultipleSeller\SellerProductSearchProvider;
use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
class IndexController extends ProductListingFrontControllerCore
{
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:43
    * version: 3.7.3.2
    */
    public $php_self = 'index';
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:43
    * version: 3.7.3.2
    */
    public $authRedirection = 'index';
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:43
    * version: 3.7.3.2
    */
    public $auth = false;
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:43
    * version: 3.7.3.2
    */
    public $display_column_left = true;
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:43
    * version: 3.7.3.2
    */
    public $display_column_right = false;
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:43
    * version: 3.7.3.2
    */
    protected $seller;
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:43
    * version: 3.7.3.2
    */
    protected $seller_info;
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:43
    * version: 3.7.3.2
    */
    protected $id_seller;
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:43
    * version: 3.7.3.2
    */
    public function init()
	{		
		if(Module::isInstalled('agilemultipleshop') && Shop::$id_shop_owner>0)
		{		
			$this->display_column_left = ((int)Configuration::get('ASP_HOME_COLUMN_LEFT') == 1);
			$this->display_column_right = ((int)Configuration::get('ASP_HOME_COLUMN_RIGHT') == 1);
		}
		
		parent::init();	
		$this->id_seller = (int)Tools::getValue('id_seller');
		if($this->id_seller <=0)$this->id_seller = Shop::$id_shop_owner;
		if ($this->id_seller && Module::isInstalled('agilemultipleshop'))
		{
			require_once(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/agilemultipleseller.php");
			require_once(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/SellerInfo.php");
			require_once(_PS_ROOT_DIR_ . "/modules/agilemultipleshop/agilemultipleshop.php");
			$this->seller = new Employee($this->id_seller);
			$this->seller_info = new SellerInfo(SellerInfo::getIdBSellerId($this->id_seller), $this->context->language->id);
		}
	}
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:43
    * version: 3.7.3.2
    */
    public function initContent()
	{
		parent::initContent();
		if($this->id_seller >0)
		{
			$HOOK_SELLER_RATINGS = '';
			if(Module::isInstalled('agilesellerratings'))
			{
				require_once(_PS_ROOT_DIR_ . "/modules/agilesellerratings/agilesellerratings.php");
				$rmodule = new AgileSellerRatings();
				$HOOK_SELLER_RATINGS = $rmodule->getAverageRating($this->id_seller,  AgileSellerRatings::RATING_TYPE_SELLER);
			}
			$categories = $this->getCategories();
			$id_selectedcategory = (int)Tools::getValue('id_selectedcategory');
			if($id_selectedcategory ==0)$id_selectedcategory = (int)Configuration::get('PS_ROOT_CATEGORY');			
			$this->context->smarty->assign(array(
				'id_seller' => (int)($this->seller->id),
				'seller' => $this->seller,
				'seller_info' => $this->seller_info,
				'HOOK_SELLER_RATINGS' => $HOOK_SELLER_RATINGS,
				'id_selectedcategory' => $id_selectedcategory,
				'categories' =>$categories
				));
			
						$this->doProductSearch('index.tpl', array());
		}
		else
		{
	        $this->context->smarty->assign(array(
				'HOOK_HOME' => Hook::exec('displayHome'),
				));
			$this->setTemplate('index');
		}
	}
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:43
    * version: 3.7.3.2
    */
    protected function getProductSearchQuery()
	{
		$query = new ProductSearchQuery();
		$query
			->setSortOrder(new SortOrder('product', Tools::getProductsOrder('by'), Tools::getProductsOrder('way')));
		;
		return $query;
	}
	
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:43
    * version: 3.7.3.2
    */
    public function getListingLabel()
	{
		return $this->trans('Virtual Shop of seller %s', array($this->seller_info->company), 'Shop.Theme.Catalog');
	}
	
	
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:43
    * version: 3.7.3.2
    */
    protected function getDefaultProductSearchProvider()
	{
		return new SellerProductSearchProvider(
			$this->getTranslator(),
			$this->id_seller,
			''
			);
	}
	
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:43
    * version: 3.7.3.2
    */
    private function getCategories()
	{
		$categories = Category::getCategories($this->context->cookie->id_lang, true, false);
		$categories = AgileHelper::getSortedFullnameCategory($categories);
		
		$sql = "SELECT DISTINCT id_category 
				FROM " . _DB_PREFIX_ . "product_owner po 
					LEFT JOIN " . _DB_PREFIX_ . "category_product cp on po.id_product = cp.id_product
				WHERE id_owner = " . Shop::$id_shop_owner;
		$ids = AgileHelper::retrieve_column_values(Db::getInstance()->ExecuteS($sql), "id_category");
		
		$ret = array();
		foreach($categories as $cat)
		{
			if(in_array((int)$cat['id_category'], $ids))$ret[] = $cat;
		}
		
		return $ret;
	}
	
	
}
