<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
require_once(dirname(__FILE__).'/../../config/config.inc.php');
include_once(_PS_ROOT_DIR_ .  "/modules/agilemultipleseller/agilemultipleseller.php");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
@set_time_limit(600);

$module = new AgileMultipleSeller();

		$controllers_flag = _PS_ROOT_DIR_ . "/modules/agilemultipleseller/override/controllers.flg";
		if(file_exists($controllers_flag ))die("Override controllers has been installed before.");
		
				$classes_flag = _PS_ROOT_DIR_ . "/modules/agilemultipleseller/override/classes.flg";
		if(file_exists($classes_flag))
		{
						if(file_exists(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/override/classes") && !file_exists(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/override/classes__"))
			{
				rename(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/override/classes", _PS_ROOT_DIR_ . "/modules/agilemultipleseller/override/classes__");
			}
		}
		
				if(file_exists(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/override/controllers__") && !file_exists(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/override/controllers"))
		{
			rename(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/override/controllers__", _PS_ROOT_DIR_ . "/modules/agilemultipleseller/override/controllers");
			$module->installOverrides();
		}
		
				$handle = fopen($controllers_flag, "a+");
		fwrite($handle, date("Y-m-d H:i:s") . " installed\r\n");
		fclose($handle);
		
				if(file_exists($classes_flag))
		{
			if(file_exists(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/override/classes__") && !file_exists(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/override/classes"))
			{
				rename(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/override/classes__", _PS_ROOT_DIR_ . "/modules/agilemultipleseller/override/classes");
			}
		}
		
		die("Override controllers has been installed.");
