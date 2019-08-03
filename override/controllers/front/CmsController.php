<?php
class CmsController extends CmsControllerCore
{
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:41
    * version: 3.7.3.2
    */
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
