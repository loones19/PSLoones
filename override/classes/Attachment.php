<?php
class Attachment extends AttachmentCore
{
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:18
    * version: 3.7.3.2
    */
    public static function getAttachments($id_lang, $id_product, $include = true)
	{
		if(Module::isInstalled('agilemultipleseller'))
		{
			$id_seller_for_filter = AgileSellerManager::get_id_seller_for_filter4att();
			if((int)$id_seller_for_filter <=0) return parent::getAttachments($id_lang, $id_product, $include);
			
			return Db::getInstance()->executeS('
			SELECT *
			FROM '._DB_PREFIX_.'attachment a
			LEFT JOIN '._DB_PREFIX_.'object_owner oo 
			ON oo.entity = \'attachment\' AND a.id_attachment = oo.id_object
			LEFT JOIN '._DB_PREFIX_.'attachment_lang al
				ON (a.id_attachment = al.id_attachment AND al.id_lang = '.(int)$id_lang.')
			WHERE (oo.id_owner = '.$id_seller_for_filter.' OR oo.id_owner = 0) AND a.id_attachment '.($include ? 'IN' : 'NOT IN').' (
				SELECT pa.id_attachment
				FROM '._DB_PREFIX_.'product_attachment pa
				WHERE id_product = '.(int)$id_product.'
			)'
			);
		}else
		{
			return parent::getAttachments($id_lang, $id_product, $include);
		}
	}
}
