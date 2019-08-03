<?php
class ProductSale extends ProductSaleCore
{
    /*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:28
    * version: 3.7.3.2
    */
    public static function getBestSales($idLang, $pageNumber = 0, $nbProducts = 10, $orderBy = null, $orderWay = null)
    {
		$agile_sql_parts = AgileSellerManager::getAdditionalSqlForProducts("p", true);
		
		if(empty($agile_sql_parts['joins']) OR empty($agile_sql_parts['wheres']))
			parent::getBestSales($idLang, $pageNumber, $nbProducts, $orderBy, $orderWay);
	
        $context = Context::getContext();
        if ($pageNumber < 1) {
            $pageNumber = 1;
        }
        if ($nbProducts < 1) {
            $nbProducts = 10;
        }
        $finalOrderBy = $orderBy;
        $orderTable = '';
        $invalidOrderBy = !Validate::isOrderBy($orderBy);
        if ($invalidOrderBy || is_null($orderBy)) {
            $orderBy = 'quantity';
            $orderTable = 'ps';
        }
        if ($orderBy == 'date_add' || $orderBy == 'date_upd') {
            $orderTable = 'product_shop';
        }
        $invalidOrderWay = !Validate::isOrderWay($orderWay);
        if ($invalidOrderWay || is_null($orderWay) || $orderBy == 'sales') {
            $orderWay = 'DESC';
        }
        $interval = Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20;
        $sql = 'SELECT p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity,
					'.(Combination::isFeatureActive()?'product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity,IFNULL(product_attribute_shop.id_product_attribute,0) id_product_attribute,':'').'
					pl.`description`, pl.`description_short`, pl.`link_rewrite`, pl.`meta_description`,
					pl.`meta_keywords`, pl.`meta_title`, pl.`name`, pl.`available_now`, pl.`available_later`,
					m.`name` AS manufacturer_name, p.`id_manufacturer` as id_manufacturer,
					image_shop.`id_image` id_image, il.`legend`,
					ps.`quantity` AS sales, t.`rate`, pl.`meta_keywords`, pl.`meta_title`, pl.`meta_description`,
					DATEDIFF(p.`date_add`, DATE_SUB("'.date('Y-m-d').' 00:00:00",
					INTERVAL '.(int) $interval.' DAY)) > 0 AS new
					' . $agile_sql_parts['selects'] . '
					'
            .' FROM `'._DB_PREFIX_.'product_sale` ps
				LEFT JOIN `'._DB_PREFIX_.'product` p ON ps.`id_product` = p.`id_product`
                ' . $agile_sql_parts['joins'] . '
				'.Shop::addSqlAssociation('product', 'p', false);
        if (Combination::isFeatureActive()) {
            $sql .= ' LEFT JOIN `'._DB_PREFIX_.'product_attribute_shop` product_attribute_shop
							ON (p.`id_product` = product_attribute_shop.`id_product` AND product_attribute_shop.`default_on` = 1 AND product_attribute_shop.id_shop='.(int)$context->shop->id.')';
        }
        $sql .=    ' LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
					ON p.`id_product` = pl.`id_product`
					AND pl.`id_lang` = '.(int) $idLang.Shop::addSqlRestrictionOnLang('pl').'
				LEFT JOIN `'._DB_PREFIX_.'image_shop` image_shop
					ON (image_shop.`id_product` = p.`id_product` AND image_shop.cover=1 AND image_shop.id_shop='.(int) $context->shop->id.')
				LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (image_shop.`id_image` = il.`id_image` AND il.`id_lang` = '.(int) $idLang.')
				LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`)
				LEFT JOIN `'._DB_PREFIX_.'tax_rule` tr ON (product_shop.`id_tax_rules_group` = tr.`id_tax_rules_group`)
					AND tr.`id_country` = '.(int) $context->country->id.'
					AND tr.`id_state` = 0
				LEFT JOIN `'._DB_PREFIX_.'tax` t ON (t.`id_tax` = tr.`id_tax`)
				'.Product::sqlStock('p', 0);
        $sql .= '
				WHERE product_shop.`active` = 1
                    ' .$agile_sql_parts['wheres'] . '
					AND product_shop.`visibility` != \'none\'';
        if (Group::isFeatureActive()) {
            $groups = FrontController::getCurrentCustomerGroups();
            $sql .= ' AND EXISTS(SELECT 1 FROM `'._DB_PREFIX_.'category_product` cp
					JOIN `'._DB_PREFIX_.'category_group` cg ON (cp.id_category = cg.id_category AND cg.`id_group` '.(count($groups) ? 'IN ('.implode(',', $groups).')' : '= 1').')
					WHERE cp.`id_product` = p.`id_product`)';
        }
        if ($finalOrderBy != 'price') {
            $sql .= '
					ORDER BY '.(!empty($orderTable) ? '`'.pSQL($orderTable).'`.' : '').'`'.pSQL($orderBy).'` '.pSQL($orderWay).'
					LIMIT '.(int) (($pageNumber-1) * $nbProducts).', '.(int) $nbProducts;
        }
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        if ($finalOrderBy == 'price') {
            Tools::orderbyPrice($result, $orderWay);
            $result = array_slice($result, (int) (($pageNumber-1) * $nbProducts), (int) $nbProducts);
        }
        if (!$result) {
            return false;
        }
		$finalResults = Product::getProductsProperties($idLang, $result);
		$finalResults = AgileSellerManager::prepareSellerRattingInfo($finalResults);
		return $finalResults;
    }
    /*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:28
    * version: 3.7.3.2
    */
    public static function getBestSalesLight($idLang, $pageNumber = 0, $nbProducts = 10, Context $context = null)
    {
		$agile_sql_parts = AgileSellerManager::getAdditionalSqlForProducts("p", true);
		
		if(empty($agile_sql_parts['joins']) OR empty($agile_sql_parts['wheres']))
			parent::getBestSalesLight($idLang, $pageNumber, $nbProducts, $context);
	
        if (!$context) {
            $context = Context::getContext();
        }
        if ($pageNumber < 0) {
            $pageNumber = 0;
        }
        if ($nbProducts < 1) {
            $nbProducts = 10;
        }
        $sql = '
		SELECT
			p.id_product, IFNULL(product_attribute_shop.id_product_attribute,0) id_product_attribute, pl.`link_rewrite`, pl.`name`, pl.`description_short`, product_shop.`id_category_default`,
			image_shop.`id_image` id_image, il.`legend`,
			ps.`quantity` AS sales, p.`ean13`, p.`upc`, cl.`link_rewrite` AS category, p.show_price, p.available_for_order, IFNULL(stock.quantity, 0) as quantity, p.customizable,
			IFNULL(pa.minimal_quantity, p.minimal_quantity) as minimal_quantity, stock.out_of_stock,
			product_shop.`date_add` > "'.date('Y-m-d', strtotime('-'.(Configuration::get('PS_NB_DAYS_NEW_PRODUCT') ? (int) Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY')).'" as new,
			product_shop.`on_sale`, product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity
            ' . $agile_sql_parts['selects'] . '
		FROM `'._DB_PREFIX_.'product_sale` ps
		LEFT JOIN `'._DB_PREFIX_.'product` p ON ps.`id_product` = p.`id_product`
        ' . $agile_sql_parts['joins'] . '
		'.Shop::addSqlAssociation('product', 'p').'
		LEFT JOIN `'._DB_PREFIX_.'product_attribute_shop` product_attribute_shop
			ON (p.`id_product` = product_attribute_shop.`id_product` AND product_attribute_shop.`default_on` = 1 AND product_attribute_shop.id_shop='.(int)$context->shop->id.')
		LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON (product_attribute_shop.id_product_attribute=pa.id_product_attribute)
		LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
			ON p.`id_product` = pl.`id_product`
			AND pl.`id_lang` = '.(int) $idLang.Shop::addSqlRestrictionOnLang('pl').'
		LEFT JOIN `'._DB_PREFIX_.'image_shop` image_shop
			ON (image_shop.`id_product` = p.`id_product` AND image_shop.cover=1 AND image_shop.id_shop='.(int) $context->shop->id.')
		LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (image_shop.`id_image` = il.`id_image` AND il.`id_lang` = '.(int) $idLang.')
		LEFT JOIN `'._DB_PREFIX_.'category_lang` cl
			ON cl.`id_category` = product_shop.`id_category_default`
			AND cl.`id_lang` = '.(int) $idLang.Shop::addSqlRestrictionOnLang('cl').Product::sqlStock('p', 0);
		$sql .= '
		WHERE product_shop.`active` = 1
        ' .$agile_sql_parts['wheres'] . '
		AND p.`visibility` != \'none\'';
		if (Group::isFeatureActive()) {
			$groups = FrontController::getCurrentCustomerGroups();
			$sql .= ' AND EXISTS(SELECT 1 FROM `'._DB_PREFIX_.'category_product` cp
				JOIN `'._DB_PREFIX_.'category_group` cg ON (cp.id_category = cg.id_category AND cg.`id_group` '.(count($groups) ? 'IN ('.implode(',', $groups).')' : '= 1').')
				WHERE cp.`id_product` = p.`id_product`)';
		}
		$sql .= '
		ORDER BY ps.quantity DESC
		LIMIT '.(int) ($pageNumber * $nbProducts).', '.(int) $nbProducts;
		if (!$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql)) {
			return false;
		}
		$result = Product::getProductsProperties($idLang, $result);
		$result = AgileSellerManager::prepareSellerRattingInfo($result);               
		return $result;
	}
	
}