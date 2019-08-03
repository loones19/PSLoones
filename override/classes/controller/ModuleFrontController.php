<?php
class ModuleFrontController extends ModuleFrontControllerCore
{
    /*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:21
    * version: 3.7.3.2
    */
    protected $className;
    /*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:21
    * version: 3.7.3.2
    */
    protected $identifier;
    /*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:21
    * version: 3.7.3.2
    */
    protected $table;
    /*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:21
    * version: 3.7.3.2
    */
    protected $object;
    /*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:21
    * version: 3.7.3.2
    */
    protected $id_object;
	
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:21
    * version: 3.7.3.2
    */
    protected $warnings;
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:21
    * version: 3.7.3.2
    */
    protected function beforeDelete($object)
	{
		return false;
	}
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:21
    * version: 3.7.3.2
    */
    protected function afterDelete($object, $oldId)
	{
		return true;
	}
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:21
    * version: 3.7.3.2
    */
    protected function beforeAdd($object)
	{
		return true;
	}
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:21
    * version: 3.7.3.2
    */
    protected function afterAdd($object)
	{
		return true;
	}
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:21
    * version: 3.7.3.2
    */
    protected function afterUpdate($object)
	{
		return true;
	}
        
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:21
    * version: 3.7.3.2
    */
    protected function updateAssoShop($id_object = false, $new_id_object = false)
	{
		if (!Shop::isFeatureActive())
			return;
		$assos_data = $this->getAssoShop($this->table, $id_object);
		$assos = $assos_data[0];
		$type = $assos_data[1];
		if (!$type)
			return;
		Db::getInstance()->execute('
			DELETE FROM '._DB_PREFIX_.$this->table.'_'.$type.($id_object ? '
			WHERE `'.$this->identifier.'`='.(int)$id_object : ''));
		foreach ($assos as $asso)
		{
			Db::getInstance()->execute('
				INSERT INTO '._DB_PREFIX_.$this->table.'_'.$type.' (`'.pSQL($this->identifier).'`, id_'.$type.')
				VALUES('.($new_id_object ? $new_id_object : (int)$asso['id_object']).', '.(int)$asso['id_'.$type].')');
		}
	}
        
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:21
    * version: 3.7.3.2
    */
    public function validateRules($class_name = false)
	{
		if (!$class_name)
			$class_name = $this->className;
				$rules = call_user_func(array($class_name, 'getValidationRules'), $class_name);
		if ((count($rules['requiredLang']) || count($rules['sizeLang']) || count($rules['validateLang'])))
		{
						$default_language = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
						$languages = Language::getLanguages(false);
		}
				foreach ($rules['required'] as $field)
		{
			if (($value = Tools::getValue($field)) == false && (string)$value != '0')
			{
				if (!Tools::getValue($this->identifier) || ($field != 'passwd' && $field != 'no-picture'))
				{
					$this->errors[] = sprintf(
						Tools::displayError('The field %1$s is required'), 
						call_user_func(array($class_name, 'displayFieldName'), $field, $class_name)
					);
				}
			}
		}
				foreach ($rules['requiredLang'] as $field_lang)
		{
			if (($empty = Tools::getValue($field_lang.'_'.$default_language->id)) === false || $empty !== '0' && empty($empty))
			{
				$this->errors[] = sprintf(
						Tools::displayError('The field %1$s is required at least in %2$s'),
						call_user_func(array($class_name, 'displayFieldName'), $field_lang, $class_name),
						$default_language->name
					);
			}
		}
				foreach ($rules['size'] as $field => $max_length)
		{
			if (Tools::getValue($field) !== false && Tools::strlen(Tools::getValue($field)) > $max_length)
			{
				$this->errors[] = sprintf(
					Tools::displayError('The field %1$s is too long (%2$d chars max)'),
					call_user_func(array($class_name, 'displayFieldName'), $field_lang, $class_name),
					$max_length
					);
			}
		}
				foreach ($rules['sizeLang'] as $field_lang => $max_length)
		{
			foreach ($languages as $language)
			{
				$field_lang = Tools::getValue($field_lang.'_'.$language['id_lang']);
				if ($field_lang !== false && Tools::strlen($field_lang) > $max_length)
				{
					$this->errors[] = sprintf(
						Tools::displayError('The field %1$s (%2$s) is too long (%3$d chars max, html chars including)'),
							call_user_func(array($class_name, 'displayFieldName'), $field_lang, $class_name),
							$language['name'],
							$max_length
						);					
				}
			}
		}
				$this->_childValidation();
				foreach ($rules['validate'] as $field => $function)
		{
			if (($value = Tools::getValue($field)) !== false && ($field != 'passwd'))
			{
				if (!Validate::$function($value) && !empty($value))
				{
					$this->errors[] = sprintf(
							Tools::displayError('The field %1$s is invalid'), 
							call_user_func(array($class_name, 'displayFieldName'), $field, $class_name)
						);					
				}
			}
		}
				if (($value = Tools::getValue('passwd')) != false)
		{
			if ($class_name == 'Employee' && !Validate::isPasswdAdmin($value))
				$this->errors[] = sprintf(
						Tools::displayError('The field %1$s is invalid'), 
						call_user_func(array($class_name, 'displayFieldName'), 'passwd', $class_name)
					);					
		
			elseif ($class_name == 'Customer' && !Validate::isPasswd($value))
				$this->errors[] = sprintf(
						Tools::displayError('The field %1$s is invalid'), 
						call_user_func(array($class_name, 'displayFieldName'), 'passwd', $class_name)
					);					
		}
				foreach ($rules['validateLang'] as $field_lang => $function)
		{
			foreach ($languages as $language)
			{
				if (($value = Tools::getValue($field_lang.'_'.$language['id_lang'])) !== false && !empty($value))
				{
					if (!Validate::$function($value))
					{
						$this->errors[] = sprintf(
							Tools::displayError('The field %1$s (%2$) is invalid'),
							call_user_func(array($class_name, 'displayFieldName'), $field_lang, $class_name),
							$language['name']
							);
					}
				}
			}
		}
	}
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:21
    * version: 3.7.3.2
    */
    public function _childValidation()
	{
	}
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:21
    * version: 3.7.3.2
    */
    public function copyFromPost(&$object, $table)
	{
				foreach ($_POST as $key => $value)
		{
			if (key_exists($key, $object) && $key != 'id_'.$table)
			{
								if ($key == 'passwd' && Tools::getValue('id_'.$table) && empty($value))
					continue;
								if ($key == 'passwd' && !empty($value))
					$value = Tools::encrypt($value);
				$object->{$key} = $value;
			}
		}
				$rules = call_user_func(array(get_class($object), 'getValidationRules'), get_class($object));
		if (count($rules['validateLang']))
		{
			$languages = Language::getLanguages(false);
			foreach ($languages as $language)
			{
				foreach (array_keys($rules['validateLang']) as $field)
				{
					if (isset($_POST[$field.'_'.(int)$language['id_lang']]))
					{
						$object->{$field}[(int)$language['id_lang']] = $_POST[$field.'_'.(int)$language['id_lang']];
					}
				}
			}
		}
	}
	
	
	 	 	 	 	 	 	 	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:21
    * version: 3.7.3.2
    */
    public function getFieldValue($obj, $key, $id_lang = null)
	{
		if ($id_lang)
			$default_value = ($obj->id && isset($obj->{$key}[$id_lang])) ? $obj->{$key}[$id_lang] : '';
		else
			$default_value = isset($obj->{$key}) ? $obj->{$key} : '';
		return Tools::getValue($key.($id_lang ? '_'.$id_lang : ''), $default_value);
	}
	
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:21
    * version: 3.7.3.2
    */
    public function display()
	{
		$this->context->smarty->assign('warnings', $this->warnings);
		parent::display();
		
	}
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:21
    * version: 3.7.3.2
    */
    protected function displayWarning($msg)
	{
		$this->warnings[] = $msg;
	}
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:21
    * version: 3.7.3.2
    */
    protected function l($string, $specific = false, $class = NULL, $addslashes = false, $htmlentities = true)
	{
		return Translate::getModuleTranslation($this->module, $string, Tools::getValue('controller'));
	}
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:21
    * version: 3.7.3.2
    */
    public function pagination($total_products = null)
	{
		if (!self::$initialized) {
			$this->init();
		} elseif (!$this->context) {
			$this->context = Context::getContext();
		}
				$default_products_per_page = max(1, (int)Configuration::get('PS_PRODUCTS_PER_PAGE'));
		$n_array = array($default_products_per_page, $default_products_per_page * 2, $default_products_per_page * 5);
		if ((int)Tools::getValue('n') && (int)$total_products > 0) {
			$n_array[] = $total_products;
		}
				$this->n = $default_products_per_page;
		if (isset($this->context->cookie->nb_item_per_page) && in_array($this->context->cookie->nb_item_per_page, $n_array)) {
			$this->n = (int)$this->context->cookie->nb_item_per_page;
		}
		if ((int)Tools::getValue('n') && in_array((int)Tools::getValue('n'), $n_array)) {
			$this->n = (int)Tools::getValue('n');
		}
				$this->p = (int)Tools::getValue('p', 1);
				if (!is_numeric($this->p) || $this->p < 1) {
			Tools::redirect($this->context->link->getPaginationLink(false, false, $this->n, false, 1, false));
		}
				$current_url = preg_replace('/(?:(\?)|&amp;)p=\d+/', '$1', Tools::htmlentitiesUTF8($_SERVER['REQUEST_URI']));
		if ($this->n != $default_products_per_page || isset($this->context->cookie->nb_item_per_page)) {
			$this->context->cookie->nb_item_per_page = $this->n;
		}
		$pages_nb = ceil($total_products / (int)$this->n);
		if ($this->p > $pages_nb && $total_products != 0) {
			Tools::redirect($this->context->link->getPaginationLink(false, false, $this->n, false, $pages_nb, false));
		}
		$range = 2; 		$start = (int)($this->p - $range);
		if ($start < 1) {
			$start = 1;
		}
		$stop = (int)($this->p + $range);
		if ($stop > $pages_nb) {
			$stop = (int)$pages_nb;
		}
		$this->context->smarty->assign(array(
			'nb_products'       => $total_products,
			'products_per_page' => $this->n,
			'pages_nb'          => $pages_nb,
			'p'                 => $this->p,
			'n'                 => $this->n,
			'nArray'            => $n_array,
			'range'             => $range,
			'start'             => $start,
			'stop'              => $stop,
			'current_url'       => $current_url,
			));
	}	
}
