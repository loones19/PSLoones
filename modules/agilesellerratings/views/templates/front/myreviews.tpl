{extends file='page.tpl'}

{block name='page_content'}

	{capture name=path}<a href="{$link->getPageLink('my-account', true)}">{l s='My Account' mod='agilesellerratings'}</a><span class="navigation-pipe">{$navigationPipe}</span>{l s='My Seller Account'  mod='agilesellerratings'}{/capture}

	<h1>{l s='My Seller Account' mod='agilesellerratings'}</h1>
	{include file="module:agilemultipleseller/views/templates/front/seller_tabs.tpl"}

	{if isset($isSeller) AND $isSeller}

	<div class="block-center" id="block-history">
		{if $reviews && count($reviews)}

		{* include file="$tpl_dir./pagination.tpl" *}

		<br>{* this is for FireFox *}
		<div class="table-responsive clearfix">
		<table id="reviews-list" class="table">
			<thead>
				<tr>
					<th class="first_item">{l s='ID' mod='agilesellerratings'}</th>
					<th class="item">{l s='Reviewer' mod='agilesellerratings'}</th>
					<th class="item">{l s='Message' mod='agilesellerratings'}</th>
					<th class="item">{l s='Your Response' mod='agilesellerratings'}</th>
					<th class="last_item">{l s='Action' mod='agilesellerratings'}</th>
				</tr>
			</thead>
			<tbody>
			{foreach from=$reviews item=review name=myLoop}
				<tr class="{if $smarty.foreach.myLoop.first}first_item{elseif $smarty.foreach.myLoop.last}last_item{else}item{/if} {if $smarty.foreach.myLoop.index % 2}alternate_item{/if}">
					<td class="history_link bold">
						{$review.id_agile_rating}
					</td>
					<td>
						<p style="overflow:hidden;white-space:nowrap;"> 
						{l s='At' mod='agilesellerratings'}:{$review.date_add}<br>
						{l s='By' mod='agilesellerratings'}:{$review.customer|nl2br nofilter}<br>
						{l s='Rating' mod='agilesellerratings'}:{$review.grade|round:1}
						</p>
					</td>
					<td>{$review.content|nl2br nofilter}</td>
					<td>{$review.response|nl2br nofilter}</td>
					<td>
						<button type="submit" class="button agile-btn agile-btn-default"  onclick="openMessageForm({$review.id_agile_rating})">
							<span>{l s='Respond' mod='agilesellerratings'}&nbsp;<i class="icon-mail-reply"></i></span>
						</button>
					</td>
				</tr>
				<tr id="trMessageForm_{$review.id_agile_rating}" style="display:none;"><td colspan="7" align="center">
				<form method="post" action="{$link->getModuleLink('agilesellerratings', 'myreviews', [], true)}" id="messageForm_{$review.id_agile_rating}" onsubmit="return onSubmitMessageForm({$review.id_agile_rating})">
					<div>
						<input type="hidden" name="id_agile_rating" value="{$review.id_agile_rating}" />
						<textarea rows="7" style="width:100%" name="response" id="response_{$review.id_agile_rating}">{$review.response}</textarea>
						<br>

						<button type="submit" name="submitResponse" class="button agile-btn agile-btn-default">
							<span>{l s='Respond' mod='agilesellerratings'}&nbsp;<i class="icon-chevron-right right"></i></span>
						</button>
						<button type="button" name="cancelResponse"  class="agile-btn agile-btn-default" onclick="closeMessageForm({$review.id_agile_rating})">
							<i class="icon-remove"></i>&nbsp;{l s='Cancel' mod='agilesellerratings'}
						</button>
					</div>
				</form>
				</td>
				</tr>
			{/foreach}
			</tbody>
		</table>
		</div>
		{else}
			<p class="warning">{l s='You do not yet have a review.' mod='agilesellerratings'}</p>
		{/if}
	</div>
	{/if}
	{include file="module:agilemultipleseller/views/templates/front/seller_footer.tpl"}

{/block}

{block name='javascript_bottom'}
    {$smarty.block.parent}  

{/block}
