<?php
class CartController extends CartControllerCore
{
    /*
    * module: agilekernel
    * date: 2019-06-24 19:39:53
    * version: 1.7.1.5
    */
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
