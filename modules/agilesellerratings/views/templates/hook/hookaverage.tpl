<div id="tabRatingSummary_{$id_rating_target}">
	<span>
    <a href="{if !empty($link2sellerpage)}{$link2sellerpage}{else}{$link_target}{/if}">{$name_rating_target}</a>
	</span>
	<br>
{if $bn_rating_target>0}
	<span>
    <a href="{$link_target}">
    {l s='Postive' mod='agilesellerratings'}&nbsp;{$average_percentage|round:2}%&nbsp;({$bn_rating_target}) 
    </a> 
	</span>
	<span>
    <a href="{$link_target}">
    {$stars_target nofilter}
    </a> 
	</span>
{else}
	<span>
    <a href="{$link_target}">
    {l s='No feedback yet' mod='agilesellerratings'}
    </a> 
	</span>
	<br><br>
{/if}    
</div>
