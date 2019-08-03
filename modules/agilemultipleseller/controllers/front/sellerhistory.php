<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
include_once(_PS_ROOT_DIR_.'/modules/agilesellercommission/SellerCommission.php');
class AgileMultipleSellerSellerHistoryModuleFrontController extends AgileModuleFrontController
{
	public function initContent()
	{
		parent::initContent();
        
		$id_commission_currency = (int)Configuration::get('ASC_COMMISSION_CURRENCY');

		$commssionrecords = SellerCommission::getRecords($this->sellerinfo->id_seller, $this->p, $this->n,$this->orderBy,$this->orderWay);

        self::$smarty->assign(array(
            'seller_tab_id' => 7
			,'commssionrecords' => $commssionrecords
			,'id_commission_currency' => $id_commission_currency
            ));

		$this->setTemplate('module:agilemultipleseller/views/templates/front/sellerhistory.tpl');
	}
	
	
}
