<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
class AgileSellerPaymentInfo extends ObjectModel
{

	public 		$id;
	
		public 		$id_currency;
	public 		$id_seller;
	public      $module_name;
	public 		$info1;
	public 		$info2;
	public 		$info3;
	public 		$info4;
	public 		$info5;
	public 		$info6;
	public 		$info7;
	public 		$info8;
	public      $in_use;
	
		public 		$date_add;
	

	public static $definition = array(
		'table' => 'agile_seller_paymentinfo',
		'primary' => 'id_agile_seller_paymentinfo',
		'multilang' => false,
		'multilang_shop' => false,
		'fields' => array(
			'id_seller' => 		array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
			'module_name' => 	array('type' => self::TYPE_STRING, 'validate' => 'isPostCode'),
			'id_currency' => 	array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
			'in_use' =>	  		array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
			'info1'	=>			array('type' => self::TYPE_STRING, 'validate' => 'isString'),
			'info2' =>			array('type' => self::TYPE_STRING, 'validate' => 'isString'),
			'info3' =>			array('type' => self::TYPE_STRING, 'validate' => 'isString'),
			'info4' =>			array('type' => self::TYPE_STRING, 'validate' => 'isString'),
			'info5' =>			array('type' => self::TYPE_STRING, 'validate' => 'isString'),
			'info6' =>			array('type' => self::TYPE_STRING, 'validate' => 'isString'),
			'info7' =>			array('type' => self::TYPE_STRING, 'validate' => 'isString'),
			'info8' =>			array('type' => self::TYPE_STRING, 'validate' => 'isString'),

			'date_add' => 		array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
			),
		);
	


	public	function getFields()
	{
	 	parent::validateFields(false);
	 	
		if (isset($this->id))
			$fields[$this->identifier] = intval($this->id);

		$fields['date_add'] = pSQL($this->date_add);
		$fields['id_seller'] = (int)pSQL($this->id_seller);
		$fields['id_currency'] = (int)pSQL($this->id_currency);
		$fields['module_name'] = pSQL($this->module_name);
		$fields['info1'] = pSQL($this->info1);
		$fields['info2'] = pSQL($this->info2);
		$fields['info3'] = pSQL($this->info3);
		$fields['info4'] = pSQL($this->info4);
		$fields['info5'] = pSQL($this->info5);
		$fields['info6'] = pSQL($this->info6);
		$fields['info7'] = pSQL($this->info7);
		$fields['info8'] = pSQL($this->info8);
		$fields['in_use'] = pSQL($this->in_use);
		
		return ($fields);
	}	

	public static function deleteForSellerByModuleName($module_name, $id_seller)
	{
		$sql = 'DELETE FROM ' .  _DB_PREFIX_ . 'agile_seller_paymentinfo WHERE id_seller=' . (int)$id_seller . ' AND module_name=\'' . $module_name . '\'' ;
		Db::getInstance()->Execute($sql);		
	}
		
	public static function getForSellerByModuleName($module_name, $id_seller)
	{
	    if(!isset($id_seller) OR intval($id_seller)<=0)return false;

		$sql ='
		SELECT a.* 
		FROM `'._DB_PREFIX_.'agile_seller_paymentinfo` a
		WHERE 1
		    AND a.`module_name` = \''.pSQL($module_name).'\'
		    AND a.`id_seller` = '.pSQL($id_seller).'
		';

		$obj = new AgileSellerPaymentInfo();

		$result = Db::getInstance()->getRow($sql);
		if (!$result)
			return $obj;

		$obj->id = $result[$obj->identifier];

		foreach ($result AS $key => $value)
		{
			if (key_exists($key, $obj))
				$obj->{$key} = $value;
		}
		return $obj;
	}
	
	public static function is_seller_payment_info_existed($module_name, $id_seller)
	{
		$sql = 'SELECT id_seller FROM `'._DB_PREFIX_.'agile_seller_paymentinfo` WHERE module_name=\'' . $module_name . '\' and id_seller =\''.$id_seller.'\' LIMIT 1';
				$id = Db::getInstance()->executeS($sql);
		return count($id) > 0;
	}
	
	public static function is_seller_payment_account_existed($module_name, $id_seller, $info)
	{
		if(empty($info)) return false;
		$sql = 'SELECT id_seller FROM `'._DB_PREFIX_.'agile_seller_paymentinfo` WHERE module_name=\'' . $module_name . '\' and info1 =\''.$info.'\' and id_seller <>\''.$id_seller.'\' LIMIT 1';
		$id = Db::getInstance()->executeS($sql);
		return count($id) > 0;
	}
	
	public static function hasDuplicationRecord($paymentifo, $mod)
	{
		if($paymentifo->id_seller <=0)return false;
		$sql = 'SELECT COUNT(*) AS num FROM ' . _DB_PREFIX_  . 'agile_seller_paymentinfo WHERE id_seller != ' . (int)$paymentifo->id_seller . ' AND module_name=\'' . $mod['name'] . '\'';
		$anyuniquefield = false;
		for($idx =1; $idx <=8 ; $idx++)
		{
			if($mod['info' .$idx]['is_unique'])
			{
				$sql = $sql . ' AND info' . $idx. ' =\'' . $paymentifo->{'info'.$idx} .'\'';
				$anyuniquefield = true;
			}
		}
				if(!$anyuniquefield)return false; 

		$num = (int)Db::getInstance()->getValue($sql);
		return $num > 0;		
	}
	
};


