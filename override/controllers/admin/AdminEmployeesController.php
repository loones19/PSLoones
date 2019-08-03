<?php
class AdminEmployeesController extends AdminEmployeesControllerCore
{
	
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:34
    * version: 3.7.3.2
    */
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
	
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:34
    * version: 3.7.3.2
    */
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
