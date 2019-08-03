<?php
class Product extends ProductCore
{
	/** @var string Transporte */
	public $id_trans_op1;
	public $id_trans_op2;
	public $trans_op2_n1;
    public $trans_op2_n2;
        
    /*
	* module: agilemultipleseller
    * date: 2019-02-22 17:35:37
    * version: 3.7.3.2
	*/
	public $id_tax_rules_group = 2;   //LOONES
	public $trans_op2_n3;
	public $id_tipo;
	
	public static $definition = array(
        'table' => 'product',
        'primary' => 'id_product',
        'multilang' => true,
        'multilang_shop' => true,
        'fields' => array(
            'id_shop_default' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'id_manufacturer' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'id_supplier' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'reference' => array('type' => self::TYPE_STRING, 'validate' => 'isReference', 'size' => 64),
            'supplier_reference' => array('type' => self::TYPE_STRING, 'validate' => 'isReference', 'size' => 64),
            'location' => array('type' => self::TYPE_STRING, 'validate' => 'isReference', 'size' => 64),
            'width' => array('type' => self::TYPE_FLOAT, 'validate' => 'isUnsignedFloat'),
            'height' => array('type' => self::TYPE_FLOAT, 'validate' => 'isUnsignedFloat'),
            'depth' => array('type' => self::TYPE_FLOAT, 'validate' => 'isUnsignedFloat'),
			'weight' => array('type' => self::TYPE_FLOAT, 'validate' => 'isUnsignedFloat'),
			'id_trans_op1' 	=> array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
			'id_trans_op2' 	=> array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
			'trans_op2_n1' 	=> array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
			'trans_op2_n2' 	=> array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
			'trans_op2_n3' 	=> array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
			'id_tipo' 		=> array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'quantity_discount' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'ean13' => array('type' => self::TYPE_STRING, 'validate' => 'isEan13', 'size' => 13),
            'isbn' => array('type' => self::TYPE_STRING, 'validate' => 'isIsbn', 'size' => 32),
            'upc' => array('type' => self::TYPE_STRING, 'validate' => 'isUpc', 'size' => 12),
            'cache_is_pack' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'cache_has_attachments' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'is_virtual' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'state' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'additional_delivery_times' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'delivery_in_stock' => array(
                'type' => self::TYPE_STRING,
                'lang' => true,
                'validate' => 'isGenericName',
                'size' => 255,
            ),
            'delivery_out_stock' => array(
                'type' => self::TYPE_STRING,
                'lang' => true,
                'validate' => 'isGenericName',
                'size' => 255,
            ),
            'id_category_default' => array('type' => self::TYPE_INT, 'shop' => true, 'validate' => 'isUnsignedId'),
            'id_tax_rules_group' => array('type' => self::TYPE_INT, 'shop' => true, 'validate' => 'isUnsignedId'),
            'on_sale' => array('type' => self::TYPE_BOOL, 'shop' => true, 'validate' => 'isBool'),
            'online_only' => array('type' => self::TYPE_BOOL, 'shop' => true, 'validate' => 'isBool'),
            'ecotax' => array('type' => self::TYPE_FLOAT, 'shop' => true, 'validate' => 'isPrice'),
            'minimal_quantity' => array('type' => self::TYPE_INT, 'shop' => true, 'validate' => 'isUnsignedInt'),
            'low_stock_threshold' => array('type' => self::TYPE_INT, 'shop' => true, 'allow_null' => true, 'validate' => 'isInt'),
            'low_stock_alert' => array('type' => self::TYPE_BOOL, 'shop' => true, 'allow_null' => true, 'validate' => 'isBool'),
            'price' => array('type' => self::TYPE_FLOAT, 'shop' => true, 'validate' => 'isPrice', 'required' => true),
            'wholesale_price' => array('type' => self::TYPE_FLOAT, 'shop' => true, 'validate' => 'isPrice'),
            'unity' => array('type' => self::TYPE_STRING, 'shop' => true, 'validate' => 'isString'),
            'unit_price_ratio' => array('type' => self::TYPE_FLOAT, 'shop' => true),
            'additional_shipping_cost' => array('type' => self::TYPE_FLOAT, 'shop' => true, 'validate' => 'isPrice'),
            'customizable' => array('type' => self::TYPE_INT, 'shop' => true, 'validate' => 'isUnsignedInt'),
            'text_fields' => array('type' => self::TYPE_INT, 'shop' => true, 'validate' => 'isUnsignedInt'),
            'uploadable_files' => array('type' => self::TYPE_INT, 'shop' => true, 'validate' => 'isUnsignedInt'),
            'active' => array('type' => self::TYPE_BOOL, 'shop' => true, 'validate' => 'isBool'),
            'redirect_type' => array('type' => self::TYPE_STRING, 'shop' => true, 'validate' => 'isString'),
            'id_type_redirected' => array('type' => self::TYPE_INT, 'shop' => true, 'validate' => 'isUnsignedId'),
            'available_for_order' => array('type' => self::TYPE_BOOL, 'shop' => true, 'validate' => 'isBool'),
            'available_date' => array('type' => self::TYPE_DATE, 'shop' => true, 'validate' => 'isDateFormat'),
            'show_condition' => array('type' => self::TYPE_BOOL, 'shop' => true, 'validate' => 'isBool'),
            'condition' => array('type' => self::TYPE_STRING, 'shop' => true, 'validate' => 'isGenericName', 'values' => array('new', 'used', 'refurbished'), 'default' => 'new'),
            'show_price' => array('type' => self::TYPE_BOOL, 'shop' => true, 'validate' => 'isBool'),
            'indexed' => array('type' => self::TYPE_BOOL, 'shop' => true, 'validate' => 'isBool'),
            'visibility' => array('type' => self::TYPE_STRING, 'shop' => true, 'validate' => 'isProductVisibility', 'values' => array('both', 'catalog', 'search', 'none'), 'default' => 'both'),
            'cache_default_attribute' => array('type' => self::TYPE_INT, 'shop' => true),
            'advanced_stock_management' => array('type' => self::TYPE_BOOL, 'shop' => true, 'validate' => 'isBool'),
            'date_add' => array('type' => self::TYPE_DATE, 'shop' => true, 'validate' => 'isDate'),
            'date_upd' => array('type' => self::TYPE_DATE, 'shop' => true, 'validate' => 'isDate'),
            'pack_stock_type' => array('type' => self::TYPE_INT, 'shop' => true, 'validate' => 'isUnsignedInt'),
            'meta_description' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'size' => 512),
            'meta_keywords' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'size' => 255),
            'meta_title' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'size' => 255),
            'link_rewrite' => array(
                'type' => self::TYPE_STRING,
                'lang' => true,
                'validate' => 'isLinkRewrite',
                'required' => false,
                'size' => 128,
                'ws_modifier' => array(
                    'http_method' => WebserviceRequest::HTTP_POST,
                    'modifier' => 'modifierWsLinkRewrite',
                ),
            ),
            'name' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCatalogName', 'required' => false, 'size' => 128),
            'description' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'),
            'description_short' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'),
            'available_now' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'size' => 255),
            'available_later' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'IsGenericName', 'size' => 255),
        ),
        'associations' => array(
            'manufacturer' => array('type' => self::HAS_ONE),
            'supplier' => array('type' => self::HAS_ONE),
            'default_category' => array('type' => self::HAS_ONE, 'field' => 'id_category_default', 'object' => 'Category'),
            'tax_rules_group' => array('type' => self::HAS_ONE),
            'categories' => array('type' => self::HAS_MANY, 'field' => 'id_category', 'object' => 'Category', 'association' => 'category_product'),
            'stock_availables' => array('type' => self::HAS_MANY, 'field' => 'id_stock_available', 'object' => 'StockAvailable', 'association' => 'stock_availables'),
        ),
	);
	
    public function setCarriers($carrier_list)
	{
		if(Module::isInstalled('agilesellershipping') AND !empty($carrier_list))
		{
			$carrier = new Carrier(intval(Configuration::get('AGILE_SS_CARRIER_ID')));
						$id_carrier = $carrier->id_reference;
			if(!in_array($id_carrier, $carrier_list))
			{
				$carrier_list[] = $id_carrier;
			}
		}
		parent::setCarriers($carrier_list);
	}	
	
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:27
    * version: 3.7.3.2
    */
    public function getDefaultCategory()	
	{		
		$default_category = Db::getInstance()->getValue('
					SELECT product_shop.`id_category_default`
					FROM `'._DB_PREFIX_.'product` p
					'.Shop::addSqlAssociation('product', 'p').'
					WHERE p.`id_product` = '.(int)$this->id);
		if (!$default_category)
			return Context::getContext()->shop->id_category;
		else
			return $default_category;
	}	
	
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:27
    * version: 3.7.3.2
    */
    public static function getProducts($id_lang, $start, $limit, $orderBy, $orderWay, $id_category = false,    $only_active = false, Context $context = null)
	{
		$agile_sql_parts = AgileSellerManager::getAdditionalSqlForProducts("p", true);
		if(Module::isInstalled('agilesellerlistoptions') &&  empty($orderby))$orderby = 'position2';
		if(empty($agile_sql_parts['joins']) OR empty($agile_sql_parts['wheres']))
			parent::getProducts($id_lang, $start, $limit, $orderBy, $orderWay, $id_category, $only_active);
		
		if (!Validate::isOrderBy($orderBy) OR !Validate::isOrderWay($orderWay))
			die (Tools::displayError());
		if ($orderBy == 'id_product' OR	$orderBy == 'price' OR	$orderBy == 'date_add')
			$orderByPrefix = 'p';
		elseif ($orderBy == 'name')
			$orderByPrefix = 'pl';
		elseif ($orderBy == 'position')
			$orderByPrefix = 'c';
		$sql = '
		SELECT p.*, pl.* , t.`rate` AS tax_rate, m.`name` AS manufacturer_name, s.`name` AS supplier_name
		' . $agile_sql_parts['selects'] . '
		FROM `'._DB_PREFIX_.'product` p
		' . $agile_sql_parts['joins'] . '
		LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product`)
		LEFT JOIN `'._DB_PREFIX_.'tax_rule` tr ON (p.`id_tax_rules_group` = tr.`id_tax_rules_group`
		   AND tr.`id_country` = '.(_PS_VERSION_ > '1.5' ?  (int)Context::getContext()->country->id : (int)Country::getDefaultCountryId()) .'
		   AND tr.`id_state` = 0)
	    LEFT JOIN `'._DB_PREFIX_.'tax` t ON (t.`id_tax` = tr.`id_tax`)
		LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`)
		LEFT JOIN `'._DB_PREFIX_.'supplier` s ON (s.`id_supplier` = p.`id_supplier`)'.
			($id_category ? 'LEFT JOIN `'._DB_PREFIX_.'category_product` c ON (c.`id_product` = p.`id_product`)' : '').'
		WHERE pl.`id_lang` = '.(int)($id_lang).
			($id_category ? ' AND c.`id_category` = '.(int)($id_category) : '').
			$agile_sql_parts['wheres'] .
			($only_active ? ' AND p.`active` = 1' : '').'
		ORDER BY '.(isset($orderByPrefix) ? pSQL($orderByPrefix).'.' : '').'`'.pSQL($orderBy).'` '.pSQL($orderWay).
			($limit > 0 ? ' LIMIT '.(int)($start).','.(int)($limit) : '');
		$finalResults = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS($sql);
		
		if ($orderBy == 'price')
			Tools::orderbyPrice($finalResults,$orderWay);
		
		$finalResults = AgileSellerManager::prepareSellerRattingInfo($finalResults);
		return $finalResults;
		
	}
	
	
    /*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:27
    * version: 3.7.3.2
    */
    public static function getNewProducts($id_lang, $page_number = 0, $nb_products = 10, $count = false, $order_by = null, $order_way = null, Context $context = null)
    {
		$agile_sql_parts = AgileSellerManager::getAdditionalSqlForProducts("p", true);
		if(Module::isInstalled('agilesellerlistoptions') &&  empty($orderby))$orderby = 'position2';
	
		if(empty($agile_sql_parts['joins']) OR empty($agile_sql_parts['wheres']))
			parent::getNewProducts($id_lang, $pageNumber, $nbProducts, $count, $orderBy, $orderWay);
	
        $now = date('Y-m-d') . ' 00:00:00';
        if (!$context) {
            $context = Context::getContext();
        }
        $front = true;
        if (!in_array($context->controller->controller_type, array('front', 'modulefront'))) {
            $front = false;
        }
        if ($page_number < 1) {
            $page_number = 1;
        }
        if ($nb_products < 1) {
            $nb_products = 10;
        }
        if (empty($order_by) || $order_by == 'position') {
            $order_by = 'date_add';
        }
        if (empty($order_way)) {
            $order_way = 'DESC';
        }
        if ($order_by == 'id_product' || $order_by == 'price' || $order_by == 'date_add' || $order_by == 'date_upd') {
            $order_by_prefix = 'product_shop';
        } elseif ($order_by == 'name') {
            $order_by_prefix = 'pl';
        }
        if (!Validate::isOrderBy($order_by) || !Validate::isOrderWay($order_way)) {
            die(Tools::displayError());
        }
        $sql_groups = '';
        if (Group::isFeatureActive()) {
            $groups = FrontController::getCurrentCustomerGroups();
            $sql_groups = ' AND EXISTS(SELECT 1 FROM `'._DB_PREFIX_.'category_product` cp
				JOIN `'._DB_PREFIX_.'category_group` cg ON (cp.id_category = cg.id_category AND cg.`id_group` '.(count($groups) ? 'IN ('.implode(',', $groups).')' : '= '.(int)Configuration::get('PS_UNIDENTIFIED_GROUP')).')
				WHERE cp.`id_product` = p.`id_product`)';
        }
        if (strpos($order_by, '.') > 0) {
            $order_by = explode('.', $order_by);
            $order_by_prefix = $order_by[0];
            $order_by = $order_by[1];
        }
        $nb_days_new_product = (int) Configuration::get('PS_NB_DAYS_NEW_PRODUCT');
        if ($count) {
            $sql = 'SELECT COUNT(p.`id_product`) AS nb
					FROM `'._DB_PREFIX_.'product` p
					'. $agile_sql_parts['joins']. '
					'.Shop::addSqlAssociation('product', 'p').'
					WHERE product_shop.`active` = 1
					AND product_shop.`date_add` > "'.date('Y-m-d', strtotime('-'.$nb_days_new_product.' DAY')).'"
					' . $agile_sql_parts['wheres']. '
					'.($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '').'
					'.$sql_groups;
            return (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
        }
        $sql = new DbQuery();
        $sql->select(
            'p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, pl.`description`, pl.`description_short`, pl.`link_rewrite`, pl.`meta_description`,
			pl.`meta_keywords`, pl.`meta_title`, pl.`name`, pl.`available_now`, pl.`available_later`, image_shop.`id_image` id_image, il.`legend`, m.`name` AS manufacturer_name,
			(DATEDIFF(product_shop.`date_add`,
				DATE_SUB(
					"'.$now.'",
					INTERVAL '.$nb_days_new_product.' DAY
				)
			) > 0) as new
			' . $agile_sql_parts['selects'] . '			
			'
        );
        $sql->from('product', 'p');
        $sql->join(Shop::addSqlAssociation('product', 'p'));
        $sql->leftJoin('product_lang', 'pl', '
			p.`id_product` = pl.`id_product`
			AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl')
        );
        $sql->leftJoin('image_shop', 'image_shop', 'image_shop.`id_product` = p.`id_product` AND image_shop.cover=1 AND image_shop.id_shop='.(int)$context->shop->id);
        $sql->leftJoin('image_lang', 'il', 'image_shop.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang);
        $sql->leftJoin('manufacturer', 'm', 'm.`id_manufacturer` = p.`id_manufacturer`');
		$sql->join($agile_sql_parts['joins']);
		
        $sql->where('product_shop.`active` = 1
			' . $agile_sql_parts['wheres']. '		
		');
		if ($front) {
			$sql->where('product_shop.`visibility` IN ("both", "catalog")');
		}
		$sql->where('product_shop.`date_add` > "'.date('Y-m-d', strtotime('-'.$nb_days_new_product.' DAY')).'"');
		if (Group::isFeatureActive()) {
			$groups = FrontController::getCurrentCustomerGroups();
			$sql->where('EXISTS(SELECT 1 FROM `'._DB_PREFIX_.'category_product` cp
				JOIN `'._DB_PREFIX_.'category_group` cg ON (cp.id_category = cg.id_category AND cg.`id_group` '.(count($groups) ? 'IN ('.implode(',', $groups).')' : '= 1').')
				WHERE cp.`id_product` = p.`id_product`)');
		}
		$sql->orderBy((isset($order_by_prefix) ? pSQL($order_by_prefix).'.' : '').'`'.pSQL($order_by).'` '.pSQL($order_way));
		$sql->limit($nb_products, (int)(($page_number-1) * $nb_products));
		if (Combination::isFeatureActive()) {
			$sql->select('product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity, IFNULL(product_attribute_shop.id_product_attribute,0) id_product_attribute');
			$sql->leftJoin('product_attribute_shop', 'product_attribute_shop', 'p.`id_product` = product_attribute_shop.`id_product` AND product_attribute_shop.`default_on` = 1 AND product_attribute_shop.id_shop='.(int)$context->shop->id);
		}
		$sql->join(Product::sqlStock('p', 0));
		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
		if (!$result) {
			return false;
		}
		if ($order_by == 'price') {
			Tools::orderbyPrice($result, $order_way);
		}
		$products_ids = array();
		foreach ($result as $row) {
			$products_ids[] = $row['id_product'];
		}
		Product::cacheFrontFeatures($products_ids, $id_lang);
		
		$finalResults = Product::getProductsProperties((int)$id_lang, $result);
		$finalResults = AgileSellerManager::prepareSellerRattingInfo($finalResults);
		return $finalResults;
	}
	
    /*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:27
    * version: 3.7.3.2
    */
    public static function getPricesDrop($id_lang, $page_number = 0, $nb_products = 10, $count = false,
        $order_by = null, $order_way = null, $beginning = false, $ending = false, Context $context = null)
    {
        if (!Validate::isBool($count)) {
            die(Tools::displayError());
        }
        if (!$context) {
            $context = Context::getContext();
        }
		$agile_sql_parts = AgileSellerManager::getAdditionalSqlForProducts("p", true);
		if(Module::isInstalled('agilesellerlistoptions') &&  empty($orderby))$orderby = 'position2';
		if(empty($agile_sql_parts['joins']) OR empty($agile_sql_parts['wheres']))
			parent::getPricesDrop($id_lang, $page_number, $nb_products, $count,$order_by, $order_way, $beginning, $ending , $context);				
        if ($page_number < 1) {
            $page_number = 1;
        }
        if ($nb_products < 1) {
            $nb_products = 10;
        }
        if (empty($order_by) || $order_by == 'position') {
            $order_by = 'price';
        }
        if (empty($order_way)) {
            $order_way = 'DESC';
        }
        if ($order_by == 'id_product' || $order_by == 'price' || $order_by == 'date_add' || $order_by == 'date_upd') {
            $order_by_prefix = 'product_shop';
        } elseif ($order_by == 'name') {
            $order_by_prefix = 'pl';
        }
        if (!Validate::isOrderBy($order_by) || !Validate::isOrderWay($order_way)) {
            die(Tools::displayError());
        }
        $current_date = date('Y-m-d H:i:00');
        $ids_product = Product::_getProductIdByDate((!$beginning ? $current_date : $beginning), (!$ending ? $current_date : $ending), $context);
        $tab_id_product = array();
        foreach ($ids_product as $product) {
            if (is_array($product)) {
                $tab_id_product[] = (int)$product['id_product'];
            } else {
                $tab_id_product[] = (int)$product;
            }
        }
        $front = true;
        if (!in_array($context->controller->controller_type, array('front', 'modulefront'))) {
            $front = false;
        }
        $sql_groups = '';
        if (Group::isFeatureActive()) {
            $groups = FrontController::getCurrentCustomerGroups();
            $sql_groups = ' AND EXISTS(SELECT 1 FROM `'._DB_PREFIX_.'category_product` cp
				JOIN `'._DB_PREFIX_.'category_group` cg ON (cp.id_category = cg.id_category AND cg.`id_group` '.(count($groups) ? 'IN ('.implode(',', $groups).')' : '= 1').')
				WHERE cp.`id_product` = p.`id_product`)';
        }
        if ($count) {
            return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('
			SELECT COUNT(DISTINCT p.`id_product`)
			FROM `'._DB_PREFIX_.'product` p
			' . $agile_sql_parts['joins'] . '
			'.Shop::addSqlAssociation('product', 'p').'
			WHERE product_shop.`active` = 1
			' . $agile_sql_parts['wheres'] . '
			AND product_shop.`show_price` = 1
			'.($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '').'
			'.((!$beginning && !$ending) ? 'AND p.`id_product` IN('.((is_array($tab_id_product) && count($tab_id_product)) ? implode(', ', $tab_id_product) : 0).')' : '').'
			'.$sql_groups);
        }
        if (strpos($order_by, '.') > 0) {
            $order_by = explode('.', $order_by);
            $order_by = pSQL($order_by[0]).'.`'.pSQL($order_by[1]).'`';
        }
        $sql = '
		SELECT
			p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, pl.`description`, pl.`description_short`, pl.`available_now`, pl.`available_later`,
			IFNULL(product_attribute_shop.id_product_attribute, 0) id_product_attribute,
			pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`,
			pl.`name`, image_shop.`id_image` id_image, il.`legend`, m.`name` AS manufacturer_name,
			DATEDIFF(
				p.`date_add`,
				DATE_SUB(
					"'.date('Y-m-d').' 00:00:00",
					INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY
				)
			) > 0 AS new
		' . $agile_sql_parts['selects'] . '
		FROM `'._DB_PREFIX_.'product` p
				' . $agile_sql_parts['joins'] . '
		'.Shop::addSqlAssociation('product', 'p').'
		LEFT JOIN `'._DB_PREFIX_.'product_attribute_shop` product_attribute_shop
			ON (p.`id_product` = product_attribute_shop.`id_product` AND product_attribute_shop.`default_on` = 1 AND product_attribute_shop.id_shop='.(int)$context->shop->id.')
		'.Product::sqlStock('p', 0, false, $context->shop).'
		LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (
			p.`id_product` = pl.`id_product`
			AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').'
		)
		LEFT JOIN `'._DB_PREFIX_.'image_shop` image_shop
			ON (image_shop.`id_product` = p.`id_product` AND image_shop.cover=1 AND image_shop.id_shop='.(int)$context->shop->id.')
		LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (image_shop.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')
		LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`)
		WHERE product_shop.`active` = 1
		AND product_shop.`show_price` = 1
		' . $agile_sql_parts['wheres'] . '
		'.($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '').'
		'.((!$beginning && !$ending) ? ' AND p.`id_product` IN ('.((is_array($tab_id_product) && count($tab_id_product)) ? implode(', ', $tab_id_product) : 0).')' : '').'
		'.$sql_groups.'
		ORDER BY '.(isset($order_by_prefix) ? pSQL($order_by_prefix).'.' : '').pSQL($order_by).' '.pSQL($order_way).'
		LIMIT '.(int)(($page_number-1) * $nb_products).', '.(int)$nb_products;
		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
		if (!$result) {
			return false;
		}
		if ($order_by == 'price') {
			Tools::orderbyPrice($result, $order_way);
		}
		$finalResults = Product::getProductsProperties($id_lang, $result);
		$finalResults = AgileSellerManager::prepareSellerRattingInfo($finalResults);
		return $finalResults;
		
	}
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:27
    * version: 3.7.3.2
    */
    public static function getRandomSpecial($id_lang, $beginning = false, $ending = false, Context $context = null)
	{
		if(!Module::isInstalled('agilemultipleseller'))return parent::getRandomSpecial($id_lang, $beginning, $ending,  $context);
		$agile_sql_parts = AgileSellerManager::getAdditionalSqlForProducts("p", true);
		
		if (!$context)
			$context = Context::getContext();
		$front = true;
		if (!in_array($context->controller->controller_type, array('front', 'modulefront')))
			$front = false;
		$current_date = date('Y-m-d H:i:s');
		$product_reductions = Product::_getProductIdByDate((!$beginning ? $current_date : $beginning), (!$ending ? $current_date : $ending), $context, true);
		if ($product_reductions)
		{
			$ids_product = ' AND (';
			foreach ($product_reductions as $product_reduction)
				$ids_product .= '( product_shop.`id_product` = '.(int)$product_reduction['id_product'].($product_reduction['id_product_attribute'] ? ' AND product_attribute_shop.`id_product_attribute`='.(int)$product_reduction['id_product_attribute'] :'').') OR';
			$ids_product = rtrim($ids_product, 'OR').')';
			$groups = FrontController::getCurrentCustomerGroups();
			$sql_groups = (count($groups) ? 'IN ('.implode(',', $groups).')' : '= 1');
						$sql = 'SELECT product_shop.id_product, MAX(product_attribute_shop.id_product_attribute) id_product_attribute
					FROM `'._DB_PREFIX_.'product` p
					'.Shop::addSqlAssociation('product', 'p').'
					LEFT JOIN  `'._DB_PREFIX_.'product_attribute` pa ON (product_shop.id_product = pa.id_product)
					'.Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.default_on = 1').'
					' . $agile_sql_parts['joins'] . '
					WHERE product_shop.`active` = 1
						'.(($ids_product) ? $ids_product : '').'
						' . $agile_sql_parts['wheres'] . '
						AND p.`id_product` IN (
							SELECT cp.`id_product`
							FROM `'._DB_PREFIX_.'category_group` cg
							LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_category` = cg.`id_category`)
							WHERE cg.`id_group` '.$sql_groups.'
						)
					'.($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '').'
					GROUP BY product_shop.id_product
					ORDER BY RAND()';
			$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($sql);
			if (!$id_product = $result['id_product'])
				return false;
			$sql = 'SELECT p.*, product_shop.*, stock.`out_of_stock` out_of_stock, pl.`description`, pl.`description_short`,
						pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`,
						p.`ean13`, p.`upc`, MAX(image_shop.`id_image`) id_image, il.`legend`,
						DATEDIFF(product_shop.`date_add`, DATE_SUB(NOW(),
						INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).'
							DAY)) > 0 AS new
					FROM `'._DB_PREFIX_.'product` p
					LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (
						p.`id_product` = pl.`id_product`
						AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').'
					)
					'.Shop::addSqlAssociation('product', 'p').'
					LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product`)'.
				Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'
					LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')
					'.Product::sqlStock('p', 0).'
					' . $agile_sql_parts['joins'] . '
					WHERE p.id_product = '.(int)$id_product.'
						' . $agile_sql_parts['wheres'] . '
					GROUP BY product_shop.id_product';
			$row = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($sql);
			if (!$row)
				return false;
			if ($result['id_product_attribute'])
				$row['id_product_attribute'] = $result['id_product_attribute'];
			return Product::getProductProperties($id_lang, $row);
		}
		else
			return false;
	}
	
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:27
    * version: 3.7.3.2
    */
    public function add($autodate = true, $nullValues = false)
	{
		$res = parent::add($autodate, $nullValues);
		if(Module::isInstalled('agilemultipleseller') AND $res)
		{
			require_once(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/SellerInfo.php");
			SellerInfo::syncProductManufacturerSupplier($this);
		}
		if(Module::isInstalled('agilesellerlistoptions') AND $res)
		{
			require_once(_PS_ROOT_DIR_ . "/modules/agilesellerlistoptions/agilesellerlistoptions.php");
			$aslo = new AgileSellerListOptions();
			$aslo->processProductExtenstions(array('product' => $this));
		}
		
		return $res;
	}
	
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:27
    * version: 3.7.3.2
    */
    public function update($null_values = false)
	{
		$res = parent::update($null_values);
		
		if(Module::isInstalled('agilemultipleseller') AND $res)
		{
			require_once(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/SellerInfo.php");
			SellerInfo::syncProductManufacturerSupplier($this);
		}
		if(Module::isInstalled('agilesellerlistoptions') AND $res)
		{
			require_once(_PS_ROOT_DIR_ . "/modules/agilesellerlistoptions/agilesellerlistoptions.php");
			$aslo = new AgileSellerListOptions();
			$aslo->processProductExtenstions(array('product' => $this));
		}
		
		return $res;
	}
	
}
