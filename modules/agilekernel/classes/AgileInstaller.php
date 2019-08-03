<?php
///-build_id: 2018051409.414
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
class AgileInstallerCore
{
	public static function prepare_newfiles($newfiles, $module, $ver)
    {
		foreach($newfiles AS $newfile => $fileinfo)
        {
			if(strlen($newfile)>=6 AND substr($newfile,0,6)=='admin/')
                $from = _PS_ROOT_DIR_ . "/" . substr($newfile,0,5) . 'DEV' . substr($newfile,5);
            else
                $from = _PS_ROOT_DIR_ . "/" . $newfile;

			if(!empty($fileinfo[$ver]))
			{
	            $to = _PS_ROOT_DIR_ . "/modules/" .$module . "/install/$ver/" . $fileinfo[$ver];
	            copy($from,$to);
			}
        }
    }
    
        public static function detect_admin_folder($scriptpath)
    {
                $scriptpath = str_replace("\\","/",$scriptpath);
        $names = explode("/",$scriptpath);
        $ret = $names[count($names)-2];        
        return $ret;
    }
    

	public static function install_newfiles($newfiles, $module, $adminfolder, $installer_ver=1)
    {
		if(empty($newfiles))return;
				if($installer_ver == 2)self::install_newfiles_ver2($newfiles, $module, $adminfolder);

		Autoload::getInstance()->generateIndex();		
	}

	public static function install_admin_theme_file_override($module, $override_theme_files)
	{
		if(empty($override_theme_files) || empty($module))return;
		foreach($override_theme_files as $file)
		{
			$dstfile = _PS_ROOT_DIR_ . "/" . $file;
						if(file_exists($dstfile))copy($dstfile, $dstfile . ".bak");
						self::ensure_path_for_file(_PS_ROOT_DIR_, $file);
						$srcfile = _PS_ROOT_DIR_ . "/modules/$module/" . $file;
			copy($srcfile, $dstfile);
		}
	}

	public static function uninstall_admin_theme_file_override($module, $override_theme_files)
	{
		if(empty($override_theme_files) || empty($module))return;
		foreach($override_theme_files as $file)
		{
			$dstfile = _PS_ROOT_DIR_ . "/" . $file;
			if(file_exists($dstfile))unlink($dstfile);
		}
	}

	
	public static function ensure_path_for_file($start, $file)
	{
		$folders = explode("/", $file);
		$path = $start;
		for($idx =0; $idx < count($folders) - 1; $idx++)
		{
			$path .=  '/' . $folders[$idx];
			if(!file_exists($path))mkdir($path);
		}
	}


		public static function install_health_check($newfiles, $module, $adminfolder)
	{
		if(empty($newfiles))return '';

		$ver = substr(_PS_VERSION_, 0,3) . "x";

		$retstr = '';
		foreach($newfiles AS $newfile=>$fileinfo)
        {
			$newfile_dst = $fileinfo[$ver];
			if(empty($newfile_dst))continue;

            $from = _PS_ROOT_DIR_ . "/modules/" .$module . "/install/$ver/" . $newfile_dst;
            if(strlen($newfile_dst)>=6 AND substr($newfile_dst,0,6) == 'admin/')
	            $to = _PS_ROOT_DIR_ . "/" . $adminfolder . substr($newfile_dst,5);
            else
	            $to = _PS_ROOT_DIR_ . "/" . $newfile_dst;
	        $new_build_id = floatval(self::get_build_id($from));
            $old_fuild_id = floatval(self::get_build_id($to));
			if(!file_exists($to) OR ($new_build_id>0 AND $new_build_id > $old_fuild_id))
			{					
				$retstr = $retstr . Context::getContext()->getTranslator()->trans('From {0} <BR>',array('{0}'=> $from),'Modules.AgileKernel.Admin');
				$retstr = $retstr .  Context::getContext()->getTranslator()->trans('To {0} [',array('{0}'=> $to),'Modules.AgileKernel.Admin');

				if(!file_exists($to))
					$retstr = $retstr .  Context::getContext()->getTranslator()->trans('File not found:  ',array(),'Modules.AgileKernel.Admin') ;
				else
					$retstr = $retstr .  Context::getContext()->getTranslator()->trans('Ver mismatch:  ({0} < {1}) ',array('{0}'=>$old_fuild_id, '{1}' => new_build_id),'Modules.AgileKernel.Admin') ;
				
				$retstr = $retstr . ']<br><br>';
			}
        }
		
		if(empty($retstr))return '';    

		return '
			<div class="alert error">
				<h3>' . Context::getContext()->getTranslator()->trans('Some files are not copied correctly during the installation. Usually it is because of your access permission setting. ',array(),'Modules.AgileKernel.Admin') .'</h3>
				' .Context::getContext()->getTranslator()->trans('Please make sure you have set permissions correctly for Folder: 755, Files: 644, then try to reinstall the module again.',array(),'Modules.AgileKernel.Admin') 
				  .Context::getContext()->getTranslator()->trans('If the error persists, please manaually copy the file as following.',array(),'Modules.AgileKernel.Admin') .'
				<br><br>' . $retstr . '
			</div>';
	}

