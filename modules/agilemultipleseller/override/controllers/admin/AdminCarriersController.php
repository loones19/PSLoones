<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.

class AdminCarriersController extends AdminCarriersControllerCore
{
	public function __construct()
	{
		parent::__construct();
		
		if(!$this->is_seller)
		{
			$this->fields_list['seller'] = array('title' => $this->l('Seller'), 'width' => 20, 'filter_key' => 'amsl!company');
		}
	}
	
	public function getList($id_lang, $orderBy = NULL,  $orderWay = NULL,  $start = 0, $limit = NULL, $id_lang_shop = false)
	{
		if(Module::isInstalled('agilemultipleseller'))
			$this->agilemultipleseller_list_override();

		parent::getList($id_lang, $orderBy , $orderWay, $start, $limit);
	}

	public function renderOptions()
	{
		if($this->is_seller)return;
		
		return parent::renderOptions();
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

	public function processDelete()
	{
		if(!Module::isInstalled('agilesellershipping'))return parent::processDelete();

		$deleteaction = Tools::getValue('deletecarrier');
		$id_carrier = Tools::getValue('id_carrier');
		$linkid = Carrier::getActiveIDByID(Configuration::get('AGILE_SS_CARRIER_ID'));;
		if($linkid == $id_carrier && $deleteaction == 1)
		{
			$this->errors[] = Tools::displayError('This is Seller Default Carrier which is linked to Agile Seller Shipping module, it cannot be deleted.');
			return false;
		}

		parent::processDelete();
	}	

}

