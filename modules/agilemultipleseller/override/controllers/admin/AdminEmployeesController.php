<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.

class AdminEmployeesController extends AdminEmployeesControllerCore
{
	
	public function initContent()
	{
		if(Module::isInstalled('agilemultipleseller'))
		{
			include_once(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/agilemultipleseller.php");
			$module = new AgileMultipleSeller();
			if(!$this->is_seller)
			{
				$this->displayWarning($module->getL('How To Create Seller Hint'));	
			}
		}
		parent::initContent();
	}
	
	protected function afterUpdate($object)
	{
		$res = parent::afterUpdate($object);
		if ($res && Module::isInstalled('agilemultipleseller') && Tools::getValue('id_employee') && Tools::getValue('passwd'))
		{
			AgileSellerManager::syncSellerCredentials('b2f', Tools::getValue('id_employee'));
		}

		return $res;
	}
		
}

