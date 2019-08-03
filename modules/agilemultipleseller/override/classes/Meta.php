<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
class Meta extends MetaCore
{
	public static function getHomeMetas($id_lang, $page_name)
	{
		if(!Module::isInstalled('agilemultipleshop') || !Module::isInstalled('agilemultipleseller')) return parent::getHomeMetas($id_lang, $page_name);
		if(Shop::$id_shop_owner<=1)return parent::getHomeMetas($id_lang, $page_name);
		include_once(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/SellerInfo.php");
		$id_sellerinfo = SellerInfo::getIdBSellerId(Shop::$id_shop_owner);
		$sellerinfo = new SellerInfo($id_sellerinfo, $id_lang);
		$metas = Meta::getMetaByPage($page_name, $id_lang);
		$ret['meta_title'] = (!empty($sellerinfo->meta_title) ? $sellerinfo->meta_title : $sellerinfo->company);
		$ret['meta_description'] = (!empty($sellerinfo->meta_description) ? $sellerinfo->meta_description : '');
		$ret['meta_keywords'] = (!empty($sellerinfo->meta_keywords) ?  $sellerinfo->meta_description :  '');
		return $ret;
	}
}
