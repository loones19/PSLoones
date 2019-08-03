<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
class AgileMultipleSellerSellerPdfInvoiceModuleFrontController extends AgileModuleFrontController
{
	protected $display_header = false;
	protected $display_footer = false;

    public $content_only = true;

	protected $template;
	public $filename;

	public function postProcess()
	{
		$id_order = (int)Tools::getValue('id_order');

		if (!$this->context->customer->isLogged() && !Tools::getValue('secure_key'))
			Tools::redirect('index.php?controller=authentication&back=my-account');

		if (!(int)Configuration::get('PS_INVOICE'))
			die(Tools::displayError('Invoices are disabled in this shop.'));

		if (isset($id_order) && Validate::isUnsignedId($id_order))
			$order = new Order($id_order);

		if (!isset($order) || !Validate::isLoadedObject($order))
			die(Tools::displayError('Invoice not found'));

		$id_order_seller = AgileSellerManager::getObjectOwnerID('order',$id_order);
		$id_customer_seller = AgileSellerManager::getLinkedSellerID($this->context->customer->id);
	    if ($id_order_seller!=$id_customer_seller)
			die(Tools::displayError('You do not have permission to see this invoice'));
    	
    	if (Tools::isSubmit('secure_key') && $order->secure_key != Tools::getValue('secure_key'))
			die(Tools::displayError('You do not have permission to see this invoice'));

		if (!OrderState::invoiceAvailable($order->getCurrentState()) && !$order->invoice_number)
			die(Tools::displayError('No invoice available'));

		$this->order = $order;
	}

	public function display()
	{	
		$order_invoice_list = $this->order->getInvoicesCollection();
		Hook::exec('actionPDFInvoiceRender', array('order_invoice_list' => $order_invoice_list));

		$pdf = new PDF($order_invoice_list, PDF::TEMPLATE_INVOICE, $this->context->smarty, $this->context->language->id);
		$pdf->render();
	}


			public function getTemplate($iso_country)
	{
		$template = _PS_THEME_PDF_DIR_.'/invoice.tpl';

		$iso_template = _PS_THEME_PDF_DIR_.'/invoice.'.$iso_country.'.tpl';
		if (file_exists($iso_template))
			$template = $iso_template;

		return $template;
	}
}

