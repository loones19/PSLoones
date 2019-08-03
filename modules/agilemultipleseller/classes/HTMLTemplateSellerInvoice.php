<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.

class HTMLTemplateSellerInvoiceCore extends HTMLTemplate
{
	public $seller;
	public $sellerinfo;
	public $sellerinvoice;
	public $available_in_your_account = false;

	public function __construct(SellerInvoice $sellerinvoice, $smarty)
	{
		$this->sellerinvoice = $sellerinvoice;
		$this->seller = new Employee((int)$this->sellerinvoice->id_seller);
		$this->sellerinfo = new SellerInfo( SellerInfo::getIdBSellerId($this->sellerinvoice->id_seller), Context::getContext()->cookie->id_lang);
		$this->smarty = $smarty;

				$this->date = Tools::displayDate($sellerinvoice->date_add, null);

		$id_lang = Context::getContext()->language->id;
		$this->title = HTMLTemplateInvoice::l('Seller Invoice ').' #'.Configuration::get('AGILE_INVOICE_PREFIX').sprintf('%06d', $sellerinvoice->id);
				$this->shop = new Shop((int)Configuration::get('PS_SHOP_DEFAULT'));
	}

				 
	public function getContent()
	{
		
		$country = new Country((int)$this->sellerinfo->id_country);
		$invoice_address = new Address(6);		$formatted_invoice_address = AddressFormat::generateAddress($invoice_address, array(), '<br />', ' ');
		$formatted_delivery_address = '';

		
		$this->smarty->assign(array(
			'sellerinfo' => $this->sellerinfo,
			'seller'=> $this->seller,
			'sellerinvoice' => $this->sellerinvoice,
			'invoice_address' => $formatted_invoice_address,
		));

		return $this->smarty->fetch($this->getTemplateByCountry($country->iso_code));
	}

		public function getTaxTabContent()
	{
			$invoice_address = new Address((int)$this->order->id_address_invoice);
			$tax_exempt = Configuration::get('VATNUMBER_MANAGEMENT')
								&& !empty($invoice_address->vat_number)
								&& $invoice_address->id_country != Configuration::get('VATNUMBER_COUNTRY');

			$this->smarty->assign(array(
				'tax_exempt' => $tax_exempt,
				'use_one_after_another_method' => $this->order_invoice->useOneAfterAnotherTaxComputationMethod(),
				'product_tax_breakdown' => $this->order_invoice->getProductTaxesBreakdown(),
				'shipping_tax_breakdown' => $this->order_invoice->getShippingTaxesBreakdown($this->order),
				'ecotax_tax_breakdown' => $this->order_invoice->getEcoTaxTaxesBreakdown(),
				'wrapping_tax_breakdown' => $this->order_invoice->getWrappingTaxesBreakdown(),
				'order' => $this->order,
				'order_invoice' => $this->order_invoice
			));

			return $this->smarty->fetch($this->getTemplate('invoice.tax-tab'));
	}

			protected function getTemplateByCountry($iso_country)
	{
		$file = 'sellerinvoice';
				$template = $this->getTemplate($file.'.'.$iso_country);


				if (!$template)
			$template = $this->getTemplate($file);

		return $template;
	}

			public function getBulkFilename()
	{
		return 'sellerinvoices.pdf';
	}

	 	 	public function getFilename()
	{
		return Configuration::get('AGILE_INVOICE_PREFIX').sprintf('%06d', $this->sellerinvoice->id).'.pdf';
	}
}

