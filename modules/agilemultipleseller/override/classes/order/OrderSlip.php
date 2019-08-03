<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
class OrderSlip extends OrderSlipCore
{
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
