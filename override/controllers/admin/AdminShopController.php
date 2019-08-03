<?php
class AdminShopController extends AdminShopControllerCore
{
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:38
    * version: 3.7.3.2
    */
    public function viewAccess($disable = false)
	{
		if(Module::isInstalled('agilemultipleshop'))return true;
		return parent::viewAccess($disable);
	}
	
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:38
    * version: 3.7.3.2
    */
    public function renderForm()
	{
		$scripts_4_hide = '';
		if(Module::isInstalled('agilemultipleshop'))$scripts_4_hide = $this->hide_shop_defaultsetting();
		return parent::renderForm() . $scripts_4_hide ;
	}
	
	/*
    * module: agilemultipleseller
    * date: 2019-06-24 19:40:38
    * version: 3.7.3.2
    */
    private function hide_shop_defaultsetting()
	{
		return '
			<script type="text/javascript">			
			$(document).ready(function(){
				$("#categories-tree").parent().parent().hide();
				$("#categories-tree").parent().parent().prev().hide();
				$("#id_category").parent().hide();
				$("#id_category").parent().prev().hide();
				$("#id_shop_group").parent().hide();
				$("#id_shop_group").parent().prev().hide();
				$(".category-filter").parent().hide();
				$(".category-filter").parent().prev().hide();
			});
			</script>
			';
	}
	
}
