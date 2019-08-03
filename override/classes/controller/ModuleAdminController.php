<?php
abstract class ModuleAdminController extends ModuleAdminControllerCore
{
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:21
    * version: 3.7.3.2
    */
    protected function l($string, $class = 'AdminTab', $addslashes = false, $htmlentities = true)
	{
		return Translate::getModuleTranslation($this->module, $string, Tools::getValue('controller'));
	}
}
