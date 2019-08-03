<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.

class SellerEvent extends ObjectModel{

    public $id;

    public $title;

    
    public $start_date;

    public $end_date;

    public $create_date;

    public $place;

    public $description;

    public $id_seller;
    
    public $id_customer;

    public $active;

	public static $definition = array(
        'table' => 'seller_event',
        'primary' => 'id_event',
        'multilang' => true,
        'fields' => array(
                        'title' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'required' => true, 'size' => 255),
            'description' =>  array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml', 'size'=>65500, 'required'=>true),
            'id_seller' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'active' => array('type' => self::TYPE_BOOL, 'shop' => true, 'validate' => 'isBool'),
            'id_customer' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => false),
            'place' => array('type' => self::TYPE_STRING, 'lang' => false, 'validate' => 'isGenericName', 'required' => true, 'size' => 255),
            'start_date' => array('type' => self::TYPE_DATE, 'validate' => 'isDate','required'=>true),
            'end_date' => array('type' => self::TYPE_DATE, 'validate' => 'isDate','required'=>true),
            'create_date' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
                    ),
    );

    public function __construct($id = null){
        $this->id_event = $id;
        parent::__construct($id);
    }

    public function add($autodate = true, $null_values = true){
        if (parent::add($autodate, $null_values)) {
            return true;
        }
        return false;
    }

    public function validateField($field, $value, $id_lang = null, $skip = array(), $human_errors = false)
    {
        return parent::validateField($field, $value, $id_lang, $skip, $human_errors);
    }

    public static function getEventsBySellerId($id_seller, $id_lang,$p=1,$n=10){
        $sql = "SELECT e.*, el.title, el.description, el.id_lang FROM `"._DB_PREFIX_."seller_event` as e
                LEFT JOIN `"._DB_PREFIX_."seller_event_lang` as el on (e.id_event = el.id_event)
                WHERE el.id_lang='".(int)$id_lang."' and e.id_seller='".(int) $id_seller."'  ORDER BY e.id_event DESC LIMIT ".(((int)($p) - 1) * (int)($n)).','.(int)($n);
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    }

    public static function getCountEventsBySellerId($id_seller, $id_lang){
        $sql = "SELECT count(e.id_event) as count FROM `"._DB_PREFIX_."seller_event` as e
                LEFT JOIN `"._DB_PREFIX_."seller_event_lang` as el on (e.id_event = el.id_event)
                WHERE el.id_lang='".(int)$id_lang."' and e.id_seller='".(int) $id_seller."'  ORDER BY e.id_event DESC";
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    }

    public static function getEventList($id_lang){
        $sql = "SELECT e.*, el.title, el.description, el.id_lang, s.id_sellerinfo, sl.company FROM `"._DB_PREFIX_."seller_event` as e
                LEFT JOIN `"._DB_PREFIX_."seller_event_lang` as el on (e.id_event = el.id_event)
                LEFT JOIN `"._DB_PREFIX_."sellerinfo` as s ON (s.id_seller = e.id_seller)
                LEFT JOIN `"._DB_PREFIX_."sellerinfo_lang` as sl ON (sl.id_sellerinfo = s.id_sellerinfo AND sl.id_lang='" . (int) $id_lang . "')
                WHERE el.id_lang='".(int)$id_lang."' and active=1 and e.end_date>='".date("Y-m-d")."' ORDER BY e.id_event DESC";
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    }

    public static function getEventById($id,$id_lang){
        $sql = "SELECT e.*, el.title, el.description, el.id_lang, s.id_sellerinfo, sl.company  FROM `"._DB_PREFIX_."seller_event` as e
                LEFT JOIN `"._DB_PREFIX_."seller_event_lang` as el on (e.id_event = el.id_event)
                LEFT JOIN `"._DB_PREFIX_."sellerinfo` as s ON (s.id_seller = e.id_seller)
                LEFT JOIN `"._DB_PREFIX_."sellerinfo_lang` as sl ON (sl.id_sellerinfo = s.id_sellerinfo AND sl.id_lang='" . (int) $id_lang . "')
                WHERE el.id_lang='".(int)$id_lang."' and active=1 and e.start_date<='".date("Y-m-d")."' and e.end_date>='".date("Y-m-d")."' and e.id_event='". (int) $id ."'";
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($sql);
    }

	public static function get_pdffile_folder()
	{
		$folder =  _PS_IMG_DIR_ . "as/";
		if (!file_exists($folder))mkdir($folder);
		return  $folder;
	}

	public static function filename_encode($id_event)
	{
		return  base64_encode("_event" . $id_event);
	}
	
	public static function processPdfUpload($id_event)
	{		
		$pdffile_folder = self::get_pdffile_folder();

		if(!empty($_FILES['pdffile']['name']))
		{
			$pathinfo = pathinfo($_FILES['pdffile']['name']);
			if(!in_array($pathinfo['extension'], array('pdf')))return false;

			$filename = $pdffile_folder . self::filename_encode($id_event) . ".pdf";
			if(!move_uploaded_file($_FILES['pdffile']['tmp_name'], $filename)) return false;
			return true;
		}
		return true;
	}
	
    public function delete()
	{
		$this->deletePDF();
	    $ret = parent::delete();
		return $ret;
	}
	
	public function deletePDF()
	{
		$filename = _PS_IMG_DIR_ . "as/" . self::filename_encode($this->id) . ".pdf";
		if(file_exists($filename))unlink($filename);
	}
	
	public function get_event_pdffile_url()
	{
		return self::get_event_pdffile_url_static($this->id);
	}

	public static function get_event_pdffile_url_static($id_event)
	{
		$pdffile = _PS_IMG_DIR_ . "as/" . self::filename_encode($id_event) . ".pdf";
		if(!file_exists($pdffile))return "";
		return Tools::getShopDomainSsl(true) . __PS_BASE_URI__ . 'img/as/' . self::filename_encode($id_event) . '.pdf?' . date("YmdHis",filemtime($pdffile));
	}	
	
    public function validateController($htmlentities = true)
    {
        $errors = array();
        $languages = Language::getLanguages(false);
        $default_language = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $class_name = 'SellerEvent';
        $required_fields_database = (isset(self::$fieldsRequiredDatabase[get_class($this)])) ? self::$fieldsRequiredDatabase[get_class($this)] : array();
        foreach ($this->def['fields'] as $field => $data)
        {
            
            if (in_array($field, $required_fields_database))
            {
                $data['required'] = true;
            }
            
             if (isset($data['required']) && $data['required'] && ($value = Tools::getValue($field, $this->{$field})) == false && (string)$value != '0')
            {
                if (!$this->id || $field != 'passwd')
                {
                    $errors[] = '<b>'.self::displayFieldName($field, get_class($this), $htmlentities).'</b> '.Tools::displayError('is required.');
                }
            }

                        if (isset($data['size']) && ($value = Tools::getValue($field, $this->{$field})) && Tools::strlen($value) > $data['size'])
            {
                $errors[] = sprintf(
                    Tools::displayError('%1$s is too long. Maximum length: %2$d'),
                    self::displayFieldName($field, get_class($this), $htmlentities),
                    $data['size']
                    );
            }

            $value = Tools::getValue($field, $this->{$field});
            if (($value || $value =='0') || ($field == 'postcode' && $value == '0'))
            {
                if($field == 'title' 
                || $field == 'description'
                || in_array($field, SellerInfo::getCustomMultiLanguageFields()))
                {
                    if (($field == 'title') && (($empty = Tools::getValue($field.'_'.$default_language->id)) === false || $empty !== '0' && empty($empty)))
                    {
                        $errors[] = sprintf(
                            Tools::displayError('The field %1$s is required at least in %2$s.'),
                            call_user_func(array($class_name, 'displayFieldName'), $field, $class_name),
                            $default_language->name
                            );
                    }
                    
                    $field_lang_value_default = '';
                    foreach ($languages as $language)
                    {
                        $field_lang_value_default = Tools::getValue($field.'_'.$language['id_lang']);
                        if(!empty($field_lang_value_default))break;
                    }
                    
                    foreach ($languages as $language)
                    {
                        $field_lang_value = Tools::getValue($field.'_'.$language['id_lang']);
                        if ($field_lang_value !== false && Tools::strlen($field_lang_value) > $data['size'])
                        {
                            $errors[] = sprintf(
                                Tools::displayError('The field %1$s (%2$s) is too long (%3$d chars max, html chars including).'),
                                call_user_func(array($class_name, 'displayFieldName'), $field, $class_name),
                                $language['name'],
                                $data['size']
                                );
                        }
                        if (isset($data['validate']) && !Validate::$data['validate']($field_lang_value) && !empty($field_lang_value))
                        {
                            $errors[] = sprintf(
                                Tools::displayError('The field %1$s (%2$s) Is Invalid.'),
                                call_user_func(array($class_name, 'displayFieldName'), $field, $class_name),
                                $language['name']
                                );
                        }

                        $this->{$field}[$language['id_lang']] = (empty($field_lang_value)? $field_lang_value_default : $field_lang_value);
                    }
                }
                else
                {
                    
                    if (isset($data['validate']) && !Validate::$data['validate']($value) && (!empty($value) || (isset($data['required']) && $data['required'])))
                    {
                        $errors[] = '<b>' . $data['validate'] . ' ' . self::displayFieldName($field, get_class($this), $htmlentities).'</b> '.Tools::displayError('is invalid.');
                    }
                    else
                    {
                        if (isset($data['copy_post']) && !$data['copy_post'])continue;
                        $this->{$field} = $value;
                    }
                }
            }
            else
            {
				if (isset($data['copy_post']) && !$data['copy_post'])continue;
				$this->{$field} = $value;
			}
		}
		return $errors;
	}
}