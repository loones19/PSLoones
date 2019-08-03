<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
class Cookie extends CookieCore
{
	protected function _setcookie($cookie = null)
	{
		if(Module::isInstalled('agilemultipleshop'))
		{
			$_SESSION[$this->_name] = $this->_cipherTool->encrypt($cookie);
		}
		return parent::_setcookie($cookie);
	}
	
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
