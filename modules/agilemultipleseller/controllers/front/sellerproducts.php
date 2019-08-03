<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
class AgileMultipleSellerSellerProductsModuleFrontController extends AgileModuleFrontController
{
	public function setMedia()
	{
		parent::setMedia();
		
		$this->registerJavascript('js_tools','/js/tools.js',['position' => 'bottom', 'priority' => 100]);

		$this->registerStylesheet('jquery.ui.theme', '/js/jquery/ui/themes/base/jquery.ui.theme.css', ['media' => 'all', 'priority' => 100]);
		$this->registerStylesheet('jquery.ui.slider', '/js/jquery/ui/themes/base/jquery.ui.slider.css', ['media' => 'all', 'priority' => 100]);
		$this->registerStylesheet('jquery.ui.datepicker', '/js/jquery/ui/themes/base/jquery.ui.datepicker.css', ['media' => 'all', 'priority' => 100]);
		$this->registerStylesheet('jquery.ui.timepicker', '/js/jquery/ui/themes/base/jquery-ui-timepicker-addon.css', ['media' => 'all', 'priority' => 100]);
		$this->registerStylesheet('jquery.fancybox', '/js/jquery/plugins/fancybox/jquery.fancybox.css', ['media' => 'all', 'priority' => 100]);

		$this->registerJavascript('agile_autocomplete','/modules/agilemultipleseller/js/autocomplete/jquery.autocomplete.js',['position' => 'bottom', 'priority' => 100]);		
		$this->registerJavascript('agile_sellerproducts','/modules/agilemultipleseller/js/sellerproducts.js',['position' => 'bottom', 'priority' => 100]);		

		$this->registerJavascript('js_uicore','/js/jquery/ui/jquery.ui.core.min.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('js_uiwidget','/js/jquery/ui/jquery.ui.widget.min.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('js_uimouse','/js/jquery/ui/jquery.ui.mouse.min.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('js_uislider','/js/jquery/ui/jquery.ui.slider.min.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('js_uidatepicker','/js/jquery/ui/jquery.ui.datepicker.min.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('js_fancybox','/js/jquery/plugins/fancybox/jquery.fancybox.js',['position' => 'bottom', 'priority' => 100]);

		$this->registerJavascript('js_idtabs','/js/jquery/plugins/jquery.idTabs.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('agile_sellerpage','/modules/agilemultipleseller/js/sellerpage.js',['position' => 'bottom', 'priority' => 100]);		
	}
	
	
	
	public function init()
    {
        parent::init();
		
		$this->n = (int)Tools::getValue('n');		
		$this->p = (int)Tools::getValue('p');		
		if($this->p <=0) $this->p = 1;
		if($this->n <=0) $this->n =  max(1, (int)Configuration::get('PS_PRODUCTS_PER_PAGE'));

		$this->orderBy = Tools::getValue("orderBy");
		$this->orderWay = Tools::getValue("orderWay");
		
		$action = Tools::getValue('process');
		$id_product = Tools::getValue('id_product');
	    if(isset($action) && isset($id_product))
        {
			if($action == 'delete' || $action =='duplicate') 
			{
				$this->sellerinfo = new SellerInfo(SellerInfo::getIdByCustomerId($this->context->customer->id), $this->context->language->id);
				$targetid_owner_id = (int)AgileSellerManager::getObjectOwnerID('product', $id_product);
				if($this->sellerinfo->id_seller>0 AND $action == 'delete' AND $this->sellerinfo->id_seller == $targetid_owner_id)
				{
					$this->processDelete($id_product);
				}
				elseif ($this->sellerinfo->id_seller>0 AND $action == 'duplicate' AND ($this->sellerinfo->id_seller == $targetid_owner_id OR $targetid_owner_id==0)){
					$this->processDuplicate($id_product);
				}
				else
				{
					$this->errors[] = Tools::displayError('You do not have permission to delete/duplicate this product or the product is not found.');
				}
			} 
			else if($action == 'inactive' || $action=='active') {
								$product = new Product((int)$id_product);
				$product->active = ($action=='active');
				$product->update();

								$this->sellerinfo = new SellerInfo(SellerInfo::getIdByCustomerId($this->context->customer->id), $this->context->language->id);
				AgileSellerManager::assignObjectOwner('product',$id_product, $this->sellerinfo->id_seller);
			}
        }
    }
		
	public function postProcess()
	{
		$export = Tools::getValue("export");
		if(!empty($export))
		{
			header('Content-type: text/csv');
			header('Content-Type: application/force-download; charset=UTF-8');
			header('Cache-Control: no-store, no-cache');
			header('Content-disposition: attachment; filename="products_'.date('Y-m-d_His').'.csv"');

			$products = AgileSellerManager::getProducts($this->sellerinfo->id_seller, $this->context, false, 1, 99999999, $this->orderBy, $this->orderWay, $this->getExtraConditions());
			$data = $this->processExport($products);
			$csv = $this->module->hookGenerateCSV(array('data' => $data));

			die($csv);
		}
		
		$bulkAction = Tools::getValue("bulkAction");
		$bulkActionData = explode(",", Tools::getValue("bulkActionData"));
		if(!empty($bulkAction) && !empty($bulkActionData))
		{
			foreach($bulkActionData as $id_product)
			{
				if($id_product>0)
				{
					$owner_id = (int)AgileSellerManager::getObjectOwnerID('product', $id_product);
					if($this->sellerinfo->id_seller>0 AND $bulkAction == 'delete' AND $this->sellerinfo->id_seller == $owner_id)
					{
						$this->processDelete($id_product);
					}
					else if($this->sellerinfo->id_seller>0 AND ($bulkAction == 'enable' || $bulkAction == 'disable') AND $this->sellerinfo->id_seller == $owner_id)
					{
						$product = new Product((int)$id_product);
						$product->active = ($bulkAction == 'enable');
						$product->update();

												AgileSellerManager::assignObjectOwner('product',$id_product, $this->sellerinfo->id_seller);
					}
					else
					{
						$this->errors[] = Tools::displayError('You do not have permission to delete/duplicate this product or the product is not found.') . 'ID:' . $id_product;
					}
				}
			}
		}
		
	}
	
	private function getExtraConditions()
	{

		$extraCondition = '';
		$filters = array( 
			array('name' =>'filter_id_product', 'type'=> 'number')
			,array('name'=>'filter_name','type'=>'text')
			,array('name'=>'filter_category','type'=>'text')
			,array('name'=>'filter_quantity','type'=>'number')
			,array('name'=>'filter_price','type'=>'float')
			,array('name'=>'filter_active','type'=>'bool')
			,array('name'=>'filter_approved','type'=>'bool')
			,array('name'=>'filter_date_add','type'=>'date')
			);
	
		foreach($filters as $filter)
		{
			$val = Tools::getValue($filter['name']);
			$len = strlen($val);
			
			switch($filter['name'])
			{
				case 'filter_id_product':
					if((int)$val > 0)$extraCondition = $extraCondition . " AND p.id_product = " . (int)$val;
					break;
				case 'filter_name':
					if($len>0)$extraCondition = $extraCondition . " AND pl.name LIKE '%" . $val . "%'";
					break;
				case 'filter_category':					
					if($len>0)$extraCondition = $extraCondition . " AND cl.name LIKE '%" . $val . "%'";
					break;
				case 'filter_quantity':					
					if($len>0)$extraCondition = $extraCondition . " AND sav.quantity =" . (int)$val;
					break;
				case 'filter_price':					
					if($len>0)$extraCondition = $extraCondition . " AND p.price =" . (float)$val;
					break;
				case 'filter_active':					
					if($len>0)$extraCondition = $extraCondition . " AND p.active =" . (int)$val;
					break;
				case 'filter_approved':					
					if($len>0)$extraCondition = $extraCondition . " AND po.approved =" . (int)$val;
					break;
				case 'filter_date_add':
					$from = Tools::getValue($filter['name'] . "_from");
					$to = Tools::getValue($filter['name'] . "_to");
					if(!empty($from))$extraCondition = $extraCondition . " AND p.date_add >='" . $from . "'";
					if(!empty($to))$extraCondition = $extraCondition . " AND p.date_add <='" . $to . "'";
					break;
			}
		} 
		return $extraCondition;		
	}
	
	public function initContent()
	{
		parent::initContent();
        
        $def_id_currency = (int)Configuration::get('PS_CURRENCY_DEFAULT');
				
		$extraCon = $this->getExtraConditions();		
		$products_nb = AgileSellerManager::getProducts($this->sellerinfo->id_seller, $this->context, true, $this->p, $this->n, $this->orderBy, $this->orderWay, $extraCon);

		$this->pagination($products_nb);
		$products = AgileSellerManager::getProducts($this->sellerinfo->id_seller, $this->context, false, $this->p, $this->n, $this->orderBy, $this->orderWay, $extraCon);
		self::$smarty->assign(array(
			'seller_tab_id' => 3
			,'products' => $products
			,'is_apprpved_required' => intval(Configuration::get('AGILE_MS_PRODUCT_APPROVAL'))
			,'def_id_currency' => $def_id_currency
			,'is_legacy_image' => (int)Configuration::get('PS_LEGACY_IMAGES')
			,'n' => $this->n
			,'p' => $this->p
			,'orderBy' => ($this->orderBy == ""? "p.date_add" : $this->orderBy)
			,'orderWay' => ($this->orderWay == ""? "DESC" : $this->orderWay)
			,'msgDelete' => $this->l('Are you sure you want to delete selected product')
			,'msgDuplicate' => $this->l('Are you sure you want to duplicate selected product')
			,'allowCopyMainStoreProduct' => (int)Configuration::get('AGILE_MS_PRODUCT_COPY')
			,'filter_id_product'=> Tools::getValue('filter_id_product')
			,'filter_name'=> Tools::getValue('filter_name')
			,'filter_category'=> Tools::getValue('filter_category')
			,'filter_price'=> Tools::getValue('filter_price')
			,'filter_quantity'=> Tools::getValue('filter_quantity')
			,'filter_date_add_from' => Tools::getValue('filter_date_add_from')
			,'filter_date_add_to' => Tools::getValue('filter_date_add_to')
			,'filter_active'=> Tools::getValue('filter_active')
			,'filter_approved'=> Tools::getValue('filter_approved')
			));

		$this->setTemplate('module:agilemultipleseller/views/templates/front/sellerproducts.tpl');
		
	}
	
	private function processDelete($id_product)
	{
		$product = new Product((int)$id_product);
		$this->beforeDelete($product);
		if(!$product->delete())
			$this->errors[] = Tools::displayError('Error occured during deleting the product.');
		
		$this->afterDelete($product,$product->id);
	}
	
	
	private function processDuplicate($id_product)
	{
		if (Validate::isLoadedObject($product = new Product($id_product)))
		{
			$id_product_old = $product->id;
			if (empty($product->price) && Shop::getContext() == Shop::CONTEXT_GROUP)
			{
				$shops = ShopGroup::getShopsFromGroup(Shop::getContextShopGroupID());
				foreach ($shops as $shop)
					if ($product->isAssociatedToShop($shop['id_shop']))
					{
						$product_price = new Product($id_product_old, false, null, $shop['id_shop']);
						$product->price = $product_price->price;
					}
			}
			unset($product->id);
			unset($product->id_product);
			$product->indexed = 0;
			$product->active = 0;
			if ($product->add()
			&& Category::duplicateProductCategories($id_product_old, $product->id)
			&& ($combination_images = Product::duplicateAttributes($id_product_old, $product->id)) !== false
			&& GroupReduction::duplicateReduction($id_product_old, $product->id)
			&& Product::duplicateAccessories($id_product_old, $product->id)
			&& Product::duplicateFeatures($id_product_old, $product->id)
			&& Product::duplicateSpecificPrices($id_product_old, $product->id)
			&& Pack::duplicate($id_product_old, $product->id)
			&& Product::duplicateCustomizationFields($id_product_old, $product->id)
			&& Product::duplicateTags($id_product_old, $product->id)
			&& Product::duplicateDownload($id_product_old, $product->id))
			{
				AgileSellerManager::assignObjectOwner('product', $product->id, $this->seller->id);
				
				if ($product->hasAttributes())
					Product::updateDefaultAttribute($product->id);

				if (!Tools::getValue('noimage') && !Image::duplicateProductImages($id_product_old, $product->id, $combination_images))
					$this->errors[] = Tools::displayError('An error occurred while copying images.');
				else
				{
					Hook::exec('actionProductAdd', array('id_product' => (int)$product->id, 'product' => $product));
					if (in_array($product->visibility, array('both', 'search')) && Configuration::get('PS_SEARCH_INDEXATION'))
						Search::indexation(false, $product->id);
				}
				
			}
			else
				$this->errors[] = Tools::displayError('An error occurred while creating an object.');
		}
	}
	
	public function processExport($list, $text_delimiter = '"')
	{
				if (ob_get_level() && ob_get_length() > 0) {
			ob_clean();
		}

		$currency = new Currency(Configuration::get('PS_CURRENCY_DEFAULT'));

		$fields = array(
			"id_product" => $this->l("ID")
			,"id_image" => $this->l("Photo")
			,"name" => $this->l("Name")
			,"name_category" => $this->l("Category")
			,"currency" => $this->l("Currency")
			,"price" => $this->l("Price")
			,"price_final" => $this->l("Total Price")
			,"sav_quantity" => $this->l("Quantity")
			,"active" => $this->l("Active")			
			); 
		
		foreach($fields as $field => $header)
		{
			$headers[] = $header;
		}
		
		
		$content = array();
		foreach ($list as $i => $row) {
			$content[$i] = array();
			foreach ($fields as $field => $header) {
				switch($field)
				{
					case "id_image":
						if($row['id_image'] > 0)
							$content[$i][] = $this->context->link->getImageLink($row['name'], $row['id_image'], 'small_default');
						else
							$content[$i][] = "";
						break;
					case "active":
						$content[$i][] = ($row[$field] == 1? 'Yes':'No');
						break;
					case "currency":
						$content[$i][] = $currency->name;
						break;
					default:
						$content[$i][] = $row[$field];
						break;
				}
			}
		}

		return array(
			'export_precontent' => $this->l("Date Range:") . Tools::getValue('date_from') . " - " . Tools::getValue('date_to') ,
			'export_headers' => $headers,
			'export_content' => $content,
			'text_delimiter' => $text_delimiter
			);

	}	

}
