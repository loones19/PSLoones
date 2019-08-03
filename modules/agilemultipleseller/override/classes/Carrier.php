<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.

class Carrier extends CarrierCore
{

    public $is_default = false;
    
	public function __construct($id = NULL, $id_lang = NULL)
	{
		parent::__construct($id, $id_lang);
		if(Module::isInstalled('agilemultipleseller') AND intval($id)>0)
		{
		    $sql = 'SELECT IFNULL(is_default,0) AS is_default FROM ' . _DB_PREFIX_ . 'carrier_owner WHERE id_carrier=' . intval($id);
		    $this->is_default = intval(Db::getInstance()->getValue($sql));
		}

	}
	
						public static function getAvailableCarrierList(Product $product, $id_warehouse, $id_address_delivery = null, $id_shop = null, $cart = null, &$error = array())
	{
		$carriers = parent::getAvailableCarrierList($product, $id_warehouse, $id_address_delivery, $id_shop, $cart);
		if(!Module::isInstalled('agilesellershipping'))
			return $carriers;
		
		if(empty($carriers))
		{
			$carriers = array();
			$carriers[]  = (int)Configuration::get('AGILE_SS_CARRIER_ID');
		}
		
		return $carriers;
	}
	
						public static function getCarriers($id_lang, $active = false, $delete = false, $id_zone = false, $ids_group = NULL, $modules_filters = 1)
	{
		$carriers = parent::getCarriers($id_lang, $active, $delete, $id_zone, $ids_group, $modules_filters);
		if(!Module::isInstalled('agilemultipleseller'))return $carriers;
		
		$id_seller_for_filter = AgileSellerManager::get_id_seller_for_filter();
		
		if((int)$id_seller_for_filter <=0)return $carriers;
						$retCarriers = array();
		foreach($carriers AS $carrier)
		{
			$id_owner = AgileSellerManager::getObjectOwnerID('carrier', $carrier['id_carrier']);
			if($id_seller_for_filter == $id_owner || $id_owner == 0)
			{
				$retCarriers[] = $carrier;
			}
		}
		
		return $retCarriers;
	}
	

		public function addDeliveryPrice($price_list, $delete = false)
	{
		if (!$price_list)
			return false;

		$keys = array_keys($price_list[0]);
		if (!in_array('id_shop', $keys))
			$keys[] = 'id_shop';
		if (!in_array('id_shop_group', $keys))
			$keys[] = 'id_shop_group';

		$sql = 'INSERT INTO `'._DB_PREFIX_.'delivery` ('.implode(', ', $keys).') VALUES ';
		foreach ($price_list as $values)
		{
			if (!isset($values['id_shop']))
				$values['id_shop'] =  null;  			if (!isset($values['id_shop_group']))
				$values['id_shop_group'] = null;  
			if ($delete)
				Db::getInstance()->execute('
					DELETE FROM `'._DB_PREFIX_.'delivery` 
					WHERE '.(is_null($values['id_shop']) ? 'ISNULL(`id_shop`) ' : 'id_shop = '.(int)$values['id_shop']).' 
					AND '.(is_null($values['id_shop_group']) ? 'ISNULL(`id_shop`) ' : 'id_shop_group='.(int)$values['id_shop_group']).'
					AND id_carrier='.(int)$values['id_carrier'].
					($values['id_range_price'] !== null ? ' AND id_range_price='.(int)$values['id_range_price'] : ' AND (ISNULL(`id_range_price`) OR `id_range_price` = 0)').
					($values['id_range_weight'] !== null ? ' AND id_range_weight='.(int)$values['id_range_weight'] : ' AND (ISNULL(`id_range_weight`) OR `id_range_weight` = 0)').'
					AND id_zone='.(int)$values['id_zone']
					);

			$sql .= '(';
			foreach ($values as $v)
			{
				if (is_null($v))
					$sql .= 'NULL';
				else if (is_int($v) || is_float($v))
					$sql .= $v;
				else
					$sql .= '\''.$v.'\'';
				$sql .= ', ';
			}
			$sql = rtrim($sql, ', ').'), ';
		}
		$sql = rtrim($sql, ', ');
		return Db::getInstance()->execute($sql);
	}
	
        	public static function getCarriersForOrder($id_zone, $groups = null, $theCart = null, &$error = array())
	{
		$context = Context::getContext();
		
		if( is_null($theCart))$theCart = $context->cart;
		if(!Module::isInstalled('agilesellershipping'))return parent::getCarriersForOrder($id_zone, $groups,$theCart);
				if($context->cookie->id_employee >0)return  parent::getCarriersForOrder($id_zone, $groups, $theCart);

        include_once(_PS_ROOT_DIR_  . "/modules/agilesellershipping/agilesellershipping.php");
		include_once(_PS_ROOT_DIR_  . "/modules/agilesellershipping/SellerShipping.php");
		$id_carrier = (int)Configuration::get('AGILE_SS_CARRIER_ID');
        $id_zone = SellerShipping::getZoneID($theCart->id_address_delivery, $theCart->id_customer);

				$use_default_carrier = intval(Configuration::get('AGILE_SS_AS_DEFAULT_CARRIER'));
		$products_without_carrier = SellerShipping::products_without_carrier($id_zone, $theCart->id, $context->language->id);
		if(!empty($products_without_carrier))			
		{
			return array();
		} 
		
		return SellerShipping::getLinkedCarriersForOrder($id_carrier, $id_zone);
    }
    

		public function add($autoDate = true, $nullValues = false)
	{
		if(parent::add($autoDate, $nullValues))
		{
			AgileInstaller::enableAllPaymentModulesForCarrier($this->id, Context::getContext()->shop->id);
			return true;
		}
		return false;		
	}
	
	public static function getActiveIDByID($id_carrier)
	{
		$sql = 'SELECT id_carrier  
				FROM `' . _DB_PREFIX_ . 'carrier` 
				WHERE deleted = 0 and id_reference in (select id_reference FROM ' . _DB_PREFIX_ . 'carrier where id_carrier = ' . (int)$id_carrier. ')	
				';
		return (int)Db::getInstance()->getValue($sql);
	}	
}
