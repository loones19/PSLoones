<?php
class AdminModulesController extends AdminModulesControllerCore
{
	/*
    * module: agilekernel
    * date: 2019-06-24 19:34:24
    * version: 1.7.1.5
    */
    public function ajaxProcessUpdateAgileModule()
	{
		$errors = AgileInstaller::update_module(Tools::getValue('m_to_update'), Tools::getValue('v_to_update'), Tools::getValue('u_to_update'));
		die(Tools::jsonEncode(array(
			'status' => empty($errors) ? 'success': 'failed'
			,'messages' => $errors
			)));
	}
}
