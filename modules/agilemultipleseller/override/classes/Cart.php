<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
class Cart extends CartCore
{
	public function duplicate()
	{
		$duplication = parent::duplicate();
		if(!$duplication || !Validate::isLoadedObject($duplication['cart']))return $duplication;

		$pagename = AgileHelper::getPageName();
		if (($pagename == 'order.php' || $pagename == 'order-opc.php' || $pagename == 'orderopc.php') && Tools::isSubmit('submitReorder') && ($id_order = (int)Tools::getValue('id_order')) && Module::isInstalled('agilesellerlistoptions'))
		{
			include_once(_PS_ROOT_DIR_ . "/modules/agilesellerlistoptions/agilesellerlistoptions.php");
			if(method_exists('AgileSellerListOptions', 'ExpiredProductNbrInOrder'))
			{
				if(AgileSellerListOptions::ExpiredProductNbrInOrder($id_order))
				{
					$duplication['success'] = false;
				}
			}
		}		
		return $duplication;
	}
	
	
	public function getGiftWrappingPrice($with_taxes = true, $id_address = null)
	{
		$wrappingPrice = parent::getGiftWrappingPrice($with_taxes, $id_address);
		if(!Module::isInstalled('agilemultipleseller'))return $wrappingPrice;
        include_once(_PS_ROOT_DIR_  . "/modules/agilemultipleseller/agilemultipleseller.php");
		$sellers = AgileMultipleSeller::getSellersByCart($this->id);
		return count($sellers) * $wrappingPrice;
	}
	
	
	public function getDiscounts($lite = false, $refresh = false)
	{
		if(!Module::isInstalled('agilemultipleseller'))return parent::getDiscounts($lite, $refresh);
				return parent::getDiscounts($lite, true); 
	}
	
	public function checkDiscountValidity($obj, $discounts, $order_total, $products, $check_cart_discount = false)
	{
		if(!Module::isInstalled('agilemultipleseller'))return parent::checkDiscountValidity($obj, $discounts, $order_total, $products, $check_cart_discount);
        include_once(_PS_ROOT_DIR_  . "/modules/agilemultipleseller/agilemultipleseller.php");
		if($valid_err = AgileMultipleSeller::validate_coupon_error($obj))return $valid_err;
		return parent::checkDiscountValidity($obj, $discounts, $order_total, $products, $check_cart_discount);
	}
	
		public function getTotalShippingCost($delivery_option = null, $use_tax = true, Country $default_country = null)
	{
		if(Module::isInstalled('agilesellershipping') AND Module::isInstalled('agilemultipleseller'))
			return $this->getPackageShippingCost(null, $use_tax);
		else
			return parent::getTotalShippingCost($delivery_option, $use_tax, $default_country);
	}
	
