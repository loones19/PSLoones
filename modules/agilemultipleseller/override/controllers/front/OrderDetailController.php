<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
class OrderDetailController extends OrderDetailControllerCore
{
    public function init()
    {
        parent::init();

		if(Module::isInstalled('agilemultipleseller'))
		{
			require_once(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/SellerInfo.php");
			$id_seller = AgileSellerManager::getObjectOwnerID('order', (int)Tools::getValue('id_order'));
			$sellerinfo = new SellerInfo(SellerInfo::getIdBSellerId($id_seller), $this->context->language->id);
			$this->context->smarty->assign(array(
				'id_seller' => $id_seller
				,'sellerinfo' => $sellerinfo
				,'selleraddress' =>  $sellerinfo->fulladdress($this->context->language->id)
				));
		}		
	}
}
