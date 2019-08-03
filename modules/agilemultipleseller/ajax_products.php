<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.

require_once(dirname(__FILE__).'/../../config/config.inc.php');
require_once(dirname(__FILE__).'/../../init.php');
require_once(dirname(__FILE__).'/agilemultipleseller.php');
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

if (!isset(Context::getContext()->customer) || !Context::getContext()->customer->id || !Context::getContext()->customer->isLogged())
	die(Tools::jsonEncode(array('status'=>'error', 'message'=>Tools::displayError('Permission denied'))));

$id_seller = AgileSellerManager::getLinkedSellerID(Context::getContext()->customer->id);

$id_product = intval(Tools::getValue('id_product'));
if($id_seller >0 AND $id_product> 0 AND $id_seller!= AgileSellerManager::getObjectOwnerID('product',$id_product))
	die(Tools::jsonEncode(array('status'=>'error', 'message'=>Tools::displayError('Permission denied'))));

$action = Tools::getValue('action');

if($action=='updateImagePosition')
    die(ajaxProcessUpdateImagePosition());
if($action=='deleteProductImage')
    die(ajaxProcessDeleteProductImage());
if($action=='UpdateCover')
    die(ajaxProcessUpdateCover());
if($action=='UpdateProductImageShopAsso')
    die(ajaxProcessUpdateProductImageShopAsso());    
if($action=='DeleteSpecificPrice')
    die(ajaxProcessDeleteSpecificPrice());    
if($action=='productQuantity')
    die(ajaxProcessProductQuantity());    
if($action=='deleteProductAttribute')
    die(ajaxProcessDeleteProductAttribute());    
if($action=='defaultProductAttribute')
    die(ajaxProcessDefaultProductAttribute());    
if($action=='editProductAttribute')
    die(ajaxProcessEditProductAttribute());    
if($action=='deleteVirtualProduct')
	die(ajaxDeleteVirtualProduct());    
   

