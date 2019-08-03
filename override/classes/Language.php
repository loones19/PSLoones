<?php
class Language extends LanguageCore
{
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:24
    * version: 3.7.3.2
    */
    public static function checkAndAddLanguage($iso_code, $lang_pack = false, $only_add = false, $params_lang = null)
	{
		$ret = parent::checkAndAddLanguage($iso_code, $lang_pack = false, $only_add = false, $params_lang = null);
		if(!Module::isInstalled('agilemultipleseller'))return $ret;
		ObjectModel::cleear_unnecessary_lang_data();
		return $ret;
	}
}