	private static function install_newfiles_ver2($newfiles, $module, $adminfolder)
    {
		if(empty($newfiles))return;
		foreach($newfiles AS $newfile=>$fileinfo)
        {
			$ver = substr(_PS_VERSION_, 0,3) . "x";		
			$newfilepath = $fileinfo[$ver];
			
			if(!empty($newfilepath))
				self::copy_file_with_backup($module, $adminfolder, $newfilepath,$newfilepath );
        }    
    }

    
	private static function copy_file_with_backup($module, $adminfolder, $newfile_src, $newfile_dst)
	{
		if(empty($newfile_src) OR empty($newfile_dst))return;

		$ver = substr(_PS_VERSION_, 0,3) . "x";		
		
		$from = _PS_ROOT_DIR_ . "/modules/" .$module . "/install/$ver/" . $newfile_src;
		if(strlen($newfile_dst)>=6 AND substr($newfile_dst,0,6) == 'admin/')
	        $to = _PS_ROOT_DIR_ . "/" . $adminfolder . substr($newfile_dst,5);
        else
	        $to = _PS_ROOT_DIR_ . "/" . $newfile_dst;
		
		
		if(!file_exists($from))return;
            
        $copy_flag = 0;
        		$new_build_id = floatval(self::get_build_id($from));
		if(!file_exists($to))
        {
            $copy_flag = 1;
			$old_fuild_id = 0;
        }
        else
        {
			$old_fuild_id = floatval(self::get_build_id($to));
            if($new_build_id > $old_fuild_id OR $old_fuild_id==0)$copy_flag = 1;
        }
		
        if($copy_flag == 1)
        {
                        $filefolder = self::get_file_folder($to);
            $folder = $filefolder[0];
            $file = $filefolder[1];
            if(!file_exists($folder))mkdir($folder); 
            if(!file_exists($folder . "/bak"))mkdir($folder . "/bak"); 
            $bak = $folder . "/bak/" . $file . "-" . $old_fuild_id . ".bak";
            if( file_exists($to) AND !file_exists($bak) )copy($to, $bak);
            copy($from,$to);
        }        
		
	}
	
    
    public static function get_file_folder($file)
    {
        $idx = strrpos($file,"/");
        if($idx === false)return "";
        $filefolder[] = substr($file,0,$idx);
        $filefolder[] = substr($file,$idx);
        return $filefolder;
    }
    
    public static function get_build_id($file)
    {
		if(!file_exists($file))return 0;
        $lines = file($file);		
        foreach($lines AS $line)
        {
			$idx = strpos($line, "-build_id:");
            if($idx !== false)
            {
                $values = explode(":",$line);
				return floatval($values[1]);
            }
        } 
        return 0;       
    }
	    

    public static function sql_install($sqlfile)
    {
		if(!file_exists($sqlfile))return true;

		$sql = "SELECT table_collation,character_set_name,engine
				FROM information_schema.`TABLES` T,
				    information_schema.`COLLATION_CHARACTER_SET_APPLICABILITY` CCSA
				WHERE CCSA.collation_name = T.table_collation
					AND T.table_schema = '" . _DB_NAME_ . "'
					AND T.table_name = '" . _DB_PREFIX_ . "employee'
				";
				
		$defs = Db::getInstance()->ExecuteS($sql);

		$charset =  $defs[0]['character_set_name'];
		if(empty($charset))$charset = 'utf8';

		$collation = $defs[0]['table_collation'];
		if(empty($collation))$collation = 'utf8_general_ci';

		$engine = $defs[0]['engine'];
		if(empty($engine))$engine = 'InnoDB';
		
		if (!file_exists($sqlfile))
			return (true);
		else if (!$sql = file_get_contents($sqlfile))
			return (false);
		$sql = str_replace('PREFIX_', _DB_PREFIX_, $sql);
		$def_charset_collation = ' DEFAULT CHARACTER  SET ' . $charset . ' COLLATE ' . $collation . ' ENGINE=' . $engine;
		$sql = str_replace('DEFAULT_CHARSET_COLLATION', $def_charset_collation , $sql);

		$sql = preg_split("/;\s*[\r\n]+/",$sql);
		

		
		foreach ($sql as $query)
		{
		    if(strlen($query)<=3)continue;
			if (!Db::getInstance()->Execute(trim($query)))
				return (false);
	    }
		return true;        
	}
	
