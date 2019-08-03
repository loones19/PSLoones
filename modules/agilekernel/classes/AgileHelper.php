<?php
///-build_id: 2018051409.414
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
use PrestaShop\PrestaShop\Core\Addon\Module\ModuleManagerBuilder;

class AgileHelperCore
{
		public static function getPageName()
	{
		$page = $_SERVER["SCRIPT_NAME"];
		$idx = strrpos($page,"/");
		$ret = strtolower(substr($page,$idx+1));
		if(_PS_VERSION_ > '1.5' AND $ret == 'index.php' AND isset($_GET['controller']) AND !empty($_GET['controller']))
		{
			$ret = strtolower(Tools::getValue("controller")) . ".php";
		}
		return $ret;
	}	
	
			public static function getDbDateTime($num_of_interval, $interval)
	{
		$sql = "SELECT NOW() AS timenow, DATE_ADD(NOW(),INTERVAL $num_of_interval $interval) as newtime";
				return Db::getInstance()->getRow($sql);
	}
	
	public static function agile_log($msg)
	{
		$handle = fopen(_PS_ROOT_DIR_ . "/debug.log", "a+");

		if(!$handle)return;
		fwrite($handle, date("Y-m-d H:i:s") . "," . $msg . "\r\n");
		fclose($handle);
	}
	
	public static function agile_log_array($data)
	{
		$msg = '';
		foreach ($data AS $key => $value)
			$msg .= '&'.$key.'='.urlencode(stripslashes($value));

		AgileHelper::agile_log($msg);
	}

		public static function retrieve_column_values($dataset, $column, $includeempty=true)
	{
		if(empty($column) OR empty($dataset) OR !is_array($dataset))return array();
		$results = array();
		foreach($dataset as $datarow)
		{
			if(!isset($datarow[$column]))continue;
			if(!$includeempty AND empty($datarow[$column]))continue;
			$results[] = $datarow[$column];
		}
		return $results;
	}
	
	
	private static function getPathFromName($cateinfo, $for_id, $path)
	{
		if($for_id == 1)return "/" . $path;
		if(!isset($cateinfo[$for_id]))return $path;
		if(!empty($path)) $path = "/" . $path;
		$path = $cateinfo[$for_id]['name'] . $path;
		if($cateinfo[$for_id]['id_parent'] > 0)$path = self::getPathFromName($cateinfo, $cateinfo[$for_id]['id_parent'], $path);
		return $path;
	}
	
