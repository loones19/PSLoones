<?php
class Category extends CategoryCore
{
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:20
    * version: 3.7.3.2
    */
    public static function updateFromShop($categories, $id_shop)
	{
				return true;
	}
	
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:20
    * version: 3.7.3.2
    */
    public static function getRootCategory($id_lang = null, Shop $shop = null)
	{
		if(!Module::isInstalled('agilemultipleseller'))return parent::getRootCategory($id_lang, $shop);
		$id_seller_home = AgileSellerManager::get_current_logged_seller_home_category_id();
		if((int)$id_seller_home <=0) 	return parent::getRootCategory($id_lang, $shop);
		if((int)$id_lang <=0)$id_lang = Context::getContext()->language->id;			
		$category = new Category($id_seller_home, $id_lang);
		if(!Validate::isLoadedObject($category))return parent::getRootCategory($id_lang, $shop);
		return $category;
	}
		
    	static /*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:20
    * version: 3.7.3.2
    */
    public function getCategories($id_lang = false, $active = true, $order = true, $sql_filter = '', $sql_sort = '',$sql_limit = '')
	{
		$context = Context::getContext();
	    
	    if(!Module::isInstalled('agilemultipleseller'))return parent::getCategories($id_lang, $active, $order, $sql_filter, $sql_sort,$sql_limit);		if(intval($context->cookie->id_employee)==0)return parent::getCategories($id_lang, $active, $order, $sql_filter, $sql_sort,$sql_limit); 		if(intval($context->cookie->profile) != intval(Configuration::get('AGILE_MS_PROFILE_ID')))return parent::getCategories($id_lang, $active, $order, $sql_filter, $sql_sort,$sql_limit); 
	    
	 	if (!Validate::isBool($active))
	 		die(Tools::displayError());
		$sql = '
		SELECT *
		FROM `'._DB_PREFIX_.'category` c
		LEFT JOIN `'._DB_PREFIX_.'category_owner` co ON c.`id_category` = co.`id_category`
		LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON c.`id_category` = cl.`id_category`
		WHERE 1 '.$sql_filter.' '.($id_lang ? 'AND `id_lang` = '.(int)($id_lang) : '').'
		'.($active ? 'AND `active` = 1' : '');
		if(_PS_VERSION_ > '1.5' && Shop::$id_shop_owner>0)
			$sql .= ' AND co.id_owner IN (0,'  . Shop::$id_shop_owner . ')';
		
		
		$sql .= (!$id_lang ? 'GROUP BY c.id_category' : '').'
		'.($sql_sort != '' ? $sql_sort : 'ORDER BY c.`level_depth` ASC, c.`position` ASC').'
		'.($sql_limit != '' ? $sql_limit : '');
		        
		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS($sql);
		if (!$order)
			return $result;
		$categories = array();
		foreach ($result AS $row)
			$categories[$row['id_parent']][$row['id_category']]['infos'] = $row;
		return $categories;
	}
		
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:20
    * version: 3.7.3.2
    */
    public function getProducts($id_lang, $p, $n, $orderBy = NULL, $orderWay = NULL, $getTotal = false, $active = true, $random = false, $randomNumberProducts = 1, $checkAccess = true, Context $context = null)
	{				
		$context = Context::getContext();
		if (!$checkAccess OR !$this->checkAccess($context->customer->id))
			return array();
		if(!$context)$context = Context::getContext();
		$front = true;
		if (!in_array($context->controller->controller_type, array('front', 'modulefront')))
			$front = false;
		
				if(Module::isInstalled('agilemembership') AND $this->id == Configuration::get('AGILE_MEMBERSHIP_CID')) 
            return parent::getProducts($id_lang, $p, $n, $orderBy, $orderWay, $getTotal, $active, $random, $randomNumberProducts, $checkAccess);
				if(Module::isInstalled('agileprepaidcredit') AND $this->id == Configuration::getGlobalValue('AGILE_PCREDIT_CID')) 
            return parent::getProducts($id_lang, $p, $n, $orderBy, $orderWay, $getTotal, $active, $random, $randomNumberProducts, $checkAccess);
				if(Module::isInstalled('agilesellerlistoptions') AND $this->id == Configuration::get('ASLO_CATEGORY_ID')) 
            return parent::getProducts($id_lang, $p, $n, $orderBy, $orderWay, $getTotal, $active, $random, $randomNumberProducts, $checkAccess);
		
		$agile_sql_parts = AgileSellerManager::getAdditionalSqlForProducts("p");
		if(empty($agile_sql_parts['selects']) AND  empty($agile_sql_parts['joins']) AND empty($agile_sql_parts['wheres']))
			return parent::getProducts($id_lang, $p, $n, $orderBy, $orderWay, $getTotal, $active, $random, $randomNumberProducts, $checkAccess);
		
	    if(Module::isInstalled('agilesellerlistoptions'))
		{
			require_once(_PS_ROOT_DIR_ . "/modules/agilesellerlistoptions/agilesellerlistoptions.php");
			if($this->id <= 1 OR $this->id == 2 OR $this->id == (int)Configuration::get('PS_HOME_CATEGORY'))
				return AgileSellerListOptions::get_home_products($id_lang, $p, $n);	
			
			if(empty($orderBy) || $orderBy == 'position')$orderBy = 'position2';
		}
        		
		if ($p < 1) $p = 1;
		if (empty($orderBy))
			$orderBy = 'position';
		else
						$orderBy = strtolower($orderBy);
		if (empty($orderWay))
			$orderWay = 'ASC';
		if ($orderBy == 'id_product' OR	$orderBy == 'date_add' OR 	$orderBy == 'date_upd')
			$orderByPrefix = 'p';
		elseif ($orderBy == 'name')
			$orderByPrefix = 'pl';
		elseif ($orderBy == 'manufacturer')
		{
			$orderByPrefix = 'm';
			$orderBy = 'name';
		}
		elseif ($orderBy == 'position')
			$orderByPrefix = 'cp';
		if ($orderBy == 'price')
			$orderBy = 'orderprice';
		
		if (!Validate::isBool($active) OR !Validate::isOrderBy($orderBy) OR !Validate::isOrderWay($orderWay))
			die (Tools::displayError());
		$id_supplier = (int)(Tools::getValue('id_supplier'));
				if ($getTotal)
		{
			$sql = '
			SELECT COUNT(cp.`id_product`) AS total
			FROM `'._DB_PREFIX_.'product` p
			'.Shop::addSqlAssociation('product', 'p').'
			LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON p.`id_product` = cp.`id_product`
			    '. $agile_sql_parts['joins']. '
			WHERE cp.`id_category` = '.(int)($this->id).
				($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '').
				($active ? ' AND product_shop.`active` = 1' : '').				
				$agile_sql_parts['wheres']. 
				($id_supplier ? 'AND p.id_supplier = '.(int)($id_supplier) : '');
 
			$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($sql);
			return isset($result) ? $result['total'] : 0;
		}
		$sql = 'SELECT p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, MAX(product_attribute_shop.id_product_attribute) id_product_attribute, product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity, pl.`description`, pl.`description_short`, pl.`available_now`,
					pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, MAX(image_shop.`id_image`) id_image,
					il.`legend`, m.`name` AS manufacturer_name, cl.`name` AS category_default,
					DATEDIFF(product_shop.`date_add`, DATE_SUB(NOW(),
					INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).'
						DAY)) > 0 AS new, product_shop.price AS orderprice
					' .$agile_sql_parts['selects']. '	
				FROM `'._DB_PREFIX_.'category_product` cp
				LEFT JOIN `'._DB_PREFIX_.'product` p
					ON p.`id_product` = cp.`id_product`
				'.Shop::addSqlAssociation('product', 'p').'
				LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa
				ON (p.`id_product` = pa.`id_product`)
				'.Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.`default_on` = 1').'
				'.Product::sqlStock('p', 'product_attribute_shop', false, $context->shop).'
				LEFT JOIN `'._DB_PREFIX_.'category_lang` cl
					ON (product_shop.`id_category_default` = cl.`id_category`
					AND cl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('cl').')
				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
					ON (p.`id_product` = pl.`id_product`
					AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').')
				LEFT JOIN `'._DB_PREFIX_.'image` i
					ON (i.`id_product` = p.`id_product`)'.
				Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'
				LEFT JOIN `'._DB_PREFIX_.'image_lang` il
					ON (image_shop.`id_image` = il.`id_image`
					AND il.`id_lang` = '.(int)$id_lang.')
				LEFT JOIN `'._DB_PREFIX_.'manufacturer` m
					ON m.`id_manufacturer` = p.`id_manufacturer`
			    '. $agile_sql_parts['joins']. '			
				WHERE product_shop.`id_shop` = '.(int)$context->shop->id.'
				    '. $agile_sql_parts['wheres']. '
					AND cp.`id_category` = '.(int)$this->id
				.($active ? ' AND product_shop.`active` = 1' : '')
				.($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '')
				.($id_supplier ? ' AND p.id_supplier = '.(int)$id_supplier : '')
				.' GROUP BY product_shop.id_product';
		if ($random === true)
		{
			$sql .= ' ORDER BY RAND()';
			$sql .= ' LIMIT 0, '.(int)($randomNumberProducts);
		}
		else
		{
			$sql .= ' ORDER BY '.(isset($orderByPrefix) ? $orderByPrefix.'.' : '').'`'.pSQL($orderBy).'` '.pSQL($orderWay).'
			LIMIT '.(((int)($p) - 1) * (int)($n)).','.(int)($n);
											}
		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS($sql);
		if (!$result)
			return array();
						$seller = array();
		$id_seller = array();
		$link_seller = array();
		foreach($result as $row)
		{
    		$pid = $row['id_product'];
			$seller[$pid] = isset($row['seller'])?$row['seller'] : '';
			$id_seller[$pid]= isset($row['id_seller'])? intval($row['id_seller']) : 0;
			$link_seller[$pid]= isset($row['has_sellerlink'])? $row['has_sellerlink'] : '';
		}
			
		$resultsArray=Product::getProductsProperties((int)$id_lang, $result);
		for($idx=0;$idx<count($resultsArray); $idx++)
		{
		      $pid = $resultsArray[$idx]['id_product'];
		      $resultsArray[$idx]['seller'] = $seller[$pid];
		      $resultsArray[$idx]['id_seller'] = $id_seller[$pid];
		      $resultsArray[$idx]['has_sellerlink'] = $link_seller[$pid];
		}
		$resultsArray = AgileSellerManager::prepareSellerRattingInfo($resultsArray);
		return $resultsArray;
	}
	
	
				/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:20
    * version: 3.7.3.2
    */
    public static function getChildrenWithNbSelectedSubCat($id_parent, $selectedCat,  $id_lang, Shop $shop = null, $use_shop_context = true)
	{
		$context = Context::getContext();
		if(!Module::isInstalled('agilemultipleseller')) 
			return parent::getChildrenWithNbSelectedSubCat($id_parent, $selectedCat,  $id_lang, $shop, $use_shop_context);
		$isSeller = (intval($context->cookie->profile) == Configuration::get('AGILE_MS_PROFILE_ID') OR intval($context->cookie->profile) == 0);
		if(!$isSeller)
			return parent::getChildrenWithNbSelectedSubCat($id_parent, $selectedCat,  $id_lang, $shop, $use_shop_context);
		require_once(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/agilemultipleseller.php");
		require_once(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/SellerInfo.php");
		if(intval($context->cookie->profile) > 0)
		{
						$id_seller = $context->cookie->id_employee;
		}	
		else
		{
						$sellerinfo = new SellerInfo(SellerInfo::getIdByCustomerId($context->customer->id));
			$id_seller = $sellerinfo->id_seller;
		}
		$exclude = AgileMultipleSeller::getSpecialCatrgoryIds();
		
				$selectedCat = explode(',', str_replace(' ', '', $selectedCat));		
		return Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS('
		SELECT c.`id_category`, c.`level_depth`, cl.`name`, IF((
			SELECT COUNT(*)
			FROM `'._DB_PREFIX_.'category` c2
			WHERE c2.`id_parent` = c.`id_category`
		) > 0, 1, 0) AS has_children, '.($selectedCat ? '(
			SELECT count(c3.`id_category`)
			FROM `'._DB_PREFIX_.'category` c3
			WHERE c3.`nleft` > c.`nleft`
			AND c3.`nright` < c.`nright`
			AND c3.`id_category`  IN ('.implode(',', array_map('intval', $selectedCat)).')
		)' : '0').' AS nbSelectedSubCat
		FROM `'._DB_PREFIX_.'category` c
		LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON c.`id_category` = cl.`id_category`
		LEFT JOIN `'._DB_PREFIX_.'category_owner` co ON c.id_category=co.id_category
		WHERE c.`active` = 1 AND `id_lang` = '.(int)($id_lang).'
		AND c.`id_parent` = '.(int)($id_parent).'
		' . (empty($exclude)?'': 'AND c.id_category NOT IN (' . $exclude . ')' ) . '
		AND (IFNULL(co.id_owner,0) = ' . $id_seller . ' OR IFNULL(co.id_owner,0)=0)
		ORDER BY `position` ASC');
	}
		/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:20
    * version: 3.7.3.2
    */
    public static function getNestedCategories($root_category = null, $id_lang = false, $active = true, $groups = null,
		$use_shop_restriction = true, $sql_filter = '', $sql_sort = '', $sql_limit = '')
	{
		$context = Context::getContext();
		if(!Module::isInstalled('agilemultipleseller')) 
			return parent::getNestedCategories($root_category, $id_lang,  $active, $groups, $use_shop_restriction,$sql_filter,$sql_sort,$sql_limit);
		if (isset($root_category) && !Validate::isInt($root_category))
			die(Tools::displayError());
		if (!Validate::isBool($active))
			die(Tools::displayError());
		if (isset($groups) && Group::isFeatureActive() && !is_array($groups))
			$groups = (array)$groups;
		if(intval($context->cookie->profile) == 0 ) 
		{ 
						$cache_id = 'Category::getNestedCategories_'.md5((int)$root_category.(int)$id_lang.(int)$active.(int)$active
				.(isset($groups) && Group::isFeatureActive() ? implode('', $groups) : ''));
			
								} 
		else 
		{  
						$cache_id = 'Category::getNestedCategories_'.md5((int)($context->cookie->id_customer).(int)$root_category.(int)$id_lang.(int)$active.(int)$active
				.(isset($groups) && Group::isFeatureActive() ? implode('', $groups) : ''));
		}
		if (!Cache::isStored($cache_id))
		{
			$result = Db::getInstance()->executeS('
				SELECT c.*, cl.*
				FROM `'._DB_PREFIX_.'category` c
				'.($use_shop_restriction ? Shop::addSqlAssociation('category', 'c') : '').'
				LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON c.`id_category` = cl.`id_category`'.Shop::addSqlRestrictionOnLang('cl').'
				'.(isset($groups) && Group::isFeatureActive() ? 'LEFT JOIN `'._DB_PREFIX_.'category_group` cg ON c.`id_category` = cg.`id_category`' : '').'
				'.(isset($root_category) ? 'RIGHT JOIN `'._DB_PREFIX_.'category` c2 ON c2.`id_category` = '.(int)$root_category.' AND c.`nleft` >= c2.`nleft` AND c.`nright` <= c2.`nright`' : '').'
				WHERE 1 '.$sql_filter.' '.($id_lang ? 'AND `id_lang` = '.(int)$id_lang : '').'
				'.($active ? ' AND c.`active` = 1' : '').'
				'.(isset($groups) && Group::isFeatureActive() ? ' AND cg.`id_group` IN ('.implode(',', $groups).')' : '').'
				'.(!$id_lang || (isset($groups) && Group::isFeatureActive()) ? ' GROUP BY c.`id_category`' : '').'
				'.($sql_sort != '' ? $sql_sort : ' ORDER BY c.`level_depth` ASC').'
				'.($sql_sort == '' && $use_shop_restriction ? ', category_shop.`position` ASC' : '').'
				'.($sql_limit != '' ? $sql_limit : '')
			);
			$categories = array();
			$buff = array();
			if (!isset($root_category))
				$root_category = 1;
			foreach ($result as $row)
			{
				$current = &$buff[$row['id_category']];
				$current = $row;
				if ($row['id_category'] == $root_category)
					$categories[$row['id_category']] = &$current;
				else
					$buff[$row['id_parent']]['children'][$row['id_category']] = &$current;
			}
			Cache::store($cache_id, $categories);
		}
		return Cache::retrieve($cache_id);
	}
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:20
    * version: 3.7.3.2
    */
    public static function getChildren_4topmenu($id_parent, $id_lang, $active = true, $id_shop = false)
	{
		if (!Validate::isBool($active))
			die(Tools::displayError());
		$cache_id = 'Category::getChildren_4topmenu_'.(int)$id_parent.'-'.(int)$id_lang.'-'.(bool)$active.'-'.(int)$id_shop;
		if (!Cache::isStored($cache_id))
		{
			$query = 'SELECT c.`id_category`, cl.`name`, cl.`link_rewrite`, category_shop.`id_shop`
			FROM `'._DB_PREFIX_.'category` c
			LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (c.`id_category` = cl.`id_category`'.Shop::addSqlRestrictionOnLang('cl').')
			'. Shop::addSqlAssociation_4topmenu('category', 'c').'
			WHERE `id_lang` = '.(int)$id_lang.'
			AND c.`id_parent` = '.(int)$id_parent.'
			'.($active ? 'AND `active` = 1' : '').'
			GROUP BY c.`id_category`
			ORDER BY category_shop.`position` ASC';
			$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
			Cache::store($cache_id, $result);
		}
		return Cache::retrieve($cache_id);
	}
	
}