	public function getTotalCodFee($id_carrier = null, $use_tax = true, Country $default_country = null, $product_list = null, $id_zone = null){
		if(!Module::isInstalled('agilemultipleseller')){
			return 0;
		}
		$shipping_cost = 0;
		if(Module::isInstalled('agilecashondelivery') && Module::isEnabled('agilecashondelivery'))
		{
			$context = Context::getContext();
			$defaultCountry = $context->country;
			include_once(_PS_ROOT_DIR_  . "/modules/agilemultipleseller/agilemultipleseller.php");
			include_once(_PS_ROOT_DIR_  . "/modules/agilecashondelivery/AgileCarrierCod.php");
			include_once(_PS_ROOT_DIR_  . "/modules/agilecashondelivery/AgileCartCod.php");
			include_once(_PS_ROOT_DIR_  . "/modules/agilecashondelivery/AgileCarrierCodFee.php");
			$carrierCod = new AgileCarrierCod();
			$cartCod = new AgileCartCod();
			$carrierCodFee = new AgileCarrierCodFee();
			$sellers = AgileMultipleSeller::getSellersByCart($this->id);
			if(empty($sellers)) return 0;
			
			if (isset($this->id_address_delivery) AND $this->id_address_delivery
				AND Customer::customerHasAddress($this->id_customer, $this->id_address_delivery)) 
			{
				$id_zone = Address::getZoneById((int)($this->id_address_delivery));
			} else {
				if (!Validate::isLoadedObject($defaultCountry)) {
					$defaultCountry = new Country(Configuration::get('PS_COUNTRY_DEFAULT'), Configuration::get('PS_LANG_DEFAULT'));
				}
				$id_zone = (int)$defaultCountry->id_zone;
			}
			if(Module::isInstalled('agilesellershipping')){
				$carriers = $cartCod->issetCod($this->id);
				if(!empty($carriers))
				{
					include_once(_PS_ROOT_DIR_  . "/modules/agilesellershipping/SellerShipping.php");
					foreach($sellers AS $seller)
					{  
						$carrier_products = SellerShipping::get_carrier_products($this->id, intval($seller['id_seller']));
						$products = $this->getProducts();
						$product_index = array();
						foreach($products as $p)
						{
							$product_index[$p['id_product']] = $p;
						}
						$carrier_amounts = $this->get_carrier_product_amount($carrier_products, $products, $product_index);
						foreach($carrier_amounts AS $id_carrier=>$carrier_amount)
						{
							if($carrierCod->supportCod($id_carrier))
							{
								$seller_cost = $carrierCodFee->getCodFee($id_carrier,$id_zone, $this);
								$shipping_cost += $seller_cost;
							}
						} 
					} 
				}
			} else {
				$carriers = $cartCod->issetCod($this->id);
				if(!empty($carriers))
				{
					foreach ($carriers as $carrier){
						if($carrierCod->supportCod($carrier['id_carrier']))
						{
							$seller_cost = $carrierCodFee->getCodFee($carrier['id_carrier'],$id_zone, $this);
							$shipping_cost += $seller_cost;
						}
					}
				}
				$shipping_cost *= count($sellers);
			}
		}
		return $shipping_cost;
	}
	
	
	public function getCarrierCashOnDeliveryCost($id_seller = null,$id_carrier1 = null, $use_tax = true, Country $default_country = null, $product_list = null, $id_zone = null){
		if(!Module::isInstalled('agilemultipleseller')){
			return 0;
		}
		$shipping_cost = 0;
		if(Module::isInstalled('agilecashondelivery') && Module::isEnabled('agilecashondelivery'))
		{
			$context = Context::getContext();
			$defaultCountry = $context->country;
			include_once(_PS_ROOT_DIR_  . "/modules/agilemultipleseller/agilemultipleseller.php");
			include_once(_PS_ROOT_DIR_  . "/modules/agilecashondelivery/AgileCarrierCod.php");
			include_once(_PS_ROOT_DIR_  . "/modules/agilecashondelivery/AgileCartCod.php");
			include_once(_PS_ROOT_DIR_  . "/modules/agilecashondelivery/AgileCarrierCodFee.php");
			$carrierCod = new AgileCarrierCod();
			$cartCod = new AgileCartCod();
			$carrierCodFee = new AgileCarrierCodFee();
			$sellers = AgileMultipleSeller::getSellersByCart($this->id);
			if(empty($sellers)) return 0;
			
			if (isset($this->id_address_delivery) AND $this->id_address_delivery
				AND Customer::customerHasAddress($this->id_customer, $this->id_address_delivery)) 
			{
				$id_zone = Address::getZoneById((int)($this->id_address_delivery));
			} else {
				if (!Validate::isLoadedObject($defaultCountry)) {
					$defaultCountry = new Country(Configuration::get('PS_COUNTRY_DEFAULT'), Configuration::get('PS_LANG_DEFAULT'));
				}
				$id_zone = (int)$defaultCountry->id_zone;
			}
			if(Module::isInstalled('agilesellershipping')){
				$carriers = $cartCod->issetCod($this->id);
				if(!empty($carriers))
				{
					include_once(_PS_ROOT_DIR_  . "/modules/agilesellershipping/SellerShipping.php");
					foreach($sellers AS $seller)
					{  
						if($id_seller === null || $id_seller == $seller['id_seller'])
						{
							$carrier_products = SellerShipping::get_carrier_products($this->id, intval($seller['id_seller']));
							$products = $this->getProducts();
							$product_index = array();
							foreach($products as $p)
							{
								$product_index[$p['id_product']] = $p;
							}
							$carrier_amounts = $this->get_carrier_product_amount($carrier_products, $products, $product_index);
							foreach($carrier_amounts AS $id_carrier=>$carrier_amount)
							{
								if($id_carrier == $id_carrier1)
								{
									if($carrierCod->supportCod($id_carrier))
									{
										$seller_cost = $carrierCodFee->getCodFee($id_carrier,$id_zone, $this);
										$shipping_cost += $seller_cost;
									}
								}
							}
						}
					} 
				}
			} else {
				$carriers = $cartCod->issetCod($this->id);
				if(!empty($carriers))
				{
					foreach ($carriers as $carrier){
						if($carrier['id_carrier'] == $id_carrier1)
						{
							if($carrierCod->supportCod($carrier['id_carrier']))
							{
								$seller_cost = $carrierCodFee->getCodFee($carrier['id_carrier'],$id_zone, $this);
								$shipping_cost += $seller_cost;
							}
						}
					}
				}
			}
		}
		return $shipping_cost;
	}

	
	
