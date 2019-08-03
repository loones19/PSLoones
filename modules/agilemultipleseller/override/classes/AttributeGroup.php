<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
class AttributeGroup extends AttributeGroupCore
{
	public static function getAttributes($id_lang, $id_attribute_group)
	{
		if (!Combination::isFeatureActive()) return array();
		if(!Module::isInstalled('agilemultipleseller'))return parent::getAttributes($id_lang, $id_attribute_group);
		
		$id_seller = AgileSellerManager::get_id_seller_for_filter4att();
		$additional_join = 'LEFT JOIN `'._DB_PREFIX_.'object_owner` ao on (ao.entity=\'attribute\' AND ago.id_object=ag.id_attribute)';
		$additional_where = ' AND IFNULL(ao.id_owner,0) in (0,' . $id_seller . ')';
		
				$sql = '
			SELECT *
			FROM `'._DB_PREFIX_.'attribute` a
			'.Shop::addSqlAssociation('attribute', 'a').'
			LEFT JOIN `'._DB_PREFIX_.'attribute_lang` al
				ON (a.`id_attribute` = al.`id_attribute` AND al.`id_lang` = '.(int)$id_lang.')
			' . $additional_join. '
			WHERE a.`id_attribute_group` = '.(int)$id_attribute_group.'
			'. $additional_where.'
			ORDER BY `position` ASC
		';
		
		return Db::getInstance()->executeS($sql);

	}

	public static function getAttributesGroups($id_lang)
	{
		if (!Combination::isFeatureActive()) return array();
		if(!Module::isInstalled('agilemultipleseller'))return parent::getAttributesGroups($id_lang);
		
		$id_seller = AgileSellerManager::get_id_seller_for_filter4att();
		$additional_join = 'LEFT JOIN `'._DB_PREFIX_.'object_owner` ago on (ago.entity=\'attribute_group\' AND ago.id_object=ag.id_attribute_group)';
		$additional_where = 'WHERE IFNULL(ago.id_owner,0) in (0,' . $id_seller . ')';

				$sql = '
			SELECT DISTINCT agl.`name`, ag.*, agl.*
			FROM `'._DB_PREFIX_.'attribute_group` ag
			'.Shop::addSqlAssociation('attribute_group', 'ag').'
			LEFT JOIN `'._DB_PREFIX_.'attribute_group_lang` agl
				ON (ag.`id_attribute_group` = agl.`id_attribute_group` AND `id_lang` = '.(int)$id_lang.')
			' .  $additional_join . '
			' . $additional_where . '
			ORDER BY `name` ASC
		';
	
		return Db::getInstance()->executeS($sql);
	}
}
