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
class CorreosAdmin
{
    public static function createOrderState()
    {
        $order_state = new OrderState();
        $order_state->name = array();
        foreach (Language::getLanguages() as $language) {
            $order_state->name[$language['id_lang']] = 'Etiquetado Correos';
        }
        $order_state->send_email = false;
        $order_state->color = '#ffff00';
        $order_state->module_name = 'correos';
        $order_state->hidden = true;
        $order_state->delivery = false;
        $order_state->logable = false;
        $order_state->invoice = false;

        if ($order_state->add()) {
            $source = _PS_MODULE_DIR_.'/correos/views/img/logo_state.gif';
            $destination = _PS_IMG_DIR_.'/os/'.(int)$order_state->id.'.gif';
            copy($source, $destination);
        }
        //Adding "Shipped" state (ID: 4) as default configuration
        self::updateCorreosConfig(array( 'order_states' =>  Tools::jsonEncode(array((int)$order_state->id, 4))));

        return true;
    }
    public static function createReturnState()
    {
        $languages = Language::getLanguages(false);
        $order_return_state = new OrderReturnState();
        $order_return_state->color = "#ffff00";
        $order_return_state->name = array();
        foreach ($languages as $language) {
            $order_return_state->name[$language['id_lang']] = "Solocitar recogida Correos";
        }
        $order_return_state->save();
        Configuration::updateValue('CORREOS_ORDER_STATE_RETURN_ID', (int) $order_return_state->id);

        return true;
    }
    public static function updateCorreosConfig($params)
    {
        foreach ($params as $conf_key => $value) {
            if (strstr(Tools::strtoupper($conf_key), 'CORREOS_')) {
                Configuration::updateValue(Tools::strtoupper($conf_key), $value);
            } else {
                Configuration::updateValue('CORREOS_' . Tools::strtoupper($conf_key), $value);
            }
        }
    }
    public static function installExternalCarrier($config)
    {

        $correos = new Correos();
        $carrier = new Carrier();
        $carrier->name = $config['name'];
        $carrier->id_tax = 1;
        $carrier->id_zone = 1;
        $carrier->active = 0;
        $carrier->deleted = 0;
        $carrier->delay = $config['delay'];
        $carrier->shipping_handling = false;
        $carrier->range_behavior = 0;
        $carrier->is_module = 0;
        $carrier->shipping_external = 0;
        $carrier->external_module_name = $correos->name;
        $carrier->need_range = true;
        $carrier->max_weight = $config['correos_carrier_code'] == 'S0360' ? 2 : 30;
        $carrier->url = 'http://www.correos.es/comun/localizador/track.asp?numero=@';
        if (version_compare(_PS_VERSION_, '1.7', '>')) {
            $carrier->is_module = 1;
        }
        $languages = Language::getLanguages(true);
        foreach ($languages as $language) {
            $carrier->delay[(int)$language['id_lang']] = $config['delay'][$language['iso_code']];
        }

        if ($carrier->add()) {
            $groups = Group::getGroups(true);
            foreach ($groups as $group) {
                Db::getInstance()->Execute(
                    'INSERT INTO `'._DB_PREFIX_.'carrier_group` 
                    VALUES (\''.(int)$carrier->id.'\',\''.(int)$group['id_group'].'\')'
                );
            }

            /*Weight range. Default 0-10000*/
            $rangePrice = new RangePrice();
            $rangePrice->id_carrier = (int) $carrier->id;
            $rangePrice->delimiter1 = '0';
            $rangePrice->delimiter2 = '10000';
            $rangePrice->add();

            /*Price range. Default 0-1000*/
            $rangeWeight = new RangeWeight();
            $rangeWeight->id_carrier = (int) $carrier->id;
            $rangeWeight->delimiter1 = '0';
            $rangeWeight->delimiter2 = $config['correos_carrier_code'] == 'S0360' ? '2' : '1000';
            $rangeWeight->add();

            $source = _PS_MODULE_DIR_.'correos/views/img/logo_ps16_'.Tools::strtolower($config['correos_carrier_code']).'.jpg';
            if (version_compare(_PS_VERSION_, '1.7', '>')) {
                 $source = _PS_MODULE_DIR_.'correos/views/img/logo_ps17_generic.jpg';
            }
            $destination = _PS_SHIP_IMG_DIR_.'/'.(int) $carrier->id.'.jpg';
            copy($source, $destination);
            
            /*Update table correos_carrier*/
            Db::getInstance()->update(
                'correos_carrier',
                array('id_reference' => (int) $carrier->id),
                "code = '".pSQL($config['correos_carrier_code'])."'"
            );
            return true;
        } else {
            return false;
        }
    }
    public static function getOrders()
    {
        $context = Context::getContext();
        if (Tools::isSubmit('form-search_shipping_action') ||
            Tools::isSubmit('form-search_shipping_filter') ||
            Tools::isSubmit('form-search_shipping_reset') ||
            Tools::getValue('order_rows') ||
            Tools::getValue('order_page')) {
            $context->smarty->assign('CURRENT_FORM', 'search_shipping');
        }

        $where = "";
        $where_collection = "";
        if (Tools::isSubmit('form-search_shipping_filter')) {
            if (Tools::getValue('orderFilter_id_order')) {
                $where .= " AND o.`id_order` = " . (int) Tools::getValue('orderFilter_id_order');
            }
            if (Tools::getValue('orderFilter_customer')) {
                $where .= " AND c.`firstname` LIKE '%" . pSQL(Tools::getValue('orderFilter_customer')) . "%'";
            }
            if (Tools::getIsset('orderFilter_exported') && Tools::getValue('orderFilter_exported') == '1') {
                $where .= " AND cp.`exported` IS NOT NULL ";
            }
            if (Tools::getIsset('orderFilter_exported') && Tools::getValue('orderFilter_exported') == '0') {
                $where .= " AND cp.`exported` IS NULL ";
            }
            if (Tools::getIsset('orderFilter_printed') && Tools::getValue('orderFilter_printed') == '1') {
                $where .= " AND cp.`label_printed` IS NOT NULL ";
            }
            if (Tools::getIsset('orderFilter_printed') && Tools::getValue('orderFilter_printed') == '0') {
                $where .= " AND cp.`label_printed` IS NULL ";
            }
            if (Tools::getIsset('orderFilter_manifest') && Tools::getValue('orderFilter_manifest') == '1') {
                $where .= " AND cp.`manifest` IS NOT NULL ";
            }
            if (Tools::getIsset('orderFilter_manifest') && Tools::getValue('orderFilter_manifest') == '0') {
                $where .= " AND cp.`manifest` IS NULL ";
            }
            if (Tools::getValue('orderFilter_dateFrom') && Tools::getValue('orderFilter_dateTo')) {
                $where .= " AND (o.`date_add` BETWEEN '" . pSQL(Tools::getValue('orderFilter_dateFrom')) .
                " 00:00:00' AND '" . pSQL(Tools::getValue('orderFilter_dateTo')) . " 23:59:59')";
            }
            if (Tools::getValue('orderFilter_parcel_number')) {
               $where_collection = " WHERE LENGTH(REPLACE(`shipment_code`, ',', '')) = " . (int)Tools::getValue('orderFilter_parcel_number') * 23 .
               " OR LENGTH(REPLACE(`shipment_code`, ',', '')) = " . (int)Tools::getValue('orderFilter_parcel_number') * 13;
            }
            if (Tools::getValue('orderFilter_collected') && Tools::getValue('orderFilter_collected') == '1') {
               $where_collection = " WHERE `collection_date` IS NOT NULL ";
            }
            if (Tools::getValue('orderFilter_collected') && Tools::getValue('orderFilter_collected') == '0') {
                $where_collection = " WHERE `collection_date` IS NULL ";
            }
        }
        $sql = 'SELECT COUNT( * )  FROM  `'._DB_PREFIX_.'orders` o
         INNER JOIN `'._DB_PREFIX_.'correos_preregister` cp ON ( o.`id_order` = cp.`id_order` ) 
         LEFT JOIN `'._DB_PREFIX_.'customer` c ON ( c.`id_customer` = o.`id_customer` ) 
         WHERE o.`id_carrier` IN ( 
         SELECT pc.`id_carrier`
         FROM `'._DB_PREFIX_.'carrier` pc
         INNER JOIN `'._DB_PREFIX_.'correos_carrier` cc ON pc.`id_reference` = cc.`id_reference`)' . $where;

        $total_rows = Db::getInstance()->getValue($sql);
        $order_rows = 50;
        $order_page = 1;

        if (Tools::getValue('order_rows')) {
            $order_rows = (int) Tools::getValue('order_rows');
        }
        if (!Tools::getValue('order_page')) {
            $limit_from = 0;
        } else {
            $limit_from = ((int) Tools::getValue('order_page') - 1) * (int) $order_rows;
            $order_page = (int) Tools::getValue('order_page');
        }
        $total_pages = ceil((int) $total_rows / (int) $order_rows);

        $context->smarty->assign(
            'search_shipping_pagination',
            array(
                'total_pages' => (int) $total_pages,
                'total_rows' => (int) $total_rows,
                'page' => (int) $order_page,
                'order_rows' => (int) $order_rows
            )
        );
        if (Db::getInstance()->executeS("SHOW TABLES LIKE '"._DB_PREFIX_."correos_collection'")) {
            //Is needed te check if table exists, in case user uploaded the module and hasn't update id (PS 1.7)
            $sql = 'SELECT * FROM (SELECT o.`id_order`, c.`firstname`, c.`lastname`, cp.`shipment_code`, cp.`code_expedition`, cp.`label_printed`, o.`reference`, 
            cp.`exported`, cp.`manifest`, o.`date_add`, o.`date_upd`, 
            (SELECT `date_requested` FROM `'._DB_PREFIX_.'correos_collection` cc WHERE cc.`id` = cp.`id_collection` LIMIT 1) AS `collection_date`
            FROM `'._DB_PREFIX_.'orders` o
            INNER JOIN `'._DB_PREFIX_.'correos_preregister` cp ON ( o.`id_order` = cp.`id_order` ) 
            LEFT JOIN `'._DB_PREFIX_.'customer` c ON ( c.`id_customer` = o.`id_customer` ) 
            WHERE o.`id_carrier`
            IN (
            SELECT pc.`id_carrier`
            FROM `'._DB_PREFIX_.'carrier` pc
            INNER JOIN `'._DB_PREFIX_.'correos_carrier` cc ON pc.`id_reference` = cc.`id_reference`
            ) '.
            $where
            . ' ORDER BY o.`date_add` DESC LIMIT '.(int) $limit_from.',' . (int) $order_rows .') AS Tab ' . $where_collection;
        } else {
            $sql = 'SELECT o.`id_order`, c.`firstname`, c.`lastname`, cp.`shipment_code`, cp.`code_expedition`, cp.`label_printed`, o.`reference`, 
            cp.`exported`, cp.`manifest`, o.`date_add`, o.`date_upd`, NULL as `collection_date`
            FROM `'._DB_PREFIX_.'orders` o
            INNER JOIN `'._DB_PREFIX_.'correos_preregister` cp ON ( o.`id_order` = cp.`id_order` ) 
            LEFT JOIN `'._DB_PREFIX_.'customer` c ON ( c.`id_customer` = o.`id_customer` ) 
            WHERE o.`id_carrier`
            IN (
            SELECT pc.`id_carrier`
            FROM `'._DB_PREFIX_.'carrier` pc
            INNER JOIN `'._DB_PREFIX_.'correos_carrier` cc ON pc.`id_reference` = cc.`id_reference`
            ) ' .
            $where
            . ' ORDER BY o.`date_add` DESC LIMIT '.(int) $limit_from.',' . (int) $order_rows;
        }

        return Db::getInstance()->executeS($sql);
    }
    public static function orderPostProcess($order, $address, $preregister_data, $request_data, $correos_config, $carrier_code)
    {
        if (Tools::isSubmit('carrier_change')) {
            self::orderCarrierChange();
            Tools::redirect(isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null);
        }
        if (Tools::isSubmit('requestCustomsDocuments_DDP') && $preregister_data) {
            self::requestCustomsDocuments(
                $address,
                explode(",", $preregister_data['shipment_code']),
                $order->id,
                'ddp'
            );
        }
        if (Tools::isSubmit('requestCustomsDocuments_DCAF') && $preregister_data) {
            self::requestCustomsDocuments(
                $address,
                explode(",", $preregister_data['shipment_code']),
                $order->id,
               'dcaf'
            );
        }
        if (Tools::isSubmit('requestCustomsContent') && $preregister_data) {
            foreach (explode(",", $preregister_data['shipment_code']) as $shipping_code) {
                self::requestCustomsContent($shipping_code);
            }
        }
        if (Tools::isSubmit('exportToFile')) {
            $cart = new Cart($order->id_cart);
            $shipping_val = self::prepareDataFromPost($order, $cart, $carrier_code, $correos_config, $address, $request_data);
                
            self::exportOrderToFileFromPost($order, $carrier_code, $shipping_val);
        }
        if (Tools::isSubmit('preregisterAgain')) {
            $correos = new Correos();
            //delete old labels
            Db::getInstance()->Execute(
                "DELETE FROM `"._DB_PREFIX_."correos_preregister_errors` 
                WHERE `id_order` = ".(int) $order->id
            );
            Db::getInstance()->Execute(
                "DELETE FROM `"._DB_PREFIX_."correos_preregister` 
                WHERE `id_order` = ".(int) $order->id
            );
            if (isset($preregister_data['shipment_code'])) {
               $shipping_code_array = explode(',', $preregister_data['shipment_code']);
               foreach ($shipping_code_array as $shipping_code) {
                   $pdf = "../modules/correos/pdftmp/" . Tools::strtolower($shipping_code) . ".pdf";
                   $customs_pdf = "../modules/correos/pdftmp/customs_" . Tools::strtolower($shipping_code) . ".pdf";
                    if (file_exists($pdf)) {
                        unlink($pdf);
                    }
                    if (file_exists($customs_pdf)) {
                        unlink($customs_pdf);
                    }
               }
            }

            $shipping_val = self::prepareDataFromPost($order, $cart, $carrier_code, $correos_config, $address, $request_data);
            if ($address->address1 != $shipping_val['delivery_address']) {
                //address has changed
                $new_address = new Address();
                foreach ($address as $name => $value) {
                    if (!is_array($value) && !in_array($name, array('date_upd', 'date_add', 'id', 'country', 'firstname', 'lastname'))) {
                        switch ($name) {
                            case 'id_customer':
                                $new_address->id_customer = $order->id_customer;
                                 break;
                            case 'address1':
                                $new_address->address1 = $shipping_val['delivery_address'];
                                break;
                            case 'address2':
                                $new_address->address2 = $shipping_val['delivery_address2'];
                                break;
                            case 'postcode':
                                $new_address->postcode = $shipping_val['delivery_postcode'];
                                break;
                            case 'city':
                                $new_address->city = $shipping_val['delivery_city'];
                                break;
                            case 'alias':
                                $new_address->alias = $address->alias;
                                break;
                            case 'phone_mobile':
                                $new_address->phone_mobile = $shipping_val['mobile'] != '' ?
                                    $shipping_val['mobile'] : $address->phone_mobile;
                                break;
                            case 'phone':
                                $new_address->phone = $shipping_val['phone'] != '' ? $shipping_val['phone'] : $address->phone;;
                                break;
                            case 'deleted':
                                $new_address->deleted = true;
                                break;
                            default:
                                $new_address->$name = $value;
                        }
                    }
                }
                if (in_array($carrier_code, $correos->carriers_codes_office)) {
                    $new_address->firstname = 'Entrega en ';
                    $new_address->lastname = 'la oficina de Correos';
                } else if (in_array($carrier_code, $correos->carriers_codes_citypaq) || in_array($carrier_code, $correos->carriers_codes_homepaq)) {
                    $new_address->firstname = 'Entrega en ';
                    $new_address->lastname = 'terminal CityPaq';
                } else {
                    $new_address->firstname = $shipping_val['customer_firstname'];
                    $new_address->lastname = $shipping_val['customer_lastname1'];
                }
                $new_address->save();
                if ($new_address->id) {
                    $order->id_address_delivery = $new_address->id;
                    $order->update();
                }
                
            }
            $xml = self::prepareXmlOrder($shipping_val, $correos_config, $carrier_code);
            //debugging
            //file_put_contents("../modules/correos/request.xml", $xml);
            if (count($shipping_val['parcel_info']) > 1) {
                $result = CorreosCommon::sendXmlCorreos('url_data', $xml, true, 'PreRegistroMultibulto');
            } else {
                $result = CorreosCommon::sendXmlCorreos('url_data', $xml, true, 'Preregistro');
            }
            //file_put_contents("../modules/correos/response.xml", $result);
            self::preregister($result, $order, $carrier_code, $shipping_val);
            
            Tools::redirect(isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null);
        }
        if (Tools::isSubmit('request_rmalabel')) {
            $result = self::requestRMALabel();
            if (is_string($result)) {
                return array('rma_error' => $result);
            } else {
               if (!$result) {
                    $correos = new Correos();
                    return array('rma_error' => $correos->l('The was a problem, try again later'));
                }
            }
            Tools::redirect(isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] . '#correos-block' : null);
        }
            
        return array();
    }
    public static function getCollections()
    {
        if (!Db::getInstance()->executeS("SHOW TABLES LIKE '"._DB_PREFIX_."correos_collection'", true, false)) {
            return array();
        }
        $context = Context::getContext();
        if (Tools::isSubmit('form-search_collection_filter') ||
            Tools::isSubmit('form-search_collection_reset') ||
            Tools::getValue('collection_rows') ||
            Tools::getValue('collection_page')) {
            $context->smarty->assign('CURRENT_FORM', 'query_collections');
        }

        $where = 'WHERE 1 ';
        if (Tools::isSubmit('form-search_collection_filter')) {
            if (Tools::getValue('collectionFilter_dateFrom') && Tools::getValue('collectionFilter_dateTo')) {
                $where .= " AND (`date_requested` BETWEEN '" . pSQL(Tools::getValue('collectionFilter_dateFrom')) .
                " 00:00:00' AND '" . pSQL(Tools::getValue('collectionFilter_dateTo')) . " 23:59:59')";
            }
            if (Tools::getValue('collectionDateFilter_dateFrom') && Tools::getValue('collectionDateFilter_dateTo')) {
               $where .= " AND (`collection_date` BETWEEN '" . pSQL(Tools::getValue('collectionDateFilter_dateFrom')) .
               "' AND '" . pSQL(Tools::getValue('collectionDateFilter_dateTo')) . "')";
            }
            if (Tools::getValue('collectionFilter_status')) {
                 $where .= "AND `status` = '".pSQL(Tools::getValue('collectionFilter_status'))."'";
            }
        }
        $sql = 'SELECT COUNT( * ) FROM `'._DB_PREFIX_.'correos_collection` '.$where;
        $total_rows = Db::getInstance()->getValue($sql);
        $collection_rows = 50;
        $collection_page = 1;

        if (Tools::getValue('collection_rows')) {
            $collection_rows = (int) Tools::getValue('collection_rows');
        }
        if (!Tools::getValue('collection_page')) {
            $limit_from = 0;
        } else {
            $limit_from = ((int) Tools::getValue('collection_page') - 1) * (int) $collection_rows;
            $collection_page = (int) Tools::getValue('collection_page');
        }
        $total_pages = ceil((int) $total_rows / (int) $collection_rows);
        
        $sql = 'SELECT `id`, `confirmation_code`, `reference_code`, `date_requested`, `collection_date`, `status` '.
               'FROM `'._DB_PREFIX_.'correos_collection` '.$where.' ORDER BY `id` DESC LIMIT '.(int) $limit_from.',' . (int) $collection_rows ;
        $context->smarty->assign(
            'collection_pagination',
            array(
                'total_pages' => (int) $total_pages,
                'total_rows' => (int) $total_rows,
                'page' => (int) $collection_page,
                'collection_rows' => (int) $collection_rows
            )
        );
        return Db::getInstance()->executeS($sql);
    }
    public static function getReturns()
    {

        if (!Db::getInstance()->executeS("SHOW TABLES LIKE '"._DB_PREFIX_."correos_rma'", true, false)) {
            return array();
        }
        $context = Context::getContext();
        if (Tools::getValue('rma_rows') ||
            Tools::getValue('rma_page')) {
            $context->smarty->assign('CURRENT_FORM', 'search_rma');
        }


        $rma_rows = 50;
        $rma_page = 1;
        if (Tools::getValue('rma_rows')) {
            $rma_rows = (int) Tools::getValue('rma_rows');
        }
        if (!Tools::getValue('rma_page')) {
            $limit_from = 0;
        } else {
            $limit_from = ((int) Tools::getValue('rma_page') - 1) * (int) $rma_rows;
            $rma_page = (int) Tools::getValue('rma_page');
        }
 
        $returns = Db::getInstance()->executeS(
            "SELECT `id_order`, `shipment_code`, `date_response` ".
            "FROM "._DB_PREFIX_."correos_rma ORDER BY date_response DESC ".
            "LIMIT ".(int) $limit_from."," . (int) $rma_rows
        );
        $sql = 'SELECT COUNT( * )  FROM  `'._DB_PREFIX_.'correos_rma` ';
        $total_rows = Db::getInstance()->getValue($sql);
        $total_pages = ceil((int) $total_rows / (int) $rma_rows);
        $context->smarty->assign(
            'search_rma_pagination',
            array(
                'total_pages' => (int) $total_pages,
                'total_rows' => (int) $total_rows,
                'page' => (int) $rma_page,
                'rma_rows' => (int) $rma_rows
            )
        );
        
        foreach ($returns as &$return) {
            $order = new Order((int)$return['id_order']);
            $custommer = $order->getCustomer();
            $address = new Address($order->id_address_invoice);
            $return['firstname'] = $address->firstname ;
            $return['lastname'] = $address->lastname;
            $return['email'] = $custommer->email;
            $return['address'] = $address->address1;
            $return['postcode'] = $address->postcode;
            $return['city'] = $address->city;
            $return['phone'] = $address->phone;
        }
        return $returns;
    }
    public static function requestCustomsDocuments($address, $shipping_code_array, $id_order, $esad_type, $rma = false)
    {
        $correos_config = CorreosCommon::getCorreosConfiguration();
        $context = Context::getContext();
        $countryISO = 'ES';
        if (isset($address->id_country)) {
            $countryISO = Db::getInstance()->getValue(
                'SELECT `iso_code` FROM `'._DB_PREFIX_.'country` WHERE `id_country` = '.(int) $address->id_country
            );
        }
        $context->smarty->assign(array(
            "esad_type"         => Tools::strtoupper($esad_type),
            "correos_config"    => $correos_config,
            "address"           => $address,
            "number_pieces"     => Tools::getValue('number_pieces'),
            "sender"            => Tools::jsonDecode($correos_config['sender_1']),
            "countryISO"        => $countryISO,
        ));
        $xmlSend = $context->smarty->fetch(
            _PS_MODULE_DIR_ . 'correos/views/templates/admin/soap_requests/customs_documets.tpl'
        );
        $data = CorreosCommon::sendXmlCorreos('url_data', $xmlSend, true, "DocumentacionAduaneraOp");
        $data = str_replace('soapenv:', 'soapenv_', $data);
        $dataXml = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);
        if (!empty($dataXml->soapenv_Body->soapenv_Fault->faultcode)) {
            $context->smarty->assign(
                'request_customs_content_error_desc',
                $dataXml->soapenv_Body->soapenv_Fault->faultstring
            );
        }
        $result_code = $dataXml->soapenv_Body->RespuestaSolicitudDocumentacionAduanera->Resultado;
        if ($result_code == 0) { //correct
            if (!empty($dataXml->soapenv_Body->RespuestaSolicitudDocumentacionAduanera->Fichero)) {
                if ($rma) {
                    file_put_contents(
                        "../modules/correos/pdftmp/customs_rma_". (string)Tools::stripslashes($esad_type) . "_" . (int)$id_order . ".pdf",
                        base64_decode($dataXml->soapenv_Body->RespuestaSolicitudDocumentacionAduanera->Fichero)
                    );
                } else {
                    file_put_contents(
                        "../modules/correos/pdftmp/customs_". (string)Tools::stripslashes($esad_type) . "_" . (int)$id_order . ".pdf",
                        base64_decode($dataXml->soapenv_Body->RespuestaSolicitudDocumentacionAduanera->Fichero)
                    );
                }
            }
        } else {
            $context->smarty->assign(
                'request_customs_content_error_desc',
                $dataXml->soapenv_Body->RespuestaSolicitudDocumentacionAduanera->MotivoError
            );
        }
    }
    public static function requestCustomsContent($shipping_code)
    {
        $context = Context::getContext();
        $context->smarty->assign('shipping_code', Tools::stripslashes($shipping_code));
        $xmlSend = $context->smarty->fetch(
            _PS_MODULE_DIR_ . 'correos/views/templates/admin/soap_requests/customs_content.tpl'
        );
        $data = CorreosCommon::sendXmlCorreos('url_data', $xmlSend, true, "DocumentacionAduaneraCN23CP71Op");
        //file_put_contents("../modules/correos/customs_request.xml", $xmlSend);
        //file_put_contents("../modules/correos/customs_response.xml", $data);
        if (!strstr(Tools::strtolower($data), 'soapenv:envelope')) {
            return $data;
        }

        $data = str_replace('soapenv:', 'soapenv_', $data);
        $dataXml = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);
        if (!empty($dataXml->soapenv_Body->soapenv_Fault->faultcode)) {
            $context->smarty->assign(
                'request_customs_content_error_desc',
                $dataXml->soapenv_Body->soapenv_Fault->faultstring
            );
        }
        $result_code = $dataXml->soapenv_Body->RespuestaSolicitudDocumentacionAduaneraCN23CP71->Resultado;

        if ($result_code == 0) { //correct
            if (!empty($dataXml->soapenv_Body->RespuestaSolicitudDocumentacionAduaneraCN23CP71->Fichero)) {
                file_put_contents(
                    "../modules/correos/pdftmp/customs_" . Tools::strtolower($shipping_code) . ".pdf",
                    base64_decode($dataXml->soapenv_Body->RespuestaSolicitudDocumentacionAduaneraCN23CP71->Fichero)
                );
            }
        } else {
            $context->smarty->assign(
                'request_customs_content_error_desc',
                $dataXml->soapenv_Body->RespuestaSolicitudDocumentacionAduaneraCN23CP71->MotivoError
            );
        }
    }
    public static function sendMailinTransit($order, $customer, $shipping_number, $carrier_url)
    {
        $templateVars = array(
          '{followup}' => str_replace('@', $shipping_number, $carrier_url),
          '{firstname}' => $customer->firstname,
          '{lastname}' => $customer->lastname,
          '{id_order}' => (int) $order->id,
          '{order_name}' => $order->getUniqReference()
        );
        @Mail::Send(
            (int)$order->id_lang,
            'in_transit',
            Mail::l('Package in transit', (int)$order->id_lang),
            $templateVars,
            $customer->email,
            $customer->firstname.' '.$customer->lastname,
            null,
            null,
            null,
            null,
            _PS_MAIL_DIR_,
            true,
            (int)$order->id_shop
        );
    }
    public static function getLastTracking($shipping_code)
    {
        $context = Context::getContext();
        $context->smarty->assign('shipping_code', $shipping_code);
        $xmlSend = $context->smarty->fetch(
            _PS_MODULE_DIR_ . 'correos/views/templates/admin/soap_requests/tracking.tpl'
        );
        $data = CorreosCommon::sendXmlCorreos(
            'url_tracking',
            $xmlSend,
            false,
            "ServiciosWebLocalizacionMI/ConsultaLocalizacionEnviosFasesMasivo"
        );
        if ($data == '') {
            return "Unknown error, please try again";
        }
        if (!strstr(Tools::strtolower($data), 'soap:envelope')) {
            return $data;
        }
        $dataXml = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOWARNING);
        $dataXml->registerXPathNamespace('soap', 'http://schemas.xmlsoap.org/soap/envelope/');
        $error = $dataXml->xpath('//soap:Fault');
        if (!empty($error)) {
            return  "Server Error: " . $error[0]->faultstring;
        }
        $dataXml->registerXPathNamespace(
            'ConsultaLocalizacionEnviosFasesMasivoResponse',
            'ServiciosWebLocalizacionMI/'
        );
        $response_fase = $dataXml->xpath(
            '//ConsultaLocalizacionEnviosFasesMasivoResponse:ConsultaLocalizacionEnviosFasesMasivoResult'
        );
        $response_xml = simplexml_load_string($response_fase[0]);
        $response = $response_xml->xpath('//Respuestas/DatosIdiomas/DatosEnvios/Datos');

        return utf8_decode($response[0]->Estado . " (" . $response[0]->Fecha . ")");
    }
    public static function getTrackingHistory($shipping_code)
    {

        $context = Context::getContext();
        $context->smarty->assign('shipping_code', (string) $shipping_code);
        $xmlSend = $context->smarty->fetch(
            _PS_MODULE_DIR_ . 'correos/views/templates/admin/soap_requests/tracking.tpl'
        );
        $data = CorreosCommon::sendXmlCorreos(
            'url_tracking',
            $xmlSend,
            false,
            "ServiciosWebLocalizacionMI/ConsultaLocalizacionEnviosFases"
        );
        if ($data == '') {
            return "Unknown error, please try again";
        }
        if (!strstr(Tools::strtolower($data), 'soap:envelope')) {
            return $data;
        }
        try {
            $dataXml = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOWARNING);
            $dataXml->registerXPathNamespace('soap', 'http://schemas.xmlsoap.org/soap/envelope/');
            
            $error = $dataXml->xpath('//soap:Fault');
            if (!empty($error)) {
                return  "Server Error: " . $error[0]->faultstring;
            }
      
            $dataXml->registerXPathNamespace('respuesta', 'ServiciosWebLocalizacionMI/');
            $_respuesta = $dataXml->xpath('//respuesta:ConsultaLocalizacionEnviosFasesResult');
            $_respuestaXml = simplexml_load_string($_respuesta[0]);
            $respuesta = $_respuestaXml->xpath('//Respuestas/DatosIdiomas/DatosEnvios');
        } catch (Exception $e) {
            return "Datos no disponibles temporalmente.";
        }
        return $respuesta;
    }
    public static function getLabelRemote($sipping_code)
    {
        $correos_config = CorreosCommon::getCorreosConfiguration();
        $context = Context::getContext();
        $context->smarty->assign(array(
            "correos_config"    => $correos_config,
            "operation_date"    => date('d-m-Y H:i:s'),
            "sipping_code"      => (string) $sipping_code
        ));
        $xmlSend = $context->smarty->fetch(
            _PS_MODULE_DIR_ . 'correos/views/templates/admin/soap_requests/label.tpl'
        );
        $data = CorreosCommon::sendXmlCorreos('url_data', $xmlSend, true, "SolicitudEtiquetaOp");
        if (!strstr(Tools::strtolower($data), 'soapenv:envelope')) {
            return $data;
        }
        $dataXml = simplexml_load_string($data);
        $dataXml->registerXPathNamespace('soapenv', 'http://schemas.xmlsoap.org/soap/envelope/');
        $error = $dataXml->xpath('//soapenv:Fault');
        if (!empty($error)) {
            return  "Server Error: " . $error[0]->faultstring;
        }
        $dataXml->registerXPathNamespace('respuesta', 'http://www.correos.es/iris6/services/preregistroetiquetas');
        $_codError = $dataXml->xpath('//respuesta:Resultado');
        if ($_codError[0] == '0') {
        // correct
            return $dataXml->xpath('//respuesta:Bulto');
        } else {
          // error
            return false;
        }
    }
    public static function generateShippingLabels()
    {
        include '../modules/correos/lib/pdfmerger.php';
        $pdf = new PDFMerger;

       //Generate new file name because of browser cache
        $pdf_file = uniqid('labels_').".pdf";

       //delete previous files
        foreach (glob("../modules/correos/pdftmp/labels*.*") as $filename) {
            unlink($filename);
        }
        $orders = Tools::getValue('id_order');

        foreach ($orders as $id_order => $shipping_code) {
            $preregister_id = Db::getInstance()->getValue(
                'SELECT `id`, `code_expedition`, `shipment_code` FROM `'._DB_PREFIX_.'correos_preregister` 
                WHERE `id_order` = '.(int) $id_order . ' ORDER BY `id` DESC'
            );
            $shipping_code_array = explode(",", $shipping_code);
            
            if (count($shipping_code_array) == 1) {
                if (file_exists("../modules/correos/pdftmp/" . (int) $id_order . "_" . (int) $preregister_id . ".pdf")) {
                    rename(
                    "../modules/correos/pdftmp/" . (int) $id_order . "_" . (int) $preregister_id . ".pdf",
                    "../modules/correos/pdftmp/" . Tools::strtolower($shipping_code_array[0]) . ".pdf");
                }
            }
            foreach ($shipping_code_array as $shipping_code) {
                $pdf_label_file = "../modules/correos/pdftmp/" . Tools::strtolower($shipping_code) . ".pdf";
                 if (!file_exists($pdf_label_file)) {
                    $dataPdf = self::getLabelRemote((int) $id_order, (string) $shipping_code);
                    file_put_contents(
                        $pdf_label_file,
                        base64_decode($dataPdf[0]->Etiqueta->Etiqueta_pdf->Fichero)
                    );
                }
                 $pdf->addPDF($pdf_label_file, 'all');
            }
        }
        if (Tools::getValue('option_order') == 'generate_label_a4') {
            $pdf->merge('file', '../modules/correos/pdftmp/'.$pdf_file);
        } else {
            $pdf->mergeTopages('file', '../modules/correos/pdftmp/'.$pdf_file);
        }
        foreach ($orders as $id_order => $shipping_code) {
            Db::getInstance()->Execute(
                "UPDATE `"._DB_PREFIX_."correos_preregister` 
                SET `label_printed` = CURRENT_TIMESTAMP 
                WHERE `id_order` = ".(int) $id_order." AND `shipment_code` = '".pSQL($shipping_code)."'"
            );
        }
        /*
       //delete old PDF
        $rows = Db::getInstance()->ExecuteS(
            "SELECT `shipment_code` FROM `"._DB_PREFIX_."correos_preregister`
            WHERE label_printed < DATE_SUB(NOW(), INTERVAL 4 MONTH)"
        );
        foreach ($rows as $row) {
            $shipping_code_array = explode(",",  $row['shipment_code']);
            foreach ($shipping_code_array as $shipping_code) {
                $pdf_label_file = "../modules/correos/pdftmp/" . $shipping_code . ".pdf";
                if (file_exists($pdf_label_file)) {
                    unlink($pdf_label_file);
                }
            }
        }
        */
        return $pdf_file;
    }
    public static function generateManifest()
    {
        $records = "";
        $correos_config = CorreosCommon::getCorreosConfiguration();
        $sender = Tools::jsonDecode($correos_config['sender_1']);
        $orders = Tools::getValue('id_order'); //array
       //Generate new file name because of browser cache
        $pdf_file = uniqid('manifest_').".pdf";
       //delete previous files
        foreach (glob("../modules/correos/pdftmp/manifest_*.*") as $filename) {
            unlink($filename);
        }
        $order_ids_arr = array();
        foreach ($orders as $id_order => $shipping_code) {
            $order_ids_arr[] = (int) $id_order;
        }
        $order_ids = implode(",", $order_ids_arr);
        $sql = 'SELECT o.`id_order`, c.`firstname`, c.`lastname`, cp.`shipment_code`, cp.`manifest`, `carrier_code`, 
      `id_address_delivery`, `id_address_invoice`, `module`, `total_paid`, `weight`, `insurance`
      FROM `'._DB_PREFIX_.'orders` o
      INNER JOIN `'._DB_PREFIX_.'correos_preregister` cp ON ( o.`id_order` = cp.`id_order` ) 
      LEFT JOIN `'._DB_PREFIX_.'customer` c ON ( c.`id_customer` = o.`id_customer` ) 
      WHERE o.`id_order` IN ('.pSQL($order_ids).')
      ORDER BY o.`date_add` DESC';
        $orders_query = Db::getInstance()->executeS($sql);
        $cr_carriers_query = Db::getInstance()->executeS(
            "SELECT `code`, `title` FROM `"._DB_PREFIX_."correos_carrier` WHERE `id_reference` <> 0"
        );
        $cr_carriers = array();
        foreach ($cr_carriers_query as $cr_carrier) {
            $cr_carriers[$cr_carrier['code']] = array(
                'title' => $cr_carrier['title'],
                'total_packeges' => 0,
                'total_weight' => 0,
                'total_cashondelivery' => 0,
                'total_insurance' => 0
            );
        }
        $arr_cashondelivery_modules = explode(",", $correos_config['cashondelivery_modules']);
        $total_packeges = 0;
        $total_weight = 0;
        $total_cashondelivery = 0;
        $total_insurance = 0;
        $records = array();
        foreach ($orders_query as $order) {
            $address = new Address($order['id_address_delivery']);
            $cashondelivery = 0;
            if (in_array($order['module'], $arr_cashondelivery_modules)) {
                $cashondelivery = $order['total_paid'];
            }
            $records[] = array(
                "order" => $order,
                "address" => $address,
                "cashondelivery" => $cashondelivery
            );

            $total_packeges += 1;
            $total_weight += $order['weight'];
            $total_cashondelivery += $cashondelivery;
            $total_insurance += $order['insurance'];
            $cr_carriers[$order['carrier_code']]['total_packeges'] += 1;
            $cr_carriers[$order['carrier_code']]['total_weight'] += $order['weight'];
            $cr_carriers[$order['carrier_code']]['total_cashondelivery'] += $cashondelivery;
            $cr_carriers[$order['carrier_code']]['total_insurance'] += $order['insurance'];
        }

        Context::getContext()->smarty->assign(array(
        'records' => $records,
        'cr_carriers' => $cr_carriers,
        'sender' => $sender->nombre. ' ' .$sender->apellidos,
        'client_code' => $correos_config['correos_key'],
        'date' => date("d/m/Y"),
        'records' => $records,
        'total_packeges' => $total_packeges,
        'total_weight' => number_format((float)$total_weight, 2, '.', ''),
        'total_cashondelivery' => number_format((float)$total_cashondelivery, 2, '.', ''),
        'total_insurance' => number_format((float)$total_insurance, 2, '.', '')

        ));
        require_once _PS_MODULE_DIR_ . 'correos/classes/ManifestPdf.php';
        $pdf = new PDF(null, 'ManifestPdf', Context::getContext()->smarty);
        $pdf_content = $pdf->render(false);
        file_put_contents('../modules/correos/pdftmp/'.$pdf_file, $pdf_content);

        foreach ($orders as $id_order => $shipping_code) {
            Db::getInstance()->Execute(
                "UPDATE `"._DB_PREFIX_."correos_preregister` 
                SET `manifest` = CURRENT_TIMESTAMP 
                WHERE `id_order` = ".(int) $id_order." AND `shipment_code` = '".pSQL($shipping_code)."'"
            );
        }
        return $pdf_file;
    }
    public static function exportOrder($orders)
    {
        $correos = new Correos();
        $correos_config = CorreosCommon::getCorreosConfiguration();
        if (file_exists("../modules/correos/pdftmp/exp-orders.txt")) {
            unlink("../modules/correos/pdftmp/exp-orders.txt");
        }
        foreach ($orders as $id_order => $shipping_code) {
            $order = new Order((int) $id_order);
            $cart = new Cart((int) $order->id_cart);
            $address = new Address((int) $order->id_address_delivery);
            $carrier = new Carrier((int) $order->id_carrier);
            $result = CorreosCommon::getCarriers(true, "`id_reference` = " . (int) $carrier->id_reference);
            $carrier_code = $result['code'];
                           
            $txt_val = CorreosAdmin::prepareData($order, $cart, $carrier_code, $correos_config, $address);
               
            if ($txt_val['homepaq_code'] != '' && Tools::substr($txt_val['homepaq_code'], -1) == "P") {
                $carrier_code = $correos->carriers_codes_homepaq[
                    array_search((string) $carrier_code, (string) $correos->carriers_codes_citypaq)
                ];
            }
            $txt_content = $txt_val['sender_firstname'] . " " . $txt_val['sender_lastnames'] . "|";
            $txt_content .= $txt_val['sender_dni'] . "|" . $txt_val['sender_company'] . "|";
            $txt_content .= $txt_val['sender_contact_person'] . "|" . $txt_val['sender_address'] . "|";
            $txt_content .= $txt_val['sender_city'] . "|" . $txt_val['sender_state'] . "|";
            $txt_content .= $txt_val['sender_cp'] . "|" . $txt_val['sender_phone']."|";
            $txt_content .= $txt_val['sender_email']."|".$txt_val['sender_mobile']."||";
            $txt_content .= $txt_val['customer_firstname'] . " " . $txt_val['customer_lastname1'] . " ";
            $txt_content .= $txt_val['customer_lastname1'] . "|" . $txt_val['customer_company'] . "|";
            $txt_content .= $txt_val['customer_firstname']."|" . $txt_val['delivery_address'] . "|";
            $txt_content .= $txt_val['delivery_city'] . "|" . $txt_val['delivery_state'] . "|" ;
            $txt_content .= $txt_val['delivery_postcode'] . "|" . $txt_val['delivery_zip'] . "|";
            $txt_content .= $txt_val['country_iso'] . "|" . $txt_val['phone'] . "|";
            $txt_content .= $txt_val['email'] . "|" . $txt_val['mobile'] . "|";
            $txt_content .= $txt_val['mobile_lang'] . "||" .  $carrier_code . "|";
            $txt_content .= $txt_val['delivery_mode'] . "|" . $txt_val['id_office'] . "|";
            $txt_content .= $txt_val['homepaq_token'] . "|" . $txt_val['homepaq_code']."|";
            $txt_content .= $txt_val['weight'] . "|". $txt_val['long'] . "|";
            $txt_content .= $txt_val['width'] . "|". $txt_val['height'] . "|";
            $txt_content .= $txt_val['insurance_value'] ."|" . $txt_val['cashondelivery_value'] . "|";
            $txt_content .= $txt_val['cashondelivery_bankac'] . "|" . $txt_val['id_schedule'] . "|";
            $txt_content .= $txt_val['order_reference']. "|".PHP_EOL;
                        
            if (!empty($shipping_code)) { //called from module config
                file_put_contents("../modules/correos/pdftmp/exp-orders.txt", $txt_content, FILE_APPEND);
            } else { //called from order
                file_put_contents("../modules/correos/pdftmp/exp-" . (int) $id_order . ".txt", $txt_content);
            }
            Db::getInstance()->Execute(
                "UPDATE `"._DB_PREFIX_."correos_preregister` 
                SET `exported` = CURRENT_TIMESTAMP 
                WHERE `id_order` = ".(int) $id_order
            );
        }
        return true;
    }
     /*Prepare data to preregister*/
    public static function getOrderData($order, $cart, $carrier_code, $delivery_address, $request_data)
    {
        $correos = new Correos();
        $customer = new Customer($cart->id_customer);

        $shipping_val = array(
            'email'                 => $customer->email,
            'mobile'                => $delivery_address->phone_mobile,
            'phone'                 => $delivery_address->phone,
            'mobile_lang'           => '1',
            'customer_firstname'    => $delivery_address->firstname,
            'customer_lastname'     => $delivery_address->lastname,
            'customer_dni'          => $delivery_address->dni,
            'customer_company'      => trim($delivery_address->company),
            'delivery_address'      => $delivery_address->address1,
            'delivery_address2'      => $delivery_address->address2,
            'delivery_address_other' => $delivery_address->other,
            'delivery_city'         => $delivery_address->city,
            'delivery_postcode'     => $delivery_address->postcode,
            'delivery_zip'          => $delivery_address->postcode,
            'delivery_state'        => Db::getInstance()->getValue(
                'SELECT `name` FROM `'._DB_PREFIX_.'state` WHERE `id_state` = '.(int) $delivery_address->id_state
            ),
            'country_iso'           => Db::getInstance()->getValue(
                'SELECT `iso_code` FROM `'._DB_PREFIX_.'country` WHERE `id_country` = '.(int) $delivery_address->id_country
            ),
        );

        if (in_array($carrier_code, $correos->carriers_codes_office) ||
            in_array($carrier_code, $correos->carriers_codes_homepaq) ||
            in_array($carrier_code, $correos->carriers_codes_citypaq)) {
            if (isset($request_data->email)) {
                $shipping_val['email'] = $request_data->email;
            }
            if (isset($request_data->mobile->lang)) {
                $shipping_val['mobile_lang'] = $request_data->mobile->lang;
            }
            if (isset($request_data->mobile->number)) {
                $request_data->mobile->number = str_replace(" ", "", $request_data->mobile->number);
                $request_data->mobile->number = str_replace("+34", "", $request_data->mobile->number);
                if (is_numeric($request_data->mobile->number)) {
                    $shipping_val['mobile'] = $request_data->mobile->number;
                    if (is_array($shipping_val['mobile'])) { //bug detected
                        $shipping_val['mobile'] = $request_data->mobile->number->number;
                    }
                }
            }
            $customer_address = new Address((int) $order->id_address_invoice);
            $shipping_val['customer_firstname']       = $customer_address->firstname;
            $shipping_val['customer_lastname']       = $customer_address->lastname;
            $shipping_val['customer_company']         = trim($customer_address->company);
            $shipping_val['country_iso']              = Db::getInstance()->getValue(
                'SELECT `iso_code` FROM `'._DB_PREFIX_.'country` WHERE `id_country` = '.(int) $customer_address->id_country
            );
            $shipping_val['state']                    = Db::getInstance()->getValue(
                'SELECT `name` FROM `'._DB_PREFIX_.'state` WHERE `id_state` = '.(int) $customer_address->id_state
            );
            $shipping_val['phone'] = $customer_address->phone;
        }
        if (in_array($carrier_code, $correos->carriers_codes_office)) {
            $office = false;
            if (isset($request_data->offices)) {
                foreach ($request_data->offices as $o) {
                    if ($o->unidad == $request_data->id_collection_office) {
                        $office = $o;
                        break;
                    }
                }
            }
            if ($office) {
                $shipping_val['delivery_address']  = $office->direccion;
                $shipping_val['delivery_city']     = $office->localidad;
                $shipping_val['delivery_postcode'] = $office->cp;
                $shipping_val['delivery_zip']      = $office->cp;
            }
            $shipping_val['delivery_address2']        = '';
        } elseif (in_array($carrier_code, $correos->carriers_codes_homepaq) ||
            in_array($carrier_code, $correos->carriers_codes_citypaq)) {
            $correos_paq = false;
            if (isset($request_data->homepaqs)) {
                foreach ($request_data->homepaqs as $h) {
                    if ($h->code == $request_data->homepaq_code) {
                        $correos_paq = $h;
                        break;
                    }
                }
            }
            if ($correos_paq) {
                $shipping_val['delivery_address']     = str_replace(
                    "undefined",
                    "",
                    $correos_paq->streetType . " " . $correos_paq->address . " " . $correos_paq->number
                );
                $shipping_val['delivery_city']        = $correos_paq->city ;
                $shipping_val['delivery_postcode']    = $correos_paq->postalCode;
                $shipping_val['delivery_zip']         = $correos_paq->postalCode;
            }
        } elseif (in_array($carrier_code, $correos->carriers_codes_international)) {
            if (isset($request_data->mobile) && isset($request_data->mobile->number)) {
                $shipping_val['mobile'] = $request_data->mobile->number;
                if (is_array($shipping_val['mobile'])) { //bug detected
                    $shipping_val['mobile'] = $request_data->mobile->number->number;
                }
            } elseif (!empty($delivery_address->phone_mobile)) {
              $shipping_val['mobile'] = $delivery_address->phone_mobile;
            }
            //Mobile phone only for Spain. Otherwise the Correos server will respond will error
            $shipping_val['phone'] = "";
            $shipping_val['mobile_lang'] = "";
        }
       //remove spaces from mobile
        $shipping_val['mobile'] = str_replace(" ", "", $shipping_val['mobile']);
        return  $shipping_val;
    }
    public static function exportOrderToFileFromPost($order, $carrier_code, $txt_val)
    {
        $txt_content = $txt_val['sender_firstname'] . " " . $txt_val['sender_lastname1'] . " " . $txt_val['sender_lastname2'] . "|";
        $txt_content .= $txt_val['sender_dni'] . "|" . $txt_val['sender_company'] . "|";
        $txt_content .= $txt_val['sender_contact_person'] . "|" . $txt_val['sender_address'] . "|";
        $txt_content .= $txt_val['sender_city'] . "|" . $txt_val['sender_state'] . "|";
        $txt_content .= $txt_val['sender_cp'] . "|" . $txt_val['sender_phone']."|";
        $txt_content .= $txt_val['sender_email']."|".$txt_val['sender_mobile']."||";
        $txt_content .= $txt_val['customer_firstname'] . " " . $txt_val['customer_lastname1'] . " " . $txt_val['customer_lastname2'] . " ";
        $txt_content .= $txt_val['customer_lastname1'] . "|" . $txt_val['customer_company'] . "|";
        $txt_content .= $txt_val['customer_firstname']."|" . $txt_val['delivery_address'] . "|";
        $txt_content .= $txt_val['delivery_city'] . "|" . $txt_val['delivery_state'] . "|" ;
        $txt_content .= $txt_val['delivery_postcode'] . "|" . $txt_val['delivery_zip'] . "|";
        $txt_content .= $txt_val['country_iso'] . "|" . $txt_val['phone'] . "|";
        $txt_content .= $txt_val['email'] . "|" . $txt_val['mobile'] . "|";
        $txt_content .= $txt_val['mobile_lang'] . "||" .  $carrier_code . "|";
        $txt_content .= $txt_val['delivery_mode'] . "|" . $txt_val['id_office'] . "|";
        $txt_content .= $txt_val['homepaq_token'] . "|" . $txt_val['homepaq_code']."|";
        $txt_content .= $txt_val['weight'] . "|". $txt_val['long'] . "|";
        $txt_content .= $txt_val['width'] . "|". $txt_val['height'] . "|";
        $txt_content .= $txt_val['insurance_value'] ."|" . $txt_val['cashondelivery_value'] . "|";
        $txt_content .= $txt_val['cashondelivery_bankac'] . "|" . $txt_val['id_schedule'] . "|";
        $txt_content .= $txt_val['order_reference']. "|".PHP_EOL;

        file_put_contents("../modules/correos/pdftmp/exp-" . (int) $order->id . ".txt", $txt_content);
        Db::getInstance()->Execute(
            "UPDATE `"._DB_PREFIX_."correos_preregister`
            SET `exported` = CURRENT_TIMESTAMP
            WHERE `id_order` = ".(int) $order->id
        );
    }
    /*Prepare data to preregister*/
    public static function prepareDataFromPost($order, $cart, $carrier_code, $correos_config = false, $delivery_address, $request_data = false)
    {
        $correos = new Correos();
        $sender = Tools::getValue('sender');
        $recipient = Tools::getValue('recipient');
        if (!$correos_config) {
            $correos_config = CorreosCommon::getCorreosConfiguration();
        }
        $shipping_val = array(
            'order_reference'       => (int) $order->id .' ' . $order->reference,
            'sender_firstname'      => $sender['firstname'],
            'sender_lastname1'      => $sender['lastname'],
            'sender_lastname2'      => '',
            'sender_dni'            => $sender['dni'],
            'sender_company'        => $sender['company'],
            'sender_contact_person' => $sender['contact_person'],
            'sender_address'        => $sender['address'],
            'sender_city'           => $sender['city'],
            'sender_state'          => $sender['state'],
            'sender_cp'             => $sender['postcode'],
            'sender_phone'          => $sender['phone'],
            'sender_email'          => $sender['email'],
            'sender_mobile'         => $sender['mobile'],
            'delivery_mode'         => 'ST',
            'parcel_info'           => array(),
            'cashondelivery_type'   => '',
            'cashondelivery_value'  => '',
            'cashondelivery_bankac' => '',
            'email'                 => $recipient['email'],
            'mobile'                => $recipient['mobile'],
            'phone'                 => $recipient['phone'],
            'mobile_lang'           => empty($recipient['mobile']) ? '' : $recipient['mobile_lang'],
            
            'insurance_value'       => Tools::getValue('correos_package_insurance') != 0 ? (float) str_replace(',', '.', Tools::getValue('correos_package_insurance')) * 100 : '',
            'customer_firstname'    => $recipient['firstname'],
            'customer_lastname1'    => $recipient['lastname'],
            'customer_lastname2'    => '',
            'customer_company'      => $recipient['company'],
            'delivery_address'      => $recipient['address'],
            'delivery_address2'     => $recipient['address2'],
            'delivery_address_other' => '',
            'delivery_city'         => $recipient['city'],
            'delivery_postcode'     => $recipient['postcode'],
            'delivery_zip'          => '',
            'delivery_state'        => $recipient['state'],
            'country_iso'           => Db::getInstance()->getValue(
                'SELECT `iso_code` FROM `'._DB_PREFIX_.'country` WHERE `id_country` = '.(int) $delivery_address->id_country
            ),
            'partial_delivery'      => 'N',
            'homepaq_token'         => '',
            'homepaq_code'          => '',
            'homepaq_admission'     => '',
            'id_office'             => '' ,
            'id_schedule'           => '',
        );
        $parcel_info_default = array(
            'weight'                => '',
            'long'                  => '',
            'width'                 => '',
            'height'                => '',
            'package_reference'     => '',
            'package_observations'  => '',
            'customs_type'          => '2',
            'customs_comercial'     => 'S',
            'customs_fra500'        => 'N',
            'customs_duacorreos'    => 'N',
            'customs_product_qty'   => '',
            'customs_product_weight' => '',
            'customs_product_value' => '',
            'customs_description'   => '',
        );

        $parcel_info = array();
        foreach (Tools::getValue('correos_package_weight') as  $index => $weight) {
            $parcel_info[] = $parcel_info_default;
            $weight = (float)str_replace(',', '.', $weight);
            $parcel_info[$index]['weight'] =  ($weight != 0 ? $weight : 1) * 1000;
            $parcel_info[$index]['long'] = (float)str_replace(',', '.', Tools::getValue('correos_package_long')[$index]);
            $parcel_info[$index]['width'] = (float)str_replace(',', '.', Tools::getValue('correos_package_width')[$index]);
            $parcel_info[$index]['height'] = (float)str_replace(',', '.', Tools::getValue('correos_package_height')[$index]);
            $parcel_info[$index]['package_reference'] = Tools::getValue('correos_package_reference')[$index];
            $parcel_info[$index]['package_observations'] = Tools::getValue('correos_package_observations')[$index];
        }

        
        if (!empty($shipping_val['sender_lastname1'])) {
            //Second Surname sender
            $sender_lastname_arr = explode(" ", $shipping_val['sender_lastname1']);
            if (count($sender_lastname_arr) > 1) {
                $shipping_val['sender_lastname1'] = $sender_lastname_arr[0];
                unset($sender_lastname_arr[0]);
                $shipping_val['sender_lastname2'] = implode(" ", $sender_lastname_arr);
            }
        }

        //Second Surname customer
        $customer_lastname_arr = explode(" ", $shipping_val['customer_lastname1']);
        if (count($customer_lastname_arr) > 1) {
            $shipping_val['customer_lastname1'] = $customer_lastname_arr[0];
            unset($customer_lastname_arr[0]);
            $shipping_val['customer_lastname2'] = implode(" ", $customer_lastname_arr);
        }
        if ($shipping_val['country_iso'] == 'PT') {
            $shipping_val['delivery_zip'] = $recipient['postcode'];
            $shipping_val['delivery_postcode'] = '';
        }
        if (in_array($order->module, explode(",", $correos_config['cashondelivery_modules']))) {
            $shipping_val['cashondelivery_type'] = 'RC';
            $shipping_val['cashondelivery_value'] = $order->total_paid * 100;
            $shipping_val['cashondelivery_bankac'] = $correos_config['bank_account_number'];
        }

        if (!$request_data) {
            $row = Db::getInstance()->getRow(
                "SELECT `data` FROM `"._DB_PREFIX_."correos_request` 
                WHERE `id_cart` = ".(int) $order->id_cart." AND `id_carrier` = ".(int) $order->id_carrier
            );
            if ($row) {
                if (_PS_MAGIC_QUOTES_GPC_) {
                    $row['data'] = str_replace("u00", "\u00", $row['data']);
                }
                $request_data = Tools::jsonDecode($row['data']);
            }
        }
        if (in_array($carrier_code, $correos->carriers_codes_office)) {
            
            $shipping_val['delivery_mode'] = "LS";
            if (Tools::getValue('id_collection_office') && Tools::getValue('offices')) {
                
                //Office changed from the order
                if (empty($request_data)) {
                    //office not selected during checkout or carrer has been changed
                    $request_data = (object) array(
                        'id_collection_office' => (string)Tools::stripslashes(Tools::getValue('id_collection_office')),
                        'offices' => Tools::jsonDecode(Tools::getValue('offices')),
                        'mobile' => array('number' => Tools::getValue('recipient')['mobile'], 'lang' => 1),
                        'email' => Tools::getValue('recipient')['email'],
                        'id_address_delivery' => $cart->id_address_delivery
                        );
                } else {
                    $request_data->id_collection_office = (string)Tools::stripslashes(Tools::getValue('id_collection_office'));
                    $request_data->offices = Tools::jsonDecode(Tools::getValue('offices'));
                }
                Db::getInstance()->Execute(
                        "UPDATE `"._DB_PREFIX_."correos_request` SET `reference` = '".pSQL(Tools::getValue('correos_postcode'))."', ".
                        "`data` = '".pSQL(Tools::jsonEncode($request_data))."'".
                        "WHERE `id_cart` = ".(int)$order->id_cart." AND `id_carrier` = ".(int) $order->id_carrier
                );
            }

            $shipping_val['id_office'] = $request_data->id_collection_office;
            $shipping_val['delivery_address2']  = '';
        } elseif (in_array($carrier_code, $correos->carriers_codes_homepaq) ||
            in_array($carrier_code, $correos->carriers_codes_citypaq)) {

            if (Tools::getValue('homepaq_code') && Tools::getValue('homepaqs') && Tools::getValue('homepaq_token')) {
                //Office changed from the order
                $request_data->homepaq_code = Tools::getValue('homepaq_code');
                $request_data->homepaqs     = Tools::jsonDecode(Tools::getValue('homepaqs'));
                $request_data->token        = Tools::getValue('homepaq_token');
                Db::getInstance()->Execute(
                    "UPDATE `"._DB_PREFIX_."correos_request` SET `data` = '".pSQL(Tools::jsonEncode($request_data))."'  
                    WHERE `id_cart` = ".(int) $order->id_cart." AND `id_carrier` = ".(int) $order->id_carrier
                );
                $shipping_val['delivery_address']     = str_replace("undefined", "", $shipping_val['delivery_address']);
            }

            $shipping_val['homepaq_token']        = $request_data->token;
            $shipping_val['homepaq_code']         = $request_data->homepaq_code;
            $shipping_val['homepaq_admission']    = 'N';
            $shipping_val['delivery_mode'] = "CP";
            // redmine #6901
            $shipping_val['mobile'] = '';
            $shipping_val['mobile_lang'] = '';
            $shipping_val['phone'] = $recipient['mobile'];

        } elseif (in_array($carrier_code, $correos->carriers_codes_hourselect)) {
            if (isset($request_data->id_schedule)) {
                $shipping_val['id_schedule'] = $request_data->id_schedule;
            }
        } elseif (in_array($carrier_code, $correos->carriers_codes_international)) {
            if (isset($request_data->mobile) && !empty($request_data->mobile)) {
                $shipping_val['phone'] = $request_data->mobile;
            }
            //Mobile phone only for Spain. Otherwise the Correos server will respond will error
            $shipping_val['mobile']      = "";
            $shipping_val['mobile_lang'] = "";
            $shipping_val['delivery_zip'] = $recipient['postcode'];
            $shipping_val['delivery_postcode'] = '';
        }
        
        if (Tools::getValue('goods_type')) {
            $products = $order->getProducts();
            $first_product = reset($products);
            foreach (Tools::getValue('correos_package_weight') as  $index => $weight) {

                if ((float)$order->total_products_wt > 500) {
                    $parcel_info[$index]['customs_fra500'] = 'S';
                }
                $parcel_info[$index]['customs_product_qty'] = $first_product['product_quantity'];
                $parcel_info[$index]['customs_product_weight'] = ((float)$first_product['product_weight'] != 0 ?
                    (float)$first_product['product_weight'] * 1000 : 1000);
                if (Tools::getValue('customs_firstproductvalue')) {
                    $parcel_info[$index]['customs_product_value'] = str_pad(
                        ((float)str_replace(',','.',Tools::getValue('customs_firstproductvalue')[$index]) * 100),
                        6,
                        "0",
                        STR_PAD_LEFT
                    );
                } else {
                    $parcel_info[$index]['customs_product_value'] = str_pad(
                        ((float)$first_product['unit_price_tax_excl'] * 100),
                        6,
                        "0",
                        STR_PAD_LEFT
                    );
                }
                $parcel_info[$index]['customs_description']   = htmlentities(Tools::getValue('goods_type')[$index]);
            }
        }

        //save parcel details
        $request_data = (array)$request_data;
        $request_data['parcel_details'] = $parcel_info;
        Db::getInstance()->Execute(
            "UPDATE `"._DB_PREFIX_."correos_request` SET `data` = '".pSQL(Tools::jsonEncode($request_data))."'  
            WHERE `id_cart` = ".(int)$order->id_cart." AND `id_carrier` = ".(int) $order->id_carrier
        );
       //remove spaces from mobile
        $shipping_val['mobile'] = str_replace(" ", "", $shipping_val['mobile']);

        $shipping_val['parcel_info'] = $parcel_info;
        if (Tools::getValue('correos-multipackage') == 1) {
            $shipping_val['partial_delivery'] = 'S';
        }

        return  $shipping_val;
    }
   /*Prepare data to preregister*/
    public static function prepareData($order, $cart, $carrier_code, $correos_config = false, $delivery_address)
    {
        $correos = new Correos();
        $customer = new Customer($cart->id_customer);
        $sender_key = "sender_1";
        if (Tools::getValue('correos_sender_hidden')) {
            $sender_key = Tools::getValue('correos_sender_hidden');
        }
        if (!$correos_config) {
            $correos_config = CorreosCommon::getCorreosConfiguration();
        }
        $sender_string = $correos_config[$sender_key];
        $sender = Tools::jsonDecode($sender_string);
        $sender->apellido1 = '';
        $sender->apellido2 = '';
        if ($sender->nombre == '') {
            $sender->nombre = $sender->presona_contacto;
        }
        if ($sender->apellidos == '') {
            $sender->apellido1 = $sender->empresa;
        } else {
            //Second Surname
            $sender_lastname_arr = explode(" ", $sender->apellidos);
            if (count($sender_lastname_arr) > 1) {
                $sender->apellido1 = $sender_lastname_arr[0];
                unset($sender_lastname_arr[0]);
                $sender->apellido2 = implode(" ", $sender_lastname_arr);
            } else {
                $sender->apellido1 = $sender->apellidos;
            }
        }
        if ($sender->presona_contacto == '') {
            $sender->presona_contacto = $sender->nombre ." ". $sender->apellidos;
        }
        if ($sender->empresa == '') {
            $sender->empresa = $sender->nombre ." ". $sender->apellidos;
        }
        $shipping_val = array(
        'sender_firstname'      => $sender->nombre,
        'sender_lastnames'       => $sender->apellidos,
        'sender_lastname1'       => $sender->apellido1,
        'sender_lastname2'       => $sender->apellido2,
        'sender_dni'            => $sender->dni,
        'sender_company'        => $sender->empresa,
        'sender_contact_person' => $sender->presona_contacto,
        'sender_address'        => $sender->direccion,
        'sender_city'           => $sender->localidad,
        'sender_state'          => $sender->provincia,
        'sender_cp'             => $sender->cp,
        'sender_phone'          => $sender->tel_fijo,
        'sender_email'          => $sender->email,
        'sender_mobile'         => $sender->movil,
        'delivery_mode'         => 'ST',
        'weight'                => $cart->getTotalWeight() * 1000,
        'long'                  => '',
        'width'                 => '',
        'height'                => '',
        'cashondelivery_type'  => '',
        'cashondelivery_value'  => '',
        'cashondelivery_bankac' => '',
        'insurance_value'       => '',
        'email'                 => $customer->email,
        'mobile'                => $delivery_address->phone_mobile != '' ? $delivery_address->phone_mobile : '',
        'phone'                 => $delivery_address->phone,
        'mobile_lang'           => $delivery_address->phone_mobile != '' ? '1' : '',
        'order_reference'       => (int) $order->id .' ' . $order->reference,
        'insurance_value'       => $correos_config['insurance'] == "1" ? $correos_config['insurance_value'] * 100 : '',
        'customer_firstname'    => $delivery_address->firstname,
        'customer_lastname1'     => $delivery_address->lastname,
        'customer_lastname2'    => '',
        'customer_company'      => $delivery_address->company,
        'delivery_address'      => $delivery_address->address1,
        'delivery_address2'      => $delivery_address->address2,
        'delivery_address_other' => $delivery_address->other,
        'delivery_city'         => $delivery_address->city,
        'delivery_postcode'     => $delivery_address->postcode,
        'delivery_zip'          => '',
        'delivery_state'        => Db::getInstance()->getValue(
            'SELECT `name` FROM `'._DB_PREFIX_.'state` WHERE `id_state` = '.(int) $delivery_address->id_state
        ),
        'country_iso'           => Db::getInstance()->getValue(
            'SELECT `iso_code` FROM `'._DB_PREFIX_.'country` WHERE `id_country` = '.(int) $delivery_address->id_country
        ),
        'homepaq_token'         => '',
        'homepaq_code'          => '',
        'homepaq_admission'     => '',
        'id_office'             => '',
        'id_schedule'           => '',
        'customs_type'          => '',
        'customs_comercial'     => '',
        'customs_fra500'        => '',
        'customs_duacorreos'    => '',
        'customs_product_qty'   => '',
        'customs_product_weight' => '',
        'customs_product_value' => '',
        'customs_description'   => Configuration::get('CORREOS_CUSTOMS_DEFAULT_CATEGORY'),
        'observations'          => ''
        );
        if ($shipping_val['country_iso'] == 'PT') {
            $shipping_val['delivery_zip'] = $shipping_val['delivery_postcode'];
            $shipping_val['delivery_postcode'] = '';
        }
        if (Tools::getValue('correos_package_insurance_hidden')) {
            $shipping_val['insurance_value'] = (float) str_replace(',', '.', Tools::getValue('correos_package_insurance_hidden')) * 100;
        }
        if (in_array($order->module, explode(",", $correos_config['cashondelivery_modules']))) {
            $shipping_val['cashondelivery_type'] = 'RC';
            $shipping_val['cashondelivery_value'] = $order->total_paid * 100;
            $shipping_val['cashondelivery_bankac'] = $correos_config['bank_account_number'];
        }
        if (Tools::getValue('correos_package_long_hidden')) {
            $shipping_val['long']  = (float) str_replace(',', '.', Tools::getValue('correos_package_long_hidden'));
        }
        if (Tools::getValue('correos_package_width_hidden')) {
            $shipping_val['width'] = (float) str_replace(',', '.', Tools::getValue('correos_package_width_hidden'));
        }
        if (Tools::getValue('correos_package_height_hidden')) {
            $shipping_val['height'] = (float) str_replace(',', '.', Tools::getValue('correos_package_height_hidden'));
        }
        if (Tools::getValue('correos_package_weight_hidden')) {
            $shipping_val['weight'] = (float) str_replace(',', '.', Tools::getValue('correos_package_weight_hidden')) * 1000;
        }
        if ($shipping_val['weight'] == 0) {
            $shipping_val['weight'] = 1000;
        }
        $row = Db::getInstance()->getRow(
            "SELECT `data` FROM `"._DB_PREFIX_."correos_request` 
            WHERE `id_cart` = ".(int) $order->id_cart." AND `id_carrier` = ".(int) $order->id_carrier
        );
        $data = array();
        if ($row) {
            if (_PS_MAGIC_QUOTES_GPC_) {
                $row['data'] = str_replace("u00", "\u00", $row['data']);
            }
            $data = Tools::jsonDecode($row['data']);
        }
        if (in_array($carrier_code, $correos->carriers_codes_office) ||
            in_array($carrier_code, $correos->carriers_codes_homepaq) ||
            in_array($carrier_code, $correos->carriers_codes_citypaq)) {
            $shipping_val['email'] = $data->email;
            
            $data->mobile->number = str_replace(" ", "", $data->mobile->number);
            $data->mobile->number = str_replace("+34", "", $data->mobile->number);
            if (is_numeric($data->mobile->number)) {
                $shipping_val['mobile'] = $data->mobile->number;
                if (is_array($shipping_val['mobile'])) { //bug detected
                    $shipping_val['mobile'] = $data->mobile->number->number;
                    $shipping_val['mobile_lang'] = $data->mobile->lang;
                }
                $shipping_val['mobile_lang'] = $data->mobile->lang;
            } else {
                //buyer didnt want to inform it
                $shipping_val['mobile'] = '';
                $shipping_val['mobile_lang'] = '';
            }
            $customer_address = new Address((int) $order->id_address_invoice);
            $shipping_val['customer_firstname']       = $customer_address->firstname;
            $shipping_val['customer_lastname1']       = $customer_address->lastname;
            $shipping_val['customer_company']         = $customer_address->company;
            $shipping_val['country_iso']              = Db::getInstance()->getValue(
                'SELECT `iso_code` FROM `'._DB_PREFIX_.'country` WHERE `id_country` = '.(int) $customer_address->id_country
            );
            $shipping_val['state']                    = Db::getInstance()->getValue(
                'SELECT `name` FROM `'._DB_PREFIX_.'state` WHERE `id_state` = '.(int) $customer_address->id_state
            );
            $shipping_val['phone'] = $customer_address->phone;
        }
        if (in_array($carrier_code, $correos->carriers_codes_office)) {
            $shipping_val['delivery_mode'] = "LS";
            
            if (Tools::getValue('id_collection_office') && Tools::getValue('offices')) {
                //Office changed from the order
                $data->id_collection_office = (string)Tools::stripslashes(Tools::getValue('id_collection_office'));
                $data->offices = Tools::jsonDecode(Tools::getValue('offices'));
                Db::getInstance()->Execute(
                    "UPDATE `"._DB_PREFIX_."correos_request` SET `data` = '".pSQL(Tools::jsonEncode($data))."'  
                    WHERE `id_cart` = ".(int)$order->id_cart." AND `id_carrier` = ".(int) $order->id_carrier
                );
            }
            $office = false;
            if (isset($data->offices)) {
                foreach ($data->offices as $o) {
                    if ($o->unidad == $data->id_collection_office) {
                        $office = $o;
                        break;
                    }
                }
            }
            if ($office) {
                $shipping_val['delivery_address']  = $office->direccion;
                $shipping_val['delivery_city']     = $office->localidad;
                $shipping_val['delivery_postcode'] = $office->cp;
                $shipping_val['delivery_zip']      = $office->cp;
                $shipping_val['id_office']         =  $data->id_collection_office;
            }
            $shipping_val['delivery_address2']        = '';
        } elseif (in_array($carrier_code, $correos->carriers_codes_homepaq) ||
            in_array($carrier_code, $correos->carriers_codes_citypaq)) {
            if (Tools::getValue('homepaq_code') && Tools::getValue('homepaqs') && Tools::getValue('homepaq_token')) {
                //Office changed from the order
                $data->homepaq_code = Tools::getValue('homepaq_code');
                $data->homepaqs     = Tools::jsonDecode(Tools::getValue('homepaqs'));
                $data->token        = Tools::getValue('homepaq_token');
                Db::getInstance()->Execute(
                    "UPDATE `"._DB_PREFIX_."correos_request` SET `data` = '".pSQL(Tools::jsonEncode($data))."'  
                    WHERE `id_cart` = ".(int) $order->id_cart." AND `id_carrier` = ".(int) $order->id_carrier
                );
            }
            $correos_paq = false;
            if (isset($data->homepaqs)) {
                foreach ($data->homepaqs as $h) {
                    if ($h->code == $data->homepaq_code) {
                        $correos_paq = $h;
                        break;
                    }
                }
            }
            if ($correos_paq) {
                $shipping_val['delivery_address']     = str_replace(
                    "undefined",
                    "",
                    $correos_paq->streetType . " " . $correos_paq->address . " " . $correos_paq->number
                );
                $shipping_val['delivery_city']        = $correos_paq->city ;
                $shipping_val['delivery_postcode']    = $correos_paq->postalCode;
                $shipping_val['delivery_zip']         = $correos_paq->postalCode;
                $shipping_val['state']                = $correos_paq->state;
                $shipping_val['homepaq_token']        = $data->token;
                $shipping_val['homepaq_code']         = $data->homepaq_code;
            }
            $shipping_val['homepaq_admission']    = 'N';
            // redmine #6901
            if (!empty($shipping_val['mobile'])) {
                $shipping_val['phone'] = $shipping_val['mobile'];
            }
            $shipping_val['mobile'] = '';
            $shipping_val['mobile_lang'] = '';
            
            
        } elseif (in_array($carrier_code, $correos->carriers_codes_hourselect)) {
            if (isset($data->id_schedule)) {
                $shipping_val['id_schedule'] = $data->id_schedule;
            }
        } elseif (in_array($carrier_code, $correos->carriers_codes_international)) {
            if (isset($data->mobile) && !empty($data->mobile)) {
                $shipping_val['phone'] = $data->mobile;
            }
            //Mobile phone only for Spain. Otherwise the Correos server will respond will error
            $shipping_val['mobile']      = "";
            $shipping_val['mobile_lang'] = "";
            $shipping_val['delivery_zip'] = $shipping_val['delivery_postcode'];
            $shipping_val['delivery_postcode'] = '';
        }
        if ($correos_config['customs_zone']!='') {
            $customs_zone = Tools::jsonDecode($correos_config['customs_zone']);
            $id_zone = State::getIdZone((int) $delivery_address->id_state);
            if (!$id_zone) {
                $id_zone = Address::getZoneById((int) $cart->id_address_delivery);
            }
            if (in_array((int) $id_zone, $customs_zone)) {
                $products = $order->getProducts();
                $first_product = reset($products);
                //$shipping_val['delivery_postcode'] = '';
                $shipping_val['customs_type'] = '2';
                $shipping_val['customs_comercial'] = 'S';
                $shipping_val['customs_fra500'] = 'N';
                $shipping_val['customs_duacorreos'] = 'N';
                if ((float)$order->total_products_wt > 500) {
                    $shipping_val['customs_fra500'] = 'S';
                }
                $shipping_val['customs_product_qty'] = $first_product['product_quantity'];
                $shipping_val['customs_product_weight'] = ((float)$first_product['product_weight'] != 0 ?
                    (float)$first_product['product_weight'] * 1000 : 1000);
                if (Tools::getValue('customs_firstproductvalue_hidden')) {
                    $shipping_val['customs_product_value'] = str_pad(
                        ((float)str_replace(',','.',Tools::getValue('customs_firstproductvalue_hidden') )* 100),
                        6,
                        "0",
                        STR_PAD_LEFT
                    );
                } else {
                    $shipping_val['customs_product_value'] = str_pad(
                        ((float)$first_product['unit_price_tax_excl'] * 100),
                        6,
                        "0",
                        STR_PAD_LEFT
                    );
                }

                if (Tools::getValue('goods_type_hidden')) {
                    $shipping_val['customs_description']   = htmlentities(Tools::getValue('goods_type_hidden'));
                }
            }
        }
       //Second Surname
        $customer_lastname_arr = explode(" ", $shipping_val['customer_lastname1']);
        if (count($customer_lastname_arr) > 1) {
            $shipping_val['customer_lastname1'] = $customer_lastname_arr[0];
            unset($customer_lastname_arr[0]);
            $shipping_val['customer_lastname2'] = implode(" ", $customer_lastname_arr);
        }
       //remove spaces from mobile
        $shipping_val['mobile'] = str_replace(" ", "", $shipping_val['mobile']);

        if (Tools::getValue('correos_package_observations_hidden')) {
            $shipping_val['observations'] =  Tools::getValue('correos_package_observations_hidden');
        }
        return  $shipping_val;
    }
    public static function prepareXmlOrder($shipping_val, $correos_config, $carrier_code)
    {
        /*
        assign new code if selected code is CityPaq (last char P - public)
        index 0 - paq 48
        index 1 - paq 72
        */
        $id_zone = 0;
        if (isset(Context::getContext()->cart)) {
            $id_zone = Address::getZoneById((int)Context::getContext()->cart->id_address_delivery);
        }
        if ($id_zone == 99 && $carrier_code == 'S0235') {
            $carrier_code = 'S0132';
        }
        if ($shipping_val['homepaq_code'] != '' && Tools::substr($shipping_val['homepaq_code'], -1) == "P") {
            $correos = new Correos();
            $carrier_code = $correos->carriers_codes_citypaq[
                array_search($carrier_code, $correos->carriers_codes_homepaq)
            ];
        }
        $context = Context::getContext();
        if (isset($shipping_val['parcel_info']) && count($shipping_val['parcel_info']) == 1) {
            $shipping_val = array_merge($shipping_val, $shipping_val['parcel_info'][0]);
        }
        $context->smarty->assign(array(
            "correos_config" => $correos_config,
            "shipping_val" => $shipping_val,
            "carrier_code" =>  $carrier_code
        ));
        if (isset($shipping_val['parcel_info']) && count($shipping_val['parcel_info']) > 1) {
             $xmlSend = $context->smarty->fetch(
            _PS_MODULE_DIR_ . 'correos/views/templates/admin/soap_requests/preregister_multipackage.tpl'
            );
        } else {
             $xmlSend = $context->smarty->fetch(
            _PS_MODULE_DIR_ . 'correos/views/templates/admin/soap_requests/preregister.tpl'
            );
        }

        $doc = new DOMDocument;
        $doc->preserveWhiteSpace = false;
        if (!$doc->loadxml($xmlSend)) {
            return $xmlSend;
        }
        $xpath = new DOMXPath($doc);
      
       //Clean Empty tags - Child
        foreach ($xpath->query('//*[not(node())]') as $node) {
            $node->parentNode->removeChild($node);
        }
       //Clean Empty tags - Child - Parent
        foreach ($xpath->query('//*[not(node())]') as $node) {
            $node->parentNode->removeChild($node);
        }
       //Clean Empty tags - Parent
        foreach ($xpath->query('//*[not(node())]') as $node) {
            $node->parentNode->removeChild($node);
        }
        if (isset($shipping_val['parcel_info']) && count($shipping_val['parcel_info']) > 1) {
            foreach ($xpath->query('//*[not(node())]') as $node) {
            $node->parentNode->removeChild($node);
            }
        }
        $doc->formatOutput = true;
        return $doc->savexml();
    }
    public static function preregister($result, $order, $carrier_code, $shipping_val, $send_email = false)
    {
        if (empty($result)) {
            return false;
        }
        try {
            $db = Db::getInstance();
            $correos = new Correos();
            if (!strstr(Tools::strtolower($result), 'schemas.xmlsoap.org')) {
                //error - xml is not soap. Correos Username may be incorrect.
                $db->Execute(
                    "INSERT INTO `"._DB_PREFIX_."correos_preregister_errors` (`id_order`, `error`) 
                    VALUES (".(int) $order->id.", '".pSQL(Tools::substr($result, 0, 254))."')"
                );
                return false;
            }
            $dataXml = simplexml_load_string($result);
            if (!$dataXml->registerXPathNamespace(
                'soap',
                'http://www.correos.es/iris6/services/preregistroetiquetas'
            )) {
                $db->Execute(
                    "INSERT INTO `"._DB_PREFIX_."correos_preregister_errors` (`id_order`, `error`) 
                    VALUES (".(int) $order->id.", '".pSQL($correos->l("Unknown error, please try again later"))."')"
                );
                return false;
            }
            $error = $dataXml->xpath('//soap:Fault');
            if (!empty($error)) {
                $db->Execute(
                    "INSERT INTO `"._DB_PREFIX_."correos_preregister_errors` (`id_order`, `error`) 
                    VALUES (".(int)$order->id.", '".pSQL(Tools::substr($error[0]->faultstring, 0, 254))."')"
                );
                return false;
            }
            
          /*Success*/
            $error_code = $dataXml->xpath('//soap:Resultado');
            if ($error_code[0] == '0') {  // correct
                $package_data = $dataXml->xpath('//soap:Bulto');
    
                $date_response = $dataXml->xpath('//soap:FechaRespuesta');

                if (in_array($carrier_code, $correos->carriers_codes_international)) {
                    $expedidion_code = array($package_data[0]->CodEnvio);
                } else {
                    $expedidion_code = $dataXml->xpath('//soap:CodExpedicion');
                }
                $db->Execute(
                    "DELETE FROM `"._DB_PREFIX_."correos_preregister_errors` WHERE `id_order` = ".(int) $order->id.""
                );
                $db->Execute(
                    "DELETE FROM `"._DB_PREFIX_."correos_preregister` WHERE `id_order` = ".(int) $order->id.""
                );
                if (empty($shipping_val['insurance_value'])) {
                    $shipping_val['insurance_value'] = 0;
                }
                $tmp_array = array();
                $shipment_code = $package_data[0]->CodEnvio;
                if (count($package_data) > 1) {
                    foreach ($package_data as $package) {
                        $tmp_array[] = $package->CodEnvio;
                    }
                    $shipment_code = implode(",", $tmp_array);
                }
                $weight = 0;
                if (isset($shipping_val['parcel_info'])) {
                    foreach ($shipping_val['parcel_info'] as $parcel_info) {
                        $weight = $weight + $parcel_info['weight'];
                    }
                } else {
                    $weight = $shipping_val['weight'];
                }
                $db->Execute(
                    "INSERT INTO `"._DB_PREFIX_."correos_preregister` 
                    (`id_order`, `id_carrier`, `carrier_code`, `code_expedition`, 
                    `date_response`, `shipment_code`, `weight`, `insurance`) 
                    VALUES (".(int) $order->id.", '".(int) $order->id_carrier."', '".pSQL($carrier_code)."', '".pSQL($expedidion_code[0])."', 
                    '".pSQL(date("Y-m-d H:i:s", strtotime($date_response[0])))."', '".pSQL($shipment_code)."', 
                    '".pSQL(($weight / 1000))."', '".pSQL($shipping_val['insurance_value'] / 100)."')"
                );

                //Set shipping number
                //early versions PS 1.6 don't have that function
                if (method_exists($order, 'setWsShippingNumber')) {
                    if (!empty($expedidion_code[0]) && count($package_data) > 1) {
                        $order->setWsShippingNumber($expedidion_code[0]);
                    } else {
                        $order->setWsShippingNumber($package_data[0]->CodEnvio);
                    }
                } else {
                    $id_order_carrier = Db::getInstance()->getValue('
                        SELECT `id_order_carrier`
                        FROM `'._DB_PREFIX_.'order_carrier`
                        WHERE `id_order` = '.(int) $order->id);
                    if ($id_order_carrier) {
                        $order_carrier = new OrderCarrier($id_order_carrier);
                        if (!empty($expedidion_code[0]) && count($package_data) > 1) {
                            $order_carrier->tracking_number = $expedidion_code[0];
                        } else {
                            $order_carrier->tracking_number = $package_data[0]->CodEnvio;
                        }
                        $order_carrier->update();
                    }
                }
                if ($send_email) {
                    $customer = new Customer((int) $order->id_customer);
                    $carrier = new Carrier((int) $order->id_carrier);
                    self::sendMailinTransit($order, $customer, $package_data[0]->CodEnvio, $carrier->url);
                }
                foreach ($package_data as $package) {
                    //Save Label PDF
                    file_put_contents(
                        "../modules/correos/pdftmp/" . Tools::strtolower($package->CodEnvio) . ".pdf",
                        base64_decode($package->Etiqueta->Etiqueta_pdf->Fichero)
                    );
                }
            } else {
                $error_msg = $dataXml->xpath('//soap:DescError');
                if (is_array($error_msg)) {
                    $error_msg = $error_msg[0];
                }
                if (empty($error_msg)) {
                    $error_msg = $correos->l("Unknown error... code:") . " " . $error_code[0];
                }
                $db->Execute(
                    "INSERT INTO `"._DB_PREFIX_."correos_preregister_errors` (`id_order`, `error`) 
                    VALUES (".(int) $order->id.", '".pSQL(Tools::substr($error_msg, 0, 254))."')"
                );
            }
        } catch (Exception $e) {
            if (!$e->getMessage()) {
                $db->Execute(
                    "INSERT INTO `"._DB_PREFIX_."correos_preregister_errors` (`id_order`, `error`) 
                    VALUES (".(int) $order->id.", '".pSQL($correos->l("Not possible to connect to Correos Server"))."')"
                );
            } else {
                $db->Execute(
                    "INSERT INTO `"._DB_PREFIX_."correos_preregister_errors` (`id_order`, `error`) 
                    VALUES (".(int) $order->id.", '".pSQL(Tools::substr($e->getMessage(), 0, 254))."')"
                );
            }
        }
    }
    public static function updateOfficeInfoFromOrder($params)
    {
        $order = new Order((int) Tools::getValue('id_order'));
        $row = Db::getInstance()->getRow(
            "SELECT `data` FROM `"._DB_PREFIX_."correos_request` 
            WHERE `id_cart` = ".(int) $order->id_cart." AND `id_carrier` = ".(int) $order->id_carrier
        );
        //$request_data = CorreosCommon::getRequestData($cart->id, $carrier['id']);
        if ($row) {
            $data = Tools::jsonDecode($row['data']);
            $data['id_collection_office'] = $params['selected_office'];
            Db::getInstance()->Execute(
                "UPDATE `"._DB_PREFIX_."correos_request` SET `data` = '".pSQL(Tools::jsonEncode($data))."' 
                WHERE `id_cart` = ".(int) $order->id_cart." AND `id_carrier` = ".(int) $order->id_carrier
            );
        }
    }
    public static function orderCarrierChange()
    {
        $id_order = Tools::getValue('id_order');
        $order = new Order((int)$id_order);
        $old_id_carrier = $order->id_carrier;
        $order->id_carrier = Tools::getValue('new_id_carrier');
        $order->update();
        $new_total_shipping_tax_excl = Tools::getValue('new_total_shipping_tax_excl');
        $new_total_shipping_tax_incl = Tools::getValue('new_total_shipping_tax_incl');
        $sql = 'SELECT * FROM `'._DB_PREFIX_.'orders` WHERE `id_order` = '.(int) $id_order;
        if ($results = Db::getInstance()->ExecuteS($sql)) {
            foreach ($results as $row) {
                $total_products_wt = (float)$row['total_products_wt'];
                $total_products = (float)$row['total_products'];
                $total_discounts_tax_incl = (float)$row['total_discounts_tax_incl'];
                $total_discounts_tax_excl = (float)$row['total_discounts_tax_excl'];
            }
        } else {
            return false;
        }
        $new_total_paid_real = (float)$total_products_wt + $new_total_shipping_tax_incl - $total_discounts_tax_incl;
        $new_total_paid_tax_excl = (float)$total_products + $new_total_shipping_tax_excl - $total_discounts_tax_excl;
        $new_carrier_tax_rate = Tools::getValue('taxvalue');
        if (!Db::getInstance()->update('orders', array(
          'total_paid' => (float)$new_total_paid_real,
          'total_paid_tax_incl' => (float)$new_total_paid_real,
          'total_paid_tax_excl' => (float)$new_total_paid_tax_excl,
          'total_paid_real' => (float)$new_total_paid_real,
          'total_shipping' => (float)$new_total_shipping_tax_incl,
          'total_shipping_tax_excl' => (float)$new_total_shipping_tax_excl,
          'total_shipping_tax_incl' => (float)$new_total_shipping_tax_incl,
          'carrier_tax_rate' => (float)$new_carrier_tax_rate,
          ), 'id_order="'.(int)$id_order.'"')) {
            return false;
        } else {
            if (!Db::getInstance()->update('order_invoice', array(
            'total_paid_tax_incl' => (float)$new_total_paid_real,
            'total_paid_tax_excl' => (float)$new_total_paid_tax_excl,
            'total_shipping_tax_excl' => (float)$new_total_shipping_tax_excl,
            'total_shipping_tax_incl' => (float)$new_total_shipping_tax_incl,
            ), 'id_order="'.(int)$id_order.'"')) {
                return false;
            }
        }
        $new_order_carrier_weight = (float) str_replace(',', '.', Tools::getValue('new_order_carrier_weight'));
        $id_order_carrier = Db::getInstance()->getValue('
                     SELECT `id_order_carrier`
                     FROM `'._DB_PREFIX_.'order_carrier`
                     WHERE `id_order` = '.(int)$id_order);
        if ( Db::getInstance()->getValue('SELECT `id` FROM `'._DB_PREFIX_.'correos_request`
            WHERE `id_cart` = '.(int)$order->id_cart.' AND `id_carrier` = '.(int)$old_id_carrier)
          ) {
            Db::getInstance()->Execute(
                'UPDATE `'._DB_PREFIX_.'correos_request` SET `id_carrier` = ' .Tools::getValue('new_id_carrier').
                ' WHERE `id_cart` = '.(int)$order->id_cart.' AND `id_carrier` = '.(int)$old_id_carrier
            );
        }
        if ($id_order_carrier) {
            $order_carrier = new OrderCarrier((int) $id_order_carrier);
            $order_carrier->id_carrier = (int) $order->id_carrier;
            $order_carrier->weight = $new_order_carrier_weight;
            $order_carrier->shipping_cost_tax_excl = $new_total_shipping_tax_excl;
            $order_carrier->shipping_cost_tax_incl = $new_total_shipping_tax_incl;
            $order_carrier->update();
        }
        $correos = new Correos();
        $carrier = new Carrier((int) Tools::getValue('new_id_carrier'));
        $result = CorreosCommon::getCarriers(true, "`id_reference` = " . (int) $carrier->id_reference);
        $new_carrier_code = $result['code'];
        
        if (in_array($new_carrier_code, $correos->carriers_codes_homedelivery)) {
            $address = new Address($order->id_address_delivery);
            $inoice_address = new Address((int) $order->id_address_invoice);
            if ($address->address1 != $inoice_address->address1 ) {
                $address->firstname = $inoice_address->firstname;
                $address->lastname = $inoice_address->lastname;
                $address->address1 = $inoice_address->address1;
                $address->address2 = $inoice_address->address2;
                $address->postcode = $inoice_address->postcode;
                $address->city = $inoice_address->city ;
                $address->phone = $inoice_address->phone;
                $address->phone_mobile = $inoice_address->phone_mobile;
                $address->update();
            }
        }
        return true;
    }
    public static function getCollectionDetails($id_collection)
    {
        $row =  Db::getInstance()->getRow(
                'SELECT `collection_data`, `reference_code`, `confirmation_code`, `status` '.
                'FROM `'._DB_PREFIX_.'correos_collection` WHERE id = ' . (int)$id_collection
        );
        $collection_data = Tools::jsonDecode($row['collection_data']);
        $collection_data->reference = $row['reference_code'];
        $collection_data->confirmation_code = $row['confirmation_code'];
        $collection_data->status = $row['status'];
        if (!isset($collection_data->orders) && $collection_data->label_print == 'S') {

            $orders_data = Db::getInstance()->executeS(
                "SELECT `id_order`, `code_expedition` AS `expedition_code`, null AS `order_reference`, `shipment_code` AS `shipping_code`, ".
                "(SELECT date_add FROM "._DB_PREFIX_."orders o WHERE o.id_order = cp.id_order) AS order_date ".
                "FROM `"._DB_PREFIX_."correos_preregister` cp WHERE `id_collection` = ".
                        (int)$id_collection
                );

            foreach ($orders_data as $order_data) {
                $collection_data->orders[] = (object)$order_data; 
            }
        }
            
         /*
        $collection_data->orders =  Db::getInstance()->getValue(
            "SELECT GROUP_CONCAT(`id_order` SEPARATOR ', ') FROM `"._DB_PREFIX_."correos_preregister` WHERE `id_collection` = " . (int)$id_collection
        );
        */
        return $collection_data;
    }
    public static function getDefaultShippingValues() {
        return array(
        'sender_firstname'      => '',
        'sender_lastname1'      => '',
        'sender_lastname2'      => '',
        'sender_dni'            => '',
        'sender_company'        => '',
        'sender_contact_person' => '',
        'sender_address'        => '',
        'sender_city'           => '',
        'sender_state'          => '',
        'sender_cp'             => '',
        'sender_phone'          => '',
        'sender_email'          => '',
        'sender_mobile'         => '',
        'delivery_mode'         => 'ST',
        'weight'                => '',
        'long'                  => '',
        'width'                 => '',
        'height'                => '',
        'cashondelivery_type'   => '',
        'cashondelivery_value'  => '',
        'cashondelivery_bankac' => '',
        'insurance_value'       => '',
        'email'                 => '',
        'mobile'                => '',
        'phone'                 => '',
        'mobile_lang'           => '1',
        'order_reference'       => '',
        'customer_firstname'    => '',
        'customer_lastname1'    => '',
        'customer_lastname2'    => '',
        'customer_company'      => '',
        'delivery_address'      => '',
        'delivery_address2'     => '',
        'delivery_address_other' => '',
        'delivery_city'         => '',
        'delivery_postcode'     => '',
        'delivery_zip'          => '',
        'delivery_state'        => '',
        'country_iso'           => 'ES',
        'homepaq_token'         => '',
        'homepaq_code'          => '',
        'homepaq_admission'     => '',
        'id_office'             => '',
        'id_schedule'           => '',
        'customs_type'          => '',
        'customs_comercial'     => '',
        'customs_duacorreos'    => '',
        'customs_fra500'        => '',
        'customs_product_qty'   => '',
        'customs_product_weight' => '',
        'customs_product_value' => '',
        'customs_description'   => '',
        'observations'          => ''
        );
    }
    public static function requestRMALabel()
    {
        
        $correos = new Correos();
        $correos_config = CorreosCommon::getCorreosConfiguration();
        $order = new Order((int)Tools::getValue('customer_id_order'));
        $carrier = new Carrier((int) $order->id_carrier);

        //delete previous files
        $shipping_code = Db::getInstance()->getValue(
            'SELECT `shipment_code` FROM `'._DB_PREFIX_.'correos_rma` WHERE id_order = ' . (int) $order->id
        );
        if ($shipping_code) {
            $shipping_code_array = explode(',', $shipping_code);
            foreach ($shipping_code_array as $shipping_code) {
                if (file_exists("../modules/correos/pdftmp/" . Tools::strtolower($shipping_code). ".pdf")) {
                     unlink("../modules/correos/pdftmp/" . $shipping_code. ".pdf");
                }
                if (file_exists("../modules/correos/pdftmp/customs_" . Tools::strtolower($shipping_code). ".pdf")) {
                     unlink("../modules/correos/pdftmp/customs_" . $shipping_code. ".pdf");
                }
            }
            Db::getInstance()->Execute(
               "DELETE FROM `"._DB_PREFIX_."correos_rma` WHERE `id_order` = ".(int) $order->id
            );
        }
        
        //Second Surname Sender
        $sender_lastname_arr = explode(" ", Tools::getValue('customer_sender_apellidos'));
        $sender_lastname1 = "";
        $sender_lastname2 = "";
        if (count($sender_lastname_arr) > 1) {
            $sender_lastname1 = $sender_lastname_arr[0];
            unset($sender_lastname_arr[0]);
            $sender_lastname2 = implode(" ", $sender_lastname_arr);
        } else {
            $sender_lastname1 = Tools::getValue('customer_sender_apellidos');
        }
        //Second Surname Sender
        $recipient_lastname_arr = explode(" ", Tools::getValue('recipient_apellidos'));
        $recipient_lastname1 = "";
        $recipient_lastname2 = "";
        if (count($recipient_lastname_arr) > 1) {
            $recipient_lastname1 = $recipient_lastname_arr[0];
            unset($recipient_lastname_arr[0]);
            $recipient_lastname2 = implode(" ", $recipient_lastname_arr);
        } else {
            $recipient_lastname1 = Tools::getValue('recipient_apellidos');
        }
        $shipping_val = self::getDefaultShippingValues();

        $shipping_val['sender_firstname'] = Tools::getValue('customer_sender_nombre');
        $shipping_val['sender_lastname1'] = $sender_lastname1;
        $shipping_val['sender_lastname2'] = $sender_lastname2;
        $shipping_val['sender_dni'] = Tools::getValue('customer_sender_dni');
        $shipping_val['sender_company'] = Tools::getValue('customer_sender_empresa');
        $shipping_val['sender_contact_person'] = Tools::getValue('customer_sender_presona_contacto');
        $shipping_val['sender_address'] = Tools::getValue('customer_sender_direccion');
        $shipping_val['sender_city'] = Tools::getValue('customer_sender_localidad');
        $shipping_val['sender_state'] = Tools::getValue('customer_sender_provincia');
        $shipping_val['sender_cp']  = Tools::getValue('customer_sender_cp');
        $shipping_val['sender_phone'] = Tools::getValue('customer_sender_tel_fijo');
        $shipping_val['sender_email']  = Tools::getValue('customer_sender_email');
        $shipping_val['sender_mobile']  = Tools::getValue('customer_sender_movil');
        $shipping_val['email'] = Tools::getValue('recipient_email');
        $shipping_val['mobile'] = Tools::getValue('recipient_movil');
        $shipping_val['phone'] = Tools::getValue('recipient_tel_fijo');
        $shipping_val['order_reference' ] = $order->id .' ' . $order->reference;
        $shipping_val['customer_firstname'] = Tools::getValue('recipient_nombre');
        $shipping_val['customer_lastname1'] = $recipient_lastname1;
        $shipping_val['customer_lastname2'] = $recipient_lastname2;
        $shipping_val['customer_company'] = Tools::getValue('recipient_empresa');
        $shipping_val['delivery_address'] = Tools::getValue('recipient_direccion');
        $shipping_val['delivery_city'] = Tools::getValue('recipient_localidad');
        $shipping_val['delivery_postcode'] = Tools::getValue('recipient_cp');
        $shipping_val['delivery_zip'] = Tools::getValue('recipient_cp');
        $shipping_val['delivery_state'] = Tools::getValue('recipient_provincia');

        $shipment_links = array();
        $shipping_code_array = array();
        foreach (Tools::getValue('rma-parcel-weight') as  $index => $weight) {
            $index++;
            $shipping_val['weight'] = (int)((float)$weight * 1000);
            if (Tools::getValue('rma_customs')) {
                $shipping_val['customs_type'] = '2';
                $shipping_val['customs_comercial'] = 'S';
                $shipping_val['customs_fra500'] = 'N';
                $shipping_val['customs_duacorreos'] = 'N';
                $shipping_val['customs_product_qty'] = 1;
                $shipping_val['customs_product_weight'] = (int)((float)$weight * 1000);
                $shipping_val['customs_product_value'] = str_pad(
                    ((float)str_replace(',','.',Tools::getValue('rma-parcel-value')[$index-1]) * 100),
                        6,
                        "0",
                        STR_PAD_LEFT
                    );
                $shipping_val['customs_description']   = htmlentities(Tools::getValue('rma-parcel-goods-type')[$index-1]);

            }
        
            $xmlSend = CorreosAdmin::prepareXmlOrder($shipping_val, $correos_config, 'S0148');
            //debugging
            //file_put_contents("../modules/correos/request-". $index.".xml", $xmlSend);
            $result = CorreosCommon::sendXmlCorreos('url_data', $xmlSend, true, 'Preregistro');
            //file_put_contents("../modules/correos/response-". $index.".xml", $result);
            try {
                if ($result == '') {
                    return false;
                }
                if (!strstr(Tools::strtolower($result), 'soapenv:envelope')) {
                   //error - xml is not soap. Correos Username may be incorrect.
                    return false;
                }
                $dataXml = simplexml_load_string($result);
                $dataXml->registerXPathNamespace('soapenv', 'http://schemas.xmlsoap.org/soap/envelope/');
                $error = $dataXml->xpath('//soapenv:Fault');
                if (!empty($error)) {
                    return false;
                }

                $dataXml->registerXPathNamespace(
                    'RespuestaPreregistroEnvio',
                    'http://www.correos.es/iris6/services/preregistroetiquetas'
                );

                $error = $dataXml->xpath('//RespuestaPreregistroEnvio:BultoError');
                
                if ($error) {
                    return (string) $error[0]->DescError;
                }

                $package_data = $dataXml->xpath('//RespuestaPreregistroEnvio:Bulto');
                $shipping_code = $package_data[0]->CodEnvio;
                $shipping_code_array[] = $shipping_code;

                //Save label PDF
                file_put_contents(
                    "../modules/correos/pdftmp/" . Tools::strtolower($shipping_code) .".pdf",
                    base64_decode($package_data[0]->Etiqueta->Etiqueta_pdf->Fichero)
                );
                $fileAttachment = array();
                $fileAttachment[] = array(
                    'content' => Tools::file_get_contents("../modules/correos/pdftmp/" . Tools::strtolower($shipping_code) .".pdf"),
                    'name' => $shipping_code.".pdf",
                    'mime' => 'application/pdf'
                );
                $shipment_links[] = str_replace('@', $shipping_code, $carrier->url);
                    

            } catch (Exception $e) {
            }
            
        }
        //Note: watch if is safe to use some many soap calls
        if (Tools::getValue('rma_customs') && !empty($shipping_code_array)) {
            foreach ($shipping_code_array as $shipping_code) {
                self::requestCustomsContent($shipping_code);

                if (file_exists("../modules/correos/pdftmp/customs_" . Tools::strtolower($shipping_code). ".pdf")) {
                    $fileAttachment[] = array(
                        'content' => Tools::file_get_contents("../modules/correos/pdftmp/customs_" . Tools::strtolower($shipping_code) .".pdf"),
                        'name' => "aduana_".$shipping_code.".pdf",
                        'mime' => 'application/pdf'
                    );
                }
            }
        }

        if (!empty($shipping_code_array)) {

            Db::getInstance()->insert('correos_rma', array(
                'id_order' => (int) $order->id,
                'shipment_code' => pSQL(implode(',', $shipping_code_array)),
                'require_customs' => (int)  Tools::getValue('rma_customs')
            ));

            $templateVars = array(
                            '{firstname}' =>  Tools::getValue('customer_sender_nombre'),
                            '{lastname}' => Tools::getValue('customer_sender_apellidos'),
                            '{message}' => Tools::getValue('mail_message') . ". " .
                                $correos->l('Tracking URL'). ":<br>".implode('<br>',$shipment_links),
                            '{order_name}' => $order->getUniqReference()
            );
            @Mail::Send(
                (int)$order->id_lang,
                'order_merchant_comment',
                $correos->l('Parcel Return Label'),
                $templateVars,
                Tools::getValue('customer_sender_email'),
                Tools::getValue('customer_sender_nombre').' '.Tools::getValue('customer_sender_apellidos'),
                null,
                null,
                $fileAttachment,
                null,
                _PS_MAIL_DIR_,
                true,
                (int)$order->id_shop
            );
        }
        

        return true;
        
    }

}