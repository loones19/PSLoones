<?php
$id_seller = (int)$cookie->id_employee;
$employee = new Employee($id_seller);
if($id_seller > 0 && $employee->id_profile == (int)Configuration::get('AGILE_MS_PROFILE_ID'))
{
	$current_path =  _PS_ROOT_DIR_.'/img/cms/sellers';
	if(!file_exists($current_path))mkdir($current_path);
	$current_path = $current_path . '/'. $id_seller;
	if(!file_exists($current_path))mkdir($current_path);
	$current_path =  $current_path . '/';

	$upload_dir = Context::getContext()->shop->getBaseURI().'img/cms/sellers/' . $id_seller . '/';

	$thumbs_base_path =  _PS_ROOT_DIR_.'/img/tmp/cms/sellers'; 
	if(!file_exists($thumbs_base_path))mkdir($thumbs_base_path);
	$thumbs_base_path = $thumbs_base_path . '/'. $id_seller;
	if(!file_exists($thumbs_base_path))mkdir($thumbs_base_path);
	$thumbs_base_path =  $thumbs_base_path . '/';
}
