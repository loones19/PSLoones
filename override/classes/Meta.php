<?php
class Meta extends MetaCore
{
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:25
    * version: 3.7.3.2
    */
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
