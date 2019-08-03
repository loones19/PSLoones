<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
class CategoryController extends CategoryControllerCore
{
	public function init()
	{
		parent::init();
		if(Module::isInstalled('agilesellerratings'))
		{
			include_once(_PS_ROOT_DIR_ . "/modules/agilesellerratings/agilesellerratings.php");
			$this->context->smarty->assign(array(
				'cate_seller_ratting' => AgileSellerRatings::getAverageRating4Category($this->category->id)
			));			
		}
	}

}