	public static function subtabs_nbr($tabClassName)
	{
		$id_tab = self::getTabIDByClassName($tabClassName);
		if($id_tab <=0)return 0;
		$sql = 'SELECT COUNT(*) AS num FROM ' . _DB_PREFIX_ . 'tab WHERE id_parent=' . intval($id_tab);
		$subtabs = Db::getInstance()->getValue($sql);
		return 	$subtabs;	
	}
    
    public static function create_tab($tabName, $tabClassName, $parentTabClassName,$module)
	{
		$id_tab = self::getTabIDByClassName($tabClassName);
        if($id_tab >0)return $id_tab;

		$id_parent = 0;
		if(!empty($parentTabClassName))
		{
			$id_parent = self::getTabIDByClassName($parentTabClassName);
						if(!$id_parent)
				$id_parent = self::getTabIDByClassName( str_replace('Admin','Agile',$parentTabClassName));
		}
				$id_tab = 1;
		$result = Db::getInstance()->ExecuteS("SELECT MAX(`id_tab`) AS max_id FROM `"._DB_PREFIX_."tab`");					
		if (is_array($result) AND sizeof($result) > 0)
			$id_tab = $result[0]['max_id'] + 1;

				$position = 0;
		$result = Db::getInstance()->ExecuteS("SELECT MAX(`position`) AS max_position FROM `"._DB_PREFIX_."tab` WHERE `id_parent` = " .  $id_parent);
		if (is_array($result) AND sizeof($result) > 0)
			$position = $result[0]['max_position'] + 1;

	    	    $sql = 'INSERT INTO `' . _DB_PREFIX_. 'tab` (`id_tab`, `id_parent`, `class_name`, `module`, `position`, `active`,`hide_host_mode`,`icon`) VALUES (' . $id_tab . ',' .  $id_parent . ',\'' . $tabClassName . '\',\'' . $module . '\',' . $position . ', 1, 0,\'\')';

	    Db::getInstance()->Execute($sql);

		$languages = Language::getLanguages(false);
	    if (is_array($languages) AND !empty($languages))
	    {
   		    $query = 'INSERT IGNORE INTO`' . _DB_PREFIX_ . 'tab_lang` (`id_tab`, `id_lang`, `name`) VALUES ';	
		    foreach ($languages as $language)
			    $query .= '(' . $id_tab . ', ' . $language['id_lang'] . ',\''. $tabName . '\'), ';
	        $query = rtrim($query, ', ');
	        Db::getInstance()->Execute($query);
        }
				$tab = array('class_name' =>$tabClassName);
			$roles = array("CREATE" => Access::sluggifyTab($tab,"CREATE"), "READ" => Access::sluggifyTab($tab,"READ"), "UPDATE" =>  Access::sluggifyTab($tab,"UPDATE"), "DELETE" =>  Access::sluggifyTab($tab,"DELETE"));
		Db::getInstance()->Execute('INSERT INTO `' . _DB_PREFIX_ . 'authorization_role` (slug) VALUES (\'' . $roles['CREATE'] . '\')');
		Db::getInstance()->Execute('INSERT INTO `' . _DB_PREFIX_ . 'authorization_role` (slug) VALUES (\'' . $roles['READ'] . '\')');
		Db::getInstance()->Execute('INSERT INTO `' . _DB_PREFIX_ . 'authorization_role` (slug) VALUES (\'' . $roles['UPDATE'] . '\')');
		Db::getInstance()->Execute('INSERT INTO `' . _DB_PREFIX_ . 'authorization_role` (slug) VALUES (\'' . $roles['DELETE'] . '\')');
		
				AgileInstaller::update_access(1, $tabClassName, 1, 1, 1, 1);

        return $id_tab;
	}
	
	public static function deleteProfilePermission($id_profile)
	{
				if(intval($id_profile) <= 1)return;		
		$sql = 'DELETE FROM ' . _DB_PREFIX_  . 'access WHERE id_profile=' . $id_profile;
		Db::getInstance()->Execute($sql);		
	}
	
