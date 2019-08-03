<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
class OrderConfirmationController extends OrderConfirmationControllerCore
{
	public $is_original_cart = false;
	public $id_cart_parent;
	public $orders = array();
	public $ordersTotalPaidSumamry=0;

    public function init()
	{
				if(Module::isInstalled('agilemultipleseller'))
		{
			include_once(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/agilemultipleseller.php");
			$id_cart = (int)Tools::getValue('id_cart');
			$id_order = (int)Order::getOrderByCartId($id_cart);
						if($id_order == 0)
			{
				$this->id_cart_parent = $id_cart;
				$_GET['id_cart'] = (int)AgileMultipleSeller::get_last_subcart_id($id_cart);
			}
			else
			{
				$this->id_cart_parent = (int)AgileMultipleSeller::get_subcart_parentid($id_cart);
				if($this->id_cart_parent == 0 || ($this->id_cart_parent == $id_cart && $id_cart>0))
				{
					$this->is_original_cart = true;
				}
			}
		}
		
				parent::init();
	}

	public function initContent()
	{
		$this->retrieveOrders();
		
		$agile_payment_split = (!$this->is_original_cart && count($this->orders)> 1  && (int)Configuration::get('AGILE_MS_PAYMENT_MODE') != AgileMultipleSeller::PAYMENT_MODE_STORE)? 1 : 0;
		$this->context->smarty->assign(array(
			'is_original_cart' => ($this->is_original_cart ? 1 : 0)
			,'agile_payment_split' => $agile_payment_split
			,'nb_products_in_cart' => $this->context->cart->nbProducts()
			));
		
						parent::initContent();

				if(!Module::isInstalled('agilemultipleseller'))return;

	
		if($this->is_original_cart)return;
		
				$presentedOrders = array();
		foreach($this->orders as $order)
		{
			$presentedOrders[] = $this->order_presenter->present($order);
		}
		$this->context->smarty->assign(array(
			'is_guest' => $this->context->customer->is_guest,
			'HOOK_ORDER_CONFIRMATION' => $this->displayHookSplitCart('displayOrderConfirmation'),
			'HOOK_PAYMENT_RETURN' => $this->displayHookSplitCart('displayPaymentReturn'),
			'orders' => $presentedOrders
			));
		
		$this->setTemplate('checkout/order-confirmation');
	}
	
	
	public function displayPaymentReturn($order)
	{
		if(!Module::isInstalled('agilemultipleseller'))
			return parent::displayPaymentReturn($order);

		return $this->displayHookSplitCart('displayPaymentReturn');
	}
	
	public function displayOrderConfirmation($order)
	{
		if(!Module::isInstalled('agilemultipleseller'))
			return parent::displayOrderConfirmation($order);

		return $this->displayHookSplitCart('displayOrderConfirmation');
	}
	
	private function retrieveOrders()
	{
		$module = Module::getInstanceById((int)($this->id_module));
		$idrows = AgileMultipleSeller::getOrdersByParentCartID($this->id_cart_parent);
		$this->orders = array();
		$this->ordersTotalPaidSumamry = 0;
		foreach($idrows as $idrow)
		{
			$order = new Order((int)$idrow['id_order']);
									if(Validate::isLoadedObject($order) && $order->module == $module->name)
			{
				$this->ordersTotalPaidSumamry = $this->ordersTotalPaidSumamry + $order->getOrdersTotalPaid();
				$this->orders[] = $order;
			}
		}
	}
	
	
	private function displayHookSplitCart($hookname)
	{

		$hookContent = '';
		$payment_mode = (int)Configuration::get('AGILE_MS_PAYMENT_MODE');

		foreach($this->orders as $order)
		{
			if (Validate::isLoadedObject($order))
			{
				$params = array();
				$currency = new Currency($order->id_currency);
				$params['total_to_pay'] = $order->getOrdersTotalPaid();
				$params['currency'] = $currency->sign;
				$params['order'] = $order;
				$params['currencyObj'] = $currency;
				$hookret = Hook::exec($hookname, $params, $this->id_module);
				$id_seller = AgileSellerManager::getObjectOwnerId('order', $order->id);
				$sellerinfo = new SellerInfo(SellerInfo::getIdBSellerId($id_seller), $this->context->cookie->id_lang);
				if(!empty($hookret))
				{
					$hookContent = $hookContent . '<p><hr><span>**********  '  . $sellerinfo->company. ' **********</span><hr></p>' . Hook::exec($hookname, $params, $this->id_module);
				}
			}
		}
		return $hookContent;	
	}
	
}

