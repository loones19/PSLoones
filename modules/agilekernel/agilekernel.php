<?php
///-build_id: 2018051409.414
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
if (!defined('_PS_VERSION_'))
	exit;

include_once(_PS_ROOT_DIR_ . "/modules/agilekernel/init.php");

class AgileKernel extends Module
{
	private	$_html = '';
	public	$shared_override = array(
		'classes/Hook.php' => array('agilemultipleseller','agilepaypal'),
		'classes/Mail.php' => array('agilemultipleseller','agileprepaidcredit','agilesellershipping','agilepickupcenter'),
		'classes/pdf/HTMLTemplateInvoice.php' => array('agilemultipleseller','agilepickupcenter'),
		'classes/PaymentModule.php' => array('agilemultipleseller','agileprepaidcredit'),
		'controllers/front/CartController.php' => array('agilemultipleseller','agileprepaidcredit','agilemembership'),
		);

	public function __construct()
	{
		$this->agile_configs = Configuration::getMultiple($this->getConfigKeys());	
		$this->agile_newfiles = array();
						$this->agile_dependencies = array(); 
		
				$this->name = 'agilekernel';
		
		$this->bootstrap = true;
		parent::__construct();		
		$this->tab = 'front_office_features';
		$this->author = 'addons-modules.com';
		$this->version = '1.7.1.5';
		$this->ps_versions_compliancy = array('min' => '1.7', 'max' => '1.8');
		$this->dependencies = array();
		$this->agile_dependencies = array(); 

		$this->displayName = $this->l('Agile Kernel');
		$this->description = $this->l('This is a kernel module for all Agile Modules from Addons-Modules.com, it holds the base and common components for all other Agile Module. You have to install this module first before installing any other Agile Modules.');
	}
	
	private function getConfigKeys()
	{
		return array('AK_ORDER_REFERENCE','AK_GRC_SITE_KEY','AK_GRC_SECRET_KEY','AK_GMAP_APIKEY');		
	}

	public function install()
	{
		$modules = $this->incompatibelModules();
		if(!empty($modules)){
			$errmsg = $this->l('The following modules are not compatible with Agile Kernel module, you need upgrade all Agile modules to the latest version(please reauest update of those modules). You will then need to uninstall/delete those old modules first:') . '<BR>' . implode("<br>",$modules);
			$errmsg .= "<br><br>" . $this->l('Please note, if there was any custom changes made to your store or if there was any override classes code merged before, you will not be able to upgarde the modules easily because PrestaShop will not be able to uninstall those override classes. You may face issues if you proceed to upgrade the modules. ');
			$errmsg .= $this->l('If this is the case, we recommend you to request an old version of module you just purchased to match the version of other Agile modules you have. ');

			$this->_errors[] = $errmsg;
			return false;
		}
				$this->renameIncompatibleOldFile();

				$files = $this->incompatibleFiles();
		if(!empty($files)){
			$this->_errors[] =   $this->l('The following files are not compatible with Agile Kernel, please delete those files first:') . '<BR>' . implode("<br>",$files);
			return false;
		}
		
				if(!defined('_IS_AGILE_DEV_') && !empty($this->agile_newfiles))
		{
			$adminfolder = AgileInstaller::detect_admin_folder($_SERVER['SCRIPT_FILENAME']);
			AgileInstaller::install_newfiles($this->agile_newfiles, $this->name, $adminfolder, 2);
			$result = AgileInstaller::install_health_check($this->agile_newfiles, $this->name, $adminfolder);	
			if(!empty($result))
			{
				$this->_errors[] = '<a target="agile" style="text-decoration:underline;color:blue;" href="http://addons-modules.com/store/en/content/36-agile-module-installation-tips">' .
					$this->l('Failed to update files due to permission issue, please visit here for more instructions.') . '</a>';
				$this->_errors[] = $result;
				return false;
			}
		}

		$reterrs = AgileInstaller::version_depencies($this->agile_dependencies);
		if(!empty($reterrs)){
			$this->_errors = array_merge($this->_errors, $reterrs);
			return false;
		}

		$reterrs = AgileInstaller::CanModuleOverride($this->name);
		if(!empty($reterrs)){
			$this->_errors = array_merge($this->_errors, $reterrs);
			return false;
		}
		
		if(!AgileInstaller::sql_install(_PS_ROOT_DIR_ . '/modules/' . $this->name . "/install.sql"))
		{
			$this->_errors[] = $this->l('Install error - creating database table ');
			return false;
		}

				if  (parent::install() == false )
		{
			$this->_errors[] = $this->l('Install error - call parent::install()');
			return false;
		}

		$this->install_configfile();
		
		if(!AgileInstaller::create_tab('Agile Modules', 'AgileModulesParent', '', $this->name))return false;
		AgileInstaller::init_tab_prmission_for_existing_profiles('AdminLocations',1,1,1,1);
		
				if(!$this->AgileSetGlobalDefaultConfig('AK_ORDER_REFERENCE', '')
			OR !$this->AgileSetGlobalDefaultConfig('AK_GRC_SITE_KEY', '')
			OR !$this->AgileSetGlobalDefaultConfig('AK_GRC_SECRET_KEY', '')
			OR !$this->AgileSetGlobalDefaultConfig('AK_GMAP_APIKEY', '')
		)
		return false;
		
		
		if (!$this->registerHook('displayHeader')		
			OR !$this->registerHook('displayBackOfficeHeader') 
			OR !$this->registerHook('displayTop') 
			OR !$this->registerHook('displayBackOfficeTop') 
		)return false;
		
		return true;
	}
	
