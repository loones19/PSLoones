<?php
abstract class ObjectModel extends ObjectModelCore
{
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:25
    * version: 3.7.3.2
    */
    public function __construct($id = null, $id_lang = null, $id_shop = null, $translator = null)
	{
		parent::__construct($id, $id_lang, $id_shop);
    }
		
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:25
    * version: 3.7.3.2
    */
    public function add($autodate = true, $nullValues = false)
	{
		if(!Module::isInstalled('agilemultipleseller'))return parent::add($autodate, $nullValues);
						
		if (!parent::add($autodate, $nullValues))return false;
						if(Module::isInstalled('agilemultipleseller') AND $this->table != 'orders')$this->assign_entity_owner();
						ObjectModel::cleear_unnecessary_lang_data();
        return true;   
    }
	
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:25
    * version: 3.7.3.2
    */
    public static function cleear_unnecessary_lang_data()
	{
		Db::getInstance()->Execute('DELETE FROM ' . _DB_PREFIX_ . 'product_lang WHERE id_shop!=' . Shop::getContextShopID());
		Db::getInstance()->Execute('DELETE FROM ' . _DB_PREFIX_ . 'category_lang WHERE id_shop!=' . Shop::getContextShopID());
		Db::getInstance()->Execute('DELETE FROM ' . _DB_PREFIX_ . 'category_shop WHERE id_shop!=' . Shop::getContextShopID());		
		Db::getInstance()->Execute('DELETE FROM ' . _DB_PREFIX_ . 'cms_shop WHERE id_shop!=' . Shop::getContextShopID());
		Db::getInstance()->Execute('DELETE FROM ' . _DB_PREFIX_ . 'cms_lang WHERE id_shop!=' . Shop::getContextShopID());
		Db::getInstance()->Execute('DELETE FROM ' . _DB_PREFIX_ . 'cms_category_shop WHERE id_shop!=' . Shop::getContextShopID());
		Db::getInstance()->Execute('DELETE FROM ' . _DB_PREFIX_ . 'cms_category_lang WHERE id_shop!=' . Shop::getContextShopID());
	}
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:25
    * version: 3.7.3.2
    */
    public function update($null_values = false)
	{
		$context = Context::getContext();
		if(!Module::isInstalled('agilemultipleseller'))return parent::update($null_values);
				if(!$this->can_edit())return false;
		if (!parent::update($null_values))return false;
				if(Module::isInstalled('agilemultipleseller'))
		{
			$is_seller = ($context->cookie->profile == (int)Configuration::get('AGILE_MS_PROFILE_ID'));
			if(!$is_seller AND $this->table!='orders')$this->assign_entity_owner();
		}
		ObjectModel::cleear_unnecessary_lang_data();
		return true;
	}
	
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:25
    * version: 3.7.3.2
    */
    public function delete()
	{
		if(!Module::isInstalled('agilemultipleseller'))return parent::delete();
				if(!$this->can_edit())return false;
		if (!parent::delete())return false;
		AgileSellerManager::deleteObjectOwner($this->table, $this->id);
		
		return true;
	}
			/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:25
    * version: 3.7.3.2
    */
    private function can_edit()
	{
		$context = Context::getContext();
		if(!Module::isInstalled('agilemultipleseller'))return true;
				if($this->table =='image' OR $this->table=='product_attribute' OR $this->table=='order_detail')return true;
						if(intval($context->cookie->profile) == 0) 
			return true;
				
				if($context->cookie->profile > 0 AND $context->cookie->profile != (int)Configuration::get('AGILE_MS_PROFILE_ID'))
			return true;
		
		$eaccess = AgileSellerManager::get_entity_access($this->table);
		$xr_table = $eaccess['owner_xr_table'];
		if(empty($xr_table))
		{
			if(intval($this->id)<=0)return true; 
						if($this->id == $context->cookie->id_employee AND $this->table == 'employee')return true;
			
						if($context->cookie->profile == (int)Configuration::get('AGILE_MS_PROFILE_ID'))
			{
				if($context->cookie->id_employee == AgileSellerManager::get_id_seller_column($this->table, $this->id))return true;
			}
			
			if(!AgileSellerManager::hasOwnership($this->table,$this->id))return false;
		}
		else
		{
			$xr_objid = intval($this->{'id_' . $xr_table});
			if(intval($xr_objid)<=0)return true; 			if(!AgileSellerManager::hasOwnership($xr_table,$xr_objid))return false;
			
		}
		return true;
	}
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:25
    * version: 3.7.3.2
    */
    private function assign_entity_owner()
	{
		$context = Context::getContext();
		if(!Module::isInstalled('agilemultipleseller'))return true;
		if(isset($context->controller) && ($context->controller->php_self == "password" || $context->controller->php_self == "identity"))return true;
				if(isset($_GET['isFreecarrier']) || isset($_GET['status' . $this->table]) || isset($_GET['submitBulkdisableSelection' . $this->table]) || isset($_GET['submitBulkenableSelection' . $this->table]))return true;
				$eaccess = AgileSellerManager::get_entity_access($this->table);
				if($eaccess['owner_table_type'] == AgileSellerManager::OWNER_TABLE_UNKNOWN)return true;
        include_once(_PS_ROOT_DIR_  . "/modules/agilemultipleseller/agilemultipleseller.php");
								if(empty($eaccess['owner_xr_table']))
		{
			$is_seller = ($context->cookie->profile == (int)Configuration::get('AGILE_MS_PROFILE_ID'));
			if($is_seller && get_class($context->controller) == "AdminControllerCore")
			{				
				$id_seller = $context->cookie->id_employee;
			}
						elseif ($is_seller AND (Tools::getValue('controller') == 'AdminCarrierWizard' OR  isset($_GET['submitAdd' . $this->table]) OR isset($_POST['submitAdd' . $this->table]) OR isset($_POST['submitAdd' . $this->table . 'AndStay']) OR (isset($_POST['import']) AND $_POST['import']=1 AND  isset($_POST['csv']) ) ))
			{	
				$id_seller = $context->cookie->id_employee;
			}
						elseif(isset($_POST['id_seller']))
			{
				$id_seller = intval($_POST['id_seller']);
			}
						else if((isset($_GET['duplicate' . $this->table]) || (isset($_GET['process']) && $_GET['process'] =='duplicate')) && isset($_GET['id_product']) && $_GET['id_product']>0)
			{
				$id_seller = AgileSellerManager::getObjectOwnerID('product', $_GET['id_product']);
			}
						else
			{
				$id_seller = 0;
			}
			AgileSellerManager::assignObjectOwner($this->table, $this->id, $id_seller);
		}
		return true;
	}
}