		public static function getTabIDByClassName($tabClassName)
	{
		$sql = 'SELECT id_tab FROM '. _DB_PREFIX_ . 'tab WHERE class_name=\'' . $tabClassName . '\'';
		return intval(Db::getInstance()->getValue($sql));
	}
	
    
    public static function delete_tab($tabClassName)
    {
		$tid = self::getTabIDByClassName($tabClassName);
        if(isset($tid) AND $tid>0)
        {
			if(AgileInstaller::tabHasChildren($tabClassName))return;
			
            $tab = new Tab($tid);
            $tab->delete();
        }
    }
    
    public static function create_hook($hookname)
    {
		return; 			    	       	    	    	        }
    
	public static function update_access($id_profile, $tabClassName, $read, $update, $create, $delete)
    {
		$id_tab =  self::getTabIDByClassName($tabClassName);
		if(intval($id_tab)>0)
		{
			$tab = array('class_name' =>$tabClassName);
			$roles = array("CREATE" => Access::sluggifyTab($tab,"CREATE"), "READ" => Access::sluggifyTab($tab,"READ"), "UPDATE" =>  Access::sluggifyTab($tab,"UPDATE"), "DELETE" =>  Access::sluggifyTab($tab,"DELETE"));
			$sql = "DELETE FROM `"._DB_PREFIX_."access` WHERE `id_profile` = $id_profile AND `id_authorization_role` IN (SELECT `id_authorization_role` FROM  `"._DB_PREFIX_."authorization_role` WHERE slug IN ('" . implode("','", array_values($roles)) . "'))";
			Db::getInstance()->Execute($sql);		

			if($read == 1)
			{
				$sql = "INSERT INTO `"._DB_PREFIX_."access` (`id_profile`, `id_authorization_role`) SELECT $id_profile AS id_profile, `id_authorization_role` FROM  `"._DB_PREFIX_."authorization_role` WHERE slug = '" . $roles['READ'] . "'";
				Db::getInstance()->Execute($sql);
			}			
			if($update == 1)
			{
				$sql = "INSERT INTO `"._DB_PREFIX_."access` (`id_profile`, `id_authorization_role`) SELECT $id_profile AS id_profile, `id_authorization_role` FROM  `"._DB_PREFIX_."authorization_role` WHERE slug = '" . $roles['UPDATE'] . "'";
				Db::getInstance()->Execute($sql);
			}
			if($create == 1)
			{
				$sql = "INSERT INTO `"._DB_PREFIX_."access` (`id_profile`, `id_authorization_role`) SELECT $id_profile AS id_profile, `id_authorization_role` FROM  `"._DB_PREFIX_."authorization_role` WHERE slug = '" . $roles['CREATE'] . "'";
				Db::getInstance()->Execute($sql);
			}
			if($delete == 1)
			{
				$sql = "INSERT INTO `"._DB_PREFIX_."access` (`id_profile`, `id_authorization_role`) SELECT $id_profile AS id_profile, `id_authorization_role` FROM  `"._DB_PREFIX_."authorization_role` WHERE slug = '" . $roles['DELETE'] . "'";
				Db::getInstance()->Execute($sql);
			}
		}
    }
	
	public static function init_tab_prmission_for_existing_profiles($className,  $view, $edit, $add, $delete)
	{
		$context = Context::getContext();
		
		$profiles = Profile::getProfiles($context->language->id);
		$id_ams = (int)Configuration::get('AGILE_MS_PROFILE_ID');
		foreach($profiles as $profile)
		{
						if($profile['id_profile'] == $id_ams)continue; 
			self::update_access((int)$profile['id_profile'],$className, $view, $edit, $add, $delete);
		}
	}

	public static function init_profile_prmission_for_existing_tabs($id_profile, $view, $edit, $add, $delete)
	{
		$context = Context::getContext();
		
		$tabs = Tab::getTabs($context->language->id);
		foreach($tabs as $tab)
		{
			self::update_access($id_profile,$tab['class_name'], $view, $edit, $add, $delete);
		}
	}

	
	public static function add_field_ifnotexists($table,$field,$datatype,$default)
    {
        if(empty($table) OR empty($field) OR empty($datatype))return;
        
        if(!self::column_exists($table,$field))
		{
			$sql = 'ALTER TABLE ' . _DB_PREFIX_ . '' . $table . ' ADD ' . $field . ' ' . $datatype . ' NULL';
						if($default != '')$sql .= ' DEFAULT ' . $default;
			$sql .= ';';
			Db::getInstance()->Execute($sql);
		}
    }
    
