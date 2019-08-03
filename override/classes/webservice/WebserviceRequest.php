<?php
class WebserviceRequest extends WebserviceRequestCore
{
    /*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:30
    * version: 3.7.3.2
    */
    public static function getResources()
	{
		$resources = parent::getResources();
		$resources['order_owners'] = array('description' => 'The order owners histories', 'class' => 'OrderOwner', 'forbidden_method' => array('PUT', 'POST', 'DELETE'));
			
		ksort($resources);
		return $resources;
	}
}
