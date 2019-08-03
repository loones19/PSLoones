<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
class ItemShipping extends ObjectModel
{
	public		$id_agile_itemshipping;

		public 		$date_add;
	public 		$date_upd;
	public static $definition = array(
		'table' => 'agile_itemshipping',
		'primary' => 'id_agile_itemshipping',
		'multilang' => false,
		'multilang_shop' => false,
		'fields' => array(
			'id_product' => 			array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true,),
			'id_zone' => 				array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
			'single_item_fee' =>		array('type' => self::TYPE_FLOAT, 'validate' => 'isString'),
			'additional_item_fee' =>	array('type' => self::TYPE_FLOAT, 'validate' => 'isString'),
			),
		);
	
	protected	$_includeContainer = false;
	
	public static function getItemShippngData($id_product)
	{
		$sql = 'SELECT s.id_agile_itemshipping, z.id_zone, z.name, s.single_item_fee,s.additional_item_fee 
				FROM  `'._DB_PREFIX_.'zone`  AS z 
					LEFT JOIN`'._DB_PREFIX_. 'agile_itemshipping`  AS s ON (z.id_zone=s.id_zone AND  s.id_product =' .$id_product  .')
					ORDER BY z.id_zone';
		$results = Db::getInstance()->ExecuteS($sql);
		return $results;
	}

}