		public static function getSortedFullnameCategory($caterows)
	{
		$nameIndex = array();
		if(!empty($caterows))
		{
			foreach($caterows as $cate)
			{
				$nameIndex[$cate['id_category']] = $cate;
			}
		}
		
		$fullnameIndex = array();
		for($idx =0; $idx < count($caterows); $idx++)
		{
			$caterow = $caterows[$idx]; 
			$fullnameIndex[$caterow['id_category']] =  self::getPathFromName($nameIndex,$caterow['id_category'], "");
		}

		unset($nameIndex);

		asort($fullnameIndex);
		$categories = array();
		$root = Category::getRootCategory();
		$rootfullname = isset($fullnameIndex[$root->id]) ? $fullnameIndex[$root->id] : '';
		$rootfullnamelen = strlen($rootfullname);
		foreach($fullnameIndex as $id=>$name)
		{
			if(strlen($name) >= $rootfullnamelen &&  substr($name, 0, $rootfullnamelen) == $rootfullname)
			{
				$categories[] = array('id_category' => $id, 'name' => $name);
			}
		}
		unset($fullnameIndex);

		return $categories;
	}
	public static function getSortedFullnameCategoryLoones($caterows)
	{

		$nameIndex = array();
		if(!empty($caterows))
		{
			foreach($caterows as $cate)
			{
				$nameIndex[$cate['id_category']] = $cate;
			}
		}
		
		$fullnameIndex = array();
		$padres = array();
		for($idx =0; $idx < count($caterows); $idx++)
		{
			$caterow = $caterows[$idx]; 
			$fullnameIndex[$caterow['id_category']] =  self::getPathFromName($nameIndex,$caterow['id_category'], "");
			$padres[$caterow['id_category']] =  $caterow['id_parent'];
			
		}
		foreach ($padres as $key => $value) {
			$categoria=new Category($key);
			$todoslospadres= $categoria->getParentsCategories();
			foreach ($todoslospadres as $llave => $valor) {
				//solo nos interesa la pdre que su pare sea la dos
				if ($valor['id_parent']==2)
					$padres[$key]=$valor['id_category'];
				
				
			}

		}



		unset($nameIndex);

		asort($fullnameIndex);
		$categories = array();
		$root = Category::getRootCategory();
		$rootfullname = isset($fullnameIndex[$root->id]) ? $fullnameIndex[$root->id] : '';
		$rootfullnamelen = strlen($rootfullname);
		foreach($fullnameIndex as $id=>$name)
		{

			if(strlen($name) >= $rootfullnamelen &&  substr($name, 0, $rootfullnamelen) == $rootfullname )
			{
				

				$valor=Category::getLastPosition($id,1);
				if ($valor==1) 
					$categories[] = array('id_category' => $id
										, 'name' => $name
										, 'id_parent' => $padres[$id]
									
									);
			}
		}
		unset($fullnameIndex);

		return $categories;
	}
	public static function getSortedParentCategoryLoones($caterows)
	{

		$nameIndex = array();
		if(!empty($caterows))
		{
			foreach($caterows as $cate)
			{
				$nameIndex[$cate['id_category']] = $cate;
			}
		}
		
		$fullnameIndex = array();
		for($idx =0; $idx < count($caterows); $idx++)
		{
			
			$caterow = $caterows[$idx];
			if  ($caterow['id_parent']==2 ){ 
				$fullnameIndex[$caterow['id_category']] =  self::getPathFromName($nameIndex,$caterow['id_category'], "");
			}
		}

		unset($nameIndex);

		asort($fullnameIndex);
		$categories = array();
		$root = Category::getRootCategory();
		$rootfullname = isset($fullnameIndex[$root->id]) ? $fullnameIndex[$root->id] : '';
		$rootfullnamelen = strlen($rootfullname);
		foreach($fullnameIndex as $id=>$name)
		{

			if(strlen($name) >= $rootfullnamelen &&  substr($name, 0, $rootfullnamelen) == $rootfullname )
			{
				
					$categories[] = array('id_category' => $id, 'name' => $name);
			}
		}
		unset($fullnameIndex);

		return $categories;
	}
	
		public static function AssignProductImgs($products)
	{
		$image_array=array();
		for($i=0;$i<count($products);$i++)
		{
			if(isset($products[$i]['id_product']))
				$image_array[$products[$i]['id_product']]= AgileHelper::getProductImgs($products[$i]['id_product']);
		}
		Context::getContext()->smarty->assign('productimg',(isset($image_array) AND $image_array) ? $image_array : NULL);
	}

		public static function GetProductImgs($product_id)
	{
		$sql = '
		(SELECT * from `'._DB_PREFIX_.'image` 
		WHERE id_product="'.$product_id.'" and cover=1)

		 union
				 (SELECT * from `'._DB_PREFIX_.'image` 
		WHERE id_product="'.$product_id.'" and cover=0 	ORDER BY `position` LIMIT 0,1 )
	
		LIMIT 0,2
		';
		$result = Db::getInstance()->ExecuteS($sql);
		return $result;
	}
	
		public static function GetFirstAvailableCategory()
	{
		$context = Context::getContext();
				
		$first_available_category = Category::getRootCategory();
		if($first_available_category->id > 2)return $first_available_category->id;
		$sql = 'SELECT c.id_category 
				FROM ' . _DB_PREFIX_ . 'category c 
					LEFT JOIN ' . _DB_PREFIX_ . 'category_lang cl ON (c.id_category=cl.id_category AND cl.id_lang=' . $context->language->id . ') 
				WHERE id_parent=' . $first_available_category->id . '
				ORDER BY cl.name';
		
		return (int)Db::getInstance()->getValue($sql);
	}
	
		public static function getAllAncessors($id_category, &$ancessors)	
	{
		if($id_category <= 2)return $ancessors;
		$sql = 'SELECT id_parent FROM ' . _DB_PREFIX_ . 'category WHERE id_category=' . (int)$id_category;
		$id_ancessor = Db::getInstance()->getValue($sql);
		$ancessors[] = $id_ancessor;
		return self::getAllAncessors($id_ancessor, $ancessors);
	}
	
