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

ini_set('default_charset', 'utf-8');
require_once(realpath(dirname(__FILE__).'/../../config/config.inc.php'));
//require_once(realpath(dirname(__FILE__).'/../../init.php'));
if (Tools::substr(Tools::encrypt('correos/index'), 0, 10) != Tools::getValue('correos_token') ||
    !Module::isInstalled('correos')) {
    die('Bad token');
}
require_once(_PS_MODULE_DIR_.'correos/correos.php');

if ((int) Tools::getValue('order') && Tools::stripslashes(Tools::getValue('codenv'))) {
    
    $pdf = "../correos/pdftmp/" . Tools::strtolower(Tools::getValue('codenv')) . ".pdf";

    if(Tools::getValue('id_preregister')) {
        //change name of previous versions
        $pdf_old_name = "../correos/pdftmp/" . (int) Tools::getValue('order') . "_" . (int) Tools::getValue('id_preregister') . ".pdf";
        if (file_exists($pdf_old_name)) {
             rename($pdf_old_name,$pdf);
        }
    }
    

    if (!file_exists($pdf)) {
        $dataPdf = CorreosAdmin::getLabelRemote(Tools::getValue('codenv'));
        file_put_contents($pdf, base64_decode($dataPdf[0]->Etiqueta->Etiqueta_pdf->Fichero));
    }

    Db::getInstance()->Execute(
        "UPDATE `"._DB_PREFIX_."correos_preregister` SET 
        `label_printed` = CURRENT_TIMESTAMP 
        WHERE `id_order` = " . (int) Tools::getValue('order'));


    Tools::redirect(
        Context::getContext()->shop->getBaseURL().'modules/correos/pdftmp/'.
        Tools::strtolower(Tools::getValue('codenv')). '.pdf'
    );
}
