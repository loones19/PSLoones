<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.

abstract class HTMLTemplate extends HTMLTemplateCore
{
	public function getFooter()
	{
		$parent_footer = parent::getFooter();
		if(!isset($this->order) OR !Validate::isLoadedObject($this->order))return $parent_footer;
		if(!Module::isInstalled('agilemultipleseller'))return $parent_footer;
		
        require_once(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/SellerInfo.php");
		$id_seller = AgileSellerManager::getObjectOwnerID('order', $this->order->id);
		$seller = new Employee($id_seller);
		$sellerinfo = new SellerInfo(SellerInfo::getIdBSellerId($id_seller), $this->order->id_lang);

		$id_lang = intval(Configuration::get('PS_COUNTRY_DEFAULT'));
	
		$this->smarty->assign(array(
			'seller_name' => $sellerinfo->company,
			'seller_address' => $sellerinfo->fulladdress($id_lang),
			'seller_fax' => $sellerinfo->fax,
			'seller_phone' => $sellerinfo->phone,
			'seller_email' => $seller->email,
			'sellerinfo' => $sellerinfo
			));

		return $this->smarty->fetch($this->getTemplate('footer'));
	}	
}

