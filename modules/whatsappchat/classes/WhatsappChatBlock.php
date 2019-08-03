<?php
/**
* WhatsApp Chat - Live chat
*
* NOTICE OF LICENSE
*
* This product is licensed for one customer to use on one installation (test stores and multishop included).
* Site developer has the right to modify this module to suit their needs, but can not redistribute the module in
* whole or in part. Any other use of this module constitues a violation of the user agreement.
*
* DISCLAIMER
*
* NO WARRANTIES OF DATA SAFETY OR MODULE SECURITY
* ARE EXPRESSED OR IMPLIED. USE THIS MODULE IN ACCORDANCE
* WITH YOUR MERCHANT AGREEMENT, KNOWING THAT VIOLATIONS OF
* PCI COMPLIANCY OR A DATA BREACH CAN COST THOUSANDS OF DOLLARS
* IN FINES AND DAMAGE A STORES REPUTATION. USE AT YOUR OWN RISK.
*
*  @author    idnovate.com <info@idnovate.com>
*  @copyright 2018 idnovate.com
*  @license   See above
*/

include_once(_PS_MODULE_DIR_.'whatsappchat/classes/array_column.php');

class WhatsappChatBlock extends ObjectModel
{
    public $id_whatsappchatblock;
    public $id_shop;
    public $id_hook;
    public $open_chat;
    public $message;
	public $def_message;
	public $offline_message;
    public $offline_link;
    public $position;
    public $color = '#25d366';
    public $mobile_phone;
    public $active;
    public $only_home;
    public $customer_groups;
    public $chat_group = '';
    public $badge_width;
	public $only_mobile;
	public $only_desktop;
    public $only_tablet;
	public $custom_css;
	public $custom_js;
	public $share_option;
	public $schedule;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'whatsappchatblock',
        'primary' => 'id_whatsappchatblock',
        'multilang' => true,
        'fields' => array(
            'id_shop'           => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'id_hook'           => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
            'open_chat'         => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'message'           => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'lang' => true),
            'def_message'       => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'lang' => true),
            'offline_message'   => array('type' => self::TYPE_STRING, 'lang' => true),
            'offline_link'      => array('type' => self::TYPE_STRING, 'lang' => true),
            'position'          => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
            'color'             => array('type' => self::TYPE_STRING, 'validate' => 'isColor'),
            'mobile_phone'      => array('type' => self::TYPE_STRING, 'size' => 32, 'lang' => true),
            'active'            => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'copy_post' => false),
            'only_home'         => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'copy_post' => false),
            'customer_groups'   => array('type' => self::TYPE_STRING),
            'chat_group'        => array('type' => self::TYPE_STRING),
            'badge_width'       => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'only_mobile'       => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'only_desktop'      => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'only_tablet'       => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'share_option'      => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'schedule'          => array('type' => self::TYPE_STRING),
            'custom_css'        => array('type' => self::TYPE_STRING),
            'custom_js'         => array('type' => self::TYPE_STRING),
        ),
    );

    public function add($autodate = true, $null_values = true)
    {
        $this->id_shop = ($this->id_shop) ? $this->id_shop : Context::getContext()->shop->id;
        return parent::add($autodate, $null_values);
    }

    public function getWhatsappChatByHook($id_hook, $active = false, $from_bo = false, $id_whatsappchatblock = false, $position = false)
    {
        $shopID = ($this->id_shop) ? $this->id_shop : Context::getContext()->shop->id;
        $langID = Context::getContext()->language->id;

        $sql = 'SELECT `'._DB_PREFIX_. bqSQL($this->def['table']).'`.`id_whatsappchatblock`, `message`, `def_message`,
                `offline_message`, `position`, `open_chat`, `mobile_phone`, `color`, `only_home`, `chat_group`, 
                `customer_groups`, `only_mobile`, `share_option`, `schedule`, `only_desktop`, `only_tablet`, 
                `custom_css`, `custom_js`, `offline_link`
            FROM `' . _DB_PREFIX_ . bqSQL($this->def['table']) . '` LEFT JOIN `'
            . _DB_PREFIX_ . bqSQL($this->def['table']) . '_lang` ON (`' . _DB_PREFIX_ . bqSQL($this->def['table'])
            . '`.`id_whatsappchatblock` = `' . _DB_PREFIX_ . bqSQL($this->def['table'])
            . '_lang`.`id_whatsappchatblock` AND `id_lang` = ' . (int)$langID.')'
            . ' WHERE `id_hook` = "' . bqSQL($id_hook) . '"'
			. (!$from_bo ? ' AND `id_shop` = ' . (int)$shopID : '')
			. ($id_whatsappchatblock ? ' AND `' . _DB_PREFIX_ . bqSQL($this->def['table']) . '`.`id_whatsappchatblock` = ' . (int)$id_whatsappchatblock : '')
            . ($active ? ' AND `active` = 1' : '')
            . ($position ? ' AND `position` = "'.$position.'"' : '');

        return Db::getInstance()->executeS($sql);
    }

    public function getWhatsappChats($active = false)
    {
        $sql = 'SELECT *
            FROM `'._DB_PREFIX_.bqSQL($this->def['table']).'`'.($active ? ' WHERE `active` = 1' : '');

        return Db::getInstance()->executeS($sql);
    }
}
