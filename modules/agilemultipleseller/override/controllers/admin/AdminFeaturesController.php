<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.

class AdminFeaturesController extends AdminFeaturesControllerCore
{
	public function __construct()
	{
		parent::__construct();
		
		if(!$this->is_seller)
		{
			$this->fields_list['seller'] = array('title' => $this->l('Seller'), 'width' => 20, 'filter_key' => 'amsl!company');
		}
	}

	public function init()
	{
		if(Module::isInstalled('agilemultipleseller'))
			$this->agilemultipleseller_list_override();	
		
				if(Tools::isSubmit('deletefeature_value'))
		{
			$_POST["id_feature"] = Db::getInstance()->getValue('SELECT id_feature FROM ' . _DB_PREFIX_ . 'feature_value WHERE id_feature_value=' . intval(Tools::getValue("id_feature_value"))); 
		}
		parent::init();
	}	

	public function getList($id_lang, $order_by = null, $order_way = null, $start = 0, $limit = null, $id_lang_shop = false)
	{
				if ($this->table == 'feature_value')
			$this->_where = sprintf('AND a.`id_feature` = %d', (int)Tools::getValue('id_feature'));
		
		parent::getList($id_lang, $order_by, $order_way, $start, $limit, $id_lang_shop);
	}

	protected function agilemultipleseller_list_override()
	{
		if(!Module::isInstalled('agilemultipleseller'))return;

		parent::agilemultipleseller_list_override();
		if($this->is_seller)
		{
			$this->_where = $this->_where . ' AND IFNULL(ao.`id_owner`,0) > 0';
		}
		else
		{
			if(empty($this->_select) OR substr(trim($this->_select),-1,1) == ",")
			{
				$this->_select = $this->_select . 'amsl.company AS seller';
			}
			else
			{
				$this->_select = $this->_select . ',amsl.company AS seller';
			}
		}
	}

		public function processSave()
	{
		$obj = parent::processSave();
		if(!Module::isInstalled('agilemultipleseller'))return $obj;

		if($this->is_seller)
		{
			AgileSellerManager::assignObjectOwner('feature',$obj->id, $this->context->cookie->id_employee);
		}
		else
		{
			AgileSellerManager::assignObjectOwner('feature',$obj->id, Tools::getValue('id_seller'));
		}
		
		return $obj;
	}

}

