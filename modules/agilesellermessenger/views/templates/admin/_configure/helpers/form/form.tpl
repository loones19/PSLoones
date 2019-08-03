{extends file="helpers/form/form.tpl"}
{block name="input"}
   {if $input.type == 'agile_condition_radio'}
		{foreach $input.values as $value}
			<div class="radio {if isset($input.class)}{$input.class}{/if}">
				{strip}
				<label>
				<input type="radio"	name="{$input.name}" id="{$value.id}" value="{$value.value|escape:'html':'UTF-8'}" onchange="{$input.onchange}" {if $fields_value[$input.name] == $value.value} checked="checked"{/if}{if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}/>
					{$value.label}
				</label>
				{/strip}
			</div>
			{if isset($value.p) && $value.p}<p class="help-block">{$value.p}</p>{/if}
		{/foreach}
    {else}
		{$smarty.block.parent}
    {/if}
{/block}
