{extends file='page.tpl'}

{block name='page_content'}

	{capture name=path}<a href="{$link->getPageLink('my-account', true)}">{l s='My Account' mod='agilemultipleseller'}</a><span class="navigation-pipe">{$navigationPipe}</span>{l s='My Seller Account'  mod='agilemultipleseller'}{/capture}

	<h1>{l s='My Seller Account' mod='agilemultipleseller'}</h1>

	{include file="module:agilemultipleseller/views/templates/front/seller_tabs.tpl"}
	{if isset($isSeller) AND $isSeller}
	<div id="agile">
<div class="block-center clearfix" id="block-history">
		<div class="row" style="line-height: 150%;">
			<div class="agile-col-sm-2" style="margin-top:10px;margin-bottom:10px;">
				<a class="agile-btn agile-btn-default" href="{$link->getModuleLink('agilemultipleseller', 'sellerproductdetail', ['id_product' =>0], true)}">
						<i class="icon-plus-sign"></i>&nbsp;{l s='Add Product' mod='agilemultipleseller'}
				</a>
			</div>
			<div class="agile-col-sm-2">
				<input class="form-control" class="ac_input" autocomplete="off" type="text" value="" id="product_autocopy_input" />
			</div>
			<div class="agile-col-sm-8">
			{l s='Type some letters of product name to find a product to copy' mod='agilemultipleseller'}
			</div>
		</div>
		<hr>
		<div class="table responsive clearfix">
		<table id="product-list" class="table table-responsive">
			<thead>
				<tr>
				<th class="first_item" style="width:30px;"><input type="checkbox" name="chkAll" id="chkAll" value="all"></th>
		        <th class="item" style="width:60px">{l s='ID' mod='agilemultipleseller'}&nbsp;<a style="color:{if $orderBy=='p.id_product' && $orderWay=='ASC'}#000{else}#00aff0{/if};cursor:pointer" onclick="orderByWay('p.id_product','ASC')"><i class="icon-caret-up"></i></a>&nbsp;<a  style="color:{if $orderBy=='p.id_product' && $orderWay=='DSSC'}#000{else}#00aff0{/if};cursor:pointer" onclick="orderByWay('p.id_product','DESC')"><i class="icon-caret-down"></i></a></th>
		        <th class="item">{l s='Photo' mod='agilemultipleseller'}</th>
		        <th class="item">{l s='Name' mod='agilemultipleseller'}&nbsp;<a style="color:{if $orderBy=='pl.name' && $orderWay=='ASC'}#000{else}#00aff0{/if};cursor:pointer" onclick="orderByWay('pl.name','ASC')"><i class="icon-caret-up"></i></a>&nbsp;<a style="color:{if $orderBy=='pl.name' && $orderWay=='DESC'}#000{else}#00aff0{/if};cursor:pointer" onclick="orderByWay('pl.name','DESC')"><i class="icon-caret-down"></i></a></th>
		        <th class="item">{l s='Category' mod='agilemultipleseller'}&nbsp;<a style="color:{if $orderBy=='cl.name' && $orderWay=='ASC'}#000{else}#00aff0{/if};cursor:pointer" onclick="orderByWay('cl.name','ASC')"><i class="icon-caret-up"></i></a>&nbsp;<a style="color:{if $orderBy=='cl.name' && $orderWay=='DESC'}#000{else}#00aff0{/if};cursor:pointer" onclick="orderByWay('cl.name','DESC')"><i class="icon-caret-down"></i></a></th>
		        <th class="item">{l s='Price' mod='agilemultipleseller'}&nbsp;<a style="color:{if $orderBy=='p.price' && $orderWay=='ASC'}#000{else}#00aff0{/if};cursor:pointer" onclick="orderByWay('p.price','ASC')"><i class="icon-caret-up"></i></a>&nbsp;<a style="color:{if $orderBy=='p.price' && $orderWay=='DESC'}#000{else}#00aff0{/if};cursor:pointer" onclick="orderByWay('p.price','DESC')"><i class="icon-caret-down"></i></a></th>
		        <th class="item" style="width:120px">{l s='Final Price' mod='agilemultipleseller'}&nbsp;<a style="color:{if $orderBy=='p.price_final' && $orderWay=='ASC'}#000{else}#00aff0{/if};cursor:pointer" onclick="orderByWay('p.price_final','ASC')"><i class="icon-caret-up"></i></a>&nbsp;<a style="color:{if $orderBy=='p.price_final' && $orderWay=='DSSC'}#000{else}#00aff0{/if};cursor:pointer" onclick="orderByWay('p.price_final','DESC')"><i class="icon-caret-down"></i></a></th>
		        <th class="item">{l s='Quantity' mod='agilemultipleseller'}&nbsp;<a style="color:{if $orderBy=='sav.quantity' && $orderWay=='ASC'}#000{else}#00aff0{/if};cursor:pointer" onclick="orderByWay('sav.quantity','ASC')"><i class="icon-caret-up"></i></a>&nbsp;<a style="color:{if $orderBy=='sav.quantity' && $orderWay=='DESC'}#000{else}#00aff0{/if};cursor:pointer" onclick="orderByWay('sav.quantity','DESC')"><i class="icon-caret-down"></i></a></th>
		        <th class="item" style="width:90px">{l s='Active' mod='agilemultipleseller'}&nbsp;<a style="color:{if $orderBy=='p.active' && $orderWay=='ASC'}#000{else}#00aff0{/if};cursor:pointer" onclick="orderByWay('p.active','ASC')"><i class="icon-caret-up"></i></a>&nbsp;<a style="color:{if $orderBy=='p.active' && $orderWay=='DESC'}#000{else}#00aff0{/if};cursor:pointer" onclick="orderByWay('p.active','DESC')"><i class="icon-caret-down"></i></a></th>
		        {if $is_apprpved_required}
		        <th class="item" style="width:90px">{l s='Approved' mod='agilemultipleseller'}&nbsp;<a style="cursor:pointer" onclick="orderByWay('po.approved','ASC')"><i class="icon-caret-up"></i></a>&nbsp;<a style="cursor:pointer" onclick="orderByWay('po.approved','DESC')""><i class="icon-caret-down"></i></a></th>
		        {/if}
		        <th class="item" style="width:130px">{l s='Date' mod='agilemultipleseller'}&nbsp;<a style="color:{if $orderBy=='p.date_add' && $orderWay=='ASC'}#000{else}#00aff0{/if};cursor:pointer" onclick="orderByWay('p.date_add','ASC')"><i class="icon-caret-up"></i></a>&nbsp;<a style="color:{if $orderBy=='p.date_add' && $orderWay=='DESC'}#000{else}#00aff0{/if};cursor:pointer" onclick="orderByWay('p.date_add','DESC')"><i class="icon-caret-down"></i></a></th>
		        <th class="last_item">
					<input type="button" class="agile-btn agile-btn-default"  name="export" value="{l s='Export' mod='agilemultipleseller'}" id="product_export" onclick="CSVOnClick()"/>
				</th>
				</tr>
			<tr>
				<th>{*Checkbox*}</th>
				<th>
					<input type="text" name="filter_id_product" id="filter_id_product" value="{$filter_id_product}">
				</th>
				<th>{*Photo*}</th>
				<th>
					<input type="text" name="filter_name" id="filter_name" value="{$filter_name}">
				</th>
				<th>
					<input type="text" name="filter_category" id="filter_category" value="{$filter_category}">
				</th>
				<th>
					<input type="text" name="filter_price" id="filter_price" value="{$filter_price}">
				</th>
				<th>{* final price*}
				</th>
				<th>
					<input type="text" name="filter_quantity" id="filter_quantity" value="{$filter_quantity}">
				</th>
				<th>
					<select name="filter_active" id="filter_active">
						<option value=""  {if strlen($filter_active)==0}selected{/if}></option>
						<option value="1" {if $filter_active=="1"}selected{/if}>Yes</option>
						<option value="0" {if strlen($filter_active)>0 && $filter_active=="0"}selected{/if}>No</option>
					</select>
				</th>
		        {if $is_apprpved_required}
				<th>
					<select name="filter_approved" id="filter_approved">
						<option value=""  {if strlen($filter_approved)==0}selected{/if}></option>
						<option value="1" {if $filter_approved=="1"}selected{/if}>Yes</option>
						<option value="0" {if strlen($filter_approved)>0 && $filter_approved=="0"}selected{/if}>No</option>
					</select>
				</th>
				{/if}
				<th>
					<input class="datepicker" type="text" id="filter_date_add_from" name="filter_date_add_from" value="{$filter_date_add_from}" style="width:85px;" maxlength="10" autocomplete="off" />
					<input class="datepicker" type="text" id="filter_date_add_to" name="filter_date_add_to" value="{$filter_date_add_to}" style="width:85px;" maxlength="10" autocomplete="off" />				
				</th>
				<th>
					<input type="button" class="agile-btn agile-btn-default"  name="reset" value="{l s='Reset' mod='agilemultipleseller'}" id="reset" onclick="ResetOnClick()"/><br>
					<input type="button" class="agile-btn agile-btn-default"  name="btnGo" value="{l s='Search' mod='agilemultipleseller'}" onclick="goOnClick()">
				</th>
			</tr>
			</thead>
			<tbody>
			{foreach from=$products item=product name=myLoop}
    		{assign var='detail_url' value=$link->getModuleLink('agilemultipleseller', 'sellerproductdetail', ['id_product' => $product.id_product], true)}
				<tr class="{if $smarty.foreach.myLoop.first}first_item{elseif $smarty.foreach.myLoop.last}last_item{else}item{/if} {if $smarty.foreach.myLoop.index % 2}alternate_item{/if}">
					<td class="pointer left" ><input type="checkbox" name="chkProd_{$product.id_product}" id="chkProd_{$product.id_product}" value="{$product.id_product}"></td>
					<td class="pointer left" onclick="document.location = '{$detail_url}'">
						<a class="color-myaccount" href="{$detail_url}">{$product.id_product}</a>
					</td>
					<td class="pointer left" onclick="document.location = '{$detail_url}'">
						<a href="{$detail_url}">
						{if $product.id_image}
							<img src="{$link->getImageLink($product['name'], $product['id_image'], 'small_default')}" />
						{else}
							<img src="{$base_dir_ssl}modules/agilemultipleseller/images/en-medium_default.jpg" />
						{/if}
						</a>
						</td>
					<td class="pointer left" onclick="document.location = '{$detail_url}'"><a href="{$detail_url}">{$product.name}</a></td>
					<td class="pointer left" onclick="document.location = '{$detail_url}'">{$product.name_category}</td>
					<td class="pointer right" onclick="document.location = '{$detail_url}'"><span class="price">{displayPrice price=$product.price currency=$def_id_currency no_utf8=false convert=false}</span></td>
					<td class="pointer right" onclick="document.location = '{$detail_url}'"><span class="price">{displayPrice price=$product.price_final currency=$def_id_currency no_utf8=false convert=false}</span></td>
					<td class="pointer center" onclick="document.location = '{$detail_url}'">{$product.sav_quantity}</td>
					<td class="center">
						{if $product.active == 1}
							<a style="cursor:pointer" onclick="actionOnProduct('inactive', {$product.id_product})"><img src="{$base_dir_ssl}img/admin/enabled.gif" /></a>
						{else}
							<a style="cursor:pointer" onclick="actionOnProduct('active', {$product.id_product})" ><img src="{$base_dir_ssl}img/admin/disabled.gif" /></a>
						{/if}
					</td>
					{if $is_apprpved_required}
					<td align="center" valign="middle">
						{if $product.approved == 1}
						<img src="{$base_dir_ssl}img/admin/enabled.gif" />
						{else}
						<img src="{$base_dir_ssl}img/admin/disabled.gif" />
						{/if}
					</td>
					{/if}
					<td>
						{$product.date_add}
					</td>
					<td class="center">
						<a style="cursor:pointer" onclick="onClickConfirm('delete',{$product.id_product})"><img src="{$base_dir_ssl}img/admin/delete.gif" /></a><br>
						<a style="cursor:pointer" onclick="onClickConfirm('duplicate',{$product.id_product})"><img src="{$base_dir_ssl}img/admin/duplicate.png" /></a>
					</td>
				</tr>
			{/foreach}
			</tbody>
		</table>
		<div class="row">
			<div class="col-md-3 col-sm-6 col-xs-12">
				<form action="{$link->getModuleLink('agilemultipleseller', 'sellerproducts', ['orderBy'=>$orderBy,'orderWay'=>$orderWay], true)}" id="frmBulkAction" method="post">
				<select name="bulkAction" id="bulkAction" style="width:150px;">
					<option value="">{l s='-- Bulk Action --' mod='agilemultipleseller'}</option>
					<option value="delete">{l s='Delete' mod='agilemultipleseller'}</option>
					<option value="enable">{l s='Enable' mod='agilemultipleseller'}</option>
					<option value="disable">{l s='Disable' mod='agilemultipleseller'}</option>
				</select>
				<input type="hidden" name="bulkActionData" id="bulkActionData" value="[]">
				</form>
			</div>
			<div class="col-md-9 col-sm-6  col-xs-12">
				{include file="module:agilemultipleseller/views/templates/front/pagination.tpl"}
			</div>
		</div>

		</div> <!-- table-responsive -->
		<div id="block-product-detail" class="hidden">&nbsp;</div>
	    {if count($products) < 1}
			<div class="row">
				<p class="alert alert-warning">{l s='You do not have any products meet your search criteria' mod='agilemultipleseller'}</p>
			</div>
		{/if}
	</div>
	</div> 
	<!-- agile -->
	{/if}
	{include file="module:agilemultipleseller/views/templates/front/seller_footer.tpl"}

{/block}

{block name='javascript_bottom'}
    {$smarty.block.parent}

	<script type="text/javascript">
		var listurl = "{$link->getModuleLink('agilemultipleseller', 'sellerproducts', [], true)}";
		var allowCopyMainStoreProduct = {$allowCopyMainStoreProduct};
		var n = {$n};
		var p = {$p};
		var orderBy = "{$orderBy}";
		var orderWay = "{$orderWay}";
		var msgDelete = "{$msgDelete}";
		var msgDuplicate = "{$msgDuplicate}";
		var duplicateURL = "{$link->getModuleLink('agilemultipleseller', 'sellerproducts', ['process' => 'duplicate'], true)}";
		var bulkmsg_delete = "{l s='Are you sure want proceed to delete selected items?' mod='agilemultipleseller'}";
		var bulkmsg_enable = "{l s='Are you sure want proceed to enable selected items?' mod='agilemultipleseller'}";
		var bulkmsg_disable = "{l s='Are you sure want proceed to disable selected items?' mod='agilemultipleseller'}";

	</script>

{/block}
