<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
include_once(dirname(__FILE__).'/../../config/config.inc.php');
include_once(dirname(__FILE__).'/../../init.php');
require_once(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/agilemultipleseller.php");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

$res = getCategoryList(intval(Tools::getValue('id_category')));
die($res);

function getCategoryList($id_category)
{
	$sql = 'SELECT id_parent FROM ' . _DB_PREFIX_. 'category WHERE id_category=' . $id_category;
	$id_parent = Db::getInstance()->getValue($sql);
	if($id_parent<=1)$id_parent = 1;

	$tds =  array();

	if(intval(Tools::getValue('id_category'))>0)
	{
		$tds[] = show_category_list(intval(Tools::getValue('id_category')), 0);
	}

		while($id_parent>0)
	{
				$tds[] = show_category_list($id_parent, $id_category);
		$sql = 'SELECT id_parent FROM ' . _DB_PREFIX_. 'category WHERE id_category=' . $id_parent;
		$id_category = $id_parent;
		$id_parent = Db::getInstance()->getValue($sql);

	}

	$table = "";
	$last = count($tds) - 1;
		for($idx=0;$idx < count($tds); $idx++)
	{
		$table = $table . $tds[$last - $idx];

	}
	return $table;

}


function show_category_list($parent, $current)
{
	$context = Context::getContext();	
		if($parent == 1)return "";
	
	$specialcategoryids = AgileMultipleSeller::getSpecialCatrgoryIds();
	$special_cond = '';
	if(!empty($specialcategoryids))
	{
		$special_cond = ' AND c.id_category NOT IN (' . $specialcategoryids . ')';
	}            

	$sql = 'SELECT c.id_category,c.id_parent, cl.name  
			FROM ' . _DB_PREFIX_. 'category c
				LEFT JOIN ' . _DB_PREFIX_. 'category_lang cl ON (c.id_category = cl.id_category AND cl.id_lang= '  .$context->language->id .' )
			WHERE id_parent=' . $parent . '
				AND c.active = 1
				' .  $special_cond . '
			ORDER BY cl.name ASC
			';
	$categories = Db::getInstance()->ExecuteS($sql);
	if(empty($categories))return '';
	$ret = '';
	
	$ret .= '<select size="5" style="width:175px;" name="categoryBox[]" id="lstCategories_' . $parent . '" onchange="oncategoryselected(\'lstCategories_' . $parent . '\');">';
	foreach($categories AS $category)
	{
		$selected = '';
		if($current == $category['id_category'])$selected = 'SELECTED';
		$ret .= '<option value="' . $category['id_category'] . '" '. $selected . '>' . $category['name']. '</option>';
	}

	$ret .= '</select>';	
	
	return $ret;
}

