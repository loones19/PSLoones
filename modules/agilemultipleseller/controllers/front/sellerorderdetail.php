<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
class AgileMultipleSellerSellerOrderDetailModuleFrontController extends AgileModuleFrontController
{
	public function init()
	{
		$id_order = (int)Db::getInstance()->getValue("SELECT id_order FROM `" . _DB_PREFIX_ ."orders` WHERE reference = '" . Tools::getValue('ref_order') . "'");
		if($id_order > 0)$_GET['id_order'] = $id_order;

		parent::init();
		header('Cache-Control: no-cache, must-revalidate');
		header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
	}

	public function setMedia()
	{
		if (Tools::getValue('ajax') != 'true')
		{
			parent::setMedia();
			$this->addCSS(_THEME_CSS_DIR_.'history.css');
			$this->addCSS(_THEME_CSS_DIR_.'addresses.css');
		}
	}
	
			public function postProcess()
	{
		$order = new Order( (int)(Tools::getValue('id_order')));
		if(!Validate::isLoadedObject($order))
		{
        	$this->errors[] = Tools::displayError('Order not found or you do not have permission to view this order.');
		    return;
        }
        
		$id_order_seller = AgileSellerManager::getObjectOwnerID('order',$order->id);
		$id_customer_seller = AgileSellerManager::getLinkedSellerID($this->context->customer->id);
        if($id_order_seller != $id_customer_seller || $id_order_seller<=0 || $id_customer_seller<=0)
		{
        	$this->errors[] = Tools::displayError('You do not have permission to view this order.');
		    return;
        }

 				if (Tools::isSubmit('submitShippingNumber') && isset($order))
		{
			$order_carrier = new OrderCarrier(Tools::getValue('id_order_carrier'));
			if (!Validate::isLoadedObject($order_carrier))
				$this->errors[] = Tools::displayError('The order carrier ID is invalid.');
			elseif (!Validate::isTrackingNumber(Tools::getValue('tracking_number')))
				$this->errors[] = Tools::displayError('The tracking number is incorrect.');
			else
			{
												$order->shipping_number = Tools::getValue('tracking_number');
				$order->update();

								$order_carrier->tracking_number = pSQL(Tools::getValue('tracking_number'));
				if ($order_carrier->update())
				{
										$customer = new Customer((int)$order->id_customer);
					$carrier = new Carrier((int)$order_carrier->id_carrier, $order->id_lang);
					if (!Validate::isLoadedObject($customer))
						throw new PrestaShopException('Can\'t load Customer object');
					if (!Validate::isLoadedObject($carrier))
						throw new PrestaShopException('Can\'t load Carrier object');
					$templateVars = array(
						'{followup}' => str_replace('@', $order_carrier->tracking_number, $carrier->url),
						'{firstname}' => $customer->firstname,
						'{lastname}' => $customer->lastname,
						'{id_order}' => $order->id,
						'{shipping_number}' => $order_carrier->tracking_number,
						'{order_name}' => $order->getUniqReference()
						);
					if (@Mail::Send((int)$order->id_lang, 'in_transit', Mail::l('Package in transit', (int)$order->id_lang), $templateVars,
						$customer->email, $customer->firstname.' '.$customer->lastname, null, null, null, null,
						_PS_MAIL_DIR_, true, (int)$order->id_shop))
					{
						Hook::exec('actionAdminOrdersTrackingNumberUpdate', array('order' => $order, 'customer' => $customer, 'carrier' => $carrier), null, false, true, false, $order->id_shop);
					}
					else
						$this->errors[] = Tools::displayError('An error occurred while sending an email to the customer.');
				}
				else
					$this->errors[] = Tools::displayError('The order carrier cannot be updated.');
			}
		}       		
		elseif  (Tools::isSubmit('submitState') && isset($order))
		{
			$order_state = new OrderState(Tools::getValue('id_order_state'));
			if (!Validate::isLoadedObject($order_state))
				$this->errors[] = Tools::displayError('Invalid new order status');
			else
			{
				$current_order_state = $order->getCurrentOrderState();
				if ($current_order_state->id != $order_state->id)
				{
										$history = new OrderHistory();
					$history->id_order = $order->id;
					include_once(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/SellerInfo.php");
					$history->id_employee = SellerInfo::getSellerIdByCustomerId($this->context->customer->id);
					$history->changeIdOrderState($order_state->id, $order->id);

					$carrier = new Carrier($order->id_carrier, $order->id_lang);
					$templateVars = array();
					if ($history->id_order_state == Configuration::get('PS_OS_SHIPPING') && $order->shipping_number)
						$templateVars = array('{followup}' => str_replace('@', $order->shipping_number, $carrier->url));
					elseif ($history->id_order_state == Configuration::get('PS_OS_CHEQUE'))
						$templateVars = array(
							'{cheque_name}' => (Configuration::get('CHEQUE_NAME') ? Configuration::get('CHEQUE_NAME') : ''),
							'{cheque_address_html}' => (Configuration::get('CHEQUE_ADDRESS') ? nl2br(Configuration::get('CHEQUE_ADDRESS')) : '')
						);
					elseif ($history->id_order_state == Configuration::get('PS_OS_BANKWIRE'))
						$templateVars = array(
							'{bankwire_owner}' => (Configuration::get('BANK_WIRE_OWNER') ? Configuration::get('BANK_WIRE_OWNER') : ''),
							'{bankwire_details}' => (Configuration::get('BANK_WIRE_DETAILS') ? nl2br(Configuration::get('BANK_WIRE_DETAILS')) : ''),
							'{bankwire_address}' => (Configuration::get('BANK_WIRE_ADDRESS') ? nl2br(Configuration::get('BANK_WIRE_ADDRESS')) : '')
						);
										if (!$history->addWithemail(true, $templateVars))
    					$this->errors[] = Tools::displayError('An error occurred while changing the status or was unable to send e-mail to the customer.');
				}
				else
					$this->errors[] = Tools::displayError('This order is already assigned this status');
			}
			if(empty($this->errors))self::$smarty->assign('cfmmsg_flag',1);
		}    
    
	
		if (Tools::isSubmit('submitMessage'))
		{
			$idOrder = (int)(Tools::getValue('id_order'));
						$msgText = Tools::getValue('msgText');

			if (!$idOrder || !Validate::isUnsignedId($idOrder))
				$this->errors[] = Tools::displayError('Order is no longer valid');
			else if (empty($msgText))
				$this->errors[] = Tools::displayError('Message cannot be blank');
			else if (!Validate::isMessage($msgText))
				$this->errors[] = Tools::displayError('Message is invalid (HTML is not allowed)');
			if (!count($this->errors))
			{
				$order = new Order($idOrder);
				if (Validate::isLoadedObject($order))
				{
				    										$seller = new Employee((int)SellerInfo::getSellerIdByCustomerId($this->context->customer->id));					$customer = new Customer($order->id_customer);
				    $id_customer_thread = CustomerThread::getIdCustomerThreadByEmailAndIdOrder($customer->email, $order->id);

				    $cm = new CustomerMessage();
				    if (!$id_customer_thread)
				    {
					    $ct = new CustomerThread();
					    $ct->id_contact = 2;
					    $ct->id_customer = (int)$order->id_customer;
					    $ct->id_shop = (int)$this->context->shop->id;
					    $id_product = (int)Tools::getValue('id_product');
					    if($id_product && $order->orderContainProduct($id_product))
						    $ct->id_product = $id_product;
					    $ct->id_order = (int)$order->id;
					    $ct->id_lang = (int)$this->context->language->id;
					    $ct->email = $customer->email;
					    $ct->status = 'open';
					    $ct->token = Tools::passwdGen(12);
					    $ct->add();
				    }
				    else
					    $ct = new CustomerThread((int)$id_customer_thread);

				    $cm->id_customer_thread = $ct->id;
										$cm->message = $msgText;						
				    $cm->ip_address = ip2long($_SERVER['REMOTE_ADDR']);
				    $cm->id_employee = $seller->id;
				    $cm->add();

                    $to = $customer->email;
				    $toName =  $customer->firstname.' '.$customer->lastname;
				    $from = $seller->email;
				    $fromName = $seller->firstname.' '.$seller->lastname;
				    if (Validate::isLoadedObject($customer))
					    Mail::Send($this->context->language->id, 'order_merchant_comment', Mail::l('Message from a seller'),
					    array(
						    '{lastname}' => $customer->lastname,
						    '{firstname}' => $customer->firstname,
						    '{email}' => $customer->email,
						    '{id_order}' => (int)($order->id),
							'{order_name}' => $order->getUniqReference(),
						    '{message}' => Tools::nl2br($msgText)
					    ),
					    $to, $toName, $from, $fromName);
				}
				else
					$this->errors[] = Tools::displayError('Order not found');
			}
		}
	}

	public function displayAjax()
	{
		$this->display();
	}

	 	 	public function initContent()
	{
	
		parent::initContent();

		if (!($id_order = (int)Tools::getValue('id_order')) || !Validate::isUnsignedId($id_order))
			$this->errors[] = Tools::displayError('Order ID required');
		else
		{
			$order = new Order($id_order);
			
			$id_order_seller = AgileSellerManager::getObjectOwnerID('order',$order->id);
            $id_customer_seller = AgileSellerManager::getLinkedSellerID($this->context->customer->id);
						
			if (Validate::isLoadedObject($order) && $id_order_seller == $id_customer_seller && $id_customer_seller>0)
			{
				$id_order_state = (int)($order->getCurrentState());
				$carrier = new Carrier((int)($order->id_carrier), (int)($order->id_lang));
				$addressInvoice = new Address((int)($order->id_address_invoice));
				$addressDelivery = new Address((int)($order->id_address_delivery));

				$inv_adr_fields = AddressFormat::getOrderedAddressFields($addressInvoice->id_country);
				$dlv_adr_fields = AddressFormat::getOrderedAddressFields($addressDelivery->id_country);

				$invoiceAddressFormatedValues = AddressFormat::getFormattedAddressFieldsValues($addressInvoice, $inv_adr_fields);
				$deliveryAddressFormatedValues = AddressFormat::getFormattedAddressFieldsValues($addressDelivery, $dlv_adr_fields);

				if ($order->total_discounts > 0)
					$this->context->smarty->assign('total_old', (float)($order->total_paid - $order->total_discounts));
				$products = $order->getProducts();

								$customizedDatas = Product::getAllCustomizedDatas((int)($order->id_cart));
				Product::addCustomizationPrice($products, $customizedDatas);

				$customer = new Customer($order->id_customer);

        		$order_state = $order->getCurrentOrderState();
        		$order_states = OrderState::getOrderStates((int)$this->context->language->id);
				$this->context->smarty->assign(array(
                    'order_states' => $order_states
                ));


				$this->context->smarty->assign(array(
					'shop_name' => strval(Configuration::get('PS_SHOP_NAME')),
					'order' => $order,
					'return_allowed' => (int)($order->isReturnable()),
					'currency' => new Currency($order->id_currency),
					'order_cur_state' => (int)($id_order_state),
					'invoiceAllowed' => (int)(Configuration::get('PS_INVOICE')),
					'invoice' => (OrderState::invoiceAvailable($id_order_state) && $order->invoice_number),
					'order_history' => $order->getHistory($this->context->language->id, false, true),
					'products' => $products,
					'discounts' => $order->getCartRules(),
					'carrier' => $carrier,
					'address_invoice' => $addressInvoice,
					'invoiceState' => (Validate::isLoadedObject($addressInvoice) && $addressInvoice->id_state) ? new State($addressInvoice->id_state) : false,
					'address_delivery' => $addressDelivery,
					'inv_adr_fields' => $inv_adr_fields,
					'dlv_adr_fields' => $dlv_adr_fields,
					'invoiceAddressFormatedValues' => $invoiceAddressFormatedValues,
					'deliveryAddressFormatedValues' => $deliveryAddressFormatedValues,
					'deliveryState' => (Validate::isLoadedObject($addressDelivery) && $addressDelivery->id_state) ? new State($addressDelivery->id_state) : false,
					'is_guest' => false,
					'messages' => CustomerMessage::getMessagesByOrderId((int)($order->id), false),
					'CUSTOMIZE_FILE' => Product::CUSTOMIZE_FILE,
					'CUSTOMIZE_TEXTFIELD' => Product::CUSTOMIZE_TEXTFIELD,
					'isRecyclable' => Configuration::get('PS_RECYCLABLE_PACK'),
					'use_tax' => Configuration::get('PS_TAX'),
					'group_use_tax' => (Group::getPriceDisplayMethod($customer->id_default_group) == PS_TAX_INC),
										'customizedDatas' => $customizedDatas
									));
				if ($carrier->url && $order->shipping_number)
					$this->context->smarty->assign('followup', str_replace('@', $order->shipping_number, $carrier->url));
				$this->context->smarty->assign('HOOK_ORDERDETAILDISPLAYED', Hook::exec('displayOrderDetail', array('order' => $order)));
				Hook::exec('actionOrderDetail', array('carrier' => $carrier, 'order' => $order));

				unset($carrier, $addressInvoice, $addressDelivery);
			}
			else
				$this->errors[] = Tools::displayError('Cannot find this order');
			unset($order);
		}

        self::$smarty->assign(array(
            'seller_tab_id' => 4
			));

		$this->setTemplate('module:agilemultipleseller/views/templates/front/sellerorderdetail.tpl');
	}

}
