{extends file='page.tpl'}

{block name='page_content'}

	{capture name=path}<a href="{$link->getPageLink('my-account.php')}">{l s='My Account' mod='agilemultipleseller'}</a><span class="navigation-pipe">{$navigationPipe}</span>{l s='My Seller Account'  mod='agilemultipleseller'}{/capture}
	<div id="agile">
	<h1>{l s='My Seller Account' mod='agilemultipleseller'}</h1>

	{include file="module:agilemultipleseller/views/templates/front/seller_tabs.tpl"}
	{if isset($seller_exists) AND $seller_exists}
		<div class="panel">
			<h3>{l s='Your account summary' mod='agilemultipleseller'}</h3>
			<form action="{$link->getModuleLink('agilemultipleseller', 'sellersummary', [], true)}" method="post" class="form-horizontal std" id="frmConvertingPayment">
				<div class="form-group">
					<label for="seller_status" class="control-label agile-col-xs-7 agile-col-sm-6 agile-col-md-3 agile-col-lg-2 agile-col-xl-2">
						<span>{l s='Seller Account Status' mod='agilemultipleseller'}</span>
					</label>
					<div class="agile-col-xs-4 agile-col-sm-6 agile-col-md-7 agile-col-lg-7 agile-col-xl-7">
						<span id="seller_status">{if $seller->active}<img src="{$base_dir_ssl}img/admin/enabled.gif" />&nbsp;&nbsp;{l s='Active' mod='agilemultipleseller'}{else}<img src="{$base_dir_ssl}img/admin/disabled.gif" />&nbsp;{l s='Inactive' mod='agilemultipleseller'}{/if}</span>
					</div>
				</div>
				<div class="form-group">
					<label for="products_stat" class="control-label agile-col-xs-7 agile-col-sm-6 agile-col-md-3 agile-col-lg-2 agile-col-xl-2">
						<span>{l s='Products Listed' mod='agilemultipleseller'}</span>
					</label>
					<div class="agile-col-xs-4 agile-col-sm-6 agile-col-md-7 agile-col-lg-7 agile-col-xl-7">
						<span id="products_stat">{$num_products}</span>
					</div>
				</div>
				<div class="form-group">
					<label for="orders_stat" class="control-label agile-col-xs-7 agile-col-sm-6 agile-col-md-3 agile-col-lg-2 agile-col-xl-2">
						<span>{l s='Orders Received' mod='agilemultipleseller'}</span>
					</label>
					<div class="agile-col-xs-4 agile-col-sm-6 agile-col-md-7 agile-col-lg-7 agile-col-xl-7">
						<span id="orders_stat">{$num_orders}</span>
					</div>
				</div>
				<div class="form-group">
					<label for="sales_stat" class="control-label agile-col-xs-7 agile-col-sm-6 agile-col-md-3 agile-col-lg-2 agile-col-xl-2">
						<span>{l s='Total Amount Sold' mod='agilemultipleseller'}</span>
					</label>
					<div class="agile-col-xs-4 agile-col-sm-6 agile-col-md-7 agile-col-lg-7 agile-col-xl-7">
						<table cellpadding="0" cellspacing="0">
						{foreach from=$totals_sold item=total}
						<tr><td align="right">
							<span id="sales_stat">{$total.currency->sign}&nbsp;{$total.amount}</span>
						</td><td>
							<span>&nbsp;{l s='Sale received in ' mod='agilemultipleseller'}{$total.currency->name}</span>
						</td></tr>
						{/foreach}
						</table>
					</div>
				</div>

				{if $is_seller_commission_installed}
					<div class="form-group">
						<label for="acct_baance"  class="control-label agile-col-xs-7 agile-col-sm-6 agile-col-md-3 agile-col-lg-2 agile-col-xl-2">
							<span>{l s='Account Balance' mod='agilemultipleseller'}</span>
						</label>
						<div class="agile-col-xs-4 agile-col-sm-6 agile-col-md-7 agile-col-lg-7 agile-col-xl-7">
							<span id="acct_baance">{$comcurrency->sign}&nbsp;{$account_balance}</span>&nbsp;&nbsp;
							{if isset($isSeller) && $isSeller}			
								{if $account_balance > 0}
									{if $is_agileprepaidcredit_installed}
									<a id="show_messagebox" onclick="validate_message('B2T','{$request_B2T}')" class="btn btn-primary" href="#confirm_submit">{$request_B2T}</a>
									{*
									<a id="show_messagebox" onclick="validate_message('MPR','{$request_MPR}')" class="btn btn-primary" href="#confirm_submit">{$request_MPR}</a>
									*}
									<input type="text" name="amount_to_convert" id="amount_to_convert" size="5" value="">
									{/if}
								{/if}
								{if $account_balance < 0}
									<span>{l s='You owe store this amount.' mod='agilemultipleseller'}&nbsp;</span>
									{if $use_paypal_forsellerpayment == 1}
									<span><a href="{$paycommission_url}">{l s='pay account balance now' mod='agilemultipleseller'}&nbsp;<img src="{$base_dir_ssl}modules/agilesellercommission/img/pay.png"></a></span>
									{/if}
								{/if}
							{/if}
						</div>
					</div>
				{/if}
				{if $is_agileprepaidcredit_installed}
					<div class="form-group">
						<label for="tkn_baance"  class="control-label agile-col-xs-7 agile-col-sm-6 agile-col-md-3 agile-col-lg-2 agile-col-xl-2">
							<span>{l s='Token Balance' mod='agilemultipleseller'}</span>
						</label>
						<div class="agile-col-xs-4 agile-col-sm-6 agile-col-md-7 agile-col-lg-7 agile-col-xl-7">
							<span id="tkn_baance">{$token_balance}</span>&nbsp;&nbsp;
							{if isset($isSeller) && $isSeller}			
								{if $token_balance > 0}
									{if $is_seller_commission_installed}
									<a id="show_messagebox" onclick="validate_message('T2B','{$request_T2B}')" class="btn btn-primary" href="#confirm_submit">{$request_T2B}</a>
									<input type="text" name="tokens_to_convert" id="tokens_to_convert" size="5" value="">
									{/if}
								{/if}
							{/if}
							<div style="display:none">
								<input type="hidden" name="submitRequest" id="submitRequest" value="">
								<div id="confirm_submit">
									<span id="msg_comfirm"><img width="20" height="20" src="{$base_dir_ssl}modules/agilemultipleseller/images/icon-info.png">&nbsp;{l s='Are you sure want to perform ' mod='agilemultipleseller'}<span id="msg_request_1">??2</span></span>
									<span id="msg_error"><img width="20" height="20" src="{$base_dir_ssl}modules/agilemultipleseller/images/icon-error.png">&nbsp;{l s='Please enter correct amount for ' mod='agilemultipleseller'}<span id="msg_request_2">??1</span></span>
									<br><br><br>
									<center>
										<input type="button" class="btn btn-primary" name="btnYes" id="btnYes" onclick="fb_yesclick()" value="{l s='Yes' mod='agilemultipleseller'}">&nbsp;
										<input type="button" class="btn btn-primary" name="btnNo" id="btnNo"  onclick="fb_noclick()" value="  {l s='No' mod='agilemultipleseller'} ">
										<input type="button" class="btn btn-primary" name="btnOK" id="btnOK" onclick="fb_okclick()" value="{l s='OK' mod='agilemultipleseller'}">&nbsp;
									</center>
								</div>
							</div>
						</div>
					</div>
				{/if}

			</form>
			{if isset($ams_custom_selllersummarybag) && $ams_custom_selllersummarybag==1}{include file="module:agilemultipleseller/custom/selllersummarybag/sellersummarybag.tpl"}{/if}
		</div>
	{else}
		<form action="{$link->getModuleLink('agilemultipleseller', 'sellersummary', [], true)}" method="post" class="form-horizontal std" id="frmSellerSummary">
		<p>
			{l s='You do not yet have a seller account.' mod='agilemultipleseller'}
			{l s='Once you register for a seller account, you will be able to list your products in this store upon approval.' mod='agilemultipleseller'}
		</p>
		<br />
		<p>
			{l s='Do you want create a seller account now so that you can list your products for sale?' mod='agilemultipleseller'}
		</p>

		{if isset($id_cms_seller_terms) AND $id_cms_seller_terms >0}
			<div class="checkbox">
				<input type="checkbox" name="iagree" id="iagree">{l s='Yes, I have read and I agree on the Seller Terms & conditions, Please create a seller account for me' mod='agilemultipleseller'}
			</div>
			<br />
			<p class="clearfix">
					<span class="agile-term">
						<a href="{$link_terms}" id="seller_terms" name="seller_terms" class="iframe">{l s='Seller Terms & conditions(read)' mod='agilemultipleseller'}</a>
					</span>
			</p>
		{/if}
		<center>
			<input type="hidden" name="submitSellerAccount" id="submitSellerAccount" value="1">
			<input type="button" class="btn btn-primary" onclick="javascript:check_terms({$id_cms_seller_terms})" name="submitSellerAccount" value="{l s='Yes, Sign me up' mod='agilemultipleseller'}" />
		</center>
		<br><br>
		</form>
	{/if}

		{* ============= Membership integration ============ *}
		<div id="fsMymembershipInfo" style="display:none;">
			<br><br>
			<div id="divMymembershipInfo">
				{l s='Loading your membership information.....' mod='agilemultipleseller'}<br />
				<img src="{$base_dir_ssl}modules/agilekernel/img/loading.gif" />
			</div>
		</div>
	</div>
	{include file="module:agilemultipleseller/views/templates/front/seller_footer.tpl"}

{/block}


{block name='javascript_bottom'}
    {$smarty.block.parent}

	<script type="text/javascript">
		var membership_module_integrated = {$membership_module_integrated};
		var msg = "{l s='You must agree on Seller Terms & Conditions' mod='agilemultipleseller'}";
		var mymembership_url = "{$link->getModuleLink('agilemembership', 'mymembership', [content_only=>1], true)}";
	</script>

{/block}