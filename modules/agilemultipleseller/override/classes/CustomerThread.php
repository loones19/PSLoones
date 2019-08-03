<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
class CustomerThread extends CustomerThreadCore
{
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

