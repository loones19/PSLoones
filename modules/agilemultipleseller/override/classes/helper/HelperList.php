<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.

class HelperList extends HelperListCore
{
	public function displayEnableLink($token, $id, $value, $active, $id_category = null, $id_product = null, $ajax = false)
	{
				if(Module::isInstalled('agilemultipleseller'))
		{
			if($this->context->controller->is_seller AND in_array($this->context->controller->table,array('customer','address')))return;
		}
		return parent::displayEnableLink($token, $id, $value, $active, $id_category, $id_product, $ajax);
	}

	public function displayViewLink($token = null, $id, $name = null)
	{
		$link = new Link();
		
		if (!array_key_exists('View', self::$cache_lang))
			self::$cache_lang['View'] = $this->l('View', 'Helper');

				if(Module::isInstalled('agilenewsletters') AND in_array($this->context->controller->table,array('agile_mail_history')))
		{
			return '<a href="' . $link->getModuleLink('agilenewsletters', 'newsletterdetail', array('nid'=>$id), true) . '" target="_new"><img src="../img/admin/details.gif" alt="'.self::$cache_lang['View'].'" title="'.self::$cache_lang['View'].'" /></a>';
		}

		return parent::displayViewLink($token, $id);
	}

	public function displayListFooter()
	{
		$summary_row = '';
		if(in_array($this->table,array('seller_commission')))
		{
			$summary_row = $this->context->controller->getSummaryRow();
		}
		return $summary_row .
		parent::displayListFooter();
	}
}

