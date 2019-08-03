{extends file='page.tpl'}

{block name='page_content'}

	{capture name=path}<a href="{$link->getPageLink('my-account', true)}">{l s='My Account' mod='agilemultipleseller'}</a><span class="navigation-pipe">{$navigationPipe}</span>{l s='My Seller Account'  mod='agilemultipleseller'}{/capture}

	<h1>{l s='My Seller Account' mod='agilemultipleseller'}</h1>

	{include file="module:agilemultipleseller/views/templates/front/seller_tabs.tpl"}

	{if isset($seller_exists) AND $seller_exists}
	<div id="agile">
	<form action="{$link->getModuleLink('agilemultipleseller', 'sellerpayments', [], true)}" method="post" class="std" id="add_adress">
	{if count($integratedModules) >0 }
		{foreach from=$integratedModules item=imod}
			<legend><strong></strng>{$imod['desc']}</strong></legend>
			<input type="hidden"  name="id_agile_seller_paymentinfo_{$imod['name']}" value="{$imod['data']->id}" />
			<div class="form-group">
				<label class="control-label agile-col-sm-4 agile-col-md-3 agile-col-lg-2 agile-col-xl-2" for="in_use_{$imod['name']}">
					<span>{l s='Use this module' mod='agilemultipleseller'}</span>
				</label>
				<div class="agile-col-sm-8 agile-col-md-9 agile-col-lg-9 agile-col-xl-9">
					<table><tr>
					<td><input type="checkbox" id="in_use_{$imod['name']}" name ="in_use_{$imod['name']}"  alt="Do you use use this payment module?" value="1" {if $imod['data']->in_use eq 1}checked="checked"{/if} /></td>
					<td>
						{l s='Please choose if you want to use this module to collect payment from buyers.' mod='agilemultipleseller'}
						{if $imod['name'] == 'agilepaypal'}
							<br>
							{l s='Or if you want to use Paypal to receive payment from store and perform payment to store' mod='agilemultipleseller'}
						{/if}
					</td>
					</tr></table>
				</div>
			</div>
			{* === Info 1 ===*}
			<div class="form-group" style="display:{if $imod['info1']['label'] =='' || $imod['info1']['label'] == 'N/A'}none;{/if}">
				<label class="control-label agile-col-sm-4 agile-col-md-3 agile-col-lg-2 agile-col-xl-2" for="info1_{$imod['name']}">
					<span>{$imod['info1']['label']} </span>
					{if isset($imod['info1']['tooltip']) && !empty($imod['info1']['tooltip'])}<span title="{$imod['info1']['tooltip']}">[?]</span>{/if}
				</label>
				<div class="agile-col-sm-8 agile-col-md-9 agile-col-lg-9 agile-col-xl-9">
					<input type="text" id="info1_{$imod['name']}" size="80" name="info1_{$imod['name']}" value="{$imod['data']->info1|escape:'htmlall':'UTF-8'}" {if isset($imod['info1']['is_readonly']) && $imod['info1']['is_readonly']==1}readonly{/if} />
				</div>
			</div>
			{* === Info 2 ===*}
			<div class="form-group" style="display:{if $imod['info2']['label'] =='' || $imod['info2']['label'] == 'N/A'}none;{/if}">
				<label class="control-label agile-col-sm-4 agile-col-md-3 agile-col-lg-2 agile-col-xl-2" for="info2_{$imod['name']}">
					<span>{$imod['info2']['label']} </span>
					{if isset($imod['info2']['tooltip']) && !empty($imod['info2']['tooltip'])}<span title="{$imod['info2']['tooltip']}">[?]</span>{/if}
				</label>
				<div class="agile-col-sm-8 agile-col-md-9 agile-col-lg-9 agile-col-xl-9">
					<input type="text" id="info2_{$imod['name']}" size="80" name="info2_{$imod['name']}" value="{$imod['data']->info2|escape:'htmlall':'UTF-8'}" {if isset($imod['info2']['is_readonly']) && $imod['info2']['is_readonly']==1}readonly{/if} />
				</div>
			</div>
			{* === Info 3 ===*}
			<div class="form-group" style="display:{if $imod['info3']['label'] =='' || $imod['info3']['label'] == 'N/A'}none;{/if}">
				<label class="control-label agile-col-sm-4 agile-col-md-3 agile-col-lg-2 agile-col-xl-2" for="info3_{$imod['name']}">
					<span>{$imod['info3']['label']} </span>
					{if isset($imod['info3']['tooltip']) && !empty($imod['info3']['tooltip'])}<span title="{$imod['info3']['tooltip']}">[?]</span>{/if}
				</label>
				<div class="agile-col-sm-8 agile-col-md-9 agile-col-lg-9 agile-col-xl-9">
					<input type="text" id="info3_{$imod['name']}" size="80" name="info3_{$imod['name']}" value="{$imod['data']->info3|escape:'htmlall':'UTF-8'}" {if isset($imod['info3']['is_readonly']) && $imod['info3']['is_readonly']==1}readonly{/if} />
				</div>
			</div>
			{* === Info 4 ===*}
			<div class="form-group" style="display:{if $imod['info4']['label'] =='' || $imod['info4']['label'] == 'N/A'}none;{/if}">
				<label class="control-label agile-col-sm-4 agile-col-md-3 agile-col-lg-2 agile-col-xl-2" for="info4_{$imod['name']}">
					<span>{$imod['info4']['label']} </span>
					{if isset($imod['info4']['tooltip']) && !empty($imod['info4']['tooltip'])}<span title="{$imod['info4']['tooltip']}">[?]</span>{/if}
				</label>
				<div class="agile-col-sm-8 agile-col-md-9 agile-col-lg-9 agile-col-xl-9">
					<input type="text" id="info4_{$imod['name']}" size="80" name="info4_{$imod['name']}" value="{$imod['data']->info4|escape:'htmlall':'UTF-8'}" {if isset($imod['info4']['is_readonly']) && $imod['info4']['is_readonly']==1}readonly{/if} />
				</div>
			</div>
			{* === Info 5 ===*}
			<div class="form-group" style="display:{if $imod['info5']['label'] =='' || $imod['info5']['label'] == 'N/A'}none;{/if}">
				<label class="control-label agile-col-sm-4 agile-col-md-3 agile-col-lg-2 agile-col-xl-2" for="info5_{$imod['name']}">
					<span>{$imod['info5']['label']}</span>
					{if isset($imod['info5']['tooltip']) && !empty($imod['info5']['tooltip'])}<span title="{$imod['info5']['tooltip']}">[?]</span>{/if}
				</label>
				<div class="agile-col-sm-8 agile-col-md-9 agile-col-lg-9 agile-col-xl-9">
					<input type="text" id="info5_{$imod['name']}" size="80" name="info5_{$imod['name']}" value="{$imod['data']->info5|escape:'htmlall':'UTF-8'}" {if isset($imod['info5']['is_readonly']) && $imod['info5']['is_readonly']==1}readonly{/if}/>
				</div>
			</div>
			{* === Info 6 ===*}
			<div class="form-group" style="display:{if $imod['info6']['label'] =='' || $imod['info6']['label'] == 'N/A'}none;{/if}">
				<label class="control-label agile-col-sm-4 agile-col-md-3 agile-col-lg-2 agile-col-xl-2" for="info6_{$imod['name']}">
					<span>{$imod['info6']['label']} </span>
					{if isset($imod['info6']['tooltip']) && !empty($imod['info6']['tooltip'])}<span title="{$imod['info6']['tooltip']}">[?]</span>{/if}
				</label>
				<div class="agile-col-sm-8 agile-col-md-9 agile-col-lg-9 agile-col-xl-9">
					<input type="text" id="info6_{$imod['name']}" size="80" name="info6_{$imod['name']}" value="{$imod['data']->info6|escape:'htmlall':'UTF-8'}" {if isset($imod['info6']['is_readonly']) && $imod['info6']['is_readonly']==1}readonly{/if} />
				</div>
			</div>
			{* === Info 7 ===*}
			<div class="form-group" style="display:{if $imod['info7']['label'] =='' || $imod['info7']['label'] == 'N/A'}none;{/if}">
				<label class="control-label agile-col-sm-4 agile-col-md-3 agile-col-lg-2 agile-col-xl-2" for="info7_{$imod['name']}">
					<span>{$imod['info7']['label']} </span>
					{if isset($imod['info7']['tooltip']) && !empty($imod['info7']['tooltip'])}<span title="{$imod['info7']['tooltip']}">[?]</span>{/if}
				</label>
				<div class="agile-col-sm-8 agile-col-md-9 agile-col-lg-9 agile-col-xl-9">
					<input type="text" id="info7_{$imod['name']}" size="80" name="info7_{$imod['name']}" value="{$imod['data']->info7|escape:'htmlall':'UTF-8'}" {if isset($imod['info7']['is_readonly']) && $imod['info7']['is_readonly']==1}readonly{/if} />
				</div>
			</div>
			{* === Info 8 ===*}
			<div class="form-group" style="display:{if $imod['info8']['label'] =='' || $imod['info8']['label'] == 'N/A'}none;{/if}">
				<label class="control-label agile-col-sm-4 agile-col-md-3 agile-col-lg-2 agile-col-xl-2" for="info8_{$imod['name']}">
					<span>{$imod['info8']['label']} </span>
					{if isset($imod['info8']['tooltip']) && !empty($imod['info8']['tooltip'])}<span title="{$imod['info8']['tooltip']}">[?]</span>{/if}
				</label>
				<div class="agile-col-sm-8 agile-col-md-9 agile-col-lg-9 agile-col-xl-9">
					<input type="text" id="info8_{$imod['name']}" size="80" name="info8_{$imod['name']}" value="{$imod['data']->info8|escape:'htmlall':'UTF-8'}" {if isset($imod['info8']['is_readonly']) && $imod['info8']['is_readonly']==1}readonly{/if} />
				</div>
			</div>
			{* === extra Links ===*}
			<div class="form-group" style="display:{if !isset($imod['elinks']) || empty($imod['elinks'])}none;{/if}">
				<label class="control-label agile-col-sm-4 agile-col-md-3 agile-col-lg-2 agile-col-xl-2" for="elinks_{$imod['name']}">
					<span>&nbsp;</span>
				</label>
				<div class="agile-col-sm-8 agile-col-md-9 agile-col-lg-9 agile-col-xl-9">
				{if isset($imod['elinks'])}
				{foreach from=$imod['elinks'] item=elink}
					<span class="agile-col-md-4"><a href="{$elink['url']}" title="{if isset($elink['tooltip'])}{$elink['tooltip']}{/if}" target="{if isset($elink['target'])}{$elink['target']}{/if}">{$elink['label']}</a></span>
				{/foreach}
				{/if}
				</div>
			</div>
		{/foreach}
		<br>
		<p class="submit2">
			<center>
				<input type="hidden" name="id_sellerinfo" value="{$sellerinfo->id|intval}" />
				<button type="submit" class="agile-btn agile-btn-default" name="submitSellerinfo" id="submitSellerinfo" value="{l s='Save' mod='agilemultipleseller'}">
				<i class="icon-save"></i>&nbsp;<span>{l s='Save' mod='agilemultipleseller'}</span></button >
			</center>
		</p>
	{else}
		<p>
			{l s='There is no payment modules found that supports seller collects payment.' mod='agilemultipleseller'}
			<br>
			<br>
		</p>
	{/if}
	</form> 
	</div>
	{/if}
	{include file="module:agilemultipleseller/views/templates/front/seller_footer.tpl"}

{/block}

{block name='javascript_bottom'}
    {$smarty.block.parent}

{/block}