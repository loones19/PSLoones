<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
class SellerHolidays
{
	const THIS_FEATURE_ON = true;
	const OPEN_TODAY = 0;
	const CLOSE_TODAY = 1;
	
	const RANGE_SIGN = ":";
	const RANGE_SEPARATOR = ",";
	const DATE_FORMAT = 'Y-m-d';
	
	public static $fields = array('ams_custom_date1','ams_custom_date2'); 	
	public static function get_holidays_message($message)
	{
		if(!self::THIS_FEATURE_ON)return "";
		
		$context = Context::getContext();
		$sellers = AgileMultipleSeller::getSellersByCart($context->cart->id);

		$retmsg = "";
		foreach($sellers as $seller)
		{
			$result = self::seller_holidays_status($seller['id_seller']) ;
			if($result['status'] != self::OPEN_TODAY)
			{
				$retmsg .= sprintf($message, $seller['company'], $result['holidays']) . "<BR>";
			}
		}
		
		return $retmsg;
	}	

	
	public static function seller_holidays_status($id_seller)
	{
		$holidays = self::get_holidays($id_seller);
		$strHolidays = self::convert_date_string($holidays);
		$ranges = self::convert_date_ranges($holidays);
		if(empty($ranges))
		{
			return array('status'=>self::OPEN_TODAY, 'holidays'=>$strHolidays);
		}
		
		$today = new DateTime(date('Y-m-d'));
		$ret = self::OPEN_TODAY;
		foreach($ranges as $range)
		{
			if($today >= new DateTime($range['start']) && $today <= new DateTime($range['end']))
			{
				return array('status'=>self::CLOSE_TODAY, 'holidays'=>$strHolidays);
			}
		}
		
		return array('status'=>self::OPEN_TODAY, 'holidays'=>$strHolidays);;
	}
	
	
	public static function get_holidays($id_seller)
	{
		$context = Context::getContext();
		$sql = 'SELECT s.id_seller,sl.company,' . implode(",", self::$fields) . ' FROM ' . _DB_PREFIX_ . 'sellerinfo s
					LEFT JOIN ' . _DB_PREFIX_ . 'sellerinfo_lang sl ON (s.id_sellerinfo = sl.id_sellerinfo AND sl.id_lang= ' . (int)$context->cookie->id_lang. ')
				WHERE id_seller = ' .  $id_seller;

		$result = Db::getInstance()->getRow($sql);
		return $result; 
	}
	
	public static function convert_date_ranges($holidays)
	{

		if(empty($holidays))
		{
			return array();
		}
		
		$ret = array();
		for($idx=0; $idx < count(self::$fields); $idx++)
		{
			if($idx % 2 == 1 && $idx > 0)
			{
				$startdate_field = self::$fields[$idx - 1];
				$enddate_field = self::$fields[$idx];
				$ret[] = array('start' => $holidays[$startdate_field], 'end' => $holidays[$enddate_field]);
			}
		}		

		return $ret;
	}

	public static function convert_date_string($holidays)
	{
		$ret = "";
		for($idx=0; $idx < count(self::$fields); $idx++)
		{
			if($idx % 2 == 1)
			{
				if($idx > 1)$ret = $ret . self::RANGE_SEPARATOR;
				$startdate_field = self::$fields[$idx - 1];
				$enddate_field = self::$fields[$idx];
				$ret = $ret . $holidays[$startdate_field] . self::RANGE_SIGN . $holidays[$enddate_field];
			}
		}		
		return $ret;
	}
}
