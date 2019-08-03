<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.

class Link extends LinkCore
{
	public function getPaginationLink($type, $id_object, $nb = false, $sort = false, $pagination = false, $array = false)
	{
		$pagename = AgileHelper::getPageName();
		if(Module::isInstalled('agilemultipleshop'))
		{
			require_once(_PS_ROOT_DIR_ . "/modules/agilemultipleshop/agilemultipleshop.php");
			if($pagename =="agilesellers.php")
			{
				$url = $this->getAgileSellersLink(Tools::getValue('filter'),NULL, Tools::getValue('loclevel'), Tools::getValue('parentid'));
				if(strpos($url, "?") === false) $url .= "?";
				$seller_location = Tools::getValue('seller_location');
				if(!empty($seller_location))$url .= "&seller_location=" . $seller_location;
				$seller_type = Tools::getValue('seller_type');
				if(!empty($seller_type))$url .= "&seller_type=" . $seller_type;
				$userview = Tools::getValue('userview');
				if(!empty($userview))$url .= "&userview=" . $userview;
				$n = Tools::getValue('n');
				if(!empty($n))$url .= "&n=" . (int)$n;
				
				return $url;
			}
			if($pagename =="sellerlocation.php")
			{
				$url = $this->getSellerLocationLink(AgileMultipleShop::getShopByLocationID(), AgileMultipleShop::getShopByLocationLevel());
				if(strpos($url, "?") === false) $url .= "?";
				return $url;
			}
		}
				$fc = Tools::getValue('fc');
		$module = Tools::getValue('module');
		$controller = Tools::getValue('controller');
		if($fc == 'module' AND !empty($module) AND !empty($controller) AND strlen($module)>5 && substr($module,0,5) == "agile")
		{
			$url = $this->getModuleLink($module,$controller,array(),true);
			if(strpos($url, "?") === false) $url .= "?";
			return $url;
		}

		if($pagename =="sellerorders.php")
		{
			$url = $this->getModuleLink('agilemultipleseller','sellerorders',array(),true);
			if(strpos($url, "?") === false) $url .= "?";
			return $url;
		}

		if($pagename =="sellerhistory.php")
		{
			$url = $this->getModuleLink('agilemultipleseller','sellerhistory',array(),true);
			if(strpos($url, "?") === false) $url .= "?";
			return $url;
		}
		
		return parent::getPaginationLink($type, $id_object, $nb, $sort, $pagination, $array);
	}  

	public function getMySellerAccountLink($id_lang = NULL)
	{
		if($this->allow)
		{
			$myseller_account_dir = Configuration::get('AGILE_MS_MYSELLER_URL_DIRECTORY');
			if(empty($myseller_account_dir))$myseller_account_dir = 'my-seller-account';		
			$url = '';
			$url .= (_PS_BASE_URL_SSL_.__PS_BASE_URI__.$this->getLangLink((int)$id_lang));
			$url .= $myseller_account_dir;
			return $url;
		}
		return _PS_BASE_URL_SSL_.__PS_BASE_URI__.'myselleraccount.php';
	}

	public function getAgileSellerLink($id_seller, $alias = NULL, $id_lang = NULL)
	{
		if(!$id_seller)return '';

		include_once(_PS_ROOT_DIR_  . "/modules/agilemultipleseller/SellerInfo.php");

		$sellerinfo = new SellerInfo(SellerInfo::getIdBSellerId($id_seller));
		$id_shopurl = Shop::get_main_url_id($sellerinfo->id_shop);
		$shopurl = new ShopUrl($id_shopurl);	
		$force_ssl = (Configuration::get('PS_SSL_ENABLED') && Configuration::get('PS_SSL_ENABLED_EVERYWHERE'));
		$url = $shopurl->getURL($force_ssl)  .$this->getLangLink();
		if(Module::IsInstalled('agilemultipleshop'))
		{
			include_once(_PS_ROOT_DIR_  . "/modules/agilemultipleshop/agilemultipleshop.php");
			if((int)Configuration::get('ASP_SHOP_URL_MODE') == AgileMultipleShop::SHOP_URL_MODE_DOMAIN)
				
				return $url;
		}

		if($this->allow)
		{
			return $url;
		}
		else
		{
			return _PS_BASE_URL_SSL_.__PS_BASE_URI__.'index.php?controller=agileseller&id_seller='.(int)($id_seller);
		}
	}
	
	

