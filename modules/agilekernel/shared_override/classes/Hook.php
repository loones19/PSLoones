<?php
///-build_id: 2018051409.414
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
class Hook extends HookCore
{
	public static function getHookModuleExecList($hook_name = null)
	{
		$list = parent::getHookModuleExecList($hook_name);
		if(!in_array($hook_name, array('displayPayment','dashboardZoneOne', 'dashboardZoneTwo')))return $list;

				if($hook_name == 'displayPayment')
		{
						if(Module::isInstalled('agilemembership') && Module::isInstalled('agilepaypal') && ((int)Configuration::get('AGILE_PAYPAL_AM_INTEGRATED')) == 1)
			{
				include_once(_PS_ROOT_DIR_ . "/modules/agilepaypal/agilepaypal.php");
				$ap = new AgilePaypal();
				if(!$ap->hasNonMembershipProducts())
				{
					foreach($list as $payment)
					{
						if($payment['module'] == 'agilepaypal')return array($payment);
					}
				}
			}
			
						if(!Module::isInstalled('agilemultipleseller') || $hook_name == null || !is_array($list))return $list;
			include_once(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/agilemultipleseller.php");
			$paycolmode = (int)Configuration::get('AGILE_MS_PAYMENT_MODE');
			if($paycolmode == AgileMultipleSeller::PAYMENT_MODE_STORE)return $list;
			$am = new AgileMultipleSeller();
			$integratedModules = array_keys($am->GetIntegratedPaymentModules(false));
			$newlist = array();
			foreach($list as $hook)
			{
				if(in_array($hook['module'], $integratedModules) || ($paycolmode ==  AgileMultipleSeller::PAYMENT_MODE_SELLER && $hook['module'] == 'cashondelivery'))$newlist[] = $hook;
			}
			return $newlist;	
		}
		
				if($hook_name == 'dashboardZoneOne' || $hook_name == 'dashboardZoneTwo')
		{
			$context = Context::getContext();
			if(!Module::isInstalled('agiledashboard') || $context->cookie->profile != (int)Configuration::get('AGILE_MS_PROFILE_ID'))
				return $list;

			$newlist = array();
			include_once(_PS_ROOT_DIR_ . "/modules/agiledashboard/agiledashboard.php");
			$dashmodules = AgileDashboard::get_supported_dashboard_modules();
			foreach($list as $hook)
			{
				if(in_array($hook['module'], $dashmodules))$newlist[] = $hook;
			}
			return $newlist;	
		}		
	}
}
