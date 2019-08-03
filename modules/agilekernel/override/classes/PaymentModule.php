<?php
///-build_id: 2018051409.414
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
class PaymentModule extends PaymentModuleCore
{	
    public function validateOrder($id_cart, $id_order_state, $amountPaid, $paymentMethod = 'Unknown',   $message = NULL, $extraVars = array(),   $currency_special = NULL, $dont_touch_amount = false,     $secure_key = false, Shop $shop = null)
	{
		if (session_status() == PHP_SESSION_NONE)@session_start();
		@set_time_limit(300);
		
		$needchangeorderstatus = false;
		$original_order_state = $id_order_state;
		if(Module::isInstalled('agileprepaidcredit'))
		{
			require_once(_PS_ROOT_DIR_ . "/modules/agileprepaidcredit/agileprepaidcredit.php");	
						AgilePrepaidCredit::set_token_payment_processing_marker($id_cart, true);
			$tokensUsedInCart = AgilePrepaidCredit::tokens_used_in_cart($id_cart);
			if($tokensUsedInCart > 0 && in_array($id_order_state, array(2,8,12)) && $this->name != 'agileprepaidcredit')
			{
				$needchangeorderstatus = true;
				$id_order_state = (int)Configuration::get('AGILE_PCREDIT_WAITING_STATE');
			}
		}

	    if(!Module::isInstalled('agilemultipleseller'))
	    {
			$ret = parent::validateOrder($id_cart, $id_order_state, $amountPaid, $paymentMethod, $message, $extraVars, $currency_special, $dont_touch_amount, $secure_key);
			if(Module::isInstalled('agileprepaidcredit'))
			{	
				AgilePrepaidCredit::adjustOrderForTokens($this->currentOrder);
				if($needchangeorderstatus)$this->changeOrderState($this->currentOrder, $original_order_state);
			}
			return $ret;
		}
        require_once(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/agilemultipleseller.php");
		$paymode = (int)Configuration::get('AGILE_MS_PAYMENT_MODE');		
        $sellers = AgileMultipleSeller::getSellersByCart($id_cart);
        if(count($sellers)<=1)
		{
			$id_cart_patent = AgileMultipleSeller::get_subcart_parentid($id_cart);
			$ordervalided =  parent::validateOrder($id_cart, $id_order_state, $amountPaid, $paymentMethod, $message, $extraVars, $currency_special, $dont_touch_amount, $secure_key);
			$this->updateSellerCommissionRecordType($paymode, $message);
			if($ordervalided AND $id_cart_patent > 0)
			{
								AgileMultipleSeller::remove_subcart_items_from_maincart($id_cart, $this->currentOrder);
			}
			if(Module::isInstalled('agileprepaidcredit'))
			{	
				AgilePrepaidCredit::adjustOrderForTokens($this->currentOrder);
				if($needchangeorderstatus)$this->changeOrderState($this->currentOrder, $original_order_state);
			}
			return $ordervalided;
		}

						$cartinfos = AgileMultipleSeller::split_shopping_cart($id_cart, $sellers);
        if(empty($cartinfos))
        {
			$ret = parent::validateOrder($id_cart, $id_order_state, $amountPaid, $paymentMethod, $message, $extraVars, $currency_special, $dont_touch_amount, $secure_key);
			$this->updateSellerCommissionRecordType($paymode, $message);
			if(Module::isInstalled('agileprepaidcredit'))
			{	
				AgilePrepaidCredit::adjustOrderForTokens($this->currentOrder);
				if($needchangeorderstatus)$this->changeOrderState($this->currentOrder, $original_order_state);
			}
			return $ret;
		}
        $ret = true;
        foreach($cartinfos AS $cartinfo)
        {
						$this->context->cart = new Cart(intval($cartinfo['id_cart']));
			$this->context->cart->getPackageList(true);
			$filter = CartRule::FILTER_ACTION_ALL;	
						$cache_key = 'Cart::getCartRules'.$cartinfo['id_cart'].'-'.$filter;
			if (Cache::isStored($cache_key))Cache::clean($cache_key);
			
			if(Module::isInstalled('agileprepaidcredit'))
			{
								AgilePrepaidCredit::set_token_payment_processing_marker($cartinfo['id_cart'], true);
			}
            $ret = $ret AND  parent::validateOrder($cartinfo['id_cart'], $id_order_state, $cartinfo['amountPaid'], $paymentMethod, $message, $extraVars, $currency_special, $dont_touch_amount, $secure_key);
			$this->updateSellerCommissionRecordType($paymode, $message);
			if($this->name == 'agilepaypalparallel')$this->updateTxnDetailCartID($message, $this->currentOrder, $cartinfo['id_cart']);
			if(Module::isInstalled('agileprepaidcredit'))
			{	
				AgilePrepaidCredit::adjustOrderForTokens($this->currentOrder);
				if($needchangeorderstatus)$this->changeOrderState($this->currentOrder, $original_order_state);
			}
		}
        return $ret;
    }	

    public static function getInstalledPaymentModules()
	{
		if(Module::isInstalled('agilemultipleseller'))
		{
						$sql = "DELETE FROM " . _DB_PREFIX_ ."hook_module  WHERE id_shop != " . (int)Configuration::get('PS_SHOP_DEFAULT');
			Db::getInstance()->Execute($sql);
		}
		return parent::getInstalledPaymentModules();
	}
	
    protected function updateSellerCommissionRecordType($record_type, $txn_id)
	{
		if(Module::isInstalled('agilesellercommission'))
		{
			require_once(_PS_ROOT_DIR_ . "/modules/agilesellercommission/SellerCommission.php");
			if($this->currentOrder>0)
			{
				SellerCommission::updateRecordType($this->currentOrder, $record_type, $txn_id);
			}
		}		
	}

	private function changeOrderState($id_order, $order_state_id)
	{
		$order_state = new OrderState($order_state_id);
		$order = new Order((int)$id_order);
		if (!Validate::isLoadedObject($order))return; 
		$current_order_state = $order->getCurrentOrderState();
		if ($current_order_state->id == $order_state->id)return;
		$history = new OrderHistory();
		$history->id_order = $order->id;
		$history->id_employee = 0;
		$use_existings_payment = !$order->hasInvoice();
		$history->changeIdOrderState((int)$order_state->id, $order, $use_existings_payment);
		if (!$history->addWithemail(true, array()))return;
		if (!Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT'))return;
		foreach ($order->getProducts() as $product) 
		{
			if (StockAvailable::dependsOnStock($product['product_id'])) 
			{
				StockAvailable::synchronize($product['product_id'], (int)$product['id_shop']);
			}
		}
	}
}
