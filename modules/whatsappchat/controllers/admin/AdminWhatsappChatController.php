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

class AdminWhatsappChatController extends ModuleAdminController
{
    protected $delete_mode;
    protected $is_multishop_selected = true;
    protected $_defaultOrderBy = 'id_whatsappchatblock';
    protected $_defaultOrderWay = 'ASC';

    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'whatsappchatblock';
        $this->className = 'WhatsappChatBlock';
        $this->tabClassName = 'AdminWhatsappChat';
        $this->module_name = 'whatsappchat';
        $this->lang = true;

        parent::__construct();

        $this->addRowAction('edit');
        $this->addRowAction('agents');
        $this->addRowAction('delete');

        $this->_orderWay = $this->_defaultOrderWay;

        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?'),
                'icon' => 'icon-trash'
            )
        );

        $this->context = Context::getContext();

        $this->default_form_language = $this->context->language->id;

        $this->fields_options = array(
            'general' => array(
                'title' => $this->l('General configuration'),
                'image' => '../modules/whatsappchat/logo.gif',
                'fields' => array(
                    'WA_CHAT_MOBILE' => array(
                        'title' => $this->l('Mobile phone number'),
                        'desc' => $this->l('Introduce mobile phone number with the international country code, without "+" character.').'<br />'.$this->l('Example: Introduce 341234567 for (+34) 1234567.'),
                        'type' => (version_compare(_PS_VERSION_, '1.5', '>=')) ? 'hidden': 'text',
                        'size' => 15,
                        'visibility' => Shop::CONTEXT_ALL
                    ),
                    'WA_FONT_AWESOME' => array(
                        'title' => $this->l('Use Font Awesome to display icon'),
                        'desc' => $this->l('Enable only if your theme is compatible'),
                        'type' => 'bool',
                        'visibility' => Shop::CONTEXT_ALL
                    ),
                    /*
                    'WA_CHAT_MESSAGE' => array(
                        'title' => $this->l('Default chat message'),
                        'type' => 'textLang',
                        'lang' => true,
                        'size' => 50,
                        'visibility' => Shop::CONTEXT_ALL
                    ),
                    */
                ),
                'submit' => array('title' => $this->l('Save'))
            )
        );

        $this->fields_list = array(
            'id_whatsappchatblock' => array(
                'title' => $this->l('ID'),
                'align' => 'text-center center',
                'class' => 'fixed-width-xs'
            ),
            'active' => array(
                'title' => $this->l('Enabled'),
                'align' => 'text-center',
                'active' => 'status',
                'type' => 'bool',
                'orderby' => false,
                'filter_key' => 'a!active'
            ),
            'mobile_phone' => array(
                'title' => $this->l('Mobile phone'),
                'align' => 'text-center center',
            ),
            'id_hook' => array(
                'title' => $this->l('Hook'),
                'callback' => 'printHookTranslation',
            ),
            /*
            'message' => array(
                'title' => $this->l('Message')
            ),
            */
            'position' => array(
                'title' => $this->l('Position'),
                'align' => 'text-center center',
                'callback' => 'printPositionTranslation',
            ),
            /*
            'open_chat' => array(
                'title' => $this->l('Open chat'),
                'align' => 'text-center center',
                'callback' => 'printOpenChatIcon',
            ),
            */
            'only_mobile' => array(
                'title' => $this->l('Show on mobile'),
                'align' => 'text-center center',
                'callback' => 'printOnlyMobileIcon',
            ),
            'only_desktop' => array(
                'title' => $this->l('Show on desktop'),
                'align' => 'text-center center',
                'callback' => 'printOnlyDesktopIcon',
            ),
            'only_tablet' => array(
                'title' => $this->l('Show on tablet'),
                'align' => 'text-center center',
                'callback' => 'printOnlyTabletIcon',
            ),
            /*
            'only_home' => array(
                'title' => $this->l('Only home'),
                'align' => 'text-center center',
                'callback' => 'printOnlyHomeIcon',
            ),
            */
            'chat_group' => array(
                'title' => $this->l('Chat group'),
                'align' => 'text-center center',
                'callback' => 'printChatGroupIcon',
            ),
            'agents' => array(
                'title' => $this->l('Agents'),
                'align' => 'text-center center',
                'callback' => 'printAgentsIcon',
            ),
            'color' => array(
                'title' => $this->l('Preview'),
                'align' => 'text-center center',
                'callback' => 'printColor',
            ),
        );

        $this->shopLinkType = 'shop';

        if (Shop::isFeatureActive() && (Shop::getContext() == Shop::CONTEXT_ALL || Shop::getContext() == Shop::CONTEXT_GROUP)) {
            $this->is_multishop_selected = false;
        }

        $this->tpl_vars = array(
            'icon' => 'icon-bars',
        );
        $this->context->smarty->assign($this->tpl_vars);
    }

    public function init()
    {
        parent::init();
        if (!$this->isBoLogged()) {
            die(Tools::jsonEncode(array('whatsappchat_response' => '[WhatsAppChat] Permission denied.')));
        }
        if (Tools::isSubmit('method')) {
            switch (Tools::getValue('method')) {
                case 'getCustomerMobilePhone':
                    if (Tools::getIsset('id_order') && $id_order = Tools::getValue('id_order')) {
                        $order = new Order((int)$id_order);
                        $id_customer = $order->id_customer;
                    } else {
                        $id_customer = Tools::getValue('id_customer');
                    }
                    $address_id = Address::getFirstCustomerAddressId((int)$id_customer, true);
                    if ($address_id > 0) {
                        $address = new Address((int)$address_id);
                        $phone = $address->phone_mobile;
                        if (!Validate::isPhoneNumber($phone) || $phone == '') {
                            $phone = $address->phone;
                            if (!Validate::isPhoneNumber($phone) || $phone == '') {
                                die(Tools::jsonEncode(array('whatsappchat_response' => array(
                                    'code' => 'NOK',
                                    'url'  => '',
                                    'msg'  => Translate::getModuleTranslation($this->module_name, 'Not a valid phone number or this customer has no mobile phone.', $this->tabClassName)
                                )
                                )));
                            }
                        }
                        $module = new WhatsAppChat();
                        $phone = $module->formatMobilePhoneForWhatsapp($phone, $address->id_country);
                        die(Tools::jsonEncode(array('whatsappchat_response' => array(
                            'code' => 'OK',
                            'url'  => $module->getWhatsappUrl($phone),
                            'msg'  => ''
                        )
                        )));
                    } else {
                        die(Tools::jsonEncode(array('whatsappchat_response' => array(
                            'code' => 'NOK',
                            'url'  => '',
                            'msg'  => Translate::getModuleTranslation($this->module_name, 'Not a valid phone number or this customer has no mobile phone.', $this->tabClassName)
                        )
                        )));
                    }
                    break;
                default:
                    break;
            }
        }
    }

    public function printHookTranslation($value)
    {
        $whatsappchat = new WhatsappChat();
        $hooks = $whatsappchat->getAvailableHooks();
        $key = array_search($value, array_column($hooks, 'id'));

        return $hooks[$key]['name'];
    }

    public function printPositionTranslation($value)
    {
        switch ($value) {
            case 'left':
                $value = $this->l('Left');
                break;
            case 'center':
                $value = $this->l('Center');
                break;
            case 'right':
                $value = $this->l('Right');
                break;
            case 'bottom-left':
                $value = $this->l('Bottom left');
                break;
            case 'bottom-right':
                $value = $this->l('Bottom right');
                break;
            case 'top-right':
                $value = $this->l('Top right');
                break;
            case 'top-left':
                $value = $this->l('Top left');
                break;
        }

        return $value;
    }

    public function printColor($value, $conf)
    {
        if (version_compare(_PS_VERSION_, '1.5', '<')) {
            return $value;
        }
        $module = new WhatsAppChat();
        return $module->displayBlock($conf['id_hook'], true, $conf['id_whatsappchatblock']);
    }

    public function printOpenChatIcon($value)
    {
        if (version_compare(_PS_VERSION_, '1.6', '>=')) {
            return '<a class="list-action-enable '.($value ? 'action-enabled' : 'action-disabled').'">'.($value ? '<i class="icon-check"></i>' : '<i class="icon-remove"></i>').'</a>';
        } else {
            return '<a>'.($value ? '<img src="../img/admin/enabled.gif" />' : '<img src="../img/admin/disabled.gif" />').'</a>';
        }
    }

    public function printOnlyMobileIcon($value)
    {
        if (version_compare(_PS_VERSION_, '1.6', '>=')) {
            return '<a class="list-action-enable '.($value ? 'action-enabled' : 'action-disabled').'">'.($value ? '<i class="icon-check"></i>' : '<i class="icon-remove"></i>').'</a>';
        } else {
            return '<a>'.($value ? '<img src="../img/admin/enabled.gif" />' : '<img src="../img/admin/disabled.gif" />').'</a>';
        }
    }

    public function printOnlyDesktopIcon($value)
    {
        if (version_compare(_PS_VERSION_, '1.6', '>=')) {
            return '<a class="list-action-enable '.($value ? 'action-enabled' : 'action-disabled').'">'.($value ? '<i class="icon-check"></i>' : '<i class="icon-remove"></i>').'</a>';
        } else {
            return '<a>'.($value ? '<img src="../img/admin/enabled.gif" />' : '<img src="../img/admin/disabled.gif" />').'</a>';
        }
    }

    public function printOnlyTabletIcon($value)
    {
        if (version_compare(_PS_VERSION_, '1.6', '>=')) {
            return '<a class="list-action-enable '.($value ? 'action-enabled' : 'action-disabled').'">'.($value ? '<i class="icon-check"></i>' : '<i class="icon-remove"></i>').'</a>';
        } else {
            return '<a>'.($value ? '<img src="../img/admin/enabled.gif" />' : '<img src="../img/admin/disabled.gif" />').'</a>';
        }
    }

    public function printOnlyHomeIcon($value)
    {
        if (version_compare(_PS_VERSION_, '1.6', '>=')) {
            return '<a class="list-action-enable '.($value ? 'action-enabled' : 'action-disabled').'">'.($value ? '<i class="icon-check"></i>' : '<i class="icon-remove"></i>').'</a>';
        } else {
            return '<a>'.($value ? '<img src="../img/admin/enabled.gif" />' : '<img src="../img/admin/disabled.gif" />').'</a>';
        }
    }

    public function printChatGroupIcon($value)
    {
        if (version_compare(_PS_VERSION_, '1.6', '>=')) {
            return '<a class="list-action-enable '.($value != '' ? 'action-enabled' : 'action-disabled').'">'.($value != '' ? '<i class="icon-check"></i>' : '<i class="icon-remove"></i>').'</a>';
        } else {
            return '<a>'.($value != '' ? '<img src="../img/admin/enabled.gif" />' : '<img src="../img/admin/disabled.gif" />').'</a>';
        }
    }

    public function initContent()
    {
        if ($this->action == 'select_delete') {
            $this->context->smarty->assign(array(
                'delete_form' => true,
                'url_delete' => htmlentities($_SERVER['REQUEST_URI']),
                'boxes' => $this->boxes,
            ));
        }

        $warningError = '';
        if ($warnings = $this->module->getWarnings(false)) {
            $warningError = $this->module->displayError($warnings);
        }

        parent::initContent();

        if (Tools::isSubmit('submitPremiumFlatRate') || Tools::isSubmit('submitPremiumFlatRateCarriersPrices') || Tools::isSubmit('submitPremiumFlatRateCustomer')) {
            //Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules').'&configure='.$this->module_name.'&tab_module='.$this->tab.'&conf=4&module_name='.$this->module_name);
            return Tools::redirectAdmin('index.php?controller=AdminWhatsappChatAgent&token='.Tools::getAdminTokenLite('AdminWhatsappChatAgent'));
        }

        if (version_compare(_PS_VERSION_, '1.6', '>=')) {
            $whatsapp = new WhatsAppChat();
            $this->context->smarty->assign(array(
                'this_path'     => $this->module->getPathUri(),
                'support_id'    => $whatsapp->addons_id_product
            ));

            $available_iso_codes = array('en', 'es');
            $default_iso_code = 'en';
            $template_iso_suffix = in_array($this->context->language->iso_code, $available_iso_codes) ? $this->context->language->iso_code : $default_iso_code;
            $this->content .= $this->context->smarty->fetch($this->module->getLocalPath().'views/templates/admin/company/information_'.$template_iso_suffix.'.tpl');
        }

        $this->context->smarty->assign(array(
            'content' => $warningError.$this->content,
        ));
    }

    public function initToolbar()
    {
        parent::initToolbar();

        if (!$this->is_multishop_selected) {
            unset($this->toolbar_btn['new']);
        }
    }

    public function initPageHeaderToolbar()
    {
        parent::initPageHeaderToolbar();

        if (empty($this->display)) {
            $this->page_header_toolbar_btn['desc-module-new'] = array(
                'href' => 'index.php?controller='.$this->tabClassName.'&add'.$this->table.'&token='.Tools::getAdminTokenLite($this->tabClassName),
                'desc' => $this->l('New'),
                'icon' => 'process-icon-new'
            );

            $this->page_header_toolbar_btn['desc-module-translate'] = array(
                'href' => '#',
                'desc' => $this->l('Translate'),
                'modal_target' => '#moduleTradLangSelect',
                'icon' => 'process-icon-flag'
            );

            $this->page_header_toolbar_btn['desc-module-hook'] = array(
                'href' => 'index.php?tab=AdminModulesPositions&token='.Tools::getAdminTokenLite('AdminModulesPositions').'&show_modules='.Module::getModuleIdByName('whatsappchat'),
                'desc' => $this->l('Manage hooks'),
                'icon' => 'process-icon-anchor'
            );
        }

        if (!$this->is_multishop_selected) {
            unset($this->page_header_toolbar_btn['desc-module-new']);
        }
    }

    public function initModal()
    {
        parent::initModal();

        $languages = Language::getLanguages(false);
        $translateLinks = array();

        if (version_compare(_PS_VERSION_, '1.7.2.1', '>=')) {
            $module = Module::getInstanceByName($this->module_name);
            $isNewTranslateSystem = $module->isUsingNewTranslationSystem();
            $link = Context::getContext()->link;
            foreach ($languages as $lang) {
                if ($isNewTranslateSystem) {
                    $translateLinks[$lang['iso_code']] = $link->getAdminLink('AdminTranslationSf', true, array(
                        'lang' => $lang['iso_code'],
                        'type' => 'modules',
                        'selected' => $module->name,
                        'locale' => $lang['locale'],
                    ));
                } else {
                    $translateLinks[$lang['iso_code']] = $link->getAdminLink('AdminTranslations', true, array(), array(
                        'type' => 'modules',
                        'module' => $module->name,
                        'lang' => $lang['iso_code'],
                    ));
                }
            }
        }

        $this->context->smarty->assign(array(
            'trad_link' => 'index.php?tab=AdminTranslations&token='.Tools::getAdminTokenLite('AdminTranslations').'&type=modules&module='.$this->module_name.'&lang=',
            'module_languages' => $languages,
            'module_name' => $this->module_name,
            'translateLinks' => $translateLinks,
        ));

        $modal_content = $this->context->smarty->fetch('controllers/modules/modal_translation.tpl');

        $this->modals[] = array(
            'modal_id' => 'moduleTradLangSelect',
            'modal_class' => 'modal-sm',
            'modal_title' => $this->l('Translate this module'),
            'modal_content' => $modal_content
        );
    }

    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);

        $this->context->controller->addJS(_MODULE_DIR_.$this->module->name.'/views/js/whatsappchat_back.js');
    }

    public function processAdd()
    {
        if (Tools::getValue('submitFormAjax')) {
            $this->redirect_after = false;
        }
        $_POST['customer_groups'] = (is_array(Tools::getValue('customer_groups')) ? (in_array('all', Tools::getValue('customer_groups')) ? 'all' : implode(';', Tools::getValue('customer_groups'))) : (Tools::getValue('customer_groups') == '' ? 'all' : Tools::getValue('customer_groups')));
        $_POST['color'] = (Tools::getValue('color') == '' ? '#25d366' : Tools::getValue('color'));
        $_POST['chat_group'] = (Tools::getValue('chat_group') == '' ? '' : Tools::getValue('chat_group'));
        return parent::processAdd();
    }

    public function processUpdate()
    {
        if (Validate::isLoadedObject($this->object)) {
            $_POST['customer_groups'] = (is_array(Tools::getValue('customer_groups')) ? (in_array('all', Tools::getValue('customer_groups')) ? 'all' : implode(';', Tools::getValue('customer_groups'))) : (Tools::getValue('customer_groups') == '' ? 'all' : Tools::getValue('customer_groups')));
            $_POST['color'] = (Tools::getValue('color') == '' ? '#25d366' : Tools::getValue('color'));
            $_POST['chat_group'] = (Tools::getValue('chat_group') == '' ? '' : Tools::getValue('chat_group'));
            if (version_compare(_PS_VERSION_, '1.6.1', '<')) {
                $whatsappchatblock = new WhatsappChatBlock((int)$this->object->id_whatsappchatblock);
                foreach (Language::getIsoIds(false) as $lang) {
                    $id_lang = $lang['id_lang'];
                    $whatsappchatblock->mobile_phone[$id_lang] = Tools::getValue('mobile_phone_'.$id_lang);
                }
                $whatsappchatblock->save();
            }
        } else {
            $this->errors[] = Tools::displayError('An error occurred while loading the object.');
        }
        return parent::processUpdate();
    }

    public function postProcess()
    {
        if (Tools::isSubmit('submitAdd'.$this->table)) {
            if (($object = $this->loadObject(true)) || Validate::isLoadedObject($object)) {
                if (version_compare(_PS_VERSION_, '1.6.1', '<')) {
                    $object = parent::postProcess();
                    $whatsappchatblock = new WhatsappChatBlock((int)$object->id);
                    foreach (Language::getIsoIds(false) as $lang) {
                        $id_lang = $lang['id_lang'];
                        $whatsappchatblock->mobile_phone[$id_lang] = Tools::getValue('mobile_phone_'.$id_lang);
                    }
                    $whatsappchatblock->save();
                } else {
                    return parent::postProcess();
                }
            } else {
                return parent::postProcess();
            }
        } else {
            return parent::postProcess();
        }
    }

    public function renderForm()
    {
        if (!($object = $this->loadObject(true))) {
            return '';
        }

        $id_lang = (int)$this->context->language->id;

        $this->multiple_fieldsets = true;
        $this->default_form_language = $id_lang;

        $whatsappchat = new WhatsappChat();

        $this->fields_form[]['form'] = array(
            'legend' => array(
                'title' => $this->l('Block configuration'),
                'icon' => 'icon-wrench'
            ),
            'input' => array(
                array(
                    'type'  => 'text',
                    'label' => $this->l('Mobile phone number'),
                    'name'  => 'mobile_phone',
                    'lang' => true,
                    'desc' => $this->l('Introduce mobile phone number with the international country code, without "+" character.').'<br />'.$this->l('Example: Introduce 341234567 for (+34) 1234567.'),
                    'col'   => 4,
                    'class' => 't',
                ),
                array(
                    'type'  => 'text',
                    'label' => $this->l('Chat group Id'),
                    'name'  => 'chat_group',
                    'desc' => array($this->l('Identification of the WhatsApp chat group. If defined, will open the group and will offer  to the customer to join it.'),
                        $this->l('You can obtain this Id going to the Info group - add participant - Invite to group via link. You can find identification needed in https://chat.whatsapp.com/xxxxxx where xxxxxx it is the Id.')
                    ),
                    'col'   => 3,
                    'class' => 't',
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Hook'),
                    'name' => 'id_hook',
                    'class' => 't fixed-width-xxl',
                    'options' => array(
                        'query' => $whatsappchat->getAvailableHooks(),
                        'id' => 'id',
                        'name' => 'name',
                    ),
                ),
                array(
                    'col' => 9,
                    'type' => 'free',
                    'name' => 'hook_position',
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Position'),
                    'name' => 'position',
                    'class' => 't',
                    'col' => '4',
                    'options' => array(
                        'query' => array(
                            array(
                                'id' => 'bottom-left',
                                'name' => $this->l('Bottom left')
                            ),
                            array(
                                'id' => 'bottom-right',
                                'name' => $this->l('Bottom right')
                            ),
                            array(
                                'id' => 'left',
                                'name' => $this->l('Left')
                            ),
                            array(
                                'id' => 'center',
                                'name' => $this->l('Center')
                            ),
                            array(
                                'id' => 'right',
                                'name' => $this->l('Right')
                            ),
                            array(
                                'id' => 'top-left',
                                'name' => $this->l('Top left')
                            ),
                            array(
                                'id' => 'top-right',
                                'name' => $this->l('Top right')
                            )
                        ),
                        'id' => 'id',
                        'name' => 'name',
                    ),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Message to display'),
                    'name' => 'message',
                    'size' => 40,
                    'col' => '3',
                    'lang' => true,
                    'desc' => $this->l('WhatsApp chat button text.'),
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Color'),
                    'name' => 'color',
                    'size' => 30,
                    'desc' => array(
                        $this->l('Choose a color with the color picker, or enter an HTML color (e.g. "lightblue", "#CC6600").'),
                        $this->l('Leave blank for default WhatsApp color.')
                    )
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Customer group(s)'),
                    'name' => 'customer_groups[]',
                    'multiple' => true,
                    'options' => array(
                        'query' => $whatsappchat->getCustomerGroups($this->context->language->id),
                        'id' => 'id_group',
                        'name' => 'name',
                    ),
                    'desc' => $this->l('Select customer groups to show this chat button.')
                ),
                /*array(
                    'type' => (version_compare(_PS_VERSION_, '1.6', '>=')) ? 'switch' : 'radio',
                    'label' => $this->l('Open chat in a new window'),
                    'name' => 'open_chat',
                    'class' => 't',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'open_chat_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'open_chat_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                    'desc' => $this->l('If enabled, a chat will be opened in a new window.')
                ),*/
                array(
                    'type' => 'text',
                    'label' => $this->l('Predefined message to send'),
                    'name' => 'def_message',
                    'size' => 40,
                    'col' => '3',
                    'lang' => true,
                    'desc' => $this->l('Predefined message to open WhatsApp chat.'),
                ),
                array(
                    'type' => (version_compare(_PS_VERSION_, '1.6', '>=')) ? 'switch' : 'radio',
                    'label' => $this->l('Show on mobile'),
                    'name' => 'only_mobile',
                    'class' => 't',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'only_mobile_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'only_mobile_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                    'desc' => $this->l('Show on mobile devices.')
                ),
                array(
                    'type' => (version_compare(_PS_VERSION_, '1.6', '>=')) ? 'switch' : 'radio',
                    'label' => $this->l('Show on desktop'),
                    'name' => 'only_desktop',
                    'class' => 't',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'only_desktop_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'only_desktop_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                    'desc' => $this->l('Show on desktop devices.')
                ),
                array(
                    'type' => (version_compare(_PS_VERSION_, '1.6', '>=')) ? 'switch' : 'radio',
                    'label' => $this->l('Show on tablet'),
                    'name' => 'only_tablet',
                    'class' => 't',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'only_tablet_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'only_tablet_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                    'desc' => $this->l('Show on tablet devices.')
                ),
                array(
                    'type' => (version_compare(_PS_VERSION_, '1.6', '>=')) ? 'switch' : 'radio',
                    'label' => $this->l('Show only at home page'),
                    'name' => 'only_home',
                    'class' => 't',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'only_home_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'only_home_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                    'desc' => $this->l('Show only at home page, not on all pages.')
                ),
                array(
                    'type' => (version_compare(_PS_VERSION_, '1.6', '>=')) ? 'switch' : 'radio',
                    'label' => $this->l('Enable share option?'),
                    'name' => 'share_option',
                    'class' => 't',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'share_option_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'share_option_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                    'desc' => array($this->l('With this option enabled, message text from WhatsApp chat opened will be filled with current URL address.'),
                        $this->l('With mobile phone number filled above, URL address will be shared to this number. Without mobile phone, will be shared with customer WhatsApp contact list.'))
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Custom CSS'),
                    'name' => 'custom_css',
                    'cols' => 40,
                    'rows' => 5,
                    'desc' => $this->l('Custom CSS styles. This will override other defined css classes.')
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Custom JS'),
                    'name' => 'custom_js',
                    'cols' => 40,
                    'rows' => 5,
                    'desc' => $this->l('Custom JavaScript code. For example, you can add here Google Analytics code to track chat button event clicks.')
                ),
                array(
                    'type' => 'free',
                    'label' => $this->l('Schedule'),
                    'name' => 'schedule',
                    'hint' => $this->l('Select days of week and hours to show this WhatsApp chat contact button.')
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Offline message'),
                    'name' => 'offline_message',
                    'size' => 40,
                    'col' => '3',
                    'lang' => true,
                    'desc' => $this->l('Offline message to show out of time. Leave blank to not show the button out of time.'),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Offline link'),
                    'name' => 'offline_link',
                    'size' => 40,
                    'col' => '3',
                    'lang' => true,
                    'desc' => $this->l('Offline link to go when out of time (contact page, for example). Leave blank to not link.'),
                ),
                array(
                    'type' => (version_compare(_PS_VERSION_, '1.6', '>=')) ? 'switch' : 'radio',
                    'label' => $this->l('Enabled'),
                    'name' => 'active',
                    'class' => 't',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                    'desc' => $this->l('Enable or disable this WhatsApp chat.')
                ),
                array(
                    'col' => 9,
                    'type' => 'free',
                    'name' => 'general_help',
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'type' => 'submit',
            ),
        );

        $this->context->smarty->assign(array(
            'languages' => Language::getLanguages(),
            'default_form_language' => $this->default_form_language,
        ));

        if ($object->id) {
            $this->fields_value =  array(
                'id_hook' => $object->id_hook,
                'message' => $object->message,
                'mobile_phone' => $object->mobile_phone,
            );
            $this->context->smarty->assign(array(
                'schedule' => $object->schedule,
            ));
        } else {
            $this->context->smarty->assign(array(
                'schedule' => '',
                //'open_chat' => true,
                'active' => true,
                'only_desktop' => true,
                'only_mobile' => true,
                'only_tablet' => true
            ));
        }

        $this->fields_value =  array(
            'hook_position' => '<a id="hook-position" target="_blank">'.$this->l('View hook position 🔗').'</a>',
            'general_help' => '<p id="help-block">'.$this->l('Note: Leaving blank "Phone" and "Message to display" (both) in a specific language, will hide this chat button for this language.').'</p>',
            'schedule' => $this->context->smarty->fetch($this->module->getLocalPath().'views/templates/admin/schedule.tpl'),
            'customer_groups[]' => explode(';', $object->customer_groups)
        );

        return parent::renderForm();
    }

    public function displayAgentsLink($token, $id)
    {
        $module = new WhatsAppChat();
        return $module->displayAgentsLink($token, $id);
    }

    private function isBoLogged()
    {
        $cookie = new Cookie('psAdmin', '', (int)Configuration::get('PS_COOKIE_LIFETIME_BO'));
        $employee = new Employee((int)$cookie->id_employee);
        if (Validate::isLoadedObject($employee) && $employee->checkPassword((int)$cookie->id_employee, $cookie->passwd)
            && (!isset($cookie->remote_addr) || $cookie->remote_addr == ip2long(Tools::getRemoteAddr()) || !Configuration::get('PS_COOKIE_CHECKIP'))) {
            return true;
        } else {
            return false;
        }
    }
}
