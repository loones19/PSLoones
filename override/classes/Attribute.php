<?php
class Attribute extends AttributeCore
{
    /*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:19
    * version: 3.7.3.2
    */
    public static function getAttributes($id_lang, $not_null = false)
	{
		if (!Combination::isFeatureActive()) return array();
		if(!Module::isInstalled('agilemultipleseller'))return parent::getAttributes($id_lang, $not_null);
		$id_seller = AgileSellerManager::get_id_seller_for_filter4att();
		$additional_join = 'LEFT JOIN `'._DB_PREFIX_.'object_owner` ago on (ago.entity=\'attribute_group\' AND ago.id_object=ag.id_attribute_group)
							LEFT JOIN `'._DB_PREFIX_.'object_owner` ao on (ago.entity=\'attribute\' AND ago.id_object=a.id_attribute)
							';
		$additional_where = ($not_null ?' AND ': ' WHERE ')  . ' IFNULL(ago.id_owner,0) in (0,' . $id_seller . ')
							AND IFNULL(ao.id_owner,0) in (0,' . $id_seller . ')
							';
		
				$sql = 'SELECT DISTINCT ag.*, agl.*, a.`id_attribute`, al.`name`, agl.`name` AS `attribute_group`
			FROM `'._DB_PREFIX_.'attribute_group` ag
			LEFT JOIN `'._DB_PREFIX_.'attribute_group_lang` agl
				ON (ag.`id_attribute_group` = agl.`id_attribute_group` AND agl.`id_lang` = '.(int)$id_lang.')
			LEFT JOIN `'._DB_PREFIX_.'attribute` a
				ON a.`id_attribute_group` = ag.`id_attribute_group`
			LEFT JOIN `'._DB_PREFIX_.'attribute_lang` al
				ON (a.`id_attribute` = al.`id_attribute` AND al.`id_lang` = '.(int)$id_lang.')
			'.Shop::addSqlAssociation('attribute_group', 'ag').'
			'.Shop::addSqlAssociation('attribute', 'a').'
			' . $additional_join . '
			'.($not_null ? 'WHERE a.`id_attribute` IS NOT NULL AND al.`name` IS NOT NULL AND agl.`id_attribute_group` IS NOT NULL' : '').'
			' . $additional_where. '
			ORDER BY agl.`name` ASC, a.`position` ASC
		';
		
		return Db::getInstance()->executeS($sql);
	}
}
