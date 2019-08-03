<?php
class Customer extends CustomerCore
{
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:22
    * version: 3.7.3.2
    */
    public function getBoughtProducts()
	{
		if(!Module::isInstalled('agilemultipleseller'))return parent::getBoughtProducts();
		$context = Context::getContext();
		if($context->cookie->id_employee == 0 || ($context->cookie->profile != (int)Configuration::get('AGILE_MS_PROFILE_ID')))return parent::getBoughtProducts();  
		
		$sql = 'SELECT * 
				FROM `'._DB_PREFIX_.'orders` o
					LEFT JOIN `'._DB_PREFIX_.'order_detail` od ON o.id_order = od.id_order
					LEFT JOIN `'._DB_PREFIX_.'product_owner` po ON od.product_id = po.id_product
				WHERE o.valid = 1 
					AND o.`id_customer` = '.(int)$this->id . '
					AND po.`id_owner` = '.(int)$context->cookie->id_employee . '
				';
			return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
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
			include_once(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/SellerInfo.php");
			$id_seller = SellerInfo::getSellerIdByCustomerId($this->id);
			$emp = new Employee($id_seller);
			if(Validate::isLoadedObject($emp))
			{
				$emp->delete();
			}
		}
		
		return $ret;
	}
	
}
