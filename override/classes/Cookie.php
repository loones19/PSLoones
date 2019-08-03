<?php
class Cookie extends CookieCore
{
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:21
    * version: 3.7.3.2
    */
    protected function _setcookie($cookie = null)
	{
		if(Module::isInstalled('agilemultipleshop'))
		{
			$_SESSION[$this->_name] = $this->_cipherTool->encrypt($cookie);
		}
		return parent::_setcookie($cookie);
	}
	
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:21
    * version: 3.7.3.2
    */
    public function update($nullValues = false)
	{
		if(Module::isInstalled('agilemultipleshop'))
		{
			if(isset($_SESSION[$this->_name]))
			{
				$_COOKIE[$this->_name] = $_SESSION[$this->_name];
			}
		}		
		parent::update($nullValues);
	}	
	
}
