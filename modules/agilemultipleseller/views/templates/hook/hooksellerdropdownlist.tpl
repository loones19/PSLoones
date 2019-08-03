<select name="id_seller" class="col-lg-6">
	{foreach from=$sellers item=seller}
		<option value="{$seller.id_seller}">{$seller.id_seller}-{$seller.name}</option> 
	{/foreach}
</select>
