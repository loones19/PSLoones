<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.

include_once(_PS_ROOT_DIR_ .'/modules/aa_loones/classes/aa_feature.php');
class AgileMultipleSellerSellerProductDetailModuleFrontController extends AgileModuleFrontController
{
	protected $product_menus = array();
	protected $max_image_size = null;

	public $auth = true;
	public $ssl = true;
	protected $languages;
	protected $id_language;
	protected $product_menu;
	protected $object;
	
	public function __construct()
	{
		parent::__construct();
		
	
		$this->product_menus[] = array('id'=>1, 'name'=> $this->l('Information'));
		$this->product_menus[] = array('id'=>2, 'name'=> $this->l('Images'));
		$this->product_menus[] = array('id'=>3, 'name'=> $this->l('3. Features'));
	//	$this->product_menus[] = array('id'=>4, 'name'=> $this->l('4. Associations'));
		$this->product_menus[] = array('id'=>5, 'name'=> $this->l('Prices(Discounts)'));
	//	$this->product_menus[] = array('id'=>6, 'name'=> $this->l('Quantity(Stock)'));
	//	$this->product_menus[] = array('id'=>7, 'name'=> $this->l('7. Combinations'));
	//	$this->product_menus[] = array('id'=>8, 'name'=> $this->l('8. Virtual Product'));
		$this->product_menus[] = array('id'=>9, 'name'=> $this->l('Shipping'));
		$this->product_menus[] = array('id'=>10, 'name'=> $this->l('Attachments'));
		if(Module::isInstalled('agileproducttags') && Module::isEnabled('agileproducttags') && Configuration::get('ALLOW_SELLER_ATTACH_TAGS')){
			$this->product_menus[] = array('id' => 11, 'name' => $this->l('11. Product Tags'));
		}
		
        $this->table = 'product';
        $this->identifier = 'id_product';
        $this->className = 'Product';
    }	

