<?php
class AdminFeaturesController extends AdminFeaturesControllerCore
{
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:35
    * version: 3.7.3.2
    */
    public function __construct()
	{
		parent::__construct();
		
		if(!$this->is_seller)
		{
			$this->fields_list['seller'] = array('title' => $this->l('Seller'), 'width' => 20, 'filter_key' => 'amsl!company');
		}
	}
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:35
    * version: 3.7.3.2
    */
    public function init()
	{
		if(Module::isInstalled('agilemultipleseller'))
			$this->agilemultipleseller_list_override();	
		
				if(Tools::isSubmit('deletefeature_value'))
		{
			$_POST["id_feature"] = Db::getInstance()->getValue('SELECT id_feature FROM ' . _DB_PREFIX_ . 'feature_value WHERE id_feature_value=' . intval(Tools::getValue("id_feature_value"))); 
		}
		parent::init();
	}	
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:35
    * version: 3.7.3.2
    */
    public function getList($id_lang, $order_by = null, $order_way = null, $start = 0, $limit = null, $id_lang_shop = false)
	{
				if ($this->table == 'feature_value')
			$this->_where = sprintf('AND a.`id_feature` = %d', (int)Tools::getValue('id_feature'));
		
		parent::getList($id_lang, $order_by, $order_way, $start, $limit, $id_lang_shop);
	}
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:35
    * version: 3.7.3.2
    */
    protected function agilemultipleseller_list_override()
	{
		if(!Module::isInstalled('agilemultipleseller'))return;
		parent::agilemultipleseller_list_override();
		if($this->is_seller)
		{
			$this->_where = $this->_where . ' AND IFNULL(ao.`id_owner`,0) > 0';
		}
		else
		{
			if(empty($this->_select) OR substr(trim($this->_select),-1,1) == ",")
			{
				$this->_select = $this->_select . 'amsl.company AS seller';
			}
			else
			{
				$this->_select = $this->_select . ',amsl.company AS seller';
			}
		}
	}
		/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:35
    * version: 3.7.3.2
    */
    public function processSave()
	{
		$obj = parent::processSave();
		if(!Module::isInstalled('agilemultipleseller'))return $obj;
		if($this->is_seller)
		{
			AgileSellerManager::assignObjectOwner('feature',$obj->id, $this->context->cookie->id_employee);
		}
		else
		{
			AgileSellerManager::assignObjectOwner('feature',$obj->id, Tools::getValue('id_seller'));
		}
		
		return $obj;
	}
}
