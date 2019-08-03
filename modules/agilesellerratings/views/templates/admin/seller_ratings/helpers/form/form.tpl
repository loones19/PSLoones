{*
*}
{extends file="helpers/form/form.tpl"}

{block name="label"}
	{if $input.type == 'customer'}
		<label>{l s='Customer' mod='agilesellerratings'}</label>
	{else}
		{$smarty.block.parent}
	{/if}
{/block}

{block name="field"}
	{if $input.type == 'grades'}
		<div class="col-lg-9">
			<table>
			{foreach from=$the_grades item=grade}
				<tr>
					<td>{$grade.name} :</td>
					<td>&nbsp;</td>
					<td><input type="text" name="criterion_{$grade.id_agile_rating_criterion}" value="{$grade.grade}" ></td>
				</tr>
			{/foreach}
			</table>
		</div>
	{else if $input.name == 'content'}
		<div class="col-lg-9">
			<textarea name="message" id="message" class="textarea-autosize">{$the_rating->content}</textarea>
		</div>
	{else}
		{$smarty.block.parent}
	{/if}
{/block}
