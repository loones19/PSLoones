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
	
	public function init(){
		parent::init();
		$id_cms = (int)Tools::getValue('id_cms');
		if($id_cms > 0 && strpos($this->cms->content, "{seller_event_list}") !== false){
			include_once(_PS_MODULE_DIR_.'agilemultipleseller/SellerEvent.php');
			include_once(_PS_ROOT_DIR_ .'/modules/agilemultipleseller/SellerInfo.php');
									$content = '';
			$events = SellerEvent::getEventList($this->context->language->id);
			$page_url = CMS::getLinks($this->context->language->id,array($this->cms->id));
			foreach ($events as $k=>$v){
				$v['logo'] = SellerInfo::get_seller_logo_url_static($v['id_sellerinfo']);
				$v['url'] = @$page_url[0]['link'];
				$content .= $this->_eventBlock($v);
							}
			$this->cms->content = str_replace("{seller_event_list}", $content, $this->cms->content);
		}
					}

	private function _eventBlock($data){
		$this->context->smarty->assign(array(
			'logo' => $data['logo'],
			'content' => $data['description'],
			'title' => $data['title'],
			'place' => $data['place'],
			'start_date' => $data['start_date'],
			'end_date' => $data['end_date'],
			'href' => $data['url'].'?event='.$data['id_event'],
			'create_date' => $data['create_date'],
			'company' => $data['company'],
			'pdffile_url' => SellerEvent::get_event_pdffile_url_static($data['id_event'])
		));
	
		return $this->context->smarty->fetch(_PS_MODULE_DIR_.'agilemultipleseller/views/templates/front/event_item.tpl');
	}

	private function _getFullEventContent($data){	
		$this->context->smarty->assign(array(
			'logo' => $data['logo'],
			'content' => $data['description'],
			'title' => $data['title'],
			'place' => $data['place'],
			'start_date' => $data['start_date'],
			'end_date' => $data['end_date'],
			'create_date' => $data['create_date'],
			'company' => $data['company']
		));
	
		return $this->context->smarty->fetch(_PS_MODULE_DIR_.'agilemultipleseller/views/templates/front/event.tpl');
	}

	private function _debug($var,$exit = false){
		echo "<pre>";
		var_dump($var);
		echo "</pre>";
		if($exit) exit;
	}	
}
