<?php 
/**
 * 2018 Loones
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author Loones https://www.loones.es/
 *  @copyright  2019 https://www.loones.es/
 *  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once _PS_MODULE_DIR_ . 'aa_loones/classes/aa_feature.php';





class aa_Loones extends Module
{
    public function __construct() 
    {
        $this->name='aa_loones';

        $this->tab= 'front_office_features';
        $this->version='0.1';
        $this->author='Loones';
        $this->need_instance = 0; 
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);

        $this->bootstrap = true;

        parent::__construct();
        $this->displayName = $this->l('Loones Marketplace'); 
        $this->description = $this->l('Este módulo gestiona el marketplace de Loones.es'); 
        $this->confirmUninstall = $this->l('¿Estás seguro de que deseas desinstalar?'); /* This is a popup message before the uninstalling of the module */
    }
    
    public function processConfiguration() {
        if (Tools::isSubmit('aa_config_form')) {
            $var1 = Tools::getValue('variable1');
            $var2 = Tools::getValue('variable2');
            $email1 = Tools::getValue('email1');
            $email2 = Tools::getValue('email2');
            $email3 = Tools::getValue('email3');
            Configuration::updateValue('AA_LOONES_VAR1',$var1);

            Configuration::updateValue('AA_LOONES_EMAIL1',$email1);
            Configuration::updateValue('AA_LOONES_EMAIL2',$email2);
            Configuration::updateValue('AA_LOONES_EMAIL3',$email3);
            $this->context->smarty->assign('confirmation', 'ok');
        }

    }
    
    public function renderForm(){

        $fields_form	=	
            array(
                'form'	=>	
                    array(
                        'legend'	=>	
                            array(
                                'title'	=>	$this->l('LONNES Configuración'),
                                'icon'	=>	'icon-envelope'				
                            ),
                        'input'	=>	
                            array(
                                array(	
                                    'type'	    =>	'switch',
                                    'label'	    =>	$this->l('Entorno Produccion:'),
                                    'name'	    =>	'variable1',
                                    'desc'	    =>	$this->l('Habilitar entorno produccion.'),
                                    'values'	=>	
                                        array(
                                            array(
                                                'id'	=>	'variable1_1',
                                                'value'	=>	1,
                                                'label'	=>	$this->l('Enabled')
                                            ),
                                            array(
                                                'id'	=>	'variable1_0',
                                                'value'	=>	0,
                                                'label'	=>	$this->l('Disabled')
                                            )
                                        ),
                                ),

                                array (
                                    'type'  =>  'text',
                                    'label' =>  $this->l('Correo Envío Pedidos'),
                                    'name'  => 'email1',
                                    'desc'  =>  'Cada vez que se relialize un solicitud se enviará un correo de aviso'
                                ),
                                array (
                                    'type'  =>  'text',
                                    'label' =>  $this->l('Correo Envío alertas seguro'),
                                    'name'  => 'email2',
                                    'desc'  =>  'Cada vez que se realize un solicitud de seguro'
                                ),
                                array (
                                    'type'  =>  'text',
                                    'label' =>  $this->l('Correo envío Transacciones bancarias'),
                                    'name'  => 'email3',
                                    'desc'  =>  'Solicitudes Bancarias'
                                )
                            ),
                        'submit'	=>	
                            array(
                                'title'	=>	$this->l('Guardar'),
                            )
                    ) 
            );



        $helper	=	new	HelperForm();
        $helper->table= 'aa_loones';
        $helper->default_form_language= (int)Configuration::get('PS_LNG_DEFAULT');
        $helper->allow_employee_form_lang = (int)Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG');
        $helper->submit_action	=	'aa_config_form';
        $helper->currentIndex	=	$this->context->link->getAdminLink('AdminModules',true).
                                    '&configure='.$this->name.
                                    '&tab_module='.$this->tab.
                                    '&module_name='.$this->name;
        $helper->token	=	Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars	=	array(		
            'fields_value'	=>	array(				
                'variable1' =>	Tools::getValue('variable1', Configuration::get('AA_LOONES_VAR1')),
                
                'email1'	=>	Tools::getValue('email1', Configuration::get('AA_LOONES_EMAIL1')),
                'email2'	=>	Tools::getValue('email2', Configuration::get('AA_LOONES_EMAIL2')),
                'email3'	=>	Tools::getValue('email3', Configuration::get('AA_LOONES_EMAIL3'))
            ),
            'languages'	    =>	$this->context->controller->getLanguages() 
            ); 

        return	$helper->generateForm(array($fields_form)); 
    }
    
    public function getContent()    // Gestión de la configuración del módulo 
    {
        $this->processConfiguration();
        $html_confirmation_message	=	$this->display(__FILE__,	'getContent.tpl');
        $html_form = $this->renderForm();
        return	$html_confirmation_message.$html_form;
    }
    
    
    public function installTab($parent, $class_name, $name) {

        // creamos la nueva entrada en el menu del administrador
        $tab= new Tab();
        $tab->id_parent= (int)Tab::getIdFromClassName($parent);
        $tab->name= array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']]=$name;
        }
        $tab->class_name=$class_name;
        $tab->module=$this->name;
        $tab->active=1;
        return $tab->add();

    }
  
    

    public function install()
    {
        
        if  (parent::install()) {
           // if (!$this->registerHook('displayOrderDetail'))
              //  return false;
            /*

            if (!$this->registerHook('actionAuthenticationBefore'))
                return false;
            */
                /*
            $this->uninstallTab('AdminProveedor');
            $this->uninstallTab('AdminAsignacion');
            $this->uninstallTab('AdminServicio');
            $this->uninstallTab('AdminHorario');
            $this->uninstallTab('AdminPedido');
            */


            if (!$this->installTab('AdminCatalog', 'AdminaaFeatures','Loones CAT CAR')){
                return false;
            }
            return true;
        }
        return false;
    }

    public function uninstallTab($class_name)
    {
        
        $id_tab = (int)Tab::getIdFromClassName($class_name);
        $tab = new Tab((int)$id_tab);
        return $tab->delete();
    }

    public function uninstall()
    {
        if (!$this->uninstallTab('AdminaaFeatures'))
            return false;

        
        return (parent::uninstall());
    }
}
