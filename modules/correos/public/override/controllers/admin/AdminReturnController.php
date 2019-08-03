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

class AdminReturnController extends AdminReturnControllerCore
{
    public function renderForm()
    {
        if (Module::isInstalled('correos')) {
            $correos = Module::getInstanceByName('correos');
            if ($correos->active == 1 && $this->object->state == 2) {
                $this->fields_form_override[] = array(
                    'type' => 'free',
                    'label' => $correos->l('RMA Labels', 'adminorder'),
                    'name' => 'order_link',
                    'size' => '',
                    'class' => 'normal-text',
                    'required' => false,
                );
                $this->object->order_link =  "<a href='" .$this->context->link->getAdminLink('AdminOrders')."&vieworder&id_order=". $this->object->id_order . "#correos-block'>".
                                            $correos->l('Manage the return from the order')."</a>";
            }
        }
        return parent::renderForm();
    }
}