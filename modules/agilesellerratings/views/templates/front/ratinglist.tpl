{extends file='page.tpl'}

{block name='page_content'}

	<div id="agilesellerratings_ratinglist">
	<h2>{l s='Seller feedback history' mod='agilesellerratings'}</h2>
	<h3>{l s='Feedback for ' mod='agilesellerratings'}{$agile_target_name}</h3>
	{if $agile_ratings}
		<div class="box">
			<h2 class="agile-top-padding-1em">{l s='Average' mod='agilesellerratings'}&nbsp;{$averageTotal}</h2>
			{foreach from=$criterions item=c}
			<div class="agile-row">
				<div class="agile-col-xs-4 agile-col-sm-4 agile-col-md-3 agile-col-lg-2 agile-col-xl-2">
					{$c.name|escape:'html':'UTF-8'}<br />
				</div>
				<div class="agile-col-xs-6 agile-col-sm-6 agile-col-md-3 agile-col-lg-2 agile-col-xl-2">
					{$averagestars[$c.id_agile_rating_criterion] nofilter}
				</div>
				<div class="agile-col-xs-1 agile-col-sm-1 agile-col-md-3 agile-col-lg-2 agile-col-xl-2">
					{$averages[$c.id_agile_rating_criterion]|round:1}
				</div>
			</div>
			{/foreach}
		</div>
		<h2  class="agile-top-padding-1em">{l s='Feedback List' mod='agilesellerratings'}</h2>
		<br>
		{foreach from=$agile_ratings item=rating name=ratingloop}
			{if $smarty.foreach.ratingloop.first}
				<div class="box">
			{/if}
			{if $rating.content}
				<div class="agile-row">
					<div class="agile-col-xs-12 agile-col-sm-3">
						<p>
						  {$rating.stars nofilter}
						  {$rating.grade|round:1}
						</p>
						<p>
						  {dateFormat date=$rating.date_add|escape:'html':'UTF-8' full=1}
						</p>
						<p>
						  {$rating.customer|truncate:20:'...':true|escape:'html':'UTF-8' nofilter}
						</p>
					</div>
					<div class="agile-col-xs-12 agile-col-sm-9">
						<p>
							{$rating.content|nl2br nofilter}
						</p>
					</div>
				</div>
				{if !empty($rating.response)}
					<div class="agile-row" style="background-color:lightblue;">
						<div class="agile-col-xs-12 agile-col-sm-3">
							<p>
								{$agile_target_name}<br>{dateFormat date=$rating.date_upd|escape:'html':'UTF-8' full=1}
							</p>
						</div>
						<div class="agile-col-xs-12 agile-col-sm-9">
							<p>
								{$rating.response|escape:'html':'UTF-8'|nl2br nofilter}
							</p>
						</div>
					</div>
				{/if}
			{/if}
			{if $smarty.foreach.ratingloop.last}
				</div>
			{else}
				<hr></hr>
			{/if}
		{/foreach}
		<div class="agile-row">
			{* include file="$agilesellerrating_tpl./pagination.tpl" *}
		</div>
	{else}
		<div class="agile-row">
			<p class="align_center">{l s='There is currently no feedback for this seller/vendor.' mod='agilesellerratings'}</p>
		</div>
	{/if}
	</div>

{/block}

{block name='javascript_bottom'}
    {$smarty.block.parent}  

{/block}
