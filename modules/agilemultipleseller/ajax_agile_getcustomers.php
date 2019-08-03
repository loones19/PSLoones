<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
include_once(dirname(__FILE__).'/../../config/config.inc.php');
include_once(dirname(__FILE__).'/../../init.php');
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

if (!isset(Context::getContext()->customer) || !Context::getContext()->customer->id || !Context::getContext()->customer->isLogged())
    die(Tools::jsonEncode(array('status'=>'error', 'message'=>Tools::displayError('Permission denied'))));

die(ajax_agile_getcustomers());

function ajax_agile_getcustomers()
{
    $id_seller = AgileSellerManager::getLinkedSellerID(Context::getContext()->customer->id);
    $customer_search = explode(' ', Tools::getValue('customer_search'));
    $array = array();
    $customer_search = array_unique($customer_search);
    foreach ($customer_search as $search) {
        if (!empty($search) && $search_result = ajax_getCustomers($id_seller,$search)) {
            foreach ($search_result as $customer) {
                $array[$customer['id_customer']] = $customer;
            }
        }
    }
    if (count($array)) $data = array('customers' => $array, 'found' => true); else $data = array('found' => false);
    return (Tools::jsonEncode($data));
}

function ajax_getCustomers($id_seller,$customer_search)
{
	$sql = 'SELECT c.id_customer, c.firstname, c.lastname, c.email, c.birthday  FROM `' . _DB_PREFIX_ . 'orders` a
		LEFT JOIN `' . _DB_PREFIX_ . 'customer` c ON (c.`id_customer` = a.`id_customer`)
		LEFT JOIN `' . _DB_PREFIX_ . 'order_owner` ao ON (a.`id_order`=ao.`id_order`)
		INNER JOIN `' . _DB_PREFIX_ . 'sellerinfo` ams ON (ao.`id_owner` = ams.`id_seller` AND ams.id_seller = '. $id_seller .')
		WHERE c.active=TRUE AND c.firstname LIKE "%'. $customer_search .'%"
		GROUP BY c.id_customer
		ORDER BY c.firstname, c.lastname';

	return Db::getInstance()->ExecuteS($sql);
}