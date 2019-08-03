<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
include(dirname(__FILE__). '/../../config/config.inc.php');

if (!isset(Context::getContext()->customer) || !Context::getContext()->customer->id || !Context::getContext()->customer->isLogged())
	die(Tools::displayError('Permission Denied'));

if (isset($_FILES['virtual_product_file_attribute']) && is_uploaded_file($_FILES['virtual_product_file_attribute']['tmp_name']) && 
(isset($_FILES['virtual_product_file_attribute']['error']) && !$_FILES['virtual_product_file_attribute']['error'])	|| 
(!empty($_FILES['virtual_product_file_attribute']['tmp_name']) && $_FILES['virtual_product_file_attribute']['tmp_name'] != 'none'))
{
	$filename = $_FILES['virtual_product_file_attribute']['name'];
	$file = $_FILES['virtual_product_file_attribute']['tmp_name'];
	$newfilename = ProductDownload::getNewFilename();

	if (!copy($file, _PS_DOWNLOAD_DIR_.$newfilename))
	{
		header('HTTP/1.1 500 Error');
		echo '<return result="error" msg="No permissions to write in the download folder" filename="'.Tools::safeOutput($filename).'" />';
	}
	@unlink($file);

	header('HTTP/1.1 200 OK');
	echo '<return result="success" msg="'.Tools::safeOutput($newfilename).'" filename="'.Tools::safeOutput($filename).'" />';
}
else
{
	header('HTTP/1.1 500 Error');
	echo '<return result="error" msg="Unknown error" filename="'.Tools::safeOutput(ProductDownload::getNewFilename()).'" />';
}
