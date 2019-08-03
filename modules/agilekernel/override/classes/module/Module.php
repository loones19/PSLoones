<?php
///-build_id: 2018051409.414
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
class Module extends ModuleCore
{
	public $agile_configs = array();
	public $agile_dependencies = array();
	public $agile_newfiles = array();

	public function AgilePreinstall()
	{		
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
				
		return true;
	}

	public function AgileSetDefaultConfig($key, $default)
	{
		$value = $default;
		if(array_key_exists($key, $this->agile_configs) && strlen($this->agile_configs[$key]) > 0)
		{
			$value = $this->agile_configs[$key];
		}
		return Configuration::updateValue($key, $value);
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
	
    protected function getCacheId($name = null)
	{
		$cacheid = parent::getCacheId($name);
		if(Module::isInstalled('agilemultipleshop'))
		{
			$cacheid = $cacheid . '|' . (int)Shop::$id_shop_owner;
		}
		return $cacheid;
	}


	public static function getInstanceByName($module_name)
	{
				$modules2skip = array('agilebankwire','agilepaybycheque');
		if(in_array($module_name, $modules2skip)) 
		{
			include_once(_PS_MODULE_DIR_.$module_name.'/'.$module_name.'.php');
			$module = new $module_name;

			return $module;
		}
		return parent::getInstanceByName($module_name);
	}
	
	public function isEnabledForShopContext()
	{
		if(Module::isInstalled('agilemultipleseller') && strlen($this->name) > 5 && substr($this->name, 0, 5)=='agile')return true;
		return parent::isEnabledForShopContext();
	}
	
	public function dumpErrors()
	{
		var_dump($this->_errors);
	}
	
}
