<?php
class IdentityController extends IdentityControllerCore
{	
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:42
    * version: 3.7.3.2
    */
    public function postProcess()
	{
		parent::postProcess();
		if (Module::isInstalled('agilemultipleseller') && Tools::getValue('passwd'))
		{
			include_once(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/SellerInfo.php");
			$id_seller = SellerInfo::getSellerIdByCustomerId(Context::getContext()->customer->id);
			AgileSellerManager::syncSellerCredentials('f2b', $id_seller);				
		}		
	}
}