	public function setMedia()
	{
		parent::setMedia();
		
		$deflang = new Language(self::$cookie->id_lang);
		$isoTinyMCE = (file_exists(_PS_ROOT_DIR_.'/js/tiny_mce/langs/'.$deflang->iso_code.'.js') ? $deflang->iso_code : 'en');
		Media::addJsDef(array(
			'iso' => $isoTinyMCE,
			'ad' => str_replace("\\", "\\\\", dirname($_SERVER["PHP_SELF"])),
			'id_language_current' => self::$cookie->id_lang,
			'allowCopyMainStoreProduct' => (int)Configuration::get('AGILE_MS_PRODUCT_COPY')			
			));
		
		
		$this->registerJavascript('js_tools','/js/tools.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('js_adminprice','/js/admin/price.js',['position' => 'bottom', 'priority' => 100]);

		$this->registerStylesheet('jquery.ui.theme', '/js/jquery/ui/themes/base/jquery.ui.theme.css', ['media' => 'all', 'priority' => 100]);
		$this->registerStylesheet('jquery.ui.slider', '/js/jquery/ui/themes/base/jquery.ui.slider.css', ['media' => 'all', 'priority' => 100]);
		$this->registerStylesheet('jquery.ui.datepicker', '/js/jquery/ui/themes/base/jquery.ui.datepicker.css', ['media' => 'all', 'priority' => 100]);
		$this->registerStylesheet('jquery.ui.timepicker', '/js/jquery/ui/themes/base/jquery-ui-timepicker-addon.css', ['media' => 'all', 'priority' => 100]);
		$this->registerStylesheet('jquery.fancybox', '/js/jquery/plugins/fancybox/jquery.fancybox.css', ['media' => 'all', 'priority' => 100]);
		$this->registerStylesheet('agile_treeview_categories', '/modules/agilemultipleseller/js/treeview-categories/jquery.treeview-categories.css', ['media' => 'all', 'priority' => 100]);


		$this->registerJavascript('js_jquerytagfy','/js/jquery/plugins/jquery.tagify.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('js_jquerytableend','/js/jquery/plugins/jquery.tablednd.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('js_fancybox','/js/jquery/plugins/fancybox/jquery.fancybox.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('js_typewatch','/js/jquery/plugins/jquery.typewatch.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('js_uicore','/js/jquery/ui/jquery.ui.core.min.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('js_uiwidget','/js/jquery/ui/jquery.ui.widget.min.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('js_uimouse','/js/jquery/ui/jquery.ui.mouse.min.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('js_uislider','/js/jquery/ui/jquery.ui.slider.min.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('js_uidatepicker','/js/jquery/ui/jquery.ui.datepicker.min.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('js_uitimepicker','/js/jquery/plugins/timepicker/jquery-ui-timepicker-addon.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('js_jqueryajaxupload','/js/jquery/plugins/ajaxfileupload/jquery.ajaxfileupload.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('js_jquerygrowl','/js/jquery/plugins/growl/jquery.growl.js',['position' => 'bottom', 'priority' => 100]);

		$this->registerJavascript('agile_tinymce','/modules/agilemultipleseller/js/agile_tiny_mce.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('agile_filemanager','/modules/agilemultipleseller/filemanager/plugin.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('agile_autocomplete','/modules/agilemultipleseller/js/autocomplete/jquery.autocomplete.js',['position' => 'bottom', 'priority' => 100]);		

		$this->registerJavascript('agile_category-tree','/modules/agilemultipleseller/js/front-categories-tree.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('agile_front_products','/modules/agilemultipleseller/js/front-products.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('agile_front_attribute','/modules/agilemultipleseller/js/front-attributes.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('agile_treeview_categoties','/modules/agilemultipleseller/js/treeview-categories/jquery.treeview-categories.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('agile_treeview_categotiessync','/modules/agilemultipleseller/js/treeview-categories/jquery.treeview-categories.async.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('agile_treeview_categotiesedit','/modules/agilemultipleseller/js/treeview-categories/jquery.treeview-categories.edit.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('agile_dropdown','/modules/agilemultipleseller/replica/themes/default/js/dropdown.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('agile_multilanguage','/modules/agilemultipleseller/js/multi-language.js',['position' => 'bottom', 'priority' => 100]);


		$this->registerJavascript('agile_sellerproducts','/modules/agilemultipleseller/js/sellerproducts.js',['position' => 'bottom', 'priority' => 100]);		


		$this->registerJavascript('js_idtabs','/js/jquery/plugins/jquery.idTabs.js',['position' => 'bottom', 'priority' => 100]);
		$this->registerJavascript('agile_sellerpage','/modules/agilemultipleseller/js/sellerpage.js',['position' => 'bottom', 'priority' => 100]);		
	}
	

	
	private function agileLoadProduct($id_product)
	{
		$prod =  new Product($id_product,true);
		$prod->price = (float)Db::getInstance()->getValue('SELECT price FROM ' . _DB_PREFIX_ . 'product WHERE id_product=' . (int)$id_product);
		$prod->unit_price = ($prod->unit_price_ratio != 0  ? $prod->price / $prod->unit_price_ratio : 0);
		if($this->product_menu == 1 && $id_product>0)  		{
			$prod->tags = Tag::getProductTags($id_product);
		}
		return $prod;
	}

	public function init()
	{
		parent::init();
		
		header('Cache-Control: no-cache, must-revalidate');
		header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');

		if(isset($_POST['price']))$_POST['price'] = str_replace(",", ".",$_POST['price']);
		if(isset($_POST['priceTI']))$_POST['priceTI'] = str_replace(",", ".",$_POST['priceTI']);
		if(isset($_POST['wholesale_price']))$_POST['wholesale_price'] = str_replace(",", ".",$_POST['wholesale_price']);
		
		$this->max_image_size = (int)Configuration::get('PS_PRODUCT_PICTURE_MAX_SIZE') / 1000;
		
		$this->languages = Language::getLanguages(false);
		$this->id_object = intval(Tools::getValue('id_product'));
		$this->id_language = intval(Tools::getValue('id_language'));
		$this->product_menu = intval(Tools::getValue('product_menu'));
		if($this->product_menu ==0)$this->product_menu=1;
		if($this->id_language==0)$this->id_language = $this->context->language->id;
		$this->object = $this->agileLoadProduct($this->id_object,true);		if((int)$this->object->id_manufacturer == 0)$this->object->id_manufacturer = $this->sellerinfo->id_manufacturer;
		if((int)$this->object->id_supplier == 0)$this->object->id_supplier = $this->sellerinfo->id_supplier;

		if($this->id_object ==0)$this->object->out_of_stock = StockAvailable::outOfStock(0);
		$hasOwnerShip = $this->hasOwnerShip();
		if(!$hasOwnerShip)
			$this->errors = Tools::displayError('You do not have permission to access/modify this data.');
		
		self::$smarty->assign(array(
			'hasOwnerShip' => $hasOwnerShip
			,'PS_ALLOW_ACCENTED_CHARS_URL' => (int)Configuration::get('PS_ALLOW_ACCENTED_CHARS_URL')			
			,'ps_force_friendly_product' => (int)Configuration::get('PS_FORCE_FRIENDLY_PRODUCT')
			));
	}

			public function postProcess()
	{				
				$_POST['id_seller'] = AgileSellerManager::getLinkedSellerID($this->context->customer->id);
		$this->object->indexed = 0;

		if (Tools::isSubmit('submitProduct'))
		{
			$this->processSaveProduct();
			if(empty($this->errors))self::$smarty->assign('cfmmsg_flag',1);
		}
		else if (Tools::isSubmit('submitAddImage'))
		{
			$this->ProcessAddImage();
			if(empty($this->errors))self::$smarty->assign('cfmmsg_flag',1);
		}
		else if (Tools::isSubmit('submitUpdateLegends'))
		{
			$this->processImageLegends();
			if(empty($this->errors))self::$smarty->assign('cfmmsg_flag',1);
		}
		else if (Tools::isSubmit('submitFeatures'))
		{
			$this->processFeatures();
			if(empty($this->errors))self::$smarty->assign('cfmmsg_flag',1);
		}
		else if (Tools::isSubmit('submitAssociations'))
		{
			$this->updateAccessories();
			$this->object->updateCategories(Tools::getValue('categoryBox'), true);
			$newid = $this->addNewCategory($this->object->id, intval(Tools::getValue('id_category_default')));
			$this->object->id_category_default =( $newid>0 ? $newid : intval(Tools::getValue('id_category_default')));
			$this->object->id_manufacturer = intval(Tools::getValue('id_manufacturer'));
			$this->object->id_supplier = intval(Tools::getValue('id_supplier'));

									if(intval($this->object->id_category_default)<=0)
			{
				$this->errors[] = Tools::displayError('Default category is required');
				return;
			}

			$this->object->save();
			if(empty($this->errors))self::$smarty->assign('cfmmsg_flag',1);
		}
		else if (Tools::isSubmit('submitSpecificPrices'))
		{
			$this->object->wholesale_price = floatval(Tools::getValue('wholesale_price'));
			$this->object->price =  floatval(Tools::getValue('price'));
			if(floatval(Tools::getValue('unit_price'))!=0)
			{
				$this->object->unit_price = (float)Tools::getValue('unit_price');
				$this->object->unit_price_ratio =  $this->object->price / floatval(Tools::getValue('unit_price'));
			}
			
			$this->object->unity =  Tools::getValue('unity');
			$this->object->on_sale =  Tools::getValue('on_sale');
			$this->object->id_tax_rules_group =  Tools::getValue('id_tax_rules_group');
			$this->object->save();
			$this->processPriceAddition();
		//	$this->processSpecificPricePriorities();    //loones

			if(empty($this->errors))self::$smarty->assign('cfmmsg_flag',1);
		}
		else if (Tools::isSubmit('submitQuantities'))
		{
			$this->copyFromPost($this->object, $this->table);
			$this->object->save();
			if(empty($this->errors))self::$smarty->assign('cfmmsg_flag',1);
		}
		else if (Tools::isSubmit('submitCombinations'))
		{
			$this->processCombinations();
			if(empty($this->errors))self::$smarty->assign('cfmmsg_flag',1);
		}
		else if (Tools::isSubmit('submitVirtualProduct'))
		{
			$this->object->is_virtual = Tools::getValue('is_virtual_good') == 'true'?1:0;
			$this->object->save($this->object);
			if($this->object->is_virtual AND intval(Tools::getValue('is_virtual_file')))
				$this->updateDownloadProduct($this->object);
			if(empty($this->errors))self::$smarty->assign('cfmmsg_flag',1);
		}
		else if (Tools::isSubmit('submitShipping'))
		{
			$this->processShipping();
			if(empty($this->errors))self::$smarty->assign('cfmmsg_flag',1);
		}
		else if (Tools::isSubmit('submitAddAttachments'))
		{
			$this->processAddAttachments();
			if(empty($this->errors))self::$smarty->assign('cfmmsg_flag',1);
		}
		else if (Tools::isSubmit('submitAttachments'))
		{
			$this->processAttachments();
			$this->object->cache_has_attachments = (bool)Db::getInstance()->getValue('SELECT id_attachment FROM ' . _DB_PREFIX_ . 'product_attachment WHERE id_product='.(int)$this->object->id);
			if(empty($this->errors))self::$smarty->assign('cfmmsg_flag',1);
		}

		if($this->object->id>0)
		{
			Hook::exec('actionProductUpdate', array('product' => $this->object));
		}

		if(empty($this->errors))
		{
			if (Validate::isLoadedObject($this->object) && $this->object->update())
				if (in_array($this->object->visibility, array('both', 'search')) && Configuration::get('PS_SEARCH_INDEXATION'))
					Search::indexation(false, $this->object->id);		
		}
	}
	
	public function processImageLegends()
	{
		if (Tools::getValue('submitUpdateLegends') == 'update_legends' && Validate::isLoadedObject($product = new Product((int)Tools::getValue('id_product')))) {
			$id_image = (int)Tools::getValue('id_caption');
			$language_ids = Language::getIDs(false);
			foreach ($_POST as $key => $val) {
				if (preg_match('/^legend_([0-9]+)/i', $key, $match)) {
					foreach ($language_ids as $id_lang) {
						if ($val && $id_lang == $match[1]) {
							Db::getInstance()->execute('UPDATE '._DB_PREFIX_.'image_lang SET legend = "'.pSQL($val).'" WHERE '.($id_image ? 'id_image = '.(int)$id_image : 'EXISTS (SELECT 1 FROM '._DB_PREFIX_.'image WHERE '._DB_PREFIX_.'image.id_image = '._DB_PREFIX_.'image_lang.id_image AND id_product = '.(int)$product->id.')').' AND id_lang = '.(int)$id_lang);
						}
					}
				}
			}
		}
	}
	
	public function updateDownloadProduct($product, $edit = 0)
	{
		$is_virtual_file = (int)Tools::getValue('is_virtual_file');

				if (Tools::getValue('is_virtual_good') == 'true')
		{
			if (Tools::getValue('virtual_product_expiration_date') && !Validate::isDate(Tools::getValue('virtual_product_expiration_date') && !empty($is_virtual_file)))
			{
				if (!Tools::getValue('virtual_product_expiration_date'))
				{
					$this->errors[] = Tools::displayError('This field expiration date attribute is required.');
					return false;
				}
			}

						if ($edit == 1)
			{
				$id_product_download = (int)ProductDownload::getIdFromIdProduct((int)$product->id);
				if (!$id_product_download)
					$id_product_download = (int)Tools::getValue('virtual_product_id');
			}
			else
				$id_product_download = Tools::getValue('virtual_product_id');

			$is_shareable = Tools::getValue('virtual_product_is_shareable');
			$virtual_product_name = Tools::getValue('virtual_product_name');
			$virtual_product_filename = Tools::getValue('virtual_product_filename');
			$virtual_product_nb_days = Tools::getValue('virtual_product_nb_days');
			$virtual_product_nb_downloable = Tools::getValue('virtual_product_nb_downloable');
			$virtual_product_expiration_date = Tools::getValue('virtual_product_expiration_date');

			if ($virtual_product_filename)
				$filename = $virtual_product_filename;
			else
				$filename = ProductDownload::getNewFilename();

			$download = new ProductDownload((int)$id_product_download);
			$download->id_product = (int)$product->id;
			$download->display_filename = $virtual_product_name;
			$download->filename = $filename;
			$download->date_add = date('Y-m-d H:i:s');
			$download->date_expiration = $virtual_product_expiration_date ? $virtual_product_expiration_date.' 23:59:59' : '';
			$download->nb_days_accessible = (int)$virtual_product_nb_days;
			$download->nb_downloadable = (int)$virtual_product_nb_downloable;
			$download->active = 1;
			$download->is_shareable = (int)$is_shareable;

			if ($download->save())
				return true;
		}
		else
		{
						if ($edit == 1)
			{
				$id_product_download = (int)ProductDownload::getIdFromIdProduct((int)$product->id);
				if (!$id_product_download)
					$id_product_download = (int)Tools::getValue('virtual_product_id');
			}
			else
				$id_product_download = ProductDownload::getIdFromIdProduct($product->id);

			if (!empty($id_product_download))
			{
				$product_download = new ProductDownload((int)$id_product_download);
				$product_download->date_expiration = date('Y-m-d H:i:s', time() - 1);
				$product_download->active = 0;
				return $product_download->save();
			}
		}
		return false;
	}

	private function addNewCategory($id_product,$id_parent)
	{
		$parent = new Category($id_parent, $this->context->language->id);
		$new_category = Tools::getValue('new_category');
				if(empty($new_category))return;
		$category = new Category();
		$languages = Language::getLanguages(false);
		foreach($languages AS	$lang)
		{
			$category->name[$lang['id_lang']] = $new_category;
			$category->link_rewrite[$lang['id_lang']] = Tools::link_rewrite($new_category);
		}	
		$category->id_parent = $id_parent;
		$category->id_shop_default = Shop::getContextShopID();
		$category->level_depth = $parent->level_depth + 1;
		$category->is_root_category = 0;
		$category->active = 1;
		$category->save();
		
		$sql = 'INSERT INTO ' . _DB_PREFIX_  . 'category_product (id_category,id_product,position) VALUES (' . $category->id . ','. $id_product .',0)';
				Db::getInstance()->Execute($sql);
		$sql = 'REPLACE INTO ' . _DB_PREFIX_  . 'category_shop (id_category,id_shop,position) VALUES (' . $category->id . ','. $category->id_shop_default .',0)';
				Db::getInstance()->Execute($sql);

		return $category->id;
	}

    public function processPriceAddition()
    {
        if (!Tools::getIsset('submitPriceAddition')) {
            return;
        }

        $id_product = Tools::getValue('id_product');
        $id_product_attribute = Tools::getValue('sp_id_product_attribute');
        $id_shop = Tools::getValue('sp_id_shop');
        $id_currency = Tools::getValue('sp_id_currency');
        $id_country = Tools::getValue('sp_id_country');
        $id_group = Tools::getValue('sp_id_group');
        $id_customer = Tools::getValue('sp_id_customer');
        $price = Tools::getValue('leave_bprice') ? '-1' : Tools::getValue('sp_price');
        $from_quantity = Tools::getValue('sp_from_quantity');
        $reduction = (float)(Tools::getValue('sp_reduction'));
        $reduction_tax = Tools::getValue('sp_reduction_tax');
        $reduction_type = !$reduction ? 'amount' : Tools::getValue('sp_reduction_type');
        $reduction_type = $reduction_type == '-' ? 'amount' : $reduction_type;
        $from = Tools::getValue('sp_from');
        if (!$from) {
            $from = '0000-00-00 00:00:00';
        }
        $to = Tools::getValue('sp_to');
        if (!$to) {
            $to = '0000-00-00 00:00:00';
        }

        if (($price == '-1') && ((float)$reduction == '0')) {
            $this->errors[] = $this->trans('No reduction value has been submitted', array(), 'Admin.Catalog.Notification');
        } elseif ($to != '0000-00-00 00:00:00' && strtotime($to) < strtotime($from)) {
            $this->errors[] = $this->trans('Invalid date range', array(), 'Admin.Notifications.Error');
        } elseif ($reduction_type == 'percentage' && ((float)$reduction <= 0 || (float)$reduction > 100)) {
            $this->errors[] = $this->trans('Submitted reduction value (0-100) is out-of-range', array(), 'Admin.Catalog.Notification');
        } elseif ($this->_validateSpecificPrice($id_shop, $id_currency, $id_country, $id_group, $id_customer, $price, $from_quantity, $reduction, $reduction_type, $from, $to, $id_product_attribute)) {
            $specificPrice = new SpecificPrice();
            $specificPrice->id_product = (int)$id_product;
            $specificPrice->id_product_attribute = (int)$id_product_attribute;
            $specificPrice->id_shop = (int)$id_shop;
            $specificPrice->id_currency = (int)($id_currency);
            $specificPrice->id_country = (int)($id_country);
            $specificPrice->id_group = (int)($id_group);
            $specificPrice->id_customer = (int)$id_customer;
            $specificPrice->price = (float)($price);
			$specificPrice->from_quantity = (int)($from_quantity);
			$specificPrice->reduction = (float)($reduction_type == 'percentage' ? $reduction / 100 : $reduction);
			$specificPrice->reduction_tax = $reduction_tax;
			$specificPrice->reduction_type = $reduction_type;
			$specificPrice->from = $from;
			$specificPrice->to = $to;
			if (!$specificPrice->add()) {
				$this->errors[] = $this->trans('An error occurred while updating the specific price.', array(), 'Admin.Catalog.Notification');
			}
		}
	}


    protected function _validateSpecificPrice($id_shop, $id_currency, $id_country, $id_group, $id_customer, $price, $from_quantity, $reduction, $reduction_type, $from, $to, $id_combination = 0)
    {
        if (!Validate::isUnsignedId($id_shop) || !Validate::isUnsignedId($id_currency) || !Validate::isUnsignedId($id_country) || !Validate::isUnsignedId($id_group) || !Validate::isUnsignedId($id_customer)) {
            $this->errors[] = $this->trans('Wrong IDs', array(), 'Admin.Catalog.Notification');
        } elseif ((!isset($price) && !isset($reduction)) || (isset($price) && !Validate::isNegativePrice($price)) || (isset($reduction) && !Validate::isPrice($reduction))) {
            $this->errors[] = $this->trans('Invalid price/discount amount', array(), 'Admin.Catalog.Notification');
        } elseif (!Validate::isUnsignedInt($from_quantity)) {
            $this->errors[] = $this->trans('Invalid quantity', array(), 'Admin.Catalog.Notification');
        } elseif ($reduction && !Validate::isReductionType($reduction_type)) {
            $this->errors[] = $this->trans('Please select a discount type (amount or percentage).', array(), 'Admin.Catalog.Notification');
        } elseif ($from && $to && (!Validate::isDateFormat($from) || !Validate::isDateFormat($to))) {
            $this->errors[] = $this->trans('The from/to date is invalid.', array(), 'Admin.Catalog.Notification');
        } elseif (SpecificPrice::exists((int)$this->object->id, $id_combination, $id_shop, $id_group, $id_country, $id_currency, $id_customer, $from_quantity, $from, $to, false)) {
            $this->errors[] = $this->trans('A specific price already exists for these parameters.', array(), 'Admin.Catalog.Notification');
        } else {
            return true;
        }
        return false;
	}
	
    public function processSpecificPricePriorities()
    {
		if (!($obj = $this->agileLoadProduct($this->id_object, true))) {
            return;
        }
        if (!$priorities = Tools::getValue('specificPricePriority')) {
            $this->errors[] = $this->trans('Please specify priorities.', array(), 'Admin.Catalog.Notification');
        } elseif (Tools::isSubmit('specificPricePriorityToAll')) {
            if (!SpecificPrice::setPriorities($priorities)) {
                $this->errors[] = $this->trans('An error occurred while updating priorities.', array(), 'Admin.Catalog.Notification');
            } else {
                $this->confirmations[] = $this->l('The price rule has successfully updated');
            }
        } elseif (!SpecificPrice::setSpecificPriority((int)$obj->id, $priorities)) {
			$this->errors[] = $this->trans('An error occurred while setting priorities.', array(), 'Admin.Catalog.Notification');
		}
	}

	public function updateAccessories()
	{
		$this->object->deleteAccessories();
		if ($accessories = Tools::getValue('inputAccessories'))
		{
			$accessories_id = array_unique(explode('-', $accessories));
			if (count($accessories_id))
			{
				array_pop($accessories_id);
				$this->object->changeAccessories($accessories_id);
			}
		}
	}

	private function processSaveProduct()
	{
		$languages = Language::getLanguages(true);
		foreach ($languages as $language)
		{
			if(empty($_POST['link_rewrite_' . $language['id_lang']]))
			{
				$_POST['link_rewrite_' . $language['id_lang']] = Tools::link_rewrite($_POST['name_' . $language['id_lang']]);
			}

			if(empty($_POST['name_' . $language['id_lang']]))
			{
				//$this->errors[] = $this->l("Name required in langauge:") . $language['name'];
				$_POST['name_' . $language['id_lang']] = $_POST['name_' . 1];
				$_POST['link_rewrite_' . $language['id_lang']] = Tools::link_rewrite($_POST['name_' . $language['id_lang']]);
			}
			
			if(empty($_POST['link_rewrite_' . $language['id_lang']]))
			{
				$this->errors[] = $this->l("Friendly URL is required in langauge:") . $language['name'];
			}



		}

//LOONES
		if (empty($_POST['id_tipo'])) {
			$this->errors[] = $this->l("Product format is mandatory"); 
		}
		if (!$_POST['id_category_default']) {
			$this->errors[] = $this->l("SubCategory and Category are mandatory"); 
		}

		
		//antes de validar le ponemos el precio(price) calculado con la comisión


		if (($wholesale_price = Tools::getValue('wholesale_price')) == false) {

			$this->errors[] = Tools::displayError('Price is required');
			return;

		}
		$comision= Configuration::get('LOONES_COMISION');
		$precio=$wholesale_price + (($wholesale_price*$comision*1.21)/100);

		$_POST['price']=$precio;
		$_POST['show_price']=1;

//LOONES FIN		

		$this->copyFromPost($this->object, $this->table);
		
		$this->object->online_only = (int)Tools::getValue("online_only");
		$this->object->available_for_order = (int)Tools::getValue("available_for_order");
		$this->object->show_price = (int)Tools::getValue("show_price");
		if($this->object->available_for_order)$this->object->show_price = 1;
		
		$limit = (int)Configuration::get('PS_PRODUCT_SHORT_DESC_LIMIT');
		if ($limit <= 0)$limit = 800;
		if(strlen($this->object->description_short[$this->id_language]) > $limit)
		{
			$this->errors[] = Tools::displayError('Short description is too long');
			return;
		}
		

		$this->validateRules();

		if (count($this->errors) > 0)return;
		if($this->id_object<=0)
		{
			$this->beforeAdd($this->object);
			if (method_exists($this->object, 'add') && !$this->object->add())
			{
				$this->errors[] = Tools::displayError('An error occurred while creating object.').
					' <b>'.$this->table.' ('.Db::getInstance()->getMsgError().')</b>';
				return;
			}
			AgileSellerManager::assignObjectOwner('product',$this->object->id, $_POST['id_seller']);
			$this->afterAdd($this->object);
			$this->updateAssoShop($this->object->id);
			StockAvailable::setQuantity($this->object->id, 0, (int)Tools::getValue('quantity'));
			$this->updateDefaultCategory($this->object->id_category_default,$this->object->id);



//LOONES   
			//asociamosa todas las categorias padre hasta la 2
			$objCat= new Category($this->object->id_category_default);
			$cats_loones= $objCat->getParentsCategories();
			$cats=array();
			foreach ($cats_loones as $cat_loones) {
				if ($cat_loones['id_category']!=$this->object->id_category_default)
					array_push($cats,$cat_loones['id_category']);
			}
			
			$this->object->updateCategories($cats);
			
			//asignamos el tipo al objeto
			$this->object->id_tipo=$_POST['id_tipo'];


//LOONES FIN
			


			Hook::exec('actionProductAdd', array('product' => $this->object));

			$languages = Language::getLanguages(false);
			$this->updateTags($languages, $this->object);

			$this->id_object = $this->object->id;
						$this->object = $this->agileLoadProduct($this->id_object, true);
		}
		else
		{
			if (method_exists($this->object, 'update') && !$this->object->update())
			{
				$this->errors[] = Tools::displayError('An error occurred while updating object.').
					' <b>'.$this->table.' ('.Db::getInstance()->getMsgError().')</b>';
				return;
			}
			StockAvailable::setQuantity($this->object->id, 0, (int)Tools::getValue('quantity'));
			$this->updateDefaultCategory($this->object->id_category_default,$this->object->id);
//LOONES si asigna diferente categoría ojo porque hay que eliminar las madres y reasignar, y elimiar categorías no asignadas
			$objCat= new Category($this->object->id_category_default);
			$cats_loones= $objCat->getParentsCategories();
			$cats=array();
			foreach ($cats_loones as $cat_loones) {
				if ($cat_loones['id_category']!=$this->object->id_category_default)
					array_push($cats,$cat_loones['id_category']);
			}
			$this->object->deleteCategories();
			$this->object->updateCategories($cats);

			
			
// LOONES FIN

			Hook::exec('actionProductUpdate', array('product' => $this->object));

			$languages = Language::getLanguages(false);
			$this->updateTags($languages, $this->object);

			$this->object = $this->agileLoadProduct($this->id_object, true);

			$this->afterUpdate($this->object);
		}


		if($this->object->id_supplier >0)$this->object->addSupplierReference($this->object->id_supplier,0);
		
	}
	
	
	public function updateTags($languages, $product)
	{
		$tag_success = true;
				if (!Tag::deleteTagsForProduct((int)$product->id))
			$this->errors[] = Tools::displayError('An error occurred while attempting to delete previous tags.');
				foreach ($languages as $language)
			if ($value = Tools::getValue('tags_'.$language['id_lang']))
				$tag_success &= Tag::addTags($language['id_lang'], (int)$product->id, $value);
		if (!$tag_success)
			$this->errors[] = Tools::displayError('An error occurred while adding tags.');
		return $tag_success;
	}
	
	private function updateDefaultCategory($id_category_default, $id_product)
	{
		$sql = 'REPLACE INTO `'._DB_PREFIX_.'category_product` (id_category,id_product,position) VALUES(' . intval($id_category_default) . ',' . intval($id_product) . ',0)';            
		Db::getInstance()->Execute($sql);
	}

	private function ProcessAddImage()
	{
		$allowedExtensions = array('jpeg', 'gif', 'png', 'jpg');
				$uploader = new FileUploader($allowedExtensions, (int)Configuration::get('PS_PRODUCT_PICTURE_MAX_SIZE'));
		$_GET['id_product'] = Tools::getValue('id_product');
		$result = $uploader->handleUpload();
		if (isset($result['success']))
		{
			$obj = new Image((int)$result['success']['id_image']);
		
			$languages = Language::getLanguages(false);
			foreach ($languages as $language)
			{
				$obj->legend[(int)$language['id_lang']] = Tools::getValue('legend_' . (int)$language['id_lang']);
			}
				
			$obj->save();
						$shops = Shop::getContextListShopID();
			$obj->associateTo($shops);
			$json_shops = array();
			foreach ($shops as $id_shop)
				$json_shops[$id_shop] = true;

			$json = array(
				'name' => $result['success']['name'],
				'status' => 'ok',
				'id'=>$obj->id,
				'path' => $obj->getExistingImgPath(),
				'position' => $obj->position,
				'cover' => $obj->cover,
				'shops' => $json_shops,
				);
			@unlink(_PS_TMP_IMG_DIR_.'product_'.(int)$obj->id_product.'.jpg');
			@unlink(_PS_TMP_IMG_DIR_.'product_mini_'.(int)$obj->id_product.'.jpg');
								}
		else
		{
			$this->errors[] = $result['error'];
								}
	}


	private function hasOwnerShip()
	{
		if($this->id_object>0)
		{
			if(!Validate::isLoadedObject($this->object))return false;
			else
			{
				$id_product_seller = AgileSellerManager::getObjectOwnerID('product',$this->object->id);
				$id_currentuser_linkedseller = AgileSellerManager::getLinkedSellerID($this->context->customer->id);
				if($id_product_seller != $id_currentuser_linkedseller AND $id_currentuser_linkedseller>0)return false;
			}        
		}
		return true;
	}

	public function displayAjax()
	{
		$this->display();
	}

	public function initContent()
	{
		parent::initContent();

		$this->initContentCommon();

		if($this->product_menu == 1)$this->initiContentForInformation();        
		if($this->product_menu == 2)$this->initiContentForImages();
		if($this->product_menu == 3)$this->initContentForFeatures();
		if($this->product_menu == 4)$this->initContentForAssociations();
		if($this->product_menu == 5)$this->initContentForPrices();
		if($this->product_menu == 6)$this->initContentForQuantities();
		if($this->product_menu == 7)$this->initContentForCombinations();
		if($this->product_menu == 8)$this->initContentVirtualProduct();
		if($this->product_menu == 9)$this->initContentForShipping();
		if($this->product_menu == 10)$this->initContentForAttachments();
		if($this->product_menu == 11)$this->initContentForProductTags();
		
		$this->sellerinfo = new SellerInfo(SellerInfo::getIdByCustomerId($this->context->customer->id), $this->context->language->id);
		$pay_options_link = '';
		if(Module::isInstalled('agilesellerlistoptions'))
		{
			include_once(_PS_ROOT_DIR_ . '/modules/agilesellerlistoptions/agilesellerlistoptions.php');
			$aslo_module = new AgileSellerListOptions();
			$pay_options_link = $aslo_module->getPayOptionLink($this->sellerinfo->id_seller);
			self::$smarty->assign(array(
				'pay_options_link' =>$pay_options_link
				));
		}
		self::$smarty->assign('all_languages', Language::getLanguages(false));
		self::$smarty->assign('errors', $this->errors);
		
		$this->setTemplate('module:agilemultipleseller/views/templates/front/sellerproductdetail.tpl');
	}
	
	private function initContentForProductTags(){
		$configuration = Configuration::get('ALLOW_SELLER_ATTACH_TAGS');
		$all_tags = array();
		if(Module::isInstalled('agileproducttags') && Module::isEnabled('agileproducttags') && $configuration){
			require_once(_PS_MODULE_DIR_ . 'agileproducttags' . DS . 'models' . DS . 'AgileTag.php');
			$all_tags = AgileTag::getTagsByProductId((int)$this->context->language->id, (int)Tools::getValue('id_product'));
		}
		$this->context->smarty->assign(array(
			'tags' => $all_tags,
			'id_product' => (int)Tools::getValue('id_product'),
			'configuration' => $configuration,
			'site_url' => _PS_BASE_URL_ . __PS_BASE_URI__
			));

	}
		
	public function initContentForQuantities()
	{
		if ($this->object->id)
		{
						$attributes = $this->object->getAttributesResume($this->context->language->id);
			if (empty($attributes))
				$attributes[] = array(
					'id_product_attribute' => 0,
					'attribute_designation' => ''
					);

						$available_quantity = array();
			$product_designation = array();

			foreach ($attributes as $attribute)
			{
								$available_quantity[$attribute['id_product_attribute']] = StockAvailable::getQuantityAvailableByProduct((int)$this->object->id,
					$attribute['id_product_attribute']);
								$product_designation[$attribute['id_product_attribute']] = rtrim(
					$this->object->name[$this->context->language->id].' - '.$attribute['attribute_designation'],
					' - '
					);
			}

			$show_quantities = true;
			$shop_context = Shop::getContext();
			$group_shop = $this->context->shop->getGroup();

						if ($shop_context == Shop::CONTEXT_ALL)
				$show_quantities = false;
						elseif ($shop_context == Shop::CONTEXT_GROUP)
			{
								if (!$group_shop->share_stock)
					$show_quantities = false;
			}
						else
			{
								if ($group_shop->share_stock)
					$show_quantities = false;
			}

			self::$smarty->assign('ps_stock_management', Configuration::get('PS_STOCK_MANAGEMENT'));
			self::$smarty->assign('has_attribute', $this->object->hasAttributes());
						if (Combination::isFeatureActive())
				self::$smarty->assign('countAttributes', (int)Db::getInstance()->getValue('SELECT COUNT(id_product) FROM '._DB_PREFIX_.'product_attribute WHERE id_product = '.(int)$this->object->id));

						$advanced_stock_management_warning = false;
			if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT') && $this->object->advanced_stock_management)
			{
				$p_attributes = Product::getProductAttributesIds($this->object->id);
				$warehouses = array();

				if (!$p_attributes)
					$warehouses[] = Warehouse::getProductWarehouseList($this->object->id, 0);

				foreach ($p_attributes as $p_attribute)
				{
					$ws = Warehouse::getProductWarehouseList($this->object->id, $p_attribute['id_product_attribute']);
					if ($ws)
						$warehouses[] = $ws;
				}
				$warehouses = array_unique($warehouses);

				if (empty($warehouses))
					$advanced_stock_management_warning = true;
			}
			if ($advanced_stock_management_warning)
			{
				$this->displayWarning($this->getMessage('If you wish to use the advanced stock management, you have to:'));
				$this->displayWarning('- '.$this->getMessage('associate your products with warehouses'));
				$this->displayWarning('- '.$this->getMessage('associate your warehouses with carriers'));
				$this->displayWarning('- '.$this->getMessage('associate your warehouses with the appropriate shops'));
			}

			$pack_quantity = null;
						if (Pack::isPack($this->object->id))
			{
				$items = Pack::getItems((int)$this->object->id, Configuration::get('PS_LANG_DEFAULT'));

								$pack_quantities = array();
				foreach ($items as $item)
				{
					if (!$item->isAvailableWhenOutOfStock((int)$item->out_of_stock))
					{
						$pack_id_product_attribute = Product::getDefaultAttribute($item->id, 1);
						$pack_quantities[] = Product::getQuantity($item->id, $pack_id_product_attribute) / ($item->pack_quantity !== 0 ? $item->pack_quantity : 1);
					}
				}

								$pack_quantity = $pack_quantities[0];
				foreach ($pack_quantities as $value)
				{
					if ($pack_quantity > $value)
						$pack_quantity = $value;
				}

				if (!Warehouse::getPackWarehouses((int)$this->object->id))
					$this->displayWarning($this->getMessage('You must have a common warehouse between this pack and its product.'));
			}

			$lang = new Language($this->id_language);
			self::$smarty->assign('iso_code', $lang->iso_code ? $lang->iso_code  :'en');

			self::$smarty->assign(array(
				'attributes' => $attributes,
				'available_quantity' => $available_quantity,
				'pack_quantity' => $pack_quantity,
				'stock_management_active' => Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT'),
				'product_designation' => $product_designation,
				'show_quantities' => $show_quantities,
				'order_out_of_stock' => Configuration::get('PS_ORDER_OUT_OF_STOCK')
				));
		}
		else
			$this->displayWarning($this->getMessage('You must save this product before managing quantities.'));

	}
	
	
	private function initContentForPrices()
	{
		if ($this->object->id)
		{
			$shops = Shop::getShops();
			$countries = Country::getCountries($this->context->language->id);
			$groups = Group::getGroups($this->context->language->id);
			$currencies = Currency::getCurrencies();
			$attributes = $this->object->getAttributesGroups((int)$this->context->language->id);
			$combinations = array();
			foreach ($attributes as $attribute)
			{
				$combinations[$attribute['id_product_attribute']]['id_product_attribute'] = $attribute['id_product_attribute'];
				if (!isset($combinations[$attribute['id_product_attribute']]['attributes']))
					$combinations[$attribute['id_product_attribute']]['attributes'] = '';
				$combinations[$attribute['id_product_attribute']]['attributes'] .= $attribute['attribute_name'].' - ';

				$combinations[$attribute['id_product_attribute']]['price'] = Tools::displayPrice(
					Tools::convertPrice(
							Product::getPriceStatic((int)$this->object->id, false, $attribute['id_product_attribute']),
							new Currency((int)Configuration::get('PS_CURRENCY_DEFAULT'))
							), new Currency((int)Configuration::get('PS_CURRENCY_DEFAULT'))
						);
			}
			foreach ($combinations as &$combination)
				$combination['attributes'] = rtrim($combination['attributes'], ' - ');
			self::$smarty->assign('specificPriceModificationForm', $this->_displaySpecificPriceModificationForm(
				new Currency((int)Configuration::get('PS_CURRENCY_DEFAULT')), $shops, $currencies, $countries, $groups)
					);

			self::$smarty->assign(array(
				'shops' => $shops,
				'currencies' => $currencies,
				'countries' => $countries,
				'groups' => $groups,
				'combinations' => $combinations,
				'multi_shop' => Shop::isFeatureActive(),
				'link' => new Link()
				));
		}
		else
			$this->displayWarning($this->getMessage('You must save this product before adding specific prices'));

		$address = new Address();
		$address->id_country = (int)$this->context->country->id;

		$tax_rules_groups = TaxRulesGroup::getTaxRulesGroups(true);

		$tax_rates = array(
			0 => array (
					'id_tax_rules_group' => 0,
					'rates' => array(0),
					'computation_method' => 0
					)
				);

		foreach ($tax_rules_groups as $tax_rules_group)
		{
			$id_tax_rules_group = (int)$tax_rules_group['id_tax_rules_group'];
			$tax_calculator = TaxManagerFactory::getManager($address, $id_tax_rules_group)->getTaxCalculator();
			$tax_rates[$id_tax_rules_group] = array(
				'id_tax_rules_group' => $id_tax_rules_group,
				'rates' => array(),
				'computation_method' => (int)$tax_calculator->computation_method
				);

			if (isset($tax_calculator->taxes) && count($tax_calculator->taxes))
			{
				foreach ($tax_calculator->taxes as $tax)
				{
					$tax_rates[$id_tax_rules_group]['rates'][] = (float)$tax->rate;
				}
			}
			else
			{
				$tax_rates[$id_tax_rules_group]['rates'][] = 0;
			}
		}
		
	
		$lang = new Language($this->id_language);
		self::$smarty->assign('iso_code', $lang->iso_code);
		self::$smarty->assign('ps_use_ecotax', Configuration::get('PS_USE_ECOTAX'));
		self::$smarty->assign('ecotax_tax_excl', $this->object->ecotax);
		self::$smarty->assign('currency', new Currency((int)Configuration::get('PS_CURRENCY_DEFAULT')));
		self::$smarty->assign('tax_rules_groups', $tax_rules_groups);
		self::$smarty->assign('taxesRatesByGroup', $tax_rates);
		self::$smarty->assign('ecotaxTaxRate', Tax::getProductEcotaxRate());
		self::$smarty->assign('tax_exclude_taxe_option', Tax::excludeTaxeOption());
		if ($this->object->unit_price_ratio != 0)
			self::$smarty->assign('unit_price', Tools::ps_round($this->object->price / $this->object->unit_price_ratio, 2));
		else
			self::$smarty->assign('unit_price', 0);
		self::$smarty->assign('ps_tax', Configuration::get('PS_TAX'));

		self::$smarty->assign('country_display_tax_label', $this->context->country->display_tax_label);
		self::$smarty->assign(array(
			'currency', new Currency((int)Configuration::get('PS_CURRENCY_DEFAULT'))
			));
	}
	
	
	

	
	private function initContentCommon()
	{
		$is_list_limited = AgileSellerManager::limited_by_membership($this->sellerinfo->id_seller);
		if($this->id_object ==0 AND $this->sellerinfo->id_seller>0 AND $is_list_limited)
		{
			$this->errors[] = Tools::displayError('You have not purchased membership yet or you have registered products more than limit allowed by your membership.');
		}
		//LOONES
		if ($this->object->wholesale_price==0){
			$wholesale_price=$this->object->price;
			$this->object->wholesale_price=$this->object->price;

		}

		//LOONES FIN
		
		self::$smarty->assign(array(
			'seller_tab_id' => 3
			,'is_list_limited' => $is_list_limited
			,'product_menus' => $this->product_menus
			,'product_menu' => $this->product_menu
			,'id_language' => $this->id_language
			,'id_product' => $this->id_object
			,'id_category_default' => $this->object->id_category_default
			,'product' => $this->object
			,'id_language_current' => self::$cookie->id_lang
			,'product_type'=> (int)Tools::getValue('type_product', $this->object->getType())
			));
	}
	
	private function initiContentForInformation()
	{
		$language = new Language($this->id_language);
		$isoTinyMCE = (file_exists(_PS_ROOT_DIR_.'/js/tiny_mce/langs/'.$language->iso_code.'.js') ? $language->iso_code : 'en');
		$ad = str_replace("\\", "\\\\", dirname($_SERVER["PHP_SELF"]));		$this->sellerinfo = new SellerInfo(SellerInfo::getIdByCustomerId($this->context->customer->id), $this->context->language->id);

		$categories = array();
		$category_nbr = (int)Db::getInstance()->getValue('SELECT COUNT(*) AS cnt FROM ' . _DB_PREFIX_ . 'category');
		if($category_nbr <= 1000)
		{
			$sql = 'SELECT c.id_category, c.id_parent, cl.name 
			FROM ' . _DB_PREFIX_ . 'category c 
				INNER JOIN ' . _DB_PREFIX_ . 'category_owner co ON (c.id_category=co.id_category AND (IFNULL(co.id_owner,0)=0 OR IFNULL(co.id_owner,0)='. intval($this->sellerinfo->id_seller) . '))
				LEFT JOIN ' . _DB_PREFIX_ . 'category_lang cl ON (c.id_category=cl.id_category AND cl.id_lang=' . $this->context->language->id. ' AND cl.id_shop=' . $this->context->shop->id . ')
				WHERE c.id_category > 1
					AND active = 1
				';

			if((int)Configuration::get('AGILE_MS_ALLOW_REGISTER_ATHOME') != 1)
				$sql .= ' AND c.id_category != 2';
	
			$specialcids = AgileMultipleSeller::getSpecialCatrgoryIds();
			if(!empty($specialcids))
				$sql .= ' AND c.id_category NOT IN (' . $specialcids . ')';

			$caterows = Db::getInstance()->ExecuteS($sql);
			//LOONES categorias solo mostramos finales
			$categoriesParent = AgileHelper::getSortedParentCategoryLoones($caterows);
			$categories = AgileHelper::getSortedFullnameCategoryLoones($caterows);
			$e="stop";
			//LOONES FIN
		}
		
		$HOOK_PRODYCT_LIST_OPTIONS = '';
		if(Module::isInstalled('agilesellerlistoptions'))
		{
			include_once(_PS_ROOT_DIR_ . '/modules/agilesellerlistoptions/agilesellerlistoptions.php');
			$aslo_module = new AgileSellerListOptions();
			$HOOK_PRODYCT_LIST_OPTIONS = $aslo_module->hookAgileAdminProductsFormTop(array('for_front'=>1,'id_product'=>$this->object->id), true, true);
		}
		



		self::$smarty->assign(array(
			'ad' => $ad,
			'isoTinyMCE' => $isoTinyMCE,
			'theme_css_dir' => _THEME_CSS_DIR_,
			'ajaxurl' => _MODULE_DIR_,
			'suppliers' => Supplier::getSuppliers(),
			'manufacturers' => Manufacturer::getManufacturers(),
			'currency' => new Currency((int)Configuration::get('PS_CURRENCY_DEFAULT')),
			'ps_ssl_enabled' => Configuration::get('PS_SSL_ENABLED'),
			'is_pack' => ($this->object->id && Pack::isPack($this->object->id)) || Tools::getValue('ppack') || Tools::getValue('type_product') == Product::PTYPE_PACK,
			'categories' => $categories,
			'categoriesParent' => $categoriesParent,
			'order_out_of_stock' => Configuration::get('PS_ORDER_OUT_OF_STOCK'),
			'bullet_common_field' => '',
			'is_agilesellerlistoptions_installed' => Module::isInstalled('agilesellerlistoptions'),
			'HOOK_PRODYCT_LIST_OPTIONS' => $HOOK_PRODYCT_LIST_OPTIONS
			));
		
		
	}

	private function initContentForFeatures()
	{
		$features = Feature::getFeatures($this->id_language);
		//LOONES seleccionamos solo las categorias asociadas al producto
			
		foreach ($features as $k => $tab_features){
			if (!aaFeature::isValidated($tab_features['id_feature'],$this->object->id_category_default)){
				unset($features[$k]);
			}

		}



		//LOONES FIN

		foreach ($features as $k => $tab_features)
		{
			$features[$k]['current_item'] = false;
			$features[$k]['val'] = array();

			$custom = true;
			foreach ($this->object->getFeatures() as $tab_products)
				if ($tab_products['id_feature'] == $tab_features['id_feature'])
					$features[$k]['current_item'] = $tab_products['id_feature_value'];

			$features[$k]['featureValues'] = FeatureValue::getFeatureValuesWithLang($this->id_language, (int)$tab_features['id_feature']);
			if (count($features[$k]['featureValues']))
				foreach ($features[$k]['featureValues'] as $value)
					if ($features[$k]['current_item'] == $value['id_feature_value'])
						$custom = false;

			if ($custom)
				$features[$k]['val'] = FeatureValue::getFeatureValueLang($features[$k]['current_item']);
		}
		
		$available_features_all = '';
		foreach($features as $available_feature)
		{
						$available_features_all = $available_features_all.'custom_'.$available_feature["id_feature"].'&curren;';
		}
		
		self::$smarty->assign(array(
			'available_features_all'=> $available_features_all
			,'available_features' => $features
			)); 
	}
	
	
	private function initiContentForImages()
	{
		$languages = Language::getLanguages(false);

				$shops = Shop::getShops();

		$count_images = Db::getInstance()->getValue('
		    SELECT COUNT(id_product)
		    FROM '._DB_PREFIX_.'image
		    WHERE id_product = '.(int)$this->object->id
			);
		
		$images = Image::getImages($this->id_language, $this->object->id);
		foreach ($images as $k => $image)
			$images[$k] = new Image($image['id_image']);

		$image_number_limit = (int)Configuration::get('AGILE_MS_PRODUCT_IMAGE_NUMBER');
		if(Module::isInstalled('agilemembership') && intval(Configuration::get('AGILE_MEMBERSHIP_SELLER_INTE'))>0)
		{
			include_once(_PS_ROOT_DIR_  . "/modules/agilemembership/MembershipType.php");
			if(method_exists('MembershipType','product_images_limit'))
			{
				$img_limit = MembershipType::product_images_limit($this->context->customer->id);
				if($img_limit>0)$image_number_limit = $img_limit;
			}
		}

		self::$smarty->assign(array(
			'table' => 'product',
			'languages' => $languages,
			'images'=> $images,
			'countImages'=> $count_images,
			'shops' => $shops,
			'max_image_size' => $this->max_image_size,
			'image_number_limit' => $image_number_limit,
			'imagesTypes' => ImageType::getImagesTypes('products')
			)); 
	}

	public function initContentForAssociations()
	{
				$root = Category::getRootCategory();
		$sql = 'SELECT id_category_default FROM ' . _DB_PREFIX_ . 'product WHERE id_product=' . intval($this->object->id);
		$default_category = intval(Db::getInstance()->getValue($sql));

		
		if (!$this->object->id)
			$selected_cat = Category::getCategoryInformations(Tools::getValue('categoryBox', array($default_category)), $this->id_language);
		else
		{
			if (Tools::isSubmit('categoryBox'))
				$selected_cat = Category::getCategoryInformations(Tools::getValue('categoryBox', array($default_category)), $this->id_language);
			else
				$selected_cat = Product::getProductCategoriesFull($this->object->id, $this->id_language);
		}

		if(!array_key_exists($default_category,$selected_cat))
		{
			$sql = 'SELECT id_category, name, link_rewrite, id_lang FROM ' . _DB_PREFIX_ . 'category_lang WHERE id_category=' . $default_category. ' AND id_lang=' . intval($this->id_language);
			$selected_cat[$default_category]  = Db::getInstance()->getRow($sql);
		}

				self::$smarty->assign('feature_shop_active', Shop::isFeatureActive());
		$helper = new HelperForm();
		if ($this->object && $this->object->id)
			$helper->id = $this->object->id;
		else
			$helper->id = null;
		$helper->table = 'product';
		$helper->identifier = 'id_product';

		self::$smarty->assign('displayAssoShop', $helper->renderAssoShop());

				$accessories = Product::getAccessoriesLight($this->id_language, $this->object->id);

		if ($post_accessories = Tools::getValue('inputAccessories'))
		{
			$post_accessories_tab = explode('-', Tools::getValue('inputAccessories'));
			foreach ($post_accessories_tab as $accessory_id)
				if (!$this->haveThisAccessory($accessory_id, $accessories) && $accessory = Product::getAccessoryById($accessory_id))
					$accessories[] = $accessory;
		}
		self::$smarty->assign('accessories', $accessories);

		$tab_root = array('id_category' => $root->id, 'name' => $root->name);
		$helper = new Helper();
		
		$disabledCategories =  AgileMultipleSeller::getSpecialCatrgoryIdsArray();
		$category_tree = $helper->renderCategoryTree($tab_root, $selected_cat, 'categoryBox', false, true, $disabledCategories);
		$category_tree = str_replace('<script type="text/javascript">searchCategory();</script>','',$category_tree);
		
		self::$smarty->assign(array('default_category' => $default_category,
			'suppliers' => Supplier::getSuppliers(),
			'manufacturers' => Manufacturer::getManufacturers(),
			'selected_cat_ids' => implode(',', array_keys($selected_cat)),
			'selected_cat' => $selected_cat,
			'id_category_default' => $this->object->getDefaultCategory(),
			'category_tree' => $category_tree,
			'product' => $this->object,
			'agile_ms_edit_category' => Configuration::get('AGILE_MS_EDIT_CATEGORY'),
			'link' => $this->context->link,
			'id_first_available_category' => AgileHelper::GetFirstAvailableCategory(),
			'allow_register_athome' => (int)Configuration::get('AGILE_MS_ALLOW_REGISTER_ATHOME'),
			'ajx_category_url' => AgileMultipleSeller::get_agile_ajax_categories_url()
			));

	}


	public function initContentForCombinations()
	{
		$product = $this->object;
		
		if($product->is_virtual)return;
		
		if (!Combination::isFeatureActive())
		{
			$this->displayWarning($this->getMessage('This feature has been disabled, you can activate this feature at this page:').$this->getMessage('link to Performances'));
			return;
		}

		$address = new Address();
		$address->id_country = (int)$this->context->country->id;

		$tax_rules_groups = TaxRulesGroup::getTaxRulesGroups(true);
		$tax_rates = array(
			0 => array (
					'id_tax_rules_group' => 0,
					'rates' => array(0),
					'computation_method' => 0
					)
				);

		foreach ($tax_rules_groups as $tax_rules_group)
		{
			$id_tax_rules_group = (int)$tax_rules_group['id_tax_rules_group'];
			$tax_calculator = TaxManagerFactory::getManager($address, $id_tax_rules_group)->getTaxCalculator();
			$tax_rates[$id_tax_rules_group] = array(
				'id_tax_rules_group' => $id_tax_rules_group,
				'rates' => array(),
				'computation_method' => (int)$tax_calculator->computation_method
				);

			if (isset($tax_calculator->taxes) && count($tax_calculator->taxes))
			{
				foreach ($tax_calculator->taxes as $tax)
				{
					$tax_rates[$id_tax_rules_group]['rates'][] = (float)$tax->rate;
				}
			}
			else
			{
				$tax_rates[$id_tax_rules_group]['rates'][] = 0;
			}
		}


		if (Validate::isLoadedObject($product))
		{
			self::$smarty->assign('country_display_tax_label', $this->context->country->display_tax_label);
			self::$smarty->assign('tax_exclude_taxe_option', Tax::excludeTaxeOption());
			self::$smarty->assign('id_tax_rules_group', $product->id_tax_rules_group);
			self::$smarty->assign('tax_rules_groups', $tax_rules_groups);
			self::$smarty->assign('taxesRatesByGroup', $tax_rates);
			self::$smarty->assign('ecotaxTaxRate', Tax::getProductEcotaxRate());
			$lang = new Language($this->id_language);
			self::$smarty->assign('iso_code', $lang->iso_code);
			self::$smarty->assign('combinationImagesJs', $this->getCombinationImagesJs());
			

			if ($product->is_virtual)
			{
				self::$smarty->assign('product', $product);
				$this->displayWarning($this->getMessage('A virtual product cannot have combinations.'));
			}
			else
			{
				$finalPrice = Product::getPriceStatic($product->id, true, null, 6, null, false, true, 1, true);
				self::$smarty->assign('finalPrice', $finalPrice);
				
				$attribute_js = array();
				$attributes = Attribute::getAttributes($this->context->language->id, true);
				foreach ($attributes as $k => $attribute)
					$attribute_js[$attribute['id_attribute_group']][$attribute['id_attribute']] = $attribute['name'];
				$currency = new Currency((int)Configuration::get('PS_CURRENCY_DEFAULT'));
				self::$smarty->assign('attributeJs', $attribute_js);
				self::$smarty->assign('attributes_groups', AttributeGroup::getAttributesGroups($this->context->language->id));

				self::$smarty->assign('currency', $currency);

				$images = Image::getImages($this->context->language->id, $product->id);

				self::$smarty->assign('tax_exclude_option', Tax::excludeTaxeOption());
				self::$smarty->assign('ps_weight_unit', Configuration::get('PS_WEIGHT_UNIT'));

				self::$smarty->assign('ps_use_ecotax', Configuration::get('PS_USE_ECOTAX'));
				self::$smarty->assign('field_value_unity', $this->getFieldValue($product, 'unity'));

				self::$smarty->assign('reasons', $reasons = StockMvtReason::getStockMvtReasons($this->context->language->id));
				self::$smarty->assign('ps_stock_mvt_reason_default', $ps_stock_mvt_reason_default = Configuration::get('PS_STOCK_MVT_REASON_DEFAULT'));
				self::$smarty->assign('minimal_quantity', $this->getFieldValue($product, 'minimal_quantity') ? $this->getFieldValue($product, 'minimal_quantity') : 1);
				self::$smarty->assign('available_date', ($this->getFieldValue($product, 'available_date') != 0) ? stripslashes(htmlentities(Tools::displayDate($this->getFieldValue($product, 'available_date'), null))) : '0000-00-00');

				$i = 0;
				self::$smarty->assign('imageType', ImageType::getByNameNType('small_default', 'products'));
				self::$smarty->assign('imageWidth', (isset($image_type['width']) ? (int)($image_type['width']) : 64) + 25);
				foreach ($images as $k => $image)
				{
					$images[$k]['obj'] = new Image($image['id_image']);
					++$i;
				}
				self::$smarty->assign('images', $images);
				self::$smarty->assign(array(
					'combinationArray' => $this->getCombinations($product, $currency),
					'product' => $product,
					'id_category' => $product->getDefaultCategory(),
					'token_generator' => 'tokengenerator', 					'combination_exists' => (Shop::isFeatureActive() && (Shop::getContextShopGroup()->share_stock) && count(AttributeGroup::getAttributesGroups($this->context->language->id)) > 0)
					));
			}
		}
		else
		{
			self::$smarty->assign('product', $product);
			$this->displayWarning($this->getMessage('You must save this product before adding combinations.'));
		}

	}

	public function getCombinationImagesJS()
	{
		if (!($obj = $this->object))
			return;

		$content = 'var combination_images = new Array();';
		if (!$allCombinationImages = $obj->getCombinationImages($this->context->language->id))
			return $content;
		foreach ($allCombinationImages as $id_product_attribute => $combination_images)
		{
			$i = 0;
			$content .= 'combination_images['.(int)$id_product_attribute.'] = new Array();';
			foreach ($combination_images as $combination_image)
				$content .= 'combination_images['.(int)$id_product_attribute.']['.$i++.'] = '.(int)$combination_image['id_image'].';';
		}
		return $content;
	}


	public function getCombinations($product, $currency)
	{
		$color_by_default = '#BDE5F8';
		if ($product->id)
		{
						$combinations = $product->getAttributeCombinations($this->context->language->id);
			$groups = array();
			$comb_array = array();
			if (is_array($combinations))
			{
				$combination_images = $product->getCombinationImages($this->context->language->id);
				foreach ($combinations as $k => $combination)
				{
					$price = Tools::displayPrice($combination['price'], $currency);

					$comb_array[$combination['id_product_attribute']]['id_product_attribute'] = $combination['id_product_attribute'];
					$comb_array[$combination['id_product_attribute']]['attributes'][] = array($combination['group_name'], $combination['attribute_name'], $combination['id_attribute']);
					$comb_array[$combination['id_product_attribute']]['wholesale_price'] = $combination['wholesale_price'];
					$comb_array[$combination['id_product_attribute']]['price'] = $price;
					$comb_array[$combination['id_product_attribute']]['weight'] = $combination['weight'].Configuration::get('PS_WEIGHT_UNIT');
					$comb_array[$combination['id_product_attribute']]['unit_impact'] = $combination['unit_price_impact'];
					$comb_array[$combination['id_product_attribute']]['reference'] = $combination['reference'];
					$comb_array[$combination['id_product_attribute']]['ean13'] = $combination['ean13'];
					$comb_array[$combination['id_product_attribute']]['upc'] = $combination['upc'];
					$comb_array[$combination['id_product_attribute']]['id_image'] = isset($combination_images[$combination['id_product_attribute']][0]['id_image']) ? $combination_images[$combination['id_product_attribute']][0]['id_image'] : 0;
					$comb_array[$combination['id_product_attribute']]['available_date'] = strftime($combination['available_date']);
					$comb_array[$combination['id_product_attribute']]['default_on'] = $combination['default_on'];
					if ($combination['is_color_group'])
						$groups[$combination['id_attribute_group']] = $combination['group_name'];
				}
			}

			$irow = 0;
			if (isset($comb_array))
			{
				foreach ($comb_array as $id_product_attribute => $product_attribute)
				{
					$list = '';

										asort($product_attribute['attributes']);

					foreach ($product_attribute['attributes'] as $attribute)
						$list .= htmlspecialchars($attribute[0]).' - '.htmlspecialchars($attribute[1]).', ';

					$list = rtrim($list, ', ');
					$comb_array[$id_product_attribute]['image'] = $product_attribute['id_image'] ? new Image($product_attribute['id_image']) : false;
					$comb_array[$id_product_attribute]['available_date'] = $product_attribute['available_date'] != 0 ? date('Y-m-d', strtotime($product_attribute['available_date'])) : '0000-00-00';
					$comb_array[$id_product_attribute]['attributes'] = $list;

					if ($product_attribute['default_on'])
					{
						$comb_array[$id_product_attribute]['name'] = 'is_default';
						$comb_array[$id_product_attribute]['color'] = $color_by_default;
					}
				}
			}
		}

		return $comb_array;
	}
	
	public function initContentVirtualProduct()
	{
		$product = $this->object;
		$currency = new Currency((int)Configuration::get('PS_CURRENCY_DEFAULT'));

		
				$product_download = new ProductDownload();
		if ($id_product_download = $product_download->getIdFromIdProduct($this->getFieldValue($product, 'id')))
			$product_download = new ProductDownload($id_product_download);
		$product->{'productDownload'} = $product_download;

		$product->cache_default_attribute = (int)Product::getDefaultAttribute($product->id);

				$exists_file = realpath(_PS_DOWNLOAD_DIR_).'/'.$product->productDownload->filename;
		self::$smarty->assign('product_downloaded', $product->productDownload->id && !empty($product->productDownload->display_filename));

				
		if (!file_exists($exists_file)
			&& !empty($product->productDownload->display_filename)
			&& empty($product->cache_default_attribute))
			$msg = sprintf(Tools::displayError('This file "%s" is missing'), $product->productDownload->display_filename);
		else
			$msg = '';
		self::$smarty->assign('download_product_file_missing', $msg);
		self::$smarty->assign('download_dir_writable', ProductDownload::checkWritableDir());

		self::$smarty->assign('up_filename', strval(Tools::getValue('virtual_product_filename')));
		self::$smarty->assign('product_type', (int)Tools::getValue('type_product', $product->getType()));

		$product->productDownload->nb_downloadable = ($product->productDownload->id > 0) ? $product->productDownload->nb_downloadable : htmlentities(Tools::getValue('virtual_product_nb_downloable'), ENT_COMPAT, 'UTF-8');
		$product->productDownload->date_expiration = ($product->productDownload->id > 0) ? ((!empty($product->productDownload->date_expiration) && $product->productDownload->date_expiration != '0000-00-00 00:00:00') ? date('Y-m-d', strtotime($product->productDownload->date_expiration)) : '' ) : htmlentities(Tools::getValue('virtual_product_expiration_date'), ENT_COMPAT, 'UTF-8');
		$product->productDownload->nb_days_accessible = ($product->productDownload->id > 0) ? $product->productDownload->nb_days_accessible : htmlentities(Tools::getValue('virtual_product_nb_days'), ENT_COMPAT, 'UTF-8');
		$product->productDownload->is_shareable = $product->productDownload->id > 0 && $product->productDownload->is_shareable;

		self::$smarty->assign('ad', dirname($_SERVER['PHP_SELF']));
		self::$smarty->assign('product', $product);
		self::$smarty->assign('currency', $currency);
				self::$smarty->assign('link', $this->context->link);
		self::$smarty->assign('is_file', $product->productDownload->checkFile());
		$upload_max_filesize = Tools::getOctets(ini_get('upload_max_filesize'));
				$upload_max_filesize = ($upload_max_filesize / 1024) / 1024;
		self::$smarty->assign('upload_max_filesize', $upload_max_filesize);
		
									}
	
	private function initContentForShipping()
	{
		$product = $this->object;
		$carrier_list = Carrier::getCarriers($this->context->language->id, true, false, false, null, Carrier::ALL_CARRIERS);
				$carrier_selected_list = $product->getCarriers();
		foreach ($carrier_list as &$carrier)
		{
			foreach ($carrier_selected_list as $carrier_selected)
			{
				if ($carrier_selected['id_reference'] == $carrier['id_reference'])
				{
					$carrier['selected'] = true;
					break;
				}
			}
		}

				$this->context->smarty->assign('bullet_common_field', '');
		if (Shop::isFeatureActive() && $this->display == 'edit')
		{
			if (Shop::getContext() != Shop::CONTEXT_SHOP)
			{
				$this->context->smarty->assign(array(
					'display_multishop_checkboxes' => true,
					'multishop_check' => Tools::getValue('multishop_check'),
					));
			}

			if (Shop::getContext() != Shop::CONTEXT_ALL)
			{
				$this->context->smarty->assign('bullet_common_field', '<img src="themes/'.$this->context->employee->bo_theme.'/img/bullet_orange.png" style="vertical-align: bottom" />');
				$this->context->smarty->assign('display_common_field', true);
			}
		}
		//LOONES Mandamos al info del transporte

		$trans_op1=aaFeature::getTransportData(1);
		$trans_op1=aaFeature::getTransportData(1);

	

		// LOONES FIN
		self::$smarty->assign(array(
			'product' => $product,
			'ps_dimension_unit' => Configuration::get('PS_DIMENSION_UNIT'),
			'ps_weight_unit' => Configuration::get('PS_WEIGHT_UNIT'),
			'carrier_list' => $carrier_list,
			'currency' => new Currency((int)Configuration::get('PS_CURRENCY_DEFAULT')),
			'country_display_tax_label' =>  $this->context->country->display_tax_label,
			//LOONES Mandamos al info del transporte
			'trans_op1'		=>	aaFeature::getTransportData(1),
			'trans_op2'		=>	aaFeature::getTransportData(2),

			// LOONES FIN
			));
	}
	
	private function processShipping()
	{
		if (Validate::isLoadedObject($product = $this->object))
		{
			$product->width = floatval(empty($_POST['width']) ? '0' : str_replace(',', '.', $_POST['width']));
			$product->height = floatval(empty($_POST['height']) ? '0' : str_replace(',', '.', $_POST['height']));
			$product->depth = floatval(empty($_POST['depth']) ? '0' : str_replace(',', '.', $_POST['depth']));
			$product->weight = floatval(empty($_POST['weight']) ? '0' : str_replace(',', '.', $_POST['weight']));
			//LOONES #endregion

			$product->id_trans_op1 = empty($_POST['id_trans_op1']) ? '0' : trim($_POST['id_trans_op1']);
			$product->id_trans_op2 = empty($_POST['id_trans_op2']) ? '0' : trim($_POST['id_trans_op2']);	
			$product->trans_op2_n1 = empty($_POST['trans_op2_n1']) ? '0' : trim($_POST['trans_op2_n1']);	
			$product->trans_op2_n2 = empty($_POST['trans_op2_n2']) ? '0' : trim($_POST['trans_op2_n2']);	
			$product->trans_op2_n3 = empty($_POST['trans_op2_n3']) ? '0' : trim($_POST['trans_op2_n3']);		

			//LOONES FIN 

			
			$product->additional_shipping_cost = floatval(empty($_POST['additional_shipping_cost']) ? '0' : str_replace(',', '.', $_POST['additional_shipping_cost']));
			
						$carriers = array();
			if (Tools::getValue('carriers'))
				$carriers = Tools::getValue('carriers');

			$product->setCarriers($carriers);
		}
	}
	
	private function initContentForAttachments()
	{
		if (Validate::isLoadedObject($product = $this->object))
		{
			$attachment_name = array();
			$attachment_description = array();
			$languages = Language::getLanguages(false);
			foreach ($languages as $language)
			{
				$attachment_name[$language['id_lang']] = '';
				$attachment_description[$language['id_lang']] = '';
			}

			$iso_tiny_mce = $this->context->language->iso_code;
			$iso_tiny_mce = (file_exists(_PS_JS_DIR_.'tiny_mce/langs/'.$iso_tiny_mce.'.js') ? $iso_tiny_mce : 'en');
			$ad = str_replace("\\", "\\\\", dirname($_SERVER["PHP_SELF"]));
			self::$smarty->assign(array(
				'languages' => $languages,
				'attach1' => Attachment::getAttachments($this->context->language->id, $product->id, true),
				'attach2' => Attachment::getAttachments($this->context->language->id, $product->id, false),
				'default_form_language' => (int)Configuration::get('PS_LANG_DEFAULT'),
				'attachment_name' => $attachment_name,
				'attachment_description' => $attachment_description,
				'PS_ATTACHMENT_MAXIMUM_SIZE' => Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE'),
				'isoTinyMCE' => $iso_tiny_mce,
				'ad' => $ad
				));
		}
		else
		{
			$this->displayWarning($this->l('You must save this product in this shop before adding attachements.'));
		}
	}
	
	private function processCombinations()
	{
				if (!Combination::isFeatureActive() || !Tools::getIsset('attribute'))
			return;

		if (Validate::isLoadedObject($product = $this->object))
		{
			if ($this->isProductFieldUpdated('attribute_price') && (!Tools::getIsset('attribute_price') || Tools::getIsset('attribute_price') == null))
				$this->errors[] = Tools::displayError('Attribute price required.');
			if (!Tools::getIsset('attribute_combination_list') || Tools::isEmpty(Tools::getValue('attribute_combination_list')))
				$this->errors[] = Tools::displayError('You must add at least one attribute.');

			if (!count($this->errors))
			{
				if (!isset($_POST['attribute_wholesale_price'])) $_POST['attribute_wholesale_price'] = 0;
				if (!isset($_POST['attribute_price_impact'])) $_POST['attribute_price_impact'] = 0;
				if (!isset($_POST['attribute_weight_impact'])) $_POST['attribute_weight_impact'] = 0;
				if (!isset($_POST['attribute_ecotax'])) $_POST['attribute_ecotax'] = 0;
				if (Tools::getValue('attribute_default'))
					$product->deleteDefaultAttributes();
								if ($id_product_attribute = (int)Tools::getValue('id_product_attribute'))
				{
					if ($product->productAttributeExists(Tools::getValue('attribute_combination_list'), (int)$id_product_attribute))
						$this->errors[] = Tools::displayError('This attribute already exists.');
					else
					{
						if ($this->isProductFieldUpdated('available_date_attribute') && !Validate::isDateFormat(Tools::getValue('available_date_attribute')))
							$this->errors[] = Tools::displayError('Invalid date format.');
						else
						{
							$product->updateAttribute((int)$id_product_attribute,
								$this->isProductFieldUpdated('attribute_wholesale_price') ? Tools::getValue('attribute_wholesale_price') : null,
								$this->isProductFieldUpdated('attribute_price_impact') ? Tools::getValue('attribute_price') * Tools::getValue('attribute_price_impact') : null,
								$this->isProductFieldUpdated('attribute_weight_impact') ? Tools::getValue('attribute_weight') * Tools::getValue('attribute_weight_impact') : null,
								$this->isProductFieldUpdated('attribute_unit_impact') ? Tools::getValue('attribute_unity') * Tools::getValue('attribute_unit_impact') : null,
								$this->isProductFieldUpdated('attribute_ecotax') ? Tools::getValue('attribute_ecotax') : null,
								Tools::getValue('id_image_attr'),
								Tools::getValue('attribute_reference'),
								Tools::getValue('attribute_ean13'),
								$this->isProductFieldUpdated('attribute_default') ? Tools::getValue('attribute_default') : null,
								Tools::getValue('attribute_location'),
								Tools::getValue('attribute_upc'),
								$this->isProductFieldUpdated('attribute_minimal_quantity') ? Tools::getValue('attribute_minimal_quantity') : null,
								$this->isProductFieldUpdated('available_date_attribute') ? Tools::getValue('available_date_attribute') : null,
								false);
						}
					}
				}
								else
				{
					if ($product->productAttributeExists(Tools::getValue('attribute_combination_list')))
						$this->errors[] = Tools::displayError('This combination already exists.');
					else
						$id_product_attribute = $product->addCombinationEntity(
							Tools::getValue('attribute_wholesale_price'),
							Tools::getValue('attribute_price') * Tools::getValue('attribute_price_impact'),
							Tools::getValue('attribute_weight') * Tools::getValue('attribute_weight_impact'),
							Tools::getValue('attribute_unity') * Tools::getValue('attribute_unit_impact'),
							Tools::getValue('attribute_ecotax'),
							0,
							Tools::getValue('id_image_attr'),
							Tools::getValue('attribute_reference'),
							null,
							Tools::getValue('attribute_ean13'),
							Tools::getValue('attribute_default'),
							Tools::getValue('attribute_location'),
							Tools::getValue('attribute_upc'),
							Tools::getValue('attribute_minimal_quantity')
							);
				}
				if (!count($this->errors))
				{
					$combination = new Combination((int)$id_product_attribute);
					$combination->setAttributes(Tools::getValue('attribute_combination_list'));
					
										$id_images = Tools::getValue('id_image_attr');
					if (!empty($id_images))
						$combination->setImages($id_images);					
					
					$product->checkDefaultAttributes();
					if (Tools::getValue('attribute_default'))
					{
						Product::updateDefaultAttribute((int)$product->id);
						if(isset($id_product_attribute))
							$product->cache_default_attribute = (int)$id_product_attribute;
						if ($available_date = Tools::getValue('available_date_attribute'))
							$product->setAvailableDate($available_date);
					}
					
					
				}
				if (!count($this->errors))
				{
					if (!$product->cache_default_attribute)
						Product::updateDefaultAttribute($product->id);
				}
			}
		}
		
	}
	
	private function haveThisAccessory($accessory_id, $accessories)
	{
		foreach ($accessories as $accessory)
			if ((int)$accessory['id_product'] == (int)$accessory_id)
				return true;
		return false;
	}
	
	public function processFeatures()
	{
		if (!Feature::isFeatureActive())
			return;

		if (!Validate::isLoadedObject($this->object))
		{
			$this->errors[] = Tools::displayError('Product must be created before adding features.');
			return;
		}

				$this->object->deleteFeatures();

				$languages = Language::getLanguages(false);
		foreach ($_POST as $key => $val)
		{
			if (preg_match('/^feature_([0-9]+)_value/i', $key, $match))
			{
				if ($val)
					$this->object->addFeaturesToDB($match[1], $val);
				else
				{
					if ($default_value = $this->checkFeatures($languages, $match[1]))
					{
						$id_value = $this->object->addFeaturesToDB($match[1], 0, 1);
						foreach ($languages as $language)
						{
							if ($cust = Tools::getValue('custom_'.$match[1].'_'.(int)$language['id_lang']))
								$this->object->addFeaturesCustomToDB($id_value, (int)$language['id_lang'], $cust);
							else
								$this->object->addFeaturesCustomToDB($id_value, (int)$language['id_lang'], $default_value);
						}
					}
					else
						$id_value = $this->object->addFeaturesToDB($match[1], 0, 1);
				}
			}
		}
	}
	
		protected function checkFeatures($languages, $feature_id)
	{
		$rules = call_user_func(array('FeatureValue', 'getValidationRules'), 'FeatureValue');
		$feature = Feature::getFeature((int)Configuration::get('PS_LANG_DEFAULT'), $feature_id);
		$val = 0;
		foreach ($languages as $language)
			if ($val = Tools::getValue('custom_'.$feature_id.'_'.$language['id_lang']))
			{
				$current_language = new Language($language['id_lang']);
				if (Tools::strlen($val) > $rules['sizeLang']['value'])
					$this->errors[] = Tools::displayError('name for feature').' <b>'.$feature['name'].'</b> '.Tools::displayError('is too long in').' '.$current_language->name;
				elseif (!call_user_func(array('Validate', $rules['validateLang']['value']), $val))
					$this->errors[] = Tools::displayError('Valid name required for feature.').' <b>'.$feature['name'].'</b> '.Tools::displayError('in').' '.$current_language->name;
				if (count($this->errors))
					return 0;
								if ($language['id_lang'] == Configuration::get('PS_LANG_DEFAULT'))
					return $val;
			}
		return 0;
	}
	
						protected function isProductFieldUpdated($field, $id_lang = null)
	{
				static $is_activated = null;
		if (is_null($is_activated))
			$is_activated = Shop::isFeatureActive() && Shop::getContext() != Shop::CONTEXT_SHOP && $this->id_object;

		if (!$is_activated)
			return true;

		if (is_null($id_lang))
			return !empty($_POST['multishop_check'][$field]);
		else
			return !empty($_POST['multishop_check'][$field][$id_lang]);
	}
	
	protected function _getFinalPrice($specific_price, $productPrice, $taxRate)
	{
		$price = Tools::ps_round((float)($specific_price['price']) ? $specific_price['price'] : $productPrice, 2);
		if (!(float)($specific_price['reduction']))
			return (float)($specific_price['price']);
		return ($specific_price['reduction_type'] == 'amount') ? ($price - $specific_price['reduction'] / (1 + $taxRate / 100)) : ($price - $price * $specific_price['reduction']);
	}
	
		public function processAddAttachments()
	{
		$languages = Language::getLanguages(false);
		$is_attachment_name_valid = false;
		foreach ($languages as $language)
		{
			$attachment_name_lang = Tools::getValue('attachment_name_'.(int)($language['id_lang']));
			if (Tools::strlen($attachment_name_lang ) > 0)
				$is_attachment_name_valid = true;

			if (!Validate::isGenericName(Tools::getValue('attachment_name_'.(int)($language['id_lang']))))
				$this->errors[] = Tools::displayError('Invalid Name');
			elseif (Tools::strlen(Tools::getValue('attachment_name_'.(int)($language['id_lang']))) > 32)
				$this->errors[] = sprintf(Tools::displayError('Name is too long (%d chars max).'), 32);
			if (!Validate::isCleanHtml(Tools::getValue('attachment_description_'.(int)($language['id_lang']))))
				$this->errors[] = Tools::displayError('Invalid description');
		}
		if (!$is_attachment_name_valid)
			$this->errors[] = Tools::displayError('Attachment name required');

		if (empty($this->errors))
		{
			if (isset($_FILES['attachment_file']) && is_uploaded_file($_FILES['attachment_file']['tmp_name']))
			{
				if ($_FILES['attachment_file']['size'] > (Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE') * 1024 * 1024))
					$this->errors[] = sprintf(
						$this->l('File too large, maximum size allowed: %1$d kB. File size you\'re trying to upload is: %2$d kB.'),
						(Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE') * 1024),
						number_format(($_FILES['attachment_file']['size'] / 1024), 2, '.', '')
						);
				else
				{
					do $uniqid = sha1(microtime());
					while (file_exists(_PS_DOWNLOAD_DIR_.$uniqid));
					if (!copy($_FILES['attachment_file']['tmp_name'], _PS_DOWNLOAD_DIR_.$uniqid))
						$this->errors[] = $this->l('File copy failed');
					@unlink($_FILES['attachment_file']['tmp_name']);
				}
			}
			elseif ((int)$_FILES['attachment_file']['error'] === 1)
			{
				$max_upload = (int)ini_get('upload_max_filesize');
				$max_post = (int)ini_get('post_max_size');
				$upload_mb = min($max_upload, $max_post);
				$this->errors[] = sprintf(
					$this->l('The File %1$s exceeds the size allowed by the server. The limit is set to %2$d MB.'),
					'<b>'.$_FILES['attachment_file']['name'].'</b> ',
					'<b>'.$upload_mb.'</b>'
					);
			}
			else
				$this->errors[] = Tools::displayError('File is missing');

			if (empty($this->errors) && isset($uniqid))
			{
				$attachment = new Attachment();
				foreach ($languages as $language)
				{
					if (Tools::getIsset('attachment_name_'.(int)$language['id_lang']))
						$attachment->name[(int)$language['id_lang']] = Tools::getValue('attachment_name_'.(int)$language['id_lang']);
					if (Tools::getIsset('attachment_description_'.(int)$language['id_lang']))
						$attachment->description[(int)$language['id_lang']] = Tools::getValue('attachment_description_'.(int)$language['id_lang']);
				}
				$attachment->file = $uniqid;
				$attachment->mime = $_FILES['attachment_file']['type'];
				$attachment->file_name = $_FILES['attachment_file']['name'];
				if (empty($attachment->mime) || Tools::strlen($attachment->mime) > 128)
					$this->errors[] = Tools::displayError('Invalid file extension');
				if (!Validate::isGenericName($attachment->file_name))
					$this->errors[] = Tools::displayError('Invalid file name');
				if (Tools::strlen($attachment->file_name) > 128)
					$this->errors[] = Tools::displayError('File name too long');
				if (empty($this->errors))
				{
					$res = $attachment->add();
					if (!$res)
						$this->errors[] = Tools::displayError('Unable to add this attachment in the database');
					else
					{
						$id_product = (int)Tools::getValue($this->identifier);
						$res = $attachment->attachProduct($id_product);
						if (!$res)
							$this->errors[] = Tools::displayError('Unable to associate this attachment to product');
					}
				}
				else
					$this->errors[] = Tools::displayError('Invalid file');
			}
		}
	}

		public function processAttachments()
	{
		if ($id = (int)Tools::getValue($this->identifier))
		{
			$attachments = trim(Tools::getValue('arrayAttachments'), ',');
			$attachments = explode(',', $attachments);
			if (!Attachment::attachToProduct($id, $attachments))
				$this->errors[] = Tools::displayError('There was an error while saving product attachments.');
		}
	}

	protected function _displaySpecificPriceModificationForm($defaultCurrency, $shops, $currencies, $countries, $groups)
	{
		$content = '';
		if (!$this->object)	return;

		$specific_prices = SpecificPrice::getByProductId((int)$this->object->id);
		$specific_price_priorities = SpecificPrice::getPriority((int)$this->object->id);

		$taxRate = $this->object->getTaxesRate(Address::initialize());

		$tmp = array();
		foreach ($shops as $shop)
			$tmp[$shop['id_shop']] = $shop;
		$shops = $tmp;
		$tmp = array();
		foreach ($currencies as $currency)
			$tmp[$currency['id_currency']] = $currency;
		$currencies = $tmp;

		$tmp = array();
		foreach ($countries as $country)
			$tmp[$country['id_country']] = $country;
		$countries = $tmp;

		$tmp = array();
		foreach ($groups as $group)
			$tmp[$group['id_group']] = $group;
		$groups = $tmp;

		if (!is_array($specific_prices) || !count($specific_prices))
			$content .= '
				<tr>
					<td colspan="13">'.$this->l('No specific prices').'</td>
				</tr>';
		else
		{
			$i = 0;
			foreach ($specific_prices as $specific_price)
			{
				$current_specific_currency = $currencies[($specific_price['id_currency'] ? $specific_price['id_currency'] : $defaultCurrency->id)];
				if ($specific_price['reduction_type'] == 'percentage')
					$impact = '- '.($specific_price['reduction'] * 100).' %';
				elseif ($specific_price['reduction'] > 0)
					$impact = '- '.Tools::displayPrice(Tools::ps_round($specific_price['reduction'], 2), $current_specific_currency);
				else
					$impact = '--';

				if ($specific_price['from'] == '0000-00-00 00:00:00' && $specific_price['to'] == '0000-00-00 00:00:00')
					$period = $this->l('Unlimited');
				else
					$period = $this->l('From').' '.($specific_price['from'] != '0000-00-00 00:00:00' ? $specific_price['from'] : '0000-00-00 00:00:00').'<br />'.$this->l('To').' '.($specific_price['to'] != '0000-00-00 00:00:00' ? $specific_price['to'] : '0000-00-00 00:00:00');
				if ($specific_price['id_product_attribute'])
				{
					$combination = new Combination((int)$specific_price['id_product_attribute']);
					$attributes = $combination->getAttributesName((int)$this->context->language->id);
					$attributes_name = '';
					foreach ($attributes as $attribute)
						$attributes_name .= $attribute['name'].' - ';
					$attributes_name = rtrim($attributes_name, ' - ');
				}
				else
					$attributes_name = $this->l('All combinations');

				$rule = new SpecificPriceRule((int)$specific_price['id_specific_price_rule']);
				$rule_name = ($rule->id ? $rule->name : '--');

				if ($specific_price['id_customer'])
				{
					$customer = new Customer((int)$specific_price['id_customer']);
					if (Validate::isLoadedObject($customer))
						$customer_full_name = $customer->firstname.' '.$customer->lastname;
					unset($customer);
				}

				$price = Tools::ps_round($specific_price['price'], 2);
				$fixed_price = ($price == Tools::ps_round($this->object->price, 2) || $specific_price['price'] == -1) ? '--' : Tools::displayPrice($price);
				$content .= '
				<tr '.($i % 2 ? 'class="alt_row"' : '').'>
					<td class="cell border">'.$rule_name.'</td>
					<td class="cell border">'.$attributes_name.'</td>
					'.(Shop::isFeatureActive() ? '<td class="cell border">'.($specific_price['id_shop'] ? $shops[$specific_price['id_shop']]['name'] : $this->l('All shops')).'</td>' : '').'
					<td class="cell border">'.($specific_price['id_currency'] ? $currencies[$specific_price['id_currency']]['name'] : $this->l('All currencies')).'</td>
					<td class="cell border">'.($specific_price['id_country'] ? $countries[$specific_price['id_country']]['name'] : $this->l('All countries')).'</td>
					<td class="cell border">'.($specific_price['id_group'] ? $groups[$specific_price['id_group']]['name'] : $this->l('All groups')).'</td>
					<td class="cell border" title="'.$this->l('ID:').' '.$specific_price['id_customer'].'">'.(isset($customer_full_name) ? $customer_full_name : $this->l('All customers')).'</td>
					<td class="cell border">'.$fixed_price.'</td>
					<td class="cell border">'.$impact.'</td>
					<td class="cell border">'.$period.'</td>
					<td class="cell border">'.$specific_price['from_quantity'].'</th>
					<td class="cell border">'.(!$rule->id ? '<a name="delete_link" href="' . __PS_BASE_URI__ . 'modules/agilemultipleseller/ajax_products.php?action=DeleteSpecificPrice&id_product=' . $this->id_object. '&id_specific_price=' . (int)($specific_price['id_specific_price']). '"><img src="' . __PS_BASE_URI__ . 'img/admin/delete.gif" alt="'.$this->l('Delete').'" /></a>': '').'</td>
				</tr>';
				$i++;
				unset($customer_full_name);
			}
		}
		$content .= '
				</tbody>
			</table>
		</div>';

		$content .= '
		<script type="text/javascript">
			var currencies = new Array();
			currencies[0] = new Array();
			currencies[0]["sign"] = "'.$defaultCurrency->sign.'";
			currencies[0]["format"] = "'.$defaultCurrency->format.'";
			';
			foreach ($currencies as $currency)
			{
				$content .= '
				currencies['.$currency['id_currency'].'] = new Array();
				currencies['.$currency['id_currency'].']["sign"] = "'.$currency['sign'].'";
				currencies['.$currency['id_currency'].']["format"] = "'.$currency['format'].'";
				';
			}
		$content .= '
		</script>
		';

				if ($specific_price_priorities[0] == 'id_customer')
			unset($specific_price_priorities[0]);
				$specific_price_priorities = array_values($specific_price_priorities);

		$content .= '<div id="agile"class="panel">
		<h3>'.$this->l('Priority management').'</h3>
		<div class="alert alert-info">
				'.$this->l('Sometimes one customer can fit into multiple price rules. Priorities allow you to define which rule applies to the customer.').'
		</div>';

		$content .= '
		<div class="form-group">
			<label class="control-label agile-col-sm-3 agile-col-md-3 agile-col-lg-3 agile-col-xl-3" for="specificPricePriority1">'.$this->l('Priorities:').'</label>
			<div class="input-group  agile-col-sm-9  agile-col-md9 col-lg-9  agile-col-xl-9">
				<select id="specificPricePriority1" name="specificPricePriority[]">
					<option value="id_shop"'.($specific_price_priorities[0] == 'id_shop' ? ' selected="selected"' : '').'>'.$this->l('Shop').'</option>
					<option value="id_currency"'.($specific_price_priorities[0] == 'id_currency' ? ' selected="selected"' : '').'>'.$this->l('Currency').'</option>
					<option value="id_country"'.($specific_price_priorities[0] == 'id_country' ? ' selected="selected"' : '').'>'.$this->l('Country').'</option>
					<option value="id_group"'.($specific_price_priorities[0] == 'id_group' ? ' selected="selected"' : '').'>'.$this->l('Group').'</option>
				</select>
				<span class="input-group-addon"><i class="icon-chevron-right"></i></span>
				<select name="specificPricePriority[]">
					<option value="id_shop"'.($specific_price_priorities[1] == 'id_shop' ? ' selected="selected"' : '').'>'.$this->l('Shop').'</option>
					<option value="id_currency"'.($specific_price_priorities[1] == 'id_currency' ? ' selected="selected"' : '').'>'.$this->l('Currency').'</option>
					<option value="id_country"'.($specific_price_priorities[1] == 'id_country' ? ' selected="selected"' : '').'>'.$this->l('Country').'</option>
					<option value="id_group"'.($specific_price_priorities[1] == 'id_group' ? ' selected="selected"' : '').'>'.$this->l('Group').'</option>
				</select>
				<span class="input-group-addon"><i class="icon-chevron-right"></i></span>
				<select name="specificPricePriority[]">
					<option value="id_shop"'.($specific_price_priorities[2] == 'id_shop' ? ' selected="selected"' : '').'>'.$this->l('Shop').'</option>
					<option value="id_currency"'.($specific_price_priorities[2] == 'id_currency' ? ' selected="selected"' : '').'>'.$this->l('Currency').'</option>
					<option value="id_country"'.($specific_price_priorities[2] == 'id_country' ? ' selected="selected"' : '').'>'.$this->l('Country').'</option>
					<option value="id_group"'.($specific_price_priorities[2] == 'id_group' ? ' selected="selected"' : '').'>'.$this->l('Group').'</option>
				</select>
				<span class="input-group-addon"><i class="icon-chevron-right"></i></span>
				<select name="specificPricePriority[]">
					<option value="id_shop"'.($specific_price_priorities[3] == 'id_shop' ? ' selected="selected"' : '').'>'.$this->l('Shop').'</option>
					<option value="id_currency"'.($specific_price_priorities[3] == 'id_currency' ? ' selected="selected"' : '').'>'.$this->l('Currency').'</option>
					<option value="id_country"'.($specific_price_priorities[3] == 'id_country' ? ' selected="selected"' : '').'>'.$this->l('Country').'</option>
					<option value="id_group"'.($specific_price_priorities[3] == 'id_group' ? ' selected="selected"' : '').'>'.$this->l('Group').'</option>
				</select>
			</div>
		</div>
		<div class="form-group">
			<div class=" agile-col-sm-9  agile-sm-offset-3  agile-col-md-9  agile-md-offset-3 col-lg-9 col-lg-offset-3  agile-col-xl-9  agile-xl-ooffset-3">
				<p class="checkbox">
					<label for="specificPricePriorityToAll"><input type="checkbox" name="specificPricePriorityToAll" id="specificPricePriorityToAll" />'.$this->l('Apply to all products').'</label>
				</p>
			</div>
		</div>
		</div>
		';

		return $content;
	}


	public function getMessage($key)
	{
		$messages = array(
			'Price rule successfully updated' => $this->l('Price rule successfully updated')
			,'If you wish to use the advanced stock management, you have to:' => $this->l('If you wish to use the advanced stock management, you have to:')
			,'associate your products with warehouses' => $this->l('associate your products with warehouses')
			,'associate your warehouses with carriers' => $this->l('associate your warehouses with carriers')
			,'associate your warehouses with the appropriate shops' => $this->l('associate your warehouses with the appropriate shops')
			,'You must have a common warehouse between this pack and its product.' => $this->l('You must have a common warehouse between this pack and its product.')
			,'You must save this product before managing quantities.' => $this->l('You must save this product before managing quantities.')
			,'You must save this product before adding specific prices' => $this->l('You must save this product before adding specific prices')
			,'This feature has been disabled, you can activate this feature at this page:' => $this->l('This feature has been disabled, you can activate this feature at this page:')
			,'link to Performances' => $this->l('link to Performances')
			,'A virtual product cannot have combinations.' => $this->l('A virtual product cannot have combinations.')
			,'You must save this product before adding combinations.' => $this->l('You must save this product before adding combinations.')
			);
		return $messages[$key];
	}
}

