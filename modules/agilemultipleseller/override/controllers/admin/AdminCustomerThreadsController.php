<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.

class AdminCustomerThreadsController extends AdminCustomerThreadsControllerCore
{
	public function renderOptions()
	{
		if($this->is_seller)return;
		
		return parent::renderOptions();
	}
	
	public function getList($id_lang, $order_by = null, $order_way = null, $start = 0, $limit = null, $id_lang_shop = false)
	{
		if(Module::isInstalled('agilemultipleseller'))
		{
			$this->agilemultipleseller_list_override();
		}

		parent::getList($id_lang, $order_by, $order_way, $start, $limit, $id_lang_shop);
	}	
	
}