    public static function add_index_ifnotexists($table,$field)
    {
    	if(empty($table) OR empty($field))return;
    
    	if(!self::index_exists($table,$field))
    	{
    		$sql = 'ALTER TABLE ' . _DB_PREFIX_ . '' . $table . ' ADD INDEX idx_' . $field . '('. $field . ')';
    		Db::getInstance()->Execute($sql);
    	}
    }

    public static function show_agile_links()
    {
        $link_guide = 'http://addons-modules.com/store/en/content/category/2-user-s-guide';
        $link_forum = 'http://addons-modules.com/forum/';
        $link_twitter = 'http://twitter.com/agilemodules/';
        $link_facebook = 'http://facebook.com/agilemodules';
		$link_youtube = 'http://www.youtube.com/channel/UCD7EEoEiGgxjNpoj4u2T3Rg';
		return '
        <div style="border:dotted 1px blue;padding:10px">Need Help?
            <a href="' . $link_youtube .'" target="help" style="text-decoration:underline;color:Blue;">YouTube</a>&nbsp;
            <a href="' . $link_guide .'" target="help" style="text-decoration:underline;color:Blue;">User Guide</a>&nbsp;
            <a href="' . $link_forum . '"  target="help" style="text-decoration:underline;color:Blue;">Agile Support Forum</a>&nbsp;
            <a href="' . $link_twitter . '"  target="help" style="text-decoration:underline;color:Blue;">Twitter</a>&nbsp;
            <a href="' . $link_facebook . '"  target="help" style="text-decoration:underline;color:Blue;">Facebook</a>
        </div>
        <br />
        ';
    }
    

   public static function column_exists($table,$column)
   {
       $sql = "show columns from " . _DB_PREFIX_ . $table;
  	   $columns = Db::getInstance()->ExecuteS($sql);
       foreach($columns AS $c)
       {
           if(strtolower($c['Field']) == strtolower($column))return true;
       }
       
       return false;
   }
   
   public static function index_exists($table,$field)
   {
   	$sql = "show index from " . _DB_PREFIX_ . $table;
   	$columns = Db::getInstance()->ExecuteS($sql);
   	foreach($columns AS $c)
   	{
   		if(strtolower($c['Column_name']) == strtolower($field))return true;
   	}
   	 
   	return false;
   }

    public static function createLinkedOrderState($state_name)
    {
        $sql = 'SELECT id_order_state FROM `'._DB_PREFIX_.'order_state_lang` WHERE name=\'' . $state_name . '\'';
        $id_order_state = (int)Db::getInstance()->getValue($sql);
        if($id_order_state >0)return new OrderState($id_order_state);

		$newState = new OrderState();
		$newState->id = 0;
	    $newState->invoice = 0;
	    $newState->send_email = 0;
	    $newState->color = '#FFFFBB';           
	    $newState->unremovable = 1;
	    $newState->hidden = 0;
        $newState->logable = 0;
        $newState->delivery = 0;

		$languages = Language::getLanguages();
		foreach ($languages AS $language)
		{
  			$newState->{'name'}[intval($language['id_lang'])] = $state_name;
  			$newState->{'template'}[intval($language['id_lang'])] = '';
        }
	    $newState->add();

        return $newState;
    }
	
	public static function CanModuleOverride($module2install)
	{
		$errors = array();

				include_once(_PS_ROOT_DIR_ . '/modules/agilekernel/agilekernel.php');
		$akmodule = new AgileKernel();
		$shared_classes = $akmodule->shared_override_needs_install($module2install);
		$sh_errs = $akmodule->can_install_shared_overrides($shared_classes);
		if(!empty($sh_errs))$errors = array_merge($errors, $sh_errs);

				if(empty($errors))
		{
			$sh_errs = $akmodule->install_shared_override($shared_classes, $module2install);
			if(!empty($sh_errs))$errors = array_merge($errors, $sh_errs);
		}

				$override_path =  _PS_ROOT_DIR_ .DIRECTORY_SEPARATOR .'modules' .DIRECTORY_SEPARATOR . $module2install .DIRECTORY_SEPARATOR. 'override';
		if (!is_dir($override_path))return $errors; 

		foreach (Tools::scandir($override_path, 'php', '', true) as $file)
		{
			$class = basename($file, '.php');
			if (Autoload::getInstance()->getClassPath($class.'Core'))
			{
				$errors_class = AgileInstaller::CanClassOverride($module2install, $class);
				if(!empty($errors_class))$errors = array_merge($errors, $errors_class);
			}
		}
		return $errors;
	}	
	
