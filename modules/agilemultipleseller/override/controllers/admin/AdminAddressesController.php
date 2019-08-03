<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.

class AdminAddressesController extends AdminAddressesControllerCore
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
				if($this->is_seller)
		{
			unset($this->toolbar_btn['new']);
			unset($this->toolbar_btn['save']);
		}
	}
	
	protected function agilemultipleseller_list_override()
	{
		if(!Module::isInstalled('agilemultipleseller'))return;
				if(!$this->is_seller)return;
		parent::agilemultipleseller_list_override();
				$this->fields_list['address1']['filter_key'] = 'a!address1';
		$this->fields_list['postcode']['filter_key'] = 'a!postcode';
		$this->fields_list['city']['filter_key'] = 'a!city';		
	}

}

