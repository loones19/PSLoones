<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.

class AdminOrdersController extends AdminOrdersControllerCore
{
	public function __construct()
	{
		parent::__construct();
		
		if(Module::isInstalled('agilemultipleseller'))
		{
			$this->agilemultipleseller_list_override();
		}
	}
	
	public function initToolbar()
	{
		parent::initToolbar();
		if(!Module::isInstalled('agilemultipleseller'))return;
				if($this->is_seller)
		{
			unset($this->toolbar_btn['new']);
		}
	}
	
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

