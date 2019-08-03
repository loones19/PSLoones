<?php
class Employee extends EmployeeCore
{
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:22
    * version: 3.7.3.2
    */
    public function update($null_values = false)
	{
				$empInDb = new Employee($this->id);
				$result = parent::update($null_values);
				if(!$result)return $result;
				if(!Module::isInstalled('agilemultipleseller'))return $result;
				if($empInDb->active == $this->active)return $result;
		
		if((int)Configuration::get('AGILE_MS_IS_MANUFACTURER') == 1)
		{			
			$sql = "UPDATE " . _DB_PREFIX_ . "manufacturer SET active = " . (int) $this->active . " where id_manufacturer IN (SELECT id_manufacturer FROM " . _DB_PREFIX_ . "sellerinfo WHERE id_seller =" .($this->id) . ")";
			Db::getInstance()->Execute($sql);
		}	
		if((int)Configuration::get('AGILE_MS_IS_SUPPLIER') == 1)
		{
			$sql = "UPDATE " . _DB_PREFIX_ . "supplier SET active = " . (int) $this->active . " where id_supplier IN (SELECT id_supplier FROM " . _DB_PREFIX_ . "sellerinfo WHERE id_seller =" .($this->id) . ")";
			Db::getInstance()->Execute($sql);
		}		
		
		if(!$this->active)
		{
	        AgileSellerManager::disableSellerProducts($this->id);
		}
		else 
		{
	        if (intval(Configuration::get('AGILE_MS_SELLER_APPROVAL')) == 1)
	        {
			}
		}
		return $result;
	}
    /*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:22
    * version: 3.7.3.2
    */
    public function delete()
	{
	    $ret = parent::delete();
	    if(Module::isInstalled('agilemultipleseller'))
		{
			AgileSellerManager::disableSellerProducts($this->id);
			AgileSellerManager::deleteSellerInfo($this->id);
		}
	    return $ret;
	}
}