		public static function getAllSuccessors($id_parent, &$successors)	
	{
		$sql = 'SELECT id_category FROM ' . _DB_PREFIX_ . 'category WHERE id_parent=' . (int)$id_parent;
		$rows = Db::getInstance()->ExecuteS($sql);
		if(empty($rows))return $successors;
		foreach($rows as $row)
		{
			$successors[] = (int)$row['id_category'];
			$successors = self::getAllSuccessors($row['id_category'], $successors);
		}
		return $successors;
	}
	
		public static function isAncessor($id_category, $id_ancessor)
	{
		if($id_category == $id_ancessor)return false;
		$sql = 'SELECT id_parent FROM ' . _DB_PREFIX_ . 'category WHERE id_category=' . (int)$id_category;
		$id_parent = (int)Db::getInstance()->getValue($sql);
		if($id_parent == $id_ancessor)return true;
		if($id_parent <=2)return false;
		return self::isAncessor($id_parent, $id_ancessor);
	}

		public static function isSuccessor($id_category, $id_successor)
	{
		if($id_category == $id_successor)return false;
		$sql = 'SELECT id_category FROM ' . _DB_PREFIX_ . 'category WHERE id_parent=' . (int)$id_category;
		$rows = Db::getInstance()->ExecuteS($sql);
		if(empty($rows))return false;
		foreach($rows as $row)
		{
			if($row['id_category'] == $id_successor)return true;
			if(self::isSuccessor($row['id_category'], $id_successor))return true;
		}
		return false;
	}

		public static function createProductWithBasics($id_category, $name, $price, $is_virtual=true)
	{
		$product = new Product();
		$languages = Language::getLanguages(false);
		foreach($languages as $lang)
		{
			$product->name[$lang['id_lang']] = $name;
			$product->link_rewrite[$lang['id_lang']] = Tools::link_rewrite($name);
		}
		
		$product->id_category_default = (int)$id_category;
		$product->id_shop_default = 1;
		$product->wholesale_price = $price;
		$product->price = $price;
		$product->ecotax = 0;
		$product->available_for_order = 1;
		$product->visibility = 'both';
		$product->is_virtual = $is_virtual;
		$product->id_tax_rules_group = 0;	

		$product->active = 1;
		$product->save();
		$product->addStockMvt(2147483647,1);
		
		AgileHelper::setDefaultCategory($product, $id_category);

		return $product;
	}
	
	public static function setDefaultCategory($product, $id_category)
	{
		if(!Validate::isLoadedObject($product))return;

		$product->id_category_default =  $id_category;
		$sql = 'DELETE FROM '. _DB_PREFIX_. 'category_product WHERE id_category=' . (int)$id_category . ' AND id_product=' . (int)$product->id ;
		Db::getinstance()->Execute($sql);
		$sql = 'INSERT INTO '. _DB_PREFIX_. 'category_product (id_category, id_product, position) VALUES (' . (int)$id_category . ',' . (int)$product->id . ',0)';
		Db::getinstance()->Execute($sql);
		$product->save();

	}
	
		public static function createCategoryByName($name)
	{
		$category = new Category();
		$languages = Language::getLanguages(false);
		foreach($languages as $lang)
		{
			$category->name[$lang['id_lang']] = $name;
			$category->link_rewrite[$lang['id_lang']] = Tools::link_rewrite($name);
		}
		
		$category->id_parent = Configuration::get('PS_HOME_CATEGORY');
		$category->id_shop_default = 1;
		$category->level = 2;
		$category->is_root_category = 0;
		$category->position = 0;
		$category->active = 1;
		$category->save();

		return $category;
	}
	
	public static function addCategoryImage($id_category, $url)
	{
		return AgileHelper::copyImg($id_category, null, $url, $entity = 'categories', true);		
	}
	
	public static function addProductImage($id_product, $url)
	{
		$url = trim($url);
		if (empty($url))return false;;
		$url = str_replace(' ', '%20', $url);
		$product_has_images = (bool)Image::getImages(Context::getContext()->language->id, (int)$id_product);

		$image = new Image();
		$image->id_product = (int)$id_product;
		$image->position = Image::getHighestPosition($id_product) + 1;
		$image->cover = $product_has_images ? false : true;
				if ($image->validateFields(false, true) === true &&
			$image->validateFieldsLang(false, true) === true && 
			$image->add())
		{
						$image->associateTo(array(1));
			return AgileHelper::copyImg($id_product, $image->id, $url, 'products', !Tools::getValue('regenerate'));
		}
		return false;
	}
	