	public static function CanClassOverride($module2install, $classname)
	{
		$errors = array();
		
		$path = Autoload::getInstance()->getClassPath($classname.'Core');

				if (!($classpath = Autoload::getInstance()->getClassPath($classname)))return array();
		
		$override_path = _PS_ROOT_DIR_.DIRECTORY_SEPARATOR.Autoload::getInstance()->getClassPath($classname);
		if (!file_exists($override_path) || (file_exists($override_path) && !is_writable($override_path)))
			$errors[] =  Context::getContext()->getTranslator()->trans('file {0} is not writable.',array('{0}'=>$override_path),'Modules.AgileKernel.Admin');

		$conflict_link = '&nbsp;&nbsp;<a href="http://addons-modules.com/store/en/content/75-classmethod-conflict-and-code-merge" target="_blank" style="color:blue;text-decoration:underline;">Why this and How to resolve this?</a>';


				$override_file = file($override_path);
		eval(preg_replace(array('#^\s*<\?php#', '#class\s+'.$classname.'\s+extends\s+([a-z0-9_]+)(\s+implements\s+([a-z0-9_]+))?#i'), array('', 'class '.$classname.'OverrideOriginalTrial'), implode('', $override_file)));
		$override_class = new ReflectionClass($classname.'OverrideOriginalTrial');

		$module_file = file(_PS_ROOT_DIR_ .DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR . $module2install . DIRECTORY_SEPARATOR. 'override'.DIRECTORY_SEPARATOR.$path);
		eval(preg_replace(array('#^\s*<\?php#', '#class\s+'.$classname.'(\s+extends\s+([a-z0-9_]+)(\s+implements\s+([a-z0-9_]+))?)?#i'), array('', 'class '.$classname.'OverrideTrial'), implode('', $module_file)));
		$module_class = new ReflectionClass($classname.'OverrideTrial');

				foreach ($module_class->getMethods() as $method)
		{
			if ($override_class->hasMethod($method->getName()))
			{
				$errors[] = Context::getContext()->getTranslator()->trans('The method {0} in the class {1} is already overriden.',array('{0}'=>$method->getName(),'{1}'=> $classname),'Modules.AgileKernel.Admin'). $conflict_link;
			}
		}
				
				foreach ($module_class->getProperties() as $property)
		{
			if ($override_class->hasProperty($property->getName()))
			{
				$errors[] = Context::getContext()->getTranslator()->trans('The property {0} in the class {1} is already defined.',array('{0}'=>$property->getName(),'{1}'=> $classname),'Modules.AgileKernel.Admin') . $conflict_link;
			}
		}
		
		return $errors;		
	}
	
