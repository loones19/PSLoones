<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
class Search extends SearchCore
{
	public static function find($id_lang, $expr, $pageNumber = 1, $pageSize = 1, $orderBy = 'position', $orderWay = 'desc', $ajax = false, $useCookie = true,Context $context = null)
	{
		$context = Context::getContext();

				if(!Module::isInstalled('agilemultipleseller') AND !Module::isInstalled('agilesellerlistoptions'))
			return  parent::find($id_lang, $expr, $pageNumber, $pageSize, $orderBy, $orderWay, $ajax, $useCookie);

		$agile_sql_parts = AgileSellerManager::getAdditionalSqlForProducts("p");
		
		$db = Db::getInstance(_PS_USE_SQL_SLAVE_);
		
				if ($useCookie)
			$id_customer = (int)$context->customer->id;
		else
			$id_customer = 0;
		
				if ($pageNumber < 1) $pageNumber = 1;
		if ($pageSize < 1) $pageSize = 1;

		if (!Validate::isOrderBy($orderBy) OR !Validate::isOrderWay($orderWay))
			return false;

		$intersectArray = array();
		$scoreArray = array();
		$words = explode(' ', Search::sanitize($expr, (int)$id_lang));

		foreach ($words AS $key => $word)
			if (!empty($word) AND strlen($word) >= (int)Configuration::get('PS_SEARCH_MINWORDLEN'))
			{
				$word = str_replace('%', '\\%', $word);
				$word = str_replace('_', '\\_', $word);
				$intersectArray[] = 'SELECT id_product
					FROM '._DB_PREFIX_.'search_word sw
					LEFT JOIN '._DB_PREFIX_.'search_index si ON sw.id_word = si.id_word
					WHERE sw.id_lang = '.(int)$id_lang.'
					AND sw.word LIKE 
					'.($word[0] == '-'
						? ' \''.pSQL(Tools::substr($word, 1, PS_SEARCH_MAX_WORD_LENGTH)).'%\''
						: '\''.pSQL(Tools::substr($word, 0, PS_SEARCH_MAX_WORD_LENGTH)).'%\''
						);
				if ($word[0] != '-')
					$scoreArray[] = 'sw.word LIKE \''.pSQL(Tools::substr($word, 0, PS_SEARCH_MAX_WORD_LENGTH)).'%\'';
			}
			else
				unset($words[$key]);

		if (!sizeof($words))
			return ($ajax ? array() : array('total' => 0, 'result' => array()));

