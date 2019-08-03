<?php
///-build_id: 2018051409.414
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
class CartController extends CartControllerCore
{
    public function postProcess()
	{
		if(Tools::getIsset('add'))
		{
			$ok2proceed = true;
						if(Module::isInstalled('agilemembership')) 
			{
				include_once(_PS_ROOT_DIR_  . "/modules/agilemembership/agilemembership.php");
				$ammodule = new AgileMembership();
				$ok2proceed = $ammodule->can_order_product();
			}
			if(!$ok2proceed) return;
			
						if(Module::isInstalled('agileprepaidcredit'))
			{	
				include_once(_PS_ROOT_DIR_  . "/modules/agileprepaidcredit/agileprepaidcredit.php");
				$apmodule = new AgilePrepaidCredit();
				$new_order = $apmodule->add_to_cart_handler();
				
								$ok2proceed = ($new_order == 0 && empty($this->errors ));
			}
			if(!$ok2proceed)return;

						if(Module::isInstalled('agilemembership')) 
			{
				include_once(_PS_ROOT_DIR_  . "/modules/agilemembership/agilemembership.php");
				$ammodule = new AgileMembership();
				$ok2proceed = $ammodule->can_add_to_cart();
			}
			if(!$ok2proceed)return;
			
			if(Module::isInstalled('agilemultipleseller'))
			{
				include_once(_PS_ROOT_DIR_  . "/modules/agilemultipleseller/agilemultipleseller.php");
				$msmodule = new AgileMultipleSeller();
				$ok2proceed = $msmodule->can_add_to_cart();
			}
			
			if(!$ok2proceed)return;
		}

		parent::postProcess();
	}
	
}
