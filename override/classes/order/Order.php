<?php
class Order extends OrderCore
{
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:25
    * version: 3.7.3.2
    */
    public static function getCustomerOrders($id_customer, $showHiddenStatus = false, Context $context = null)
	{
		$res = parent::getCustomerOrders($id_customer, $showHiddenStatus,  $context);
		if(!Module::isInstalled('agilemultipleseller'))return $res;
		if($context == null)$context = Context::getContext();
		if($context->cookie->id_employee == 0 || ($context->cookie->profile != (int)Configuration::get('AGILE_MS_PROFILE_ID')))return $res;  
		$ret = array();
		foreach($res as $data)
		{
			$id_owner = AgileSellerManager::getObjectOwnerID('order',$data['id_order']);
			if($id_owner != $context->cookie->id_employee)continue;
			$ret[] = $data;
		}
		return $ret;
	}
	
	
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:25
    * version: 3.7.3.2
    */
    public static function getOrdersWithInformations($limit = null, Context $context = null)
	{
		if (!$context) {
			$context = Context::getContext();
		}
		if(!Module::isInstalled('agilemultipleseller') || $context->cookie->profile != (int)Configuration::get('AGILE_MS_PROFILE_ID'))
			return parent::getOrdersWithInformations($limit, $context);
		
		$sql = 'SELECT *, (
					SELECT osl.`name`
					FROM `'._DB_PREFIX_.'order_state_lang` osl
					WHERE osl.`id_order_state` = o.`current_state`
					AND osl.`id_lang` = '.(int)$context->language->id.'
					LIMIT 1
				) AS `state_name`, o.`date_add` AS `date_add`, o.`date_upd` AS `date_upd`
				FROM `'._DB_PREFIX_.'orders` o
				LEFT JOIN `'._DB_PREFIX_.'customer` c ON (c.`id_customer` = o.`id_customer`)
				LEFT JOIN `'._DB_PREFIX_.'order_owner` oo ON (o.`id_order` = oo.`id_order`)
				WHERE 1
					 AND oo.id_owner=' . (int)$context->cookie->id_employee . '
					'.Shop::addSqlRestriction(false, 'o').'
				ORDER BY o.`date_add` DESC
				'.((int)$limit ? 'LIMIT 0, '.(int)$limit : '');
		
				
		return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
	}
	
}
