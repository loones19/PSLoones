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
 *  @author     Loones https://www.loones.es/
 *  @copyright  2019 https://www.loones.es/
 *  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */
include_once(_PS_ROOT_DIR_ .'/modules/aa_loones/classes/aa_feature.php');
class AdminaaFeaturesController extends ModuleAdminController {
    
    public function __construct() {
                
        $this->table = 'feature_loones';
        $this->identifier = 'id_aa_feature';
        $this->module='aa_loones';
        $this->className = 'aaFeature';
        //$this->name = 'ca_Proveedor';
        $this->bootstrap = true;
        $this->lang = false;

        parent::__construct();
        // $this->addRowAction('view');
        $this->addRowAction('edit');
        $this->addRowAction('delete');

        $this->_select = "cat.`name` as name_cat, feat.`name` as name_feat";
        $this->_join =  'LEFT JOIN `'._DB_PREFIX_.'category_lang` cat ON (cat.`id_category` = a.`id_category` AND cat.`id_lang` = 1) ';
        $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'feature_lang` feat ON (feat.`id_feature` = a.`id_feature`AND feat.`id_lang` = 1) ';
        $this->_defaultOrderBy = 'id_feature';
        
        $this->fields_list = array (
            'id_feature' => array (
                'title' => $this->l('ID Característica'),
                'align' => 'center',
                'width' => 50
            ),

            'name_feat' => array (
                'title' => $this->l('Característica'),
                'align' => 'center',
                'filter_key' => 'feat!name',
                'width' => 50,

            ),
            'id_category' => array (
                'title' => $this->l('ID Categoría'),
                'align' => 'center',
                'width' => 50
            ),
            'name_cat' => array (
                'title' => $this->l('Nombre Categoría'),
                'align' => 'center',
                'filter_key'=> 'cat!name',
                'width' => 50
            ),
        );

        $this->context = Context::getContext();
        $this->context->controller = $this;

        $this->fields_form = array (
            'legend'=> array (
                'title' => $this->l('Añadir / Modificar'),
                'image' => '../img/admin/contact.gif'
            ),
            'input' => array (
                array (
                    'type'      => 'select',
                    'label'      => $this->l('Característica'),
                    'name'      => 'id_feature',
                    'required'  => true,
                    'default_value' => 1,
                    'options' => array (
 //                       'query'=> Feature::getFeatures($this->id_language),
                        'query' => Feature::getFeatures($this->context->cookie->id_lang,1,1000,'name', 'ASC'),
                        'id' => 'id_feature',
                        'name' => 'name'
                    )
                ),

                array (
                    'type'      => 'select',
                    'label'     => $this->l('Categoría'),
                    'name'      => 'id_category',
                    'required'  => true,
                    'default_value'=>1,
                    'options' => array (
                        'query' => aaFeature::categoriesShow($this->context->cookie->id_lang),
                        'id' => 'id_category',
                        'name' => 'name'
                    )

                ),

             

            ),
            'submit' => array('title' => $this->l('Guardar'))

        );
    
    }

    
    
}