die(Tools::jsonEncode(array('status'=>'error', 'message'=>Tools::displayError('Unknown Request'))));



	function ajaxProcessProductQuantity()
	{
		if (!Tools::getValue('actionQty'))
			return Tools::jsonEncode(array('error' => Tools::displayError('Undefined action')));

		$product = new Product((int)Tools::getValue('id_product'));
		switch (Tools::getValue('actionQty'))
		{
			case 'depends_on_stock':
				if (Tools::getValue('value') === false)
					return (Tools::jsonEncode(array('error' =>  Tools::displayError('Undefined value'))));
				if ((int)Tools::getValue('value') != 0 && (int)Tools::getValue('value') != 1)
					return (Tools::jsonEncode(array('error' =>  Tools::displayError('Uncorrect value'))));
				if (!$product->advanced_stock_management && (int)Tools::getValue('value') == 1)
					return (Tools::jsonEncode(array('error' =>  Tools::displayError('Not possible if advanced stock management is not enabled'))));
				if ($product->advanced_stock_management && Pack::isPack($product->id))
					return (Tools::jsonEncode(array('error' =>  Tools::displayError('Not possible if the product is a pack'))));

				StockAvailable::setProductDependsOnStock($product->id, (int)Tools::getValue('value'));
				break;

			case 'out_of_stock':
				if (Tools::getValue('value') === false)
					return (Tools::jsonEncode(array('error' =>  Tools::displayError('Undefined value'))));
				if (!in_array((int)Tools::getValue('value'), array(0, 1, 2)))
					return (Tools::jsonEncode(array('error' =>  Tools::displayError('Uncorrect value'))));

				StockAvailable::setProductOutOfStock($product->id, (int)Tools::getValue('value'));
				break;

			case 'set_qty':
				if (Tools::getValue('value') === false)
					return (Tools::jsonEncode(array('error' =>  Tools::displayError('Undefined value'))));
				if (Tools::getValue('id_product_attribute') === false)
					return (Tools::jsonEncode(array('error' =>  Tools::displayError('Undefined id product attribute'))));

				StockAvailable::setQuantity($product->id, (int)Tools::getValue('id_product_attribute'), (int)Tools::getValue('value'));
				break;
			case 'advanced_stock_management' :
				if (Tools::getValue('value') === false)
					return (Tools::jsonEncode(array('error' =>  Tools::displayError('Undefined value'))));
				if ((int)Tools::getValue('value') != 1 && (int)Tools::getValue('value') != 0)
					return (Tools::jsonEncode(array('error' =>  Tools::displayError('Uncorrect value'))));
				if (!Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT') && (int)Tools::getValue('value') == 1)
					return (Tools::jsonEncode(array('error' =>  Tools::displayError('Not possible if advanced stock management is not enabled'))));
				if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT') && Pack::isPack($product->id))
					return (Tools::jsonEncode(array('error' =>  Tools::displayError('Not possible if the product is a pack'))));

				$product->advanced_stock_management = (int)Tools::getValue('value');
				$product->save();
				if (StockAvailable::dependsOnStock($product->id) == 1 && (int)Tools::getValue('value') == 0)
					StockAvailable::setProductDependsOnStock($product->id, 0);
				break;

		}
		return(Tools::jsonEncode(array('error' => false)));
	}


	function ajaxProcessUpdateProductImageShopAsso()
	{
		if (($id_image = Tools::getValue('id_image')) && ($id_shop = (int)Tools::getValue('id_shop')))
			if (Tools::getValue('active') == 'true')
				$res = Db::getInstance()->execute(
					'INSERT INTO '._DB_PREFIX_.'image_shop (`id_image`, `id_shop`)
					VALUES('.(int)$id_image.', '.(int)$id_shop.')
				');
			else
				$res = Db::getInstance()->execute('
					DELETE FROM '._DB_PREFIX_.'image_shop
					WHERE `id_image`='.(int)$id_image.' && `id_shop`='.(int)$id_shop
				);

				if ($res)$json =  array('status' => 'ok','message'=> '');
		else $json = array('status'=>'error', 'message'=>Tools::displayError('Error on picture shop association'));

		return Tools::jsonEncode($json);
	}


	function ajaxProcessUpdateCover()
	{
		Image::deleteCover((int)Tools::getValue('id_product'));
		$img = new Image((int)Tools::getValue('id_image'));
		$img->cover = 1;

		@unlink(_PS_TMP_IMG_DIR_.'product_'.(int)$img->id_product.'.jpg');
		@unlink(_PS_TMP_IMG_DIR_.'product_mini_'.(int)$img->id_product.'.jpg');

		if ($img->update())$json =  array('status' => 'ok','message'=> '');
		else $json = array('status'=>'error', 'message'=>Tools::displayError('Error on updating product cover'));
		
		return Tools::jsonEncode($json);

	}

	function ajaxProcessDeleteProductImage()
	{
				$res = true;
		$image = new Image((int)Tools::getValue('id_image'));
		$res &= $image->delete();
				if (!Image::getCover($image->id_product))
		{
			$res &= Db::getInstance()->execute('
			UPDATE `'._DB_PREFIX_.'image`
			SET `cover` = 1
			WHERE `id_product` = '.(int)$image->id_product.' LIMIT 1');
		}

		if (file_exists(_PS_TMP_IMG_DIR_.'product_'.$image->id_product.'.jpg'))
			$res &= @unlink(_PS_TMP_IMG_DIR_.'product_'.$image->id_product.'.jpg');
		if (file_exists(_PS_TMP_IMG_DIR_.'product_mini_'.$image->id_product.'.jpg'))
			$res &= @unlink(_PS_TMP_IMG_DIR_.'product_mini_'.$image->id_product.'.jpg');

		if ($res)$json =  array('status' => 'ok','content'=>array('id'=>$image->id), 'message'=> '');
		else $json = array('status'=>'error', 'message'=>Tools::displayError('Error on deleting product image'));
		
		return Tools::jsonEncode($json);
	}

	function ajaxProcessUpdateImagePosition()
	{
		$res = false;
		if ($json = Tools::getValue('json'))
		{
			$res = true;
			$json = stripslashes($json);
			$images = Tools::jsonDecode($json, true);
			foreach ($images as $id => $position)
			{
				$img = new Image((int)$id);
				$img->position = (int)$position;
				$res &= $img->update();
			}
		}

		if ($res)$json =  array('status' => 'ok','message'=> '');
		else $json = array('status'=>'error', 'message'=>Tools::displayError('Error on moving picture'));
		
		return Tools::jsonEncode($json);
	}
	
	function ajaxProcessDeleteSpecificPrice()
	{
		$id_specific_price = (int)Tools::getValue('id_specific_price');
		if (!$id_specific_price || !Validate::isUnsignedId($id_specific_price))
			$error = Tools::displayError('Invalid specific price ID');
		else
		{
			$specificPrice = new SpecificPrice((int)$id_specific_price);
			if (!$specificPrice->delete())
				$error = Tools::displayError('An error occurred while deleting the specific price');
		}

		if (isset($error))
			$json = array(
				'status' => 'error',
				'message'=> $error
			);
		else
			$json = array(
				'status' => 'ok',
				'message'=> ''
			);

		return Tools::jsonEncode($json);
	}



	function ajaxProcessDeleteProductAttribute()
	{
		if (!Combination::isFeatureActive())
			return;


		$id_product = (int)Tools::getValue('id_product');
		$id_product_attribute = (int)Tools::getValue('id_product_attribute');
		if ($id_product && Validate::isUnsignedId($id_product) && Validate::isLoadedObject($product = new Product($id_product)))
		{
			$product->deleteAttributeCombination((int)$id_product_attribute);
			$product->checkDefaultAttributes();
			if (!$product->hasAttributes())
			{
								Db::getInstance()->update('product', array(
					'cache_default_attribute' => 0,
				), 'id_product = '.(int)$id_product);

				Db::getInstance()->update('product_shop', array(
					'cache_default_attribute' => 0,
				), 'id_product = '.(int)$id_product);
				
			}
			else
				Product::updateDefaultAttribute($id_product);

			$json = array(
				'status' => 'ok',
				'message'=> Tools::displayError('Attribute deleted')
			);
		}
		else
			$json = array(
				'status' => 'error',
				'message'=> Tools::displayError('Cannot delete attribute')
			);

        return Tools::jsonEncode($json);
	}

	function ajaxProcessDefaultProductAttribute()
	{
		if (!Combination::isFeatureActive())
			return;

		if (Validate::isLoadedObject($product = new Product((int)Tools::getValue('id_product'))))
		{
			$product->deleteDefaultAttributes();
			$product->setDefaultAttribute((int)Tools::getValue('id_product_attribute'));
			$json = array(
				'status' => 'ok',
				'message'=> Tools::displayError('Default change success')
			);
		}
		else
			$json = array(
				'status' => 'error',
				'message'=> Tools::displayError('Cannot make default attribute')
			);
	    
        return Tools::jsonEncode($json);

	}
	
	function ajaxProcessEditProductAttribute()
	{
		$id_product = (int)Tools::getValue('id_product');
		$id_product_attribute = (int)Tools::getValue('id_product_attribute');
		if ($id_product && Validate::isUnsignedId($id_product) && Validate::isLoadedObject($product = new Product((int)$id_product)))
		{
			$combinations = $product->getAttributeCombinationsById($id_product_attribute, Context::getContext()->language->id);
			foreach ($combinations as $key => $combination)
				$combinations[$key]['attributes'][] = array($combination['group_name'], $combination['attribute_name'], $combination['id_attribute']);

            return Tools::jsonEncode($combinations);
		}
	}
	

	function ajaxDeleteVirtualProduct()
	{
		if (!($id_product_download = ProductDownload::getIdFromIdProduct((int)Tools::getValue('id_product'))))
		{
			$json = array(
				'status' => 'error',
				'message'=> Tools::displayError('Cannot retrieve file, please save it first!')
			);
		}
		else
		{
			$product_download = new ProductDownload((int)$id_product_download);
			if (!$product_download->deleteFile((int)$id_product_download))
			{
				$json = array(
					'status' => 'error',
					'message'=> Tools::displayError('Cannot delete file, please save it first!')
				);
			}
			else
			{
				$json = array(
					'status' => 'ok',
					'message'=> Tools::displayError('Success')
				);
			}
		}
        return Tools::jsonEncode($json);
	}
