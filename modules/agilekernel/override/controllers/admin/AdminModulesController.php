<?php
///-build_id: 2018051409.414
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
class AdminModulesController extends AdminModulesControllerCore
{
	public function ajaxProcessUpdateAgileModule()
	{
		$errors = AgileInstaller::update_module(Tools::getValue('m_to_update'), Tools::getValue('v_to_update'), Tools::getValue('u_to_update'));

		die(Tools::jsonEncode(array(
			'status' => empty($errors) ? 'success': 'failed'
			,'messages' => $errors
			)));
	}
}
