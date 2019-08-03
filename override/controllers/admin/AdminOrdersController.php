<?php
class AdminOrdersController extends AdminOrdersControllerCore
{
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:36
    * version: 3.7.3.2
    */
    public function __construct()
	{
		parent::__construct();
		
		if(Module::isInstalled('agilemultipleseller'))
		{
			$this->agilemultipleseller_list_override();
		}
	}
	
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:36
    * version: 3.7.3.2
    */
    public function initToolbar()
	{
		parent::initToolbar();
		if(!Module::isInstalled('agilemultipleseller'))return;
				if($this->is_seller)
		{
			unset($this->toolbar_btn['new']);
		}
	}
	
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:36
    * version: 3.7.3.2
    */
    public function renderView()
	{
		if(Module::isInstalled('agilemultipleseller') && !intval(Tools::getValue('id_product')) AND $this->is_seller AND AgileSellerManager::limited_by_membership($this->context->cookie->id_employee))
		{
			$this->errors[] = Tools::displayError('You have not purchased membership yet or you have registered products more than limit allowed by your membership.');
			return;
		}
		
		return parent::renderView();
	}	
}
