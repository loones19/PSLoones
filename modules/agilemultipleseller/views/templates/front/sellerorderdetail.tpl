{extends file='page.tpl'}

{block name='page_content'}

	{capture name=path}<a href="{$link->getPageLink('my-account', true)}">{l s='My Account' mod='agilemultipleseller'}</a><span class="navigation-pipe">{$navigationPipe}</span>{l s='My Seller Account'  mod='agilemultipleseller'}{/capture}

	<h1>{l s='My Seller Account' mod='agilemultipleseller'}</h1>

	{include file="module:agilemultipleseller/views/templates/front/seller_tabs.tpl"}
	<br />
	{if isset($isSeller) AND $isSeller}
	<div id="agile">
	{if count($order_history)}
		<div class="panel">
			<form action="{$link->getModuleLink('agilemultipleseller', 'sellerorderdetail', ['ref_order' => $order->reference], true)}" method="post" class="std">
			<div class="row">
			  <h3>
				<span class="agile-pull-left">
					{l s='Order' mod='agilemultipleseller'} <span class="color-myaccount">{l s='#' mod='agilemultipleseller'}{$order->reference}</span> - {l s='placed on' mod='agilemultipleseller'} {dateFormat date=$order->date_add full=0}
				</span>
				<span class="agile-pull-right">
				  <a class="agile-btn agile-btn-default" href="{$link->getModuleLink('agilemultipleseller', 'sellerorders', ['process' =>'orders'], true)}">
					<i class="icon-th-list"></i>&nbsp;{l s=' Back to order list' mod='agilemultipleseller'}
				  </a>
				</span>
			  </h3>
			</div>
			<div class="form-group">
				<div class="agile-col-sm-4 agile-col-md-4 agile-col-lg-4 agile-col-xl-4">
				<select id="id_order_state" name="id_order_state">
					{foreach from=$order_states item=order_state}
						{if $order_state['id_order_state'] != $order_cur_state}
							<option value="{$order_state['id_order_state']}">{$order_state['name']}</option>
						{/if}
					{/foreach}
				</select>
				</div>
				<div class="input-group agile-col-sm-4 agile-col-md-4 agile-col-lg-4 agile-col-xl-4">
					<input type="hidden" name="id_order" value="{$order->id}" />
					<input type="submit" name="submitState" value="{l s='Change Status' mod='agilemultipleseller'}" class="btn btn-primary" />
				</div>
			</div>
			</form>
		</div>

		<div class="form-group">
			<div class="table-responsive clearfix">
				<table class="table detail_step_by_step">
					<thead>
						<tr>
							<th class="first_item">{l s='Date' mod='agilemultipleseller'}</th>
							<th class="last_item">{l s='Status' mod='agilemultipleseller'}</th>
							<th class="last_item">{l s='Changed By' mod='agilemultipleseller'}</th>
						</tr>
					</thead>
					<tbody>
						{foreach from=$order_history item=state name="orderStates"}
							<tr class="{if $smarty.foreach.orderStates.first}first_item{elseif $smarty.foreach.orderStates.last}last_item{/if} {if $smarty.foreach.orderStates.index % 2}alternate_item{else}item{/if}">
								<td>{dateFormat date=$state.date_add full=1}</td>
								<td>{$state.ostate_name|escape:'htmlall':'UTF-8'}</td>
								<td>{$state.employee_firstname|escape:'htmlall':'UTF-8'}&nbsp;{$state.employee_firstname|escape:'htmlall':'UTF-8'}</td>
							</tr>
						{/foreach}
					</tbody>
				</table>
			</div>
		</div>
	{/if}

	{if isset($followup)}
		<div class="form-group">
			<p class="bold">{l s='Click the following link to track the delivery of your order' mod='agilemultipleseller'}</p>
			<a href="{$followup|escape:'htmlall':'UTF-8'}">{$followup|escape:'htmlall':'UTF-8'}</a>
		</div>
	{/if}

	<div class="panel form-group">
		<div class="form-group">
			<h3>
			<p class="bold">{l s='Order:' mod='agilemultipleseller'} <span class="color-myaccount">{l s='#' mod='agilemultipleseller'}{$order->reference}</span></p>
			{if $carrier->id}<p class="bold">{l s='Carrier:' mod='agilemultipleseller'} {if $carrier->name == "0"}{$shop_name|escape:'htmlall':'UTF-8'}{else}{$carrier->name|escape:'htmlall':'UTF-8'}{/if}</p>{/if}
			<p class="bold">{l s='Payment method:' mod='agilemultipleseller'} <span class="color-myaccount">{$order->payment|escape:'htmlall':'UTF-8'}</span></p>
			</h3>

			{if $order->recyclable}
			<p><img src="{$base_dir_ssl}modules/agilemultipleseller/images/icon/recyclable.gif" alt="" class="icon" />&nbsp;{l s='Permission to receive order in recycled packaging is given.' mod='agilemultipleseller'}</p>
			{/if}
			{if $order->gift}
				<p><img src="{$base_dir_ssl}modules/agilemultipleseller/images/icon/gift.gif" alt="" class="icon" />&nbsp;{l s='Gift-wrapping for order is requested.' mod='agilemultipleseller'}</p>
				<p>{l s='Message:' mod='agilemultipleseller'} {$order->gift_message|nl2br}</p>
			{/if}
		</div>
		<ul class="address item form-group agile-col-sm-6 agile-col-md-6 agile-col-lg-6 agile-col-xl-6" {if $order->isVirtual()}style="display:none;"{/if}>
			<li class="address_title">{l s='Invoice' mod='agilemultipleseller'}</li>
			{foreach from=$inv_adr_fields name=inv_loop item=field_item}
				{if $field_item eq "company" && isset($address_invoice->company)}<li class="address_company">{$address_invoice->company|escape:'htmlall':'UTF-8'}</li>
				{elseif $field_item eq "address2" && $address_invoice->address2}<li class="address_address2">{$address_invoice->address2|escape:'htmlall':'UTF-8'}</li>
				{elseif $field_item eq "phone_mobile" && $address_invoice->phone_mobile}<li class="address_phone_mobile">{$address_invoice->phone_mobile|escape:'htmlall':'UTF-8'}</li>
				{else}
						{assign var=address_words value=" "|explode:$field_item}
						<li>
						{foreach from=$address_words item=word_item name="word_loop"}
							{if !$smarty.foreach.word_loop.first} {/if}<span class="address_{trim($word_item,",")}">{$invoiceAddressFormatedValues[trim($word_item,",")]|escape:'htmlall':'UTF-8'}</span>
						{/foreach}
						</li>
				{/if}

			{/foreach}
		</ul>
		<ul class="address alternate_item agile-col-sm-6 agile-col-md-6 agile-col-lg-6 agile-col-xl-6 {if $order->isVirtual()}full_width{/if}">
			<li class="address_title">{l s='Delivery' mod='agilemultipleseller'}</li>
			{foreach from=$dlv_adr_fields name=dlv_loop item=field_item}
				{if $field_item eq "company" && isset($address_delivery->company)}<li class="address_company">{$address_delivery->company|escape:'htmlall':'UTF-8'}</li>
				{elseif $field_item eq "address2" && $address_delivery->address2}<li class="address_address2">{$address_delivery->address2|escape:'htmlall':'UTF-8'}</li>
				{elseif $field_item eq "phone_mobile" && $address_delivery->phone_mobile}<li class="address_phone_mobile">{$address_delivery->phone_mobile|escape:'htmlall':'UTF-8'}</li>
				{else}
						{assign var=address_words value=" "|explode:$field_item} 
						<li>
						{foreach from=$address_words item=word_item name="word_loop"}
							{if !$smarty.foreach.word_loop.first} {/if}<span class="address_{$word_item}">{$deliveryAddressFormatedValues[trim($word_item,",")]|escape:'htmlall':'UTF-8'}</span>
						{/foreach}
						</li>
				{/if}
			{/foreach}
		</ul>
	</div>
	{$HOOK_ORDERDETAILDISPLAYED}
	{if !$is_guest}<form action="{$link->getPageLink('order-follow', true)}" method="post">{/if}
		<div class="form-group">
			<div id="order-detail-content" class="table-responsive clearfix">
				<table class="table">
					<thead>
						<tr>
							<th class="first_item">{l s='Reference' mod='agilemultipleseller'}</th>
							<th class="item">{l s='Product' mod='agilemultipleseller'}</th>
							<th class="item">{l s='Quantity' mod='agilemultipleseller'}</th>
							<th class="item">{l s='Unit price' mod='agilemultipleseller'}</th>
							<th class="last_item">{l s='Total price' mod='agilemultipleseller'}</th>
						</tr>
					</thead>
					<tfoot>
						{if $priceDisplay && $use_tax}
							<tr class="item">
								<td colspan="5">
									{l s='Total products (tax excl.):' mod='agilemultipleseller'} <span class="price">{displayPrice price=$order->getTotalProductsWithoutTaxes() currency=$currency->id}</span>
								</td>
							</tr>
						{/if}
						<tr class="item">
							<td colspan="5">
								{l s='Total products' mod='agilemultipleseller'} {if $use_tax}{l s='(tax incl.)' mod='agilemultipleseller'}{/if}: <span class="price">{displayPrice price=$order->getTotalProductsWithTaxes() currency=$currency->id}</span>
							</td>
						</tr>
						{if $order->total_discounts > 0}
						<tr class="item">
							<td colspan="5}">
								{l s='Total vouchers:' mod='agilemultipleseller'} <span class="price-discount">{displayPrice price=$order->total_discounts currency=$currency->id}</span>
							</td>
						</tr>
						{/if}
						{if $order->total_wrapping > 0}
						<tr class="item">
							<td colspan="5">
								{l s='Total gift-wrapping:' mod='agilemultipleseller'} <span class="price-wrapping">{displayPrice price=$order->total_wrapping currency=$currency->id}</span>
							</td>
						</tr>
						{/if}
						<tr class="item">
							<td colspan="5">
								{l s='Total shipping' mod='agilemultipleseller'} {if $use_tax}{l s='(tax incl.)' mod='agilemultipleseller'}{/if}: <span class="price-shipping">{displayPrice price=$order->total_shipping currency=$currency->id}</span>
							</td>
						</tr>
						<tr class="totalprice item">
							<td colspan="5">
								{l s='Total:' mod='agilemultipleseller'} <span class="price">{displayPrice price=$order->total_paid currency=$currency->id}</span>
							</td>
						</tr>
					</tfoot>
					<tbody>
						{foreach from=$products item=product name=products}
							{if !isset($product.deleted)}
								{assign var='productId' value=$product.product_id}
								{assign var='productAttributeId' value=$product.product_attribute_id}
								{if isset($product.customizedDatas)}
									{assign var='productQuantity' value=$product.product_quantity-$product.customizationQuantityTotal}
								{else}
									{assign var='productQuantity' value=$product.product_quantity}
								{/if}
								<!-- Customized products -->
								{if isset($product.customizedDatas)}
									<tr class="item">
										<td><label for="cb_{$product.id_order_detail|intval}">{if $product.product_reference}{$product.product_reference|escape:'htmlall':'UTF-8'}{else}--{/if}</label></td>
										<td class="bold">
											<label for="cb_{$product.id_order_detail|intval}">{$product.product_name|escape:'htmlall':'UTF-8'}</label>
										</td>
										<td><label for="cb_{$product.id_order_detail|intval}"><span class="order_qte_span editable">{$product.customizationQuantityTotal|intval}</span></label></td>
										{if $order->hasProductReturned()}
											<td>
												{$product['product_quantity_return']}
											</td>
										{/if}
										<td>
											<label for="cb_{$product.id_order_detail|intval}">
												{if $group_use_tax}
													{displayPrice price=$product.unit_price_tax_incl currency=$currency->id}
												{else}
													{displayPrice price=$product.unit_price_tax_excl currency=$currency->id}
												{/if}
											</label>
										</td>
										<td>
											<label for="cb_{$product.id_order_detail|intval}">
												{if isset($customizedDatas.$productId.$productAttributeId)}
													{if $group_use_tax}
														{displayPrice price=$product.total_customization_wt currency=$currency->id}
													{else}
														{displayPrice price=$product.total_customization currency=$currency->id}
													{/if}
												{else}
													{if $group_use_tax}
														{displayPrice price=$product.total_price_tax_incl currency=$currency->id}
													{else}
														{displayPrice price=$product.total_price_tax_excl currency=$currency->id}
													{/if}
												{/if}
											</label>
										</td>
									</tr>
									{foreach $product.customizedDatas  as $customizationPerAddress}
										{foreach $customizationPerAddress as $customizationId => $customization}
										<tr class="alternate_item">
											<td colspan="2">
											{foreach from=$customization.datas key='type' item='datas'}
												{if $type == $CUSTOMIZE_FILE}
												<ul class="customizationUploaded">
													{foreach from=$datas item='data'}
														<li><img src="{$pic_dir}{$data.value}_small" alt="" class="customizationUploaded" /></li>
													{/foreach}
												</ul>
												{elseif $type == $CUSTOMIZE_TEXTFIELD}
												<ul class="typedText">{counter start=0 print=false}
													{foreach from=$datas item='data'}
														{assign var='customizationFieldName' value="Text #"|cat:$data.id_customization_field}
														<li>{$data.name|default:$customizationFieldName} : {$data.value}</li>
													{/foreach}
												</ul>
												{/if}
											{/foreach}
											</td>
											<td>
												<label for="cb_{$product.id_order_detail|intval}"><span class="order_qte_span editable">{$customization.quantity|intval}</span></label>
											</td>
											<td colspan="2"></td>
										</tr>
										{/foreach}
									{/foreach}
								{/if}
								<!-- Classic products -->
								{if $product.product_quantity > $product.customizationQuantityTotal}
									<tr class="item">
										<td><label for="cb_{$product.id_order_detail|intval}">{if $product.product_reference}{$product.product_reference|escape:'htmlall':'UTF-8'}{else}--{/if}</label></td>
										<td class="bold">
											<label for="cb_{$product.id_order_detail|intval}">
												{if $product.download_hash && $invoice && $product.display_filename != '' && $product.product_quantity_refunded == 0 && $product.product_quantity_return == 0}
													{if isset($is_guest) && $is_guest}
													<a href="{$link->getPageLink('get-file', true, NULL, "key={$product.filename|escape:'htmlall':'UTF-8'}-{$product.download_hash|escape:'htmlall':'UTF-8'}&amp;id_order={$order->id}&secure_key={$order->secure_key}")|escape:'html'}" title="{l s='Download this product' mod='agilemultipleseller'}">
													{else}
														<a href="{$link->getPageLink('get-file', true, NULL, "key={$product.filename|escape:'htmlall':'UTF-8'}-{$product.download_hash|escape:'htmlall':'UTF-8'}")|escape:'html'}" title="{l s='Download this product' mod='agilemultipleseller'}">
													{/if}
														<img src="{$base_dir_ssl}modules/agilemultipleseller/images/icon/download_product.gif" class="icon" alt="{l s='Download product' mod='agilemultipleseller'}" />
													</a>
													{if isset($is_guest) && $is_guest}
														<a href="{$link->getPageLink('get-file', true, NULL, "key={$product.filename|escape:'htmlall':'UTF-8'}-{$product.download_hash|escape:'htmlall':'UTF-8'}&id_order={$order->id}&secure_key={$order->secure_key}")|escape:'html'}" title="{l s='Download this product' mod='agilemultipleseller'}"> {$product.product_name|escape:'htmlall':'UTF-8'} 	</a>
													{else}
													<a href="{$link->getPageLink('get-file', true, NULL, "key={$product.filename|escape:'htmlall':'UTF-8'}-{$product.download_hash|escape:'htmlall':'UTF-8'}")|escape:'html'}" title="{l s='Download this product' mod='agilemultipleseller'}"> {$product.product_name|escape:'htmlall':'UTF-8'} 	</a>
													{/if}
												{else}
													{$product.product_name|escape:'htmlall':'UTF-8'}
												{/if}
											</label>
										</td>
										<td><label for="cb_{$product.id_order_detail|intval}"><span class="order_qte_span editable">{$productQuantity|intval}</span></label></td>
										{if $order->hasProductReturned()}
											<td>
												{$product['product_quantity_return']}
											</td>
										{/if}
										<td>
											<label for="cb_{$product.id_order_detail|intval}">
											{if $group_use_tax}
												{displayPrice price=$product.unit_price_tax_incl currency=$currency->id}
											{else}
												{displayPrice price=$product.unit_price_tax_excl currency=$currency->id}
											{/if}
											</label>
										</td>
										<td>
											<label for="cb_{$product.id_order_detail|intval}">
											{if $group_use_tax}
												{displayPrice price=$product.total_price_tax_incl currency=$currency->id}
											{else}
												{displayPrice price=$product.total_price_tax_excl currency=$currency->id}
											{/if}
											</label>
										</td>
									</tr>
								{/if}
							{/if}
						{/foreach}
						{foreach from=$discounts item=discount}
							<tr class="item">
								<td>{$discount.name|escape:'htmlall':'UTF-8'}</td>
								<td>{l s='Voucher:' mod='agilemultipleseller'} {$discount.name|escape:'htmlall':'UTF-8'}</td>
								<td><span class="order_qte_span editable">1</span></td>
								<td>&nbsp;</td>
								<td>{if $discount.value != 0.00}{l s='-' mod='agilemultipleseller'}{/if}{displayPrice price=$discount.value currency=$currency->id}</td>
							</tr>
						{/foreach}
					</tbody>
				</table>
			</div>
		</div>

	{if !$is_guest}
		</form>

	{if $order->getShipping()|count > 0}
		<div class="form-group">
			<div class="table-responsive clearfix">
				<table class="table">
					<thead>
						<tr>
							<th class="first_item">{l s='Date' mod='agilemultipleseller'}</th>
							<th class="item">{l s='Carrier' mod='agilemultipleseller'}</th>
							<th class="item">{l s='Weight' mod='agilemultipleseller'}</th>
							<th class="item">{l s='Shipping cost' mod='agilemultipleseller'}</th>
							<th class="last_item">{l s='Tracking number' mod='agilemultipleseller'}</th>
						</tr>
					</thead>
					<tbody>
						{foreach from=$order->getShipping() item=line}
						<tr class="item">
							<td>{$line.date_add}</td>
							<td>{$line.state_name}</td>
							<td>{$line.weight|string_format:"%.3f"} {Configuration::get('PS_WEIGHT_UNIT')}</td>
							<td>{if $order->getTaxCalculationMethod() == $smarty.const.PS_TAX_INC}{displayPrice price=$line.shipping_cost_tax_incl currency=$currency->id}{else}{displayPrice price=$line.shipping_cost_tax_excl currency=$currency->id}{/if}</td>
							<td>
								<span id="shipping_number_show">{if $line.url && $line.tracking_number}<a href="{$line.url|replace:'@':$line.tracking_number}">{$line.tracking_number}</a>{else}{$line.tracking_number}{/if}</span>
								{if $line.can_edit}
									<form method="post" action="{$link->getModuleLink('agilemultipleseller', 'sellerorderdetail', ['ref_order' => $order->reference], true)}">
										<span class="shipping_number_edit" style="display:none;">
											<input type="hidden" name="id_order_carrier" value="{$line.id_order_carrier|htmlentities}" />
											<input type="text" name="tracking_number" value="{$line.tracking_number|htmlentities}" />
											<button type="submit" class="btn btn-default" name="submitShippingNumber">
												<i class="icon-ok"></i>
												{l s='Update' mod='agilemultipleseller'}
											</button>
										</span>
										<a href="#" class="edit_shipping_number_link btn btn-default">
											<i class="icon-pencil"></i>
											{l s='Edit' mod='agilemultipleseller'}
										</a>
										<a href="#" class="cancel_shipping_number_link btn btn-default" style="display: none;">
											<i class="icon-remove"></i>
											{l s='Cancel' mod='agilemultipleseller'}
										</a>
									</form>
								{/if}

							</td>
						</tr>
						{/foreach}
					</tbody>
				</table>
			</div>
		</div>
	{/if}


		<div class="panel">
			{if count($messages)}
				<div class="form-group">
					<div class="table-responsive clearfix">
						<h3>{l s='Messages' mod='agilemultipleseller'}</h3>
						<table class="detail_step_by_step std">
							<thead>
								<tr>
									<th class="first_item" style="width:150px;">{l s='From' mod='agilemultipleseller'}</th>
									<th class="last_item">{l s='Message' mod='agilemultipleseller'}</th>
								</tr>
							</thead>
							<tbody>
							{foreach from=$messages item=message name="messageList"}
								<tr class="{if $smarty.foreach.messageList.first}first_item{elseif $smarty.foreach.messageList.last}last_item{/if} {if $smarty.foreach.messageList.index % 2}alternate_item{else}item{/if}">
									<td>
										{if isset($message.elastname) && $message.elastname}
											{$message.efirstname|escape:'htmlall':'UTF-8'} {$message.elastname|escape:'htmlall':'UTF-8'}
										{elseif $message.clastname}
											{$message.cfirstname|escape:'htmlall':'UTF-8'} {$message.clastname|escape:'htmlall':'UTF-8'}
										{else}
											<b>{$shop_name|escape:'htmlall':'UTF-8'}</b>
										{/if}
										<br />
										{dateFormat date=$message.date_add full=1}
									</td>
									<td>{$message.message|nl2br nofilter}</td>
								</tr>
							{/foreach}
							</tbody>
						</table>
					</div>
				</div>
			{/if}

			<form action="{$link->getModuleLink('agilemultipleseller', 'sellerorderdetail', ['ref_order' => $order->reference], true)}" method="post" class="std" id="sendOrderMessage">
				<h3>{l s='Add a message:' mod='agilemultipleseller'}</h3>
				<p>{l s='If you would like to add a comment about your order, please write it below.' mod='agilemultipleseller'}</p>
				<p>
				<div class="form-group">
					<div class="agile-col-sm-2 agile-col-md-2 agile-col-lg-2 agile-col-xl-2">
						<label for="id_product" class="control-label">{l s='Product' mod='agilemultipleseller'}</label>
					</div>
					<div class="input-group agile-col-sm-9 agile-col-md-9 agile-col-lg-9 agile-col-xl-9">
						<select name="id_product">
							<option value="0">{l s='-- Choose --' mod='agilemultipleseller'}</option>
							{foreach from=$products item=product name=products}
								<option value="{$product.product_id}">{$product.product_name}</option>
							{/foreach}
						</select>
					</div>
				</div>
				<div class="form-group textarea">
					<div class="agile-col-sm-2 agile-col-md-2 agile-col-lg-2 agile-col-xl-2">
						<label for="msgText" class="control-label">{l s='Comment' mod='agilemultipleseller'}</label>
					</div>
					<div class="input-group agile-col-sm-9 agile-col-md-9 agile-col-lg-9 agile-col-xl-9">
						<textarea cols="67" rows="6" name="msgText" class="form-control textarea"></textarea>
					</div>
				</div>

				<div class="form-group agile-align-center">
					<input type="hidden" name="id_order" value="{$order->id|intval}" />
					<button type="submit" class="agile-btn agile-btn-default" name="submitMessage" value="{l s='Send' mod='agilemultipleseller'}">
					<i class="icon-save "></i>&nbsp;<span>{l s='Send' mod='agilemultipleseller'}</span></button >
				</div>
			</form>
		</div>
	{else}
		<div class="form-group">
			<p><img src="{$base_dir_ssl}modules/agilemultipleseller/images/icon/infos.gif" alt="" class="icon" />&nbsp;{l s='You cannot make a merchandise return with a guest account' mod='agilemultipleseller'}</p>
		</div>
	{/if}
	</div>
	{/if} {*  isSeller  *}
	{include file="module:agilemultipleseller/views/templates/front/seller_footer.tpl"}

{/block}

{block name='javascript_bottom'}
    {$smarty.block.parent}

	<script type="text/javascript" language="javascript">
		$('.edit_shipping_number_link').unbind('click').click(function(e) {
			$(this).parent().find('.shipping_number_show').hide();
			$(this).parent().find('.shipping_number_edit').show();

			$(this).parent().find('.edit_shipping_number_link').hide();
			$(this).parent().find('.cancel_shipping_number_link').show();
			e.preventDefault();
		});

		$('.cancel_shipping_number_link').unbind('click').click(function(e) {
			$(this).parent().find('.shipping_number_show').show();
			$(this).parent().find('.shipping_number_edit').hide();

			$(this).parent().find('.edit_shipping_number_link').show();
			$(this).parent().find('.cancel_shipping_number_link').hide();
			e.preventDefault();
		});
	</script>

{/block}
