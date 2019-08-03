<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
define('_AMS_ZIPCODE_RESUTRICTION_','2346,23456');
class ZipcodeRestriction
{
		public static function validate_zipcode($context)
	{
		if(!$context)$context = Context::getContext();

		if(!defined('_AMS_ZIPCODE_RESUTRICTION_'))return '';
		$zipcodes = explode(",", _AMS_ZIPCODE_RESUTRICTION_);
		if(empty($zipcodes))return '';
		if((int)$context->cart->id_address_delivery == 0)return '';		
		$address = new Address($context->cart->id_address_delivery);		
		if(!in_array($address->postcode, $zipcodes))return '';		
		$msg = sprintf(Tools::displayError('We do not delivery to your area with zipcode %s'), $address->postcode);			
		return $msg;
	}
}
