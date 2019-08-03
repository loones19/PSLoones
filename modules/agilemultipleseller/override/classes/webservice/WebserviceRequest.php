<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
class WebserviceRequest extends WebserviceRequestCore
{
    public static function getResources()
	{
		$resources = parent::getResources();

		$resources['order_owners'] = array('description' => 'The order owners histories', 'class' => 'OrderOwner', 'forbidden_method' => array('PUT', 'POST', 'DELETE'));
			
		ksort($resources);
		return $resources;
	}

}
