<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.

class IdentityController extends IdentityControllerCore
{	
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