		public function AgileSetGlobalDefaultConfig($key, $default)
	{
		$value = $default;
		if(array_key_exists($key, $this->agile_configs) && strlen($this->agile_configs[$key]) > 0)
		{
			$value = $this->agile_configs[$key];
		}
		return Configuration::updateGlobalValue($key, $value);
	}
	
	private function install_configfile()
	{
		$adminfolder = AgileInstaller::detect_admin_folder($_SERVER['SCRIPT_FILENAME']);
				$configfile = _PS_ROOT_DIR_ . "/config/config.inc.php";
		$content = file_get_contents($configfile);		
		$pos = strrpos($content, "agilekernel");
		if($pos === false)
		{
			$handle = fopen($configfile, "a+");
			if(!$handle)return;
						fwrite($handle, "\r\nif(file_exists(_PS_ROOT_DIR_.'/modules/agilekernel/init.php'))include_once(_PS_ROOT_DIR_.'/modules/agilekernel/init.php');\r\n");
			fclose($handle);
		}
	}
	

	public function uninstall()
	{   
		@set_time_limit(300);
		$modules = $this->getAllAgileModules(true, false);
		if(!empty($modules))
		{
			$this->_errors[] = $this->l('This module can not be uninstalled, you must uninstall following Agile Modules first: ') . '<br>' . implode("<br>",$modules);
			return false;
		}
		
		AgileInstaller::delete_tab('AgileModulesParent');
		
		if(!parent::uninstall())return false;
		return true;
	}

	public function uninstall4Update()
	{   
		@set_time_limit(300);
		if(!parent::uninstall())return false;
		return true;
	}


	private function getAllAgileModules($forDisplay, $includeme)
	{
		$modules = array();
		$sql = 'SELECT id_module, name FROM ' . _DB_PREFIX_ . 'module WHERE name like \'agile%\'';
		if(!$includeme)$sql = $sql  . ' AND name !=\'agilekernel\'';
		$agilemodules = Db::getInstance()->ExecuteS($sql);
		$hasOldModules = false;
		foreach($agilemodules as $mod)
		{
			$mi = Module::getInstanceByName($mod['name']);
			if(strtolower($mi->author) == 'addons-modules.com')
			{
				if($forDisplay)
					$modules[] = $mi->displayName . '-' .  $mi->version;
				else
					$modules[] = $mi->name . '-' .  $mi->version;
			}
		}
		return $modules;
	}
	
	private function incompatibelModules()
	{
		$modules = array();
		$sql = 'SELECT id_module, name FROM ' . _DB_PREFIX_ . 'module WHERE name like \'agile%\' and name!=\'agilekernel\'';
		$agilemodules = Db::getInstance()->ExecuteS($sql);
		$hasOldModules = false;
		foreach($agilemodules as $mod)
		{
			$mi = Module::getInstanceByName($mod['name']);
			if(strtolower($mi->author) == 'addons-modules.com' && (!property_exists($mi, 'isAgileKernelCompatible') || $mi->isAgileKernelCompatible != true))
			{
				$modules[] = $mi->displayName . '(' .  $mi->name .')' . '-' . $this->l('version') . $mi->version;
			}
		}
		return $modules;
	}
	
