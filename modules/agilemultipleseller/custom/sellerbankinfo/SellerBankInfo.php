<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
class	SellerBankInfo extends ObjectModel
{
	public		$id_sellerbankinfo;
	public		$id_seller; 
    public      $business_name;
	public      $shop_name;
	public 		$business_address1;
	public 		$business_address2;
	public 		$account_name;
	public 		$account_number;
	public 		$bank_name;
	public 		$bank_address;
	public 		$passwd;
	public 		$date_add;
	public 		$date_upd;
	
	public static $definition = array(
		'table' => 'sellerbankinfo',
		'primary' => 'id_sellerbankinfo',
		'multilang' => false,
		'multilang_shop' => false,
		'fields' => array(
			'id_seller' => 			array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
			'shop_name' =>			array('type' => self::TYPE_STRING, 'validate' => 'isString'),
			'business_name' =>		array('type' => self::TYPE_STRING, 'validate' => 'isString'),
			'business_address1' =>	array('type' => self::TYPE_STRING, 'validate' => 'isString'),
			'business_address2' =>	array('type' => self::TYPE_STRING, 'validate' => 'isString'),
			'account_name' =>		array('type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true),
			'account_number' =>		array('type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true),
			'bank_name' =>			array('type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true),
			'bank_address' =>		array('type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true),
			'passwd' =>				array('type' => self::TYPE_STRING, 'validate' => 'isPasswd'),			
			'date_add' => 			array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
			'date_upd' => 			array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
			),
		);

	public static function getIdBySellerID($id_seller)
	{
		$sql = 'SELECT id_sellerbankinfo FROM ' . _DB_PREFIX_ . 'sellerbankinfo WHERE id_seller=' . (int) $id_seller;
		return (int)Db::getInstance()->getValue($sql);
	}

}

