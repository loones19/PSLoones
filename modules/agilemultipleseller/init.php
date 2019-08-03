<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
if(file_exists(_PS_ROOT_DIR_ . "/classes/controller/AgileModuleFrontController.php"))
	copy(_PS_ROOT_DIR_ . "/modules/agilekernel/classes/AgileModuleFrontController.php",_PS_ROOT_DIR_ . "/classes/controller/AgileModuleFrontController.php");

if(!class_exists('AgileModuleFrontController'))
{
	include_once(_PS_ROOT_DIR_ . '/modules/agilemultipleseller/classes/AgileModuleFrontController.php');
	eval("class AgileModuleFrontController extends AgileModuleFrontControllerCore {}");	
}

if(!class_exists('AgileSellerManager'))
{
	include_once(_PS_ROOT_DIR_ . '/modules/agilemultipleseller/classes/AgileSellerManager.php');
	eval("class AgileSellerManager extends AgileSellerManagerCore {}");	
}

if(!class_exists('HTMLTemplateSellerInvoice'))
{
	include_once(_PS_ROOT_DIR_ . '/modules/agilemultipleseller/classes/HTMLTemplateSellerInvoice.php');
	eval("class HTMLTemplateSellerInvoice extends HTMLTemplateSellerInvoiceCore {}");	
}

if(!class_exists('OrderOwner'))
{
	include_once(_PS_ROOT_DIR_ . '/modules/agilemultipleseller/classes/OrderOwner.php');
	eval("class OrderOwner extends OrderOwnerCore {}");	
}
