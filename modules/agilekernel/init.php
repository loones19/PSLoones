<?php
///-build_id: 2018051409.414
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
if(!class_exists('AgileHelper'))
{
	include_once(_PS_ROOT_DIR_ . '/modules/agilekernel/classes/AgileHelper.php');
	eval("class AgileHelper extends AgileHelperCore {}");	
}

if(!class_exists('AgileInstaller'))
{
	include_once(_PS_ROOT_DIR_ . '/modules/agilekernel/classes/AgileInstaller.php');
	eval("class AgileInstaller extends AgileInstallerCore {}");	
}

if(!class_exists('AgileZipper'))
{
	include_once(_PS_ROOT_DIR_ . '/modules/agilekernel/classes/AgileZipper.php');
	eval("class AgileZipper extends AgileZipperCore {}");	
}

if(file_exists(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/init.php"))
	include_once(_PS_ROOT_DIR_ . '/modules/agilemultipleseller/init.php');
