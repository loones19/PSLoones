<?php
class CustomerThread extends CustomerThreadCore
{
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:22
    * version: 3.7.3.2
    */
    public static function getTotalCustomerThreads($where = null)
	{
				$context = Context::getContext();
		if(!Module::isInstalled('agilemultipleseller') || $context->cookie->profile != (int)Configuration::get('AGILE_MS_PROFILE_ID'))
			return parent::getTotalCustomerThreads($where);
		
		if (is_null($where)) {
			return (int)Db::getInstance()->getValue('
				SELECT COUNT(*)
				FROM '._DB_PREFIX_.'customer_thread ct
				LEFT JOIN '._DB_PREFIX_.'order_owner oo ON ct.id_order = oo.id_order
				WHERE 1 
				AND oo.id_owner = ' . (int)$context->cookie->id_employee. ' 
				'.Shop::addSqlRestriction()
				);
		} else {
			return (int)Db::getInstance()->getValue('
				SELECT COUNT(*)
				FROM '._DB_PREFIX_.'customer_thread ct
				LEFT JOIN '._DB_PREFIX_.'order_owner oo ON ct.id_order = oo.id_order
				WHERE '.$where.Shop::addSqlRestriction() . '
				AND oo.id_owner = ' . (int)$context->cookie->id_employee. ' 							
				'
				);
		}
	}	
}