	public static function version_depencies($version_depencies)
	{
		$errors = array();
		foreach($version_depencies as $module_name => $min_version)
		{

			if(!Module::isInstalled($module_name))
			{
				$errors[] = Context::getContext()->getTranslator()->trans('This module requires module {0} {1} or above.',array('{0}'=>$module_name,'{1}'=> $min_version),'Modules.AgileKernel.Admin');
			}
			else
			{		
				include_once(_PS_ROOT_DIR_ . "/modules/" . $module_name . "/" . $module_name . ".php");
				$module = new $module_name();
				if(version_compare($module->version, $min_version , '<'))
				{
					$errors[] = Context::getContext()->getTranslator()->trans('This module requires module {0} {1} or above.',array('{0}'=>$module_name,'{1}'=> $min_version),'Modules.AgileKernel.Admin');
				}
			}
		}
		return $errors;
	}

	
	public static function update_module($module, $new_version, $url)
	{
		$errors = array();
		if(defined('_IS_AGILE_DEV_'))
		{
			$errors[] = Context::getContext()->getTranslator()->trans('You can not run update on Development environment.',array(),'Modules.AgileKernel.Admin'). $conflict_link;
			return $errors;
		}
		
		$updatepath = _PS_ROOT_DIR_ . "/modules/agilekernel/updates/";
		if(!file_exists($updatepath))mkdir($updatepath);
		if(!file_exists($updatepath . "zips"))mkdir($updatepath . "zips");
		if(!file_exists($updatepath . "backups"))mkdir($updatepath . "backups");
		if(!file_exists($updatepath . "modules"))mkdir($updatepath . "modules");

		if(empty($module))$errors[] = Context::getContext()->getTranslator()->trans('Invalid module name to update',array(),'Modules.AgileKernel.Admin');
	
		if(empty($new_version))$errors[] = Context::getContext()->getTranslator()->trans('Invalid version to update',array(),'Modules.AgileKernel.Admin');
		if(empty($url))$errors[] =  Context::getContext()->getTranslator()->trans('Invalid url to update',array(),'Modules.AgileKernel.Admin'); 
		if(!empty($errors))return $errors;

				$mobj = Module::getInstanceByName($module);
		if(version_compare($new_version, $mobj->version, "<="))$errors[] =  Context::getContext()->getTranslator()->trans('The version to update is less than or eauql to current version',array(),'Modules.AgileKernel.Admin');
		if(!empty($errors))return $errors;
		
				$zipfile = $updatepath . "zips/" . $module . "-" . $new_version . ".zip";
		file_put_contents($zipfile, file_get_contents($url));
		
				$backup_module = $updatepath . "backups/" . date("YmdHis") . "-for-" . $module ."-modulefolder.zip"; 
		$backup_override = $updatepath . "backups/" . date("YmdHis") . "-for-" . $module ."-storeoverride.zip"; 
		$zipper = new AgileZipper();
		$zipper->zipFolderRecusive($backup_module, _PS_ROOT_DIR_ . "/modules/" . $module);
		$zipper->zipFolderRecusive($backup_override, _PS_ROOT_DIR_ . "/override");
		
				$is_installed = Module::isInstalled($module);
		if($is_installed)
		{
			if(method_exists($mobj, "uninstall4Update"))$res = $mobj->uninstall4Update();
			else $res = $mobj->uninstall();
			
			if(!$res)$errors[] = Context::getContext()->getTranslator()->trans('Module was not uninstalled correctly.',array(),'Modules.AgileKernel.Admin'); 	
		}
		if(!empty($errors))return $errors;

		
				$modulefolder = _PS_ROOT_DIR_ . "/modules/" . $module;
		if($module != 'agilekernel')
		{
			if(!AgileHelper::RmdirRecursive($modulefolder))
			{

				$errors[] = Context::getContext()->getTranslator()->trans('Module folder cleaned up falied - some files or folder was not able to remove.',array(),'Modules.AgileKernel.Admin');
			}
		}

				$zip = new ZipArchive;
		if ($zip->open($zipfile) === TRUE) {
			
			$zip->extractTo(_PS_ROOT_DIR_ . "/modules");
			$zip->close();
		}
		else
		{
			$errors[] = Context::getContext()->getTranslator()->trans('Downloaded zip package is not a valid zip file or damaged.',array(),'Modules.AgileKernel.Admin'); 
		}
		if(!empty($errors))return $errors;

		$mobj = Module::getInstanceByName($module);
		if(!mobj)
		{
			$errors[] = Context::getContext()->getTranslator()->trans('Not able to load instance of module {0}.',array('{0}' => $module),'Modules.AgileKernel.Admin'); 
		}
		if(!empty($errors))return $errors;
		
		if($is_installed)
		{
			if(!$mobj->install())
				$errors = array_merge($errors, $mobj->$errors);
		}
		return $errors;
	}
		

