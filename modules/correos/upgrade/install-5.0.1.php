<?php
/**
* 2015-2016 YDRAL.COM
*
* NOTICE OF LICENSE
*
*  @author    YDRAL.COM <info@ydral.com>
*  @copyright 2015-2016 YDRAL.COM
*  @license   GNU General Public License version 2
*
* You can not resell or redistribute this software.
*/

if (!defined('_PS_VERSION_')) {
    exit;
}
function upgrade_module_5_0_1($object)
{
    $db = Db::getInstance();

    if ($db->executeS("SHOW TABLES LIKE '"._DB_PREFIX_."correos_carrier'")) {
        $id_referenceS0178 = Db::getInstance()->getValue(
            "SELECT `id_reference` FROM `"._DB_PREFIX_."correos_carrier` 
            WHERE `code` = 'S0177'"
        );
        if ($id_referenceS0178) {
            $db->Execute("UPDATE `"._DB_PREFIX_."correos_carrier` SET `id_reference` = ".$id_referenceS0178." ".
            " WHERE `code` = 'S0178'");
        }
    
        $id_referenceS0176 = Db::getInstance()->getValue(
            "SELECT `id_reference` FROM `"._DB_PREFIX_."correos_carrier` 
            WHERE `code` = 'S0175'"
        );
        if ($id_referenceS0176) {
            $db->Execute("UPDATE `"._DB_PREFIX_."correos_carrier` SET `id_reference` = ".$id_referenceS0176." ".
            " WHERE `code` = 'S0176'");
        }
            
        $db->Execute("UPDATE `"._DB_PREFIX_."correos_carrier` SET `title` = 'Paq Premium Oficina', ".
            "`delay` = 'Envíos en 1-2 días a la oficina que tú elijas' WHERE `code` = 'S0236'");
        $db->Execute("UPDATE `"._DB_PREFIX_."correos_carrier` SET `title` = 'Paq Estándar Oficina', ".
            "`delay` = 'Envíos en 2-3 días a la oficina que tú elijas' WHERE `code` = 'S0133'");
        $db->Execute("UPDATE `"._DB_PREFIX_."correos_carrier` SET `title` = 'Paq Premium Domicilio', ".
            "`delay` = 'Entrega a domicilio en 1-2 días' WHERE `code` = 'S0235'");
        $db->Execute("UPDATE `"._DB_PREFIX_."correos_carrier` SET `title` = 'Paq Estándar Domicilio', ".
            "`delay` = 'Entrega a domicilio en 2-3 días' WHERE `code` = 'S0132'");
        $db->Execute("UPDATE `"._DB_PREFIX_."correos_carrier` SET `title` = 'Paq Premium CityPaq', ".
            "`delay` = 'Envíos en 1-2 días al terminal que tú elijas' WHERE `code` = 'S0176'");
        
        $db->Execute("UPDATE `"._DB_PREFIX_."correos_carrier` SET `title` = 'Paq Estándar CityPaq', ".
            "`delay` = 'Envíos en 2-3 días al terminal que tú elijas' WHERE `code` = 'S0178'");
        $db->Execute("UPDATE `"._DB_PREFIX_."correos_carrier` SET `title` = 'Paq Standard Internacional', ".
            "`code` = 'S0410', `delay` = 'Envíos Preferentes a domicilio' WHERE `code` = 'S0030'");
        $db->Execute("UPDATE `"._DB_PREFIX_."correos_carrier` SET `title` = 'Paq Light Internacional', ".
            "`delay` = 'Envíos a domicilio con seguimiento, sin firma' WHERE `code` = 'S0360'");
            
        $db->Execute("DELETE FROM `"._DB_PREFIX_."correos_carrier` WHERE `code` = 'S0177'");
        $db->Execute("DELETE FROM `"._DB_PREFIX_."correos_carrier` WHERE `code` = 'S0175'");

    }

    if ($db->executeS("SHOW TABLES LIKE '"._DB_PREFIX_."correos_preregister'")) {
          $db->Execute("ALTER TABLE `"._DB_PREFIX_."correos_preregister` CHANGE `shipment_code` `shipment_code` VARCHAR(250) ".
          "CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL");
    }
    if ($db->executeS("SHOW TABLES LIKE '"._DB_PREFIX_."correos_collection'")) {
        $db->Execute("ALTER TABLE `"._DB_PREFIX_."correos_collection` ADD `status` VARCHAR(15) NOT NULL DEFAULT 'Solicitada' AFTER `date_requested`");
    }

    $db->Execute("CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."correos_rma` (".
            "`id_order` int(10) UNSIGNED NOT NULL,".
            "`shipment_code` varchar(250) DEFAULT NULL,".
            "`require_customs` tinyint(4) NOT NULL DEFAULT '0',".
            "`date_response` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP".
            ") ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8");
                    
    if ($id_order_return_state = (int)Configuration::get('CORREOS_ORDER_STATE_RETURN_ID') ) {
        if ($order_return_state = new OrderReturnState((int)$id_order_return_state)) {
            $order_return_state->delete();
        }
    }
    if (file_exists(_PS_MODULE_DIR_.'/correos/override/')) {
        Tools::deleteDirectory(_PS_MODULE_DIR_.'/correos/override/');
    }


    $source = _PS_MODULE_DIR_.'/correos/public/override/controllers/admin/AdminReturnController.php';
    $dest = _PS_ROOT_DIR_.'/override/controllers/admin/AdminReturnController.php';
    if (file_exists($dest)) {
        //check if existing override is from Correos
        if (preg_match("/correos/", Tools::file_get_contents($source)) > 0) {
            if (@copy($source, $dest)) {
                $path_cache_file = _PS_ROOT_DIR_.'/cache/class_index.php';
                if (file_exists($path_cache_file)) {
                     unlink($path_cache_file);
                }
            }
        }
    } else {
        if (@copy($source, $dest)) {
            $path_cache_file = _PS_ROOT_DIR_.'/cache/class_index.php';
            if (file_exists($path_cache_file)) {
                unlink($path_cache_file);
            }
        }
    }

    return true;
}
