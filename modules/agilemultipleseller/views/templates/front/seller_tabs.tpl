	{if isset($warnings) AND !empty($warnings)}
    {foreach from=$warnings item=warning}
			<div class="alert alert-danger">
			{$warning}<br>
			</div>
		{/foreach}
	{/if}

    {if isset($cfmmsg_flag) && $cfmmsg_flag == 1}
	<div style="margin:5px;padding:10px;border:solid green 1px;">
			<img src="{$base_dir_ssl}/modules/agilemultipleseller/images/icon-ok.png" alt="{l s='Confirmation' mod='agilemultipleseller'}" />&nbsp;{l s='Update successful.' mod='agilemultipleseller'}
	</div>
	{/if}
    {if $seller_exists && !$isSeller}
	<div style="margin:5px;padding:10px;border:solid green 1px;">
			<img src="{$base_dir_ssl}/modules/agilemultipleseller/images/icon-attention.png" height="20" alt="{l s='Attention' mod='agilemultipleseller'}" />&nbsp;{l s='Your account is under approval or pending. Some functions are not available to you.' mod='agilemultipleseller'}
	</div>
	{/if}

    {if isset($pay_options_link) && !empty($pay_options_link)}{$pay_options_link nofilter}{/if}
    <nav id="nav-main" class="agile-nav">
		<ul class="idTabs idTabsShort clearfix">
			<li {if $seller_tab_id==1}class="current"{/if}><a id="seler_summary" href="{if $seller_tab_id==1}#idTab1{else}{$link->getModuleLink('agilemultipleseller', 'sellersummary', [],true)}{/if}">{l s='Summary' mod='agilemultipleseller'}</a></li>
			{if $seller_exists}
				<li {if $seller_tab_id==2}class="current"{/if}><a id="seller_business" href="{if $seller_tab_id==2}#idTab2{else}{$link->getModuleLink('agilemultipleseller', 'sellerbusiness', [], true)}{/if}">{l s='Business Info' mod='agilemultipleseller'}</a></li>
{* LOONES
				<li {if $seller_tab_id==5}class="current"{/if}><a id="seller_Payments" href="{if $seller_tab_id==5}#idTab5{else}{$link->getModuleLink('agilemultipleseller', 'sellerpayments', [], true)}{/if}">{l s='Payment Info' mod='agilemultipleseller'}</a></li>
*}
				{if $isSeller}
					<li {if $seller_tab_id==3}class="current"{/if}><a id="seller_products" href="{if $seller_tab_id==3}#idTab3{else}{$link->getModuleLink('agilemultipleseller', 'sellerproducts', [], true)}{/if}">{l s='Products' mod='agilemultipleseller'}</a></li> 
					<li {if $seller_tab_id==4}class="current"{/if}><a id="seller_orders" href="{if $seller_tab_id==4}#idTab4{else}{$link->getModuleLink('agilemultipleseller', 'sellerorders', [], true)}{/if}">{l s='Orders' mod='agilemultipleseller'}</a></li>
					{if $is_seller_shipping_installed}
					<li {if $seller_tab_id==6}class="current"{/if}><a id="seller_shipping" href="{if $seller_tab_id==6}#idTab6{else}{$link->getModuleLink('agilesellershipping', 'sellercarriers', [], true)}{/if}">{l s='Shipping' mod='agilemultipleseller'}</a></li>
					{/if}
					{if $is_seller_commission_installed} {* This has not been implemented yet *}
					<li {if $seller_tab_id==7}class="current"{/if}><a id="seller_history" href="{if $seller_tab_id==7}#idTab6{else}{$link->getModuleLink('agilemultipleseller', 'sellerhistory', [], true)}{/if}">{l s='Account History' mod='agilemultipleseller'}</a></li>
					{/if}
					{if $is_seller_messenger_installed}
					<li {if $seller_tab_id==8}class="current"{/if}><a id="seller_messages" href="{if $seller_tab_id==8}#idTab8{else}{$link->getModuleLink('agilesellermessenger', 'sellermessages', [], true)}{/if}">{l s='Messages' mod='agilemultipleseller'}</a></li>
					{/if}
					{if $is_seller_ratings_installed}
					<li {if $seller_tab_id==9}class="current"{/if}><a id="seller_ratings" href="{if $seller_tab_id==9}#idTab9{else}{$link->getModuleLink('agilesellerratings', 'myreviews', [], true)}{/if}">{l s='Feedback' mod='agilemultipleseller'}</a></li>
					{/if}
					{if $is_seller_listoptions_installed}
					<li {if $seller_tab_id==10}class="current"{/if}><a id="seller_expiredproducts" href="{if $seller_tab_id==10}#idTab10{else}{$link->getModuleLink('agilesellerlistoptions', 'expiredproducts', [], true)}{/if}">{l s='Expired Products' mod='agilemultipleseller'}</a></li>
					{/if}
					{if $is_seller_tools_installed}
					<li {if $seller_tab_id==11}class="current"{/if}><a id="seller_tools" href="{if $seller_tab_id==11}#idTab11{else}{$link->getModuleLink('agilesellertools', 'sellertoollist', [], true)}{/if}">{l s='Seller Tools' mod='agilemultipleseller'}</a></li>
					{/if}
					{**** Temporary disable pickup center integration ************ 
					{if $is_seller_pickupcenter_installed}
					<li><a id="seller_pickuplocations" href="{if $seller_tab_id==11}#idTab11{else}{$link->getModuleLink('agilepickupcenter', 'pickuplocations', [], true)}{/if}">{l s='Locations' mod='agilemultipleseller'}</a></li>
					{/if}
					*************************************************************}
				{/if}
			{/if}
		</ul>
	</nav>
	<div id="nav-trigger">
    <span style="width:100%">{l s='My Account' mod='agilemultipleseller'}</span>
	</div>
	<nav id="nav-mobile"></nav>