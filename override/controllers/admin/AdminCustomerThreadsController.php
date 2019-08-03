<?php
class AdminCustomerThreadsController extends AdminCustomerThreadsControllerCore
{
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:34
    * version: 3.7.3.2
    */
    public function renderOptions()
	{
		if($this->is_seller)return;
		
		return parent::renderOptions();
	}
	
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:34
    * version: 3.7.3.2
    */
    public function getList($id_lang, $order_by = null, $order_way = null, $start = 0, $limit = null, $id_lang_shop = false)
	{
		if(Module::isInstalled('agilemultipleseller'))
		{
			$this->agilemultipleseller_list_override();
		}
		parent::getList($id_lang, $order_by, $order_way, $start, $limit, $id_lang_shop);
	}	
	
}