	private function renameIncompatibleOldFile()
	{	
		if(file_exists(_PS_ROOT_DIR_ . "/classes/AgileHelper.php"))rename(_PS_ROOT_DIR_ . "/classes/AgileHelper.php",_PS_ROOT_DIR_ . "/classes/AgileHelper.php.removed");
		if(file_exists(_PS_ROOT_DIR_ . "/classes/AgileInstaller.php")) rename(_PS_ROOT_DIR_ . "/classes/AgileInstaller.php",_PS_ROOT_DIR_ . "/classes/AgileInstaller.php.removed");
		if(file_exists(_PS_ROOT_DIR_ . "/classes/AgileSellerManager.php")) rename(_PS_ROOT_DIR_ . "/classes/AgileSellerManager.php",_PS_ROOT_DIR_ . "/classes/AgileSellerManager.php.removed");
		if(file_exists(_PS_ROOT_DIR_ . "/classes/AgileSessionData.php")) rename(_PS_ROOT_DIR_ . "/classes/AgileSessionData.php",_PS_ROOT_DIR_ . "/classes/AgileSessionData.php.removed");
		if(file_exists(_PS_ROOT_DIR_ . "/classes/AgileSessionHandler.php")) rename(_PS_ROOT_DIR_ . "/classes/AgileSessionHandler.php",_PS_ROOT_DIR_ . "/classes/AgileSessionHandler.php.removed");
		if(file_exists(_PS_ROOT_DIR_ . "/classes/controller/AgileModuleFrontController.php")) rename(_PS_ROOT_DIR_ . "/classes/controller/AgileModuleFrontController.php",_PS_ROOT_DIR_ . "/classes/controller/AgileModuleFrontController.php.removed");
		if(file_exists(_PS_ROOT_DIR_ . "/classes/module/AgileModule.php")) rename(_PS_ROOT_DIR_ . "/classes/module/AgileModule.php",_PS_ROOT_DIR_ . "/classes/module/AgileModule.php.removed");
	}
	
	
	private function incompatibleFiles()
	{	
		$files =  array();
		if(file_exists(_PS_ROOT_DIR_ . "/classes/AgileHelper.php")) $files[] =  _PS_ROOT_DIR_ . "/classes/AgileHelper.php";
		if(file_exists(_PS_ROOT_DIR_ . "/classes/AgileInstaller.php")) $files[] =  _PS_ROOT_DIR_ . "/classes/AgileInstaller.php";
		if(file_exists(_PS_ROOT_DIR_ . "/classes/AgileSellerManager.php")) $files[] =  _PS_ROOT_DIR_ . "/classes/AgileSellerManager.php";
		if(file_exists(_PS_ROOT_DIR_ . "/classes/AgileSessionData.php")) $files[] =  _PS_ROOT_DIR_ . "/classes/AgileSessionData.php";
		if(file_exists(_PS_ROOT_DIR_ . "/classes/AgileSessionHandler.php")) $files[] =  _PS_ROOT_DIR_ . "/classes/AgileSessionHandler.php";
		if(file_exists(_PS_ROOT_DIR_ . "/classes/controller/AgileModuleFrontController.php")) $files[] =  _PS_ROOT_DIR_ . "/classes/controller/AgileModuleFrontController.php";
		if(file_exists(_PS_ROOT_DIR_ . "/classes/module/AgileModule.php")) $files[] =  _PS_ROOT_DIR_ . "/classes/module/AgileModule.php";
		
		return $files;
	}
	
	public function displayErrors()
	{
		$nbErrors = sizeof($this->_errors);
		$this->_html .=  '
		<div class="module_error alert alert-danger">
			<h3>'.($nbErrors > 1 ? $this->l('There are') : $this->l('There is')).' '.$nbErrors.' '.($nbErrors > 1 ? $this->l('errors') : $this->l('error')).'</h3>
			<ol>';
		foreach ($this->_errors AS $error)
			$this->_html .=  '
				<li>'.$error.'</li>';

		$this->_html .=  '
			</ol>
		</div>';
	}
	