		public static function copyImg($id_entity, $id_image = null, $url, $entity = 'products', $regenerate = true)
	{
		$tmpfile = tempnam(_PS_TMP_IMG_DIR_, 'ps_import');
		$watermark_types = explode(',', Configuration::get('WATERMARK_TYPES'));

		switch ($entity)
		{
			default:
			case 'products':
				$image_obj = new Image($id_image);
				$path = $image_obj->getPathForCreation();
				break;
			case 'categories':
				$path = _PS_CAT_IMG_DIR_.(int)$id_entity;
				break;
		}
		$url = str_replace(' ', '%20', trim($url));

				if (!ImageManager::checkImageMemoryLimit($url))
			return false;

						if (AgileHelper::copy($url, $tmpfile))
		{
			ImageManager::resize($tmpfile, $path.'.jpg');
			$images_types = ImageType::getImagesTypes($entity);

			if ($regenerate)
				foreach ($images_types as $image_type)
				{
					ImageManager::resize($tmpfile, $path.'-'.stripslashes($image_type['name']).'.jpg', $image_type['width'], $image_type['height']);
					if (in_array($image_type['id_image_type'], $watermark_types))
						Hook::exec('actionWatermark', array('id_image' => $id_image, 'id_product' => $id_entity));
				}
		}
		else
		{
			unlink($tmpfile);
			return false;
		}
		unlink($tmpfile);
		return true;
	}
	
	public static function getCountryIDbyIso($iso)
	{
		$sql = 'SELECT id_country FROM ' . _DB_PREFIX_ . 'country WHERE iso_code=\'' . $iso . '\'';
		return (int)Db::getInstance()->getValue($sql);
	}
	
	
	public static function copy($source, $destination, $stream_context = null)
	{
		return Tools::copy($source, $destination, $stream_context );		
	}
	
	public static function isLocalIP()
	{
		return ((substr($_SERVER['REMOTE_ADDR'],0,8) == "192.168.") || ($_SERVER['REMOTE_ADDR'] == "127.0.0.1"));	
	}
	
	public static function recurse_copy($src,$dst,$overwrite = true) 
	{ 
		$dir = opendir($src); 
		@mkdir($dst); 
		while(false !== ( $file = readdir($dir)) ) 
		{ 
			if (( $file != '.' ) && ( $file != '..' )) 
			{
				if ( is_dir($src . '/' . $file) ) 
				{ 
					AgileHelper::recurse_copy($src . '/' . $file, $dst . '/' . $file, $overwrite); 
				} 
				else 
				{ 
					if(!$overwrite && file_exists($dst . '/' . $file))continue;
					if(strpos($src, "/app/cache/")  !== false && strpos(php_uname(),"Windows") !== false && (strlen($src . '/' . $file) > 254 || strlen($dst . '/' . $file) > 254 ))continue;
					copy($src . '/' . $file,  $dst . '/' . $file); 
				} 
			} 
		} 
		closedir($dir); 
	}
		
	public static function FixInvalidPaypalData($value, $isRequired=true)
	{
		$value = str_replace("?", "", $value);
		if(empty($value) && $isRequired)$value = "NULL";
		return $value;
	}

	public static function call_remote_server($remote_url, $params)
	{

		if(!function_exists('curl_exec'))return Tools::displayError('cURL is not available');
				$ch = curl_init($remote_url);
		if (!$ch)return Tools::displayError('can not open Demo Manager Server via cURL');

		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSLVERSION, defined('CURL_SSLVERSION_TLSv1_2') ? CURL_SSLVERSION_TLSv1_2 : 6);
		
		$result = curl_exec($ch);
		curl_close($ch);
		
