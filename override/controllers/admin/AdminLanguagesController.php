<?php
class AdminLanguagesController extends AdminLanguagesControllerCore
{
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:35
    * version: 3.7.3.2
    */
    public function processAdd()
	{
		$ret = parent::processAdd();
		if(!Module::isInstalled('agilemultipleseller'))return $ret;
		ObjectModel::cleear_unnecessary_lang_data();
		return $ret;
	}
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:35
    * version: 3.7.3.2
    */
    public function processUpdate()
	{
		$ret = parent::processUpdate();
		if(!Module::isInstalled('agilemultipleseller'))return $ret;
		ObjectModel::cleear_unnecessary_lang_data();
		return $ret;
	}
}