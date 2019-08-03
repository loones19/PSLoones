<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
require(dirname(__FILE__).'/../../config/config.inc.php');
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

if (!isset(Context::getContext()->customer) || !Context::getContext()->customer->id || !Context::getContext()->customer->isLogged())
	die(Tools::displayError('Permission Denied'));

$id_seller = AgileSellerManager::getLinkedSellerID(Context::getContext()->customer->id);


$query = Tools::getValue('q', false);
if (!$query OR $query == '' OR strlen($query) < 1)
	die();

if($pos = strpos($query, ' (ref:'))
	$query = substr($query, 0, $pos);

$excludeIds = Tools::getValue('excludeIds', false);
if ($excludeIds && $excludeIds != 'NaN')
{
	$excludeIds = implode(',', array_map('intval', explode(',', $excludeIds)));
}
else
{
	$excludeIds = '';
}

$includepublicproduct = (int)Tools::getValue('includepublicproduct');

$excludeVirtuals = (bool)Tools::getValue('excludeVirtuals', false);

$sql = 'SELECT p.`id_product`, `reference`, pl.name
FROM `'._DB_PREFIX_.'product` p 
		INNER JOIN `'._DB_PREFIX_.'product_owner` po ON (p.id_product=po.id_product AND po.id_owner IN (' . ($includepublicproduct==1?0:$id_seller) . ',' . $id_seller . '))
		LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (pl.id_product = p.id_product AND pl.id_lang = '.(int)Context::getContext()->language->id.Shop::addSqlRestrictionOnLang('pl').')
		WHERE (pl.name LIKE \'%'.pSQL($query).'%\' OR p.reference LIKE \'%'.pSQL($query).'%\')'.
		(!empty($excludeIds) ? ' AND p.id_product NOT IN ('.$excludeIds.') ' : ' ').
		($excludeVirtuals ? 'AND p.id_product NOT IN (SELECT pd.id_product FROM `'._DB_PREFIX_.'product_download` pd WHERE (pd.id_product = p.id_product))' : '');

$items = Db::getInstance()->executeS($sql);

if ($items)
{
	foreach ($items AS $item)
	{
		echo trim($item['name']).(!empty($item['reference']) ? ' (ref: '.$item['reference'].')' : '').'|'.(int)($item['id_product'])."\n";
	}
}