    public function getOrderShippingCost($id_carrier = NULL, $use_tax = true, Country $default_country = null, $product_list = null)
    {
		return $this->getPackageShippingCost($id_carrier, $use_tax, $default_country, $product_list);
	}
		
	public function getPackageShippingCost($id_carrier = null, $use_tax = true, Country $default_country = null, $product_list = null, $id_zone = null)
    {
		$context = Context::getContext();
		$defaultCountry = $context->country;
        if(!Module::isInstalled('agilesellershipping') OR !Module::isInstalled('agilemultipleseller'))
			return parent::getPackageShippingCost($id_carrier, $use_tax, $default_country, $product_list,$id_zone ) + $this->getTotalCodFee(null, $use_tax);

				$order_total = $this->getOrderTotal($use_tax, Cart::ONLY_PRODUCTS_WITHOUT_SHIPPING);

				if ($order_total <= 0 AND !(int)(self::getNbProducts($this->id)))
			return 0;

        include_once(_PS_ROOT_DIR_  . "/modules/agilemultipleseller/agilemultipleseller.php");
        include_once(_PS_ROOT_DIR_  . "/modules/agilesellershipping/SellerShipping.php");

        $sellers = AgileMultipleSeller::getSellersByCart($this->id);
        if(empty($sellers))
  		return parent::getPackageShippingCost($id_carrier, $use_tax, $default_country, $product_list,$id_zone ) + $this->getTotalCodFee(null, $use_tax);

				if (isset($this->id_address_delivery)
			AND $this->id_address_delivery
			AND Customer::customerHasAddress($this->id_customer, $this->id_address_delivery))
			$id_zone = Address::getZoneById((int)($this->id_address_delivery));
		else
		{
						if (!Validate::isLoadedObject($defaultCountry))
				$defaultCountry = new Country(Configuration::get('PS_COUNTRY_DEFAULT'), Configuration::get('PS_LANG_DEFAULT'));
			$id_zone = (int)$defaultCountry->id_zone;
		}


        $shipping_cost = 0;
        foreach($sellers AS $seller)
        {   $seller_cost = $this->getOrderShippingCostPerSeller($id_zone, intval($seller['id_seller']), $use_tax);
            $shipping_cost += $seller_cost;
        }        
        return $shipping_cost;        
    }        

    public function getOrderShippingCostPerSeller($id_zone, $id_seller, $use_tax = true)
    {
                $carrier_products = SellerShipping::get_carrier_products($this->id, $id_seller);
                $products = $this->getProducts();
		$product_index = array();
		foreach($products as $p)
		{
			$product_index[$p['id_product']] = $p;
		}

        $carrier_amounts = $this->get_carrier_product_amount($carrier_products, $products, $product_index);
        $shipping_cost = 0;
        foreach($carrier_amounts AS $id_carrier=>$carrier_amount)
        {
			if($this->is_all_virtual($id_carrier, $carrier_products,$product_index))continue;
			$carrier_weight = $this->getTotalWeightOfCarrier($id_carrier, $id_seller); 
			$carrier_cost = $this->getOrderShippingCostPerSellerCarrier($id_seller, $use_tax, $id_zone, $id_carrier, $carrier_amount,$carrier_weight);
            $shipping_cost += $carrier_cost;
        }
        
        return $shipping_cost;
    }

	public static function is_all_virtual($id_carrier, $carrier_products, $product_index)
	{
		foreach($carrier_products as $cp)
		{
			if($cp['id_carrier'] != $id_carrier)continue;
			$product = $product_index[$cp['id_product']];
			if(intval($product['is_virtual']) != 1)return false;
		}
		return true;
	} 

