<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
class AgileSellerManagerCore
{
	const OWNER_TABLE_UNKNOWN = 0;
	const OWNER_TABLE_DEFINED = 1; 	const OWNER_TABLE_SHARED = 2; 
		private static $entity_multiowner = array('customer');
	private static $entity_accesses = array(
		'agile_sellermessage'  => array('owner_table_type' => 2, 'owner_xr_table'=>'', 'is_exclusive' => 1), 
		'attachment'  => array('owner_table_type' => 2, 'owner_xr_table'=>'', 'is_exclusive' => 1), 
		'attribute'  => array('owner_table_type' => 2, 'owner_xr_table'=>'', 'is_exclusive' => 0), 
		'attribute_group'  => array('owner_table_type' => 2, 'owner_xr_table'=>'', 'is_exclusive' => 0), 
		'cart_rule'  => array('owner_table_type' => 2, 'owner_xr_table'=>'', 'is_exclusive' => 1), 
		'cms'  => array('owner_table_type' => 1, 'owner_xr_table'=>'', 'is_exclusive' => 1), 
		'cms_category'  => array('owner_table_type' => 2, 'owner_xr_table'=>'', 'is_exclusive' => 1), 
		'feature'  => array('owner_table_type' => 2, 'owner_xr_table'=>'', 'is_exclusive' => 1), 
		'group'  => array('owner_table_type' => 2, 'owner_xr_table'=>'', 'is_exclusive' => 0), 
		'product'  => array('owner_table_type' => 1, 'owner_xr_table'=>'', 'is_exclusive' => 1 ), 
		'carrier'  => array('owner_table_type' => 1, 'owner_xr_table'=>'', 'is_exclusive' => 0 ), 
		'category'  => array('owner_table_type' => 1, 'owner_xr_table'=>'', 'is_exclusive' => 0 ), 
		'customer'  => array('owner_table_type' => 1, 'owner_xr_table'=>'', 'is_exclusive' => 1 ), 
		'customer_thread'  => array('owner_table_type' => 1, 'owner_xr_table'=>'order', 'is_exclusive' => 1 ), 
		'location'  => array('owner_table_type' => 2, 'owner_xr_table'=>'', 'is_exclusive' => 1), 
		'manufacturer'  => array('owner_table_type' => 2, 'owner_xr_table'=>'', 'is_exclusive' => 1 ), 
		'order'  => array('owner_table_type' => 1, 'owner_xr_table'=>'', 'is_exclusive' => 1 ), 
		'order_carrier'  => array('owner_table_type' => 1, 'owner_xr_table'=>'order', 'is_exclusive' => 1 ), 
		'order_message'  => array('owner_table_type' => 2, 'owner_xr_table'=>'', 'is_exclusive' => 1 ), 
		'order_slip'  => array('owner_table_type' => 1, 'owner_xr_table'=>'order', 'is_exclusive' => 1 ), 
		'supplier'  => array('owner_table_type' => 2, 'owner_xr_table'=>'', 'is_exclusive' => 0 ), 
		'specific_price'  => array('owner_table_type' => 2, 'owner_xr_table'=>'product', 'is_exclusive' => 1 ), 
		'specific_price_rule'  => array('owner_table_type' => 2, 'owner_xr_table'=>'', 'is_exclusive' => 1 ), 
		'tag'  => array('owner_table_type' => 2, 'owner_xr_table'=>'', 'is_exclusive' => 1 ), 

		'order_history'  => array('owner_table_type' => 1, 'owner_xr_table'=>'order', 'is_exclusive' => 1 ), 
		'order_invoice'  => array('owner_table_type' => 1, 'owner_xr_table'=>'order', 'is_exclusive' => 1 ), 
		'order_return'  => array('owner_table_type' => 1, 'owner_xr_table'=>'order', 'is_exclusive' => 1 ), 
		'range_price'  => array('owner_table_type' => 1, 'owner_xr_table'=>'carrier', 'is_exclusive' => 1 ), 
		'range_weight'  => array('owner_table_type' => 1, 'owner_xr_table'=>'carrier', 'is_exclusive' => 1 ), 
		'stock_available'  => array('owner_table_type' => 1, 'owner_xr_table'=>'product', 'is_exclusive' => 1 ), 
		'feature_value'  => array('owner_table_type' => 2, 'owner_xr_table'=>'feature', 'is_exclusive' => 1), 

		'sellerinfo'  => array('owner_table_type' => 2, 'owner_xr_table'=>'', 'is_exclusive' => 1 ), 
		);

	public static function get_entity_access($table)
	{
		if($table == 'orders')$table = 'order';

		if($table == 'address')
		{
			$context = Context::getContext();
			$controllername = isset($context->controller->controller_name)? strtolower($context->controller->controller_name) : "";
			switch($controllername)
			{
				case "admincustomers":
					return array('owner_table_type' => 1, 'owner_xr_table'=>'customer', 'is_exclusive' => 1);
				case "adminmanufacturers":
					return array('owner_table_type' => 2, 'owner_xr_table'=>'manufacturer', 'is_exclusive' => 1);
				case "adminsuppliers":
					return array('owner_table_type' => 2, 'owner_xr_table'=>'supplier', 'is_exclusive' => 1);
			}
		}
		
		if(isset(self::$entity_accesses[$table]))return self::$entity_accesses[$table];
		return array('owner_table_type' => self::OWNER_TABLE_UNKNOWN, 'owner_xr_table'=>'', 'is_exclusive' => 1 );
	}
	
	public static function assignObjectOwner($table, $id, $id_seller)
	{
		$eaccess = self::get_entity_access($table);
		if($eaccess['owner_table_type'] == AgileSellerManager::OWNER_TABLE_DEFINED)
		{
			self::assignObjectOwnerDefined($table, $eaccess['owner_xr_table'], $id, $id_seller);
		}
		else		{
			self::assignObjectOwnerShared($table, $eaccess['owner_xr_table'], $id, $id_seller);
		}
	}

	public static function deleteObjectOwner($table, $id)
	{
		if(intval($id)<=0)return;
		$eaccess = self::get_entity_access($table);

		if(!empty($eaccess['owner_xr_table']))return;

		if($eaccess['owner_table_type'] == AgileSellerManager::OWNER_TABLE_DEFINED)
		{
			$sql = 'DELETE FROM ' . _DB_PREFIX_ . ($table=='orders'?'order':$table) . '_owner WHERE id_' . ($table=='orders'?'order':$table) . '=' . $id;

		}
		else		{
			$sql = 'DELETE FROM ' . _DB_PREFIX_ . 'object_owner WHERE `entity`=\'' . $table . '\' AND id_object=' . $id;
		}
				Db::getInstance()->Execute($sql);
	}

