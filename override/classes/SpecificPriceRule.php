<?php
class SpecificPriceRule extends SpecificPriceRuleCore
{
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:29
    * version: 3.7.3.2
    */
    public static function applyAllRules($products = false)
	{
		if(!Module::isInstalled('agilemultipleseller'))
		{
			parent::applyAllRules($products);
			return;
		}
		if (!SpecificPriceRule::$rules_application_enable)return;
		include_once(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/SellerInfo.php");
		$context = Context::getContext();
		$id_seller = 0;
				if($context->cookie->id_employee >0 AND  ($context->cookie->profile == (int)Configuration::get('AGILE_MS_PROFILE_ID')))
			$id_seller = $context->cookie->id_employee;
		else 			$id_seller == SellerInfo::getSellerIdByCustomerId($context->cookie->id_customer); 
		$query = new DbQuery();
		$query->select('s.*')
			->from('specific_price_rule', 's')
			->leftJoin('object_owner', 'oo', '(s.`id_specific_price_rule` = oo.`id_object` AND oo.entity=\'specific_price_rule\')')
			->where('s.id_shop = ' . Shop::getContextShopID() . ' AND IFNULL(oo.id_owner,0) IN (0,' . $id_seller . ')');
			
		$results = Db::getInstance()->executeS($query); 
		$rules = ObjectModel::hydrateCollection('SpecificPriceRule', $results);
		foreach ($rules as $rule)
		{
						$rule->apply($products);
		}
	}
	
	
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:29
    * version: 3.7.3.2
    */
    public function getAffectedProducts($products = false)
	{
		$products = parent::getAffectedProducts($products);
		if(empty($products))return $products;
		$rule_owner_id = (int)AgileSellerManager::getObjectOwnerID('specific_price_rule', $this->id);
				
		$query = new DbQuery();
		
				$query->select('p.`id_product`')
			->select('NULL as `id_product_attribute`')
			->from('product', 'p')
			->leftJoin('product_shop', 'ps', 'p.`id_product` = ps.`id_product`')
			->leftJoin('product_owner', 'po', '(p.`id_product` = po.`id_product`)')
			->where('ps.id_shop = ' . Shop::getContextShopID() . ' AND (po.id_owner=' . (int)$rule_owner_id .' OR 0 ='  . (int)$rule_owner_id . ' )');
				if ($products && count($products))
			$query->where('p.`id_product` IN ('.implode(', ', AgileHelper::retrieve_column_values($products, 'id_product')).')');
		$result = Db::getInstance()->executeS($query);
		return $result;
	}	
}
