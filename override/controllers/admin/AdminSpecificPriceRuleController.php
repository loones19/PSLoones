<?php
class AdminSpecificPriceRuleController extends AdminSpecificPriceRuleControllerCore
{
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:40
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
    * date: 2019-06-24 19:40:40
    * version: 3.7.3.2
    */
    public function getList($id_lang, $orderBy = NULL,  $orderWay = NULL,  $start = 0, $limit = NULL, $id_lang_shop = false)
	{
		if(Module::isInstalled('agilemultipleseller'))
			$this->agilemultipleseller_list_override();
		parent::getList($id_lang, $orderBy , $orderWay, $start, $limit);
	}
	
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:40
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
	
}
