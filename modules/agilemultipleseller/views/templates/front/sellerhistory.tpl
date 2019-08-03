{extends file='page.tpl'}

{block name='page_content'}

	{capture name=path}<a href="{$link->getPageLink('my-account', true)}">{l s='My Account' mod='agilemultipleseller'}</a><span class="navigation-pipe">{$navigationPipe}</span>{l s='My Seller Account'  mod='agilemultipleseller'}{/capture}

	<h1>{l s='My Seller Account' mod='agilemultipleseller'}</h1>
	{include file="module:agilemultipleseller/views/templates/front/seller_tabs.tpl"}

	{if isset($isSeller) AND $isSeller}
	<div id="agile">
		{if $commssionrecords && count($commssionrecords)}
		<div class="table-responsive clearfix">
		{*
		{include file="$tpl_dir./pagination.tpl"}
		*}
		<table id="order-list" class="table">
			<thead>
				<tr>
					<th class="first_item">{l s='TXN #' mod='agilemultipleseller'}</th>
					<th class="item">{l s='Order' mod='agilemultipleseller'}</th>
					<th class="item">{l s='Date' mod='agilemultipleseller'}</th>
					<th class="item">{l s='Sales' mod='agilemultipleseller'}</th>
					<th class="item">{l s='TXN Fee' mod='agilemultipleseller'}</th>
					<th class="item">{l s='Commission Fee' mod='agilemultipleseller'}</th>
					<th class="item">{l s='Credit/Debit' mod='agilemultipleseller'}</th>
					<th class="item">{l s='Balance' mod='agilemultipleseller'}</th>
					<th class="item">{l s='TXN Type' mod='agilemultipleseller'}</th>
					<th class="last_item" style="width:5px">&nbsp;</th>
				</tr>
			</thead>
			<tbody>
			{foreach from=$commssionrecords  item=commssionrecord name=myLoop}
				<tr class="{if $smarty.foreach.myLoop.first}first_item{elseif $smarty.foreach.myLoop.last}last_item{else}item{/if} {if $smarty.foreach.myLoop.index % 2}alternate_item{/if}">
					<td>{$commssionrecord.id_seller_commission}</td>
					<td>{if $commssionrecord.id_order > 0}<a href="{$link->getModuleLink('agilemultipleseller', 'sellerorderdetail', ['ref_order' => $commssionrecord.reference], true)}">{l s='#' mod='agilemultipleseller'}{$commssionrecord.reference}</a>{else}--{/if}</td>
					<td class="history_date bold">{dateFormat date=$commssionrecord.date_add full=0}</td>
					<td>{if $commssionrecord.sales_amount != 0}{displayPrice price=$commssionrecord.sales_amount currency = $id_commission_currency no_utf8=false convert=false}{else}--{/if}</td>
					<td>{if $commssionrecord.base_commission != 0}{displayPrice price=$commssionrecord.base_commission currency = $id_commission_currency no_utf8=false convert=false}{else}--{/if}</td>
					<td>{if $commssionrecord.range_commission != 0}{displayPrice price=$commssionrecord.range_commission currency = $id_commission_currency no_utf8=false convert=false}{else}--{/if}</td>
					<td>{displayPrice price=$commssionrecord.record_balance currency=$id_commission_currency no_utf8=false convert=false}</td>
					<td>{displayPrice price=$commssionrecord.balance currency=$id_commission_currency no_utf8=false convert=false}</td>
					<td>
						{if $commssionrecord.record_type == 3 || $commssionrecord.record_type == 0}{l s='Customer paid to store' mod='agilemultipleseller'}{/if}
						{if $commssionrecord.record_type == 1}{l s='Customer paid to you' mod='agilemultipleseller'}{/if}
						{if $commssionrecord.record_type == 2}{l s='Customer paid to you and store' mod='agilemultipleseller'}{/if}
						{if $commssionrecord.record_type == 101}{l s='You paid to store(offline)' mod='agilemultipleseller'}{/if}
						{if $commssionrecord.record_type == 102}{l s='Store paid to you(offiline)' mod='agilemultipleseller'}{/if}
						{if $commssionrecord.record_type == -1}{l s='Order cancellation' mod='agilemultipleseller'}{/if}
					</td>
					<td class="history_detail">
					</td>
				</tr>
			{/foreach}
			</tbody>
		</table>
		<div> <!-- table-responsive -->
		<div id="block-order-detail" class="hidden">&nbsp;</div>
		{else}
			<p class="warning">{l s='You do not yet have a transaction.' mod='agilemultipleseller'}</p>
		{/if}
	</div> <!-- bootstrap -->
	{/if}
	{include file="module:agilemultipleseller/views/templates/front/seller_footer.tpl"}

{/block}