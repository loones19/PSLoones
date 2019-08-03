<?php
class OrderSlip extends OrderSlipCore
{
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:26
    * version: 3.7.3.2
    */
    public static function getSlipsIdByDate($dateFrom, $dateTo)
	{
		$context = Context::getContext();
		$is_seller = ($context->cookie->profile == (int)Configuration::get('AGILE_MS_PROFILE_ID'));	
		if(!Module::isInstalled('agilemultipleseller') || !$is_seller)return parent::getSlipsIdByDate($dateFrom, $dateTo);
		
		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
		SELECT `id_order_slip`
		FROM `'._DB_PREFIX_.'order_slip` os
		LEFT JOIN `'._DB_PREFIX_.'orders` o ON (o.`id_order` = os.`id_order`)
		LEFT JOIN `'._DB_PREFIX_.'order_owner` oo ON (os.id_order = oo.id_order)
		WHERE os.`date_add` BETWEEN \''.pSQL($dateFrom).' 00:00:00\' AND \''.pSQL($dateTo).' 23:59:59\' 
		AND oo.id_owner = ' . (int)$context->cookie->id_employee . ' 
		'.Shop::addSqlRestriction(Shop::SHARE_ORDER, 'o').'
		ORDER BY os.`date_add` ASC');
		$slips = array();
		foreach ($result as $slip) {
			$slips[] = (int)$slip['id_order_slip'];
		}
		return $slips;
	}
}
