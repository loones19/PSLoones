<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.

class SellerType extends ObjectModel
{
	public 		$id;

	public 		$name;
	
	public 		$date_add;

	public static $definition = array(
		'table' => 'sellertype',
		'primary' => 'id_sellertype',
		'multilang' => true,
		'multilang_shop' => false,
		'fields' => array(
			'name' => 			array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isString', 'required' => true, 'size' => 256),
			),
		);
	
	
	public function getFields()
	{
		parent::validateFields();
		if (isset($this->id))
			$fields['id_sellertype'] = (int)($this->id);
		$fields['date_add'] = pSQL($this->date_add);

		return $fields;
	}
	
	public function getTranslationsFieldsChild()
	{
		if (!parent::validateFieldsLang())
			return false;
		return parent::getTranslationsFields(array('name'));
	}
	
	public static function getSellerTypes($id_lang, $label4zero=null)
	{
		$rets = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS('
		SELECT s.`id_sellertype`,sl.`name`
		FROM `'._DB_PREFIX_.'sellertype` s
		LEFT JOIN `'._DB_PREFIX_.'sellertype_lang` AS sl ON (s.`id_sellertype` = sl.`id_sellertype` AND sl.`id_lang` = '.(int)($id_lang).')
		ORDER BY sl.name
		');
	
		if(empty($label4zero))return $rets;
		
		return array_merge(array(array('id_sellertype' => 0, 'name' => $label4zero)), $rets);
	}
}


