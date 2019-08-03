<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
define('_AMS_SELLER_MIN_PURCHASE_',0);
define('_AMS_SELLER_MIN_PURCHASE_FIELD_', 'ams_custom_number1');
define('_AMS_SELLER_MIN_PURCHASE_AFTER_DISCOUNT_', true);
define('_AMS_SELLER_MIN_PURCHASE_WITHTAX_',0);

class SellerMinimumPurchase
{
		public static function validate_seller_min_purchase($context, $short=false)
	{
		if(!$context)$context = Context::getContext();

		if(!defined('_AMS_SELLER_MIN_PURCHASE_') OR !defined('_AMS_SELLER_MIN_PURCHASE_FIELD_') OR _AMS_SELLER_MIN_PURCHASE_ ==0)return '';
		if(_AMS_SELLER_MIN_PURCHASE_FIELD_ == '')return '';
		$sql = 'SELECT s.id_seller,sl.company,' . _AMS_SELLER_MIN_PURCHASE_FIELD_ . ' FROM ' . _DB_PREFIX_ . 'sellerinfo s
					LEFT JOIN ' . _DB_PREFIX_ . 'sellerinfo_lang sl ON (s.id_sellerinfo = sl.id_sellerinfo AND sl.id_lang= ' . (int)$context->cookie->id_lang. ')
					WHERE id_seller IN (SELECT id_owner FROM ' . _DB_PREFIX_ . 'cart_product cp LEFT JOIN ' . _DB_PREFIX_ . 'product_owner po ON cp.id_product=po.id_product WHERE cp.id_cart=' . (int)$context->cart->id . ')';

		$sellers = Db::getInstance()->ExecuteS($sql);
		$sellers_total = array();
		$products = $context->cart->getProducts();
		foreach($products as $product)
		{
			$id_seller = (int)AgileSellerManager::getObjectOwnerID('product',$product['id_product']);
			if(!isset($sellers_total[$id_seller]))$sellers_total[$id_seller] = 0;
			if(_AMS_SELLER_MIN_PURCHASE_WITHTAX_ == 1)
				$sellers_total[$id_seller] += $product['quantity'] * $product['price_wt'];
			else
				$sellers_total[$id_seller] += $product['quantity'] * $product['price'];
		}
		
		$ret = '';
		$currency = Currency::getCurrency((int)$context->cart->id_currency);
		foreach($sellers as $seller)
		{	
			$id_seller = (int)$seller['id_seller'];
			$seller_total = $sellers_total[$id_seller];
			$min_purchase = (float)$seller[_AMS_SELLER_MIN_PURCHASE_FIELD_];
			if ($seller_total < $min_purchase)
			{
				if(!empty($ret))
				{
					 $ret .= '<br />';
				}
				$ret .= sprintf(
					$short ? Tools::displayError('Minimum Purchase: %s') : Tools::displayError('A minimum purchase amount of %s is required from Seller %s in order to validate your order.'),
					Tools::displayPrice($min_purchase, $currency),
					$seller['company']
				);
			}
		}
		
		return $ret;
	}
	
}
