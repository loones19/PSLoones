<?php
class AdminStatsController extends AdminStatsControllerCore
{
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:40
    * version: 3.7.3.2
    */
    public static function getOrders($date_from, $date_to, $granularity = false)
	{
				$context = Context::getContext();
		if(!Module::isInstalled('agilemultipleseller') || $context->cookie->profile != (int)Configuration::get('AGILE_MS_PROFILE_ID'))
			return parent::getOrders($date_from, $date_to, $granularity);
		
		if ($granularity == 'day') {
			$orders = array();
			$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS('
			SELECT LEFT(`invoice_date`, 10) as date, COUNT(*) as orders
			FROM `'._DB_PREFIX_.'orders` o
			LEFT JOIN `'._DB_PREFIX_.'order_state` os ON o.current_state = os.id_order_state
			LEFT JOIN `'._DB_PREFIX_.'order_owner` oo ON o.id_order = oo.id_order
			WHERE `invoice_date` BETWEEN "'.pSQL($date_from).' 00:00:00" AND "'.pSQL($date_to).' 23:59:59" AND os.logable = 1	
			AND oo.id_owner = ' . $context->cookie->id_employee . '			
			'.Shop::addSqlRestriction(false, 'o').'
			GROUP BY LEFT(`invoice_date`, 10)');
			foreach ($result as $row) {
				$orders[strtotime($row['date'])] = $row['orders'];
			}
			return $orders;
		} elseif ($granularity == 'month') {
			$orders = array();
			$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS('
			SELECT LEFT(`invoice_date`, 7) as date, COUNT(*) as orders
			FROM `'._DB_PREFIX_.'orders` o
			LEFT JOIN `'._DB_PREFIX_.'order_state` os ON o.current_state = os.id_order_state
			LEFT JOIN `'._DB_PREFIX_.'order_owner` oo ON o.id_order = oo.id_order
			WHERE `invoice_date` BETWEEN "'.pSQL($date_from).' 00:00:00" AND "'.pSQL($date_to).' 23:59:59" AND os.logable = 1
			AND oo.id_owner = ' . $context->cookie->id_employee . '
			'.Shop::addSqlRestriction(false, 'o').'
			GROUP BY LEFT(`invoice_date`, 7)');
			foreach ($result as $row) {
				$orders[strtotime($row['date'].'-01')] = $row['orders'];
			}
			return $orders;
		} else {
			$orders = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('
			SELECT COUNT(*) as orders
			FROM `'._DB_PREFIX_.'orders` o
			LEFT JOIN `'._DB_PREFIX_.'order_state` os ON o.current_state = os.id_order_state
			LEFT JOIN `'._DB_PREFIX_.'order_owner` oo ON o.id_order = oo.id_order
			WHERE `invoice_date` BETWEEN "'.pSQL($date_from).' 00:00:00" AND "'.pSQL($date_to).' 23:59:59" AND os.logable = 1
			AND oo.id_owner = ' . $context->cookie->id_employee . '
			'.Shop::addSqlRestriction(false, 'o'));
		}
		
		return $orders;
	}
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:40
    * version: 3.7.3.2
    */
    public static function getTotalSales($date_from, $date_to, $granularity = false)
	{
				$context = Context::getContext();
		if(!Module::isInstalled('agilemultipleseller') || $context->cookie->profile != (int)Configuration::get('AGILE_MS_PROFILE_ID'))
			return parent::getTotalSales($date_from, $date_to, $granularity);
		
		if ($granularity == 'day') {
			$sales = array();
			$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS('
			SELECT LEFT(`invoice_date`, 10) as date, SUM(total_paid_tax_excl / o.conversion_rate) as sales
			FROM `'._DB_PREFIX_.'orders` o
			LEFT JOIN `'._DB_PREFIX_.'order_state` os ON o.current_state = os.id_order_state
			LEFT JOIN `'._DB_PREFIX_.'order_owner` oo ON o.id_order = oo.id_order
			WHERE `invoice_date` BETWEEN "'.pSQL($date_from).' 00:00:00" AND "'.pSQL($date_to).' 23:59:59" AND os.logable = 1
			AND oo.id_owner = ' . $context->cookie->id_employee . '
			'.Shop::addSqlRestriction(false, 'o').'
			GROUP BY LEFT(`invoice_date`, 10)');
			foreach ($result as $row) {
				$sales[strtotime($row['date'])] = $row['sales'];
			}
			return $sales;
		} elseif ($granularity == 'month') {
			$sales = array();
			$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS('
			SELECT LEFT(`invoice_date`, 7) as date, SUM(total_paid_tax_excl / o.conversion_rate) as sales
			FROM `'._DB_PREFIX_.'orders` o
			LEFT JOIN `'._DB_PREFIX_.'order_state` os ON o.current_state = os.id_order_state
			LEFT JOIN `'._DB_PREFIX_.'order_owner` oo ON o.id_order = oo.id_order
			WHERE `invoice_date` BETWEEN "'.pSQL($date_from).' 00:00:00" AND "'.pSQL($date_to).' 23:59:59" AND os.logable = 1
			AND oo.id_owner = ' . $context->cookie->id_employee . '
			'.Shop::addSqlRestriction(false, 'o').'
			GROUP BY LEFT(`invoice_date`, 7)');
			foreach ($result as $row) {
				$sales[strtotime($row['date'].'-01')] = $row['sales'];
			}
			return $sales;
		} else {
			return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('
			SELECT SUM(total_paid_tax_excl / o.conversion_rate)
			FROM `'._DB_PREFIX_.'orders` o
			LEFT JOIN `'._DB_PREFIX_.'order_state` os ON o.current_state = os.id_order_state
			LEFT JOIN `'._DB_PREFIX_.'order_owner` oo ON o.id_order = oo.id_order
			WHERE `invoice_date` BETWEEN "'.pSQL($date_from).' 00:00:00" AND "'.pSQL($date_to).' 23:59:59" AND os.logable = 1
			AND oo.id_owner = ' . $context->cookie->id_employee . '
			'.Shop::addSqlRestriction(false, 'o'));
		}
	}
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:40
    * version: 3.7.3.2
    */
    public static function getPurchases($date_from, $date_to, $granularity = false)
	{
				$context = Context::getContext();
		if(!Module::isInstalled('agilemultipleseller') || $context->cookie->profile != (int)Configuration::get('AGILE_MS_PROFILE_ID'))
			return parent::getPurchases($date_from, $date_to, $granularity);
		
		if ($granularity == 'day') {
			$purchases = array();
			$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS('
			SELECT
				LEFT(`invoice_date`, 10) as date,
				SUM(od.`product_quantity` * IF(
					od.`purchase_supplier_price` > 0,
					od.`purchase_supplier_price` / `conversion_rate`,
					od.`original_product_price` * '.(int)Configuration::get('CONF_AVERAGE_PRODUCT_MARGIN').' / 100
				)) as total_purchase_price
			FROM `'._DB_PREFIX_.'orders` o
			LEFT JOIN `'._DB_PREFIX_.'order_detail` od ON o.id_order = od.id_order
			LEFT JOIN `'._DB_PREFIX_.'order_state` os ON o.current_state = os.id_order_state
			LEFT JOIN `'._DB_PREFIX_.'order_owner` oo ON o.id_order = oo.id_order
			WHERE `invoice_date` BETWEEN "'.pSQL($date_from).' 00:00:00" AND "'.pSQL($date_to).' 23:59:59" AND os.logable = 1
			AND oo.id_owner = ' . $context->cookie->id_employee . '
			'.Shop::addSqlRestriction(false, 'o').'
			GROUP BY LEFT(`invoice_date`, 10)');
			foreach ($result as $row) {
				$purchases[strtotime($row['date'])] = $row['total_purchase_price'];
			}
			return $purchases;
		} else {
			return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('
			SELECT SUM(od.`product_quantity` * IF(
				od.`purchase_supplier_price` > 0,
				od.`purchase_supplier_price` / `conversion_rate`,
				od.`original_product_price` * '.(int)Configuration::get('CONF_AVERAGE_PRODUCT_MARGIN').' / 100
			)) as total_purchase_price
			FROM `'._DB_PREFIX_.'orders` o
			LEFT JOIN `'._DB_PREFIX_.'order_detail` od ON o.id_order = od.id_order
			LEFT JOIN `'._DB_PREFIX_.'order_state` os ON o.current_state = os.id_order_state
			LEFT JOIN `'._DB_PREFIX_.'order_owner` oo ON o.id_order = oo.id_order
			WHERE `invoice_date` BETWEEN "'.pSQL($date_from).' 00:00:00" AND "'.pSQL($date_to).' 23:59:59" AND os.logable = 1
			AND oo.id_owner = ' . $context->cookie->id_employee . '
			'.Shop::addSqlRestriction(false, 'o'));
		}
	}
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:40
    * version: 3.7.3.2
    */
    public static function getExpenses($date_from, $date_to, $granularity = false)
	{
				$context = Context::getContext();
		if(!Module::isInstalled('agilemultipleseller') || $context->cookie->profile != (int)Configuration::get('AGILE_MS_PROFILE_ID'))
			return parent::getPurchases($date_from, $date_to, $granularity);		
		
		$expenses = ($granularity == 'day' ? array() : 0);
		$orders = Db::getInstance()->ExecuteS('
		SELECT
			LEFT(`invoice_date`, 10) as date,
			total_paid_tax_incl / o.conversion_rate as total_paid_tax_incl,
			total_shipping_tax_excl / o.conversion_rate as total_shipping_tax_excl,
			o.module,
			a.id_country,
			o.id_currency,
			c.id_reference as carrier_reference
		FROM `'._DB_PREFIX_.'orders` o
		LEFT JOIN `'._DB_PREFIX_.'address` a ON o.id_address_delivery = a.id_address
		LEFT JOIN `'._DB_PREFIX_.'carrier` c ON o.id_carrier = c.id_carrier
		LEFT JOIN `'._DB_PREFIX_.'order_state` os ON o.current_state = os.id_order_state
		LEFT JOIN `'._DB_PREFIX_.'order_owner` oo ON o.id_order = oo.id_order
		WHERE `invoice_date` BETWEEN "'.pSQL($date_from).' 00:00:00" AND "'.pSQL($date_to).' 23:59:59" AND os.logable = 1
		AND oo.id_owner = ' . $context->cookie->id_employee . '
		'.Shop::addSqlRestriction(false, 'o'));
		foreach ($orders as $order) {
						$flat_fees = Configuration::get('CONF_ORDER_FIXED') + (
				$order['id_currency'] == Configuration::get('PS_CURRENCY_DEFAULT')
				? Configuration::get('CONF_'.strtoupper($order['module']).'_FIXED')
				: Configuration::get('CONF_'.strtoupper($order['module']).'_FIXED_FOREIGN')
				);
						$var_fees = $order['total_paid_tax_incl'] * (
				$order['id_currency'] == Configuration::get('PS_CURRENCY_DEFAULT')
				? Configuration::get('CONF_'.strtoupper($order['module']).'_VAR')
				: Configuration::get('CONF_'.strtoupper($order['module']).'_VAR_FOREIGN')
				) / 100;
						$shipping_fees = $order['total_shipping_tax_excl'] * (
				$order['id_country'] == Configuration::get('PS_COUNTRY_DEFAULT')
				? Configuration::get('CONF_'.strtoupper($order['carrier_reference']).'_SHIP')
				: Configuration::get('CONF_'.strtoupper($order['carrier_reference']).'_SHIP_OVERSEAS')
				) / 100;
						if ($granularity == 'day') {
				if (!isset($expenses[strtotime($order['date'])])) {
					$expenses[strtotime($order['date'])] = 0;
				}
				$expenses[strtotime($order['date'])] += $flat_fees + $var_fees + $shipping_fees;
			} else {
				$expenses += $flat_fees + $var_fees + $shipping_fees;
			}
		}
		return $expenses;
	}
}