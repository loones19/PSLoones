<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
include_once(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/custom/agileitemshippings/ItemShipping.php");

class AgileItemShippings extends Module
{
	public static function assignItemShippngData($id_product)
	{
		$$this->context->smarty->assign(array(
			'itemShippingData' => ItemShipping::getItemShippngData($id_product) 
		));
	}
	
	public static function updateItemShippingData($id_product )
	{
				$errors = array();
		$zones = Zone::getZones(true);
		foreach ($zones as $z)
		{
			$single_item_fee = Tools::getValue("sitm_".$z['id_zone']);
			$additional_item_fee = Tools::getValue("aitm_".$z['id_zone']);
			if (isset($_POST["zone_".$z['id_zone']]))
			{
				if (!is_numeric($single_item_fee ) || !is_numeric($single_item_fee ))
				{
					$errors[] = Tools::displayError('The Single Item Fee or Addition Item Fee must be numeric.');
					return $errors;
				}
				$sql = 'SELECT COUNT(*) AS num FROM `'._DB_PREFIX_.'agile_itemshipping` WHERE id_zone=' . $z['id_zone'] . ' AND id_product=' . $id_product;
				$row = Db::getInstance()->getRow($sql);
				if(intval($row['num'])==0)
				{
					$sql = 'INSERT INTO `'._DB_PREFIX_.'agile_itemshipping` (id_zone,id_product,single_item_fee,additional_item_fee,date_add) VALUES (' . $z['id_zone'] . ','. $id_product . ',' . $single_item_fee . ',' . $additional_item_fee . ',\'' . date('Y-m-d H:i:s') . '\')';
					Db::getInstance()->Execute($sql);
				} else {
					$sql = 'UPDATE `'._DB_PREFIX_.'agile_itemshipping` SET single_item_fee ='. $single_item_fee . ', additional_item_fee = ' . $additional_item_fee . ' WHERE id_zone=' . $z['id_zone'] .' AND id_product= '  . $id_product ; 
					
					Db::getInstance()->Execute($sql);
				}
			} else {
				$sql = 'DELETE FROM `'._DB_PREFIX_.'agile_itemshipping`  WHERE id_zone=' . $z['id_zone'] .' AND id_product= '  . $id_product ;
				Db::getInstance()->Execute($sql);
			}
		}
		
		return $errors;		
	}
	
	public static function getItemShippingCost4Product($id_product, $id_zone, $quantity)
	{
		$sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'agile_itemshipping WHERE id_zone=' . $id_zone . ' AND id_product=' . (int)$id_product;
		$shpping_settings = Db::getInstance()->getRow($sql);
		$item_fees = 0;
		if(!empty($shpping_settings))
		{
			$single_fee = (float)$shpping_settings['single_item_fee'];
			$item_fees += $single_fee;
			$additional_item_fees =  (float)(($quantity -1)  * (float)$shpping_settings['additional_item_fee']);
			$item_fees += $additional_item_fees;
		}
		return $item_fees;		
	}
	
	public static function getItemShippingCost4SellerCarrier($id_cart, $id_seller, $id_carrier, $id_zone)
	{
		$sql = 'SELECT cp.id_product, sum(cp.quantity) AS quantity
				FROM ' . _DB_PREFIX_ . 'cart_product cp 
					LEFT JOIN ' . _DB_PREFIX_ . 'product p ON cp.id_product=p.id_product
					LEFT JOIN ' . _DB_PREFIX_ . 'product_owner po ON cp.id_product=po.id_product
					LEFT JOIN `' . _DB_PREFIX_ . 'agile_cartcarrier` cc on (cp.id_cart=cc.id_cart AND cp.id_product=cc.id_product AND cp.id_product_attribute=cc.id_product_attribute) 
				WHERE cc.id_carrier = ' . intval($id_carrier) .'
					AND cc.id_cart = ' . (int)$id_cart . '
					AND po.id_owner = ' . (int)$id_seller . '
				GROUP BY  cp.id_product
				';

				$rows = Db::getInstance()->ExecuteS($sql);
		$item_fees = 0;
		foreach($rows AS $row)
		{
			$quantity = (int)$row['quantity'];
			if($quantity <=0)continue;
			$item_fees += AgileItemShippings::getItemShippingCost4Product($row['id_product'], $id_zone, $quantity);
		}
		
		return $item_fees;
	}
	
}