	private function getParentName($loclevel,$parentid, $id_lang)
	{
		if($loclevel == 'country'){
			return 'all';
		}
		if($loclevel == 'state'){
			$country = new Country($parentid, $id_lang);
			if(Validate::isLoadedObject($country))return $country->name;
		}
		if($loclevel == 'city'){
			$state = new State($parentid);
			if(Validate::isLoadedObject($state))return $state->name;
		}
		
	}
	
	public function getAgileSellersLink($filter = NULL, $id_lang = NULL, $loclevel = NULL, $parentid = NULL)
	{
		$vars = array();
		$vars['filter'] = ($filter == NULL? Tools::getValue('filter', 'all') : $filter); 
		$vars['loclevel'] = ($loclevel == NULL ? Tools::getValue('loclevel', Configuration::get('ASP_LOCATION_BLOCK_LEVEL')) : $loclevel);
		$vars['parentid'] = ($parentid == NULL? Tools::getValue('parentid') : $parentid);
		
		$vars['seller_location'] = Tools::getValue('seller_location');
		$vars['seller_type'] = Tools::getValue('seller_type');
		$vars['p'] = Tools::getValue('p', 1);
		$vars['n'] = Tools::getValue('n', 10);
		$vars['userview'] = Tools::getValue('userview');
		
		return $this->getModuleLink('agilemultipleshop', 'agilesellers', $vars, true, $id_lang);
	}
	
	
	public function getSellerLocationLink($id_location, $location_level='', $id_lang = NULL)
	{
		include_once(_PS_ROOT_DIR_ . "/modules/agilemultipleshop/agilemultipleshop.php");
		include_once(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/SellerType.php");
		if(!isset($location_level) || empty($location_level))$location_level = Configuration::get('ASP_LOCATION_BLOCK_LEVEL');

		$vars = array();
		$vars['location_level'] = $location_level;
		$vars['id_location'] = $id_location;
		return $this->getModuleLink('agilemultipleshop','sellerlocation', $vars, true, $id_lang);
	}
	
	public function getRatingListLink($id_type, $id_target, $alias = NULL)
	{
		$allow = (int)Configuration::get('PS_REWRITING_SETTINGS');
		return $this->getModuleLink("agilesellerratings","ratinglist", array('id_type'=>$id_type, 'id_target'=>$id_target), true);		
	}


	public function getFeedbackWaitListLink($alias = NULL)
	{
		$allow = (int)Configuration::get('PS_REWRITING_SETTINGS');
		return $this->getModuleLink("agilesellerratings","feedbackwaitlist", array(), true);
	}
	
	private function getAgileBaseUrl($usevirtual, $id_seller, $ssl,  $relative_protocol = false)
	{
		static $force_ssl = null;

		$def_shop_id = (int)Configuration::get('PS_SHOP_DEFAULT');
		$shop = new Shop($def_shop_id);
		if($usevirtual && $id_seller > 0)
		{
			$sql = 'SELECT id_shop FROM ' . _DB_PREFIX_ . 'sellerinfo WHERE id_seller=' . (int)$id_seller;
			$id_shop = Db::getInstance()->getValue($sql);
			$shop = new Shop($id_shop);
		}


		if ($ssl === null)
		{
			if ($force_ssl === null)
				$force_ssl = (Configuration::get('PS_SSL_ENABLED') && Configuration::get('PS_SSL_ENABLED_EVERYWHERE'));
			$ssl = $force_ssl;
		}
		else 
			$ssl = $ssl && Configuration::get('PS_SSL_ENABLED');


		if ($relative_protocol)
			$base = '//'.($ssl && $this->ssl_enable ? $shop->domain_ssl : $shop->domain);
		else
			$base = (($ssl && $this->ssl_enable) ? 'https://'.$shop->domain_ssl : 'http://'.$shop->domain);

		
		return $base.$shop->getBaseURI();
	}

	
	public function getProductLink($product, $alias = NULL, $category = NULL, $ean13 = NULL, $id_lang = NULL, $id_shop = NULL, $ipa = 0, $force_routes = false, $relative_protocol = false, $add_anchor = false, $extra_params = array())
	{
		if (!$id_lang)
			$id_lang = Context::getContext()->language->id;

		if(!Module::isInstalled('agilemultipleshop'))
			return parent::getProductLink($product, $alias, $category, $ean13, $id_lang, $id_shop, $ipa, $force_routes,$relative_protocol,$add_anchor,$extra_params);

		$url_choice_prod = (int)Configuration::get('ASP_URL_CHOICE_PROD');

		if($url_choice_prod == 0)
			return parent::getProductLink($product, $alias, $category, $ean13, $id_lang, $id_shop, $ipa, $force_routes,$relative_protocol,$add_anchor,$extra_params);

				$id_seller = AgileSellerManager::getObjectOwnerID('product',  is_object($product)? $product->id : (int)$product);
		$url = $this->getAgileBaseUrl($url_choice_prod == 2, $id_seller, null, $relative_protocol) . $this->getLangLink($id_lang, null, $id_shop);

	
		$dispatcher = Dispatcher::getInstance();

		if (!is_object($product))
		{
			if (is_array($product) && isset($product['id_product']))
				$product = new Product($product['id_product'], false, $id_lang, $id_shop);
			elseif ((int)$product)
				$product = new Product((int)$product, false, $id_lang, $id_shop);
			else
				throw new PrestaShopException('Invalid product vars');
		}
		
				$params = array();
		$params['id'] = $product->id;
		$params['rewrite'] = (!$alias) ? $product->getFieldByLang('link_rewrite') : $alias;
		$params['ean13'] = (!$ean13) ? $product->ean13 : $ean13;
		$params['meta_keywords'] =	Tools::str2url($product->getFieldByLang('meta_keywords'));
		$params['meta_title'] = Tools::str2url($product->getFieldByLang('meta_title'));
		$params['id_product_attribute'] = $ipa;

		if ($dispatcher->hasKeyword('product_rule', $id_lang, 'manufacturer', $id_shop))
			$params['manufacturer'] = Tools::str2url($product->isFullyLoaded ? $product->manufacturer_name : Manufacturer::getNameById($product->id_manufacturer));

		if ($dispatcher->hasKeyword('product_rule', $id_lang, 'supplier', $id_shop))
			$params['supplier'] = Tools::str2url($product->isFullyLoaded ? $product->supplier_name : Supplier::getNameById($product->id_supplier));

		if ($dispatcher->hasKeyword('product_rule', $id_lang, 'price', $id_shop))
			$params['price'] = $product->isFullyLoaded ? $product->price : Product::getPriceStatic($product->id, false, null, 6, null, false, true, 1, false, null, null, null, $product->specificPrice);

		if ($dispatcher->hasKeyword('product_rule', $id_lang, 'tags', $id_shop))
			$params['tags'] = Tools::str2url($product->getTags($id_lang));
		
		if ($dispatcher->hasKeyword('product_rule', $id_lang, 'category', $id_shop))
			$params['category'] = (!is_null($product->category) && !empty($product->category)) ? Tools::str2url($product->category) : Tools::str2url($category);

		if ($dispatcher->hasKeyword('product_rule', $id_lang, 'reference', $id_shop))
			$params['reference'] = Tools::str2url($product->reference);

		if ($dispatcher->hasKeyword('product_rule', $id_lang, 'categories', $id_shop))
		{
			$params['category'] = (!$category) ? $product->category : $category;
			$cats = array();
			foreach ($product->getParentCategories() as $cat)
				if (!in_array($cat['id_category'], Link::$category_disable_rewrite))					$cats[] = $cat['link_rewrite'];
			$params['categories'] = implode('/', $cats);
		}
		$anchor = $ipa ? $product->getAnchor($ipa) : '';

		return $url.$dispatcher->createUrl('product_rule', $id_lang, array_merge($params, $extra_params), $force_routes, $anchor, $id_shop);
	}
	
	
	public function getModuleLink($module, $controller = 'default', array $params = array(), $ssl = null, $id_lang = null, $id_shop = null, $relative_protocol = false)
	{
		if(!Module::isInstalled('agilemultipleshop'))
			return parent::getModuleLink($module, $controller, $params, $ssl, $id_lang, $id_shop, $relative_protocol);
		
		if (!$id_lang)
			$id_lang = Context::getContext()->language->id;

				$url = $this->getAgileBaseUrl(false, 0, $ssl, $relative_protocol) . $this->getLangLink($id_lang, null, $id_shop);

				if (Dispatcher::getInstance()->hasRoute('module-'.$module.'-'.$controller, $id_lang, $id_shop))
			return $this->getPageLink('module-'.$module.'-'.$controller, $ssl, $id_lang, $params);
		else
		{
						$params['module'] = $module;
			$params['controller'] = $controller ? $controller : 'default';		
			return $url.Dispatcher::getInstance()->createUrl('module', $id_lang, $params, $this->allow, '', $id_shop);
		}
	}
	
	
	public function getPageLink($controller, $ssl = null, $id_lang = null, $request = null, $request_url_encode = false, $id_shop = null, $relative_protocol = false)
	{
		if(!Module::isInstalled('agilemultipleshop'))
			return parent::getPageLink($controller, $ssl, $id_lang, $request, $request_url_encode, $id_shop, $relative_protocol);
		
				$p = strpos($controller, '&');
		if ($p !== false) {
			$request = substr($controller, $p + 1);
			$request_url_encode = false;
			$controller = substr($controller, 0, $p);
		}
		
		$controller = Tools::strReplaceFirst('.php', '', $controller);
		if (!$id_lang)
			$id_lang = (int)Context::getContext()->language->id;

		if (!is_array($request))
		{
						$request = html_entity_decode($request);
			if ($request_url_encode)
				$request = urlencode($request);
			parse_str($request, $request);
		}
		
		if ($controller === 'cart' && (!empty($request['add']) || !empty($request['delete'])) && Configuration::get('PS_TOKEN_ENABLE')) {
			$request['token'] = Tools::getToken(false);
		}

		$uri_path = Dispatcher::getInstance()->createUrl($controller, $id_lang, $request, false, '', $id_shop);

		$url = $this->getBaseLink($id_shop, $ssl, $relative_protocol);
				$pages = array('index');
		if(!in_array($controller, $pages))
		{ 
			$url =  $this->getAgileBaseUrl(false, Shop::$id_shop_owner, $ssl); 
		}
		
		return $url.$this->getLangLink($id_lang, null, $id_shop).ltrim($uri_path, '/');
	}
	
	
	public function getCategoryLink($category, $alias = null, $id_lang = null, $selected_filters = null, $id_shop = null, $relative_protocol = false)
	{
		if(!Module::isInstalled('agilemultipleshop'))
			return parent::getCategoryLink($category, $alias, $id_lang, $selected_filters, $id_shop, $relative_protocol);
		
		$url_choice_cat = (int)Configuration::get('ASP_URL_CHOICE_CAT');
		if($url_choice_cat == 0)
			return parent::getCategoryLink($category, $alias, $id_lang, $selected_filters, $id_shop, $relative_protocol);

				$id_seller = AgileSellerManager::getObjectOwnerID('category',  is_object($category)? $category->id : (int)$category);
		$url = $this->getAgileBaseUrl($url_choice_cat == 2, $id_seller, null, $relative_protocol) . $this->getLangLink($id_lang, null, $id_shop);

		if (!$id_lang)
			$id_lang = Context::getContext()->language->id;
		
		if (!is_object($category))
			$category = new Category($category, $id_lang);

				$params = array();
		$params['id'] = $category->id;
		$params['rewrite'] = (!$alias) ? $category->link_rewrite : $alias;
		$params['meta_keywords'] =	Tools::str2url($category->getFieldByLang('meta_keywords'));
		$params['meta_title'] = Tools::str2url($category->getFieldByLang('meta_title'));

				$selected_filters = is_null($selected_filters) ? '' : $selected_filters;

		if (empty($selected_filters))
			$rule = 'category_rule';
		else
		{
			$rule = 'layered_rule';
			$params['selected_filters'] = $selected_filters;
		}

		return $url.Dispatcher::getInstance()->createUrl($rule, $id_lang, $params, $this->allow, '', $id_shop);
	}

	public function getCMSCategoryLink($cms_category, $alias = null, $id_lang = null, $id_shop = null, $relative_protocol = false)
	{
		if(!Module::isInstalled('agilemultipleshop'))
			return parent::getCMSCategoryLink($cms_category, $alias, $id_lang, $id_shop, $relative_protocol);

		$url_choice_cms = (int)Configuration::get('ASP_URL_CHOICE_CMS');

		if (!$id_lang)
			$id_lang = Context::getContext()->language->id;

				$id_seller = AgileSellerManager::getObjectOwnerID('cms_category',  is_object($cms_category)? $cms_category->id : (int)$cms_category);
		$url = $this->getAgileBaseUrl($url_choice_cms == 2 || $url_choice_cms == 0, $id_seller, null, $relative_protocol) .$this->getLangLink($id_lang, null, $id_shop);

		$dispatcher = Dispatcher::getInstance();
		if (!is_object($cms_category))
		{
			if ($alias !== null && !$dispatcher->hasKeyword('cms_category_rule', $id_lang, 'meta_keywords', $id_shop) && !$dispatcher->hasKeyword('cms_category_rule', $id_lang, 'meta_title', $id_shop))
				return $url.$dispatcher->createUrl('cms_category_rule', $id_lang, array('id' => (int)$cms_category, 'rewrite' => (string)$alias), $this->allow, '', $id_shop);
			$cms_category = new CMSCategory($cms_category, $id_lang);
		}

				$params = array();
		$params['id'] = $cms_category->id;
		$params['rewrite'] = (!$alias) ? $cms_category->link_rewrite : $alias;
		$params['meta_keywords'] =	Tools::str2url($cms_category->meta_keywords);
		$params['meta_title'] = Tools::str2url($cms_category->meta_title);

		return $url.$dispatcher->createUrl('cms_category_rule', $id_lang, $params, $this->allow, '', $id_shop);
	}

	public function getCMSLink($cms, $alias = null, $ssl = null, $id_lang = null, $id_shop = null, $relative_protocol = false)
	{
		if(!Module::isInstalled('agilemultipleshop'))
			return parent::getCMSLink($cms, $alias, $ssl, $id_lang, $id_shop, $relative_protocol);

		$url_choice_cms = (int)Configuration::get('ASP_URL_CHOICE_CMS');

		if (!$id_lang)
			$id_lang = Context::getContext()->language->id;

				$id_seller = AgileSellerManager::getObjectOwnerID('cms',  is_object($cms)? $cms->id : (int)$cms);
		$url = $this->getAgileBaseUrl($url_choice_cms == 2, $id_seller, $ssl, $relative_protocol).$this->getLangLink($id_lang, null, $id_shop);

		$dispatcher = Dispatcher::getInstance();
		if (!is_object($cms))
		{
			if ($alias !== null && !$dispatcher->hasKeyword('cms_rule', $id_lang, 'meta_keywords', $id_shop) && !$dispatcher->hasKeyword('cms_rule', $id_lang, 'meta_title', $id_shop))
				return $url.$dispatcher->createUrl('cms_rule', $id_lang, array('id' => (int)$cms, 'rewrite' => (string)$alias), $this->allow, '', $id_shop);

			$cms = new CMS($cms, $id_lang);
		}

				$params = array();
		$params['id'] = $cms->id;
		$params['rewrite'] = (!$alias) ? (is_array($cms->link_rewrite) ? $cms->link_rewrite[(int)$id_lang] : $cms->link_rewrite) : $alias;

		$params['meta_keywords'] = '';
		if (isset($cms->meta_keywords) && !empty($cms->meta_keywords))
			$params['meta_keywords'] = is_array($cms->meta_keywords) ?  Tools::str2url($cms->meta_keywords[(int)$id_lang]) :  Tools::str2url($cms->meta_keywords);

		$params['meta_title'] = '';
		if (isset($cms->meta_title) && !empty($cms->meta_title))
			$params['meta_title'] = is_array($cms->meta_title) ? Tools::str2url($cms->meta_title[(int)$id_lang]) : Tools::str2url($cms->meta_title);

		return $url.$dispatcher->createUrl('cms_rule', $id_lang, $params, $this->allow, '', $id_shop);
	}
	
}
