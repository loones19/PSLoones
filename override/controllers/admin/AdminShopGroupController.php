<?php
class AdminShopGroupController extends AdminShopGroupControllerCore
{
	
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:38
    * version: 3.7.3.2
    */
    public function viewAccess($disable = false)
	{
		if(Module::isInstalled('agilemultipleshop'))return true;
		return parent::viewAccess($disable);
	}
}
