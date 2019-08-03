<?php
class AdminThemesController extends AdminThemesControllerCore
{
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:41
    * version: 3.7.3.2
    */
    private function logo_name_for_theme($logo_name, $theme_name)
	{
		$pathinfo = pathinfo($logo_name);
		return $pathinfo['filename'] . '_' . $theme_name . '.' .  $pathinfo['extension'];
	}
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:41
    * version: 3.7.3.2
    */
    private function get_file_name($filename)
	{
		$pathinfo = pathinfo($filename);	
		return $pathinfo['filename'];
	}
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:41
    * version: 3.7.3.2
    */
    protected function updateLogo($field_name, $logo_prefix)
	{
				parent::updateLogo($field_name, $logo_prefix);
				$default_shop = new Shop((int)Configuration::get("PS_SHOP_DEFAULT"));
				$logo_name =  Configuration::get($field_name);
		if(empty($logo_name))return;
		$old_logo_name_theme = Configuration::get($field_name . "_"  . $default_shop->theme_name);
		if(!empty($old_logo_name_theme) && strpos($this->get_file_name($old_logo_name_theme), $this->get_file_name($logo_name)) !==false)return;
				$logo_name_theme = $this->logo_name_for_theme($logo_name, $default_shop->theme_theme);
		copy(_PS_IMG_DIR_ . $logo_name, _PS_IMG_DIR_ . $logo_name_theme);
				Configuration::updateValue($field_name . "_"  . $default_shop->theme_name, $logo_name_theme);
		if(!empty($old_logo_name_theme) && file_exists(_PS_IMG_DIR_ . $old_logo_name_theme))
			unlink(_PS_IMG_DIR_ . $old_logo_name_theme);		
		if(!empty($logo_name_theme) && file_exists(_PS_IMG_DIR_ . $logo_name_theme))
		{
			list($width, $height, $type, $attr) = getimagesize(_PS_IMG_DIR_. $logo_name_theme);
			if($field_name == "PS_LOGO")
			{
				Configuration::updateValue('SHOP_LOGO_HEIGHT' .  '_' . $default_shop->theme_name, (int)round($height));
				Configuration::updateValue('SHOP_LOGO_WIDTH'.  '_' . $default_shop->theme_name, (int)round($width));		
			}
			if($field_name == "PS_LOGO_MOBILE")
			{
				Configuration::updateValue('SHOP_LOGO_MOBILE_HEIGHT' .  '_' . $default_shop->theme_name, (int)round($height));
				Configuration::updateValue('SHOP_LOGO_MOBILE_WIDTH'.  '_' . $default_shop->theme_name, (int)round($width));		
			}				
		}
	}
}
