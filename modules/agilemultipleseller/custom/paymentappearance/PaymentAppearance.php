<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
class PaymentAppearance
{
	const CART_CONTENTS_MIXED_PRODUCTS = 0;
	const CART_CONTENTS_TOKENS_ONLY = 1;
	const CART_CONTENTS_LISTOPTIONS_ONLY = 2;
	const CART_CONTENTS_SELLER_PRODUCTS = 3;
	
		
	public static function get_cart_contents_type()
	{
		$context = Context::getContext();
		$products = $context->cart->getProducts();
		$nbr_tokens_products = 0;
		$nbr_listoption_products = 0;
		$nbr_seller_products = 0;
		$listoption_category = (int)Configuration::get('ASLO_CATEGORY_ID');
		$tokens_category = (int)Configuration::getGlobalValue('AGILE_PCREDIT_CID');
		foreach($products as $product)
		{
			if((int)$product['id_category_default'] == $listoption_category)$nbr_listoption_products++;
			if((int)$product['id_category_default'] == $tokens_category)$nbr_tokens_products++;
			if(AgileSellerManager::getObjectOwnerID('product',$product['id_product'])>0)$nbr_seller_products++;
		}
		
		$nbr_product = count($products);		
		if($nbr_listoption_products == $nbr_product)return self::CART_CONTENTS_LISTOPTIONS_ONLY;
		if($nbr_tokens_products == $nbr_product)return self::CART_CONTENTS_TOKENS_ONLY;
		if($nbr_seller_products == $nbr_product)return self::CART_CONTENTS_SELLER_PRODUCTS;
		
		return self::CART_CONTENTS_MIXED_PRODUCTS;
	}
}