	private function getServicePoint()
	{
				if(defined('_IS_AGILE_DEV_') OR defined('_IS_AGILE_TEST_'))return "http://teststore.com/store/getModuleUpdateInfo.php";
		else return "https://addons-modules.com/store/getModuleUpdateInfo.php";
	}
	
	
	private function getModuleUpdateInfo()
	{
		$protocol_link = (Configuration::get('PS_SSL_ENABLED') || Tools::usingSecureMode()) ? 'https://' : 'http://';
		$store_url = $protocol_link.Tools::getShopDomainSsl().__PS_BASE_URI__;
		$params = array();
		$params['order_reference'] = Configuration::get('AK_ORDER_REFERENCE');
		$params['ps_version'] = _PS_VERSION_;
		$params['store_url'] = $store_url;
		$params['modules'] = urlencode(implode("|",$this->getAllAgileModules(false,true)));
		return AgileHelper::call_remote_server($this->getServicePoint(), $params);		
	}
	

	public function getContent()
	{
		if (Tools::isSubmit('submitSettings'))
		{
			Configuration::updateValue('AK_ORDER_REFERENCE', Tools::getValue('ak_order_reference'));
			Configuration::updateValue('AK_GRC_SITE_KEY', Tools::getValue('ak_grc_site_key'));
			Configuration::updateValue('AK_GRC_SECRET_KEY', Tools::getValue('ak_grc_secret_key'));
			Configuration::updateValue('AK_GMAP_APIKEY', Tools::getValue('ak_gmap_apikey'));
			
			$this->_html .= $this->displayConfirmation($this->l('Settings updated'));
		}
		

		$this->_html = '<h2>'.$this->displayName.'</h2>';

		$adminfolder = AgileInstaller::detect_admin_folder($_SERVER['SCRIPT_FILENAME']);
		$health_check = AgileInstaller::install_health_check($this->agile_newfiles, $this->name, $adminfolder);
		if(!empty($health_check))	$this->_html .= $health_check;

		$this->_html .= AgileInstaller::show_agile_links();

		$this->_html .= $this->displayModuleUpdateInfo();
		
		if(!empty($this->_errors))$this->_html .= $this->displayErrors();
		

		$this->_html .= $this->renderForm();
		
		return $this->_html;
	}
	
	
	public function displayModuleUpdateInfo()
	{
		
		$updateInfoStr = $this->getModuleUpdateInfo();	
		$updateInfoObj = Tools::jsonDecode($updateInfoStr);
		
		$errors = array();
		
		$objtype = gettype($updateInfoObj);
				if($objtype == "NULL" && !empty($updateInfoStr)){
			$errors[] = $updateInfoStr;
		}
				else if($updateInfoObj->status == 'error')
		{
			$errors[] = $updateInfoObj->message;
		}

		$this->_html .=  '
		<div class="alert alert-info">
			<script type="text/javascript">
			function updateAgileModule(module, version, url) {
				var adminModuleUrl = "./index.php?controller=AdminModules&token=' . Tools::getAdminTokenLite('AdminModules') . '&ajax=true&action=update_agile_module&m_to_update=" + module + "&v_to_update=" + version + "&u_to_update=" + url;
				$("[id^=updateModule_]").hide();
				$("#divUpdateMessage").show();
				$.post(adminModuleUrl, "",
				function (response, status, xhr) {
					var msg = response.status + "\\r\\n";
					if(response.messages.length ==0) msg = msg + "'  . $this->l('Module has been updated successfully.') .'\\r\\n";
					else
					{
						for(idx=0;idx<response.messages.length;idx++)msg = msg + response.messages[idx] + "\\r\\n";
					}
					msg = msg + "\\r\\n";
					$("#divUpdateMessage").html();
					agile_show_message(msg);
					window.location.reload(true);
					
				}, "json");

			}
			$("document").ready(function(){
				$("#divUpdateMessage").hide();			
			});
			</script>
		
			<H4><b>' . $this->l('Agile Module Update Info'). '</b></h4>
				<div id="divUpdateMessage" class="alert alert-info"><img src="../modules/agilekernel/img/processing.gif" style="display:;"></div>
			';

		if($updateInfoObj->status == 'ok')
		{
			$nbModules = sizeof($updateInfoObj->modules);
			if($nbModules > 0)
				$this->_html .=  '<span>'.$this->l('There following ') .' '.$nbModules.' '. $this->l('module(s) areavailable for upodate') .'</span>';
			else
				$this->_html .=  '<span>'.$this->l('All your modules are up to date at this moment') .'</span>';
			
			$this->_html .=	'<ol>';
			foreach ($updateInfoObj->modules as $module)
			{
				$updatePanel = '';
				if(!empty($module->update_url))$updatePanel = '<input id="updateModule_' . $module->module . '" type=button onclick="updateAgileModule(\'' . $module->module . '\',\'' . $module->version  . '\',\'' . urlencode($module->update_url) .'\');" class="btn btn-default" value="' . $this->l('Update') . '">';
				else $updatePanel = '<span>' . $this->l('Please contact support@addons-modules.com for module upgrade.') . '</span>';

				$this->_html .=  '
							<li>'.$module->module. ' ' . $module->version . '&nbsp;&nbsp;(your version:' . $module->your_version . ')&nbsp;' . $updatePanel. '</li>';
			}
			$this->_html .=  '
					</ol>';
		}
		else
		{
			$this->_html .=  '
					<span>'.$this->l('There following ') .' '.count($errors).' '. $this->l('error(s) occured when retrive module upodate information.') .'</span>
					<ol style="color:red;">';
			foreach ($errors as $error)
			{
				$this->_html .=  '
						<li>'.$error. '</li>';
			}
			$this->_html .=  '
					</ol>';
			
		}
		$this->_html .=  '</div>';
		
	}


