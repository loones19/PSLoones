<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
if (!defined('_PS_VERSION_'))
	exit;

include_once(_PS_ROOT_DIR_ . '/modules/agilemultipleseller/agilemultiplesellermailer.php');
include_once(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/SellerInfo.php");


class AgileMultipleSeller extends Module {
	const FIELDS_CONFIG_SQL_FILE = 'fields_config.sql';
	const CUSTOM_FIELDS_CONFIG_SQL_FILE = 'custom/custom_fields_config.sql';
	
	const   ORDER_ORIGIN_PRESTASHOP = 0;
	const   ORDER_ORIGIN_EBAY = 1;

	const PAYMENT_MODE_STORE = 3; 	const PAYMENT_MODE_SELLER = 1;
	const PAYMENT_MODE_BOTH = 2; 
	const CART_MODE_MULTIPLE_SELLER = 0;
	const CART_MODE_SINGLE_SELLER = 1;
	
	const SUBCART_SESSION_KEY = 'agile_subcart_id_session_key';

	protected	$_html = '';
	protected $_postErrors = array();
	
	protected static $_tabs = array( 
		'AdminCatalog' => array('view'=>1, 'add'=>1, 'edit'=>1, 'delete'=>1)
		,'AdminCategories' => array('view'=>1, 'add'=>1, 'edit'=>1, 'delete'=>1)
		,'AdminCarts' => array('view'=>1, 'add'=>1, 'edit'=>1, 'delete'=>0)
		,'AdminProducts' => array('view'=>1, 'add'=>1, 'edit'=>1, 'delete'=>1)
		,'AdminParentManufacturers' => array('view'=>1, 'add'=>1, 'edit'=>1, 'delete'=>1)
		,'AdminManufacturers' => array('view'=>1, 'add'=>1, 'edit'=>1, 'delete'=>1)		
		,'AdminSuppliers' => array('view'=>1, 'add'=>1, 'edit'=>1, 'delete'=>1)
		,'AdminFeatures' => array('view'=>1, 'add'=>1, 'edit'=>1, 'delete'=>1)
		,'AdminAttachments' => array('view'=>1, 'add'=>1, 'edit'=>1, 'delete'=>1)
		,'AdminAttributesGroups' => array( 'view'=>1, 'add'=>1, 'edit'=>1, 'delete'=>1)
		,'AdminParentAttributesGroups' => array('view'=>1, 'add'=>1, 'edit'=>1, 'delete'=>1)
		,'AdminAttributeGenerator' => array('view'=>1, 'add'=>1, 'edit'=>1, 'delete'=>1)
		
		,'SELL' => array('view'=>1, 'add'=>0, 'edit'=>0, 'delete'=>0)
		,'CONFIGURE' => array('view'=>1, 'add'=>0, 'edit'=>0, 'delete'=>0)
		,'IMPROVE' => array('view'=>1, 'add'=>0, 'edit'=>0, 'delete'=>0)

		,'AdminOrders' => array('view'=>1, 'add'=>1, 'edit'=>1, 'delete'=>1)
		,'AdminReturn' => array('view'=>1, 'add'=>1, 'edit'=>1, 'delete'=>1)
		,'AdminOrderMessage' => array('view'=>1, 'add'=>1, 'edit'=>1, 'delete'=>1)
		
		,'AdminCustomers' => array('view'=>1, 'add'=>1, 'edit'=>1, 'delete'=>0)
		,'AdminGroups' => array('view'=>0, 'add'=>0, 'edit'=>0, 'delete'=>0)
		,'AdminAddresses' => array('view'=>1, 'add'=>1, 'edit'=>1, 'delete'=>0)
		,'AdminParentCustomerThreads' => array('view'=>1, 'add'=>1, 'edit'=>1, 'delete'=>0)
		,'AdminCustomerThreads' => array('view'=>1, 'add'=>1, 'edit'=>1, 'delete'=>0)


		,'AdminPriceRule' => array('view'=>0, 'add'=>0, 'edit'=>0, 'delete'=>0)
		,'AdminSpecificPriceRule' => array('view'=>0, 'add'=>0, 'edit'=>0, 'delete'=>0)
		,'AdminCartRules' => array('view'=>0, 'add'=>0, 'edit'=>0, 'delete'=>0)
		
		,'AdminCarriers' => array('view'=>1, 'add'=>1, 'edit'=>1, 'delete'=>1)
		,'AdminRangePrice' => array('view'=>1, 'add'=>1, 'edit'=>1, 'delete'=>1)
		,'AdminRangeWeight' => array( 'view'=>1, 'add'=>1, 'edit'=>1, 'delete'=>1)
		
		,'AdminMessages' => array('view'=>1, 'add'=>0, 'edit'=>1, 'delete'=>0)
		,'AdminReturn' => array( 'view'=>1, 'add'=>0, 'edit'=>0, 'delete'=>0)
		,'AdminEmployees' => array( 'view'=>0, 'add'=>0, 'edit'=>0, 'delete'=>0)
		,'AdminSearch' => array('view'=>1, 'add'=>0, 'edit'=>0, 'delete'=>0)
		
		,'AdminParentThemes' => array( 'view'=>1, 'add'=>0, 'edit'=>0, 'delete'=>0)
		,'AdminCMSContent' => array( 'view'=>1, 'add'=>1, 'edit'=>1, 'delete'=>1)
		,'AdminCMS' => array('view'=>1, 'add'=>1, 'edit'=>1, 'delete'=>1)
		,'AdminCMSCategories' => array('view'=>1, 'add'=>0, 'edit'=>0, 'delete'=>0)
		
		,'AdminOrderProducts' => array( 'view'=>1, 'add'=>0, 'edit'=>1, 'delete'=>0)
		,'AdminBulkApproval' => array( 'view'=>0, 'add'=>0, 'edit'=>0, 'delete'=>0)
		,'AdminSellerinfos' => array( 'view'=>1, 'add'=>0, 'edit'=>1, 'delete'=>0)
		,'AdminSellerPaymentinfos' => array( 'view'=>1, 'add'=>1, 'edit'=>1, 'delete'=>1)
		,'AgileSellerTypes' => array( 'view'=>0, 'add'=>0, 'edit'=>0, 'delete'=>0)
				,'AdminParentOrders' => array( 'view'=>1, 'add'=>0, 'edit'=>0, 'delete'=>0)
		,'AdminParentCustomer' => array( 'view'=>1, 'add'=>0, 'edit'=>0, 'delete'=>0)
		,'AdminParentShipping' => array( 'view'=>1, 'add'=>0, 'edit'=>0, 'delete'=>0)
		,'AdminParentPreferences' => array( 'view'=>1, 'add'=>0, 'edit'=>0, 'delete'=>0)
				,'AdminCommissions' => array( 'view'=>1, 'add'=>0, 'edit'=>0, 'delete'=>0)
		,'AdminCommissionBalances' => array( 'view'=>1, 'add'=>0, 'edit'=>0, 'delete'=>0)
		,'AdminBaseCommissionRates' => array( 'view'=>1, 'add'=>0, 'edit'=>0, 'delete'=>0)
		,'AdminRangeCommissionRates' => array( 'view'=>1, 'add'=>0, 'edit'=>0, 'delete'=>0)
				,'AdminSellerMessages' => array('view'=>1, 'add'=>0, 'edit'=>0, 'delete'=>0)
		,'AdminSlips' => array('view'=>1, 'add'=>1, 'edit'=>1, 'delete'=>0)
		,'AdminTools' => array('view'=>1, 'add'=>0, 'edit'=>0, 'delete'=>0)
		,'AdminImport' => array('view'=>1, 'add'=>1, 'edit'=>1, 'delete'=>1)
		
		,'AdminStates' => array('view'=>1, 'add'=>0, 'edit'=>0, 'delete'=>0)
		,'AdminSpecificPriceRule' => array('view'=>1, 'add'=>1, 'edit'=>1, 'delete'=>1)
		
		,'AgileModulesParent' => array('view'=>1, 'add'=>0, 'edit'=>0, 'delete'=>0)

		);
	
	
	function __construct()
	{
		$this->agile_configs = Configuration::getMultiple($this->getConfigKeys());	
		$this->agile_dependencies = array('agilekernel' => '1.7.1.0'); 
		$this->agile_newfiles = array(
			'src/Adapter/AgileMultipleSeller/LocationProductSearchProvider.php' => array('1.7x'=>'src/Adapter/AgileMultipleSeller/LocationProductSearchProvider.php', '1.6x' => 'src/Adapter/AgileMultipleSeller/LocationProductSearchProvider.php')
			,'src/Adapter/AgileMultipleSeller/SellerProductSearchProvider.php' => array('1.7x'=>'src/Adapter/AgileMultipleSeller/SellerProductSearchProvider.php', '1.6x' => 'src/Adapter/AgileMultipleSeller/SellerProductSearchProvider.php')
			);

		$this->name = 'agilemultipleseller';
		$this->isAgileKernelCompatible = true;
		$this->tab = 'front_office_features';
		$this->author = 'addons-modules.com';
		$this->version = '3.7.3.2';
		$this->ps_versions_compliancy = array('min' => '1.7', 'max' => '1.8');
		$this->dependencies = array('agilekernel');
		$this->bootstrap = true;

		parent::__construct();
		
		$this->displayName = $this->l('Agile Multiple Seller module');
		$this->description = $this->l('This module will turn your normal PrestaShop store into a multiple seller/vendor marketplace. With this module, you will be able to allow anyone sign up as seller on your store to sell products with your store.');
		
	}

	private function getConfigKeys()
	{
		$configKeys = array('AGILE_MS_PROFILE_ID','AGILE_MS_CUSTOMER_SELLER','AGILE_MS_SELLER_APPROVAL','AGILE_MS_EDIT_CATEGORY','AGILE_MS_PAYMENT_MODE', 'AGILE_MS_CART_MODE','AGILE_MS_SELLER_TAB','AGILE_MS_SELLER_TERMS','AGILE_MS_PRODUCT_APPROVAL','AGILE_MS_PRODUCT_APPROVAL_NOTICE','AGILE_MS_PRODUCT_COPY','AGILE_MS_PRODUCT_IMAGE_NUMBER','AGILE_MS_MYSELLER_URL_DIRECTORY','AGILE_MS_SELLER_PANEL_WITHLEFT','AGILE_MS_SELLER_PANEL_WITHRIGHT','AGILE_MS_ALLOW_REGISTER_ATHOME','AGILE_MS_SELLER_BACK_OFFICE','AGILE_MS_SELLER_INFO_TAB_STYLE','AGILE_MS_SELLER_INFO_TAB_GMAP','AGILE_MS_SELLER_CHOOSE_THEME','AGILE_MS_IS_MANUFACTURER','AGILE_MS_IS_SUPPLIER');
				for ($i = 1; $i <= 10; $i++)$configKeys[] = sprintf('AGILE_MS_SELLER_TEXT%s', $i);
		for ($i = 1; $i <= 2; $i++)$configKeys[] = sprintf('AGILE_MS_SELLER_HTML%s', $i);
		for ($i = 1; $i <= 10; $i++)$configKeys[] = sprintf('AGILE_MS_SELLER_NUMBER%s', $i);
		for ($i = 1; $i <= 5; $i++)$configKeys[] = sprintf('AGILE_MS_SELLER_DATE%s', $i);
		for ($i = 1; $i <= 15; $i++)$configKeys[] = sprintf('AGILE_MS_SELLER_STRING%s', $i);
		return $configKeys;
	}

	function install()
	{
		@set_time_limit(600);

				if(!Module::isInstalled('agilekernel'))
		{
			$this->_errors[] = $this->l('You have to install Agile Kernel module before installing this module. The download link of this module should have been included your download email for your order. If you can not find it, please request by email to support@addons-modules.com with your order #.');
			return false;
		}

		if(!$this->AgilePreinstall())return false;
		
				if(file_exists(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/override/classes") && !file_exists(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/override/classes__"))
		{
			rename(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/override/classes", _PS_ROOT_DIR_ . "/modules/agilemultipleseller/override/classes__");
		}
		if(file_exists(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/override/controllers") && !file_exists(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/override/controllers__"))
		{
			rename(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/override/controllers", _PS_ROOT_DIR_ . "/modules/agilemultipleseller/override/controllers__");
		}
		
		
				if  (parent::install() == false )
		{
			$this->_errors[] = $this->l('Install error - call parent::install()');
			return false;
		}
		
		$this->add_fields_for_upgrade();
		$this->build_index();
		

		if(!AgileInstaller::sql_install(dirname(__FILE__).'/'.self::FIELDS_CONFIG_SQL_FILE) || 
			!AgileInstaller::sql_install(dirname(__FILE__).'/'.self::CUSTOM_FIELDS_CONFIG_SQL_FILE))
		{
			$this->_errors[] = $this->l('Install error - custom fields ');
			return false;
		}
		if(!$this->register_tabs())
		{
			$this->_errors[] = $this->l('Install error - register tabs ');
			return false;
		}
		$pid = $this->createLinkedProfile();
		AgileInstaller::init_profile_prmission_for_existing_tabs($pid, 0,0,0,0);

				$this->set_permissions($pid, self::$_tabs);

	    		if  (Configuration::updateValue('AGILE_MS_PROFILE_ID', $pid) == false
			OR $this->AgileSetDefaultConfig('AGILE_MS_CUSTOMER_SELLER', 1) == false
			OR $this->AgileSetDefaultConfig('AGILE_MS_SELLER_APPROVAL', 0) == false
			OR $this->AgileSetDefaultConfig('AGILE_MS_EDIT_CATEGORY', 0) == false
			OR $this->AgileSetDefaultConfig('AGILE_MS_PAYMENT_MODE', AgileMultipleSeller::PAYMENT_MODE_STORE) == false
			OR $this->AgileSetDefaultConfig('AGILE_MS_CART_MODE', 0) == false
			OR $this->AgileSetDefaultConfig('AGILE_MS_SELLER_TAB', 1) == false
			OR $this->AgileSetDefaultConfig('AGILE_MS_SELLER_TERMS', 3) == false
			OR $this->AgileSetDefaultConfig('AGILE_MS_PRODUCT_APPROVAL', 0) == false
			OR $this->AgileSetDefaultConfig('AGILE_MS_PRODUCT_APPROVAL_NOTICE', 0) == false
			OR $this->AgileSetDefaultConfig('AGILE_MS_PRODUCT_COPY', 0) == false
			OR $this->AgileSetDefaultConfig('AGILE_MS_PRODUCT_IMAGE_NUMBER', 0) == false
			OR $this->AgileSetDefaultConfig('AGILE_MS_MYSELLER_URL_DIRECTORY', 'my-seller-account') == false			    
			OR $this->AgileSetDefaultConfig('AGILE_MS_SELLER_PANEL_WITHLEFT', 0) == false			    
			OR $this->AgileSetDefaultConfig('AGILE_MS_SELLER_PANEL_WITHRIGHT', 0) == false			    
			OR $this->AgileSetDefaultConfig('AGILE_MS_ALLOW_REGISTER_ATHOME', 0) == false
			OR $this->AgileSetDefaultConfig('AGILE_MS_SELLER_BACK_OFFICE', 0) == false
			OR $this->AgileSetDefaultConfig('AGILE_MS_SELLER_INFO_TAB_STYLE', 0) == false
			OR $this->AgileSetDefaultConfig('AGILE_MS_SELLER_INFO_TAB_GMAP', 1) == false
			OR $this->AgileSetDefaultConfig('AGILE_MS_SELLER_CHOOSE_THEME', 0) == false
			OR $this->AgileSetDefaultConfig('AGILE_MS_IS_MANUFACTURER', 0) == false
			OR $this->AgileSetDefaultConfig('AGILE_MS_IS_SUPPLIER', 0) == false
		)
		{
			$this->_errors[] = $this->l('Install error - initialize configuration settings');
			return 	false;
		}

				for ($i = 1; $i <= 10; $i++)$this->AgileSetDefaultConfig(sprintf('AGILE_MS_SELLER_TEXT%s', $i), 0);
		for ($i = 1; $i <= 2; $i++)$this->AgileSetDefaultConfig(sprintf('AGILE_MS_SELLER_HTML%s', $i), 0);
		for ($i = 1; $i <= 10; $i++)$this->AgileSetDefaultConfig(sprintf('AGILE_MS_SELLER_NUMBER%s', $i), 0);
		for ($i = 1; $i <= 5; $i++)$this->AgileSetDefaultConfig(sprintf('AGILE_MS_SELLER_DATE%s', $i), 0);
		for ($i = 1; $i <= 15; $i++)$this->AgileSetDefaultConfig(sprintf('AGILE_MS_SELLER_STRING%s', $i), 0);
		

        		if (!$this->registerHook('actionCartSave')
			OR !$this->registerHook('displayCustomerAccountFormTop')
			OR !$this->registerHook('actionValidateOrder')
			OR !$this->registerHook('displayProductExtraContent') 
			OR !$this->registerHook('actionOrderStatusUpdate')
			OR !$this->registerHook('actionHtaccessCreate')
			OR !$this->registerHook('actionCustomerAccountAdd')
			OR !$this->registerHook('actionCarrierUpdate')
			OR !$this->registerHook('displayHeader')
			OR !$this->registerHook('displayHome')
			OR !$this->registerHook('actionProductAdd')
			OR !$this->registerHook('actionProductUpdate')
			OR !$this->registerHook('displayAdminProductAction')
			OR !$this->registerHook('actionAdminProductsListingFieldsModifier')
			OR !$this->registerHook('actionAdminProductsListingResultsModifier')
			OR !$this->registerHook('displayOverrideTemplate')		
			OR !$this->registerHook('displayBackOfficeHeader')	
			OR !$this->registerHook('displayCustomerAccount')	
			OR !$this->registerHook('displaymyAccountBlock')	
			OR !$this->registerHook('actionOrderReturn')
		)
		{
			$this->_errors[] = $this->l('Install error - registering hooks');
			return false;
		}


		try
		{		
			$this->update_sellerinfo_lang_data();
		
			$this->hookActionHtaccessCreate(array('install'=>1));

			$this->AgileSetDefaultConfig('AGILE_MS_ADMIN_FOLDER_NAME', AgileInstaller::detect_admin_folder($_SERVER['SCRIPT_FILENAME']));
			Db::getInstance()->Execute("UPDATE " . _DB_PREFIX_ . "sellerinfo SET theme_name='classic' WHERE IFNULL(theme_name,'') = ''");
		
			$this->assign_existing_objects();

			Autoload::getInstance()->generateIndex();

			$this->upgradeAgileSellerPaymentInfoDataVer3201();

			$adminfolder = AgileInstaller::detect_admin_folder($_SERVER['SCRIPT_FILENAME']);
			AgileInstaller::install_newfiles($this->agile_newfiles, $this->name, $adminfolder, 2);

						$this->install_otherfiles();
		
						if(defined('_IS_AGILE_DEV_') &&  _IS_AGILE_DEV_ == 1)
			{
				AgileHelper::recurse_copy(_PS_ROOT_DIR_ . "/" . AgileInstaller::detect_admin_folder($_SERVER['SCRIPT_FILENAME']) . "/filemanager", _PS_ROOT_DIR_ . "/modules/agilemultipleseller/filemanager", false);
			}

						$this->install_filemanager();
			
						$this->install_adapters();

						$this->install_OrderController();
			
			AgileInstaller::ensureSellerReadModulePermission(Configuration::get('AGILE_MS_PROFILE_ID'), array('READ'));
		}
		catch(Exception $e)
		{
			$this->_errors[] = $this->l('Install error - ' . $e->getMessage());
			return false;			
		}
		
		copy(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/images/en-default-small_default.jpg", _PS_ROOT_DIR_ . "/img/p/en-default-small_default.jpg"); 
		
		return true;
	}
	
	public function uninstall()
	{   
		@set_time_limit(300);
				include_once(_PS_ROOT_DIR_ . '/modules/agilekernel/agilekernel.php');
		$ak = new AgileKernel();
		$this->_errors = $ak->uninstall_shared_override($this->name);
		if(!empty($this->_errors))
		{
			$this->_errors[] = $this->l('Error occured during uninstall shared override classes');
			return false;		
		}
				if(file_exists(_PS_ROOT_DIR_ . "/override/controllers/front/IndexController.php"))
			unlink(_PS_ROOT_DIR_ . "/override/controllers/front/IndexController.php");
		
						if(!parent::uninstall())
		{
			$this->_errors[] = $this->l('Error occured during uninstall the module - parent');
			return false;
		}

				$classes_flag = _PS_ROOT_DIR_ . "/modules/agilemultipleseller/override/classes.flg";
		if(file_exists($classes_flag )) unlink($classes_flag);
		$controllers_flag = _PS_ROOT_DIR_ . "/modules/agilemultipleseller/override/controllers.flg";
		if(file_exists($controllers_flag )) unlink($controllers_flag);

		if(!$this->unregister_tabs())
		{
			$this->_errors[] = $this->l('Error occured during unregistering tabs');
			return false;
		}
		
		return true;
	}
	
	private function install_filemanager()
	{
		$adminfolder = AgileInstaller::detect_admin_folder($_SERVER['SCRIPT_FILENAME']);
				$configfile = _PS_ROOT_DIR_ . "/" .  $adminfolder . "/filemanager/config/config.php";
		$content = file_get_contents($configfile);		
		$pos = strrpos($content, "agilemultipleseller");
		if($pos === false)
		{
			$handle = fopen($configfile, "a+");
			if(!$handle)return;
						fwrite($handle, "\r\nif(Module::isInstalled('agilemultipleseller'))include_once(_PS_ROOT_DIR_.'/modules/agilemultipleseller/filemanager.bo/config_override.php');\r\n");
			fclose($handle);
		}

				$dialogfile = _PS_ROOT_DIR_ . "/" .  $adminfolder . "/filemanager/dialog.php";
		$content = file_get_contents($dialogfile);		
		$pos = strrpos($content, "agilemultipleseller");
		$idx1 = strrpos($content, "* PrestaShop *");
		$idx2 = strrpos($content, "* END PrestaShop *");
		if($idx1 === false || $idx2 == false)
		{
									$content = file_get_contents(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/filemanager.bo/dialog.php.org");
		}
				$idx1 = strrpos($content, "* PrestaShop *");
		$idx2 = strrpos($content, "* END PrestaShop *");
		$override_code = file_get_contents(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/filemanager.bo/dialog_override.txt");
		if($idx1 > 1 && $idx2>0)
		{
			$newcontent = substr($content,0, $idx1 - 1) .  $override_code . substr($content, $idx2 + strlen("* END PrestaShop *") + 1);
			file_put_contents($dialogfile, $newcontent);
		}
	}
	
	
	private function install_agilebundles()
	{
		if(!defined('_IS_AGILE_DEV_') && file_exists(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/install/1.7x/src"))
			AgileHelper::recurse_copy(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/install/1.7x/src", _PS_ROOT_DIR_ . "/src");
	}
	
	private function upgradeAgileSellerPaymentInfoDataVer3201()
	{
				$sql = 'INSERT INTO  ' . _DB_PREFIX_ . 'agile_seller_paymentinfo (id_seller, module_name, id_currency, info1, info2, info3, info4, info5, info6, info7, info8, date_add, in_use)
				SELECT ap.id_seller, \'agilepaypaldaptive\' , ap.id_currency, ap.info1, ap.info2, ap.info3, ap.info4, ap.info5, ap.info6, ap.info7, ap.info8, ap.date_add, ap.in_use 
				FROM ' . _DB_PREFIX_ . 'agile_seller_paymentinfo ap
				LEFT JOIN ' . _DB_PREFIX_ . 'agile_seller_paymentinfo app ON (ap.id_seller=app.id_seller AND app.module_name=\'agilepaypaldaptive\')
				where ap.module_name=\'agilepaypal\'
				AND app.id_agile_seller_paymentinfo IS NULL
				';
		Db::getInstance()->Execute($sql);

				$sql = 'INSERT INTO  ' . _DB_PREFIX_ . 'agile_seller_paymentinfo (id_seller, module_name, id_currency, info1, info2, info3, info4, info5, info6, info7, info8, date_add, in_use)
				SELECT ap.id_seller, \'agilepaypalparallel\' , ap.id_currency, ap.info1, ap.info2, ap.info3, ap.info4, ap.info5, ap.info6, ap.info7, ap.info8, ap.date_add, ap.in_use 
				FROM ' . _DB_PREFIX_ . 'agile_seller_paymentinfo ap
				LEFT JOIN ' . _DB_PREFIX_ . 'agile_seller_paymentinfo app ON (ap.id_seller=app.id_seller AND app.module_name=\'agilepaypalparallel\')
				where ap.module_name=\'agilepaypal\'
				AND app.id_agile_seller_paymentinfo IS NULL
				';
		Db::getInstance()->Execute($sql);
		
				$sql = 'UPDATE ' . _DB_PREFIX_  . 'agile_seller_paymentinfo SET module_name=\'agilebankwire\' WHERE module_name=\'bankwire\'';
		Db::getInstance()->Execute($sql);

	}
		
	private function add_fields_for_upgrade()
	{
	    		Db::getInstance()->Execute('alter table ' . _DB_PREFIX_ . 'agile_subcart drop primary key, add primary key(id_seller, id_cart_parent, id_order);');
        AgileInstaller::add_field_ifnotexists('product_owner','approved','tinyint(1)','NULL');
        AgileInstaller::add_field_ifnotexists('sellerinfo','id_customer','bigint(11)','NULL');
        AgileInstaller::add_field_ifnotexists('sellerinfo','dni','varchar(128)','NULL');
		AgileInstaller::add_field_ifnotexists('sellerinfo','id_shop','bigint(11)','NULL');
		AgileInstaller::add_field_ifnotexists('sellerinfo','id_category_default','bigint(11)','NULL');
		AgileInstaller::add_field_ifnotexists('sellerinfo','id_manufacturer','bigint(11)','NULL');
		AgileInstaller::add_field_ifnotexists('sellerinfo','id_supplier','bigint(11)','NULL');
		AgileInstaller::add_field_ifnotexists('carrier_owner','is_default','tinyint(1)','NULL');
		AgileInstaller::add_field_ifnotexists('carrier_owner','date_add','datetime','NULL');
		AgileInstaller::add_field_ifnotexists('sellerinfo','id_sellertype1','bigint(11)','NULL');
		AgileInstaller::add_field_ifnotexists('sellerinfo','id_sellertype2','bigint(11)','NULL');
		AgileInstaller::add_field_ifnotexists('sellerinfo','theme_name','varchar(256)','NULL');		
		AgileInstaller::add_field_ifnotexists('sellerinfo','service_zipcodes','varchar(2000)','NULL');		
		AgileInstaller::add_field_ifnotexists('sellerinfo','service_distance','float','NULL');		
		AgileInstaller::add_field_ifnotexists('sellerinfo','payment_collection','tinyint(1)','NULL');		

		AgileInstaller::add_field_ifnotexists('sellerinfo_lang','meta_title','varchar(256)','NULL');
		AgileInstaller::add_field_ifnotexists('sellerinfo_lang','meta_keywords','varchar(256)','NULL');
		AgileInstaller::add_field_ifnotexists('sellerinfo_lang','meta_description','varchar(256)','NULL');		

		AgileInstaller::add_field_ifnotexists('agile_seller_paymentinfo','in_use','tinyint(1)','1');
		for ($i = 1; $i <= 10; $i++) {
			AgileInstaller::add_field_ifnotexists('sellerinfo_lang','ams_custom_text'.$i,'text','NULL');
		}
		for ($i = 1; $i <= 2; $i++) {
			AgileInstaller::add_field_ifnotexists('sellerinfo_lang','ams_custom_html'.$i,'text','NULL');
		}
		for ($i = 1; $i <= 10; $i++) {
			AgileInstaller::add_field_ifnotexists('sellerinfo','ams_custom_number'.$i,'float','NULL');
		}
		for ($i = 1; $i <= 5; $i++) {
			AgileInstaller::add_field_ifnotexists('sellerinfo','ams_custom_date'.$i,'date','NULL');
		}
		for ($i = 1; $i <= 15; $i++) {
			AgileInstaller::add_field_ifnotexists('sellerinfo','ams_custom_string'.$i,'varchar(1024)','NULL');
		}
	}
	
	public static function getCustomFields()
	{
		$custom_fields = array();
		for ($i = 1; $i <= 10; $i++) {
			$custom_fields[] = 'AGILE_MS_SELLER_TEXT'.$i;
		}
		for ($i = 1; $i <= 2; $i++) {
			$custom_fields[] = 'AGILE_MS_SELLER_HTML'.$i;
		}
		for ($i = 1; $i <= 10; $i++) {
			$custom_fields[] = 'AGILE_MS_SELLER_NUMBER'.$i;
		}
		for ($i = 1; $i <= 5; $i++) {
			$custom_fields[] = 'AGILE_MS_SELLER_DATE'.$i;
		}
		for ($i = 1; $i <= 15; $i++) {
			$custom_fields[] = 'AGILE_MS_SELLER_STRING'.$i;
		}
		return $custom_fields;
	}
	
	private function build_index()
	{		
				AgileInstaller::add_index_ifnotexists('sellerinfo','id_seller');
		AgileInstaller::add_index_ifnotexists('sellerinfo','id_customer');


		AgileInstaller::add_index_ifnotexists('category_owner','id_category');
		AgileInstaller::add_index_ifnotexists('category_owner','id_owner');

		AgileInstaller::add_index_ifnotexists('product_owner','id_product');
		AgileInstaller::add_index_ifnotexists('product_owner','id_owner');
		
		AgileInstaller::add_index_ifnotexists('customer_owner','id_customer');
		AgileInstaller::add_index_ifnotexists('customer_owner','id_owner');

		AgileInstaller::add_index_ifnotexists('order_owner','id_order');
		AgileInstaller::add_index_ifnotexists('order_owner','id_owner');

		AgileInstaller::add_index_ifnotexists('object_owner','id_object');
		AgileInstaller::add_index_ifnotexists('object_owner','id_owner');
		AgileInstaller::add_index_ifnotexists('object_owner','entity');
	}
	
	private function update_sellerinfo_lang_data()
	{
		Db::getInstance()->execute(
			'INSERT INTO '._DB_PREFIX_.'sellerinfo_lang (id_sellerinfo, id_lang, company, description, address1, address2, city)
		     SELECT id_sellerinfo, id_lang, si.company, si.description, address1, address2, city
			 FROM '._DB_PREFIX_.'lang pl
			 CROSS JOIN '._DB_PREFIX_.'sellerinfo si
			 WHERE NOT EXISTS (
			 SELECT \'x\'
			 FROM '._DB_PREFIX_.'sellerinfo_lang sil
			 WHERE si.id_sellerinfo = sil.id_sellerinfo
			 AND pl.id_lang = sil.id_lang)'
		);
	}	
	
	public function displayForm()
	{
		
		$to_install_classes = 0;
		$to_install_controllers = 0;

		$classes_flag = _PS_ROOT_DIR_ . "/modules/agilemultipleseller/override/classes.flg";
		if(!file_exists($classes_flag ))$to_install_classes = 1;		

		$controllers_flag = _PS_ROOT_DIR_ . "/modules/agilemultipleseller/override/controllers.flg";
		if(!file_exists($controllers_flag ))$to_install_controllers = 1;
		
		if($to_install_classes || $to_install_controllers)
		{
			$base_dir_ssl = Tools::getShopDomainSsl(true, true).__PS_BASE_URI__;
			$this->_html = $this->l('The module has been installed.') . '<br>';
			$this->_html .= $this->l('In order to make this module work properly, we need install some additional override classes and controllers') . '<br>';
			$this->_html .= '<img src="' .  Tools::getShopDomainSsl(true, true).__PS_BASE_URI__ . 'modules/agilekernel/img/processing.gif"><br>';
			$this->_html .= '
						<script type="text/javascript">
						$(document).ready(function(){
							$("#pInstallMsg").html("Classes:");
							$.ajax({
								url: "' . $base_dir_ssl. '" + "modules/agilemultipleseller/ajax_install_override_classes.php",
								type: "POST",
								success: function (data) {
									$("#pInstallMsg").html($("#pInstallMsg").html() + data + "<br>Controllers:");
									$.ajax({
										url: "' . $base_dir_ssl . '" + "modules/agilemultipleseller/ajax_install_override_controllers.php",
										type: "POST",
										success: function (data) {
											$("#pInstallMsg").html($("#pInstallMsg").html() + data);
											window.location.reload(true);
										}
									});
								}
							});
						});
						
						</script>
			';
			$this->_html .= '<p id="pInstallMsg">></p>';
			$this->_html .= $this->l('It may take minutes to install all override classes and controllers, please wait....') . '<br>';
			$this->_html .= $this->l('Once the override installation is done, configuration options will be displayed automatically.') . '<br>';
			return;
		}
		
		$conf_keys = array('AGILE_MS_SELLER_APPROVAL', 'AGILE_MS_custom_SELLER','AGILE_MS_EDIT_CATEGORY','AGILE_MS_PAYMENT_MODE','AGILE_MS_SELLER_TAB','AGILE_MS_CART_MODE','AGILE_MS_PRODUCT_APPROVAL','AGILE_MS_PRODUCT_APPROVAL_NOTICE','AGILE_MS_PRODUCT_COPY','AGILE_MS_PRODUCT_IMAGE_NUMBER','AGILE_MS_SELLER_TERMS','AGILE_MS_CUSTOMER_SELLER', 'AGILE_MS_SELLER_PANEL_WITHRIGHT','AGILE_MS_SELLER_PANEL_WITHLEFT','AGILE_MS_ALLOW_REGISTER_ATHOME','AGILE_MS_SELLER_BACK_OFFICE','AGILE_MS_SELLER_INFO_TAB_STYLE','AGILE_MS_SELLER_INFO_TAB_GMAP','AGILE_MS_SELLER_CHOOSE_THEME','AGILE_MS_IS_MANUFACTURER','AGILE_MS_IS_SUPPLIER');
		$conf_keys = array_merge($conf_keys, AgileMultipleSeller::getCustomFields());
		$conf = Configuration::getMultiple($conf_keys);
		$seller_tab= Tools::getValue('seller_tab', (array_key_exists('AGILE_MS_SELLER_TAB', $conf) ? $conf['AGILE_MS_SELLER_TAB'] : ''));

		$permissiontabid = Tab::getIdFromClassName('AdminAccess');
		$permissiontoken = Tools::getAdminToken('AdminAccess' .intval($permissiontabid).intval($this->context->cookie->id_employee));
		$profile = new Profile(intval(Configuration::get('AGILE_MS_PROFILE_ID')),$this->context->language->id);

		$this->context->controller->addCSS($this->_path.'css/agileglobal.css', 'all');
		$this->context->controller->addCSS($this->_path.'css/agilemultipleseller.css', 'all');

		$custom_lables = $this->getCustomLabels();
		$custom_hints = $this->getCustomHints();
		$array2_fieldvalue=array();
		$array2_field=array();
		for ($i = 1; $i <= 10; $i++) {
			$key = sprintf('AGILE_MS_SELLER_TEXT%s', $i);
			$field_name = sprintf('ams_custom_text%s', $i);
			$text = array_key_exists($field_name, $_POST) ? $_POST[$field_name] : (array_key_exists($key, $conf) ? $conf[$key] : '');
			$array2_fieldvalue[$field_name]= $text;
			array_push($array2_field, array('id'=> $field_name, 'name'=> $field_name, 'label'=> $custom_lables[$field_name], 'hint' =>$custom_hints[$field_name]));
		}

		$array3_fieldvalue=array();
		$array3_field=array();
		for ($i = 1; $i <= 2; $i++) {
			$key = sprintf('AGILE_MS_SELLER_HTML%s', $i);
			$field_name = sprintf('ams_custom_html%s', $i);
			$html = array_key_exists($field_name, $_POST) ? $_POST[$field_name] : (array_key_exists($key, $conf) ? $conf[$key] : '');
			$array3_fieldvalue[$field_name]= $html;
			array_push($array3_field, array('id'=> $field_name, 'name'=> $field_name, 'label'=> $custom_lables[$field_name], 'hint' =>$custom_hints[$field_name]));
		}

		$array4_fieldvalue=array();
		$array4_field=array();
		for ($i = 1; $i <= 10; $i++) {
			$key = sprintf('AGILE_MS_SELLER_NUMBER%s', $i);
			$field_name = sprintf('ams_custom_number%s', $i);
			$html = array_key_exists($field_name, $_POST) ? $_POST[$field_name] : (array_key_exists($key, $conf) ? $conf[$key] : '');
			$array4_fieldvalue[$field_name]= $html;
			array_push($array4_field, array('id'=> $field_name, 'name'=> $field_name, 'label'=> $custom_lables[$field_name], 'hint' =>$custom_hints[$field_name]));
		}

		$array5_fieldvalue=array();
		$array5_field=array();
		for ($i = 1; $i <= 5; $i++) {
			$key = sprintf('AGILE_MS_SELLER_DATE%s', $i);
			$field_name = sprintf('ams_custom_date%s', $i);
			$html = array_key_exists($field_name, $_POST) ? $_POST[$field_name] : (array_key_exists($key, $conf) ? $conf[$key] : '');
			$array5_fieldvalue[$field_name]= $html;
			array_push($array5_field, array('id'=> $field_name, 'name'=> $field_name, 'label'=> $custom_lables[$field_name], 'hint' =>$custom_hints[$field_name]));
		}

		$array6_fieldvalue=array();
		$array6_field=array();
		for ($i = 1; $i <= 15; $i++) {
			$key = sprintf('AGILE_MS_SELLER_STRING%s', $i);
			$field_name = sprintf('ams_custom_string%s', $i);
			$html = array_key_exists($field_name, $_POST) ? $_POST[$field_name] : (array_key_exists($key, $conf) ? $conf[$key] : '');
			$array6_fieldvalue[$field_name]= $html;
			array_push($array6_field, array('id'=> $field_name, 'name'=> $field_name, 'label'=> $custom_lables[$field_name], 'hint' =>$custom_hints[$field_name]));
		}

		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Settings'),
					'image' => $this->_path.'logo.png',
				),
				'input' => array(
					array(
						'type' => 'text',
						'label' => $this->l('Seller Terms & Conditions'),
						'class' => 'width10',
						'name' => 'seller_terms',
						'desc' =>  $this->l('Please enter the CMS page ID for Seller Terms & Conditions. Enter "0" if there is no Seller Terms & Conditions.')
					),
			
					array(
						'type' => 'htmlhr',
						'name' => 'seller_term_section',
						'values' => array(),
					),
					array(
						'type' => 'agile_radio',
						'label' => $this->l('Payment Collection Mode'),
						'name' => 'payment_mode',
						'values' => array(
							array(
								'id' => 'payment_mode_store',
								'value' => 3,
								'label' => $this->l('Store collects payments'),
								'p' =>array(
									$this->l('You can use any payment modules available in your store, but you will need to pay sellers\' account balances separately.')
									,$this->l('Even you choose other payment collection mode, you are able to allow some special customers always use Store Collects Payment mode, this can configured at seller business info page.')
									)
							),
							array(
								'id' => 'payment_mode_seller',
								'value' => 1,
								'label' => $this->l('Seller collects payments'),
								'p' => array (
									$this->l('Only customized (Agile Multiple Seller integrated) payment modules are supported for this payment collection mode'),
									$this->l('Seller will need to pay the store for any accounts owed (commissions) separately.')
								)
							),
							array(
								'id' => 'payment_mode_both',
								'value' => 2,
								'label' => $this->l('Both Store and Seller collect payment '),
								'p' => array(
									$this->l('Only customized (Agile Multiple Seller integrated) payment modules with split payment function are supported.<br>Payments among customers, sellers, and store are distributed automatically. No additional payment is required.'),
									'<a href="http://addons-modules.com/en/content/28-how-to-choose-payment" style="color:blue;text-decoration:underline;" target="_new">' . $this->l('Click here') . '</a>&nbsp;' . $this->l('to find more information on how to set the "Payment Collection Mode" and choose payment methods correctly.')
								)
							),
						),
					),
					array(
						'type' => 'htmlhr',
						'name' => 'seller_term_section',
						'values' => array(),
					),
					array(
						'type' => 'radio',
						'label' => $this->l('Shopping Cart Mode'),
						'name' => 'cart_mode',
						'values' => array(
							array(
								'id' => 'cart_mode_multipleseller',
								'value' => 0,
								'label' => $this->l('Products from multiple seller'),
							),
							array(
								'id' => 'cart_mode_singleseller',
								'value' => 1,
								'label' => $this->l('Product from single seller'),
							),
						),
						'desc' =>'<a href="http://addons-modules.com/en/content/28-how-to-choose-payment" style="color:blue;text-decoration:underline;" target="_new">' . $this->l('Click here') . '</a>&nbsp;' . $this->l(' to see section "D. Payemnt collection mode, Shipping Cart mode" for more details.')
					),

					array(
						'type' => 'htmlhr',
						'name' => 'shoppingcart_section',
						'values' => array(),
					),

				
					array(
						'type' => 'checkbox',
						'label' => $this->l('Listing Approval Required'),
						'name' => 'product',
						'values' => array(
							'query' => array(
								array(
									'id' => 'approval',
									'val' => 1,
									'name' => $this->l('Do you want approve seller products before they are listed?'),
								),
							),
							'id' => 'id',
							'name' => 'name'
						),
					),
				
					array(
						'type' => 'checkbox',
						'label' => $this->l('Approval Notification To Seller'),
						'name' => 'product',
						'values' => array(
							'query' => array(
								array(
									'id' => 'approval_notice',
									'val' => 1,
									'name' => $this->l('Do you want send product approval/disapproval to seller?'),
								),
							),
							'id' => 'id',
							'name' => 'name'
						),
					),

					array(
						'type' => 'checkbox',
						'label' => $this->l('Seller Copy Product'),
						'name' => 'product',
						'values' => array(
							'query' => array(
								array(
									'id' => 'copy',
									'val' => 1,
									'name' => $this->l('Do you allow seller to copy from main store products when add a new product?'),
								),
							),
							'id' => 'id',
							'name' => 'name'
						),
					),

					array(
						'type' => 'text',
							'label' => $this->l('Limit Product Image Number'),
						'class' => 'width10',
						'name' => 'product_image_number',
						'desc' =>  $this->l('Please enter the max number of product images seller can upload, Enter "0" if you do not want to set limit.')
					),
				
					array(
						'type' => 'htmlhr',
						'name' => 'approval_section',
						'values' => array(),
					),
					array(
						'type' => 'checkbox',
						'label' => $this->l('Allow customer to be a seller'),
						'name' => 'customer',
						'values' => array(
							'query' => array(
								array(
									'id' => 'seller',
									'val' => 1,
									'name' => $this->l('Allow customers to sign up for a sellers account and list products.'),
								),
							),
							'id' => 'id',
							'name' => 'name'
						),
					),
					array(
						'type' => 'checkbox',
						'label' => $this->l('Account Approval Required'),
						'name' => 'seller',
						'values' => array(
							'query' => array(
								array(
									'id' => 'approval',
									'val' => 1,
									'name' => $this->l('Sellers registering from the front office are required to be approved/activated by Admin.'),
								),
							),
							'id' => 'id',
							'name' => 'name'
						),
					),
					array(
						'type' => 'checkbox',
						'label' => $this->l('Link to a manufacturer'),
						'name' => 'is',
						'values' => array(
							'query' => array(
								array(
									'id' => 'manufacturer',
									'val' => 1,
									'name' => $this->l('Want to link a seller to a manufacturer and create a manufacturer automatically?'),
								),
							),
							'id' => 'id',
							'name' => 'name'
						),
					),
					array(
						'type' => 'checkbox',
						'label' => $this->l('Link to a supplier'),
						'name' => 'is',
						'values' => array(
							'query' => array(
								array(
									'id' => 'supplier',
									'val' => 1,
									'name' => $this->l('Want to link a seller to a supplier and create a supplier automatically?'),
								),
							),
							'id' => 'id',
							'name' => 'name'
						),
					),
							
					array(
						'type' => 'htmlhr',
						'name' => 'customer_seller_section',
						'values' => array(),
					),
					array(
						'type' => 'checkbox',
						'label' => $this->l('Seller back office access'),
						'name' => 'seller',
						'values' => array(
							'query' => array(
								array(
									'id' => 'back_office',
									'val' => 1,
									'name' => $this->l('If you want to allow seller to access back office'),
								),
							),
							'id' => 'id',
							'name' => 'name'
						),
					),
				
					array(
						'type' => 'htmlhr',
						'name' => 'seller_choose_theme_section',
						'values' => array(),
					),
				
				
					array(
						'type' => 'checkbox',
						'label' => $this->l('Seller Choose Theme'),
						'name' => 'seller',
						'form_group_class' => 'seller_choose_theme ' . (Module::isInstalled('agilemultipleshop') ? '' : 'hidden'),
						'values' => array(
							'query' => array(
								array(
									'id' => 'choose_theme',
									'val' => 1,
									'name' => $this->l('If you want to allow seller to choose  virtual shop theme'),
								),
							),
							'id' => 'id',
							'name' => 'name'
						),
					),

					array(
						'type' => 'htmlhr',
						'name' => 'seller_info_section',
						'values' => array(),
					),
				
				
					array(
						'type' => 'checkbox',
						'label' => $this->l('Seller Products At Store Home'),
						'name' => 'allow_register',
						'values' => array(
							'query' => array(
								array(
									'id' => 'athome',
									'val' => 1,
									'name' => $this->l('If you want to allow seller to register product at store Home category'),
								),
							),
							'id' => 'id',
							'name' => 'name'
						),
					),
					array(
						'type' => 'htmlhr',
						'name' => 'register_athome_section',
						'values' => array(),
					),
					array(
						'type' => 'checkbox',
						'label' => $this->l('Edit product category'),
						'name' => 'edit',
						'values' => array(
							'query' => array(
								array(
									'id' => 'category',
									'val' => 1,
									'name' => $this->l('Allow the seller the following permissions for product categories:  add/edit/enable/disable.'),
								),
							),
							'id' => 'id',
							'name' => 'name'
						),
					),
					array(
						'type' => 'htmlhr',
						'name' => 'edit_category_section',
						'values' => array(),
					),
					array(
						'type' => 'checkbox',
						'label' => $this->l('Seller Info Tab'),
						'name' => 'seller',
						'values' => array(
							'query' => array(
								array(
									'id' => 'tab',
									'val' => 1,
									'name' => $this->l('Checking this box adds a new tab on the Product Detail page in the Front Office (store), for seller added products. This new tab displays the seller\'s information (i.e. address and phone number), as well as the seller\'s location in a Google Maps window if they so choose.'),
								),
							),
							'id' => 'id',
							'name' => 'name'
						),
					),
					array(
						'type' => 'radio',
						'label' => $this->l('Google Map Sisplay'),
						'name' => 'seller_info_tab_gmap',
						'form_group_class' => 'seller_info_tab_gmap ' . ($seller_tab ? '' : 'hidden'),
						'values' => array(
							array(
								'id' => 'gmap_yes',
								'value' => 1,
								'label' => $this->l('Yes'),
								'p' => 'Choose this if want Google map showing on Seller Info tab on public product listing page.'
							),
							array(
								'id' => 'gmap_no',
								'value' => 0,
								'label' => $this->l('No'),
								'p' => 'Choose this if do not want Google map showing on Seller Info tab on public product listing page.'
							),
						),
					),

					array(
						'type' => 'htmlhr',
						'name' => 'seller_info_section',
						'values' => array(),
					),
					array(
						'type' => 'checkboxgroup',
						'label' => $this->l('Custom Fields'),
						'name' => 'customized_fields',
						'header' => $this->l('You can choose to use following custom fileds for additional informaiton of seller. Tips: You can use PrestaShop translation funciton to change the display name of each field. '),
						'values' => array(
							array(
								'section_name' => 'customize_text',
								'items' => $array2_field
							),
							array(
								'section_name' => 'customize_html',
								'items' => $array3_field
							),
							array(
								'section_name' => 'customize_number',
								'items' => $array4_field
							),
							array(
								'section_name' => 'customize_date',
								'items' => $array5_field
							),
							array(
								'section_name' => 'customize_string',
								'items' => $array6_field
							),
						),
					),
					array(
						'type' => 'htmlhr',
						'name' => 'customized_field_section',
						'values' => array(),
					),
					array(
						'type' => 'hidden',
						'name' => 'profile_id',
					),
					array(
						'type' => 'text',
						'label' => $this->l('Linked Profile'),
						'readonly' => 1,
						'name' => 'profilename',
						'desc' => $this->l('By installing this module, a new employee profile called "agilemultipleseller" was created and is now linked to this module. You can view this new profile by going to the "Administration" -> "Employees" tab.') 
					),
				),
				'submit' => array(
					'title' => $this->l('Save'),
				)
			)
		);
		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table =  $this->name;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->module = $this;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submitSetting';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => array_merge($this->getConfigFieldsValues($profile,$conf), $array2_fieldvalue,$array3_fieldvalue, $array4_fieldvalue,$array5_fieldvalue, $array6_fieldvalue),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);
		$this->_html .=  $helper->generateForm(array($fields_form));

		$this->_html .= ' 
			<fieldset id="instructions">
			<legend><img src="'.$this->_path.'logo.png" alt="" title="" />'.$this->l('Maintenance Notes').'</legend>
		    <div class="alert alert-info">' . 
    		        $this->l('1. You can view and/or modify the permissions for the "agilemultipleseller" profile by going to the "Administration" -> "Permissions" tab and selecting "agilemultipleseller" from the list.') . '&nbsp;<a href="./index.php?tab=AdminAccess&profile='. $profile->id . '&token=' . $permissiontoken . '" style="color:Blue;text-decoration:underline;">' . $this->l('Or, just click here.') . '</a><br />
	    	        <font color="red">' . $this->l('2.  Please DO NOT DELETE this profile').'<br /></font>
				</div>
			</fieldset>
			';
		$this->context->controller->addJS(array(
				_PS_ROOT_DIR_.'/modules/agilemultipleseller/js/agile_admin.js',
				));
	}
		
   	public function getConfigFieldsValues($profile,$conf)
	{
		return $array1=array(
			'seller_terms' => Tools::getValue('seller_terms', (array_key_exists('AGILE_MS_SELLER_TERMS', $conf) ? $conf['AGILE_MS_SELLER_TERMS'] : '')),
			'payment_mode' => Tools::getValue('payment_mode', (array_key_exists('AGILE_MS_PAYMENT_MODE', $conf) ? $conf['AGILE_MS_PAYMENT_MODE'] : '')),
			'cart_mode' => Tools::getValue('cart_mode', (array_key_exists('AGILE_MS_CART_MODE', $conf) ? $conf['AGILE_MS_CART_MODE'] : '')),
			'product_approval' => Tools::getValue('product_approval', (array_key_exists('AGILE_MS_PRODUCT_APPROVAL', $conf) ? $conf['AGILE_MS_PRODUCT_APPROVAL'] : '')),
			'product_approval_notice' => Tools::getValue('product_approval_notice', (array_key_exists('AGILE_MS_PRODUCT_APPROVAL_NOTICE', $conf) ? $conf['AGILE_MS_PRODUCT_APPROVAL_NOTICE'] : '')),
			'product_copy' => Tools::getValue('product_copy', (array_key_exists('AGILE_MS_PRODUCT_COPY', $conf) ? $conf['AGILE_MS_PRODUCT_COPY'] : '')),
			'product_image_number' => Tools::getValue('product_image_number', (array_key_exists('AGILE_MS_PRODUCT_IMAGE_NUMBER', $conf) ? $conf['AGILE_MS_PRODUCT_IMAGE_NUMBER'] : '')),
			'customer_seller' => Tools::getValue('customer_seller', (array_key_exists('AGILE_MS_CUSTOMER_SELLER', $conf) ? $conf['AGILE_MS_CUSTOMER_SELLER'] : '')),
			'seller_approval' => Tools::getValue('seller_approval', (array_key_exists('AGILE_MS_SELLER_APPROVAL', $conf) ? $conf['AGILE_MS_SELLER_APPROVAL'] : '')),
			'seller_back_office' => Tools::getValue('seller_back_office', (array_key_exists('AGILE_MS_SELLER_BACK_OFFICE', $conf) ? $conf['AGILE_MS_SELLER_BACK_OFFICE'] : 0)),
			'allow_register_athome' => Tools::getValue('allow_register_athome', (array_key_exists('AGILE_MS_ALLOW_REGISTER_ATHOME', $conf) ? $conf['AGILE_MS_ALLOW_REGISTER_ATHOME'] : '')),
			'edit_category' => Tools::getValue('edit_category', (array_key_exists('AGILE_MS_EDIT_CATEGORY', $conf) ? $conf['AGILE_MS_EDIT_CATEGORY'] : '')),
			'seller_tab' => Tools::getValue('seller_tab', (array_key_exists('AGILE_MS_SELLER_TAB', $conf) ? $conf['AGILE_MS_SELLER_TAB'] : '')),
			'seller_panel_withleft' => Tools::getValue('seller_panel_withleft', (array_key_exists('AGILE_MS_SELLER_PANEL_WITHLEFT', $conf) ? $conf['AGILE_MS_SELLER_PANEL_WITHLEFT'] : 0)),
			'seller_panel_withright' => Tools::getValue('seller_panel_withright', (array_key_exists('AGILE_MS_SELLER_PANEL_WITHRIGHT', $conf) ? $conf['AGILE_MS_SELLER_PANEL_WITHRIGHT'] : 0)),
			'profile_id' => $profile->id,
			'profilename' => $profile->name,
			'seller_info_tab_style' =>Tools::getValue('seller_info_tab_style', (array_key_exists('AGILE_MS_SELLER_INFO_TAB_STYLE', $conf) ? $conf['AGILE_MS_SELLER_INFO_TAB_STYLE'] : '')),
			'seller_info_tab_gmap' =>Tools::getValue('seller_info_tab_gmap', (array_key_exists('AGILE_MS_SELLER_INFO_TAB_GMAP', $conf) ? $conf['AGILE_MS_SELLER_INFO_TAB_GMAP'] : '')),
			'seller_choose_theme' =>Tools::getValue('seller_choose_theme', (array_key_exists('AGILE_MS_SELLER_CHOOSE_THEME', $conf) ? $conf['AGILE_MS_SELLER_CHOOSE_THEME'] : '')),
			'is_manufacturer' => Tools::getValue('is_manufacturer', (array_key_exists('AGILE_MS_IS_MANUFACTURER', $conf) ? $conf['AGILE_MS_IS_MANUFACTURER'] : '')),
			'is_supplier' => Tools::getValue('is_supplier', (array_key_exists('AGILE_MS_IS_SUPPLIER', $conf) ? $conf['AGILE_MS_IS_SUPPLIER'] : '')),
			);

	}

	
			
	public function displayConf()
	{
		$this->_html .= $this->displayConfirmation($this->l('Settings updated'));
	}

	public function displayErrors()
	{
		$nbErrors = sizeof($this->_postErrors);
		$this->_html .= '
		<div class="module_error alert alert-danger">
			<h3>'.($nbErrors > 1 ? $this->l('There are') : $this->l('There is')).' '.$nbErrors.' '.($nbErrors > 1 ? $this->l('errors') : $this->l('error')).'</h3>
			<ol>';
		foreach ($this->_postErrors AS $error)
			$this->_html .= '<li>'.$error.'</li>';
		$this->_html .= '
			</ol>
		</div>';
	}

	public function getCustomLabels($surfix='')
	{
		return array(
			'ams_custom_text1' => $this->l('Text1') . $surfix,
			'ams_custom_text2' => $this->l('Text2') . $surfix,
			'ams_custom_text3' => $this->l('Text3') . $surfix,
			'ams_custom_text4' => $this->l('Text4') . $surfix,
			'ams_custom_text5' => $this->l('Text5') . $surfix,
			'ams_custom_text6' => $this->l('Text6') . $surfix,
			'ams_custom_text7' => $this->l('Text7') . $surfix,
			'ams_custom_text8' => $this->l('Text8') . $surfix,
			'ams_custom_text9' => $this->l('Text9') . $surfix,
			'ams_custom_text10' => $this->l('Text10') . $surfix,
			'ams_custom_html1' => $this->l('Html1') . $surfix,
			'ams_custom_html2' => $this->l('Html2') . $surfix,
			'ams_custom_number1' => $this->l('Number1') . $surfix,
			'ams_custom_number2' => $this->l('Number2') . $surfix,
			'ams_custom_number3' => $this->l('Number3') . $surfix,
			'ams_custom_number4' => $this->l('Number4') . $surfix,
			'ams_custom_number5' => $this->l('Number5') . $surfix,
			'ams_custom_number6' => $this->l('Number6') . $surfix,
			'ams_custom_number7' => $this->l('Number7') . $surfix,
			'ams_custom_number8' => $this->l('Number8') . $surfix,
			'ams_custom_number9' => $this->l('Number9') . $surfix,
			'ams_custom_number10' => $this->l('Number10') . $surfix,
			'ams_custom_date1' => $this->l('Date1') . $surfix,
			'ams_custom_date2' => $this->l('Date2') . $surfix,
			'ams_custom_date3' => $this->l('Date3') . $surfix,
			'ams_custom_date4' => $this->l('Date4') . $surfix,
			'ams_custom_date5' => $this->l('Date5') . $surfix,
			'ams_custom_string1' => $this->l('String1') . $surfix,
			'ams_custom_string2' => $this->l('String2') . $surfix,
			'ams_custom_string3' => $this->l('String3') . $surfix,
			'ams_custom_string4' => $this->l('String4') . $surfix,
			'ams_custom_string5' => $this->l('String5') . $surfix,
			'ams_custom_string6' => $this->l('String6') . $surfix,
			'ams_custom_string7' => $this->l('String7') . $surfix,
			'ams_custom_string8' => $this->l('String8') . $surfix,
			'ams_custom_string9' => $this->l('String9') . $surfix,
			'ams_custom_string10' => $this->l('String10') . $surfix,
			'ams_custom_string11' => $this->l('String11') . $surfix,
			'ams_custom_string12' => $this->l('String12') . $surfix,
			'ams_custom_string13' => $this->l('String13') . $surfix,
			'ams_custom_string14' => $this->l('String14') . $surfix,
			'ams_custom_string15' => $this->l('String15') . $surfix,
		);
	}
		
	public function getCustomHints()
	{
		return array(
			'ams_custom_text1' => $this->l('Hints for Text1'),
			'ams_custom_text2' => $this->l('Hints for Text2'),
			'ams_custom_text3' => $this->l('Hints for Text3'),
			'ams_custom_text4' => $this->l('Hints for Text4'),
			'ams_custom_text5' => $this->l('Hints for Text5'),
			'ams_custom_text6' => $this->l('Hints for Text6'),
			'ams_custom_text7' => $this->l('Hints for Text7'),
			'ams_custom_text8' => $this->l('Hints for Text8'),
			'ams_custom_text9' => $this->l('Hints for Text9'),
			'ams_custom_text10' => $this->l('Hints for Text10'),
			'ams_custom_html1' => $this->l('Hints for Html1'),
			'ams_custom_html2' => $this->l('Hints for Html2'),
			'ams_custom_number1' => $this->l('Hints for Number1'),
			'ams_custom_number2' => $this->l('Hints for Number2'),
			'ams_custom_number3' => $this->l('Hints for Number3'),
			'ams_custom_number4' => $this->l('Hints for Number4'),
			'ams_custom_number5' => $this->l('Hints for Number5'),
			'ams_custom_number6' => $this->l('Hints for Number6'),
			'ams_custom_number7' => $this->l('Hints for Number7'),
			'ams_custom_number8' => $this->l('Hints for Number8'),
			'ams_custom_number9' => $this->l('Hints for Number9'),
			'ams_custom_number10' => $this->l('Hints for Number10'),
			'ams_custom_date1' => $this->l('Hints for Date1'),
			'ams_custom_date2' => $this->l('Hints for Date2'),
			'ams_custom_date3' => $this->l('Hints for Date3'),
			'ams_custom_date4' => $this->l('Hints for Date4'),
			'ams_custom_date5' => $this->l('Hints for Date5'),
			'ams_custom_string1' => $this->l('Hints for String1'),
			'ams_custom_string2' => $this->l('Hints for String2'),
			'ams_custom_string3' => $this->l('Hints for String3'),
			'ams_custom_string4' => $this->l('Hints for String4'),
			'ams_custom_string5' => $this->l('Hints for String5'),
			'ams_custom_string6' => $this->l('Hints for String6'),
			'ams_custom_string7' => $this->l('Hints for String7'),
			'ams_custom_string8' => $this->l('Hints for String8'),
			'ams_custom_string9' => $this->l('Hints for String9'),
			'ams_custom_string10' => $this->l('Hints for String10'),
			'ams_custom_string11' => $this->l('Hints for String11'),
			'ams_custom_string12' => $this->l('Hints for String12'),
			'ams_custom_string13' => $this->l('Hints for String13'),
			'ams_custom_string14' => $this->l('Hints for String14'),
			'ams_custom_string15' => $this->l('Hints for String15'),
		);
	}

	public function getL($key)
	{
        $messages = $this->getMessages();	
		return $messages[$key];
	}

	public function existsL($key)
	{
        $messages = $this->getMessages();	

        $ret = false;
        
        if(array_key_exists($key,$messages))$ret = true;
        else $ret = false;
		return $ret;
	}

	public function getMessages()
	{
		$messages = array(
					    'No message' => $this->l('No message')
			,'Voucher code' => $this->l('Voucher code')
			,'New order' => $this->l('New order')
			,'You must install a module that support this payment collection mode first' => $this->l('You must install a module that support this payment collection mode first')

			,'Paypal Account Info' => $this->l('Paypal Account Info')
		    ,'Paypal Account Email' => $this->l('Paypal Account Email')
		    ,'Bank Account Info' => $this->l('Bank Account Info')
		    ,'Bank Account Owner' => $this->l('Bank Account Owner')
		    ,'Bank Account Details' => $this->l('Bank Account Details')
		    ,'Bank Bank Address' => $this->l('Bank Bank Address')
			,'ProductIsFromDifferentSellerInCart' => $this->l('The product you adding to your cart is from a different seller than those currently in your shopping cart. Please check out of your current shopping cart first.')
			,'Owner' => $this->l('Owner')
			,'OtherSellerProductNotice' => 'This order contains products from other sellers, they are hidden from you. But they are visible to admin and your customer. '
			,'Seller' => $this->l('Seller')
			,'Is Seller' => $this->l('Is Seller')
			,'Approved' => $this->l('Approved')
			,'Yes' => $this->l('Yes')
			,'No' => $this->l('No')
			,'Save' => $this->l('Save')
			,'Not available' => $this->l('Not available') 
			,'in CMS Category' => $this->l('in CMS Category')
			,'subCMS Category' => $this->l('subCMS Category')
			,'Seller selection is only available for existing Categories. Please click "Save" button to save it first.' => $this->l('Seller selection is only available for existing Categories. Please click "Save" button to save it first.')
            ,'Seller selection is only available for existing products. Please click "Save and Stay" button to save it first.' => $this->l('Seller selection is only available for existing products. Please click "Save and Stay" button to save it first.')
			,'There are no subcategories' =>$this->l('There are no subcategories')
			,'Add a new sub CMS Category' => $this->l('Add a new sub CMS Category')
			,'Seller Additional Info - Seller Info' => $this->l('Seller Additional Info - Seller Info')
			,'Address Line 1' => $this->l('Address Line 1')
			,'Address Line 2' => $this->l('Address Line 2')
			,'City' => $this->l('City')
            ,'Other Info' => $this->l('Other Info')
            ,'Company' => $this->l('Company')
            ,'Invalid characters' => $this->l('Invalid characters')
            ,'Logo' => $this->l('Logo')
            ,'Upload seller logo from your computer' => $this->l('Upload seller logo from your computer')
            ,'Address' => $this->l('Address')
            ,'Post/Zip code' => $this->l('Post/Zip code')
            ,'Country' => $this->l('Country')
            ,'State' => $this->l('State')
            ,'Phone' => $this->l('Phone')
            ,'Fax' => $this->l('Fax')
            ,'Description' => $this->l('Description')
            ,'Forbidden characters' => $this->l('Forbidden characters')
            ,'Latitude' => $this->l('Latitude')
            ,'Longitude' => $this->l('Longitude')
            ,'Required field' => $this->l('Required field')
            ,'Map' => $this->l('Map')
            ,'Click Here To Get Map Location' => $this->l('City')
            ,'Linked Customer Account' => $this->l('Linked Customer Account')
            ,'Not linked to any account' => $this->l('Not linked to any account')
            ,'Link by email address' => $this->l('Link by email address')
            ,'Link by customer ID' => $this->l('Link by customer ID')
            ,'Last name' => $this->l('Last name')
            ,'First name' => $this->l('First name')
            ,'Seller Employee Info' => $this->l('Seller Employee Info')
            ,'Seller Payment Info' => $this->l('Seller Payment Info')
            ,'Seller Other Info' => $this->l('Seller Other Info')
			,'ListingLimitReached' => $this->l('You have reached your listing limits.')
			,'Approve selection' => $this->l('Approve selection')
			,'Approve selected items?' => $this->l('Approve selected items?')
			,'How To Create Seller Hint'=>$this->l('Note: To add a new seller please go to "Customers"->"Customers", click "Add new", and choose the option to "Create seller account". A new seller record will be created automatically.')
			,'1. Information' => $this->l('1. Information')
			,'2. Images' =>$this->l('2. Images')
			,'You must install the Agile Paypal Parallel Payment module if you choose payment mode "Seller/Store Split Payments" in the Agile Multiple Seller module.' =>$this->l('You must install the Agile Paypal Parallel Payment module if you choose payment mode "Seller/Store Split Payments" in the Agile Multiple Seller module.')
			,'Seller informaiton not found.' => $this->l('Seller informaiton not found.')
			,'Please Choose' => $this->l('Please Choose')
			,'Agile Paypal Module for Payments between seller and store' => $this->l('Agile Paypal Module for Payments between seller and store')
			,'Paypal Email Address' => $this->l('Paypal Email Address')
			
		);
		return $messages;
	}
	
			private function install_OrderController()
	{
		$file2change = _PS_ROOT_DIR_ . "/controllers/front/OrderController.php";
		if(!file_exists($file2change . ".bak0"))
			copy($file2change, $file2change . ".bak0");

		$lines = file($file2change);
		$handle = fopen($file2change, "w");		
		if(!$handle)return;
		$distance = strlen('private') + 2;
		foreach($lines AS $line)
		{
			$idx0 = strrpos($line, "private");
			$idx1 = strrpos($line, "checkoutProcess");
			if($idx0 !== false && $idx1 !== false && ($idx1 - $idx0)== $distance)
			{
				fwrite($handle, str_replace('private', 'protected', $line));
			}
			else 
			{
				fwrite($handle, $line);
			}
		}
		fclose($handle);		
	}

	private function install_adapters()
	{
		$toRemove   = array(" ", "\t", "\r\n", "\n", "\r");
		$file2change = _PS_ROOT_DIR_ . "/vendor/composer/autoload_classmap.php";
		$content = file_get_contents($file2change);		
		$pos = strrpos($content, 'PrestaShop\\\\PrestaShop\\\\Adapter\\\\AgileMultipleSeller\\\\');
		
				if($pos === false)
		{
						if(!file_exists($file2change . ".bak0"))
				copy($file2change, $file2change . ".bak0");

			$lines = file($file2change);
			$handle = fopen($file2change, "w");
			
			if(!$handle)return;
			$str2find = "returnarray(";
			foreach($lines AS $line)
			{
				fwrite($handle, $line);
				$trimedline = str_replace($toRemove,'',$line);
				$linepos = strrpos($trimedline, $str2find);
				if($linepos !== false)
				{
										fwrite($handle, '    \'PrestaShop\\\\PrestaShop\\\\Adapter\\\\AgileMultipleSeller\\\\LocationProductSearchProvider\' => dirname(dirname(dirname(__FILE__))) . \'/src/Adapter/AgileMultipleSeller/LocationProductSearchProvider.php\',' . "\r\n");
					fwrite($handle, '    \'PrestaShop\\\\PrestaShop\\\\Adapter\\\\AgileMultipleSeller\\\\SellerProductSearchProvider\' => dirname(dirname(dirname(__FILE__))) . \'/src/Adapter/AgileMultipleSeller/SellerProductSearchProvider.php\', ' . "\r\n");
				}
			}
			fclose($handle);
		}
		
		$file2change = _PS_ROOT_DIR_ . "/vendor/composer/autoload_static.php";
		$content = file_get_contents($file2change);		
		$pos = strrpos($content, 'PrestaShop\\\\PrestaShop\\\\Adapter\\\\AgileMultipleSeller\\\\');
		
				if($pos === false)
		{
						if(!file_exists($file2change . ".bak0"))
				copy($file2change, $file2change . ".bak0");

			$lines = file($file2change);
			$handle = fopen($file2change, "w");
			
			if(!$handle)return;
			$str2find = 'publicstatic$classMap=array(';
			foreach($lines AS $line)
			{
				fwrite($handle, $line);
				$trimedline = str_replace($toRemove,'',$line);
				$linepos = strrpos($trimedline, $str2find);
				if($linepos !== false)
				{
										fwrite($handle, '    \'PrestaShop\\\\PrestaShop\\\\Adapter\\\\AgileMultipleSeller\\\\LocationProductSearchProvider\' => __DIR__ . \'/../..\' . \'/src/Adapter/AgileMultipleSeller/LocationProductSearchProvider.php\',' . "\r\n");
					fwrite($handle, '    \'PrestaShop\\\\PrestaShop\\\\Adapter\\\\AgileMultipleSeller\\\\SellerProductSearchProvider\' => __DIR__ . \'/../..\' . \'/src/Adapter/AgileMultipleSeller/SellerProductSearchProvider.php\', ' . "\r\n");
				}
			}
			fclose($handle);
		}				

			
	} 

	public function install_otherfiles()
	{
		$toRemove   = array(" ", "\t", "\r\n", "\n", "\r");
	
				$file2change = _PS_ADMIN_DIR_ . "/ajax_products_list.php";
		if(!file_exists($file2change . ".bak0"))
			copy($file2change, $file2change . ".bak0");

		$lines = file($file2change);
		$handle = fopen($file2change, "w");
		
		if(!$handle)return;
		$str2find = "FROM`'._DB_PREFIX_.'product`p";
		$len = 29;
		foreach($lines AS $line)
		{
			$trimedline = str_replace($toRemove,'',$line);
			if(strlen($trimedline)>= $len)
			{
				if(substr($trimedline,0, $len) == substr($str2find,0,$len))
				{
					fwrite($handle, "FROM `'._DB_PREFIX_.'product` p ' . ((intval(". '$cookie->profile' .") == intval(Configuration::get('AGILE_MS_PROFILE_ID')))? 'INNER JOIN `'._DB_PREFIX_.'product_owner` po ON (p.id_product=po.id_product AND po.id_owner=' . " . '$cookie->id_employee' . " . ')' :'')  . '\r\n");
				}
				else
				{
					fwrite($handle, $line);
				}
			}
			else
			{
				fwrite($handle, $line);
			}
		}
		fclose($handle);	
		
		
						return true;
		
				$file2change = _PS_ROOT_DIR_ . "/app/AppKernel.php";
		$content = file_get_contents($file2change);		
		$pos = strrpos($content, "new AgileServeXBundle\AgileServeXBundle()");

				if($pos === false)
		{
						if(!file_exists($file2change . ".bak0"))
				copy($file2change, $file2change . ".bak0");

			$lines = file($file2change);
			$handle = fopen($file2change, "w");
	
			if(!$handle)return;
			$str2find = "PrestaShopBundle\PrestaShopBundle()";
			foreach($lines AS $line)
			{
				fwrite($handle, $line);
				$trimedline = str_replace($toRemove,'',$line);
				$linepos = strrpos($trimedline, $str2find);
				if($linepos !== false)
				{
					fwrite($handle, "			new AgileServeXBundle\AgileServeXBundle(),\r\n");
				}
			}
			fclose($handle);
		}		

		


				$file2change = _PS_ROOT_DIR_ . "/src/PrestaShopBundle/Resources/config/routing.yml";
		$content = file_get_contents($file2change);		
		$pos = strrpos($content, "_agileservex_routing:");
				if($pos === false)
		{
			$content = $content . "_agileservex_routing:\r\n    resource: \"@AgileServeXBundle/Resources/config/routing.yml\"";
		}		
		file_put_contents($file2change,$content);		

		return true;
	}

	protected function register_tabs()
	{
		if(!AgileInstaller::create_tab('Multiple Seller', 'MultipleSellersParent', 'AgileModulesParent', $this->name))return false;

		if(!AgileInstaller::create_tab('Seller Business Info', 'AdminSellerinfos', 'MultipleSellersParent', $this->name))return false;
		AgileInstaller::init_tab_prmission_for_existing_profiles('AdminSellerinfos',1,1,1,1);

		if(!AgileInstaller::create_tab('Seller Payment Info', 'AdminSellerPaymentinfos','MultipleSellersParent', $this->name))return false;
		AgileInstaller::init_tab_prmission_for_existing_profiles('AdminSellerPaymentinfos',1,1,1,1);

		if(!AgileInstaller::create_tab('Order Products', 'AdminOrderProducts', 'MultipleSellersParent', $this->name))return false;
		AgileInstaller::init_tab_prmission_for_existing_profiles('AdminOrderProducts',1,1,1,1);		

		if(!AgileInstaller::create_tab('Bulk Approval', 'AdminBulkApproval', 'MultipleSellersParent', $this->name))return false;
		AgileInstaller::init_tab_prmission_for_existing_profiles('AdminBulkApproval',1,1,1,1);		

		if(!AgileInstaller::create_tab('Seller Types', 'AdminSellerTypes', 'MultipleSellersParent', $this->name))return false;
		AgileInstaller::init_tab_prmission_for_existing_profiles('AdminSellerTypes',1,1,1,1);
		
		return true;
	}

	public function unregister_tabs()
	{   
		AgileInstaller::delete_tab('AdminSellerinfos');
		AgileInstaller::delete_tab('AdminSellerPaymentinfos');
		AgileInstaller::delete_tab('AdminBulkApproval');
		AgileInstaller::delete_tab('AdminOrderProducts');
		AgileInstaller::delete_tab('AdminSellerTypes');
		
		AgileInstaller::delete_tab('MultipleSellersParent');

		return true;
	}



	public function getContent()
	{
		$this->_html = '<h2>'.$this->displayName.'</h2>';

		$adminfolder = AgileInstaller::detect_admin_folder($_SERVER['SCRIPT_FILENAME']);
		Configuration::updateValue('AGILE_MS_ADMIN_FOLDER_NAME', $adminfolder);

		$health_check = AgileInstaller::install_health_check($this->agile_newfiles, $this->name, $adminfolder);
		if(!empty($health_check))	$this->_html .= $health_check;

		$this->_html .= AgileInstaller::show_agile_links();
		if (Tools::isSubmit('submitSetting'))
		{
			$seller_approval = intval(Tools::getValue('seller_approval'));
			$customer_seller = intval(Tools::getValue('customer_seller'));

			$edit_category = intval(Tools::getValue('edit_category'));
			$payment_mode = intval(Tools::getValue('payment_mode'));
			$cart_mode = intval(Tools::getValue('cart_mode'));
			$seller_tab = intval(Tools::getValue('seller_tab'));
			$seller_terms = intval(Tools::getValue('seller_terms'));
			$product_approval = intval(Tools::getValue('product_approval'));
			$product_approval_notice = intval(Tools::getValue('product_approval_notice'));
			$product_copy = intval(Tools::getValue('product_copy'));
			$product_image_number = intval(Tools::getValue('product_image_number'));
			$seller_panel_withleft = (int)Tools::getValue('seller_panel_withleft');
			$seller_panel_withright = (int)Tools::getValue('$seller_panel_withright');
			$allow_register_athome = (int)Tools::getValue('allow_register_athome');
			$seller_back_office = (int)Tools::getValue('seller_back_office');
			$seller_info_tab_style = (int)Tools::getValue('seller_info_tab_style');
			$seller_info_tab_gmap = (int)Tools::getValue('seller_info_tab_gmap');
			$seller_choose_theme = (int)Tools::getValue('seller_choose_theme');
			$is_manufacturer = (int)Tools::getValue('is_manufacturer');
			$is_supplier = (int)Tools::getValue('is_supplier');
			if($payment_mode == self::PAYMENT_MODE_BOTH AND ($this->PaymentModuleExistsForCollectionMode(2) <= 0))
			{
				$this->_postErrors[] = $this->l('You must install a module that support this payment collection mode first');
			}
			
			if (!sizeof($this->_postErrors))
			{
				Configuration::updateValue('AGILE_MS_SELLER_APPROVAL', intval($seller_approval));
				Configuration::updateValue('AGILE_MS_CUSTOMER_SELLER', intval($customer_seller));
				Configuration::updateValue('AGILE_MS_EDIT_CATEGORY', intval($edit_category));
				Configuration::updateValue('AGILE_MS_PAYMENT_MODE', intval($payment_mode));
				Configuration::updateValue('AGILE_MS_CART_MODE', intval($cart_mode));
				Configuration::updateValue('AGILE_MS_SELLER_TAB', intval($seller_tab));
				Configuration::updateValue('AGILE_MS_SELLER_TERMS', intval($seller_terms));
				Configuration::updateValue('AGILE_MS_PRODUCT_APPROVAL', $product_approval);
				Configuration::updateValue('AGILE_MS_PRODUCT_APPROVAL_NOTICE', $product_approval_notice);
				Configuration::updateValue('AGILE_MS_PRODUCT_COPY', $product_copy);
				Configuration::updateValue('AGILE_MS_PRODUCT_IMAGE_NUMBER', $product_image_number);
				Configuration::updateValue('AGILE_MS_SELLER_PANEL_WITHLEFT', $seller_panel_withleft);
				Configuration::updateValue('AGILE_MS_SELLER_PANEL_WITHRIGHT', $seller_panel_withright);
				Configuration::updateValue('AGILE_MS_ALLOW_REGISTER_ATHOME', $allow_register_athome);
				Configuration::updateValue('AGILE_MS_SELLER_BACK_OFFICE', $seller_back_office);
				Configuration::updateValue('AGILE_MS_SELLER_INFO_TAB_STYLE', $seller_info_tab_style);
				Configuration::updateValue('AGILE_MS_SELLER_INFO_TAB_GMAP', $seller_info_tab_gmap);
				Configuration::updateValue('AGILE_MS_SELLER_CHOOSE_THEME', $seller_choose_theme);
				Configuration::updateValue('AGILE_MS_IS_MANUFACTURER', intval($is_manufacturer));
				Configuration::updateValue('AGILE_MS_IS_SUPPLIER', intval($is_supplier));

				for ($i = 1; $i <= 10; $i++) {
					$key = sprintf('AGILE_MS_SELLER_TEXT%s', $i);
					$field_name = sprintf('ams_custom_text%s', $i);
					Configuration::updateValue($key, intval(Tools::getValue($field_name)));
				}
				for ($i = 1; $i <= 2; $i++) {
					$key = sprintf('AGILE_MS_SELLER_HTML%s', $i);
					$field_name = sprintf('ams_custom_html%s', $i);
					Configuration::updateValue($key, intval(Tools::getValue($field_name)));
				}
				for ($i = 1; $i <= 10; $i++) {
					$key = sprintf('AGILE_MS_SELLER_NUMBER%s', $i);
					$field_name = sprintf('ams_custom_number%s', $i);
					Configuration::updateValue($key, intval(Tools::getValue($field_name)));
				}
				for ($i = 1; $i <= 5; $i++) {
					$key = sprintf('AGILE_MS_SELLER_DATE%s', $i);
					$field_name = sprintf('ams_custom_date%s', $i);
					Configuration::updateValue($key, intval(Tools::getValue($field_name)));
				}
				for ($i = 1; $i <= 15; $i++) {
					$key = sprintf('AGILE_MS_SELLER_STRING%s', $i);
					$field_name = sprintf('ams_custom_string%s', $i);
					Configuration::updateValue($key, intval(Tools::getValue($field_name)));
				}
				
				if($product_approval)
				{					
					if(!AgileInstaller::create_tab('Bulk Approval','AdminBulkApproval','AdminCatalog',$this->name))return false;
					AgileInstaller::init_tab_prmission_for_existing_profiles('AdminBulkApproval',1,1,1,1);
				}
				else
				{
					AgileInstaller::delete_tab('AdminBulkApproval');
				}

				$this->displayConf();
			}
			else
				$this->displayErrors();
			
		}
		
		$this->displayForm();
		return $this->_html;
	}


	public function PaymentModuleExistsForCollectionMode($mode)
	{
		$ret = 0;
		$paymentModules = $this->GetIntegratedPaymentModules(false);
		if(empty($paymentModules))return false;	
		foreach($paymentModules as $key => $pmod)
		{
			if($pmod['mode'][$mode] == 1)
			{
				$ret++;
			}
		}
		return $ret;
	}
	
	public function hookDisplayHome($params)
	{
		if(!$this->active)return;
		if((int)Configuration::get('AGILE_MS_CUSTOMER_SELLER')!=1)return;

		$sellerinfo = new SellerInfo(SellerInfo::getIdByCustomerId(Context::getContext()->customer->id));		
		$seller = new Employee($sellerinfo->id_seller);
		$seller_exists = Validate::isLoadedObject($seller);		

		$pageurl =  ($this->context->customer->islogged()? 'sellersummary' : 'sellersignup');
		
		$this->context->smarty->assign(array(
			'seller_signup_url' =>  $this->context->link->getModuleLink('agilemultipleseller', $pageurl, array(), true),
			'show_sellersignup' => ((!$seller_exists && (int)Configuration::get('AGILE_MS_CUSTOMER_SELLER')==1 )? 1 : 0 )
		));

		return $this->display(__FILE__, 'ams_hookhome.tpl', $this->getCacheId('ams_hookhome'));
		
	}	
	public function hookDisplayBackOfficeHeader($params)
	{		
		$pos = strrpos($_SERVER['PHP_SELF'],"/");
		$params['id_product'] = (int)substr($_SERVER['PHP_SELF'], $pos + 1);
		if($this->context->controller->controller_name=="AdminProducts")
		{
			$this->context->smarty->assign(array(
				'ams_hook_extra' => AgileHelper::escapePackJS($this->hookDisplayProductInformations($params))
				));
			
			if(Module::isInstalled('agilesellerlistoptions'))
			{
				require_once(_PS_ROOT_DIR_ .'/modules/agilesellerlistoptions/agilesellerlistoptions.php');
				$aslo_module = new AgileSellerListOptions();
				$hookproductinfo = $aslo_module->hookDisplayProductInformations(array('for_front'=>0,'id_product'=>$params['id_product']), $this->is_seller, false);
				$this->context->smarty->assign(array(
					'aslo_hook_extra' => AgileHelper::escapePackJS($hookproductinfo),
					));					
			}
						
			return $this->display(__FILE__, 'hookbackofficeheader.tpl');
		}
	}
	
	public function hookDisplayOverrideTemplate($params)
	{
		if (isset($this->context->controller->php_self) && ($this->context->controller->php_self == 'order-confirmation')) {
			$is_original_cart = false;
			$id_cart_parent = 0;
			$id_cart = (int)Tools::getValue('id_cart');
			$id_order = (int)Order::getOrderByCartId($id_cart);
						if($id_order == 0)
			{
				$id_cart_parent = $id_cart;
			}
			else
			{
				$id_cart_parent = (int)AgileMultipleSeller::get_subcart_parentid($id_cart);
				if($id_cart_parent == 0 || ($id_cart_parent == $id_cart && $id_cart>0))
				{
					$is_original_cart = true;
				}
			}
			if($is_original_cart) return; 			return $this->getTemplatePath('hookOverrideTemplateOrderConfirmation.tpl');
		}
	}
	
	
			public function hookDisplayAdminProductAction($params)
	{
		$array = array();
		$array[] = (new PrestaShopBundle\Model\Product\AdminProductAction())
		->setHref('www.google.com')
		->setLabel('Action from module')
		->setIcon('android');
		return $array;
	}	

	public function hookActionAdminProductsListingResultsModifier($params)
	{
	}


		public function hookActionAdminProductsListingFieldsModifier($params)
	{
		$params['sql_table'] = array_merge(isset($params['sql_table'])?$params['sql_table'] : array()  , array(
			'p_o'=>array(
						'table' => 'product_owner'
						,'join'=>'LEFT JOIN'
						,'on' =>'p.id_product=p_o.id_product'
						),
			'si'=>array(
						'table' => 'sellerinfo'
						,'join'=>'LEFT JOIN'
						,'on' =>'p_o.id_owner=si.id_seller'
						),
			'sil'=>array(
						'table' => 'sellerinfo_lang'
						,'join'=>'LEFT JOIN'
						,'on' =>' (si.id_sellerinfo=sil.id_sellerinfo AND sil.id_lang=' . Context::getContext()->language->id. ')'
						),
					));
		

		$params['sql_select'] = array_merge(isset($params['sql_select'])?$params['sql_select'] : array(), array(
			'seller'=>array(
						'table' => 'sil'
						,'field'=>'company'
						,'filtering' =>' LIKE  \'%%%s%%\' '
						),
					));

		$context = Context::getContext();
		$is_seller = ($context->cookie->profile == (int)Configuration::get('AGILE_MS_PROFILE_ID'));
		if($is_seller)
		{
			$params['sql_where'][] = ' IFNULL(p_o.id_owner,0) ='  . (int)$context->cookie->id_employee;
		}

	}


	public function hookActionProductAdd($params)
	{
		$this->processProductExtensions($params['product']);
	}

	public function hookActionProductUpdate($params)
	{
		$this->processProductExtensions($params['product']);
	}
	
	private function processProductExtensions($product)
	{
		$approved = intval(Tools::getValue('approved'));
			
		if(intval(Configuration::get('AGILE_MS_PRODUCT_APPROVAL')) != 1)$approved = 1;
		$sql = 'UPDATE '._DB_PREFIX_.'product_owner SET approved=' . $approved . ' WHERE id_product=' . (int)$product->id;
		Db::getInstance()->Execute($sql); 

		if(Module::isInstalled('agilesellerlistoptions'))
		{
			require_once(_PS_ROOT_DIR_ .'/modules/agilesellerlistoptions/agilesellerlistoptions.php');
			$aslo_module = new AgileSellerListOptions();
			$aslo_module->processProductExtenstions(array('product' => $product));
		}
		
	}

	public function hookactionOrderReturn($params)
	{
		if(!$this->active)return;
		$orderReturn = $params['orderReturn'];
		$order = new Order($orderReturn->id_order);
		$id_seller = AgileSellerManager::getObjectOwnerID('order',$orderReturn->id_order);
		$seller = new Employee($id_seller);
		$vendd = $seller->email;
		$sellername = $seller->firstname. ' '.$seller->lastname;
		$customername = $this->context->customer->firstname. ' '.$this->context->customer->lastname;
		$template_name = 'order_return_request';  
		$templateVars = array(
			'{id_order}' => $orderReturn->id_order,
			'{sellername}' => $sellername,
			'{order_name}' => $order->getUniqReference(),
			'{customername}' => $customername,
			'{customermail}' => $this->context->customer->email,
			'{message}' => $orderReturn->question,
			'{shop_name}' => Configuration::get('PS_SHOP_NAME'),
			'{shop_url}'=>Tools::getShopDomainSsl(true, true).__PS_BASE_URI__,
			'{shop_logo}' => Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.'img/logo.jpg'
			);
		
		$to = $seller->email;
		$from = $this->context->customer->email;
		AgileMultipleSellerMailer::SendTranslateSubject($this->context->language->id, $template_name, $templateVars, $to, $sellername, $from, null,null,null, dirname(__FILE__).'/mails/', false);
	}	

	public function hookActionCarrierUpdate($params)
	{
		if(!$this->active)return;
		
		$id_carrier_old = $params['id_carrier'];
		$carrier_new = $params['carrier'];
		$id_carrier_new = $carrier_new->id;
		$iw_owner = AgileSellerManager::getObjectOwnerID('carrier', $id_carrier_old);
		AgileSellerManager::assignObjectOwner('carrier', $id_carrier_new , $iw_owner);
		$carrier_new->update();
		
	}	

	public function hookActionCartSave($params)
	{
		if(!$this->active)return;
				if(!isset($params['cart']))return; 		$cart = new Cart($params['cart']->id); 
		$products = $cart->getProducts();
		if(count($products)<=1)return;
		if(intval(Configuration::get('AGILE_MS_CART_MODE'))==AgileMultipleSeller::CART_MODE_MULTIPLE_SELLER)return;
		
		$sql = 'SELECT count(DISTINCT IFNULL( po.id_owner, 0 ))
                FROM `'._DB_PREFIX_. 'cart_product` cp
                LEFT JOIN `'._DB_PREFIX_. 'product_owner` po ON cp.id_product = po.id_product
                WHERE cp.id_cart = ' . $cart->id. '
                ';
				$cnt = Db::getInstance()->getValue($sql);
		if($cnt<=1)return; 
				$last_prod = $cart->getLastProduct();
		if($last_prod)$cart->deleteProduct($last_prod['id_product'],$last_prod['id_product_attribute']); 
	}
	
	public function hookMyAccountBlock($params)
	{
		if(!$this->active)return;
		
		$mysellerurl = '';
		$mysellerurl = $this->context->link->getModuleLink('agilemultipleseller', 'sellersummary', array(), true);
		
		$this->context->smarty->assign(array(
			'mysellerurl' => $mysellerurl		
			));

		$sellerinfo = new SellerInfo(SellerInfo::getIdByCustomerId($this->context->customer->id), $this->context->language->id);
				if(!empty($this->context->cookie->email) AND Validate::isEmail($this->context->cookie->email))
		{
			$seller = new Employee((int)SellerInfo::getSellerIdByCustomerId($this->context->customer->id));			$seller_exists = Employee::employeeExists($this->context->cookie->email);
		}
		else
		{
			$seller = new Employee();
			$seller_exists= false;
		}


		$isSeller =  ($seller_exists AND Validate::isLoadedObject($seller) AND $seller->active AND $seller->id_profile == (int)Configuration::get('AGILE_MS_PROFILE_ID'));

		if(intval(Configuration::get('AGILE_MS_CUSTOMER_SELLER'))==1 OR $isSeller)
			return $this->display(__FILE__, 'views/templates/hook/myaccount.tpl');
		
		return '';	
	}


	public function hookDisplayCustomerAccount($params)
	{
		if(!$this->active)return;

		return $this->displayMySellerAccountHook($params, 'customeraccount.tpl');
	}
	
	public function hookDisplayMyAccountBlock($params)
	{
		if(!$this->active)return;

		return $this->displayMySellerAccountHook($params, 'myaccount.tpl');
	}

	private function displayMySellerAccountHook($params, $hooktpl)
	{
		$mysellerurl = $this->context->link->getModuleLink('agilemultipleseller', 'sellersummary', array(), true);
		
		$this->context->smarty->assign(array(
			'mysellerurl' => $mysellerurl		
			));

		include_once(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/SellerInfo.php");
		$sellerinfo = new SellerInfo(SellerInfo::getIdByCustomerId($this->context->customer->id), $this->context->language->id);
				if(!empty($this->context->cookie->email) AND Validate::isEmail($this->context->cookie->email))
		{
			$seller = new Employee((int)SellerInfo::getSellerIdByCustomerId($this->context->customer->id));			$seller_exists = Employee::employeeExists($this->context->cookie->email);
		}
		else
		{
			$seller = new Employee();
			$seller_exists= false;
		}

		$isSeller =  ($seller_exists AND Validate::isLoadedObject($seller) AND $seller->active AND $seller->id_profile == (int)Configuration::get('AGILE_MS_PROFILE_ID'));
		if(intval(Configuration::get('AGILE_MS_CUSTOMER_SELLER'))==1 OR $isSeller)
			return $this->display(__FILE__, $hooktpl);		
		
		return '';
	}	

	public function hookMaxUploadImages($num)
	{
		$this->context->smarty->assign(array(
			'image_number_limit' =>  $num 
			));
	
		return $this->display(__FILE__, 'views/templates/hook/hookmaxuploadimages.tpl');		
	}

	
	
	public function hookActionCustomerAccountAdd($params)
	{
		if(!$this->active)return;
		if(intval(Configuration::get('AGILE_MS_CUSTOMER_SELLER')) != 1)return;
		if(!isset($params["_POST"]["seller_account_signup"]) OR intval($params["_POST"]["seller_account_signup"])!=1)return;
		self::createSellerAccount($params['newCustomer']);		
	}
	
	public function hookDisplayCustomerAccountFormTop($params)
	{ 
		if(!$this->active)return;

				$pname = AgileHelper::getPageName();
		if($pname == "orderopc.php" OR $pname =="order-opc.php")return;
				if(intval(Configuration::get('AGILE_MS_CUSTOMER_SELLER')) != 1)return;

		return $this->display(__FILE__, 'views/templates/hook/displaycustomeraccountformtop.tpl');
	}
	
		public function hookActionValidateOrder($params)
	{
		if(!$this->active)return;
		$order = $params['order'];
		if(!Validate::isLoadedObject($order))return; 

		$cart = new Cart($order->id_cart);
		if(!Validate::isLoadedObject($cart))return; 
		
		$owners = array();
		foreach ($cart->getProducts() AS $key => $product)
		{
			$id_owner = AgileSellerManager::getObjectOwnerID('product',$product['id_product']);
						if(in_array(intval($id_owner),$owners,true))continue;
			
			$owners[] = intval($id_owner);
			$sql = 'INSERT INTO `'._DB_PREFIX_.'order_owner` (id_order,id_owner,date_add) VALUES (' . $order->id . ',' . $id_owner . ',\'' . date('Y-m-d H:i:s') . '\')';

			Db::getInstance()->Execute($sql);
			$sql = 'SELECT COUNT(*) AS num FROM `'._DB_PREFIX_.'customer_owner` WHERE id_customer=' . $order->id_customer . ' AND id_owner=' . $id_owner;
			$row = Db::getInstance()->getRow($sql);
			if(intval($row['num'])==0)
			{
				$sql = 'INSERT INTO `'._DB_PREFIX_.'customer_owner` (id_customer,id_owner,date_add) VALUES (' . $order->id_customer . ',' . $id_owner . ',\'' . date('Y-m-d H:i:s') . '\')';
				Db::getInstance()->Execute($sql);
			}
		}
		
		if(!Module::isInstalled('agilesellercommission'))return;
		require_once(dirname(__FILE__) .'/../agilesellercommission/agilesellercommission.php');
		require_once(dirname(__FILE__) .'/../agilesellercommission/SellerCommission.php');
		
		$ct_Ids = Configuration::get('ASC_CT_COMMISSION_AT');
		if(empty($ct_Ids))$ct_Ids = AgileMultipleSeller::getCommissionCreationDefaultStatuses();
		
		
		$orderState =  $params['orderStatus'];
		if (in_array($orderState->id, AgileMultipleSeller::StringIDsToArray($ct_Ids)))
			AgileSellerCommission::createSellerCommission($order);

	}

		public function hookDisplayProductExtraContent($params)
	{
		
		
		$array = array();
		if(!$this->active)return $array;
		if(Configuration::get('AGILE_MS_SELLER_TAB')!=1)return $array;

		require_once(dirname(__FILE__) .'/SellerInfo.php');
		$id_seller =  AgileSellerManager::getObjectOwnerID('product',intval(Tools::getValue('id_product')));
		$id_sellerinfo = SellerInfo::getIdBSellerId($id_seller);
		if(intval($id_sellerinfo)<=0)return  $array;
		$sellerinfo = new SellerInfo($id_sellerinfo,$this->context->language->id);
		if(Configuration::get('AGILE_MS_SELLER_INFO_TAB_GMAP') == 1)
		{
			Context::getContext()->controller->registerJavascript('agile_hookdisplayextracontent','/modules/agilemultipleseller/js/hookdisplayextracontent.js',['position' => 'bottom', 'priority' => 100]);
		}
		
		$HOOK_SELLER_RATINGS = '';
		if(Module::isInstalled('agilesellerratings'))
		{
			require_once(_PS_ROOT_DIR_ . "/modules/agilesellerratings/agilesellerratings.php");
			$rmodule = new AgileSellerRatings();
			$HOOK_SELLER_RATINGS = $rmodule->getAverageRating($id_seller,  AgileSellerRatings::RATING_TYPE_SELLER);
		}

		
		$this->context->smarty->assign(array(
			'sellerInfo'=>$sellerinfo,
			'goreviewtab' => intval(Tools::getValue('goreviewtab')),
			'show_seller_store_link' => ((Module::isInstalled('agilemultipleshop') && Validate::isLoadedObject($sellerinfo)) ? 1 : 0),
			'HOOK_SELLER_RATINGS' => $HOOK_SELLER_RATINGS,
			'google_map_show' => Configuration::get('AGILE_MS_SELLER_INFO_TAB_GMAP') == 1 ? 1 : 0
			));

		$content = $this->display(__FILE__, '/views/templates/hook/hookdisplayextracontent.tpl');

		$array[] = (new PrestaShop\PrestaShop\Core\Product\ProductExtraContent())
		->setTitle($this->l('Seller info'))
		->setContent($content);

		return $array;
	}
	
	private static function StringIDsToArray($ids)
	{
		if(empty($ids))return array();
		return explode(",", $ids);
	}
	
	
	public static function getCommissionCreationDefaultStatuses()
	{
		return  _PS_OS_PAYMENT_ . "," . _PS_OS_WS_PAYMENT_ . "," . _PS_OS_SHIPPING_ . "," . _PS_OS_DELIVERED_;
	}
	
	public static function getCommissionCancellationDefaultStatuses()
	{
		return  _PS_OS_CANCELED_ . "," . _PS_OS_REFUND_;
	}

	
	public function hookActionOrderStatusUpdate($params)
	{
		if(!$this->active)return;
		
		if(!Module::isInstalled('agilesellercommission'))return;
		require_once(dirname(__FILE__) .'/../agilesellercommission/agilesellercommission.php');
		require_once(dirname(__FILE__) .'/../agilesellercommission/SellerCommission.php');
		
		$rt_Ids = Configuration::get('ASC_RT_COMMISSION_AT');
		if(empty($rt_Ids))$rt_Ids = AgileMultipleSeller::getCommissionCancellationDefaultStatuses();

		$ct_Ids = Configuration::get('ASC_CT_COMMISSION_AT');
		if(empty($ct_Ids))$ct_Ids = AgileMultipleSeller::getCommissionCreationDefaultStatuses();

		$id_order = $params['id_order'];
		$order = new Order($id_order);
		if(!Validate::isLoadedObject($order))return;
		
		if (in_array($params['newOrderStatus']->id, AgileMultipleSeller::StringIDsToArray($rt_Ids)))
		{
			AgileSellerCommission::cancelSellerCommission($order);
		} 
		else if (in_array($params['newOrderStatus']->id, AgileMultipleSeller::StringIDsToArray($ct_Ids))) 
		{
			AgileSellerCommission::createSellerCommission($order);
		}
	}    
	
	public static function is_list_approved($id_product)
	{
		$sql = 'SELECT approved FROM '._DB_PREFIX_.'product_owner WHERE id_product=' . $id_product;
		$approved = intval(Db::getInstance()->getValue($sql));
		return ($approved>0?1:0);
	}
	
	public static function getPageName()
	{
		$page = $_SERVER["SCRIPT_NAME"];
		$idx = strrpos($page,"/");
		$ret = strtolower(substr($page,$idx+1));
		return $ret;
	}
	public static function sendNewOrderMail($id_lang, $templateVars, $from, $fromName, $fileAttachment, $modeSMTP, $die, $id_shop, $bcc)
	{
		$configuration = Configuration::getMultiple(array('PS_SHOP_EMAIL', 'PS_MAIL_METHOD', 'PS_MAIL_SERVER', 'PS_MAIL_USER', 'PS_MAIL_PASSWD', 'PS_SHOP_NAME'));
		$id_order = AgileHelper::get_order_id_from_maildata($templateVars);
		$id_seller = AgileSellerManager::getObjectOwnerID('order',$id_order);
		$order = new Order($id_order);
		$message = $order->getFirstMessage();
		if (!$message OR empty($message)) 
		{
			$message = "";
		}
		$seller = new Employee($id_seller);
		$templateVars = array_merge($templateVars, array('{message}' => $message,'{seller-firstname}'=>$seller->firstname,'{seller-lastname}' => $seller->lastname));
		$iso = Language::getIsoById((int)($id_lang));
		if (file_exists(dirname(__FILE__).'/mails/'.$iso.'/new_order.txt') AND file_exists(dirname(__FILE__).'/mails/'.$iso.'/new_order.html') AND $seller->email)
		{
			AgileMultipleSellerMailer::SendTranslateSubject($id_lang,'new_order', $templateVars, $seller->email, NULL, $configuration['PS_SHOP_EMAIL'], $configuration['PS_SHOP_NAME'], $fileAttachment, $modeSMTP, dirname(__FILE__).'/mails/', $die, $id_shop, $bcc);
		}
	}

	public static function createSellerAccount($customer)
	{
		$context = Context::getContext();
		
		if(!Validate::isLoadedObject($customer))return;
		require_once(dirname(__FILE__) .'/SellerInfo.php');
		$sid = AgileSellerManager::getLinkedSellerID($customer->id);
		if($sid>0)return; 
		$aid = Address::getFirstCustomerAddressId($customer->id, true);
		$address = new Address(intval($aid));
		if(!Validate::isLoadedObject($address))
		{
			$address->id_country = Configuration::get('PS_COUNTRY_DEFAULT');
		}
		
		if(Employee::employeeExists($customer->email))
		{
						$seller_emp = new Employee((int)SellerInfo::getSellerIdByCustomerId($customer->id));			
		}
		else
		{
			$seller_emp = new Employee();
			$seller_emp->firstname = $customer->firstname;
			$seller_emp->lastname = $customer->lastname;
			$seller_emp->email = $customer->email;
			$seller_emp->id_profile = (int)Configuration::get('AGILE_MS_PROFILE_ID');
			$seller_emp->active =(intval(Configuration::get('AGILE_MS_SELLER_APPROVAL')) == 1? 0 : 1);
			$seller_emp->id_lang = $context->language->id;
			$seller_emp->passwd = $customer->passwd;
			$seller_emp->default_tab = (Module::isInstalled('agiledashboard')? Tab::getIdFromClassName("AdminDashboard") : Tab::getIdFromClassName("AdminProducts"));
			$seller_emp->bo_theme = "default";
			$seller_emp->optin = 0;
			$seller_emp->add();
		}

		AgileSellerManager::assignObjectOwner('customer', $customer->id, $seller_emp->id);
		
		$sellerinfo = self::createSellerInfo($customer, $address, $seller_emp);
		
		self::sendNewSellerAccountEmail($sellerinfo->id);
		
	}
	
	public static function createSellerInfo($customer, $address, $seller_emp)
	{
		$context = Context::getContext();
				$id_sellerinfo = SellerInfo::getIdBSellerId($seller_emp->id);
		$sellerinfo = new SellerInfo($id_sellerinfo);
		$sellerinfo->id_customer = $customer->id;
		$sellerinfo->id_seller = $seller_emp->id;
		$sellerinfo->approved = (intval(Configuration::get('AGILE_MS_SELLER_APPROVAL'))==1?0:1);
		if(Tools::getValue('signin'))
		{
			$sellerinfo->id_country = intval(Tools::getValue('id_country'));
			if(Country::containsStates($sellerinfo->id_country))
			{
				$sellerinfo->id_state = intval(Tools::getValue('id_state'));
			}
			$sellerinfo->postcode = Tools::getValue('postcode');
			$sellerinfo->phone = Tools::getValue('phone');
			$languages = Language::getLanguages(false);
			
			foreach($languages as $lang)
			{
				$sellerinfo->description[$lang['id_lang']] = Tools::getValue('description_'.$lang['id_lang']);
				$sellerinfo->company[$lang['id_lang']] = Tools::getValue('company_'.$lang['id_lang']);
				$sellerinfo->city[$lang['id_lang']] = Tools::getValue('city_'.$lang['id_lang']);
				$sellerinfo->address1[$lang['id_lang']] = Tools::getValue('address1_'.$lang['id_lang']);
				$sellerinfo->address2[$lang['id_lang']] = Tools::getValue('address2_'.$lang['id_lang']);
			}
		}
		else
		{
			$sellerinfo->id_country = $address->id_country;
			$sellerinfo->id_state = $address->id_state;
			$sellerinfo->postcode = $address->postcode;
			$sellerinfo->phone = $address->phone;
			$company = (empty($address->company)?($customer->firstname . ' ' . $customer->lastname) : $address->company);
			$languages = Language::getLanguages(false);
			foreach($languages as $lang)
			{
				$sellerinfo->company[$lang['id_lang']] = $company;
				$sellerinfo->city[$lang['id_lang']] = $address->city;
				$sellerinfo->address1[$lang['id_lang']] = $address->address1;
				$sellerinfo->address2[$lang['id_lang']] = $address->address2;
			}
		}
		
		$sellerinfo->longitude = 0;
		$sellerinfo->latitude = 0;
		$sellerinfo->ams_custom_date1 = null;
		$sellerinfo->ams_custom_date2 = null;
		$sellerinfo->ams_custom_date3 = null;
		$sellerinfo->ams_custom_date4 = null;
		$sellerinfo->ams_custom_date5 = null;

		if($sellerinfo->save())
		{	
			if(Module::isInstalled('agilemultipleshop') && $sellerinfo->id_shop <=0)
			{
				include_once(_PS_ROOT_DIR_ . "/modules/agilemultipleshop/agilemultipleshop.php");
				$shop_name = $sellerinfo->company[$context->language->id];
				if(empty($shop_name))$shop_name = $seller_emp->firstname . '-' . $seller_emp->lastname;
				$vshop = AgileMultipleShop::create_new_shop($sellerinfo->id_seller, $sellerinfo->company[$context->language->id]);
				$sellerinfo->id_shop = $vshop->id;
				$sellerinfo->theme_name = $vshop->theme_name;
				$sellerinfo->update();
			}
		}

		return $sellerinfo;
	}
	
		
	public static function getNumberOfOwners($id_order)
	{
		$sql = 'SELECT count( DISTINCT id_owner ) as num
                FROM `' . _DB_PREFIX_ . 'order_owner`
                WHERE id_order =' . $id_order;
		$row = Db::getInstance()->getRow($sql);
		if(isset($row['num']) AND intval($row['num'])>0)return intval($row['num']);

		return 0;
	}
	
	protected function createLinkedProfile()
	{
		$sql = 'SELECT id_profile FROM  `'._DB_PREFIX_.'profile_lang` WHERE name=\'agilemultipleseller\'';
		$results = Db::getInstance()->ExecuteS($sql);
		$result = array_shift($results);
		if(isset($result['id_profile']) AND intval($result['id_profile'])>0)
		{
			$profile = new Profile( intval($result['id_profile']));
		}
		else
		{
			$profile = new Profile();
			$profile->id = 0;
			$languages = Language::getLanguages();
			foreach ($languages AS $language)
			{
				$fields = array('name');
				foreach($fields AS $field)
					$profile->{$field}[intval($language['id_lang'])] = 'agilemultipleseller';
			}
			$profile->add();
		}
		
		return $profile->id;
	}
	
	function set_permissions($id_profile, $tabs)
	{
		$classes = array_keys($tabs);
		foreach($classes AS $class)
		{
			$classname = $class;
						if(isset($tabs[$class]['new_class']) AND !empty($tabs[$class]['new_class']))$classname = $tabs[$class]['new_class'];
			AgileInstaller::update_access($id_profile, $classname, $tabs[$class]['view'], $tabs[$class]['edit'], $tabs[$class]['add'], $tabs[$class]['delete']);
		}
	}

	
	public function hookActionHtaccessCreate($params)
	{
		if(!$this->active AND !intval($params['install']))return;
		if(!Module::isInstalled('agilemultipleseller'))return;
		$htfile = _PS_ROOT_DIR_ . "/.htaccess";
		if(!file_exists($htfile))return;

		$lines = file($htfile);
		$handle = fopen($htfile, "w");
		if(!$handle)return;
		foreach($lines AS $line)
		{
						if(strpos($line, "myselleraccount.php")>0)continue; 
			
						fwrite($handle, $line);
									$idx = intval(strpos(strtolower($line), "ewriteengine on"));
			if($idx>0)
			{
				$myselleraccount_url_directory = Configuration::get('AGILE_MS_MYSELLER_URL_DIRECTORY');
				if(empty($myselleraccount_url_directory))$myselleraccount_url_directory = 'my-seller-account';
				fwrite($handle,"\r\n");
			}
		}
		fclose($handle);
	}
	
	public static function getOrderOrigin($id_order)
	{
		if(Module::isInstalled('prestabay'))
		{
			$sql = 'SELECT id  FROM ' . _DB_PREFIX_ . 'prestabay_order WHERE presta_order_id=' . intval($id_order);
			$id = intval(Db::getInstance()->getValue($sql));
			if($id > 0)return self::ORDER_ORIGIN_EBAY;
		}
		return self::ORDER_ORIGIN_PRESTASHOP;;
	}
	
	public static function getSelllerEmail($id_seller)
	{
		if((int)$id_seller == 0)return Configuration::get('PS_SHOP_EMAIL');
		$sql = 'SELECT email FROM `'._DB_PREFIX_.'employee` WHERE id_employee=' . intval($id_seller);
				return strval(Db::getInstance()->getValue($sql));
	}

	public static function getSelllerName($id_seller, $id_lang)
	{
		if((int)$id_seller == 0)return Configuration::get('PS_SHOP_NAME');
		$sql = 'SELECT CASE WHEN IFNULL(sl.company,\'\') THEN e.firstname + \' \' + e.lastname ELSE sl.company END As Name
				FROM `'._DB_PREFIX_.'sellerinfo` s
				INNER JOIN '._DB_PREFIX_.'sellerinfo_lang sl ON s.id_sellerinfo = sl.id_sellerinfo AND id_lang = ' . (int)$id_lang .' 
				INNER JOIN '._DB_PREFIX_.'employee e ON s.id_seller = e.id_employee
				WHERE s.id_seller = ' . (int)$id_seller . '
		';	
		
				return strval(Db::getInstance()->getValue($sql));
		
	}

	
	public static function get_payment_info_from_cart($paychanied = false)
	{
		$payments = self::get_payment_info_from_cart_before_merge($paychanied);
		if(empty($payments))return $payments;

				$payments_after_merge = array();
				if(isset($payments[0]))
		{
			$payments_after_merge[0] = $payments[0];
		}
		
		foreach($payments as $id_seller => $payinfo)
		{
						if($id_seller == 0)continue;
			$payment_collection = SellerInfo::get_seller_payment_collection($id_seller);
			if($payment_collection != 1)
			{
								$payments_after_merge[$id_seller] = $payinfo;
			}
			else
			{
								if(!isset($payments_after_merge[0]))
				{
					$payments_after_merge[0] = array(
						'email' => Configuration::get('PS_SHOP_EMAIL')
						,'amount' => (float)$payinfo['amount']
						,'commission' => 0  						,'payto' => Configuration::get('PS_SHOP_NAME')
						);
				}
				else
				{
					$existing = $payments_after_merge[0];
					$payments_after_merge[0] = array(
						'email' => Configuration::get('PS_SHOP_EMAIL')
						,'amount' => (float)$existing['amount'] + (float)$payinfo['amount']
						,'commission' => (float)$existing['commission'] 						,'payto' => Configuration::get('PS_SHOP_NAME')
						);
				}
			}
		}
		
																						$payments_after_adjustment = array();
		if(isset($payments_after_merge[0]))
		{
			$payments_after_adjustment[0] = $payments_after_merge[0];
		}

		foreach($payments_after_merge as $id_seller => $payinfo)
		{
						if($id_seller == 0)continue;
						if((float)$payinfo['amount'] >0)
			{
				$payments_after_adjustment[$id_seller] = $payinfo;
			}
			else  			{
				$payments_after_adjustment[0]['amount'] = 	(float)$payments_after_adjustment[0]['amount'] + 	(float)$payinfo['amount'];
			}
		}
		
		return $payments_after_adjustment;
		
	}


	public static function get_payment_info_from_cart_before_merge($paychanied = false)
	{
		$context = Context::getContext();
		if(!$context->cart)return array();
		if(!Module::isInstalled('agilesellercommission') OR !Module::isInstalled('agilemultipleseller'))return array();
		
		require_once(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/agilemultipleseller.php");
		require_once(_PS_ROOT_DIR_ . "/modules/agilesellercommission/agilesellercommission.php");
		$pmode = intval(Configuration::get('AGILE_MS_PAYMENT_MODE'));
		switch($pmode)
		{
			case AgileMultipleseller::PAYMENT_MODE_STORE:
				return self::get_payment_info_paystoreonly();
			case AgileMultipleseller::PAYMENT_MODE_SELLER:
				return self::get_payment_info_payselleronly();
				break;
			case AgileMultipleseller::PAYMENT_MODE_BOTH:
				if($paychanied)
				{
					return self::get_payment_info_paychained();
				}
				else
				{
					return self::get_payment_info_payparallel();
				}
				break;    
		}            
	}

			public static function get_payment_info_paystoreonly()
	{
		$context = Context::getContext();
		
		$payments = array();
		$store_paypalemail = Configuration::get('AGILE_PAYPALPL_BUSINESS');
						
		$payments[0] = array('email'=>$store_paypalemail, 'amount' => $context->cart->getOrderTotal(true, Cart::BOTH), 'commission' =>0, 'payto' => self::getSelllerName(0, $context->language->id));  				return $payments;    
	}

			public static function get_payment_info_payselleronly()
	{
		$context = Context::getContext();

		require_once(_PS_ROOT_DIR_ . "/modules/agilesellercommission/agilesellercommission.php");
				$seller_sales_4payment = AgileMultipleSeller::get_seller_sales_from_cart($context->cart->id, true, true, true);
		
		$include_shipping = (intval(Configuration::get('ASC_SHIPPING_INCLUDE'))==1 );
		$include_tax = (intval(Configuration::get('ASC_TAX_INCLUDE'))==1);
		$after_discount = (intval(Configuration::get('ASC_VOUCHER_COST'))==AgileSellerCommission::VOUCHER_COST_AT_SELLER);	
		$seller_sales_4commission = AgileMultipleSeller::get_seller_sales_from_cart($context->cart->id, $include_shipping, $after_discount, $include_tax);

		if(empty($seller_sales_4payment))return;
		$payments = array();
		$total_commission_amount = 0;
		foreach($seller_sales_4commission AS $id_seller=>$sales_amount)
		{
			$seller_commission_amount = AgileSellerCommission::get_seller_commission_amount($id_seller,$sales_amount, $context->cart->id_currency);
			$seller_commission_amount = Tools::ps_round($seller_commission_amount, 2);
			if($seller_commission_amount<=0)$seller_commission_amount = 0;			$seller_payment_amount = $seller_sales_4payment[$id_seller];			$payments[$id_seller] = array('email'=>self::getSelllerEmail($id_seller),'amount'=>$seller_payment_amount, 'commission' => floatval($seller_commission_amount), 'payto' => self::getSelllerName($id_seller, $context->language->id));
						$total_commission_amount = $total_commission_amount + $seller_commission_amount;
		}
		
		return $payments;    
	}

					public static function get_payment_info_payparallel()
	{
		$context = Context::getContext();

		require_once(_PS_ROOT_DIR_ . "/modules/agilesellercommission/agilesellercommission.php");

		$seller_sales_4payment = AgileMultipleSeller::get_seller_sales_from_cart($context->cart->id, true, true, true);
		
		$include_shipping = (intval(Configuration::get('ASC_SHIPPING_INCLUDE'))==1);
		$include_tax = (intval(Configuration::get('ASC_TAX_INCLUDE'))==1);
		$after_discount = (intval(Configuration::get('ASC_VOUCHER_COST'))==AgileSellerCommission::VOUCHER_COST_AT_SELLER);	
		$seller_sales_4commission = AgileMultipleSeller::get_seller_sales_from_cart($context->cart->id, $include_shipping, $after_discount, $include_tax);

		if(empty($seller_sales_4payment))return;
		$payments = array();
		$total_commission_amount = 0;
		foreach($seller_sales_4commission AS $id_seller=>$sales_amount)
		{
			$seller_commission_amount = AgileSellerCommission::get_seller_commission_amount($id_seller,$sales_amount, $context->cart->id_currency);
			$seller_commission_amount = Tools::ps_round($seller_commission_amount, 2);
			if($seller_commission_amount<=0)$seller_commission_amount = 0;			$seller_payment_amount = $seller_sales_4payment[$id_seller] - $seller_commission_amount;
			$payments[$id_seller] = array('email'=>self::getSelllerEmail($id_seller),'amount'=>$seller_payment_amount, 'commission' => floatval($seller_commission_amount), 'payto' => self::getSelllerName($id_seller, $context->language->id));
						$total_commission_amount = $total_commission_amount + $seller_commission_amount;
		}
		
		$store_paypalemail = Configuration::get('AGILE_PAYPALPL_BUSINESS');
								if(!isset($payments[0]))
		{
			$payments[0] = array('email'=>$store_paypalemail, 'amount'=> $total_commission_amount, 'commission' => floatval($total_commission_amount), 'payto' => self::getSelllerName(0, $context->language->id));
		}
		else
		{
			$payments[0]['email'] = $store_paypalemail;
			$payments[0]['amount'] = floatval($payments[0]['amount']) + floatval($total_commission_amount);
			$payments[0]['commission'] =  floatval($total_commission_amount); 
			$payments[0]['payto'] = Configuration::get('PS_SHOP_NAME');
		}

		return $payments;    
	}
	
					public static function get_payment_info_paychained()
	{
		$context = Context::getContext();

		$seller_sales_4payment = AgileMultipleSeller::get_seller_sales_from_cart($context->cart->id, true, true, true);

		$include_shipping = (intval(Configuration::get('ASC_SHIPPING_INCLUDE'))==1);
		$include_tax = (intval(Configuration::get('ASC_TAX_INCLUDE'))==1);
		$after_discount = (intval(Configuration::get('ASC_VOUCHER_COST'))==AgileSellerCommission::VOUCHER_COST_AT_SELLER);	
		$seller_sales_4com = AgileMultipleSeller::get_seller_sales_from_cart($context->cart->id, $include_shipping, $after_discount, $include_tax);
				if(empty($seller_sales_4payment))return;
		$payments = array();
		$seller_commission_amount_total = 0;
		foreach($seller_sales_4com AS $id_seller=>$sales_amount_4com)
		{
			$seller_commission_amount = AgileSellerCommission::get_seller_commission_amount($id_seller,$sales_amount_4com, $context->cart->id_currency);
			if($seller_commission_amount<=0)$seller_commission_amount = 0;			$seller_payment_amount = $seller_sales_4payment[$id_seller] - $seller_commission_amount;
			$seller_commission_amount_total += $seller_commission_amount;
			if(floatval($seller_payment_amount)<0)return;
			$payments[$id_seller] = array('email'=>self::getSelllerEmail($id_seller),'amount'=>$seller_payment_amount, 'payto' => self::getSelllerName($id_seller, $context->language->id), 'commission' =>$seller_commission_amount);
					}
		
		$store_paypalemail = Configuration::get('AGILE_PAYPALAD_BUSINESS');
		if(empty($store_paypalemail))return;
		$payments[0] = array('email'=>$store_paypalemail, 'amount'=>$context->cart->getOrderTotal(true, Cart::BOTH), 'payto' => self::getSelllerName(0, $context->language->id), 'commission' => $seller_commission_amount_total);  				return $payments;
	}
	
			public static function get_seller_sales_from_order($id_order, $include_shppingcost = true, $after_discounts = false, $include_tax = true)
	{
		$order = new Order($id_order);
		if(!Validate::isLoadedObject($order))return;

		$seller_sales_amount = array();
		
		$order_total_product = 0;
		foreach ($order->getProducts() AS $product)
		{
			$id_owner = intval(AgileSellerManager::getObjectOwnerID('product',$product['product_id']));
			if(!isset($seller_sales_amount[$id_owner]))$seller_sales_amount[$id_owner] = 0;
			if($include_tax)
			{
				$lineamount = $product['unit_price_tax_incl'] * $product['product_quantity'];
			}
			else
			{
				$lineamount = $product['unit_price_tax_excl'] * $product['product_quantity'];
			}
			$seller_sales_amount[$id_owner] +=  $lineamount;

			$order_total_product +=  $lineamount;
		}
		if($order_total_product == 0)return $seller_sales_amount;


		$total_discounts = 0 - $order->total_discounts;
		if($after_discounts AND $total_discounts < 0)
		{
						foreach($seller_sales_amount AS $key=>$amount)
			{
				$seller_discount = $amount* $total_discounts/$order_total_product;
				$seller_sales_amount[$key] = Tools::ps_round($amount + $seller_discount, 2);
			}
		}

				$total_shipping = $order->total_shipping;
		if($include_shppingcost AND $total_shipping>0)
		{
						foreach($seller_sales_amount AS $key=>$amount)
			{
				
								$seller_shipping = ($include_tax? $order->total_shipping_tax_incl : $order->total_shipping_tax_excl);
				$seller_sales_amount[$key] = Tools::ps_round($amount + $seller_shipping, 2);
			}
		}
		
		return $seller_sales_amount;
	}
	
	public static function get_seller_sales_from_cart($id_cart, $include_shppingcost = true, $after_discounts = false, $include_tax = true)
	{
		$theCart = new Cart($id_cart);
		if(!Validate::isLoadedObject($theCart))return;

		$seller_sales_amount = array();
		
				$order_total_product = 0;
		foreach ($theCart->getProducts() AS $key => $product)
		{
			$id_owner = intval(AgileSellerManager::getObjectOwnerID('product',$product['id_product']));
			if(!isset($seller_sales_amount[$id_owner]))$seller_sales_amount[$id_owner] = 0;
			if($include_tax)
			{
				$lineamount = $product['total_wt'];
			}
			else
			{
				$lineamount = $product['total'];
			}
			$seller_sales_amount[$id_owner] +=  $lineamount;

			$order_total_product +=  $lineamount;
		}
		if($order_total_product == 0)return $seller_sales_amount;


		$total_discounts = abs($theCart->getOrderTotal(true,CART::ONLY_DISCOUNTS));
				if($after_discounts AND $total_discounts > 0)
		{
						foreach($seller_sales_amount AS $key=>$amount)
			{
				$seller_discount = $amount* $total_discounts/$order_total_product;
								$seller_sales_amount[$key] = Tools::ps_round($amount - $seller_discount, 2);
			}
		}

		$order_total_product_after_discount = 0;
		foreach($seller_sales_amount as $key => $amount)
		{
			$order_total_product_after_discount = $order_total_product_after_discount + $amount;
		}
		
				$total_shipping = floatval($theCart->getOrderTotal($include_tax, Cart::ONLY_SHIPPING)) + floatval($theCart->getOrderTotal($include_tax, Cart::ONLY_WRAPPING));
		
		if($include_shppingcost AND $total_shipping>0)
		{
			if (isset($theCart->id_address_delivery)
				AND $theCart->id_address_delivery
				AND Customer::customerHasAddress($theCart->id_customer, $theCart->id_address_delivery))
				$id_zone = Address::getZoneById((int)($theCart->id_address_delivery));
			else
			{
								$defaultCountry = new Country(Configuration::get('PS_COUNTRY_DEFAULT'), Configuration::get('PS_LANG_DEFAULT'));
				$id_zone = (int)$defaultCountry->id_zone;
			}
			
						foreach($seller_sales_amount AS $key=>$amount)
			{
								if(Module::isInstalled('agilesellershipping'))
				{
					$shipping = $theCart->getOrderShippingCostPerSeller($id_zone, $key, $include_tax);
				}
								else
				{
					$shipping = $amount* $total_shipping/$order_total_product_after_discount;
				}
				$seller_sales_amount[$key] = Tools::ps_round($amount + $shipping, 2);
			}
		}
		
		return $seller_sales_amount;
	}

	public static function getSellersByOrder($id_order)
	{
		$sql = '
            SELECT * 
            FROM ' . _DB_PREFIX_ . 'employee 
            WHERE id_employee in (SELECT distinct id_owner FROM ' . _DB_PREFIX_ . 'order_owner WHERE id_order=' . $id_order . ')
            ';
		return Db::getInstance()->ExecuteS($sql);
	}

	public static function getSellersByCart($id_cart)
	{
		$context = Context::getContext();
		$sql = '
            SELECT distinct IFNULL(po.id_owner,0) AS id_seller, CASE WHEN IFNULL(sl.`company`,\'\')=\'\' THEN CONCAT(e.firstname,\' \', e.lastname) ELSE sl.`company` END AS company 
            FROM ' . _DB_PREFIX_ . 'cart_product cp 
                LEFT JOIN ' . _DB_PREFIX_ . 'product_owner po ON cp.id_product=po.id_product  
                LEFT JOIN ' . _DB_PREFIX_ . 'sellerinfo s ON po.id_owner=s.id_seller  
                LEFT JOIN ' . _DB_PREFIX_ . 'sellerinfo_lang sl ON (sl.id_sellerinfo=s.id_sellerinfo AND sl.id_lang=' . intval($context->language->id). ')
	        	LEFT JOIN `'._DB_PREFIX_.'employee` e ON (po.`id_owner` = e.`id_employee`)
            WHERE cp.id_cart=' . intval($id_cart) . '
            ';

		return Db::getInstance()->ExecuteS($sql);
	}

	public static function getSingleSellerIDByCart($id_cart)
	{
		$sellers = self::getSellersByCart($id_cart);
		if(!isset($sellers) OR empty($sellers))return 0;
		return intval($sellers[0]['id_seller']);
	}
	
			public static function validate_coupon_error($discountObj)
	{
		if(intval(Configuration::get('AGILE_MS_PAYMENT_MODE')) != self::PAYMENT_MODE_SELLER)return false; 		if(intval(Configuration::get('AGILE_MS_CART_MODE')) != self::CART_MODE_MULTIPLE_SELLER)return false; 
		$seller_sales = self::get_seller_sales_from_cart($context->cart->id, false,false);
		if(empty($seller_sales) OR count($seller_sales)<=1)return false; 		$smallest_seller_sale = min($seller_sales);
				if($discountObj->id_discount_type == 1 AND $smallest_seller_sale >= $discountObj->minimal)return false;
// LOONES		return $this->l('The coupon can not bsued in multiple seller cart, because there is minium ');	
					return self::l('The coupon can not bsued in multiple seller cart, because there is minium ');		

	}	
	
			public static function split_shopping_cart($id_cart, $sellers)
	{
		$cartinfos = array();
		if(intval($id_cart)<=0 OR empty($sellers))return $cartinfos;
		
		$bigcart = new Cart($id_cart);
		$bigcart_discount = abs(floatval($bigcart->getOrderTotal(true, Cart::ONLY_DISCOUNTS)));
		$bigcart_total_products = $bigcart->getOrderTotal(true, Cart::ONLY_PRODUCTS, null, null, false);
				
		$message = Message::getMessageByCartId($id_cart);
		$id_message = 0;
		if(isset($message['id_message']))
			$id_message = intval($message['id_message']);

		
		foreach($sellers AS $seller)
		{
			$id_seller = intval($seller['id_seller']); 						$cartinfos[] = self::generate_subacart($id_cart, 0,$id_message, $bigcart_discount, $bigcart_total_products, $id_seller);
		} 
				$bigcart->delete();
		return $cartinfos;
		
	}
	
	
	public static function copy_customization_data_for_subcart($id_cart_from, $id_cart_to)
	{
		$sql = 'SELECT `id_customization`,`id_product`, `id_product_attribute`, `quantity`, `id_address_delivery`, `in_cart` FROM `' . _DB_PREFIX_ . 'customization` WHERE id_cart =' . (int)$id_cart_from . ' AND id_product IN (SELECT DISTINCT id_product FROM ' . _DB_PREFIX_ . 'cart_product WHERE id_cart=' . $id_cart_to . ')';
				$rows = Db::getInstance()->ExecuteS($sql);
		if(empty($rows))return;
		foreach($rows as $row)
		{
			$sql ='INSERT INTO `'._DB_PREFIX_.'customization` (`id_cart`, `id_product`, `id_product_attribute`, `quantity`, `id_address_delivery`,`in_cart`) VALUES ('.(int)$id_cart_to .', '.(int)$row['id_product'].', '.(int)$row['id_product_attribute'].', '.(int)$row['quantity'].',' . (int)$row['id_address_delivery'] . ',' . (int)$row['in_cart'] .')';
			Db::getInstance()->Execute($sql);

			$sql = 'SELECT id_customization FROM ' . _DB_PREFIX_ . 'customization WHERE id_cart= '. (int)$id_cart_to . ' AND id_product=' . (int)$row['id_product']. ' AND id_product_attribute=' . (int)$row['id_product_attribute'];
			$new_id = (int)Db::getInstance()->getValue($sql);

			$sql = 'SELECT cd.id_customization, cd.type, cd.index, cd.value FROM ' . _DB_PREFIX_ . 'customization c LEFT JOIN ' . _DB_PREFIX_ . 'customized_data cd ON c.id_customization=cd.id_customization WHERE id_cart= '. (int)$id_cart_from . ' AND id_product=' . (int)$row['id_product']. ' AND id_product_attribute=' . (int)$row['id_product_attribute'];
			$from_datas = Db::getInstance()->ExecuteS($sql);
			foreach($from_datas AS $from_data)
			{		
				$sql ='INSERT INTO `'._DB_PREFIX_.'customized_data` (`id_customization`, `type`, `index`, `value`) VALUES('. $new_id  .',' . $from_data['type']. ',' . $from_data['index']. ',\'' . $from_data['value']. '\')';
				Db::getInstance()->Execute($sql);
			}
		}
	}

	protected static function generate_subacart($id_cart, $existing_id,  $id_message, $bigcart_discount, $bigcart_total_products, $id_seller)
	{
		if(Module::isInstalled('agileprepaidcredit'))
		{
			require_once(_PS_ROOT_DIR_ . "/modules/agileprepaidcredit/agileprepaidcredit.php");	
		}
						$smallcart = new Cart($id_cart);
		$smallcart->id = 0;
		if(intval($existing_id)>0)
		{
						$smallcart->id = $existing_id;
						$sql = 'DELETE FROM ' . _DB_PREFIX_ . 'cart_product WHERE id_cart=' . intval($existing_id);
			Db::getInstance()->Execute($sql);
						if(Module::isInstalled('agilesellershipping'))
			{
				$sql = 'DELETE FROM ' . _DB_PREFIX_ . 'agile_cartcarrier WHERE id_cart=' . intval($existing_id);
				Db::getInstance()->Execute($sql);
			}
			
						$sql = 'DELETE FROM ' . _DB_PREFIX_ . 'customized_data WHERE id_customization IN (SELECT id_customization FROM ' . _DB_PREFIX_ . 'customization WHERE id_cart=' . intval($existing_id) . ')';
			Db::getInstance()->Execute($sql);

			$sql = 'DELETE FROM ' . _DB_PREFIX_ . 'customization WHERE id_cart=' . intval($existing_id);
			Db::getInstance()->Execute($sql);
			
						$sql = 'DELETE FROM ' . _DB_PREFIX_ . 'cart_cart_rule WHERE id_cart=' . intval($existing_id);
			Db::getInstance()->Execute($sql);
						if(Module::isInstalled('agileprepaidcredit'))
			{
				$sql = 'DELETE FROM ' . _DB_PREFIX_ . 'cart_tokens WHERE id_cart=' . intval($existing_id);
				Db::getInstance()->Execute($sql);
			}			
		}
		$smallcart->save();
		
				$newfields = '';
		$newfields = ',id_address_delivery';
		$sql = 'INSERT INTO ' . _DB_PREFIX_ . 'cart_product (id_cart,id_product,id_product_attribute,quantity,date_add' . $newfields . ')
                    SELECT ' .$smallcart->id . ' AS id_cart, cp.id_product,cp.id_product_attribute,cp.quantity,cp.date_add ' . $newfields . '
                    FROM ' . _DB_PREFIX_ . 'cart_product cp
                        LEFT JOIN ' . _DB_PREFIX_ . 'product_owner po ON cp.id_product=po.id_product 
                    WHERE cp.id_cart=' . intval($id_cart) . ' 
                        AND  IFNULL(po.id_owner,0)=' . intval($id_seller) . '
            ';
				Db::getInstance()->Execute($sql);
		
				if(Module::isInstalled('agilesellershipping'))
		{
			$sql = 'REPLACE INTO ' . _DB_PREFIX_ . 'agile_cartcarrier (id_cart,id_product,id_product_attribute,id_carrier,date_add)
                        SELECT ' .$smallcart->id . ' AS id_cart, cc.id_product,cc.id_product_attribute,cc.id_carrier,cc.date_add
                        FROM ' . _DB_PREFIX_ . 'agile_cartcarrier cc
                            LEFT JOIN ' . _DB_PREFIX_ . 'product_owner po ON cc.id_product=po.id_product 
                        WHERE id_cart=' . $id_cart . ' 
                            AND  IFNULL(po.id_owner,0)=' . intval($id_seller) . '
                ';
			Db::getInstance()->Execute($sql);
		}                        
				if($id_message>0)
		{
			$newmessage = new Message($id_message);
						$newmessage->id = 0;
			$newmessage->id_cart =  $smallcart->id;
			$newmessage->save();
		}
		
				self::copy_customization_data_for_subcart($id_cart, $smallcart->id);

				$smallcart_total_products = $smallcart->getOrderTotal(true, Cart::ONLY_PRODUCTS, null, null, false);
		$smallcart_total_shipping = $smallcart->getOrderTotal(true, Cart::ONLY_SHIPPING, null, null, false);		
		
				$bigcart_token_value = 0;
		$tokens_value = 0;
		if(Module::isInstalled('agileprepaidcredit'))
		{
			$bigcart_tokens_used = AgilePrepaidCredit::tokens_used_in_cart($id_cart);
			if($bigcart_tokens_used > 0)
				$bigcart_token_value =  AgilePrepaidCredit::ConvertTokens2Price($bigcart_tokens_used,$smallcart->id_currency);

			$tokens_used = 0;
			$tokens_value = 0;
			if($bigcart_total_products > 0)
			{
				$tokens_used = $bigcart_tokens_used * $smallcart_total_products / $bigcart_total_products;
				$tokens_value = $bigcart_token_value * $smallcart_total_products / $bigcart_total_products;
			}
			$sql = 'INSERT INTO ' . _DB_PREFIX_ . 'cart_tokens (id_cart, tokens) VALUES(' . $smallcart->id . ',' . round(floatval($tokens_used),9) . ')';
			Db::getInstance()->Execute($sql);	
		}
				
																$discountamount = 0;
		if($bigcart_total_products>0) $discountamount =  ($bigcart_discount - $bigcart_token_value) * $smallcart_total_products / $bigcart_total_products;
		if(Module::isInstalled('agileprepaidcredit') AND AgilePrepaidCredit::is_token_payment_underway($id_cart) AND $bigcart_total_products>0)
		{
						$discountamount =  $bigcart_discount  * $smallcart_total_products / $bigcart_total_products;
		}
		
				if($discountamount >0)
		{
									$discount = self::create_split_discount($discountamount, $smallcart->id_customer,$smallcart->id_currency, $id_cart . '-' . $smallcart->id);
			$sql = 'INSERT INTO ' . _DB_PREFIX_ . 'cart_cart_rule (id_cart, id_cart_rule) VALUES(' . $smallcart->id . ',' . $discount->id. ')';
			Db::getInstance()->Execute($sql);
		}
		
						$smallcart_total = $smallcart_total_shipping + $smallcart_total_products - $discountamount - $tokens_value;
		if(Module::isInstalled('agileprepaidcredit') AND AgilePrepaidCredit::is_token_payment_underway($id_cart))
		{
									$smallcart_total =  $smallcart_total_shipping + $smallcart_total_products - $discountamount;
		}
				
		$cartinfo = array('id_cart'=>$smallcart->id, 'amountPaid'=>$smallcart_total);
		
		$sql = 'REPLACE INTO ' . _DB_PREFIX_ . 'agile_subcart (id_cart, id_cart_parent,id_seller,id_order,progress,date_add) VALUES (' . $cartinfo['id_cart'] . ',' . $id_cart . ',' . intval($id_seller) . ',0,0,\'' . date('Y-m-d H:i:s') . '\')';
		Db::getInstance()->Execute($sql);
		
		if(Module::isInstalled('agilecashondelivery') && Module::isEnabled('agilecashondelivery'))
		{
			$cart = new Cart($id_cart);
			if(!$cart->id) return;
			$sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'agile_subcart  where id_cart_parent='.$cart->id." AND id_seller=".intval($id_seller);
			$subcarts = Db::getInstance()->ExecuteS($sql);
			include_once(_PS_ROOT_DIR_  . "/modules/agilecashondelivery/AgileCartCod.php");
			include_once(_PS_ROOT_DIR_  . "/modules/agilecashondelivery/AgileCarrierCod.php");
			include_once(_PS_ROOT_DIR_  . "/modules/agilecashondelivery/AgileCarrierCodFee.php");
			$agileCartCod = new AgileCartCod();
			$agileCartCodFee = new AgileCarrierCodFee();
			if(Module::isInstalled('agilesellershipping'))
			{
				include_once(_PS_ROOT_DIR_  . "/modules/agilesellershipping/SellerShipping.php");
				$id_zone = SellerShipping::getZoneID($cart->id_address_delivery, $cart->id_customer);
				if(!empty($subcarts))
				{
					foreach ($subcarts as $cart1)
					{ 
						$sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'agile_cartcarrier  where id_cart='.$cart1['id_cart'];
						$carriers = Db::getInstance()->ExecuteS($sql); 
						$carriers_id = array();
						$agileCartCod->deleteByCartId($cart1['id_cart']);
						foreach($carriers as $carrier)
						{
							if(!in_array($carrier['id_carrier'], $carriers_id))
							{
								$cod_fee = $agileCartCodFee->getCodFee($carrier['id_carrier'], $id_zone, new Cart((int)$cart1['id_cart']));
								$agileCartCod->addCartCod($carrier['id_cart'],$carrier['id_carrier'],1, $cod_fee);
								$carriers_id[] = $carrier['id_carrier'];
								$cartinfo['amountPaid'] += $cod_fee;
							}
						}
					}
				}
			} 
			else 
			{
				$context = Context::getContext();
				$defaultCountry = $context->country;
				if (isset($cart->id_address_delivery) AND $cart->id_address_delivery AND Customer::customerHasAddress($cart->id_customer, $cart->id_address_delivery)) 
				{
					$id_zone = Address::getZoneById((int)($cart->id_address_delivery));
				} 
				else
				{
					if (!Validate::isLoadedObject($defaultCountry)) 
					{
						$defaultCountry = new Country(Configuration::get('PS_COUNTRY_DEFAULT'), Configuration::get('PS_LANG_DEFAULT'));
					}
					$id_zone = (int)$defaultCountry->id_zone;
				}
				$carrierCod = new AgileCarrierCod();
				$id_carrier = AgileCartCod::getCarrierByCartId($cart->id);
				if(!$id_carrier) return;
				if(!empty($subcarts))
				{
					foreach ($subcarts as $cart1)
					{ 
						$agileCartCod->deleteByCartId($cart1['id_cart']);
						if($carrierCod->supportCod($id_carrier))
						{
							$cod_fee = $agileCartCodFee->getCodfee($id_carrier,$id_zone, new Cart((int)$cart1['id_cart']));
							$agileCartCod->addCartCod($cart1['id_cart'],$id_carrier,1, $cod_fee);
						}
					}
				}
			}
		}

		return $cartinfo;

	}
	
	private static function create_split_discount($amount,$id_customer, $id_currency, $id_carts)
	{
		$context = Context::getContext();
		$code = 'split discount-' . $id_carts;
		if(CartRule::cartRuleExists($code))
		{
			$cartRuleInfo = CartRule::getCartsRuleByCode($code, $context->language->id);
			if(!empty($cartRuleInfo))
			{
				$discount = new CartRule((int)$cartRuleInfo[0]['id_cart_rule']);
				$discount->reduction_amount = Tools::ps_round(abs($amount),2);
				$discount->id_customer = $id_customer;
				$discount->reduction_currency = $id_currency;
				$discount->save();
				return $discount;
			}
		}
		
		$timeinfo = AgileHelper::getDbDateTime(1, 'DAY');
				$languages = Language::getLanguages();

		$discount = new CartRule();
		$discount->code = 'split discount-' . $id_carts;
		$discount->description = $discount->code;
		$discount->id_customer = $id_customer;
		$discount->id_group = 0;
		$discount->reduction_currency = $id_currency;
		$discount->reduction_amount = Tools::ps_round(abs($amount),2);
		$discount->minimum_amount_currency = $id_currency;
		$discount->quantity = 1;
		$discount->quantity_per_user = 1;
		$discount->date_from = $timeinfo['timenow'];
		$discount->date_to = $timeinfo['newtime'];
		$discount->partial_use = 0;
		$discount->reduction_tax = 1;
		$discount->active = 1;
		$discount->cart_display = 0;
		$discount->date_add =  $timeinfo['timenow'];
		$discount->date_upd =  $timeinfo['timenow'];

		foreach ($languages AS $language)
		{
			$fields = array('name');
			foreach($fields AS $field)
				$discount->{$field}[intval($language['id_lang'])] = $discount->code;
		}
		$discount->save();

				return $discount;
	}
	
	public static function getSpecialCatrgoryIds()
	{
		$specialIds = '';
		if(Module::isInstalled('agilemembership'))
		{
			if(!empty($specialIds))$specialIds .= ',';
			$specialIds .= intval(Configuration::get('AGILE_MEMBERSHIP_CID'));
		}
		if(Module::isInstalled('agileprepaidcredit'))
		{
			if(!empty($specialIds))$specialIds .= ',';
			$specialIds .= intval(Configuration::getGlobalValue('AGILE_PCREDIT_CID'));
		}
		if(Module::isInstalled('agilesellerlistoptions'))
		{
			if(!empty($specialIds))$specialIds .= ',';
			$specialIds .= intval(Configuration::get('ASLO_CATEGORY_ID'));
		}
		return $specialIds;
		
	}

	public static function getSpecialCatrgoryIdsArray()
	{
		$specialIds = array();
		if(Module::isInstalled('agilemembership'))
		{
			$specialIds[] = intval(Configuration::get('AGILE_MEMBERSHIP_CID'));
		}
		if(Module::isInstalled('agileprepaidcredit'))
		{
			$specialIds[] = intval(Configuration::getGlobalValue('AGILE_PCREDIT_CID'));
		}
		if(Module::isInstalled('agilesellerlistoptions'))
		{
			$specialIds[] = intval(Configuration::get('ASLO_CATEGORY_ID'));
		}
		return $specialIds;
		
	}
	
			public static function create_subcart_for_seller($id_seller)
	{
		$context = Context::getContext();
		$id_cart = $context->cart->id;
		
		$bigcart = new Cart($id_cart);
		$bigcart_discount = abs(floatval($bigcart->getOrderTotal(true, Cart::ONLY_DISCOUNTS)));
		$bigcart_total_products = $bigcart->getOrderTotal(true, Cart::ONLY_PRODUCTS, null, null, false);
				
		$message = Message::getMessageByCartId($id_cart);
		$id_message = 0;
		if(isset($message['id_message']))
			$id_message = intval($message['id_message']);

		$existing_id = self::get_subcart_id($id_cart,$id_seller);
				$cartinfo = self::generate_subacart($id_cart, $existing_id, $id_message, $bigcart_discount, $bigcart_total_products, $id_seller);	
		return $cartinfo;
		
	}
	
	public static function get_unpaid_sellers()
	{	
		$context = Context::getContext();
		
		$sql = 'SELECT id_owner, sl.company, id_product,IFNULL(sc.id_cart,-1),IFNULL(sc.progress,0)
			FROM (
				SELECT po.id_owner,max(po.id_product) AS id_product 
				FROM  `' . _DB_PREFIX_ . 'cart_product` cp
					LEFT JOIN ' . _DB_PREFIX_ . 'product_owner po ON cp.id_product = po.id_product
				WHERE 1 
					AND cp.id_cart= ' . (int)($context->cart->id) . '
				Group by id_owner
			)AS T1
				LEFT JOIN ' . _DB_PREFIX_ . 'agile_subcart sc ON (T1.id_owner = sc.id_seller AND sc.id_cart_parent = ' . (int)($context->cart->id) . ' AND IFNULL(sc.id_order,0)=0 )
				LEFT JOIN ' . _DB_PREFIX_ . 'sellerinfo s ON T1.id_owner = s.id_seller
				LEFT JOIN ' . _DB_PREFIX_ . 'sellerinfo_lang sl ON (s.id_sellerinfo = sl.id_sellerinfo AND sl.id_lang=' . intval($context->language->id). ')
			ORDER BY IFNULL(sc.id_cart,-1) DESC		
		';
				$rows = Db::getInstance()->ExecuteS($sql);
		return $rows;		
	}
	
		public static function backup_cart_for_subcart_payment($context)
	{
		if(Configuration::get('AGILE_MS_PAYMENT_MODE') == self::PAYMENT_MODE_SELLER AND intval(Tools::getValue('id_subcart')) > 0)
		{
			$id_backup = $context->cart->id;
			$context->cookie->id_cart = intval(Tools::getValue('id_subcart'));
			$context->cart = new Cart($context->cookie->id_cart);
			return $id_backup;
		}
		return 0;
	}	
	
	public static function restore_cart_for_subcart_payment($context, $id_backup)
	{
		if(Configuration::get('AGILE_MS_PAYMENT_MODE') == self::PAYMENT_MODE_SELLER AND intval(Tools::getValue('id_subcart')) > 0)
		{
			$context->cookie->id_cart = $id_backup;
			$context->cart = new Cart($context->cookie->id_cart);
		}
	}
	
	public static function restotre_check_after_subcart_payment($context, $id_backup)
	{
		if(Configuration::get('AGILE_MS_PAYMENT_MODE') == self::PAYMENT_MODE_SELLER AND intval(Tools::getValue('id_subcart')) > 0)
		{
			AgileMultipleSeller::update_subcart_progress(intval(Tools::getValue('id_subcart')), 1);	
			$context->cookie->id_cart = $id_backup;
			$context->cart = new Cart($context->cookie->id_cart);
						$sql = 'SELECT count(*) FROM ' . _DB_PREFIX_ . 'cart_product WHERE id_cart=' . (int)$context->cart->id;
						$nbr = Db::getInstance()->getValue($sql);
			if($nbr ==0)
			{
												$context->cart->delete();
								$context->cookie->id_cart =  0;
			}
		}
	}
	
		public static function get_subcart_id($id_parent,$id_seller)
	{
		$sql = 'SELECT id_cart FROM ' . _DB_PREFIX_ . 'agile_subcart WHERE id_cart_parent=' . intval($id_parent) . ' AND IFNULL(id_order,0)=0  AND id_seller=' . intval($id_seller);
		$id_cart = Db::getInstance()->getValue($sql);
		return $id_cart;
	}


		public static function update_subcart_progress($id_cart, $progress)
	{
		$sql = 'UPDATE ' . _DB_PREFIX_ . 'agile_subcart SET progress=' . $progress. ' WHERE id_cart=' . intval($id_cart);
				Db::getInstance()->Execute($sql);
	}


	public static function get_subcart_parentid($id_cart)
	{
		$sql = 'SELECT id_cart_parent FROM ' . _DB_PREFIX_ . 'agile_subcart WHERE id_cart=' . intval($id_cart);
		$id_cart_parent = Db::getInstance()->getValue($sql);
		return $id_cart_parent;
	}

	
			public static function remove_subcart_items_from_maincart($id_cart, $id_order)
	{
		$sql = 'UPDATE ' . _DB_PREFIX_ . 'agile_subcart SET id_order=' . intval($id_order) . ', progress=2 WHERE id_cart=' . intval($id_cart);
		Db::getInstance()->Execute($sql);

		$id_cart_parent =  self::get_subcart_parentid($id_cart);
				
		$records = Db::getInstance()->ExecuteS('SELECT id_product FROM ' . _DB_PREFIX_ . 'cart_product WHERE id_cart=' . intval($id_cart) );
		if(!empty($records))
		{
			foreach($records AS $record)
			{
				$sql = 'DELETE FROM ' . _DB_PREFIX_ . 'cart_product WHERE id_cart= ' . $id_cart_parent . ' AND id_product =' . $record['id_product'];
								Db::getInstance()->Execute($sql);
				
				if(Module::isInstalled('agilesellershipping'))
				{
					$sql = 'DELETE FROM ' . _DB_PREFIX_ . 'agile_cartcarrier WHERE id_cart= ' . $id_cart_parent . ' AND id_product=' . $record['id_product'];
										Db::getInstance()->Execute($sql);
				}
			}
		}
		
				if(Module::isInstalled('agileprepaidcredit'))
		{
			require_once(_PS_ROOT_DIR_ . "/modules/agileprepaidcredit/agileprepaidcredit.php");	
			$tokens = AgilePrepaidCredit::tokens_used_in_cart($id_cart);
			$sql = 'UPDATE ' . _DB_PREFIX_ . 'cart_tokens SET tokens = tokens - ' . round(floatval($tokens),0) . ' WHERE id_cart=' . intval($id_cart_parent);
			Db::getInstance()->Execute($sql);
		}
		
			}
	

	public function hookSubcartPaymentInfo($modulename, $formid, $is_parallel_payment= false)
	{
				$_SESSION[self::SUBCART_SESSION_KEY] = 0;
		if((intval(Configuration::get('AGILE_MS_PAYMENT_MODE')) == self::PAYMENT_MODE_SELLER) OR $formid == 'parallel_confirm_form')
		{
						include_once(_PS_ROOT_DIR_  . "/modules/agilemultipleseller/SellerInfo.php");
			include_once(_PS_ROOT_DIR_  . "/modules/agilemultipleseller/AgileSellerPaymentInfo.php");
			
			$unpaid_sellers = self::get_unpaid_sellers();

			if(!empty($unpaid_sellers) AND count($unpaid_sellers)>0)
			{
				$recepients = array();

				foreach($unpaid_sellers AS $unpaid_seller)
				{
					$support_payment = 1;
					$id_seller = $unpaid_seller['id_owner'];
					if($id_seller > 0 &&  $formid != 'parallel_confirm_form')
					{	
						$payment_modulename = $modulename;
						$paymentinfo = AgileSellerPaymentInfo::getForSellerByModuleName($payment_modulename,$id_seller);
						if(!Validate::isLoadedObject($paymentinfo))
						{
							$support_payment = 0;
						}
						else
						{
							if($paymentinfo->in_use <> 1)
							{
								$support_payment = 0;
							}
						}
					}

										$cartinfo = AgileMultipleSeller::create_subcart_for_seller($id_seller);
					$seller_name = '';
					if($id_seller == 0)$seller_name = Configuration::get('PS_SHOP_NAME');
					else
					{
						$sellerinfo = new SellerInfo(SellerInfo::getIdBSellerId($id_seller), $this->context->language->id);
						$seller_name = (isset($sellerinfo)?$sellerinfo->company : $this->l('Unknow'));
					}
					
										Cache::clean("Cart::getCartRules" . $cartinfo['id_cart'] . "-*");
					$subcart = new Cart($cartinfo['id_cart']);		

					$subcart_total = $subcart->getOrderTotal(true, Cart::BOTH, null, null, false);
					$subcart_discounts = $subcart->getOrderTotal(true, Cart::ONLY_DISCOUNTS, null, null, false);
					
					$total_tax = $subcart->getOrderTotal(true,  Cart::BOTH) - $subcart->getOrderTotal(false, Cart::BOTH);
					$moduleinstance = Module::getInstanceByName($modulename);
					$other_info = '';
					if(Validate::isLoadedObject($moduleinstance) && method_exists($moduleinstance, "getAdditionalInfo"))
					{
						$other_info = $moduleinstance->getAdditionalInfo($id_seller);
					}
					
					$recepients[] = array('id_seller' => $id_seller, 'support_payment'=> $support_payment, 'seller_name' => $seller_name, 'products' => $subcart->getProducts(), 'id_subcart' =>$subcart->id, 'total_tax'=>$total_tax, 'shippingCost'=>$subcart->getOrderTotal(true, Cart::ONLY_SHIPPING), 'subcart_total'=>$subcart_total,'subcart_totaldiscounts'=>$subcart_discounts, 'other_info' =>$other_info);					
				}
				$existSupportPayment = false;
				
				foreach($recepients AS $recepient)
				{
					if($recepient['support_payment'] == 1){
						$existSupportPayment = true;
						break;
					}
				}
				if(!$existSupportPayment) return '';
				
				$this->context->smarty->assign(array(
					'recepients' => $recepients,
					'modulename'=>$modulename,
					'moduleformid' => $formid,
					'is_parallel_payment' => $is_parallel_payment,
					));
				return $this->display(__FILE__, 'views/templates/hook/payment-subcart.tpl');								
			}
		}
	}

	
	

	protected function assign_existing_objects()
	{
				$entities = array("category","product","carrier","customer");
		foreach($entities as $entity)
		{
						$sql = 'SELECT id_' . $entity . ' FROM ' . _DB_PREFIX_ . $entity . ' WHERE id_' . $entity . ' NOT IN (SELECT id_' . $entity . ' FROM ' . _DB_PREFIX_ . $entity. '_owner)';
			$rows = Db::getInstance()->ExecuteS($sql);
			if(!empty($rows))
			{
				$sql = 'INSERT INTO ' . _DB_PREFIX_ . $entity . '_owner (id_' . $entity . ', id_owner, date_add) VALUES';
				$cnt = 0;
				foreach($rows as $row)
				{
					if($cnt > 0)$sql .= ',';
					$sql .= ' (' . $row['id_' . $entity]. ',0,  CURRENT_TIMESTAMP)';
					$cnt++;
				}
				Db::getInstance()->Execute($sql);
			}
		}
		
				$sql = "INSERT INTO " . _DB_PREFIX_ . "object_owner (id_object,entity, id_owner)
			SELECT id_attribute_group as id_object,'attribute_group' as entity, 0 as id_owner 
			FROM `" . _DB_PREFIX_ . "attribute_group` a
			LEFT JOIN " . _DB_PREFIX_ . "object_owner oo ON (a.id_attribute_group = oo.id_object AND oo.entity = 'attribute_group')
			WHERE oo.id_owner IS NULL
			";
		Db::getInstance()->Execute($sql);

		$sql = "INSERT INTO " . _DB_PREFIX_ . "object_owner (id_object,entity, id_owner)
			SELECT id_attribute as id_object,'attribute' as entity, 0 as id_owner 
			FROM `" . _DB_PREFIX_ . "attribute` a 
			LEFT JOIN " . _DB_PREFIX_ . "object_owner oo ON (a.id_attribute = oo.id_object AND oo.entity = 'attribute')
			WHERE oo.id_owner IS NULL
		";
		Db::getInstance()->Execute($sql);

	}


	public static function make_fund_request($id_customer, $amount_requested, $desc)
	{
		$context = Context::getContext();
		
		if(!Module::isInstalled('agileprepaidcredit'))return;
		if(!Module::isInstalled('agilesellercommission'))return;

		if($amount_requested <=0)
			return Tools::displayError('Invalid request amount.');

		$sellerinfo = new SellerInfo(SellerInfo::getIdByCustomerId($id_customer));
		if(!Validate::isLoadedObject($sellerinfo))
			return Tools::displayError('Seller info can not be found.');

		$account_balance = AgileSellerManager::getAccountBalance($sellerinfo->id_seller);
		if($account_balance < $amount_requested)
			return Tools::displayError('You do not have enough account balance.');
		
		$currency = new Currency( Configuration::get('ASC_COMMISSION_CURRENCY'));
		
		include_once(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/SellerInfo.php");
		$configuration = Configuration::getMultiple(array('PS_SHOP_EMAIL', 'PS_SHOP_NAME'));

		$id_lang = $context->language->id;     
		$iso = Language::getIsoById((int)($id_lang));
		$id_sellerinfo = SellerInfo::getIdByCustomerId($id_customer);
		$sellerinfo = new SellerInfo($id_sellerinfo, $context->language->id);
		$employee = new Employee($sellerinfo->id_seller);
		if(!Validate::isLoadedObject($employee))
//LOONES	return $this->l('Seller informaiton not found.');
			return self::l('Seller informaiton not found.');
		$templateVars = array(
			'{firstname}' => $employee->firstname,
			'{lastname}' => $employee->lastname,
			'{seller_company}' => $sellerinfo->company,
			'{seller_id}' => $sellerinfo->id_seller,
			'{seller_address}' => $sellerinfo->fulladdress($id_lang),
			'{email}' => $employee->email,
			'{amount_requested}' => Tools::displayprice($amount_requested, $currency),
			'{shop_name}' => Configuration::get('PS_SHOP_NAME'),
			'{shop_url}'=>Tools::getShopDomainSsl(true, true).__PS_BASE_URI__,
			'{shop_logo}' => Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.'img/logo.jpg'
			);
		$temp_folder = _PS_ROOT_DIR_.'/modules/agilemultipleseller/mails/';
		if ( !file_exists($temp_folder .$iso.'/fund_request.txt') OR !file_exists($temp_folder .$iso.'/fund_request.html'))
		{
			$id_lang = (int)(Configuration::get('PS_LANG_DEFAULT'));
			$iso = Language::getIsoById($id_lang);
		}
		if (file_exists($temp_folder .$iso.'/fund_request.txt') AND file_exists($temp_folder .$iso.'/fund_request.html'))
		{
			AgileMultipleSellerMailer::SendTranslateSubject($id_lang, 'fund_request', $templateVars, $configuration['PS_SHOP_EMAIL'], $configuration['PS_SHOP_NAME'], $employee->email,  $employee->firstname . ' ' . $employee->lastname, NULL, NULL, $temp_folder);

			require_once(_PS_ROOT_DIR_ . "/modules/agilesellercommission/SellerCommission.php");
			$currency = new Currency( Configuration::get('ASC_COMMISSION_CURRENCY'));
						$amount_requested = $amount_requested / $currency->conversion_rate;
			
			SellerCommission::addCreditMemoRecord($sellerinfo->id_seller, SellerCommission::RECORD_TYPE_SELLER_DEBIT, $amount_requested, $desc);
		}
	}

	public static function convert_tokens_to_balance($id_customer, $tokens_to_convert, $desc)
	{
		$context = Context::getContext();

		if(!Module::isInstalled('agileprepaidcredit'))return;
		if(!Module::isInstalled('agilesellercommission'))return;

		require_once(_PS_ROOT_DIR_ . "/modules/agileprepaidcredit/agileprepaidcredit.php");
		require_once(_PS_ROOT_DIR_ . "/modules/agileprepaidcredit/CustomerCredit.php");
		require_once(_PS_ROOT_DIR_ . "/modules/agilesellercommission/SellerCommission.php");

		if($tokens_to_convert <=0)
			return Tools::displayError('Invalid convert amount.');

		$sellerinfo = new SellerInfo(SellerInfo::getIdByCustomerId($id_customer));
		if(!Validate::isLoadedObject($sellerinfo))
			return Tools::displayError('Seller info can not be found.');
		
		$tokens_balance = AgilePrepaidCredit::GetTokenBalance($context->customer->id);
		if($tokens_balance < $tokens_to_convert)
			return Tools::displayError('You do not have enough account balance.');
		
		$currency = new Currency( Configuration::get('ASC_COMMISSION_CURRENCY'));
		$tokens_value = AgilePrepaidCredit::ConvertTokens2Price($tokens_to_convert, $currency->id);

		$cc = new CustomerCredit();
		$cc->id = 0;
		$cc->id_customer = $context->customer->id;
		$cc->id_order = 0;
		$apcm = new AgilePrepaidCredit();
		$cc->id_reason = AgilePrepaidCredit::CREDIT_REASON_TO_SELLERCOMMISSION;
		$cc->units = 0 - $tokens_to_convert;
		$cc->date_add = date('Y-m-d H:i:s');
		$cc->add();

		SellerCommission::addCreditMemoRecord($sellerinfo->id_seller, SellerCommission::RECORD_TYPE_SELLER_CREDIT, $tokens_value, $desc);
		
		return '';			
		

	}
	
	public static function convert_balance_to_token($id_customer, $amount_to_convert, $desc)
	{
		$context = Context::getContext();

		if(!Module::isInstalled('agileprepaidcredit'))return;
		if(!Module::isInstalled('agilesellercommission'))return;

		require_once(_PS_ROOT_DIR_ . "/modules/agileprepaidcredit/agileprepaidcredit.php");
		require_once(_PS_ROOT_DIR_ . "/modules/agileprepaidcredit/CustomerCredit.php");
		require_once(_PS_ROOT_DIR_ . "/modules/agilesellercommission/SellerCommission.php");
		require_once(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/SellerInfo.php");

		if($amount_to_convert <=0)
			return Tools::displayError('Invalid convert amount.');

		$sellerinfo = new SellerInfo(SellerInfo::getIdByCustomerId($id_customer));
		if(!Validate::isLoadedObject($sellerinfo))
			return Tools::displayError('Seller info can not be found.');

		$account_balance = AgileSellerManager::getAccountBalance($sellerinfo->id_seller);
		if($account_balance < $amount_to_convert)
			return Tools::displayError('You do not have enough account balance.');

		$currency = new Currency( Configuration::get('ASC_COMMISSION_CURRENCY'));
				$token_units =  AgilePrepaidCredit::ConvertPrice2Tokens($currency->id, $amount_to_convert);

		$cc = new CustomerCredit();
		$cc->id = 0;
		$cc->id_customer = $context->customer->id;
		$cc->id_order = 0;
		$apcm = new AgilePrepaidCredit();
		$cc->id_reason = AgilePrepaidCredit::CREDIT_REASON_FROM_SELLERCOMMISSION;
		$cc->units = $token_units;
		$cc->date_add = date('Y-m-d H:i:s');
		$cc->add();

		SellerCommission::addCreditMemoRecord($sellerinfo->id_seller, SellerCommission::RECORD_TYPE_SELLER_DEBIT, $amount_to_convert, $desc);
		
		return '';			
	}	


	public static function sendProductApproveNotification($id_seller, $product, $approved) 
	{
		if(intval(Configuration::get('AGILE_MS_PRODUCT_APPROVAL')) != 1)return;
		if(intval(Configuration::get('AGILE_MS_PRODUCT_APPROVAL_NOTICE')) != 1)return;
		$context = Context::getContext();

		include_once(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/SellerInfo.php");
		$template = $approved? 'product_approval' : 'product_disapproval';
		$configuration = Configuration::getMultiple(array('PS_SHOP_EMAIL', 'PS_SHOP_NAME'));

		$employee = new Employee($id_seller);
		if(!Validate::isLoadedObject($employee))return;
		$id_lang = $employee->id_lang;     
		$iso = Language::getIsoById((int)($id_lang));
		$id_sellerinfo = SellerInfo::getIdBSellerId($id_seller);
		if((int)$id_sellerinfo <=0)return;
		$sellerinfo = new SellerInfo($id_sellerinfo, $id_lang);
		$templateVars = array(
			'{firstname}' => $employee->firstname,
			'{lastname}' => $employee->lastname,
			'{seller_company}' => $sellerinfo->company,
			'{seller_address}' => $sellerinfo->address1,
			'{email}' => $employee->email,
			'{product_name}' => $product->name,
			'{id_product}' => $product->id,
			'{shop_name}' => Configuration::get('PS_SHOP_NAME'),
			'{shop_url}'=>Tools::getShopDomainSsl(true, true).__PS_BASE_URI__,
			'{shop_logo}' => Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.'img/logo.jpg'
			);
		
		$temp_folder = _PS_ROOT_DIR_.'/modules/agilemultipleseller/mails/';
		if ( !file_exists($temp_folder .$iso.'/' . $template . '.txt') OR !file_exists($temp_folder .$iso.'/' . $template . '.html'))
		{
			$id_lang = (int)(Configuration::get('PS_LANG_DEFAULT'));
			$iso = Language::getIsoById($id_lang);
		}		
		if (file_exists($temp_folder .$iso.'/' . $template . '.txt') AND file_exists($temp_folder .$iso.'/' . $template . '.html'))
		{
			AgileMultipleSellerMailer::SendTranslateSubject($id_lang, $template, $templateVars, $employee->email, $employee->firstname.' '.$employee->lastname, $configuration['PS_SHOP_EMAIL'], $configuration['PS_SHOP_NAME'], NULL, NULL, $temp_folder);
		}
	}


	public static function sendSellerAccountApprovalEmail($id_seller) 
	{
		$context = Context::getContext();

		include_once(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/SellerInfo.php");
		$template = 'app_selleraccount';
		$configuration = Configuration::getMultiple(array('PS_SHOP_EMAIL', 'PS_SHOP_NAME'));

		$employee = new Employee($id_seller);
		if(!Validate::isLoadedObject($employee))return;
		$id_lang = $employee->id_lang;     
		$iso = Language::getIsoById((int)($id_lang));
		$id_sellerinfo = SellerInfo::getIdBSellerId($id_seller);
		if((int)$id_sellerinfo <=0)return;
		$sellerinfo = new SellerInfo($id_sellerinfo, $id_lang);
		$templateVars = array(
			'{firstname}' => $employee->firstname,
			'{lastname}' => $employee->lastname,
			'{seller_company}' => $sellerinfo->company,
			'{seller_address}' => $sellerinfo->address1,
			'{email}' => $employee->email,
			'{shop_name}' => Configuration::get('PS_SHOP_NAME'),
			'{shop_url}'=>Tools::getShopDomainSsl(true, true).__PS_BASE_URI__,
			'{shop_logo}' => Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.'img/logo.jpg'
			);
		
		$temp_folder = _PS_ROOT_DIR_.'/modules/agilemultipleseller/mails/';
		if ( !file_exists($temp_folder .$iso.'/app_selleraccount.txt') OR !file_exists($temp_folder .$iso.'/app_selleraccount.html'))
		{
			$id_lang = (int)(Configuration::get('PS_LANG_DEFAULT'));
			$iso = Language::getIsoById($id_lang);
		}		
		if (file_exists($temp_folder .$iso.'/app_selleraccount.txt') AND file_exists($temp_folder .$iso.'/app_selleraccount.html'))
		{
			AgileMultipleSellerMailer::SendTranslateSubject($id_lang, 'app_selleraccount', $templateVars, $employee->email, $employee->firstname.' '.$employee->lastname, $configuration['PS_SHOP_EMAIL'], $configuration['PS_SHOP_NAME'], NULL, NULL, $temp_folder);
		}
	}
	
	public static function sendNewSellerAccountEmail($id_sellerinfo)
	{
		$context = Context::getContext();

		include_once(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/SellerInfo.php");
		if(!intval($id_sellerinfo))return;
		$sellerinfo = new SellerInfo($id_sellerinfo, $context->language->id);
		if(!Validate::isLoadedObject($sellerinfo))return;
		
		$configuration = Configuration::getMultiple(array('PS_SHOP_EMAIL', 'PS_SHOP_NAME'));
		$employee = new Employee($sellerinfo->id_seller);
		if(!Validate::isLoadedObject($employee))return;

		$id_lang = ($context->language->id);
		$iso = Language::getIsoById($id_lang);
		
		$aprove_message = '';
		if ($employee->active ==0)
			$aprove_message = Mail::l('You will be automatically notified by email when your account has been approved.');
		else
			$aprove_message = Mail::l('You can access your seller account by: login to front office, go to my seller account then click link to your seller account at back office.');
		
		$templateVars = array(
			'{firstname}' => $employee->firstname,
			'{lastname}' => $employee->lastname,
			'{email}' => $employee->email,
			'{seller_id}' => $employee->id,
			'{seller_company}' => $sellerinfo->company,
			'{seller_address}' => $sellerinfo->fulladdress($id_lang),
			'{shop_name}' => Configuration::get('PS_SHOP_NAME'),
			'{message}'=> $aprove_message,
			'{shop_url}'=>Tools::getShopDomainSsl(true, true).__PS_BASE_URI__,
			'{shop_logo}' => Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.'img/logo.jpg'
			);
		
		$temp_folder = _PS_ROOT_DIR_.'/modules/agilemultipleseller/mails/';
		if ( !file_exists($temp_folder .$iso.'/new_selleraccount.txt') OR !file_exists($temp_folder .$iso.'/new_selleraccount.html'))
		{
			$id_lang = (int)(Configuration::get('PS_LANG_DEFAULT'));
			$iso = Language::getIsoById($id_lang);
		}
		
		if (file_exists($temp_folder .$iso.'/new_selleraccount.txt') AND file_exists($temp_folder .$iso.'/new_selleraccount.html'))
		{
						AgileMultipleSellerMailer::SendTranslateSubject($id_lang, 'new_selleraccount', $templateVars, $employee->email, $employee->firstname.' '.$employee->lastname, $configuration['PS_SHOP_EMAIL'], $configuration['PS_SHOP_NAME'], NULL, NULL, $temp_folder);
		}
		
		if ( !file_exists($temp_folder .$iso.'/new_selleraccount_admin.txt') OR !file_exists($temp_folder .$iso.'/new_selleraccount_admin.html'))
		{
			$id_lang = (int)(Configuration::get('PS_LANG_DEFAULT'));
			$iso = Language::getIsoById($id_lang);
		}
		
		if (file_exists($temp_folder .$iso.'/new_selleraccount_admin.txt') AND file_exists($temp_folder .$iso.'/new_selleraccount_admin.html'))
		{
						AgileMultipleSellerMailer::SendTranslateSubject($id_lang, 'new_selleraccount_admin', $templateVars, $configuration['PS_SHOP_EMAIL'], 'Administrator', $configuration['PS_SHOP_EMAIL'], $configuration['PS_SHOP_NAME'], NULL, NULL, $temp_folder);
		}
	}
	
	public static function sendNewProductEmail($id_product)
	{
		$context = Context::getContext();
		
		$configuration = Configuration::getMultiple(array('PS_SHOP_EMAIL', 'PS_SHOP_NAME'));

		if(intval(Configuration::get('AGILE_MS_PRODUCT_APPROVAL'))!=1)return;

		$product = new Product($id_product,false, $context->language->id);
		$id_owner = AgileSellerManager::getObjectOwnerID('product',$id_product);
		$employee = new Employee($id_owner);
		if(!Validate::isLoadedObject($employee) OR !Validate::isLoadedObject($product))return;
		
		include_once(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/SellerInfo.php");
		$sellerinfo = new SellerInfo(SellerInfo::getIdBSellerId($id_owner), $context->language->id);

		$id_lang = ($context->language->id);
		$iso = Language::getIsoById($id_lang);

		$companyName = $sellerinfo->company;
		if(empty($companyName)) $companyName = $employee->firstName + $employee->lastName;
		$templateVars = array(
			'{seller_company}' => $companyName, 
			'{seller_id}' => $employee->id,
			'{product_name}' => $product->name,
			'{product_id}' => $product->id,
			'{shop_name}' => Configuration::get('PS_SHOP_NAME'),
			'{shop_url}'=>Tools::getShopDomainSsl(true, true).__PS_BASE_URI__,
			'{shop_logo}' => Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.'img/logo.jpg'
			);
		
		$temp_folder = _PS_ROOT_DIR_.'/modules/agilemultipleseller/mails/';
		if ( !file_exists($temp_folder .$iso.'/new_product.txt') OR !file_exists($temp_folder .$iso.'/new_product.html'))
		{
			$id_lang = (int)(Configuration::get('PS_LANG_DEFAULT'));
			$iso = Language::getIsoById($id_lang);
		}

		if (file_exists($temp_folder .$iso.'/new_product.txt') AND file_exists($temp_folder .$iso.'/new_product.html'))
		{
						AgileMultipleSellerMailer::SendTranslateSubject($id_lang, 'new_product', $templateVars, $configuration['PS_SHOP_EMAIL'],
				'Administrator', $configuration['PS_SHOP_EMAIL'], $configuration['PS_SHOP_NAME'], NULL, NULL, $temp_folder);
		}
	}
	
	public static function ensure_date_custom_field()
	{
		$conf = Configuration::getMultiple(array('AGILE_MS_SELLER_DATE1','AGILE_MS_SELLER_DATE2','AGILE_MS_SELLER_DATE3','AGILE_MS_SELLER_DATE4','AGILE_MS_SELLER_DATE5'));

		for($idx=1;$idx<=5;$idx++)
		{
			if(!$conf['AGILE_MS_SELLER_DATE' .$idx] OR !isset($_POST['ams_custom_date' .$idx]) OR !Validate::isDate($_POST['ams_custom_date' .$idx]))
			{
				$_POST['ams_custom_date'.$idx] = null;
			}
		}
	} 
	
	public static function get_agile_ajax_categories_url()
	{
		return Tools::getShopDomainSsl(true,true) . __PS_BASE_URI__ . "modules/agilemultipleseller/ajax_agile_categories.php";
	}	

	public static function get_display_fields($page_name, $allow_level)
	{
		$sql = 'SELECT field_name FROM ' . _DB_PREFIX_ . 'agile_pageconfig WHERE page_name=\'' . $page_name.'\' AND allow_level='.$allow_level;
		return $categories = Db::getInstance()->executeS($sql);
	}
	
	public static function jsForHideHome()
	{
		return '<script type="text/javascript">
					$(document).ready(function(){
						$("input[type=\'checkbox\'][name=\'categoryBox[]\'][value=2]").parent().remove();
					});
			</script>';
		
	}

	public function hookDisplayHeader($params)
	{
		if(property_exists($this->context->controller,"page_name"))
		{
			if($this->context->controller->page_name =='module-agilemembership-mymembership' && Configuration::get('AGILE_MEMBERSHIP_SELLER_INTE')==1)return "";
		}

		$this->context->controller->registerJavascript('tools-js','/js/tools.js',['position' => 'bottom', 'priority' => 100]);
		$this->context->controller->registerJavascript('ams-agile-global-js','/modules/agilemultipleseller/js/agile_global.js',['position' => 'bottom', 'priority' => 100]);
		/* LOONES
		Media::addJsDef(array(
			'AGILE_MS_CUSTOMER_SELLER' => intval(Configuration::get('AGILE_MS_CUSTOMER_SELLER')),
			'sellwithus_link' => ' | <a href="' . $this->context->link->getModuleLink('agilemultipleseller','sellersignup',array(), true) . '">' . $this->l('Seller Signup') . '</a>'
			));

		*/ 
		Media::addJsDef(array(
			'AGILE_MS_CUSTOMER_SELLER' => intval(Configuration::get('AGILE_MS_CUSTOMER_SELLER')),
			'sellwithus_link' => '<a href="' . $this->context->link->getModuleLink('agilemultipleseller','sellersignup',array(), true) 
						.'"><i class="material-icons">&#xE7FF;</i> <span class="hidden-md-down">'
						. $this->l('Seller Signup')  
						.'</span></a>'
			));

				
		if($this->context->controller->php_self != "index")
		{
			$this->context->controller->addCSS($this->_path.'css/agileglobal.css', 'all');
			$this->context->controller->addCSS($this->_path.'css/agilemultipleseller.css', 'all');
		}
		
				if(!empty($this->context->cookie->viewed))
		{
			$agile_filters = AgileSellerManager::getAdditionalSqlForProducts('p');
			$sql = 'SELECT distinct p.id_product 
					FROM '._DB_PREFIX_.'product p
					' . $agile_filters['joins'] . '
			        WHERE p.id_product IN (' . $this->context->cookie->viewed . ')
					' . $agile_filters['wheres'] . '
                ';
			$viewed = Db::getInstance()->ExecuteS($sql);
			if(!empty($viewed))
				$this->context->cookie->viewed = implode(",", AgileHelper::retrieve_column_values($viewed,"id_product", false));
			else
				$this->context->cookie->viewed = '';				
		}
		
		return "";
	}


	public function stop_mix_handler()
	{
		if(!$this->active) return true;

				if(! Configuration::get('AGILE_MEDICAL_PRODUCT_CID') ) return true;

		$cart_products = $this->context->cart->getProducts();

				if (count($cart_products)>0) 
		{
			$isMedicalProduct = AgileMultipleSeller::IsProductInMedicalCategory(Tools::getValue('id_product'));
						if($isMedicalProduct && (int)($context->cart->nbProducts())>1) {
				return false;
			} else {
								foreach ($cart_products as $cart_product)
				{
					$continue = ($isMedicalProduct == AgileMultipleSeller::IsProductInMedicalCategory( $cart_product['id_product']));
										if(!$continue) 	{
						break;  
					}
				}
				return $continue;
			}
		} else {
			return true;
		}
		
		return true;
	}

	public static function IsProductInMedicalCategory($id_product)
	{
		$list = implode(AgileSellerManager::get_all_children(Configuration::get('AGILE_MEDICAL_PRODUCT_CID')), ",");

		$sql = "SELECT id_category from "._DB_PREFIX_."category_product where id_category in (" 
			. $list . ") AND id_product =" . $id_product;
		$result = Db::getInstance()->getRow($sql);
		if(isset($result['id_category']) AND intval($result['id_category'])>0)return true;
		return false;
	}

	public static function getProductNamesFromCartBySeller($id_cart, $id_seller, $id_lang, $separator="<BR>")
	{
		$products = AgileMultipleSeller::getProductsFromCartBySeller($id_cart, $id_seller, $id_lang);
		$productnames = "";
		if(!empty($products))
		{
			foreach($products as $prod)
			{
				if(!empty($productnames))$productnames .= $separator;
				$productnames .=  $prod['quantity'] .' x ' . $prod['name'] . "(ID:" . $prod['id_product'] . ")";
			} 
		}
		return $productnames;
		
	}	

	
	public static function getProductsFromCartBySeller($id_cart, $id_seller, $id_lang)
	{
		$sql = 'SELECT p.id_product, pl.name, cp.quantity
				FROM ' . _DB_PREFIX_ . 'cart_product cp
				INNER JOIN ' . _DB_PREFIX_ . 'product p ON (cp.id_product = p.id_product)
				INNER JOIN ' . _DB_PREFIX_ . 'product_lang pl ON (p.id_product = pl.id_product AND pl.id_lang = ' . (int)$id_lang .  ' AND pl.id_shop= '  . (int)Configuration::get('PS_SHOP_DEFAULT') . ')
				LEFT  JOIN ' . _DB_PREFIX_ . 'product_owner po ON (p.id_product = po.id_product)  
		where cp.id_cart = ' . (int)$id_cart. '
			 AND po.id_owner= ' . (int)$id_seller. '
		';

		return Db::getInstance()->ExecuteS($sql);
	}	
	
	public static function  get_subcartid_for_seller($id_seller, $id_cart_parent)
	{
		$sql = 'SELECT id_cart FROM `' . _DB_PREFIX_ . 'agile_subcart` WHERE id_cart_parent = ' . (int)$id_cart_parent . ' and id_seller = ' . (int)$id_seller;
		$id_cart = (int)Db::getInstance()->getValue($sql);
				if($id_cart ==0)$id_cart =  $id_cart_parent;
		return $id_cart;
	}

	public static function get_last_subcart_id($id_cart_parent)
	{
		$sql = 'SELECT id_cart FROM `' . _DB_PREFIX_ . 'agile_subcart` WHERE id_cart_parent = ' . (int)$id_cart_parent . ' ORDER BY id_cart DESC';
		$id_cart = (int)Db::getInstance()->getValue($sql);
		return $id_cart;
	}

	public function GetIntegratedPaymentModules($include_pleasechose = true)
	{
		$modules = Hook::exec('actionAgilePaymentModuleIntegrate', array(), null, true);
		if(empty($modules))$modules = array();
		$pleasechoose = array('0' =>
			array(
					'name' => '0',
					'desc' => $this->l('Please Choose'),
					'mode' => array(
						1 =>0
						,2 => 0
						,3 => 0
						),
					'info1' => array('label' => '','is_unique'=>0),
					'info2' => array('label' => '','is_unique'=>0),
					'info3' => array('label' => '','is_unique'=>0),
					'info4' => array('label' => '','is_unique'=>0),
					'info5' => array('label' => '','is_unique'=>0),
					'info6' => array('label' => '','is_unique'=>0),
					'info7' => array('label' => '','is_unique'=>0),
					'info8' => array('label' => '','is_unique'=>0),
					));

						if(Module::isInstalled('agilesellercommission') && Configuration::get('ASC_USE_PAYPAL') == 1 && empty($modules['agilepaypal']) && empty($modules['agilepaypalparallel']) && empty($modules['agilepaypaladaptive']))
		{
			$modules['agilepaypal'] = array(
				'name' => 'agilepaypal',
				'desc' => $this->l('Agile Paypal Module for Payments between seller and store'),
				'mode' => array(
						1 => true								,2 => false								,3 => true								),
					'info1' => array('label' => $this->l('Paypal Email Address'), 'is_unique'=>1),
					'info2' => array('label' => 'N/A','is_unique'=>0),
					'info3' => array('label' => 'N/A','is_unique'=>0),
					'info4' => array('label' => 'N/A','is_unique'=>0),
					'info5' => array('label' => 'N/A','is_unique'=>0),
					'info6' => array('label' => 'N/A','is_unique'=>0),
					'info7' => array('label' => 'N/A','is_unique'=>0),
					'info8' => array('label' => 'N/A','is_unique'=>0),
					);
		}
		
		if(empty($modules))$modules = array();
		if($include_pleasechose)
		{
			$modules = array_merge($pleasechoose, $modules);
		}

		return $modules;
	}
	
	public function hookDisplayProductInformations($params)
	{
		$id_product = (int)$params['id_product'];
		$ownerinfo = AgileSellerManager::getObjectOwnerInfo('product',$params['id_product']);
		$this->context->smarty->assign(array(
			'approveal_required' => Configuration::get('AGILE_MS_PRODUCT_APPROVAL')
			,'approved' => (int)$ownerinfo['approved']
			,'id_seller' => $ownerinfo['id_owner']
			,'is_seller' => $this->context->cookie->profile == (int)Configuration::get('AGILE_MS_PROFILE_ID')
			,'sellers' => AgileSellerManager::getSellersNV(true, $this->l('Shared'))
			));
		
		return $this->display(__FILE__, 'views/templates/hook/hook_product_information.tpl');
	}

	public static function getOrdersByParentCartID($id_cart_parent)
	{
		$sql = 'SELECT o.id_order 
				FROM ' . _DB_PREFIX_ . 'orders o
				INNER JOIN ' . _DB_PREFIX_ . 'agile_subcart ac ON o.id_cart = ac.id_cart AND id_cart_parent= ' . (int)($id_cart_parent).'
				';
		
		return Db::getInstance()->ExecuteS($sql);
	}
	

	public static function getOrderIDBySellerParentCartID($id_cart_parent, $id_seller)
	{
		$sql = 'SELECT o.id_order, o.id_cart 
				FROM ' . _DB_PREFIX_ . 'orders o
				INNER JOIN ' . _DB_PREFIX_ . 'agile_subcart ac ON o.id_cart = ac.id_cart AND id_cart_parent= ' . (int)($id_cart_parent).'
				LEFT JOIN ' . _DB_PREFIX_ . 'order_owner oo ON o.id_order=oo.id_order
				WHERE oo.id_owner = '.(int)$id_seller. '
				';
				return Db::getInstance()->getRow($sql);
	}
	
	public function displayCreateSellerAccountCheckbox($params)
	{
		$this->context->smarty->assign(
			array('id_sellerinfo' => (int)$params['id_sellerinfo'])
		);

		return $this->display(__FILE__, 'views/templates/hook/hookcreateselleraccountcheckbox.tpl');
	}		

	public function displayAssignAllProductsForm($params)
	{
		return $this->display(__FILE__, 'views/templates/hook/hookassignallproductsform.tpl');
	}		

	public function displaySellerDropdownList($params)
	{
		$this->context->smarty->assign(
			array('sellers' => $params['sellers'])
		);
		return $this->display(__FILE__, 'views/templates/hook/hooksellerdropdownlist.tpl');
	}
	
	public static function RemoveNotWantedModules($modules, $to_be_removed)
	{
		$ret = array();
		foreach($modules as $key => $info)
		{
			if($key  == "0" || !in_array($key, $to_be_removed))
			{
				$ret[$key] = $info;
			}
		}
		
		return $ret;
	}		
	
	public function hookGenerateCSV($params)
	{
		
		$this->context->smarty->assign($params['data']);
		return $this->display(__FILE__, 'csv-export.tpl');
	}	
	
	public function can_add_to_cart()
	{
		if(!$this->active)return true;	
		if(!$this->context->cart)return true; 		if(intval(Configuration::get('AGILE_MS_CART_MODE'))==AgileMultipleSeller::CART_MODE_MULTIPLE_SELLER)return true;
		$cartProducts = $this->context->cart->getProducts();
		if(empty($cartProducts))return true;
		$id_product = intval(Tools::getValue('id_product'));
		$id_owner = AgileSellerManager::getObjectOwnerID('product',$id_product);
		foreach($cartProducts As $product)
		{
			$oid = AgileSellerManager::getObjectOwnerID('product',$product['id_product']);
			if($oid != $id_owner)
			{
				$this->context->controller->errors[] = -31;
				$this->context->controller->errors[] = $this->l('ProductIsFromDifferentSellerInCart');
				return false;
			}
		}
		return true;
	}	
}
