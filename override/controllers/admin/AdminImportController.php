<?php
class AdminImportController extends AdminImportControllerCore
{
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:35
    * version: 3.7.3.2
    */
    public static function getPath($file = '')
	{
		$controller = Context::getContext()->controller;
		if(!Module::isInstalled('agilemultipleseller'))return parent::getPath($file);
		if(!$controller->is_seller)return parent::getPath($file);
		
		$dir = (defined('_PS_HOST_MODE_') ? _PS_ROOT_DIR_ : _PS_ADMIN_DIR_).DIRECTORY_SEPARATOR.'import' .DIRECTORY_SEPARATOR. $controller-> context->cookie->id_employee;
		if(!file_exists($dir))mkdir($dir);
		return $dir	.DIRECTORY_SEPARATOR.$file;
	}
	
	
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:35
    * version: 3.7.3.2
    */
    protected function truncateTables($case)
	{
		if(!Module::isInstalled('agilemultipleseller'))return parent::truncateTables($case);
		$controller = Context::getContext()->controller;
		if(!$controller->is_seller)return parent::truncateTables($case);
				switch ((int)$case)
		{
			case $this->entities[$this->l('Products')]:
				$subquery = 'SELECT id_product FROM '._DB_PREFIX_. 'product_owner WHERE id_owner=' . (int)$controller-> context->cookie->id_employee;
				Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'product` WHERE id_product IN (' . $subquery . ')');
				Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'product_shop` WHERE id_product IN (' . $subquery . ')');
				Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'feature_product` WHERE id_product IN (' . $subquery . ')');
				Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'product_lang` WHERE id_product IN (' . $subquery . ')');
				Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'category_product` WHERE id_product IN (' . $subquery . ')');
				Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'product_tag` WHERE id_product IN (' . $subquery . ')');
				Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'specific_price` WHERE id_product IN (' . $subquery . ')');
				Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'specific_price_priority` WHERE id_product IN (' . $subquery . ')');
				Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'product_carrier` WHERE id_product IN (' . $subquery . ')');
				Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'cart_product` WHERE id_product IN (' . $subquery . ')');
				if (count(Db::getInstance()->executeS('SHOW TABLES LIKE \''._DB_PREFIX_.'favorite_product\' '))) 					Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'favorite_product` WHERE id_product IN (' . $subquery . ')');
				Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'product_attachment` WHERE id_product IN (' . $subquery . ')');
				Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'product_country_tax` WHERE id_product IN (' . $subquery . ')');
				Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'product_download` WHERE id_product IN (' . $subquery . ')');
				Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'product_group_reduction_cache` WHERE id_product IN (' . $subquery . ')');
				Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'product_sale` WHERE id_product IN (' . $subquery . ')');
				Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'product_supplier` WHERE id_product IN (' . $subquery . ')');
				Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'warehouse_product_location` WHERE id_product IN (' . $subquery . ')');
				Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'customization` WHERE id_product IN (' . $subquery . ')');
				Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'customization_field` WHERE id_product IN (' . $subquery . ')');
				Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'supply_order_detail` WHERE id_product IN (' . $subquery . ')');
				Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'attribute_impact` WHERE id_product IN (' . $subquery . ')');
				Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'product_attribute` WHERE id_product IN (' . $subquery . ')');
				Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'product_attribute_shop` WHERE id_product IN (' . $subquery . ')');
				Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'product_attribute_combination` WHERE id_product_attribute IN (Select id_product_attribute from ' . _DB_PREFIX_ . 'product_attribute where id_product in (' . $subquery . '))');
				Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'product_attribute_image` WHERE id_product_attribute IN (Select id_product_attribute from ' . _DB_PREFIX_ . 'product_attribute where id_product in (' . $subquery . '))');
				Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'pack` WHERE id_product_pack IN (' . $subquery . ') OR  id_product_item IN (' . $subquery . ')');
				$stockquery = 'SELECT id_stock FROM `'._DB_PREFIX_.'stock` WHERE id_product IN (' . $subquery . ')';
				Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'stock` WHERE id_product IN (' . $subquery . ')');
				Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'stock_available` WHERE id_product IN (' . $subquery . ')');
				Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'stock_mvt` WHERE id_stock IN (' . $stockquery . ')');
				$imagequery = 'SELECT id_image FROM `'._DB_PREFIX_.'image` WHERE id_product IN (' . $subquery . ')';
				Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'image_lang` WHERE id_image IN (' . $imagequery . ')');
				Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'image_shop` WHERE id_image IN (' . $imagequery . ')');
				Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'image` WHERE id_product IN (' . $subquery . ')');
				Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_. 'product_owner WHERE id_owner=' . (int)$this->sellerinfo->id_seller);
												
				if (!file_exists(_PS_PROD_IMG_DIR_))
					mkdir(_PS_PROD_IMG_DIR_);
				break;
		}
		Image::clearTmpDir();
		return true;
	}
}
