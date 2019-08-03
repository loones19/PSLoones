<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.

class CmsController extends CmsControllerCore
{
	public function __construct()
	{
		parent::__construct();
		if(Module::isInstalled('agilemultipleseller'))
		{
			$seller_terms_id = (int)Tools::getValue('id_cms');
			if($seller_terms_id == (int)Configuration::get('AGILE_MS_SELLER_TERMS'))
			{
				$seller_terms = new CMS($seller_terms_id, $this->context->language->id);
				if (Configuration::get('PS_SSL_ENABLED') && Tools::getValue('content_only') &&  Validate::isLoadedObject($seller_terms))
					$this->ssl = true;		
			}
		}
	}
}

