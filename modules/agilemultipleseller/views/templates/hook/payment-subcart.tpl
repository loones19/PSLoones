{if isset($recepients) && count($recepients) >0}
<div class="row">
	<div>
		<p style="margin-left:20px;">
			<script language="javascript" type="text/javascript">
			function toogle_visibility_cart_details(id)
			{
				$("#" + id).toggle();
			}
			function pay_this_seller(formid, id, id_subcart)
			{
				$("#" + id).val(id_subcart);
			/** _agile_ alert($("#" + id).val()); _agile **/
				$("form#" + formid).submit();
			}
			</script>

			{if !$is_parallel_payment}
				<span style="color:red;">{l s='Please Note' mod='agilemultipleseller'}</span>
				<br>
				{l s='The product items in your shopping cart are from different sellers, you have to approve the payment for each seller separately.' mod='agilemultipleseller'}
				{l s='After you finish each vendor payment, please return to the store to complete additional order payments.' mod='agilemultipleseller'}
				<br>
				<br>
			{/if}
			<input type="hidden" name="id_subcart" value="" id="id_subcart_{$modulename}">
		</p>

		{foreach from=$recepients key=k item=recepient}
		<div>
			<div class="table-responsive">
				<table class="table"><tr>
				<td>
				{$recepient.seller_name}: {displayPrice price=$recepient.subcart_total} 
				</td>
				<td align="right">
				{if $recepient.support_payment == 1 OR $is_parallel_payment}				
					<input type="button" class="btn btn-primary" value="{l s='Details' mod='agilemultipleseller'}" onclick="toogle_visibility_cart_details('subcart_details_{$modulename}_{$recepient.id_subcart}');"/>
					{if !$is_parallel_payment}
					<input type="button" class="btn btn-primary" value="{l s='Confirm This Order' mod='agilemultipleseller'}" onclick="pay_this_seller('{$moduleformid}', 'id_subcart_{$modulename}',{$recepient.id_subcart});"/>
					{/if}
				{else}
					{if !$is_parallel_payment}
					{l s='This seller does not use this payment method.' mod='agilemultipleseller'}
					{/if}
				{/if}
				</td>
				</tr>
				</table>
			</div>

			{if $recepient.support_payment == 1 OR $is_parallel_payment}
			<div class="table-responsive" id="subcart_details_{$modulename}_{$recepient.id_subcart}" style="display:none;">
				<table class="table">
					<thead>
					<tr><td>{l s='Product' mod='agilemultipleseller'}</td><td  align="center">{l s='Quantity' mod='agilemultipleseller'}</td><td align="right">{l s='Price' mod='agilemultipleseller'}</td></tr>
					</thead>
					{foreach from=$recepient.products item=product}
						<tr><td>{$product.name}</td>
						<td align="center">{$product.quantity}</td>
						<td  align="right">{displayPrice price=$product.price}</td></tr>
					{/foreach}
					{if $recepient.subcart_totaldiscounts != 0}
					<tr><td></td><td align="right">{l s='Total Discount/Credits' mod='agilemultipleseller'}</td><td align="right">-{displayPrice price=$recepient.subcart_totaldiscounts}</td></tr>
					{/if}
					<tr><td></td><td align="right">{l s='Shipping' mod='agilemultipleseller'}</td><td align="right">{displayPrice price=$recepient.shippingCost}</td></tr>
					<tr><td></td><td align="right">{l s='Total Tax' mod='agilemultipleseller'}</td><td align="right">{displayPrice price=$recepient.total_tax}</td></tr>
					<tr><td colspan="3">{$recepient.other_info nofilter}</td></tr>
				</table>
			</div>
			{/if}

		</div>
		{/foreach}


	</div>
</div>
{/if}