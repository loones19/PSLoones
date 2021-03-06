<?php
class ProductController extends ProductControllerCore
{
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:45
    * version: 3.7.3.2
    */
    public function init()
	{
		parent::init();
		if(Module::isInstalled('agilepricecomparison'))
		{
			$HOOK_AGILE_PRICE_COMPARISON = '';
			if(!intval(Configuration::get('AGILE_PC_USE_DEFAULT_HOOK')))
				$HOOK_AGILE_PRICE_COMPARISON = Module::hookExec('agilepricecomparison', array());
			$this->context->smarty->assign(array(	'HOOK_AGILE_PRICE_COMPARISON' => $HOOK_AGILE_PRICE_COMPARISON));
		}		
	}
	
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:45
    * version: 3.7.3.2
    */
    public function initContent()
	{
		if(!$this->listAllowed())
		{
			Tools::redirect('index.php?controller=404');
		}
		parent::initContent();
	}
	
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:45
    * version: 3.7.3.2
    */
    private function listAllowed()
	{
				if (Tools::getValue('adtoken') == Tools::getAdminToken('AdminProducts'.(int)Tab::getIdFromClassName('AdminProducts').(int)Tools::getValue('id_employee')))
		{
			return true;				
		}
		
	    if(Module::isInstalled('agilemultipleseller'))
	    {
			include_once(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/agilemultipleseller.php");
			include_once(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/SellerInfo.php");
	    
			$id_owner = AgileSellerManager::getObjectOwnerID('product',Tools::getValue('id_product'));
			$this->context->smarty->assign(
				array('id_seller' => $id_owner
			));
			if($id_owner > 0)
			{
				if(intval(Configuration::get('AGILE_MS_PRODUCT_APPROVAL'))==1)
				{
					$approved = AgileMultipleSeller::is_list_approved(Tools::getValue('id_product'));
					if($approved !=1)return false;
				}
								if(Module::isInstalled('agilesellerlistoptions'))
				{
					include_once(_PS_ROOT_DIR_ . "/modules/agilesellerlistoptions/agilesellerlistoptions.php");
					$listoption = AgileSellerListOptions::get_product_list_option(Tools::getValue('id_product'), AgileSellerListOptions::ASLO_OPTION_LIST);
					$liststatus = intval($listoption['status']);
					$aslo_list_prod_id = intval(Configuration::get('ASLO_PROD_FOR_OPTION' . AgileSellerListOptions::ASLO_OPTION_LIST));
					if($liststatus != AgileSellerListOptions::ASLO_STATUS_IN_EFFECT && $aslo_list_prod_id != AgileSellerListOptions::ASLO_ALWAYS_FREE)
    					return false;
				}
			}	
		}
		return true;		
	}
}