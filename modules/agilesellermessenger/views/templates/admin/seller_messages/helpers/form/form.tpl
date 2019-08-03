{*
*}
{extends file="helpers/form/form.tpl"}

{block name="label"}
	{if $input.type == 'customer'}
		<label>{l s='Customer' mod='agilesellermessenger'}</label>
	{else}
		{$smarty.block.parent}
	{/if}
{/block}

{block name="field"}
	{if $input.type == 'attachment'}
		<div class="margin-form">
			<input type="hidden"  name="{$input.name}" value="{$fields_value[$input.name]}">
			{if !empty({$fields_value[$input.name]})}
				<a href="index.php?tab=AdminSellerMessages&id_agile_sellermessage={$the_message->id}&viewagile_sellermessage&token={$token}&filename={$fields_value[$input.name]}&id_seller={$the_message->id_seller}" title="{l s='View file' mod='agilesellermessenger'}">{$fields_value[$input.name]}</a><br>
			{else}
				{l s='N/A' mod='agilesellermessenger'}
			{/if}
		</div>
	{elseif $input.type == 'product'}
		<div class="margin-form">
			<input type="hidden"  name="id_product" value="{$fields_value[$input.name]}">{$fields_value[$input.name]}
			<a href="?controller=AdminProducts&id_product={$product->id}&updateproduct&token={$tokenProduct}">{$product->name}</a>
		</div>
	{else}
		{$smarty.block.parent}
	{/if}
{/block}
