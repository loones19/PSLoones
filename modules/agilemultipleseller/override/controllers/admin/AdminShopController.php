<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.

class AdminShopController extends AdminShopControllerCore
{
	public function viewAccess($disable = false)
	{
		if(Module::isInstalled('agilemultipleshop'))return true;
		return parent::viewAccess($disable);
	}
	
	public function renderForm()
	{
		$scripts_4_hide = '';
		if(Module::isInstalled('agilemultipleshop'))$scripts_4_hide = $this->hide_shop_defaultsetting();
		return parent::renderForm() . $scripts_4_hide ;
	}
	
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

