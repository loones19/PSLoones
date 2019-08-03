<?php
///-build_id: 2019051707.2854
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
require_once(dirname(__FILE__).'/../../config/config.inc.php');
require_once(dirname(__FILE__).'/../../init.php');
require_once(dirname(__FILE__).'/agilesellermessenger.php');
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

$ret = AgileSellerMessenger::activate_message_front(Tools::getValue('id_agile_sellermessage'),Tools::getValue('status'));    
die($ret);