	public static function getSellersNV($activeonly = true, $label4zero = '')
	{
		$context = Context::getContext();
		
		$sql = '
    		SELECT e.`id_employee` AS id_seller, CASE WHEN IFNULL(sl.`company`,\'\')=\'\' THEN CONCAT(e.firstname,\' \', e.lastname) ELSE sl.`company` END AS name
	    	FROM `'._DB_PREFIX_.'employee` e
	    		LEFT JOIN `'._DB_PREFIX_.'sellerinfo` s ON IFNULL(s.id_seller,0)=e.id_employee
	    		LEFT JOIN `'._DB_PREFIX_.'sellerinfo_lang` sl ON (s.id_sellerinfo=sl.id_sellerinfo AND sl.id_lang=' . intval($context->language->id) . ')
		    WHERE 1=1 AND e.id_profile = ' .  (int)Configuration::get('AGILE_MS_PROFILE_ID') . ' 
		    ' . ($activeonly?'AND e.active = 1':'') . '
		';
				if(!empty($label4zero))
			$sql = 'SELECT 0 AS id_seller, \'' . $label4zero . '\' AS name UNION ' . $sql;
		
		return Db::getInstance()->ExecuteS($sql);
	}


	public static function getObjectOwnerID($table, $objid)
	{
		$ownerinfo = self::getObjectOwnerInfo($table, $objid);
		if(isset($ownerinfo['id_owner']))return intval($ownerinfo['id_owner']);
		return -1;
	}
	
	public static function getLinkedSellerID($id_customer)
	{
		$sql = 'SELECT id_seller FROM `'._DB_PREFIX_.'sellerinfo` WHERE id_customer=' . intval($id_customer);
		$res = Db::getInstance()->getRow($sql);
		if(isset($res['id_seller']) AND intval($res['id_seller'])>0)return intval($res['id_seller']);
		return 0;
	}

	public static function getSellerIdByShopId($id_shop)
	{
		$sql = 'SELECT id_seller FROM `'._DB_PREFIX_.'sellerinfo` WHERE id_shop=' . intval($id_shop);
		$res = Db::getInstance()->getRow($sql);
		if(isset($res['id_seller']) AND intval($res['id_seller'])>0)return intval($res['id_seller']);
		return 0;
	}


		public static function getObjectOwnerInfo($table, $objid, $id_seller=null)
	{
		$eacess = self::get_entity_access($table);
		if($eacess['owner_table_type'] == self::OWNER_TABLE_SHARED)
		{
						return self::getObjectOwnerInfoShared($table, $objid, $eacess['owner_xr_table'], $id_seller);
		}
		elseif($eacess['owner_table_type'] == AgileSellerManager::OWNER_TABLE_DEFINED)
		{
						return self::getObjectOwnerInfoDefined($table, $objid, $eacess['owner_xr_table'], $id_seller);
		}
		return;		
	}



			private static function assignObjectOwnerDefined($table, $xr_table, $id, $id_seller)
	{
		if(intval($id)<=0)return;
		if($table =='orders')$table = 'order';

		$ownerInfo = self::getObjectOwnerInfoDefined($table, $id, $xr_table, $id_seller);
		$id_field_name = 'id_' .$table;
		if(isset($ownerInfo[$id_field_name]) AND intval($ownerInfo[$id_field_name])>0)
		{
			$sql = 'UPDATE '._DB_PREFIX_.$table .'_owner SET id_owner=' . $id_seller . ' WHERE ' . $id_field_name . '=' . intval($id);
		}
		else
		{
			$sql = 'INSERT INTO '._DB_PREFIX_.$table .'_owner (id_' . $table . ',id_owner,date_add) VALUES(' . intval($id) . ','. intval($id_seller) . ',\'' . date('Y-m-d H:i:s') . '\')';
		}
				try
		{
			Db::getInstance()->Execute($sql);
		}
		catch(Exception $e){die($e->getMessage());}
		
		
	}
	
	private static function assignObjectOwnerShared($table,$xr_table, $id, $id_seller)
	{
		if(intval($id)<=0)return;

		$ownerInfo = self::getObjectOwnerInfoShared($table, $id,$xr_table, $id_seller);
		if(isset($ownerInfo['id_object']) AND intval($ownerInfo['id_object'])>0)
		{
			$sql = 'UPDATE '._DB_PREFIX_.'object_owner SET id_owner=' . (int)$id_seller . ' WHERE  `entity`=\'' . $table . '\' AND id_object=' . intval($id);
		}
		else
		{
			$sql = 'INSERT INTO '._DB_PREFIX_.'object_owner (id_object,`entity` ,id_owner,date_add, datetime1) VALUES(' . intval($id) . ',\'' . $table . '\','. intval($id_seller) . ',\'' . date('Y-m-d H:i:s') . '\',\'' . date('Y-m-d H:i:s') . '\')';
		}
				try
		{
			Db::getInstance()->Execute($sql);
		}
		catch(Exception $e){die($e->getMessage());}
		
	}

	private static function getObjectOwnerInfoDefined($table, $id , $xr_table, $id_seller=null)
	{
		if(intval($id)<=0)return;

		if($table =='orders')$table = 'order';
		if(empty($xr_table))
		{
			$sql = 'SELECT * FROM ' . _DB_PREFIX_ . ($table=='orders'?'order':$table) .'_owner WHERE id_' . ($table=='orders'?'order':$table) . '=' . intval($id);
		}
		else
		{
			$sql = '
				SELECT axo.* 
				FROM ' . _DB_PREFIX_ . $table .' a 
					LEFT JOIN ' . _DB_PREFIX_ . $xr_table . '_owner axo ON a.id_' . $xr_table . '=axo.id_' . $xr_table . '
				WHERE a.id_' . $table . '=' . intval($id);
		}	
				if(intval($id_seller)>0 AND in_array($table, self::$entity_multiowner))
			$sql .=   ' AND (id_owner=0 OR id_Owner=' . intval($id_seller) . ') ORDER BY id_owner DESC';
				try
		{
			$row = Db::getInstance()->getRow($sql);
		}
		
		catch(Exception $e) {die($e->getMessage());}

		return $row;		
	}

	private static function getObjectOwnerInfoShared($table, $id, $xr_table, $id_seller=null)
	{
		if(intval($id)<=0)return;
		if(empty($xr_table))
		{
			$sql = 'SELECT * FROM ' . _DB_PREFIX_ .'object_owner WHERE `entity`=\'' . $table . '\' AND id_object=' . intval($id);
		}
		else
		{
			$sql = '
				SELECT axo.* 
				FROM ' . _DB_PREFIX_ . $table .' a 
					
					LEFT JOIN ' . _DB_PREFIX_ . 'object_owner axo ON (a.id_' . $xr_table . '=axo.id_object AND axo.`entity`=\'' . $xr_table . '\')
				WHERE a.id_' . $table . '=' . intval($id);
		}
				if(intval($id_seller)>0 AND in_array($table, self::$entity_multiowner))
			$sql .=   ' AND (id_owner=0 OR id_Owner=' . intval($id_seller) . ') ORDER BY id_owner DESC';

		$row = Db::getINstance()->getRow($sql);

		return $row;		
	}
	
		public static function hasOwnership($table, $objid)
	{
		$context = Context::getContext();

		$id_seller = $context->cookie->id_employee;
		if($context->cookie->id_employee <= 0)
		{
			$query = 'SELECT id_seller FROM ' . _DB_PREFIX_ . 'sellerinfo WHERE id_customer=' . intval($context->cookie->id_customer);
			$id_seller = intval(Db::getInstance()->getValue($query));
		}
		
		$ownerinfo = self::getObjectOwnerInfo($table, $objid,  $id_seller);
						if(!isset($ownerinfo) OR !isset($ownerinfo['id_owner']))return false;
		if(intval($ownerinfo['id_owner']) != $id_seller)return false;
		return true;
	}

	public static function appendMailTemplateVars($templateVars, $id_lang)
	{
		include_once(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/SellerInfo.php");
		if(!isset($templateVars['{order_name}']))return $templateVars;
		$id_order = AgileHelper::get_order_id_from_maildata($templateVars);
		if(Module::isInstalled('agilesellershipping'))
		{
			include_once(_PS_ROOT_DIR_ . "/modules/agilesellershipping/agilesellershipping.php");	
			$module = new AgileSellerShipping();
			$id_cart = Order::getCartIdStatic($id_order);
			$theCart = new Cart($id_cart);
			$shipping_info = $module->displayInfoByCart($id_cart);
			$templateVars['{carrier}'] = $shipping_info;
			$templateVars['{total_shipping}'] =  Tools::displayPrice($theCart->getOrderTotal(true,Cart::ONLY_SHIPPING));
			$templateVars['{total_paid}'] =  Tools::displayPrice($theCart->getOrderTotal(true,Cart::BOTH));
		}

		$id_seller = intval(self::getObjectOwnerID('order',$id_order));
		$id_sellerinfo = SellerInfo::getIdBSellerId($id_seller);
		$sellerinfo = new SellerInfo($id_sellerinfo, $id_lang);
		if(!Validate::isLoadedObject($sellerinfo))return $templateVars;
		$templateVars['{seller_logo}'] = Tools::getShopDomainSsl(true) . __PS_BASE_URI__ . 'img/as/' . $sellerinfo->id . '.jpg';
		$templateVars['{seller_address}'] = $sellerinfo->fulladdress($id_lang);
		$templateVars['{seller_name}'] = $sellerinfo->company;
		return $templateVars;	
	}
	
	public static function getConfFromSellerInfo($id_order)
	{
		$context = Context::getContext();
		$conf = array();

		$id_seller = AgileSellerManager::getObjectOwnerID('order',$id_order);
		if($id_seller <=0)return $conf;
		$id_sellerinfo = self::getIdBSellerId($id_seller);
		$sellerinfo = new SellerInfo($id_sellerinfo, $context->language->id);
		if(!Validate::isLoadedObject($sellerinfo))return $conf;
		
		$conf['PS_SHOP_NAME_UPPER'] = strtoupper($sellerinfo->company);
		$conf['PS_SHOP_ADDR1'] = $sellerinfo->address1;
		$conf['PS_SHOP_ADDR2'] = $sellerinfo->address2;
		$conf['PS_SHOP_CITY'] = $sellerinfo->city;
		$conf['PS_SHOP_STATE'] = '';
		if($sellerinfo->id_state)
		{
			$state = new State($sellerinfo->id_state);
			$conf['PS_SHOP_STATE'] = $state->name;
		}
		$conf['PS_SHOP_COUNTRY'] = '';
		if($sellerinfo->id_country)
		{
			$country = new Country($sellerinfo->id_country,$context->language->id);
			$conf['PS_SHOP_COUNTRY'] = $country->name;
		}
		$conf['PS_SHOP_PHONE'] = $sellerinfo->phone;
		$conf['PS_SHOP_CODE'] = $sellerinfo->postcode;

		return $conf;
	}

	public static function checkEmptySellerInfoRecords()
	{
				$sql = 'INSERT INTO '._DB_PREFIX_.'sellerinfo (id_seller,id_country,id_state,date_add,date_upd) 
                SELECT id_employee AS id_seller,' . Configuration::get('PS_COUNTRY_DEFAULT') . ' AS id_country,0 AS id_state, CURDATE() AS date_add, CURDATE() AS date_upd
                FROM '._DB_PREFIX_.'employee e
                    LEFT JOIN '._DB_PREFIX_.'sellerinfo s ON (e.id_employee=s.id_seller)
                WHERE IFNULL(s.id_seller,0)=0 AND id_profile=' . intval(Configuration::get('AGILE_MS_PROFILE_ID')) . '
                ORDER BY id_employee';
		Db::getInstance()->Execute($sql);
	}

	public static function getAcitveProductsListed($id_seller)
	{
		if(!intval($id_seller))return 0;
		
		$sql = 'SELECT count(p.id_product) as num
            FROM ' . _DB_PREFIX_ . 'product p 
                LEFT JOIN ' . _DB_PREFIX_ . 'product_owner po ON p.id_product=po.id_product 
            WHERE id_owner=' . $id_seller . '
                AND p.active=1
            ';
				
		return intval(Db::getInstance()->getValue($sql));
	}
	
	public static function disableSellerProducts($id_seller)
	{
		if(intval($id_seller)<=0)return;

		$action_required = 0;
		$sql = 'SELECT active FROM ' . _DB_PREFIX_ . 'employee WHERE id_employee=' . intval($id_seller);
		$status = Db::getInstance()->getValue($sql);
		if (!isset($status)) 			$action_required = 1;
		else if (intval($status) ==0)			$action_required = 1;
		
		if($action_required == 0)return;

				$sql4pids = 'SELECT id_product FROM `'._DB_PREFIX_.'product_owner` WHERE id_owner=' . $id_seller;
		$sql = 'UPDATE `'._DB_PREFIX_.'product`  SET active=0 WHERE id_product IN (' . $sql4pids . ')';
		Db::getInstance()->Execute($sql);
		$sql = 'UPDATE `'._DB_PREFIX_.'product_shop`  SET active=0 WHERE id_product IN (' . $sql4pids . ')';
		Db::getInstance()->Execute($sql);

				$sql4cids = 'SELECT id_category FROM `'._DB_PREFIX_.'category_owner` WHERE id_owner=' . $id_seller;
		$sql = 'UPDATE `'._DB_PREFIX_.'category`  SET active=0 WHERE id_category IN (' . $sql4cids . ')';
				Db::getInstance()->Execute($sql);        
	}
	
	public static function deleteSellerInfo($id_seller)
	{
		if(intval($id_seller)<=0)return;
	
		$sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'sellerinfo WHERE id_seller=' . (int)$id_seller;
		$info = Db::getInstance()->getRow($sql);
		$id_sellerinfo = (int)$info['id_sellerinfo'];
		
				$id_default_shop = (int)Configuration::get('PS_SHOP_DEFAULT');
		$sql = 'DELETE FROM `'._DB_PREFIX_.'shop` WHERE id_shop !=' . $id_default_shop . ' AND id_shop IN ( SELECT id_shop FROM ' . _DB_PREFIX_ . 'sellerinfo WHERE id_sellerinfo=' . (int)$id_sellerinfo . ')';
		Db::getInstance()->Execute($sql);        

		$sql = 'DELETE FROM `'._DB_PREFIX_.'shop_url` WHERE id_shop!=' . $id_default_shop . ' AND id_shop IN ( SELECT id_shop FROM ' . _DB_PREFIX_ . 'sellerinfo WHERE id_sellerinfo=' . (int)$id_sellerinfo . ')';
		Db::getInstance()->Execute($sql); 
		
				$sql = 'DELETE FROM `'._DB_PREFIX_.'sellerinfo` WHERE id_sellerinfo =' . (int)$id_sellerinfo;
		Db::getInstance()->Execute($sql);        

		$sql = 'DELETE FROM `'._DB_PREFIX_.'sellerinfo_lang` WHERE id_sellerinfo =' . (int)$id_sellerinfo;
		Db::getInstance()->Execute($sql);        

		$sql = 'DELETE FROM `'._DB_PREFIX_.'agile_seller_paymentinfo` WHERE id_seller =' . (int)$id_seller;
		Db::getInstance()->Execute($sql);        
		
		$mid = (int)$info['id_manufacturer'];
		if($mid > 0)
		{
			$sql = 'DELETE FROM `'._DB_PREFIX_.'manufacturer_lang` WHERE id_manufacturer=' . $mid;
			Db::getInstance()->Execute($sql); 
			$sql = 'DELETE FROM `'._DB_PREFIX_.'address` WHERE id_manufacturer=' . $mid;
			Db::getInstance()->Execute($sql); 
			$sql = 'DELETE FROM `'._DB_PREFIX_.'manufacturer` WHERE id_manufacturer=' . $mid;
			Db::getInstance()->Execute($sql); 
			$sql = 'DELETE FROM `'._DB_PREFIX_.'manufacturer_shop` WHERE id_manufacturer=' . $mid;
			Db::getInstance()->Execute($sql); 
		}
		
		$sid = (int)$info['id_supplier'];
		if($sid > 0)
		{
			$sql = 'DELETE FROM `'._DB_PREFIX_.'supplier_lang` WHERE id_supplier=' .$sid;
			Db::getInstance()->Execute($sql); 
			$sql = 'DELETE FROM `'._DB_PREFIX_.'address` WHERE id_supplier=' .$sid;
			Db::getInstance()->Execute($sql); 
			$sql = 'DELETE FROM `'._DB_PREFIX_.'supplier` WHERE id_supplier=' .$sid;
			Db::getInstance()->Execute($sql); 
			$sql = 'DELETE FROM `'._DB_PREFIX_.'supplier_shop` WHERE id_supplier=' .$sid;
			Db::getInstance()->Execute($sql); 
		}
	}	
	
	public static function getProducts($id_seller, Context $context, $nb_only=false, $p=1, $n=10, $orderby='', $orderway='', $extraConditions='')
	{
		$select = '
		    SELECT distinct p.*, pl.name,pl.link_rewrite, cl.name `name_category`, i.`id_image`, 0 AS price_final, sav.`quantity` as sav_quantity
		        ,po.approved
	        ';
								$from = '
				FROM `'._DB_PREFIX_.'product` p
				INNER JOIN '._DB_PREFIX_.'product_shop product_shop ON (product_shop.id_product = p.id_product AND product_shop.id_shop = ' . Configuration::get('PS_SHOP_DEFAULT') . ')
				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (pl.id_lang=' . $context->language->id . ' AND p.`id_product` = pl.`id_product` '.Shop::addSqlRestrictionOnLang('pl').')
				LEFT JOIN `'._DB_PREFIX_.'tax_rule` tr ON (product_shop.`id_tax_rules_group` = tr.`id_tax_rules_group`
		 		  AND tr.`id_country` = '.(int)$context->country->id.'
		 		  AND tr.`id_state` = 0)
	  		 	LEFT JOIN `'._DB_PREFIX_.'tax` t ON (t.`id_tax` = tr.`id_tax`)
				LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`)
				LEFT JOIN `'._DB_PREFIX_.'supplier` s ON (s.`id_supplier` = p.`id_supplier`)
			LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product` AND i.`cover` = 1)
			LEFT JOIN `'._DB_PREFIX_.'stock_available` sav ON (sav.`id_product` = p.`id_product` AND sav.`id_product_attribute` = 0
				AND sav.`id_shop` = '.(int)$context->shop->id.')
		    LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (p.id_category_default=cl.id_category AND cl.id_lang=' . $context->language->id . ')
		    LEFT JOIN `'._DB_PREFIX_.'product_owner` po ON p.id_product=po.id_product
		';
		$where =' WHERE po.id_owner>0 AND po.id_owner = ' . intval($id_seller) . ' 
		';

		if($extraConditions != '')$where = $where . $extraConditions;

		if($nb_only)  return Db::getInstance()->getValue('SELECT COUNT(*) ' . $from . $where);
		if($p < 1) $p = 1;
		if($n < 1) $n = 10;
		if(empty($orderby))$orderby ='p.date_add';
		if(empty($orderway))$orderway ='DESC';

		$sort = '';
		if($orderby != 'p.price_final')$sort = ' ORDER BY ' . $orderby . ' ' . $orderway;

		$limit = ' LIMIT '.(((int)($p) - 1) * (int)($n)).','.(int)($n);
		
		$sql = $select . $from .  $where . $sort . $limit;
				$res = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
		if(empty($res))return array();
		$currency = new Currency((int)Configuration::get('PS_CURRENCY_DEFAULT'));

		
		for($i=0; $i< count($res); $i++)
		{
			$res[$i]['price_final'] = Product::getPriceStatic($res[$i]['id_product'], true, null, 6, null, false, true, 1, true) * $currency->conversion_rate/Context::getContext()->currency->conversion_rate;
		}
		
		if($orderby == "p.price_final")
		{
			if($orderway == "ASC")
				usort($res, function ($a, $b){ return $a['price_final'] > $b['price_final'];});
			else
				usort($res, function ($a, $b){ return $a['price_final'] < $b['price_final'];});
		}
		
		return $res;
	}

		public static function getProductsBySeller($id_seller, Context $context, $nb_only=false, $p=1, $n=10, $orderby='', $orderway='', $extraConditions='')
	{
		$agile_sql_parts = AgileSellerManager::getAdditionalSqlForProducts("p");
		
		$select = '
		    SELECT distinct p.*, pl.name,pl.link_rewrite, cl.name `name_category`, i.`id_image`, 0 AS price_final, sav.`quantity` as sav_quantity
		        ,po.approved
	        ';
								$from = '
				FROM `'._DB_PREFIX_.'product` p
				INNER JOIN '._DB_PREFIX_.'product_shop product_shop ON (product_shop.id_product = p.id_product AND product_shop.id_shop = ' . Configuration::get('PS_SHOP_DEFAULT') . ')
				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (pl.id_lang=' . $context->language->id . ' AND p.`id_product` = pl.`id_product` '.Shop::addSqlRestrictionOnLang('pl').')
				LEFT JOIN `'._DB_PREFIX_.'tax_rule` tr ON (product_shop.`id_tax_rules_group` = tr.`id_tax_rules_group`
		 		  AND tr.`id_country` = '.(int)$context->country->id.'
		 		  AND tr.`id_state` = 0)
	  		 	LEFT JOIN `'._DB_PREFIX_.'tax` t ON (t.`id_tax` = tr.`id_tax`)
				LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`)
				LEFT JOIN `'._DB_PREFIX_.'supplier` s ON (s.`id_supplier` = p.`id_supplier`)
			LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product` AND i.`cover` = 1)
			LEFT JOIN `'._DB_PREFIX_.'stock_available` sav ON (sav.`id_product` = p.`id_product` AND sav.`id_product_attribute` = 0
				AND sav.`id_shop` = '.(int)$context->shop->id.')
		    LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (p.id_category_default=cl.id_category AND cl.id_lang=' . $context->language->id . ')
		    LEFT JOIN `'._DB_PREFIX_.'product_owner` po ON p.id_product=po.id_product
			' . $agile_sql_parts['joins'] . '
		';
		$where =' WHERE po.id_owner>0 
			AND po.id_owner = ' . intval($id_seller) . ' 
			' . $agile_sql_parts['wheres'] . '
		';

		if($extraConditions != '')$where = $where . $extraConditions;

		if($nb_only)  return Db::getInstance()->getValue('SELECT COUNT(*) ' . $from . $where);
		if($p < 1) $p = 1;
		if($n < 1) $n = 10;
		if(empty($orderby))$orderby ='p.date_add';
		if(empty($orderway))$orderway ='DESC';

		$sort = ' ORDER BY ' . $orderby . ' ' . $orderway . ' 
        ';

		$limit = ' LIMIT '.(((int)($p) - 1) * (int)($n)).','.(int)($n) . '
		';
		
		$sql = $select . $from .  $where . $sort . $limit;
				$res = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
		if(empty($res))return array();
		$currency = new Currency((int)Configuration::get('PS_CURRENCY_DEFAULT'));

		
		for($i=0; $i< count($res); $i++)
		{
			$res[$i]['price_final'] = Product::getPriceStatic($res[$i]['id_product'], true, null, 6, null, false, true, 1, true) * $currency->conversion_rate/Context::getContext()->currency->conversion_rate;
		}
		return $res;
	}

	public function getProductsByLocation($location_level, $location, $id_lang, $p, $n, $orderBy = NULL, $orderWay = NULL, $getCount = false, $active = true)
    {

		$id_seller_country = (int)Tools::getValue('id_seller_country');

		if ($p < 1) $p = 1;
        if ($n <= 0)$n = 10;

		if (empty($orderBy))
			$orderBy = 'price';
		else
						$orderBy = strtolower($orderBy);

		if (empty($orderWay))
			$orderWay = 'ASC';
		if ($orderBy == 'id_product' OR	$orderBy == 'date_add')
			$orderByPrefix = 'p';
		elseif ($orderBy == 'name')
			$orderByPrefix = 'pl';
		elseif ($orderBy == 'manufacturer')
		{
			$orderByPrefix = 'm';
			$orderBy = 'name';
		}

		if ($orderBy == 'price')
			$orderBy = 'orderprice';

		if (!Validate::isBool($active) OR !Validate::isOrderBy($orderBy) OR !Validate::isOrderWay($orderWay))
			die (Tools::displayError());

        $requiredcond = '';
        if(intval(Configuration::get('AGILE_MS_PRODUCT_APPROVAL')) ==1)
            $requiredcond = ' AND po.approved = 1 ';

		$joins = '';
		$wheres = '';
        if(Module::isInstalled('agilesellerlistoptions'))
        {
			require_once(_PS_ROOT_DIR_ . "/modules/agilesellerlistoptions/agilesellerlistoptions.php");

            $joins = $joins . '
                LEFT JOIN `'._DB_PREFIX_.'seller_listoption` slb ON (p.id_product = slb.id_product AND slb.id_option = ' . AgileSellerListOptions::ASLO_OPTION_LIST . ')
                ';
                
			$aslo_list_prod_id = intval(Configuration::get('ASLO_PROD_FOR_OPTION' . AgileSellerListOptions::ASLO_OPTION_LIST));
			$wheres = $wheres . ' 
    		    AND (slb.status = ' . AgileSellerListOptions::ASLO_STATUS_IN_EFFECT . '  OR IFNULL(po.id_owner,0) = 0 OR ' . $aslo_list_prod_id . '=' . AgileSellerListOptions::ASLO_ALWAYS_FREE .')
                ';
        }

		$location_conditions = '';
		switch($location_level)
		{
			case 'country':
				if((int)$location >0)
					$location_conditions = ' AND si.id_country=' . (int)$location;
				break;
			case 'state':
				if((int)$location >0)
					$location_conditions = ' AND si.id_state=' . (int)$location;
				break;
			case 'city':
				if(!empty($location))
					$location_conditions = ' AND sil.city=\'' . $location . '\'';
				break;
			case 'sellertype':
				if(!empty($location))
					$location_conditions = ' AND (si.id_sellertype1=' . (int)$location . ' OR si.id_sellertype2 = ' . (int)$location . ')';
				break;
			case 'custom':
				if(!empty($id_location))
				{
					if(AgileMultipleShop::SHOP_BY_CUSTOM_LANG)
					{
						$location_conditions = ' AND sil.' . AgileMultipleShop::SHOP_BY_CUSTOM_FIELD . '=\'' . $location . '\'';
					}
					else
					{
						$location_conditions = ' AND si.' . AgileMultipleShop::SHOP_BY_CUSTOM_FIELD . '=\'' . $location . '\'';
					}
				}
				break;
		}

				if ($getCount)
		{		
			$sql = '
			SELECT COUNT(po.`id_product`) AS total
			FROM `'._DB_PREFIX_.'product` p
			LEFT JOIN `'._DB_PREFIX_.'product_owner` po ON p.`id_product` = po.`id_product`
	        LEFT JOIN `'._DB_PREFIX_.'sellerinfo` si ON si.`id_seller` = po.`id_owner`
	        LEFT JOIN `'._DB_PREFIX_.'sellerinfo_lang` sil ON si.`id_sellerinfo` = sil.`id_sellerinfo` AND sil.id_lang = ' . $id_lang . '
			' . $joins . '
			WHERE p.active=1  AND p.`visibility` IN ("both", "catalog")
				' . $location_conditions .'
			    ' . ($active ? ' AND p.`active` = 1' : '') . '
				' . $requiredcond . '
				' . $wheres .'
			    ';

			$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($sql);

			return isset($result) ? $result['total'] : 0;
		}

		$sql = '
		        SELECT p.*, pa.`id_product_attribute`, pl.`description`, pl.`description_short`, pl.`available_now`, pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, i.`id_image`, il.`legend`, m.`name` AS manufacturer_name, tl.`name` AS tax_name, t.`rate`, cl.`name` AS category_default, DATEDIFF(p.`date_add`, DATE_SUB(NOW(), INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY)) > 0 AS new,
			        (p.`price` * IF(t.`rate`,((100 + (t.`rate`))/100),1)) AS orderprice
		        FROM `'._DB_PREFIX_.'product_owner` po
		        LEFT JOIN `'._DB_PREFIX_.'sellerinfo` si ON si.`id_seller` = po.`id_owner`
		        LEFT JOIN `'._DB_PREFIX_.'sellerinfo_lang` sil ON si.`id_sellerinfo` = sil.`id_sellerinfo` AND sil.id_lang = ' . $id_lang . '
		        LEFT JOIN `'._DB_PREFIX_.'product` p ON p.`id_product` = po.`id_product`
		        LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON (p.`id_product` = pa.`id_product` AND default_on = 1)
		        LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (p.`id_category_default` = cl.`id_category` AND cl.`id_lang` = '.(int)($id_lang).')
		        LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = '.(int)($id_lang).')
		        LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product` AND i.`cover` = 1)
		        LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)($id_lang).')
		        LEFT JOIN `'._DB_PREFIX_.'tax_rule` tr ON (p.`id_tax_rules_group` = tr.`id_tax_rules_group`
		                                                   AND tr.`id_country` = '.(int)(_PS_VERSION_>'1.5' ? Context::getContext()->country->id :  Country::getDefaultCountryId()).'
	                                           	           AND tr.`id_state` = 0)
	            LEFT JOIN `'._DB_PREFIX_.'tax` t ON (t.`id_tax` = tr.`id_tax`)
		        LEFT JOIN `'._DB_PREFIX_.'tax_lang` tl ON (t.`id_tax` = tl.`id_tax` AND tl.`id_lang` = '.(int)($id_lang).')
		        LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON m.`id_manufacturer` = p.`id_manufacturer`
				' . $joins . '
		        WHERE p.active=1  AND p.`visibility` IN ("both", "catalog")
					' . $location_conditions. '
        			' . $requiredcond . '
					' . $wheres . '
		        ';

		$sql .= ' ORDER BY '.(isset($orderByPrefix) ? $orderByPrefix.'.' : '').'`'.pSQL($orderBy).'` '.pSQL($orderWay).'
				LIMIT '.(((int)($p) - 1) * (int)($n)).','.(int)($n);


		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS($sql);

		if ($orderBy == 'orderprice')
			Tools::orderbyPrice($result, $orderWay);

		if (!$result)
			return array();

				return Product::getProductsProperties($id_lang, $result);

	}	

	public static function getOrders($id_seller, $showHiddenStatus = false, Context $context = null, $nb_only=false, $p=1, $n=10, $orderby='', $orderway='',$extraConditions='')
	{
		if(!intval($id_seller))return;
		if (!$context)
			$context = Context::getContext();

		$select = '
		SELECT o.id_order 
				, CONCAT(LEFT(c.`firstname`, 1), \'. \', c.`lastname`) AS `customer`
    			, IF((SELECT COUNT(so.id_order) FROM `'._DB_PREFIX_.'orders` so WHERE so.id_customer = o.id_customer) > 1, 0, 1) as new
				, (SELECT SUM(od.`product_quantity`) FROM `'._DB_PREFIX_.'order_detail` od WHERE od.`id_order` = o.`id_order`) nb_products
				, CONCAT(LEFT(c.`firstname`, 1), \'. \', c.`lastname`) AS `customer`
                ,o.date_add
				,o.payment
				,o.total_paid
				,oo.id_owner
				,o.id_customer
				,o.id_currency as id_currency
				,o.reference
				,cy.name AS currency
				,CONCAT(c.`firstname`, \' \', c.`lastname`) AS `customer`
			    ,osl.`name` AS `osname`
				,os.`color`
			    ,IFNULL(ac.`id_cart_parent`,o.id_cart) AS id_transaction
		';
		
		$from = '
		FROM `'._DB_PREFIX_.'orders` o
		    LEFT JOIN `'._DB_PREFIX_.'customer` c ON (o.id_customer=c.id_customer) 
		    LEFT JOIN `'._DB_PREFIX_.'order_owner` oo ON (o.id_order=oo.id_order) 
			LEFT JOIN `'._DB_PREFIX_.'currency` cy ON (o.`id_currency`=cy.id_currency)
            LEFT JOIN `'._DB_PREFIX_.'sellerinfo` s ON (oo.`id_owner`=s.`id_seller`)
            LEFT JOIN `'._DB_PREFIX_.'sellerinfo_lang` sl ON (sl.`id_sellerinfo`=s.`id_sellerinfo` AND sl.id_lang=' . $context->language->id . ')
	 	    LEFT JOIN `'._DB_PREFIX_.'order_history` oh ON (oh.`id_order` = o.`id_order` and o.current_state = oh.id_order_state)
		    LEFT JOIN `'._DB_PREFIX_.'order_state` os ON (os.`id_order_state` = oh.`id_order_state`)
		    LEFT JOIN `'._DB_PREFIX_.'order_state_lang` osl ON (os.`id_order_state` = osl.`id_order_state` AND osl.`id_lang` = '.(int)($context->language->id).')
		    LEFT JOIN `'._DB_PREFIX_.'agile_subcart` ac ON o.id_cart = ac.id_cart
			LEFT JOIN `'._DB_PREFIX_.'order_invoice` oi ON o.id_order = oi.id_order
			
		';
		
		$where = '
    		WHERE oo.`id_owner` = '.(int)$id_seller.'
    	';

		if(!empty($extraConditions))$where .= $extraConditions;
		
		$sql = 'SELECT COUNT(*) ' . $from . $where;
		if($nb_only) return Db::getInstance()->getValue($sql);

		if($p < 1) $p = 1;
		if($n < 1) $n = 10;
		if(empty($orderby))$orderby ='o.date_add';
		if(empty($orderway))$orderway ='DESC';

		$sort = ' ORDER BY ' . $orderby . ' ' . $orderway . ' 
        ';

		$limit = ' LIMIT '.(((int)($p) - 1) * (int)($n)).','.(int)($n) . '
		';


		$sql = $select . $from .  $where . $sort . $limit;
		$res = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

		if (!$res)
			return array();

				foreach ($res as $key => $val)
		{
			$sql = '
				SELECT os.`id_order_state`, osl.`name` AS order_state, os.`invoice`
				FROM `'._DB_PREFIX_.'order_history` oh
				LEFT JOIN `'._DB_PREFIX_.'order_state` os ON (os.`id_order_state` = oh.`id_order_state`)
				INNER JOIN `'._DB_PREFIX_.'order_state_lang` osl ON (os.`id_order_state` = osl.`id_order_state` AND osl.`id_lang` = '.(int)$context->language->id.')
			WHERE oh.`id_order` = '.(int)($val['id_order']).(!$showHiddenStatus ? ' AND os.`hidden` != 1' : '').'
				ORDER BY oh.`date_add` DESC, oh.`id_order_history` DESC
			LIMIT 1';
			
			$res2 = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

			if ($res2)
				$res[$key] = array_merge($res[$key], $res2[0]);

		}
		return $res;
	}

	
	
	public static function linkCustomerAndSeller($id_seller,$id_customer)
	{
		if(intval($id_customer) <=0 OR intval($id_seller) <=0)return;
		$sql = 'UPDATE `'._DB_PREFIX_.'sellerinfo` SET id_customer=' . intval($id_customer) . ' WHERE id_seller=' . intval($id_seller);
		Db::getInstance()->Execute($sql);
	}
	
	public static function getNumOfProducts($id_seller)
	{
		if(intval($id_seller) <=0)return 0;
		$sql = 'SELECT count(`id_owner`) AS num from `' . _DB_PREFIX_ . 'product_owner` where `id_owner`=' . intval($id_seller);
		$result = Db::getInstance()->getRow($sql);
		if(isset($result['num']) AND intval($result['num'])>0)return intval($result['num']);
		return 0;
	}

	public static function getNumOfOrders($id_seller)
	{
		if(intval($id_seller) <=0)return 0;
		$sql = 'SELECT count(`id_owner`) AS num from `' . _DB_PREFIX_ . 'order_owner` where `id_owner`=' . intval($id_seller);
		$result = Db::getInstance()->getRow($sql);
		if(isset($result['num']) AND intval($result['num'])>0)return intval($result['num']);
		return 0;
	}
	
	public static function getAccountBalance($id_seller)
	{
		if(intval($id_seller)<=0)return 0;
		$sql = '
            SELECT balance 
            FROM  `'._DB_PREFIX_.'seller_commission` 
            WHERE id_seller =' . $id_seller . '
            ORDER BY id_seller_commission DESC
            ';
		$ret = Db::getInstance()->getValue($sql);
		return floatval($ret);
	}
	
	public static function getTotalAmountSold($id_seller)
	{
		if(intval($id_seller) <=0)return 0;
		$sql = '
                SELECT SUM( (product_quantity  - product_quantity_refunded  ) * 
                            (product_price 
                              - CASE WHEN reduction_percent>0 THEN product_price * reduction_percent/100 ELSE reduction_amount END
                              - product_price * group_reduction/100
                             )
                            - CASE WHEN IFNULL(discount_quantity_applied,0) = 1 THEN IFNULL(product_quantity_discount,0) ELSE 0 END
                           ) AS total
						,o.id_currency
                FROM `' . _DB_PREFIX_ . 'order_detail` a
                    LEFT JOIN `' . _DB_PREFIX_ . 'order_owner` oo ON (a.id_order=oo.id_order)
                    LEFT JOIN `' . _DB_PREFIX_ . 'orders` o ON (a.id_order=o.id_order)
                WHERE oo.id_owner =  '. intval($id_seller) . '
					AND current_state NOT IN (6,7)
				GROUP BY o.id_currency
                ';

		$result = Db::getInstance()->ExecuteS($sql);
		
		$totals = array();
		if(empty($result))return array(array('currency' => new Currency((int)Configuration::get('PS_CURRENCY_DEFAULT')), 'amount' => 0));
		foreach($result as $res)
		{
			$totals[] = array('currency' => new Currency($res['id_currency']), 'amount' => Tools::ps_round($res['total'],2));
		}
		return $totals;
	}
	
	
	public static function assign_all_products_under_category($id_category)
	{
		if(!intval($id_category))return;
		$id_owner =  (int)AgileSellerManager::getObjectOwnerID('category', $id_category);
		if(!$id_owner)return;
		
		$list = implode(AgileSellerManager::get_all_children($id_category), ",");
		$sql = 'SELECT cp.id_product AS id_product, IFNULL(po.id_owner,-1) AS id_owner
				FROM ' . _DB_PREFIX_ . 'category_product cp
				LEFT JOIN ' . _DB_PREFIX_ . 'product_owner po on cp.id_product = po.id_product
				WHERE cp.id_category IN (' . $list . ')
					AND  IFNULL( po.id_owner , -1 ) != ' . $id_owner . '
		 ';
				$rows = Db::getInstance()->ExecuteS($sql);
		if(empty($rows))return;
		$inserts = array();
		$updates = array();
		foreach($rows as $row)
		{
			if($row['id_owner'] >=0)$updates[] = $row['id_product'];
			else $inserts[] = $row['id_product'];
		}
				if(!empty($inserts))
		{
			$sql = 'INSERT INTO ' . _DB_PREFIX_  .'product_owner (id_product,id_owner,date_add) VALUES ';
			$idx =0;
			foreach($inserts as $id_product)
			{
				if($idx>0)$sql .= ',';
				$sql .= '(' . $id_product . ',' . $id_owner . ',NOW())';
				$idx++;
			}
						Db::getInstance()->Execute($sql);
			
		}
		if(!empty($updates))
		{
			$sql = 'UPDATE ' . _DB_PREFIX_ . 'product_owner SET id_owner = ' . $id_owner . ' WHERE id_product IN (' . implode($updates, ',') . ')';
			Db::getInstance()->Execute($sql);
					}
		
	}
	
	public static function get_all_children($id_category)
	{
		$list = array();
		if(!intval($id_category))return $list;
		$list[] = intval($id_category);
		$sql = 'SELECT id_category FROM ' . _DB_PREFIX_ . 'category where id_parent=' . $id_category;
		$recs = Db::getInstance()->ExecuteS($sql);
		if(empty($recs))return $list;
		foreach($recs as $rec)
		{
			$list = array_merge($list, AgileSellerManager::get_all_children($rec['id_category']));
		}
		return $list;
	}
		public static function get_order_id_from_maildata($templateVars)
	{
		return AgileHelper::get_order_id_from_maildata($templateVars);
	}
	


	public static function adjust_shipping_cost_carriers($templateVars)
	{
		if(_PS_VERSION_ < '1.5')return;
		if(!Module::isInstalled('agilesellershipping'))return;
		$id_order = AgileHelper::get_order_id_from_maildata($templateVars);
		if(!intval($id_order))return;
		include_once(_PS_ROOT_DIR_ . "/modules/agilesellershipping/agilesellershipping.php");

		$order = new Order($id_order);
		if(!Validate::isLoadedObject($order))return;
				Db::getInstance()->Execute('DELETE FROM ' . _DB_PREFIX_ . 'order_carrier WHERE id_order=' . $id_order);
		
		$theCart = new Cart($order->id_cart);
		$id_zone = SellerShipping::getZoneID($theCart->id_address_delivery, $theCart->id_customer);
		$products = $theCart->getProducts();
		$product_index = array();
		foreach($products as $p)
		{
			$product_index[$p['id_product']] = $p;
		}

		$id_seller = AgileSellerManager::getObjectOwnerID('order', $order->id);
				if($id_seller <=0)$id_seller =0; 
				$carrier_products = SellerShipping::get_carrier_products($order->id_cart, $id_seller);
				$carrier_amounts = $theCart->get_carrier_product_amount($carrier_products, $products,$product_index);
		foreach($carrier_amounts AS $id_carrier=>$carrier_amount)
		{
						$carrier = new Carrier($id_carrier);
			$carrier_weight = $theCart->getTotalWeightOfCarrier($id_carrier,$id_seller); 
			if($theCart->is_all_virtual($id_carrier, $carrier_products,$product_index))
			{
				$carrier_cost_wt = 0;
				$carrier_cost_nt = 0;
			}
			else
			{
				$carrier_cost_wt = $theCart->getOrderShippingCostPerSellerCarrier($id_seller, true, $id_zone, $id_carrier, $carrier_amount,$carrier_weight);
				$carrier_cost_nt = $theCart->getOrderShippingCostPerSellerCarrier($id_seller, true, $id_zone, $id_carrier, $carrier_amount,$carrier_weight);
			}
			$sql = 'INSERT INTO ' . _DB_PREFIX_ . 'order_carrier (id_order,id_carrier,id_order_invoice,weight,shipping_cost_tax_excl,shipping_cost_tax_incl,tracking_number,date_add) VALUES
				(' . $order->id. ','. $id_carrier . ',' . $order->invoice_number . ',' . $carrier_weight . ','. $carrier_cost_nt  . ',' .$carrier_cost_wt .',\'\',\''. date('Y-m-d H:i:s') . '\')
			';
			Db::getInstance()->Execute($sql);
									$sql = 'UPDATE ' . _DB_PREFIX_ . 'orders SET id_carrier=' . (int) $id_carrier . ' WHERE id_order=' . (int)$id_order;
			Db::getInstance()->Execute($sql);
		}
		$order_total_wt = $theCart->getOrderTotal(true);
		$order_total_nt = $theCart->getOrderTotal(false);
		$shipping_total_wt = $theCart->getOrderTotal(true,Cart::ONLY_SHIPPING);
		$shipping_total_nt = $theCart->getOrderTotal(false,Cart::ONLY_SHIPPING);
				$sql = 'UPDATE ' . _DB_PREFIX_ . 'orders SET total_shipping=' . $shipping_total_wt . ',total_shipping_tax_incl=' . $shipping_total_wt . ',total_shipping_tax_excl=' . $shipping_total_nt . ',total_paid=' . $order_total_wt . ',total_paid_tax_excl=' . $order_total_nt . ', total_paid_tax_incl=' . $order_total_wt  . ' WHERE id_order=' . $id_order;
		Db::getInstance()->Execute($sql);

		$sql = 'UPDATE ' . _DB_PREFIX_ . 'order_invoice SET total_shipping_tax_incl=' . $shipping_total_wt . ',total_shipping_tax_excl=' . $shipping_total_nt . ',total_paid_tax_excl=' . $order_total_nt . ', total_paid_tax_incl=' . $order_total_wt  . ' WHERE id_order=' . $id_order;
		Db::getInstance()->Execute($sql);
	}
	
	public static function limited_by_membership($id_seller)
	{
		if(intval($id_seller) >0 AND Module::isInstalled('agilemembership') AND intval(Configuration::get('AGILE_MEMBERSHIP_SELLER_INTE'))>0)
		{
			include_once(_PS_ROOT_DIR_  . "/modules/agilemultipleseller/SellerInfo.php");
			include_once(_PS_ROOT_DIR_  . "/modules/agilemembership/agilemembership.php");
			include_once(_PS_ROOT_DIR_  . "/modules/agilemembership/MembershipType.php");
			include_once(_PS_ROOT_DIR_  . "/modules/agilemembership/CustomerMembership.php");
			$sellerinfo = new SellerInfo(SellerInfo::getIdBSellerId($id_seller));
			
			$membership = new CustomerMembership(CustomerMembership::getIdByCustomerId($sellerinfo->id_customer));

			if(!Validate::isLoadedObject($membership))return true;
			$membershiptype = new MembershipType($membership->id_membership_type);
						if(!Validate::isLoadedObject($membershiptype))return true;

			if(!AgileMembership::isActivated($sellerinfo->id_customer) OR !AgileMembership::isMemberFeePaid($sellerinfo->id_customer))
				return true;

			$num = AgileSellerManager::getAcitveProductsListed($id_seller);
			if($num >= intval($membershiptype->listing_max) AND intval($membershiptype->listing_max)>0)return true;
			return false;
		}

		return false;
	}
	
	public static function getCarriersBySellerId($id_seller, $id_lang)
	{
		$sql = 'Select c.id_carrier, c.name, c.active, c.is_free, cl.delay,  IFNULL(co.id_owner,0) AS id_owner 
			from '. _DB_PREFIX_ . 'carrier c 
				inner join '. _DB_PREFIX_ . 'carrier_owner co on c.id_carrier = co.id_carrier 
				LEFT JOIN '._DB_PREFIX_.'carrier_lang cl ON (c.id_carrier = cl.id_carrier AND cl.id_lang = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('cl').')
			where c.deleted = 0 
	                AND IFNULL(co.id_owner,0) = ' . (int)$id_seller . '
			';

		return Db::getInstance()->executeS($sql);
	}

	public static function getPickupCentersBySellerId($id_seller, $id_lang)
	{
		$id_carrier = (int)Configuration::getGlobalValue('AGILE_PICKUPCENTER_CARRIER_ID');

		$sql = 'Select l.id_location, l.id_carrier, c.name as carrier,l.location, l.active,l.address1,l.postcode,l.city,cl.name as country 
			from ' . _DB_PREFIX_ . 'location l '
			. 'INNER JOIN ' . _DB_PREFIX_ . 'carrier c on c.id_carrier=l.id_carrier '
			. 'LEFT JOIN '  . _DB_PREFIX_ .'country_lang cl ON (l.id_country = cl.id_country AND cl.id_lang = '
 			. (int)$id_lang
			.' ) WHERE l.deleted = 0  AND l.id_seller = ' . (int)$id_seller 
			. ' AND l.id_carrier = ' . $id_carrier;
		return Db::getInstance()->executeS($sql);
	}

	public static function getTaxes($id_lang)
	{
		$sql = 'Select * from '. _DB_PREFIX_ . 'tax_lang where id_lang = '.$id_lang;
		return Db::getInstance()->executeS($sql);
	}

					public static function get_id_seller_for_filter()
	{
		$context = Context::getContext();
		
				include_once(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/SellerInfo.php");
		$pagename = AgileHelper::getPageName();
		switch($pagename)
		{
						case 'sellercarrierdetail.php':
			case 'sellercarrierranges.php':
			case 'sellercarriers.php':
				$sellerinfo = new SellerInfo(SellerInfo::getIdByCustomerId($context->customer->id));
				return $sellerinfo->id_seller;

			case 'sellerproductdetail.php':
			case 'adminproducts.php':
								if(!Module::isInstalled('agilemultipleseller'))return 0; 
				if(Module::isInstalled('agilesellershipping'))
				{
					return AgileSellerManager::getObjectOwnerID('product',(int)Tools::getValue("id_product"));
				}
				else
				{
					if((int)$context->cookie->id_employee >0 AND (int)$context->cookie->profile == (int)Configuration::get('AGILE_MS_PROFILE_ID'))return (int)$context->cookie->id_employee;
					return 0;
				}
				break;
			
			case 'admincarriers.php':
			case 'adminshipping.php':
			case 'adminrangeprice.php':
			case 'adminrangeweight.php':
												if((int)$context->cookie->id_employee >0 AND (int)$context->cookie->profile == (int)Configuration::get('AGILE_MS_PROFILE_ID'))return (int)$context->cookie->id_employee;
				return 0;

			default:
								if(Module::isInstalled('agilesellershipping'))return 0; 
								if((int)Configuration::get('AGILE_MS_CART_MODE') != 1)return 0;
				
								if(!Validate::isLoadedObject($context->cart))return 0;
				$products = $context->cart->getProducts();
				if (!sizeof($products))return 0;
				$product = array_shift($products);
				return intval(AgileSellerManager::getObjectOwnerID('product', $product['id_product']));
		}
	}
	
	public static function get_id_seller_for_filter4att()
	{
		$context = Context::getContext();
		
				include_once(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/SellerInfo.php");
		$pagename = AgileHelper::getPageName();
		switch($pagename)
		{
			case 'sellerproductdetail.php':
				$sellerinfo = new SellerInfo(SellerInfo::getIdByCustomerId($context->cookie->id_customer));
				return $sellerinfo->id_seller;

			case 'adminproducts.php':
								if($context->cookie->profile == (int)Configuration::get('AGILE_MS_PROFILE_ID'))return $context->cookie->id_employee;
				
				return 0;

			default:
				return 0;
		}
	}	

	public static function current_logged_seller_id()
	{
		if(!isset($context->cookie))return 0;
		
		if($context->cookie->id_employee>0)		{
			if(intval($context->cookie->profile) == Configuration::get('AGILE_MS_PROFILE_ID'))
				return intval($context->cookie->id_employee);
			else
				return 0;			 
		}
		else 		{
			include_once(_PS_ROOT_DIR_  . "/modules/agilemultipleseller/SellerInfo.php");
			$sellerinfo = new SellerInfo(SellerInfo::getIdByCustomerId(Context::getContext()->customer->id), Context::getContext()->language->id);
			if(Validate::isLoadedObject($sellerinfo))return $sellerinfo->id_seller;
			return 0;
		}
	}
	
	public static function get_current_logged_seller_home_category_id()
	{
		if(!Module::isInstalled('agilemultipleseller'))return 0;
		require_once(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/SellerInfo.php");
		$id_seller = AgileSellerManager::current_logged_seller_id();
		if($id_seller<=0)return 0;
		$sellerinfo = new SellerInfo(SellerInfo::getIdBSellerId($id_seller));
		if($sellerinfo->id_category_default <=2 )return  0;
		$category = new Category($sellerinfo->id_category_default,  Context::getContext()->language->id);			
		if(!Validate::isLoadedObject($category))return 0;
		return $sellerinfo->id_category_default;
	}
	
	public static function getXRObjectID($table, $objid)
	{
		$eaccess = AgileSellerManager::get_entity_access($table);
		$xr_objid = intval(Tools::getValue('id_' . $eaccess['owner_xr_table']));
		if($xr_objid == 0)
		{
			$sql = 'SELECT id_' . $eaccess['owner_xr_table'] . ' FROM ' . _DB_PREFIX_ . $table . ' WHERE id_' . $table . '=' . $objid  ;
			$xr_objid = Db::getInstance()->getValue($sql);
		}		
		return $xr_objid;
	}
	
				public static function seller_payment_uses($id_cart, $module)
	{
		$sql = 'SELECT COUNT(*) AS num, SUM(in_use) AS in_use
				FROM
				(
					SELECT IFNULL(po.id_owner,0) AS id_seller, MAX(CASE WHEN IFNULL(po.id_owner,0) = 0 OR IFNULL(si.payment_collection,0) = 1 OR IFNULL(asp.in_use,0)=1 THEN 1 ELSE 0 END) AS in_use 
					FROM ' . _DB_PREFIX_ . 'cart_product cp
						LEFT JOIN ' . _DB_PREFIX_ . 'product_owner po on cp.id_product=po.id_product
						LEFT JOIN ' . _DB_PREFIX_ . 'agile_seller_paymentinfo asp ON po.id_owner=asp.id_seller AND asp.module_name=\'' . $module . '\'
						LEFT JOIN ' . _DB_PREFIX_ . 'sellerinfo si on po.id_owner=si.id_seller
					WHERE id_cart = ' . (int)$id_cart . '
					GROUP BY IFNULL(po.id_owner,0)
				) T		
				';
		$row = Db::getInstance()->getRow($sql);

		if((int)$row['num'] == (int)$row['in_use'])return 1;
		if((int)$row['in_use'] == 0)return 0;
		else return 2;
		
	}
	
	public static function getAdditionalSqlForProducts($prefix, $no_special_products = false)
	{
		$context = Context::getContext();
		
		$joins = '';
		$wheres = '';
		$selects = '';

		if(Module::isInstalled('agilemultipleseller'))
		{
			require_once(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/agilemultipleseller.php");
			$joins = $joins . '
                 LEFT JOIN `'._DB_PREFIX_.'product_owner` a_po ON ' . $prefix  . '.id_product=a_po.id_product
                 LEFT JOIN `'._DB_PREFIX_.'sellerinfo` a_s ON a_po.id_owner=a_s.id_seller
                 LEFT JOIN `'._DB_PREFIX_.'sellerinfo_lang` a_sl ON (a_sl.id_sellerinfo=a_s.id_sellerinfo  AND a_sl.id_lang=' . intval($context->language->id). ')
                 ';

			$selects = $selects . '
                ,a_sl.company as seller, a_s.id_seller
            ';
			
			if(Module::isInstalled('agilesellerproducts') || Module::isInstalled('agilemultipleshop'))
				$selects = $selects . '
                    ,1 AS has_sellerlink
                ';

						$wheres = $wheres . (Shop::$id_shop_owner>0? ' AND a_s.id_seller='  . Shop::$id_shop_owner : '' );            
						if(intval(Configuration::get('AGILE_MS_PRODUCT_APPROVAL')))
			{
				$wheres = $wheres . ' AND (IFNULL(a_po.approved,0) = 1 OR IFNULL(a_po.id_owner,0)=0) ';
			}
						$specialcategoryids = AgileMultipleSeller::getSpecialCatrgoryIds();
			if(!empty($specialcategoryids) && $no_special_products)
			{
				$wheres = $wheres . ' AND ' .  $prefix . '.id_category_default NOT IN (' . $specialcategoryids . ')';
			}            
		}

		if(Module::isInstalled('agilesellerlistoptions'))
		{                
			require_once(_PS_ROOT_DIR_ .  "/modules/agilesellerlistoptions/agilesellerlistoptions.php");

			$joins = $joins . '
                LEFT JOIN `'._DB_PREFIX_.'seller_listoption` a_slh ON (' . $prefix  . '.id_product = a_slh.id_product AND a_slh.id_option = ' . AgileSellerListOptions::ASLO_OPTION_HOT . ')
                LEFT JOIN `'._DB_PREFIX_.'seller_listoption` a_slt ON (' . $prefix  . '.id_product = a_slt.id_product AND a_slt.id_option = ' . AgileSellerListOptions::ASLO_OPTION_LISTTOP . ')
                LEFT JOIN `'._DB_PREFIX_.'seller_listoption` a_slb ON (' . $prefix  . '.id_product = a_slb.id_product AND a_slb.id_option = ' . AgileSellerListOptions::ASLO_OPTION_LIST . ')
                ';

			$selects = $selects . '
            			,CASE WHEN a_slh.status = ' . AgileSellerListOptions::ASLO_STATUS_IN_EFFECT . ' THEN 1 ELSE 0 END AS ishot
            			,CASE WHEN a_slt.status = ' . AgileSellerListOptions::ASLO_STATUS_IN_EFFECT . ' THEN 0-RAND() ELSE 0 END AS position2
                    ';
			
			$aslo_list_prod_id = intval(Configuration::get('ASLO_PROD_FOR_OPTION' . AgileSellerListOptions::ASLO_OPTION_LIST));
			$wheres = $wheres . ' 
    		    AND (a_slb.status = ' . AgileSellerListOptions::ASLO_STATUS_IN_EFFECT . ' OR IFNULL(a_po.id_owner,0) = 0 OR ' . $aslo_list_prod_id  . '=' . AgileSellerListOptions::ASLO_ALWAYS_FREE . ')
				
                ';
		}
		if(Module::isInstalled('agilezipcodefilter'))
		{
			require_once(_PS_ROOT_DIR_ .  "/modules/agilezipcodefilter/agilezipcodefilter.php");

			$wheres = $wheres . AgileZipcodeFilter::sql_filter("a_s");
		}
			
		return array('selects' => $selects, 'joins' => $joins, 'wheres' => $wheres);		
	}
	
	public static function prepareSellerRattingInfo($data)
	{
		if(Module::isInstalled('agilesellerratings'))
		{
			require_once(_PS_ROOT_DIR_ . "/modules/agilesellerratings/agilesellerratings.php");
			$module = new AgileSellerRatings();
			if(method_exists($module, "assignSellerRatingForList"))
				$data = $module->assignSellerRatingForList($data, true);
		}
		return $data;
	}

	public static function ensure_configuration_record_for_all_shops()
	{
						$id_main_store = intval(Configuration::get('PS_SHOP_DEFAULT'));
		$rewritting_settings = intval(Configuration::get('PS_REWRITING_SETTINGS',null, null,$id_main_store));
				$sql = "INSERT INTO " . _DB_PREFIX_ . "configuration (id_shop_group,id_shop,name,value, date_add, date_upd)
				SELECT NULL,id_shop,'PS_REWRITING_SETTINGS', $rewritting_settings, CURDATE( ), CURDATE( )
				FROM
				(
					SELECT s.id_shop as id_shop, MAX(IFNULL(c.id_configuration,0)) AS id_configuration
					FROM `" . _DB_PREFIX_ . "shop` s
					LEFT JOIN " . _DB_PREFIX_ . "configuration c ON s.id_shop=c.id_shop AND c.name='PS_REWRITING_SETTINGS'
					GROUp BY s.id_shop
				)AS T
				WHERE id_configuration =0
		";
				Db::getInstance()->Execute($sql);
				$sql = "UPDATE " . _DB_PREFIX_ . "configuration SET value=$rewritting_settings WHERE name='PS_REWRITING_SETTINGS' AND id_shop>0 AND value!=$rewritting_settings";
				Db::getInstance()->Execute($sql);

		$default = Db::getInstance()->getRow('SELECT * FROM ' . _DB_PREFIX_ . 'shop_url WHERE id_shop=' . (int)Configuration::get('PS_SHOP_DEFAULT'));
		Db::getInstance()->Execute('UPDATE ' . _DB_PREFIX_ . 'shop_url SET domain=\'' . $default ['domain'] . '\',domain_ssl=\'' . $default ['domain_ssl'] . '\',physical_uri=\'' . $default ['physical_uri'] . '\' WHERE id_shop!=' . (int)Configuration::get('PS_SHOP_DEFAULT'));
	}
	
	public static function syncSellerCredentials($dir, $id_seller)
	{
		if(!$id_seller)return;
		$id_customer = (int)Db::getInstance()->getValue('SELECT id_customer FROM ' . _DB_PREFIX_ . 'sellerinfo WHERE id_seller=' . (int)$id_seller);
		if(!$id_customer)return;
		
		if($dir =='f2b')
		{
			$encrypted = Db::getInstance()->getValue('SELECT passwd FROM ' . _DB_PREFIX_ . 'customer WHERE id_customer=' . (int)$id_customer);
			$sql = 'UPDATE ' . _DB_PREFIX_ . 'employee SET passwd=\'' . $encrypted . '\' WHERE id_employee=' . (int)$id_seller;
			Db::getInstance()->Execute($sql);
		}
		if($dir =='b2f')
		{
			$encrypted = Db::getInstance()->getValue('SELECT passwd FROM ' . _DB_PREFIX_ . 'employee WHERE id_employee=' . (int)$id_seller);
			$sql = 'UPDATE ' . _DB_PREFIX_ . 'customer SET passwd=\'' . $encrypted . '\' WHERE id_customer=' . (int)$id_customer;
			Db::getInstance()->Execute($sql);
		}
	}
	
	public static function get_id_seller_column($table, $objid)
	{
		$id_seller = -1;
		$sql = "SELECT id_seller FROM " . _DB_PREFIX_ . $table ." WHERE id_" . $table . "=" . $objid;
		try
		{
			$id_seller = Db::getInstance()->getValue($sql);	
		}
		catch(Exception $e)
		{
			$id_seller = -1;
		}
		return $id_seller;
	}

}