	public function renderForm()
	{
		$fields_form = array(
			'form' => array(
					'legend' => array(
						'title' => $this->l('Settings'),
						'image' => $this->_path.'logo.png',
						),
					'input' => array(
						array(
							'type' => 'text',
							'label' => $this->l('Your Order Reference #'),
							'name' => 'ak_order_reference',
							'desc' => $this->l('Order reference - please enter a correct/valid order reference # from Addons-module.com, separated by a comma. For example: CODJHZHBJ,BYNPHFRJP,NDUSMTDZV.')
							),
						array(
							'type' => 'text',
							'label' => $this->l('Google ReCapcha Site Key'),
							'name' => 'ak_grc_site_key',
							),
						array(
							'type' => 'text',
							'label' => $this->l('Google ReCapcha Secret Key'),
							'name' => 'ak_grc_secret_key',
							'desc' => $this->l('All Capcha used in modules from Addons-Modules.com is Google ReCapcha, you need this if you using one of following modules: Agile Showcase Manager, Agile Product Reviews, Agile Seller Messenger. Otherwise, you can leave those 2 fields empty.') . '<br><a href="https://www.google.com/recaptcha/admin#list" target="_new">' . $this->l('You can get a pair of key for your domain from Goole for free') . '</a>'
							),
						array(
							'type' => 'text',
							'label' => $this->l('Google Map API Key'),
							'name' => 'ak_gmap_apikey',
							'desc' => $this->l('All Google Maps used in modules from Addons-Modules.com will need this, if you using one of following modules: Agile Pickup Center, Agile Multiple Seller. Otherwise, you can leave this field empty.') . '<br><a href="https://addons-modules.com/store/en/content/99-how-to-apply-or-get-google-api-key" target="_new">' . $this->l('You can get a Google Map API Key for your domain from Goole for free') . '</a>'
							),
						),
					'submit' => array(
						'title' => $this->l('Save'),
						)
					)
				);

		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table = $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submitSettings';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => array(
					'ak_order_reference' => Tools::getValue('ak_order_reference', array_key_exists('AK_ORDER_REFERENCE', $this->agile_configs) ? $this->agile_configs['AK_ORDER_REFERENCE'] : ''),
					'ak_grc_site_key' => Tools::getValue('ak_grc_site_key', array_key_exists('AK_GRC_SITE_KEY', $this->agile_configs) ? $this->agile_configs['AK_GRC_SITE_KEY'] : ''),
					'ak_grc_secret_key' => Tools::getValue('ak_grc_secret_key', array_key_exists('AK_GRC_SECRET_KEY', $this->agile_configs) ? $this->agile_configs['AK_GRC_SECRET_KEY'] : ''),
					'ak_gmap_apikey' => Tools::getValue('ak_gmap_apikey', array_key_exists('AK_GMAP_APIKEY', $this->agile_configs) ? $this->agile_configs['AK_GMAP_APIKEY'] : ''),
					),
				'languages' => $this->context->controller->getLanguages(),
				'id_language' => $this->context->language->id
				);

		return $helper->generateForm(array($fields_form));

	}
	
	public function hookDisplayTop($params)
	{
		return $this->display(__FILE__, 'views/templates/hook/agilemodal.tpl');				
	}
	
	public function hookDisplayHeader($params)
	{
		if (Tools::getValue('action') == 'quickview') {
			return;
		}
		
				$this->context->controller->registerJavascript('agile-common-js','/modules/agilekernel/js/common.js',['position' => 'bottom', 'priority' => 100]);
		Media::addJsDef(array(
			'PleaseWaitWhileProcessYourRequest' => $this->l('Please wait while your request is been processed.')
			,'return_code' => $this->l('Retun Code')
			,'is_agileprepaidcredit_installed' => (Module::isInstalled('agileprepaidcredit') ? true : false)
		)); 

				if(	(isset($this->context->controller->needGoogleMap) &&  $this->context->controller->needGoogleMap)
			||(Module::isInstalled('agilemultipleseller') && (in_array($this->context->controller->php_self, array('product'))))
			||(Module::isInstalled('agilepickupcenter') && (in_array($this->context->controller->php_self, array('order'))))
		){
			$this->context->controller->registerJavascript('agile-googlemap-js','/modules/agilekernel/js/googlemaps.js',['position' => 'bottom', 'priority' => 100]);
			$this->assignCommonGoogleMaps();
		}
		
		$sitekey = Configuration::get('AK_GRC_SITE_KEY');
				if(!empty($sitekey))
		{
			if(in_array($this->context->controller->php_self, array('product','showcaseform')))
			{
				$this->context->smarty->assign(array(
					'ak_grc_site_key' =>  $sitekey,
					'ak_grc_language' => $this->context->language->iso_code
				));
			}
		}

				if ($this->context->controller->php_self == 'product')
		{
			$this->context->controller->registerJavascript('ak-hookproduct-js','/modules/agilekernel/js/hookproduct.js',['position' => 'bottom', 'priority' => 100]);
		}
		
		
																																																																																																																																					
		return $this->display(__FILE__, 'views/templates/hook/header.tpl');		
	}
	
	private function assignCommonGoogleMaps()
	{
		$ak_gmap_apikey = Configuration::get('AK_GMAP_APIKEY');
		$this->context->smarty->assign(array(
			'ak_gmap_apikey' =>  $ak_gmap_apikey
			));
		
	}

	public function hookDisplayBackOfficeHeader($params)
	{
		$this->context->controller->addJS(__PS_BASE_URI__ . 'modules/agilekernel/js/common.js');
		
		if( (Module::isInstalled('agilemultipleseller') && in_array($this->context->controller->php_self, array('adminsellerinfos'))) ||
			(Module::isInstalled('agilepickupcenter') && $this->context->controller->php_self = 'adminlocations')
		){
			$this->context->controller->addJS(__PS_BASE_URI__ . 'modules/agilekernel/js/googlemaps.js');
			$this->assignCommonGoogleMaps();
		}
					}

	private function displayCommonGoogleMaps()
	{
		
	}

	private function remove_parameters($keys)
	{
		$ret = array();
		foreach($_GET as $key => $val)
		{
			if(!in_array($key, $keys))$ret[$key] = $val;
		}
		return $ret;
		
	}

	public function hookDisplayBackOfficeTop($params)
	{
		if(!$this->active)return '';

				$incompatible_agilemodules = $this->incompatibelModules();
		$incompatible_agilefiles = array();
		if(empty($incompatible_agilemodules))
		{
									$this->renameIncompatibleOldFile();
						$incompatible_agilefiles = $this->incompatibleFiles();
		}
		
		$this->context->smarty->assign(array(
			'incompatible_agilemodules' => $incompatible_agilemodules,
			'incompatible_agilefiles' => $incompatible_agilefiles
			));
		
		return  $this->display(__FILE__, 'views/templates/hook/backofficetop.tpl')  . $this->display(__FILE__, 'views/templates/hook/agilemodal.tpl') ;		
	}
	
		public function get_shared_override_files($for_module)
	{
		$override_files = array();
		if(empty($this->shared_override))return $override_files;
		foreach($this->shared_override as $override_class => $shared_modules)
		{
						if(!in_array($for_module, $shared_modules))continue;
						if(!file_exists($this->get_shared_override_path($override_class)))continue;
			$override_files[$override_class] = $shared_modules;
		}
		return $override_files;		
	}

	public function get_shared_override_path($classfile)
	{
		return _PS_ROOT_DIR_ . DIRECTORY_SEPARATOR . "modules" .DIRECTORY_SEPARATOR . $this->name . DIRECTORY_SEPARATOR . "shared_override" . DIRECTORY_SEPARATOR . $classfile;
	}
	
	public function get_override_path($classfile)
	{
		return _PS_ROOT_DIR_ . DIRECTORY_SEPARATOR . "modules" .DIRECTORY_SEPARATOR . $this->name . DIRECTORY_SEPARATOR . "override" . DIRECTORY_SEPARATOR . $classfile;
	}
	
	
	public function shared_override_needs_install($formodule)
	{
		$override_classes = $this->get_shared_override_files($formodule);
		$override_to_install = array();
		foreach($override_classes as $override_class => $shared_modules)
		{
						if(file_exists($this->get_override_path($override_class)))continue;
						if(file_exists($this->get_override_path($override_class) . ".merged"))continue;
			$override_to_install[$override_class] = $shared_modules;
		}
		return $override_to_install;
	}	
	
		public function can_install_shared_overrides($override__install)
	{
		$errors = array();
		$override_copied = array();
		if(empty($override__install))return $errors;
		foreach($override__install as $override_class => $shared_modules)
		{
						if(file_exists($this->get_override_path($override_class)))continue;

						$pathinfo = explode('/', $override_class);
			$len = count($pathinfo);
			$path = _PS_ROOT_DIR_ . "/modules/agilekernel/override";
			for($idx = 0; $idx < $len - 1; $idx++)
			{
				$path =  $path . "/" . $pathinfo[$idx];
				if(!file_exists($path))mkdir($path);
			}
			
						copy($this->get_shared_override_path( $override_class), $this->get_override_path($override_class));
			$override_copied[] = $override_class;
			$errors = array_merge($errors, AgileInstaller::CanClassOverride($this->name, basename($override_class, ".php")));
		}
				if(!empty($errors))
		{
			foreach($override_copied as $override_class)
			{
								unlink($this->get_override_path($override_class));
			}
		}
		return $errors;
	}
	
	
	public function install_shared_override($override__install, $for_module)
	{
		$errors = array();
		foreach($override__install as $override_class => $shared_modules)
		{
			if(!$this->addOverride(basename($override_class, ".php")))
			{
								$errors[] = $this->l('override Class installation was not successfull:') . $override_class;
				if(file_exists($this->get_override_path($override_class)))unlink($this->get_override_path($override_class));
			}
		}
		return $errors;
	}
	


	public function uninstall_shared_override($for_module)
	{
		$errors = array();
		$override_classes = $this->get_shared_override_files($for_module);

		foreach($override_classes as $override_class => $shared_modules)
		{
						if($this->any_other_modules_installed($shared_modules, $for_module))
				continue;

						if(!file_exists($this->get_override_path($override_class)))
				copy($this->get_shared_override_path( $override_class), $this->get_override_path($override_class));

			if($this->removeOverride(basename($override_class, ".php")))
			{
				unlink($this->get_override_path($override_class));
			}
			else
			{
				$errors[] = $this->l('override Class uninstall was not successfull.');
			}
		}
		return $errors;		
	}
	
	private function any_other_modules_installed($modules, $the_module)
	{
		if(empty($modules))return false;
		foreach($modules as $module)
		{
			if($module != $the_module && Module::isInstalled($module))return true;
		}
		return false;
	}
}

