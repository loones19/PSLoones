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

class aaFeature extends ObjectModel {
    public $id_aa_feature;
    public $id_feature;
    public $id_category;
    public $date_add;

    public static $definition= array (
        'table' => 'feature_loones',
        'primary' => 'id_aa_feature',
        'multilang' => false,
        'fields' => array (
            'id_feature' => array (
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedId'
            ),
            'id_category' => array (
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedId'
            ),
             'date_add' => array(
                'type' => self::TYPE_DATE,
                'validate' => 'isDate',
                'copy_post' =>false
            )
        )

    );

    public static function isValidated($id_feature,$id_category) {

        $sql= 'SELECT * FROM '. _DB_PREFIX_ .'feature_loones WHERE id_feature='.$id_feature.' AND id_category='.$id_category;
       
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($sql);

        if (!$result)
            return false;
        else
            return true;

    }

    public static function categoriesShow ($idlang) {
        $categories= Category::getCategories($idlang);
        unset($categories[0]);
        unset($categories[1]);
        $resultado=array();
        foreach ($categories as $key => $arraySup) {
            foreach($arraySup as $keyCat => $category) {

                array_push($resultado,array(
                    'id_category' => $category['infos']['id_category'],
                    'name'=> $category['infos']['name'],
                    'id_parent'=> $category['infos']['id_parent'],
                ));
           //     $resultado[$keyCat]['id_category']=$category['infos']['id_category'];
             //   $resultado[$keyCat]['name']=$category['infos']['name'];

            } 
        }

        $categoriesH = AgileHelper::getSortedFullnameCategoryLoones($resultado);


        return $categoriesH;




    }
    public static function getTransportData($op)
	{
        

        $sql = 'SELECT * FROM '._DB_PREFIX_.'loones_trans_op'.$op;
        
        

		return Db::getInstance()->executeS($sql);
	}

}