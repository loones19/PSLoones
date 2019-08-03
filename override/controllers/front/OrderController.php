<?php
class OrderController extends OrderControllerCore
{
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:45
    * version: 3.7.3.2
    */
    public function HasDeliveryStep()
	{
		$steps = $this->checkoutProcess->getSteps();
		foreach ($steps as $position => $step) {
			if (get_class($step) == "CheckoutDeliveryStep") return true;
		}
		return false;
	}
    /*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:45
    * version: 3.7.3.2
    */
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
