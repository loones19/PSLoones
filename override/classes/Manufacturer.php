<?php
class Manufacturer extends ManufacturerCore
{
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:24
    * version: 3.7.3.2
    */
    public static function getProducts($id_manufacturer, $id_lang, $p, $n, $orderBy = NULL, $orderWay = NULL, $getTotal = false, $active = true, $active_category = true, Context $context = NULL)
	{
		$agile_sql_parts = AgileSellerManager::getAdditionalSqlForProducts("p");
		if(empty($agile_sql_parts['joins']) OR empty($agile_sql_parts['wheres']))
            parent::getProducts($id_manufacturer, $id_lang, $p, $n, $orderBy, $orderWay, $getTotal, $active, $active_category);
	
		if ($p < 1) $p = 1;
	 	if (empty($orderBy) ||$orderBy == 'position') $orderBy = 'name';
	 	if (empty($orderWay)) $orderWay = 'ASC';
		if (!Validate::isOrderBy($orderBy) OR !Validate::isOrderWay($orderWay))
			die (Tools::displayError());
			
		$groups = FrontController::getCurrentCustomerGroups();
		$sqlGroups = (count($groups) ? 'IN ('.implode(',', $groups).')' : '= 1');
				if ($getTotal)
		{
			$sql = '
				SELECT p.`id_product`
				FROM `'._DB_PREFIX_.'product` p
			    '. $agile_sql_parts['joins']. '
				WHERE p.id_manufacturer = '.(int)($id_manufacturer)
				.($active ? ' AND p.`active` = 1' : '').'
    			'. $agile_sql_parts['wheres']. '
				AND p.`id_product` IN (
					SELECT cp.`id_product`
					FROM `'._DB_PREFIX_.'category_group` cg
					LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_category` = cg.`id_category`)'.
					($active_category ? ' INNER JOIN `'._DB_PREFIX_.'category` ca ON cp.`id_category` = ca.`id_category` AND ca.`active` = 1' : '').'
					WHERE cg.`id_group` '.$sqlGroups.'
				)';
			$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS($sql);
			return (int)(sizeof($result));
		}
		$sql = '
		SELECT p.*, pa.`id_product_attribute`, pl.`description`, pl.`description_short`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, i.`id_image`, il.`legend`, m.`name` AS manufacturer_name, tl.`name` AS tax_name, t.`rate`, DATEDIFF(p.`date_add`, DATE_SUB(NOW(), INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY)) > 0 AS new,
			(p.`price` * ((100 + (t.`rate`))/100)) AS orderprice
			' . $agile_sql_parts['selects'] . '
		FROM `'._DB_PREFIX_.'product` p
			    '. $agile_sql_parts['joins']. '
		LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON (p.`id_product` = pa.`id_product` AND default_on = 1)
		LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = '.(int)($id_lang).')
		LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product` AND i.`cover` = 1)
		LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)($id_lang).')
		LEFT JOIN `'._DB_PREFIX_.'tax_rule` tr ON (p.`id_tax_rules_group` = tr.`id_tax_rules_group`
		                                           AND tr.`id_country` = '.(int)(_PS_VERSION_>'1.5' ? Context::getContext()->country->id :  Country::getDefaultCountryId()).'
	                                           	   AND tr.`id_state` = 0)
	    LEFT JOIN `'._DB_PREFIX_.'tax` t ON (t.`id_tax` = tr.`id_tax`)
		LEFT JOIN `'._DB_PREFIX_.'tax_lang` tl ON (t.`id_tax` = tl.`id_tax` AND tl.`id_lang` = '.(int)($id_lang).')
		LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON m.`id_manufacturer` = p.`id_manufacturer`
		WHERE p.`id_manufacturer` = '.(int)($id_manufacturer).($active ? ' AND p.`active` = 1' : '').'
			'. $agile_sql_parts['wheres']. '
		AND p.`id_product` IN (
					SELECT cp.`id_product`
					FROM `'._DB_PREFIX_.'category_group` cg
					LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_category` = cg.`id_category`)'.
					($active_category ? ' INNER JOIN `'._DB_PREFIX_.'category` ca ON cp.`id_category` = ca.`id_category` AND ca.`active` = 1' : '').'
					WHERE cg.`id_group` '.$sqlGroups.'
				)
		ORDER BY '.(($orderBy == 'id_product') ? 'p.' : '').'`'.pSQL($orderBy).'` '.pSQL($orderWay).'
		LIMIT '.(((int)($p) - 1) * (int)($n)).','.(int)($n);
		
		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS($sql);
		if (!$result)
			return false;
		if ($orderBy == 'price')
			Tools::orderbyPrice($result, $orderWay);
		
		$finalResults = Product::getProductsProperties($id_lang, $result);
		$finalResults = AgileSellerManager::prepareSellerRattingInfo($finalResults);
		return $finalResults;
	}
	
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:24
    * version: 3.7.3.2
    */
    public static function getManufacturers($get_nb_products = false, $id_lang = 0, $active = true, $p = false, $n = false, $all_group = false, $group_by = false, $withProduct = false)
	{
		if(!Module::isInstalled('agilemultipleseller'))
			parent::getManufacturers($get_nb_products, $id_lang, $active, $p, $n, $all_group, $group_by);
		
		$agile_sql_parts = AgileSellerManager::getAdditionalSqlForProducts("p");
		if(empty($agile_sql_parts['joins']) OR empty($agile_sql_parts['wheres']))
			parent::getManufacturers($get_nb_products, $id_lang, $active, $p, $n, $all_group, $group_by);
		
		if (!$id_lang) {
			$id_lang = (int)Configuration::get('PS_LANG_DEFAULT');
		}
		if (!Group::isFeatureActive()) {
			$all_group = true;
		}
		$manufacturers = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
		SELECT m.*, ml.`description`, ml.`short_description`
		FROM `'._DB_PREFIX_.'manufacturer` m
		'.Shop::addSqlAssociation('manufacturer', 'm').'
		INNER JOIN `'._DB_PREFIX_.'manufacturer_lang` ml ON (m.`id_manufacturer` = ml.`id_manufacturer` AND ml.`id_lang` = '.(int)$id_lang.')
		'.($active ? 'WHERE m.`active` = 1' : '')
			.($group_by ? ' GROUP BY m.`id_manufacturer`' : '').'
		ORDER BY m.`name` ASC
		'.($p ? ' LIMIT '.(((int)$p - 1) * (int)$n).','.(int)$n : ''));
		if ($manufacturers === false) {
			return false;
		}
		if ($get_nb_products) {
			$sql_groups = '';
			if (!$all_group) {
				$groups = FrontController::getCurrentCustomerGroups();
				$sql_groups = (count($groups) ? 'IN ('.implode(',', $groups).')' : '= 1');
			}
			$results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
					SELECT  p.`id_manufacturer`, COUNT(DISTINCT p.`id_product`) as nb_products
					FROM `'._DB_PREFIX_.'product` p USE INDEX (product_manufacturer)
					'.Shop::addSqlAssociation('product', 'p').'
					LEFT JOIN `'._DB_PREFIX_.'manufacturer` as m ON (m.`id_manufacturer`= p.`id_manufacturer`)
				    '. $agile_sql_parts['joins']. '
					WHERE p.`id_manufacturer` != 0 AND product_shop.`visibility` NOT IN ("none")
				    '. $agile_sql_parts['wheres']. '
					'.($active ? ' AND product_shop.`active` = 1 ' : '').'
					'.(Group::isFeatureActive() && $all_group ? '' : ' AND EXISTS (
						SELECT 1
						FROM `'._DB_PREFIX_.'category_group` cg
						LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_category` = cg.`id_category`)
						WHERE p.`id_product` = cp.`id_product` AND cg.`id_group` '.$sql_groups.'
					)').'
					GROUP BY p.`id_manufacturer`'
					);
			$counts = array();
			foreach ($results as $result) {
				$counts[(int)$result['id_manufacturer']] = (int)$result['nb_products'];
			}
			if (count($counts)) {
				foreach ($manufacturers as $key => $manufacturer) {
					if (array_key_exists((int)$manufacturer['id_manufacturer'], $counts)) {
						$manufacturers[$key]['nb_products'] = $counts[(int)$manufacturer['id_manufacturer']];
					} else {
						$manufacturers[$key]['nb_products'] = 0;
					}
				}
			}
		}
		$total_manufacturers = count($manufacturers);
		$rewrite_settings = (int)Configuration::get('PS_REWRITING_SETTINGS');
		for ($i = 0; $i < $total_manufacturers; $i++) {
			$manufacturers[$i]['link_rewrite'] = ($rewrite_settings ? Tools::link_rewrite($manufacturers[$i]['name']) : 0);
		}
		return $manufacturers;
	}
	
}
