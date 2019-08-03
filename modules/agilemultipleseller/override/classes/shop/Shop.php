<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.

class Shop extends ShopCore
{
	
		public static $id_shop_owner =0;
		public static $id_shop_virtual =0;
		public static $sellerinfo;
	
		public static function getContextShopID($null_value_without_multishop = false)
	{
		self::$context_id_shop = 1; 		return self::$context_id_shop;
	}	

	public static function isFeatureActive()
	{
		$isFeatureActive = parent::isFeatureActive();
								
						if($isFeatureActive)
		{
			Db::getInstance()->Execute('UPDATE ' . _DB_PREFIX_ .  'configuration SET value=0 WHERE name=\'PS_MULTISHOP_FEATURE_ACTIVE\'');
		}
		return false;
		
	}
		public static function initialize()
	{		
		if(file_exists(_PS_ROOT_DIR_.'/modules/agilekernel/init.php'))include_once(_PS_ROOT_DIR_.'/modules/agilekernel/init.php');
		$shop = parent::initialize();		
		if(Module::isInstalled('agilemultipleseller') AND Module::isInstalled('agilemultipleshop') AND $shop->id != 1)
		{
			include_once(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/SellerInfo.php");
			include_once(_PS_ROOT_DIR_ . "/modules/agilemultipleshop/agilemultipleshop.php");

			self::$id_shop_owner = AgileSellerManager::getSellerIdByShopId($shop->id);
			
			self::$id_shop_virtual = $shop->id;
			self::$sellerinfo = new SellerInfo(SellerInfo::getIdBSellerId(self::$id_shop_owner));
			$shop->id = Configuration::get('PS_SHOP_DEFAULT');
			self::$context_id_shop = $shop->id;
		}

		return $shop;
	}
	
	public static function addSqlAssociation($table, $alias, $inner_join = true, $on = null, $force_not_default = false)
	{
		$sql = 	parent::addSqlAssociation($table, $alias, $inner_join, $on, $force_not_default);
		if(!Module::isInstalled('agilemultipleshop'))return $sql;

		$eaccess = AgileSellerManager::get_entity_access($table);
		if($eaccess['owner_table_type'] == AgileSellerManager::OWNER_TABLE_UNKNOWN)return $sql;

		$xr_table = $eaccess['owner_xr_table'];
		
				$include_shared = '';
		if(!$eaccess['is_exclusive'])$include_shared = ',0';

		if(Shop::$id_shop_owner >0)
		{
									$join = ($inner_join ? 'INNER JOIN ': 'LEFT JOIN ');
			if($table == 'feature')$join = 'LEFT JOIN '; 
			if($table == 'orders')$table = 'order';
			if($eaccess['owner_table_type'] == AgileSellerManager::OWNER_TABLE_DEFINED)
				$sql .= $join . _DB_PREFIX_ . $table . '_owner ' . $table . '_owner ON (' . $alias . '.id_' . $table . '=' . $table . '_owner.id_' . $table . ' AND IFNULL(' . $table . '_owner.id_owner,0) IN (' . Shop::$id_shop_owner . $include_shared . '))';
			else
				$sql .= $join . _DB_PREFIX_ . 'object_owner ' . $table . '_object_owner ON ('. $table . '_object_owner.entity=\'' . $table. '\' AND ' . $alias . '.id_' . $table . '= ' . $table . '_object_owner.id_object AND IFNULL(' . $table . '_object_owner.id_owner,0) IN (' . Shop::$id_shop_owner . $include_shared . '))';
		}
		return $sql;
	}	
	
	public static function shop_name_duplicated($name, $id)
	{
		if(empty($name))return false;
		$sql = '
			SELECT id_shop
			FROM '._DB_PREFIX_.'shop
			WHERE name = \''.pSQL($name) .'\'';
		
		$id_found = intval(Db::getInstance()->getValue($sql));
				if($id_found ==0)return false;
		if($id_found>0 AND $id_found != (int)$id)return true;
		return false;
	}

	public static function get_main_url_id($id_shop)
	{
		if(empty($id_shop))return 0;
		$sql = '
			SELECT id_shop_url
			FROM '._DB_PREFIX_.'shop_url
			WHERE main=1 AND id_shop = \''.(int)$id_shop .'\'';
		
		$id_shopurl = intval(Db::getInstance()->getValue($sql));
		return $id_shopurl;
	}

	public static function addSqlAssociation_4topmenu($table, $alias, $inner_join = true, $on = null, $force_not_default = false)
	{
		$table_alias = $table.'_shop';
		if (strpos($table, '.') !== false)
			list($table_alias, $table) = explode('.', $table);

		$asso_table = Shop::getAssoTable($table);
		if ($asso_table === false || $asso_table['type'] != 'shop')
			return;
		$sql = (($inner_join) ? ' INNER' : ' LEFT').' JOIN '._DB_PREFIX_.$table.'_shop '.$table_alias.'
		ON ('.$table_alias.'.id_'.$table.' = '.$alias.'.id_'.$table;
		if ((int)self::$context_id_shop)
			$sql .= ' AND '.$table_alias.'.id_shop = '.(int)self::$context_id_shop;
		elseif (Shop::checkIdShopDefault($table) && !$force_not_default)
			$sql .= ' AND '.$table_alias.'.id_shop = '.$alias.'.id_shop_default';
		else
			$sql .= ' AND '.$table_alias.'.id_shop IN ('.implode(', ', Shop::getContextListShopID()).')';
		$sql .= (($on) ? ' AND '.$on : '').')';
		return $sql;
	}

}