		return $result;
	}
	
	public static function ValidateRecapcha()
	{
		$captcha=Tools::getValue('g-recaptcha-response');
		if(!$captcha){
			return false;
		}
		$grc_url = "https://www.google.com/recaptcha/api/siteverify?secret=" . Configuration::get('AK_GRC_SECRET_KEY') . "&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR'];
		
		$response = json_decode(file_get_contents($grc_url), true);
		return $response['success'];
	}
	
	public static function RmdirRecursive($dir) {
		$files = array_diff(scandir($dir), array('.','..'));
		foreach ($files as $file) {
			(is_dir("$dir/$file")) ? AgileHelper::RmdirRecursive("$dir/$file") : unlink("$dir/$file");
		}
		return rmdir($dir);
	}
	
	public static function EscapePackJS($jsStr)
	{
		$jsStr = str_replace("'","\\'",$jsStr); 		$search  = array("\r\n", "\n", "\r");
		$jsStr = str_replace($search, '', $jsStr);
		return $jsStr;
	}
	
	public static function array_insert(&$array, $insert, $position) {
		settype($array, "array");
		settype($insert, "array");
		settype($position, "int");

				if($position==0) {
			$array = array_merge($insert, $array);
		} else {

			
						if($position >= (count($array)-1)) {
				$array = array_merge($array, $insert);
			} else {
				#split into head and tail, then merge head+inserted bit+tail
				$head = array_slice($array, 0, $position);
				$tail = array_slice($array, $position);
				$array = array_merge($head, $insert, $tail);
			}
		}
	} 

	public static function get_order_id_from_maildata($templateVars)
	{
		$order_name = $templateVars['{order_name}'];
		$idx=0;
								$oninfo = explode("#",$order_name);
		if(count($oninfo) > 1)
		{
			$order_name = $oninfo[0];
			$idx = intval($oninfo[1])  - 1 ;
		}
		$orders = Db::getInstance()->executeS('SELECT id_order FROM ' . _DB_PREFIX_ . 'orders WHERE reference=\'' .$order_name . '\' ORDER BY id_order');
		$id_order = intval($orders[$idx]["id_order"]);
		if($id_order <=0)
		{
			$id_order =  (int)str_replace('#','',$templateVars['{order_name}']);	
		}
		
		if($id_order <=0)
		{
			$id_order =  (int)$templateVars['{id_order}'];
		}
		return $id_order;		
	}
	
		public static function getAllPaymentModules($id_shop)
	{
		$payment_modules = array();  
		
		$modules = Module::getModulesOnDisk(true);
		$moduleManagerBuilder = ModuleManagerBuilder::getInstance();
		$moduleRepository = $moduleManagerBuilder->buildRepository();

		foreach ($modules as $module) 
		{
			$addonModule = $moduleRepository->getModule($module->name);
						if ($addonModule->attributes->get('parent_class') != 'PaymentModule') continue;

						if (!$module->id) continue;
			
			if (!get_class($module) == 'SimpleXMLElement') {
				$module->country = array();
			}

			$sql = new DbQuery();
			$sql->select('`id_country`');
			$sql->from('module_country');
			$sql->where('`id_module` = '.(int)$module->id);
			$sql->where('`id_shop` = '.(int)$id_shop);
			$countries = Db::getInstance()->executeS($sql);
			foreach ($countries as $country) {
				$module->country[] = $country['id_country'];
			}

			if (!get_class($module) == 'SimpleXMLElement') {
				$module->currency = array();
			}

			$sql = new DbQuery();
			$sql->select('`id_currency`');
			$sql->from('module_currency');
			$sql->where('`id_module` = '.(int)$module->id);
			$sql->where('`id_shop` = '.(int)$id_shop);
			$currencies = Db::getInstance()->executeS($sql);
			foreach ($currencies as $currency) {
				$module->currency[] = $currency['id_currency'];
			}

			if (!get_class($module) == 'SimpleXMLElement') {
				$module->group = array();
			}

			$sql = new DbQuery();
			$sql->select('`id_group`');
			$sql->from('module_group');
			$sql->where('`id_module` = '.(int)$module->id);
			$sql->where('`id_shop` = '.(int)$id_shop);
			$groups = Db::getInstance()->executeS($sql);
			foreach ($groups as $group) {
				$module->group[] = $group['id_group'];
			}

			if (!get_class($module) == 'SimpleXMLElement') {
				$module->reference = array();
			}
			$sql = new DbQuery();
			$sql->select('`id_reference`');
			$sql->from('module_carrier');
			$sql->where('`id_module` = '.(int)$module->id);
			$sql->where('`id_shop` = '.(int)$id_shop);
			$carriers = Db::getInstance()->executeS($sql);
			foreach ($carriers as $carrier) {
				$module->reference[] = $carrier['id_reference'];
			}

			$payment_modules[] = $module;
		}
		return $payment_modules;
	}

	public static function remove_phone($message)
	{
		return preg_replace('/([0-9]+[\- ]?[0-9]+)/','',$message);		
	}
	
	public static function remove_email($message)
	{
		return preg_replace('/([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)/','',$message);		
	}

}
