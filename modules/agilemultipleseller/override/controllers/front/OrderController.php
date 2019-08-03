<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
class OrderController extends OrderControllerCore
{
	public function HasDeliveryStep()
	{
		$steps = $this->checkoutProcess->getSteps();
		foreach ($steps as $position => $step) {
			if (get_class($step) == "CheckoutDeliveryStep") return true;
		}
		return false;
	}

    public function initContent()
    {
		parent::initContent();
		if(Module::isInstalled('agilesellershipping') && $this->HasDeliveryStep())
		{
			include_once(_PS_ROOT_DIR_  . "/modules/agilesellershipping/agilesellershipping.php");
			AgileSellerShipping::override_carriers();
		}
	}
}
