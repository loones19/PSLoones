<?php
class OrderDetailController extends OrderDetailControllerCore
{
    /*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:45
    * version: 3.7.3.2
    */
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