    public function get_carrier_product_amount($carrier_products, $products, $products_index)
    {
        $carrier_amounts = array();
        foreach($carrier_products AS $carrier_product)
        {
            $id_carrier = intval($carrier_product['id_carrier']);
            $id_product = intval($carrier_product['id_product']);            
            $id_product_attribute = intval($carrier_product['id_product_attribute']);            
            if(!isset($carrier_amounts[$id_carrier]))$carrier_amounts[$id_carrier] = 0;
			if(($products_index[$id_product]['is_virtual']) != 1)
				$carrier_amounts[$id_carrier] += $this->getProductAmount($products, $id_product, $id_product_attribute); 
        }
        return $carrier_amounts;
    }

	public function getOrderShippingCostPerSellerCarrier($id_seller, $use_tax, $id_zone, $id_carrier, $carrier_amount, $carrier_weight)
    {            
                $shipping_cost = 0;

		        
                $carrier = new Carrier($id_carrier,$this->id_lang);
	    	    if ($carrier->is_free == 1)
		    return 0;

				if ($use_tax AND !Tax::excludeTaxeOption())
			 $carrierTax = Tax::getCarrierTaxRate((int)$carrier->id, (int)$this->{Configuration::get('PS_TAX_ADDRESS_TYPE')});
		
        	    $configuration = Configuration::getMultiple(array('PS_SHIPPING_FREE_PRICE', 'PS_SHIPPING_HANDLING', 'PS_SHIPPING_FREE_WEIGHT'));

	    $free_fees_price = 0;
	    if (isset($configuration['PS_SHIPPING_FREE_PRICE']))
		    $free_fees_price = Tools::convertPrice((float)($configuration['PS_SHIPPING_FREE_PRICE']), Currency::getCurrencyInstance((int)($this->id_currency)));

        $free_fees_weight = 0;
	    if (isset($configuration['PS_SHIPPING_FREE_WEIGHT']))
		    $free_fees_weight = Tools::convertPrice((float)($configuration['PS_SHIPPING_FREE_WEIGHT']), Currency::getCurrencyInstance((int)($this->id_currency)));


        $shipping_method = $carrier->getShippingMethod();

	    if ($shipping_method == Carrier::SHIPPING_METHOD_PRICE AND $carrier_amount >= (float)($free_fees_price) AND (float)($free_fees_price) > 0)
	    {
       	    	    }    
	    else if ($shipping_method == Carrier::SHIPPING_METHOD_WEIGHT AND $carrier_weight >= (float)($free_fees_weight) AND (float)($free_fees_weight) > 0)
        {
                    }            
		else if ($shipping_method  == Carrier::SHIPPING_METHOD_WEIGHT)
		{
			$seller_shipping = $carrier->getDeliveryPriceByWeight($carrier_weight, $id_zone);
            $shipping_cost += $seller_shipping;
		}
		else
		{
			$seller_shipping = $carrier->getDeliveryPriceByPrice($carrier_amount, $id_zone, (int)($this->id_currency));
            $shipping_cost += $seller_shipping;
        }
		$shipping_cost += $this->getAdditionalShippingCostOfSeller($id_carrier);

				if($carrier->shipping_handling)
			$shipping_cost += (float)Configuration::get('PS_SHIPPING_HANDLING');		

				if (isset($carrierTax))$shipping_cost *= 1 + ($carrierTax / 100);
               	    
				$shipping_cost = Tools::convertPrice($shipping_cost);
				
		return $shipping_cost  + $this->getCarrierCashOnDeliveryCost($id_seller,$id_carrier);
		
    }

    private function getProductAmount($products, $id_product, $id_product_attribute)
    {
        foreach($products AS $product)
        {
			if($product['id_product'] == $id_product AND $product['id_product_attribute'] == $id_product_attribute)
				return $product['price_wt'] * $product['quantity'];
        }
        return 0;
    }

    private function getProductWeight($products, $id_product)
    {
        foreach($product AS $product)
        {
            if($product['id_product'] == $id_product)
                return $product['weight'] * $product['cart_quantity'];
        }
        return 0;
    }

	private function getAdditionalShippingCostOfSeller($id_carrier)
    {
		$sql = 'SELECT SUM(cp.quantity *  IFNULL(p.additional_shipping_cost,0)) 
				FROM ' . _DB_PREFIX_ . 'cart_product cp 
					LEFT JOIN ' . _DB_PREFIX_ . 'product p ON cp.id_product=p.id_product
					LEFT JOIN `' . _DB_PREFIX_ . 'agile_cartcarrier` cc on (cp.id_cart=cc.id_cart AND cp.id_product=cc.id_product AND cp.id_product_attribute=cc.id_product_attribute) 
				WHERE cc.id_carrier = ' . intval($id_carrier) .'
					AND cc.id_cart = ' . $this->id . '
				';
		return floatval(Db::getInstance()->getValue($sql));
    }
    
