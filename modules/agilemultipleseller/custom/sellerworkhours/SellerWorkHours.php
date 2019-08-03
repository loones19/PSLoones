<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
class SellerWorkHours
{
	const THIS_FEATURE_ON = false;
	
	const OPEN_NOW = 0;
	const CLOSED_TODAY = 1;
	const OUT_OF_HOUR = 2;
	const PERIOD_SEPARATOR = ",";
	
	public static $fields = array('ams_custom_string1','ams_custom_string2','ams_custom_string3','ams_custom_string4','ams_custom_string5','ams_custom_string6','ams_custom_string7');
	
	public static function get_work_hour_message($messages)
	{
		if(!self::THIS_FEATURE_ON)return "";
		
		$context = Context::getContext();
		$sellers = AgileMultipleSeller::getSellersByCart($context->cart->id);

		$retmsg = "";
		foreach($sellers as $seller)
		{
			$result = SellerWorkHours::seller_hour_status($seller['id_seller']) ;
			if($result['status'] != SellerWorkHours::OPEN_NOW)
			{
				$retmsg .= sprintf($messages[$result['status']], $seller['company'], $result['hours']) . "<BR>";
			}
		}
		
		return $retmsg;
	}	
	
	public static function seller_hour_status($id_seller)
	{
		$sellerhours = SellerWorkHours::work_hours_today($id_seller);
		if($sellerhours['IsWorkDay'] == 0)
		{
			return array('status'=>SellerWorkHours::CLOSED_TODAY, 'hours'=>'');
		}
		$now = date('Y-m-d H:i:s');
		
		$p1_hours = $sellerhours['Hours'][0];
		
		$p1_begin = sprintf("%s %02d:%02d:00",date('Y-m-d'), $p1_hours['BeginHour'],$p1_hours['BeginMinute']);
		$p1_end = sprintf("%s %02d:%02d:00",date('Y-m-d'), $p1_hours['EndHour'], $p1_hours['EndMinute']);	
		$nowtime = strtotime($now);
		$p1_begintime = strtotime($p1_begin);
		$p1_endtime = strtotime($p1_end);
		$within_period1 = ($nowtime >= $p1_begintime && $nowtime <= $p1_endtime);

		
		$p2_hours = $sellerhours['Hours'][1];
		$within_period2 = false;
		if(!empty($p2_hours))
		{
			$p2_begin = sprintf("%s %02d:%02d:00",date('Y-m-d'), $p2_hours['BeginHour'],$p2_hours['BeginMinute']);
			$p2_end = sprintf("%s %02d:%02d:00",date('Y-m-d'), $p2_hours['EndHour'], $p2_hours['EndMinute']);	
			$nowtime = strtotime($now);
			$p2_begintime = strtotime($p2_begin);
			$p2_endtime = strtotime($p2_end);
			$within_period2 = ($nowtime >= $p2_begintime && $nowtime <= $p2_endtime);
		}
	
		$ret = array("status" => (($within_period1 || $within_period2)? SellerWorkHours::OPEN_NOW : SellerWorkHours::OUT_OF_HOUR)
			,"hours" => sprintf("%02d:%02d - %02d:%02d",$p1_hours['BeginHour'],$p1_hours['BeginMinute'], $p1_hours['EndHour'], $p1_hours['EndMinute'])
			. (empty($p2_hours)? "" : sprintf(" and %02d:%02d - %02d:%02d",$p2_hours['BeginHour'],$p2_hours['BeginMinute'], $p2_hours['EndHour'], $p2_hours['EndMinute']))
		);
		return $ret;
	}
	
	
	public static function work_hours_today($id_seller)
	{
		$now = date('Y-m-d H:i:s');
		$timestamp = strtotime($now);
		$dayofweek = date( "w", $timestamp);
		return SellerWorkHours::get_work_hours_week($id_seller, $dayofweek);
	}

					public static function get_work_hours_week($id_seller, $dayofweek)
	{
		$context = Context::getContext();
		$sql = 'SELECT s.id_seller,sl.company,' . implode(",", SellerWorkHours::$fields) . ' FROM ' . _DB_PREFIX_ . 'sellerinfo s
					LEFT JOIN ' . _DB_PREFIX_ . 'sellerinfo_lang sl ON (s.id_sellerinfo = sl.id_sellerinfo AND sl.id_lang= ' . (int)$context->cookie->id_lang. ')
				WHERE id_seller = ' .  $id_seller;

		$result = Db::getInstance()->getRow($sql);
		return SellerWorkHours::convert_to_timeperiod($result[SellerWorkHours::$fields[$dayofweek]]); 
	}
	
	public static function convert_to_timeperiod($strData)
	{
				$toRemove   = array(" ", "\t", "\r\n", "\n", "\r");
		$strData = str_replace($toRemove,'',$strData);

		if(empty($strData))
		{
			return array("IsWorkDay" => 0, array());
		}
		
		$periods = explode(self::PERIOD_SEPARATOR, $strData);
		$period1_hours = SellerWorkHours::convert_period_hours($periods[0]);
		$period2_hours = (count($periods)<=1) ? array() : SellerWorkHours::convert_period_hours($periods[1]);
		
		return array(
			"IsWorkDay" => empty($period1_hours) ? 0 : 1
			,"Hours" => array($period1_hours, $period2_hours)
		);
	}
	
	public static function convert_period_hours($period)
	{
		$begin_end = explode("-", $period);
				if(count($begin_end) <= 1)
		{
			return array();
		} 
		$begin = explode(":", $begin_end[0]);
		$end = explode(":", $begin_end[1]);
		if(count($begin) <=1 OR count($end) <=1)
		{
			return array();
		}

		return array("BeginHour" => (int)$begin[0], "BeginMinute" => (int)$begin[1], "EndHour" => (int)$end[0], "EndMinute" => (int)$end[1]);
	}
	
}
