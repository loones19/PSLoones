{extends file='page.tpl'}

{block name='page_content'}

	{capture name=path}<a href="{$link->getPageLink('my-account', true)}">{l s='My Account' mod='agilemultipleseller'}</a><span class="navigation-pipe">{$navigationPipe}</span>{l s='My Seller Account'  mod='agilemultipleseller'}{/capture}

	<h1>{l s='My Seller Account' mod='agilemultipleseller'}</h1>

	{include file="module:agilemultipleseller/views/templates/front/seller_tabs.tpl"}

	{if isset($isSeller) AND $isSeller}
	<div id="agile">
	<div class="block-center" id="block-history">
		<div class="table-responsive clearfix">
		<table id="order-list" class="table">
			<thead>
				<tr>
					<th class="first_item">{l s='Reference' mod='agilemultipleseller'}&nbsp;<a style="color:{if $orderBy=='o.reference' && $orderWay=='ASC'}#000{else}#00aff0{/if};cursor:pointer" onclick="orderByWay('o.reference','ASC')"><i class="icon-caret-up"></i></a>&nbsp;<a style="color:{if $orderBy=='o.reference' && $orderWay=='DESC'}#000{else}#00aff0{/if};cursor:pointer" onclick="orderByWay('o.reference','DESC')"><i class="icon-caret-down"></i></a></th>
					<th class="first_item">{l s='Order #' mod='agilemultipleseller'}&nbsp;<a style="color:{if $orderBy=='o.id_order' && $orderWay=='ASC'}#000{else}#00aff0{/if};cursor:pointer" onclick="orderByWay('o.id_order','ASC')"><i class="icon-caret-up"></i></a>&nbsp;<a style="color:{if $orderBy=='o.id_order' && $orderWay=='DESC'}#000{else}#00aff0{/if};cursor:pointer" onclick="orderByWay('o.id_order','DESC')"><i class="icon-caret-down"></i></a></th>
					<th class="item">{l s='New' mod='agilemultipleseller'}</th>
					<th class="item">{l s='Customer Name' mod='agilemultipleseller'}&nbsp;<a style="color:{if $orderBy=='c.firstname' && $orderWay=='ASC'}#000{else}#00aff0{/if};cursor:pointer" onclick="orderByWay('c.firstname','ASC')"><i class="icon-caret-up"></i></a>&nbsp;<a style="color:{if $orderBy=='c.firstname' && $orderWay=='DSSC'}#000{else}#00aff0{/if};cursor:pointer" onclick="orderByWay('c.firstname','DESC')"><i class="icon-caret-down"></i></a></th>
					<th class="item">{l s='Total' mod='agilemultipleseller'}&nbsp;<a style="color:{if $orderBy=='o.total_paid' && $orderWay=='ASC'}#000{else}#00aff0{/if};cursor:pointer" onclick="orderByWay('o.total_paid','ASC')"><i class="icon-caret-up"></i></a>&nbsp;<a style="color:{if $orderBy=='o.total_paid' && $orderWay=='DESC'}#000{else}#00aff0{/if};cursor:pointer" onclick="orderByWay('o.total_paid','DESC')"><i class="icon-caret-down"></i></a></th>
					<th class="item">{l s='Payment' mod='agilemultipleseller'}&nbsp;<a style="color:{if $orderBy=='o.payment' && $orderWay=='ASC'}#000{else}#00aff0{/if};cursor:pointer" onclick="orderByWay('o.payment','ASC')"><i class="icon-caret-up"></i></a>&nbsp;<a style="color:{if $orderBy=='o.payment' && $orderWay=='DSSC'}#000{else}#00aff0{/if};cursor:pointer" onclick="orderByWay('o.payment','DESC')"><i class="icon-caret-down"></i></a></th>
					<th class="item">{l s='Status' mod='agilemultipleseller'}&nbsp;<a style="color:{if $orderBy=='osl.name' && $orderWay=='ASC'}#000{else}#00aff0{/if};cursor:pointer" onclick="orderByWay('osl.name','ASC')"><i class="icon-caret-up"></i></a>&nbsp;<a style="color:{if $orderBy=='osl.name' && $orderWay=='DESC'}#000{else}#00aff0{/if};cursor:pointer" onclick="orderByWay('osl.name','DESC')"><i class="icon-caret-down"></i></a></th>
					<th class="item">{l s='Invoice' mod='agilemultipleseller'}</th>
					<th class="item">{l s='Date' mod='agilemultipleseller'}&nbsp;<a style="color:{if $orderBy=='o.date_add' && $orderWay=='ASC'}#000{else}#00aff0{/if};cursor:pointer" onclick="orderByWay('o.date_add','ASC')"><i class="icon-caret-up"></i></a>&nbsp;<a style="color:{if $orderBy=='o.date_add' && $orderWay=='DESC'}#000{else}#00aff0{/if};cursor:pointer" onclick="orderByWay('o.date_add','DESC')"><i class="icon-caret-down"></i></a></th>
					<th class="last_item">
						<input type="button" class="agile-btn agile-btn-default"  name="export" value="Export" id="order_export" onclick="CSVOnClick()"/><br>
					</th>
				</tr>
			<tr>
				<th>
					<input type="text" name="filter_reference" id="filter_reference" value="{$filter_reference}">
				</th>
				<th>
					<input type="text" name="filter_id_order" id="filter_id_order" value="{$filter_id_order}">
				</th>
				<th></th>
				<th>
					<input type="text" name="filter_customer" id="filter_customer" value="{$filter_customer}">
				</th>
				<th>
					<input type="text" name="filter_total" id="filter_total" value="{$filter_total}">
				</th>
				<th>
					<input type="text" name="filter_payment" id="filter_payment" value="{$filter_payment}">
				</th>
				<th>
					<select name="filter_id_order_state" id="filter_id_order_state">
					<option value=""></option>
					{foreach $statuses as $status}
						<option value="{$status.id_order_state}" {if $status.id_order_state  == $filter_id_order_state}selected{/if}>{$status.name}</option>
					{/foreach}
					</select>
				</th>
				<th></th>
				<th>
					<input class="datepicker" type="text" id="filter_date_add_from" name="filter_date_add_from" value="{$filter_date_add_from}" style="width:85px;" maxlength="10" autocomplete="off" />
					<input class="datepicker" type="text" id="filter_date_add_to" name="filter_date_add_to" value="{$filter_date_add_to}" style="width:85px;" maxlength="10" autocomplete="off" />				
				</th>
				<th>
					<input type="button" class="agile-btn agile-btn-default"  name="reset" value="Reset" id="reset" onclick="ResetOnClick()"/><br>
					<input type="button" class="agile-btn agile-btn-default"  name="btnGo" value="Search" onclick="goOnClick()">
				</th>
			</tr>
			</thead>
			<tbody>
			{foreach from=$orders item=order name=myLoop}
				<tr class="{if $smarty.foreach.myLoop.first}first_item{elseif $smarty.foreach.myLoop.last}last_item{else}item{/if} {if $smarty.foreach.myLoop.index % 2}alternate_item{/if}">
					<td class="history_date bold">{dateFormat date=$order.date_add full=0}</td>
					<td class="history_link bold">
						{if isset($order.invoice) && $order.invoice && isset($order.virtual) && $order.virtual}<img src="{$base_dir_ssl}//modules/agilemultipleseller/images//download_product.gif" class="icon" alt="{l s='Products to download' mod='agilemultipleseller'}" title="{l s='Products to download' mod='agilemultipleseller'}" />{/if}
						<a class="color-myaccount" href="{$link->getModuleLink('agilemultipleseller', 'sellerorderdetail', ['ref_order' => $order.reference], true)}">{l s='#' mod='agilemultipleseller'}{$order.reference}</a>
					</td>
					<td>{$order.id_order}</td>
					<td>{if $order.new == 1}<img src="{$base_dir_ssl}//modules/agilemultipleseller/images/news-new.gif" />{/if}</td>
					<td>{$order.customer}</td>
					<td class="history_price"><span class="price">{displayPrice price=$order.total_paid currency=$order.id_currency no_utf8=false convert=false}</span></td>
					<td class="history_method">{$order.payment|escape:'htmlall':'UTF-8'}</td>
					<td class="history_state">{if isset($order.order_state)}{$order.order_state|escape:'htmlall':'UTF-8'}{/if}</td>
					<td class="history_invoice">
					{if isset($order.invoice) && $order.invoice && isset($invoiceAllowed) && $invoiceAllowed == 1}
						<a href="{$link->getModuleLink('agilemultipleseller', 'sellerpdfinvoice', ['id_order' => $order.id_order], true)}" title="{l s='Invoice' mod='agilemultipleseller'}" target="pdf"><img src="{$base_dir_ssl}//modules/agilemultipleseller/images/pdf.gif" alt="{l s='Invoice'}" class="icon" /></a>
						<a href="{$link->getModuleLink('agilemultipleseller', 'sellerpdfinvoice', ['id_order' => $order.id_order], true)}" title="{l s='Invoice' mod='agilemultipleseller'}" target="pdf">{l s='PDF' mod='agilemultipleseller'}</a>
					{else}-{/if}
					</td>
					<td class="history_detail">
					</td>
				</tr>
			{/foreach}
			</tbody>
		</table>
		</div> <!-- responsive -->
		<div class="row">
			{include file="module:agilemultipleseller/views/templates/front/pagination.tpl"}
		</div>
		<div id="block-order-detail" class="hidden">&nbsp;</div>
		{if count($orders)<1}
			<p class="alert alert-warning">{l s='You do not have any orders meet your search criteria.' mod='agilemultipleseller'}</p>
		{/if}
	</div> <!-- block-center -->
	</div> <!-- agile -->
	{/if}
	{include file="module:agilemultipleseller/views/templates/front/seller_footer.tpl"}

{/block}

{block name='javascript_bottom'}
    {$smarty.block.parent}

	<script type="text/javascript">
		var listurl = "{$link->getModuleLink('agilemultipleseller', 'sellerorders', [], true)}";
		var p = {$p};
		var n = {$n};
		var orderBy = "{$orderBy}";
		var orderWay = "{$orderWay}";
	</script>

{/block}