    private function getTotalWeightOfSeller($id_seller)
    {
		$sql ='
		    SELECT SUM((p.`weight` + pa.`weight`) * cp.`quantity`) as nb
		    FROM `'._DB_PREFIX_.'cart_product` cp
    		    LEFT JOIN `'._DB_PREFIX_.'product` p ON cp.`id_product` = p.`id_product`
	    	    LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON cp.`id_product_attribute` = pa.`id_product_attribute`
	    	    LEFT JOIN `'._DB_PREFIX_.'product_owner` po ON cp.`id_product` = po.`id_product`
		    WHERE (cp.`id_product_attribute` IS NOT NULL 
		        AND cp.`id_product_attribute` != 0)
		        AND po.id_owner = ' . $id_seller . '
    		    AND cp.`id_cart` = '.(int)($this->id);

    	$result = Db::getInstance()->getRow($sql);

        $sql ='
		    SELECT SUM(p.`weight` * cp.`quantity`) as nb
		    FROM `'._DB_PREFIX_.'cart_product` cp
    		    LEFT JOIN `'._DB_PREFIX_.'product` p ON cp.`id_product` = p.`id_product`
	    	    LEFT JOIN `'._DB_PREFIX_.'product_owner` po ON cp.`id_product` = po.`id_product`
		    WHERE (cp.`id_product_attribute` IS NULL OR cp.`id_product_attribute` = 0)
		        AND po.id_owner = ' . $id_seller . '
    		    AND cp.`id_cart` = '.(int)($this->id);
	    $result2 = Db::getInstance()->getRow($sql);

		$weight =  round((float)($result['nb']) + (float)($result2['nb']), 3);
		return $weight;
    }


	public function getTotalWeightOfCarrier($id_carrier, $id_seller)
    {
		$sql ='
		    SELECT SUM((p.`weight` + pa.`weight`) * cp.`quantity`) as nb
		    FROM `'._DB_PREFIX_.'cart_product` cp
    		    LEFT JOIN `'._DB_PREFIX_.'product` p ON cp.`id_product` = p.`id_product`
	    	    LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON cp.`id_product_attribute` = pa.`id_product_attribute`
	    	    LEFT JOIN `'._DB_PREFIX_.'agile_cartcarrier` cc ON cp.`id_product` = cc.`id_product` AND cc.id_cart=cp.id_cart  AND cp.id_product_attribute=cc.id_product_attribute
    		    LEFT JOIN `'._DB_PREFIX_.'product_owner` po ON p.`id_product` = po.`id_product`
		    WHERE (cp.`id_product_attribute` IS NOT NULL 
		        AND cp.`id_product_attribute` != 0)
		        AND cc.id_carrier = ' . intval($id_carrier) . '
		        AND po.id_owner = ' .  intval($id_seller) . '
    		    AND cp.`id_cart` = '.(int)($this->id);

    	$result = Db::getInstance()->getRow($sql);

        $sql ='
		    SELECT SUM(p.`weight` * cp.`quantity`) as nb
		    FROM `'._DB_PREFIX_.'cart_product` cp
    		    LEFT JOIN `'._DB_PREFIX_.'product` p ON cp.`id_product` = p.`id_product`
	    	    LEFT JOIN `'._DB_PREFIX_.'agile_cartcarrier` cc ON cp.`id_product` = cc.`id_product` AND cc.id_cart=cp.id_cart  AND cp.id_product_attribute=cc.id_product_attribute
    		    LEFT JOIN `'._DB_PREFIX_.'product_owner` po ON p.`id_product` = po.`id_product`
		    WHERE (cp.`id_product_attribute` IS NULL OR cp.`id_product_attribute` = 0)
		        AND cc.id_carrier = ' . intval($id_carrier) . '
		        AND po.id_owner = ' .  intval($id_seller) . '
    		    AND cp.`id_cart` = '.(int)($this->id);
	    $result2 = Db::getInstance()->getRow($sql);

		$weight =  round((float)($result['nb']) + (float)($result2['nb']), 3);
		return $weight;
    }
    
	public function getDeliveryAddressesWithoutCarriers($return_collection = false, &$error = array())
	{
		if(!Module::isInstalled('agilesellershipping'))return parent::getDeliveryAddressesWithoutCarriers($return_collection);		
		return array();
	}

}