	public static function cleanup_old_installation()
	{
		$adminfolder = AgileInstaller::detect_admin_folder($_SERVER['SCRIPT_FILENAME']);
		$files = array(
			'override/classes/controller/AdminController.php' 
			,'override/classes/controller/FrontController.php'
			,'override/classes/controller/ModuleFrontController.php' 
			,'override/classes/controller/ModuleAdminController.php'			
			,'override/classes/AdminTab.php'
			,'override/classes/Attachment.php' 
			,'override/classes/Carrier.php' 
			,'override/classes/Category.php' 
			,'override/classes/Customer.php' 
			,'override/classes/Cart.php' 
			,'override/classes/Dispatcher.php' 
			,'override/classes/Employee.php'
			,'override/classes/Link.php'
			,'override/classes/Language.php' 
			,'override/classes/Mail.php'
			,'override/classes/Manufacturer.php' 
			,'override/classes/ObjectModel.php' 
			,'override/classes/PaymentModule.php'
			,'override/classes/Product.php' 
			,'override/classes/ProductDownload.php' 
			,'override/classes/ProductSale.php' 
			,'override/classes/Search.php' 
			,'override/classes/Tools.php' 
			,'override/classes/helper/HelperList.php' 
			,'override/classes/module/Module.php' 
			,'override/classes/order/Order.php'
			,'override/classes/pdf/HTMLTemplate.php'
			,'override/classes/shop/Shop.php' 
			,'override/controllers/admin/AdminAddressesController.php' 
			,'override/controllers/admin/AdminCarriersController.php' 
			,'override/controllers/admin/AdminCarrierWizardController.php' 
			,'override/controllers/admin/AdminCategoriesController.php' 
			,'override/controllers/admin/AdminCustomersController.php' 
			,'override/controllers/admin/AdminEmployeesController.php' 
			,'override/controllers/admin/AdminFeaturesController.php' 
			,'override/controllers/admin/AdminLanguagesController.php' 
			,'override/controllers/admin/AdminLoginController.php' 
			,'override/controllers/admin/AdminManufacturersController.php' 
			,'override/controllers/admin/AdminOrdersController.php' 
			,'override/controllers/admin/AdminProductsController.php' 
			,'override/controllers/admin/AdminReturnController.php' 
			,'override/controllers/admin/AdminShippingController.php' 
			,'override/controllers/admin/AdminPreferencesController.php'
			,'override/controllers/admin/AdminShopController.php' 
			,'override/controllers/admin/AdminShopGroupController.php' 
			,'override/controllers/admin/AdminShopUrlController.php' 
			,'override/controllers/front/AuthController.php' 
			,'override/controllers/front/ProductController.php' 
			,'override/controllers/front/OrderController.php' 
			,'override/controllers/front/OrderOpcController.php' 
		);
		$results = array();
		foreach($files as $file)
		{
			if(empty($file))continue;
			if(strlen($file) >=5 AND substr($file,0,5) == "admin")
			{
				$file = $adminfolder . substr($file,5);					
			}
			$pathinfo = explode("/", $file);
			$filename = array_pop($pathinfo);
			$folder = implode("/",$pathinfo);

			$path = _PS_ROOT_DIR_ . (empty($folder)? "":  "/" . $folder);
			$bakpath = $path . "/bak";
			$fullname = $path . "/" . $filename;
			if(!file_exists($bakpath))mkdir($bakpath);

			if(file_exists($fullname))
			{
				$build_id = floatval(self::get_build_id($fullname));
				$bakfullname = $bakpath . "/" . $filename . ".last";
				if($build_id >0 && $build_id < 2013092401.0101)
				{
					rename($fullname , $bakfullname);
				}
			}			
		}
	}
	
	public static function tabHasChildren($tabClassName)
	{
		$id_tab = self::getTabIDByClassName($tabClassName);
		$sql = "SELECT COUNT(*) FROM " . _DB_PREFIX_ ."tab WHERE id_parent=" . (int)$id_tab;
		$cnt = Db::getInstance()->getValue($sql);
		return ($cnt > 0);
	}
	
	
		public static function ensureSellerReadModulePermission($id_profile, $slugs)
	{
		if((int)$id_profile <=0)return;
		foreach($slugs as $slug)
		{
			$sql = 'REPLACE INTO ' . _DB_PREFIX_ . 'module_access (id_authorization_role,id_profile)
				SELECT r.id_authorization_role, '  . $id_profile . ' as id_profile 
				FROM `' . _DB_PREFIX_ . 'authorization_role` r 
					LEFT JOIN ' . _DB_PREFIX_ . 'module_access a ON r.id_authorization_role = a.id_authorization_role AND a.id_profile = ' . $id_profile . '
				WHERE r.slug like \'ROLE_MOD_MODULE_%_' . $slug . '\' 
				and a.id_authorization_role IS NULL
				';
			DB::getInstance()->Execute($sql);
		}
	}
	
	public static function enableAllPaymentModulesForCarrier($id_carrier, $id_shop)
	{
		if((int)$id_carrier <=0 || (int)$id_shop <=0)return;
		$carrier = new Carrier($id_carrier);
		$modules = AgileHelper::getAllPaymentModules($id_shop);
		$moduleIds = array();
		foreach($modules as $module)
		{
			if(!in_array($carrier->id_reference, $module->reference))$moduleIds[] = $module->id;
		}
		if(empty($moduleIds))return;
		
		$sql = 'INSERT INTO ' . _DB_PREFIX_ . 'module_carrier (id_module, id_shop, id_reference) VALUES ';
		$idx = 0;
		foreach($moduleIds as $mid)
		{
			if($idx > 0)$sql = $sql  . ',';
			$sql = $sql . '(' . $mid . ',' . $id_shop . ',' . $carrier->id_reference . ')';
			$idx++;
		}
		Db::getInstance()->Execute($sql);
	}
	
}
