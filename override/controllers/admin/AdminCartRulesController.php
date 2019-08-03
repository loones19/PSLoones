<?php
class AdminCartRulesController extends AdminCartRulesControllerCore
{
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:32
    * version: 3.7.3.2
    */
    public function initToolbar()
	{
		if(Module::isInstalled('agilemultipleseller') AND $this->is_seller)return;
		parent::initToolbar();
	}
}
