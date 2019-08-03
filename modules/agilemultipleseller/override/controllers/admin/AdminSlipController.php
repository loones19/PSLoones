<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.

class AdminSlipController extends AdminSlipControllerCore
{
	public function getList($id_lang, $orderBy = NULL,  $orderWay = NULL,  $start = 0, $limit = NULL, $id_lang_shop = false)
	{
		if(Module::isInstalled('agilemultipleseller'))
			$this->agilemultipleseller_list_override();

		parent::getList($id_lang, $orderBy , $orderWay, $start, $limit);
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

}

