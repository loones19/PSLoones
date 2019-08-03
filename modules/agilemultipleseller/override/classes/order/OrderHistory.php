<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
class OrderHistory extends OrderHistoryCore
{
		public function sendEmail($order, $template_vars = false)
    {
		$sql = '
            SELECT osl.`template`, c.`lastname`, c.`firstname`, osl.`name` AS osname, c.`email`, os.`module_name`, os.`id_order_state`, os.`pdf_invoice`, os.`pdf_delivery`
            FROM `'._DB_PREFIX_.'order_history` oh
                LEFT JOIN `'._DB_PREFIX_.'orders` o ON oh.`id_order` = o.`id_order`
                LEFT JOIN `'._DB_PREFIX_.'customer` c ON o.`id_customer` = c.`id_customer`
                LEFT JOIN `'._DB_PREFIX_.'order_state` os ON oh.`id_order_state` = os.`id_order_state`
                LEFT JOIN `'._DB_PREFIX_.'order_state_lang` osl ON (os.`id_order_state` = osl.`id_order_state` AND osl.`id_lang` = o.`id_lang`)
            WHERE oh.`id_order_history` = '.(int)$this->id.' AND os.`send_email` = 1';

        $result = Db::getInstance()->getRow($sql);

		
        if (isset($result['template']) && Validate::isEmail($result['email'])) {
            ShopUrl::cacheMainDomainForShop($order->id_shop);

            $topic = $result['osname'];
            $data = array(
                '{lastname}' => $result['lastname'],
                '{firstname}' => $result['firstname'],
                '{id_order}' => (int)$this->id_order,
                '{order_name}' => $order->getUniqReference()
            );

            if ($result['module_name']) {
				if($result['module_name'] == 'ps_wirepayment' && Module::isInstalled('agilebankwire'))$result['module_name'] = 'agilebankwire';
				if($result['module_name'] == 'ps_checkpayment' && Module::isInstalled('agilepaybycheque'))$result['module_name'] = 'agilepaybycheque';
				$module = Module::getInstanceByName($result['module_name']);			
				if(Module::isInstalled('agilemultipleseller') && in_array($result['module_name'], array('agilebankwire','agilepaybycheque')))
				{				
					$id_seller = AgileSellerManager::getObjectOwnerID('order', (int)$this->id_order);
					$module->extra_mail_vars = $module->getExtraMailVAars($id_seller);
				}								
				
				if (Validate::isLoadedObject($module) && isset($module->extra_mail_vars) && is_array($module->extra_mail_vars)) {
                    $data = array_merge($data, $module->extra_mail_vars);
                }
            }

            if ($template_vars) {
                $data = array_merge($data, $template_vars);
            }

            $data['{total_paid}'] = Tools::displayPrice((float)$order->total_paid, new Currency((int)$order->id_currency), false);

            if (Validate::isLoadedObject($order)) {
                                if (($result['pdf_invoice'] || $result['pdf_delivery'])) {
                    $context = Context::getContext();
                    $invoice = $order->getInvoicesCollection();
                    $file_attachement = array();

                    if ($result['pdf_invoice'] && (int)Configuration::get('PS_INVOICE') && $order->invoice_number) {
                        Hook::exec('actionPDFInvoiceRender', array('order_invoice_list' => $invoice));
                        $pdf = new PDF($invoice, PDF::TEMPLATE_INVOICE, $context->smarty);
                        $file_attachement['invoice']['content'] = $pdf->render(false);
                        $file_attachement['invoice']['name'] = Configuration::get('PS_INVOICE_PREFIX', (int)$order->id_lang, null, $order->id_shop).sprintf('%06d', $order->invoice_number).'.pdf';
                        $file_attachement['invoice']['mime'] = 'application/pdf';
                    }
                    if ($result['pdf_delivery'] && $order->delivery_number) {
                        $pdf = new PDF($invoice, PDF::TEMPLATE_DELIVERY_SLIP, $context->smarty);
                        $file_attachement['delivery']['content'] = $pdf->render(false);
                        $file_attachement['delivery']['name'] = Configuration::get('PS_DELIVERY_PREFIX', Context::getContext()->language->id, null, $order->id_shop).sprintf('%06d', $order->delivery_number).'.pdf';
                        $file_attachement['delivery']['mime'] = 'application/pdf';
                    }
                } else {
                    $file_attachement = null;
                }

                if (!Mail::Send(
                    (int)$order->id_lang,
                    $result['template'],
                    $topic,
                    $data,
                    $result['email'],
                    $result['firstname'].' '.$result['lastname'],
                    null,
                    null,
                    $file_attachement,
                    null,
                    _PS_MAIL_DIR_,
                    false,
                    (int)$order->id_shop
					)) {
						return false;
					}
				}

				ShopUrl::resetMainDomainCache();
			}

			return true;
		}
	}
