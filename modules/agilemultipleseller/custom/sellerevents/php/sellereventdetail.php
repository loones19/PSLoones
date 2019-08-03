<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.

class AgileMultipleSellerSellerEventDetailModuleFrontController extends AgileModuleFrontController{
	protected $event_menus = array();

	protected $max_image_size = null;



	public $auth = true;

	public $ssl = true;

	protected $languages;

	protected $id_language;

	protected $event_menu;

	protected $object;

	protected $id_object;

	public function __construct(){
		parent::__construct();
		
		include_once(_PS_MODULE_DIR_.'agilemultipleseller/SellerEvent.php');
	
				
		$this->object = new SellerEvent();

		$this->table = 'seller_event';
        $this->identifier = 'id_event';
        $this->className = 'SellerEvent';
    }	

    public function init(){
		if (session_status() == PHP_SESSION_NONE)@session_start();
		parent::init();
    	$this->id_object = intval(Tools::getValue('id_event'));
    	$this->id_language = intval(Tools::getValue('id_language'));
    	if($this->id_language==0)$this->id_language = $this->context->language->id;
    }

    public function setMedia()   {
    	parent::setMedia();       
    	$R818286DEB5BD8530DE0AC1396B31A7C2 = new Language($this->context->language->id);    
    	$RC5E17D4F14E490518D56BCC1406C8E81 = (file_exists(_PS_JS_DIR_.'jquery/ui/jquery.ui.datepicker-'.$R818286DEB5BD8530DE0AC1396B31A7C2->iso_code.'.js') ? $R818286DEB5BD8530DE0AC1396B31A7C2->iso_code : 'en');      
    	$this->addJqueryUI(array(     'ui.core',     'ui.widget'     )); 

		$this->addJS(array(    
			_PS_JS_DIR_.'tools.js',    
			_PS_JS_DIR_.'jquery/ui/jquery.ui.mouse.min.js',    
			_PS_JS_DIR_.'jquery/ui/jquery.ui.slider.min.js',   
			_PS_JS_DIR_.'jquery/ui/jquery.ui.datepicker.min.js',    
			_PS_JS_DIR_.'jquery/ui/jquery.ui.datepicker-' .$RC5E17D4F14E490518D56BCC1406C8E81 . '.js',    
			_PS_JS_DIR_.'jquery/ui/jquery.ui.core.min.js',    
			_PS_JS_DIR_.'jquery/ui/jquery.ui.widget.min.js',    
			_PS_JS_DIR_.'jquery/plugins/jquery.typewatch.js',    
			_PS_JS_DIR_.'jquery/plugins/timepicker/jquery-ui-timepicker-addon.js',    
			_PS_JS_DIR_.'jquery/plugins/jquery.tablednd.js',        
			_PS_ROOT_DIR_.'/modules/agilemultipleseller/js/AgileStatesManagement.js',    
			_PS_ROOT_DIR_.'/modules/agilemultipleseller/js/front-categories-tree.js',    
			_PS_ROOT_DIR_.'/modules/agilemultipleseller/js/front-products.js',    
			_PS_ROOT_DIR_.'/modules/agilemultipleseller/js/treeview-categories/jquery.treeview-categories.js',    
			_PS_ROOT_DIR_.'/modules/agilemultipleseller/js/treeview-categories/jquery.treeview-categories.async.js',    
			_PS_ROOT_DIR_.'/modules/agilemultipleseller/js/treeview-categories/jquery.treeview-categories.edit.js',    
			_PS_ROOT_DIR_.'/modules/agilemultipleseller/js/agile_tiny_mce.js',    
			_PS_ROOT_DIR_.'/modules/agilemultipleseller/filemanager/plugin.js',
			_PS_ROOT_DIR_.'/modules/agilemultipleseller/replica/themes/default/js/dropdown.js',
			)); 
	}
    public function initContent(){
		parent::initContent();

		$language = new Language($this->id_language);
		$isoTinyMCE = (file_exists(_PS_ROOT_DIR_.'/js/tiny_mce/langs/'.$language->iso_code.'.js') ? $language->iso_code : 'en');
		$ad = str_replace("\\", "\\\\", dirname($_SERVER["PHP_SELF"]));        

		$categories = array();

		$this->languages = Language::getLanguages(false);
		
		$HOOK_PRODYCT_LIST_OPTIONS = '';

		$eventid = Tools::getValue('id_event'); 
 		if(!empty($eventid)){
			$this->object = new SellerEvent((int)Tools::getValue('id_event'));
			if(Validate::isLoadedObject($this->object)){
				$this->id_object = $this->object->id_event;
				if($this->object->id_seller != AgileSellerManager::getLinkedSellerID($this->context->customer->id)){
					$this->errors[] = Tools::displayError('Cannot find event');
					$this->object = null;
				}
			}else
				$this->errors[] = Tools::displayError('Cannot find event');
		}


		
        self::$smarty->assign(array(
            'ad' => $ad,
            'isoTinyMCE' => $isoTinyMCE,
            'theme_css_dir' => _THEME_CSS_DIR_,
			'ajaxurl' => _MODULE_DIR_,
			'event' => $this->object,
			'id_event' => $this->id_object,
			'id_language' => $this->id_language,
			'languages' => $this->languages,
        ));

		if(isset($_SESSION['cfmmsg_flag']) && (int)$_SESSION['cfmmsg_flag'] == 1)
		{
			self::$smarty->assign(array(
				'cfmmsg_flag' => 1
				));
				
			unset($_SESSION['cfmmsg_flag']);
		}
		
		self::$smarty->assign(array(
            'seller_tab_id' => 101
			));
			
		$this->setTemplate('sellereventdetail.tpl');
	}

	public function postProcess(){
				if (Tools::isSubmit('submitEvent') and Tools::getValue('action') == 'save_event'){

						$this->copyFromPost($this->object,$this->table);
			if(empty($this->object->id)){
				$this->object->create_date = date("Y-m-d H:i:s");
			}
			if(empty($this->object->id_seller)){
				$this->object->id_seller = AgileSellerManager::getLinkedSellerID($this->context->customer->id);
				$this->object->id_customer = $this->context->customer->id;
			}
			$this->errors = array_merge($this->errors, $this->object->validateController());
						if(empty($this->errors))
				if($this->id_object <= 0){
					if($this->object->add()){
												$_SESSION['cfmmsg_flag'] = 1;
						SellerEvent::processPdfUpload($this->object->id);
						Tools::redirect($this->context->link->getModuleLink('agilemultipleseller', 'sellereventdetail', array('id_event' => $this->object->id)));
					}
				}
				else{
					$this->object->id_event = $this->object->id = $this->id_object;
					if($this->object->update()){
					SellerEvent::processPdfUpload($this->object->id);
					}
				}
									if(empty($this->errors))self::$smarty->assign('cfmmsg_flag',1);
		}
		else if (Tools::isSubmit('deletePDF'))
		{
			$this->object = new SellerEvent((int)Tools::getValue('id_event'));
			if($this->object->id > 0)$this->object->deletePDF();
		}
		
	}
	
	private function _debug($var,$exit = false){
		echo "<pre>";
		var_dump($var);
		echo "</pre>";
		if($exit) exit;
	}
}