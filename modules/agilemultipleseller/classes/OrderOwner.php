<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
class OrderOwnerCore extends ObjectModel {
        
        public $id_order;

        public $id_owner;

        public $date_add;
    
        public $orders_owner;

        public static $definition = array(
        'table' => 'order_owner',
        'primary' => 'id_order_owner',
        'fields' => array(
            'id_order' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'id_owner' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'date_add' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
        ),
    );

        protected $webserviceParameters = array(
        'objectsNodeName' => 'order_histories',
        'fields' => array(
            'id_owner' => array('xlink_resource' => 'owners'),
            'id_order' => array('xlink_resource' => 'orders'),
        ),
        'objectMethods' => array(
            'add' => 'addWs',
        ),
    );


                public function addWs() {
        $sendemail = (bool) Tools::getValue('sendemail', false);
        $this->changeIdOrderState($this->id_order_state, $this->id_order);

        if ($sendemail) {
                        $context = Context::getContext();
            if ($context->link == null) {
                $protocol_link = (Tools::usingSecureMode() && Configuration::get('PS_SSL_ENABLED')) ? 'https://' : 'http://';
                $protocol_content = (Tools::usingSecureMode() && Configuration::get('PS_SSL_ENABLED')) ? 'https://' : 'http://';
                $context->link = new Link($protocol_link, $protocol_content);
            }
            return $this->addWithemail();
        } else {
            return $this->add();
            }
    }
    
   
    }
