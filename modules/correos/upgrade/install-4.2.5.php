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
function upgrade_module_4_2_5($object)
{
    $db = Db::getInstance();
    if ($db->executeS("SHOW TABLES LIKE '"._DB_PREFIX_."correos_configuration'")) {
       $db ->Execute(
            "INSERT INTO `"._DB_PREFIX_."correos_configuration` 
            (`value`, `name`) 
            VALUES ('https://serviciorecogidas.correos.es/serviciorecogidas', 'url_collection') 
            ON DUPLICATE KEY UPDATE `value` = 'https://serviciorecogidas.correos.es/serviciorecogidas'"
        );
    }
    return true;
}
