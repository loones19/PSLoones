<?php
class AdminCartsController extends AdminCartsControllerCore
{
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:33
    * version: 3.7.3.2
    */
    public function viewAccess($disable = false)
	{
				if($this->is_seller)return true;
		return parent::viewAccess($disable);
	}
}