		$score = '';
		if (sizeof($scoreArray))
			$score = ',(
				SELECT SUM(weight)
				FROM '._DB_PREFIX_.'search_word sw
				LEFT JOIN '._DB_PREFIX_.'search_index si ON sw.id_word = si.id_word
				WHERE sw.id_lang = '.(int)$id_lang.'
				AND si.id_product = p.id_product
				AND ('.implode(' OR ', $scoreArray).')
			) position';

		$result = $db->ExecuteS('
		SELECT cp.`id_product`
		FROM `'._DB_PREFIX_.'category_group` cg
		INNER JOIN `'._DB_PREFIX_.'category_product` cp ON cp.`id_category` = cg.`id_category`
		INNER JOIN `'._DB_PREFIX_.'category` c ON cp.`id_category` = c.`id_category`
		INNER JOIN `'._DB_PREFIX_.'product` p ON cp.`id_product` = p.`id_product`
		WHERE c.`active` = 1 AND p.`active` = 1 AND indexed = 1
		AND cg.`id_group` '.(!$id_customer ?  '= 1' : 'IN (
			SELECT id_group FROM '._DB_PREFIX_.'customer_group
			WHERE id_customer = '.(int)$id_customer.'
		)'), false);
		
		$eligibleProducts = array();
		while ($row = $db->nextRow($result))
			$eligibleProducts[] = $row['id_product'];
		foreach ($intersectArray as $query)
		{
			$result = $db->ExecuteS($query, false);
			$eligibleProducts2 = array();
			while ($row = $db->nextRow($result))
				$eligibleProducts2[] = $row['id_product'];

			$eligibleProducts = array_intersect($eligibleProducts, $eligibleProducts2);
			if (!count($eligibleProducts))
				return ($ajax ? array() : array('total' => 0, 'result' => array()));
		}
		array_unique($eligibleProducts);
		
		$productPool = '';
		foreach ($eligibleProducts AS $id_product)
			if ($id_product)
				$productPool .= (int)$id_product.',';
		if (empty($productPool))
			return ($ajax ? array() : array('total' => 0, 'result' => array()));
		$productPool = ((strpos($productPool, ',') === false) ? (' = '.(int)$productPool.' ') : (' IN ('.rtrim($productPool, ',').') '));

		if ($ajax)
		{
			$sql = 'SELECT DISTINCT p.id_product, pl.name pname, cl.name cname,
						cl.link_rewrite crewrite, pl.link_rewrite prewrite '.$score.'
						' . $agile_sql_parts['selects'] . '
					FROM '._DB_PREFIX_.'product p
					INNER JOIN `'._DB_PREFIX_.'product_lang` pl ON (
						p.`id_product` = pl.`id_product`
						AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').'
					)
					'.Shop::addSqlAssociation('product', 'p').'
					INNER JOIN `'._DB_PREFIX_.'category_lang` cl ON (
						product_shop.`id_category_default` = cl.`id_category`
						AND cl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('cl').'
					)
					' . $agile_sql_parts['joins'] . '
					WHERE p.`id_product` '.$productPool.'
					' . $agile_sql_parts['wheres'] . '
					ORDER BY position DESC LIMIT 10';
			return $db->executeS($sql);
		}
		
		$from_conds = '
		FROM '._DB_PREFIX_.'product p
            ' . $agile_sql_parts['joins'] . '
		INNER JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = '.(int)$id_lang.')
		LEFT JOIN `'._DB_PREFIX_.'tax_rule` tr ON (p.`id_tax_rules_group` = tr.`id_tax_rules_group`
		                                           AND tr.`id_country` = '.(int)(_PS_VERSION_>'1.5' ? Context::getContext()->country->id :  Country::getDefaultCountryId()).'
	                                           	   AND tr.`id_state` = 0)
	    LEFT JOIN `'._DB_PREFIX_.'tax` tax ON (tax.`id_tax` = tr.`id_tax`)
		LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON m.`id_manufacturer` = p.`id_manufacturer`
		LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product` AND i.`cover` = 1)
		LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')
		WHERE p.`id_product` '.$productPool.'
		' . $agile_sql_parts['wheres'] . '
		';

		$sort_limit = ($orderBy ? 'ORDER BY  '.$orderBy : '').($orderWay ? ' '.$orderWay : '').'
			LIMIT '.(int)(($pageNumber - 1) * $pageSize).','.(int)$pageSize;
		

		$total = $db->getValue(' SELECT COUNT(*) ' . $from_conds);
		
		$queryResults = '	SELECT p.*, pl.`description_short`, pl.`available_now`, pl.`available_later`, pl.`link_rewrite`, pl.`name`,
			tax.`rate`, i.`id_image`, il.`legend`, m.`name` manufacturer_name '.$score.', DATEDIFF(p.`date_add`, DATE_SUB(NOW(), INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY)) > 0 new
            ' . $agile_sql_parts['selects'] . '
			' . $from_conds. '
			' . $sort_limit . '
			';

		$result = $db->ExecuteS($queryResults);
				
		if (!$result)
			$resultProperties = false;
		else
			$resultProperties = Product::getProductsProperties((int)$id_lang, $result);

		$resultProperties = AgileSellerManager::prepareSellerRattingInfo($resultProperties);		
		return array('total' => $total,'result' => $resultProperties);
	}
	
	public static function searchTag($id_lang, $tag, $count = false, $pageNumber = 0, $pageSize = 10, $orderBy = false, $orderWay = false,
		$useCookie = true, Context $context = null)
	{
		if (!$context)
			$context = Context::getContext();

				if(!Module::isInstalled('agilemultipleseller') AND !Module::isInstalled('agilesellerlistoptions'))
			return  parent::searchTag($id_lang, $expr, $pageNumber, $pageSize, $orderBy, $orderWay, $ajax, $useCookie, $context);

		$agile_sql_parts = AgileSellerManager::getAdditionalSqlForProducts("p");
		

				if ($useCookie)
			$id_customer = (int)$context->customer->id;
		else
			$id_customer = 0;

		if (!is_numeric($pageNumber) || !is_numeric($pageSize) || !Validate::isBool($count) || !Validate::isValidSearch($tag)
			|| $orderBy && !$orderWay || ($orderBy && !Validate::isOrderBy($orderBy)) || ($orderWay && !Validate::isOrderBy($orderWay)))
			return false;

		if ($pageNumber < 1) $pageNumber = 1;
		if ($pageSize < 1) $pageSize = 10;

		$id = Context::getContext()->shop->id;
		$id_shop = $id ? $id : Configuration::get('PS_SHOP_DEFAULT');
		if ($count)
		{
			$sql = 'SELECT COUNT(DISTINCT pt.`id_product`) nb
					FROM `'._DB_PREFIX_.'product` p
		            ' . $agile_sql_parts['joins'] . '
					'.Shop::addSqlAssociation('product', 'p').'
					LEFT JOIN `'._DB_PREFIX_.'product_tag` pt ON (p.`id_product` = pt.`id_product`)
					LEFT JOIN `'._DB_PREFIX_.'tag` t ON (pt.`id_tag` = t.`id_tag` AND t.`id_lang` = '.(int)$id_lang.')
					LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_product` = p.`id_product`)
					LEFT JOIN `'._DB_PREFIX_.'category_shop` cs ON (cp.`id_category` = cs.`id_category` AND cs.`id_shop` = '.(int)$id_shop.')
					LEFT JOIN `'._DB_PREFIX_.'category_group` cg ON (cg.`id_category` = cp.`id_category`)
					WHERE product_shop.`active` = 1
						' . $agile_sql_parts['wheres'] . '
						AND cs.`id_shop` = '.(int)Context::getContext()->shop->id.'
						AND cg.`id_group` '.(!$id_customer ?  '= '.(int)Configuration::get('PS_UNIDENTIFIED_GROUP') : 'IN (
							SELECT id_group FROM '._DB_PREFIX_.'customer_group
							WHERE id_customer = '.(int)$id_customer.')').'
						AND t.`name` LIKE \'%'.pSQL($tag).'%\'';
			return (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
		}

		$sql = 'SELECT DISTINCT p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, pl.`description_short`, pl.`link_rewrite`, pl.`name`,
					MAX(image_shop.`id_image`) id_image, il.`legend`, m.`name` manufacturer_name, 1 position,
					DATEDIFF(
						p.`date_add`,
						DATE_SUB(
							NOW(),
							INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY
						)
					) > 0 new
	            ' . $agile_sql_parts['selects'] . '
				FROM `'._DB_PREFIX_.'product` p
	            ' . $agile_sql_parts['joins'] . '
				INNER JOIN `'._DB_PREFIX_.'product_lang` pl ON (
					p.`id_product` = pl.`id_product`
					AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').'
				)
				'.Shop::addSqlAssociation('product', 'p', false).'
				LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product`)'.
			Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'		
				LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')
				LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`)
				LEFT JOIN `'._DB_PREFIX_.'product_tag` pt ON (p.`id_product` = pt.`id_product`)
				LEFT JOIN `'._DB_PREFIX_.'tag` t ON (pt.`id_tag` = t.`id_tag` AND t.`id_lang` = '.(int)$id_lang.')
				LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_product` = p.`id_product`)
				LEFT JOIN `'._DB_PREFIX_.'category_group` cg ON (cg.`id_category` = cp.`id_category`)
				LEFT JOIN `'._DB_PREFIX_.'category_shop` cs ON (cg.`id_category` = cs.`id_category` AND cs.`id_shop` = '.(int)$id_shop.')
				'.Product::sqlStock('p', 0).'
				WHERE product_shop.`active` = 1
					' . $agile_sql_parts['wheres'] . '
					AND cs.`id_shop` = '.(int)Context::getContext()->shop->id.'
					AND cg.`id_group` '.(!$id_customer ?  '= '.(int)Configuration::get('PS_UNIDENTIFIED_GROUP') : 'IN (
						SELECT id_group FROM '._DB_PREFIX_.'customer_group
						WHERE id_customer = '.(int)$id_customer.')').'
					AND t.`name` LIKE \'%'.pSQL($tag).'%\'
					GROUP BY product_shop.id_product
				ORDER BY position DESC'.($orderBy ? ', '.$orderBy : '').($orderWay ? ' '.$orderWay : '').'
				LIMIT '.(int)(($pageNumber - 1) * $pageSize).','.(int)$pageSize;
		if (!$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql))
			return false;

		$results = Product::getProductsProperties((int)$id_lang, $result);

		$results = AgileSellerManager::prepareSellerRattingInfo($results);				
		return $results;
	}

}
