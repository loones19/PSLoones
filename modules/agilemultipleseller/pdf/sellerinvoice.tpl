{*
*}
<div style="font-size: 8pt; color: #444">

<table>
	<tr><td>&nbsp;</td></tr>
</table>

<!-- ADDRESSES -->
<table style="width: 100%">
	<tr>
		<td style="width: 15%"></td>
		<td style="width: 85%">
				<table style="width: 100%">
					<tr>
						<td style="width: 50%">
							<span style="font-weight: bold; font-size: 10pt; color: #9E9F9E">{l s='Store Address' pdf='true'}</span><br />
							 {$invoice_address}
						</td>
						<td style="width: 50%">
							<span style="font-weight: bold; font-size: 10pt; color: #9E9F9E">{l s='Seller Address' pdf='true'}</span><br />
							 {$invoice_address}
						</td>
					</tr>
				</table>
		</td>
	</tr>
</table>
<!-- / ADDRESSES -->

<div style="line-height: 1pt">&nbsp;</div>

<!-- Invoice Details TAB -->
<table style="width: 100%">
	<tr>
		<td style="width: 15%; padding-right: 7px; text-align: right; vertical-align: top; font-size: 7pt;">
			{l s='Invoice #' pdf='true'}
		</td>
		<td>
			43534634634
		</td>
	</tr>
	<tr>
		<td style="width: 15%; padding-right: 7px; text-align: right; vertical-align: top; font-size: 7pt;">
			{l s='Invoice Date' pdf='true'}
		</td>
		<td>
		{dateFormat date=$sellerinvoice->date_add|escape:'html':'UTF-8' full=0}
		</td>
	</tr>
</table>
<div style="line-height: 1pt">&nbsp;</div>
<table border="1" cellpadding="2" cellspacing="2">
<thrad>
<tr style="background-color:lightgray;">
	<td>{l s='No.' pdf='true'}</td>
	<td>{l s='Order #' pdf='true'}</td>
	<td>{l s='Order Date' pdf='true'}</td>
	<td align="right">{l s='Order Amount' pdf='true'}</td>
	<td align="right">{l s='Transaction Fee' pdf='true'}</td>
	<td align="right">{l s='Commission Fee' pdf='true'}</td>
	<td align="right">{l s='Commission Due' pdf='true'}</td>
</tr>
</thrad>
{assign var="itemNo" value="1"}
{foreach from=$invoice_items item=item}
<tr>
	<td>
		{$itemNo}
	</td>
	<td>
		{$item.reference}
	</td>
	<td>
		{dateFormat date=$item.order_date|escape:'html':'UTF-8' full=0}
	</td>
	<td align="right">
		{displayPrice price=$item.order_amount}
	</td>
	<td align="right">
		{displayPrice price=$item.base_commission}
	</td>
	<td align="right">
		{displayPrice price=$item.range_commission}
	</td>
	<td align="right">
		{displayPrice price=($item.base_commission + $item.range_commission)}
	</td>
</tr>
{assign var="itemNo" value=($itemNo+1)}
{/foreach}
<tr style="background-color:lightgray;">
	<td colspan="4" align="right">
		{l s='Subtotal' pdf='true'}:
	</td>
	<td align="right">
		{displayPrice price=$base_commission_total}
	</td>
	<td align="right">
		{displayPrice price=$range_commission_total}
	</td>
	<td align="right">
		{displayPrice price=$commission_due_total}
	</td>
</tr>
<tr style="background-color:lightgray;">
	<td colspan="6" align="right">
		{l s='Total' pdf='true'}:
	</td>
	<td align="right">
		{displayPrice price=$total}
	</td>
</tr>
</table>

<div style="line-height: 1pt">&nbsp;</div>

{if isset($HOOK_DISPLAY_PDF)}
<div style="line-height: 1pt">&nbsp;</div>
<table style="width: 100%">
	<tr>
		<td style="width: 15%"></td>
		<td style="width: 85%">{$HOOK_DISPLAY_PDF}</td>
	</tr>
</table>
{/if}

</div>
