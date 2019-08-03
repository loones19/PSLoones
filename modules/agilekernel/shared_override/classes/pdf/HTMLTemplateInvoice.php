<?php
///-build_id: 2018051409.414
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.

class HTMLTemplateInvoice extends HTMLTemplateInvoiceCore
{
	public function getContent()
	{
		if(isset($this->order) AND Validate::isLoadedObject($this->order) AND Module::isInstalled('agilemultipleseller'))
		{
			$carrier = new Carrier($this->order->id_carrier);
			$this->smarty->assign(array(
				'order_number_text' => sprintf('%06d', $this->order->id),
				'carrier_name' => $carrier->name
				));
		}		
		$content = parent::getContent();
		
		if(isset($this->order) AND Validate::isLoadedObject($this->order) AND Module::isInstalled('agilepickupcenter'))
		{
			$country = new Country((int)$this->order->id_address_invoice);
			$invoice_address = new Address((int)$this->order->id_address_invoice);
			$formatted_invoice_address = AddressFormat::generateAddress($invoice_address, array(), '<br />', ' ');
			$formatted_delivery_address = $formatted_invoice_address;
			if ($this->order->id_address_delivery != $this->order->id_address_invoice)
			{
				$delivery_address = new Address((int)$this->order->id_address_delivery);
				$formatted_delivery_address = AddressFormat::generateAddress($delivery_address, array(), '<br />', ' ');
			}

						require_once(_PS_ROOT_DIR_ . "/modules/agilepickupcenter/agilepickupcenter.php");
			$pickupcenter = new AgilePickupCenter();
			$delivery_address = $formatted_delivery_address . "<BR>" . $pickupcenter->displayInfoByCart($this->order->id_cart);
			
			$this->smarty->assign(array(
				'delivery_address' => $delivery_address
				,'addresses' => array('invoice' => $invoice_address, 'delivery' => $delivery_address)
				));
			

			$this->smarty->assign(array(
				'addresses_tab' => $this->smarty->fetch($this->getTemplate('invoice.addresses-tab'))
				));
			
						$content = $this->smarty->fetch($this->getTemplateByCountry($country->iso_code));
		}		
		
		return $content;

	}
	
	public function getHeader()
	{
		$parent_header = parent::getHeader();
		if(!isset($this->order) OR !Validate::isLoadedObject($this->order))return $parent_header;
		if(!Module::isInstalled('agilemultipleseller'))return $parent_header;
		
		require_once(_PS_ROOT_DIR_ . "/modules/agilemultipleseller/SellerInfo.php");
		$id_seller = AgileSellerManager::getObjectOwnerID('order', $this->order->id);
				$sellerinfo = new SellerInfo(SellerInfo::getIdBSellerId($id_seller), $this->order->id_lang);

		
		$seller_logo_path = SellerInfo::seller_logo_physical_path($sellerinfo->id);
				if(!file_exists($seller_logo_path))$seller_logo_path = false;

				$this->smarty->assign(array(
			'seller_name' => $sellerinfo->company,
			'seller_logo_path' => $seller_logo_path,
			'seller_logo_url' => SellerInfo::get_seller_logo_url_static($sellerinfo->id),
			'sellerinfo' => $sellerinfo
			));
		
		return $this->smarty->fetch($this->getTemplate('header'));

	}

	public function getFilename()
	{
		if(Module::IsInstalled('agileinvoicenumber'))		
			return	$this->order_invoice->getInvoiceNumberFormatted(Context::getContext()->language->id, (int)$this->order->id_shop) . ".pdf";

		return parent::getFilename();
	}
	
}

