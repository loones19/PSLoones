<?php
class CategoryController extends CategoryControllerCore
{
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:43
    * version: 3.7.3.2
    */
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
