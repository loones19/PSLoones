<?php
class AdminPreferencesController extends AdminPreferencesControllerCore
{
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:37
    * version: 3.7.3.2
    */
    public function __construct()
	{
		parent::__construct();
				if(Module::isInstalled('agilemultipleseller') OR Module::isInstalled('agilemultipleshop'))
			unset($this->fields_options['general']['fields']['PS_MULTISHOP_FEATURE_ACTIVE']);	
	}
}
