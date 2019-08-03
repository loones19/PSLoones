<script type="text/javascript">
/** _agile_ <![CDATA[ _agile_ **/
idSelectedCountry = {if isset($smarty.post.id_state)}{$smarty.post.id_state|intval}{else}false{/if};
countries = new Array();
countriesNeedIDNumber = new Array();
countriesNeedZipCode = new Array();
{if isset($countries)}
	{foreach from=$countries item='country'}
		{if isset($country.states) && $country.contains_states}
			countries[{$country.id_country|intval}] = new Array();
			{foreach from=$country.states item='state' name='states'}
				countries[{$country.id_country|intval}].push({ldelim}'id' : '{$state.id_state}', 'name' : '{$state.name|escape:'htmlall':'UTF-8'}'{rdelim});
			{/foreach}
		{/if}
		{if $country.need_identification_number}
			countriesNeedIDNumber.push({$country.id_country|intval});
		{/if}
		{if isset($country.need_zip_code)}
			countriesNeedZipCode[{$country.id_country|intval}] = {$country.need_zip_code};
		{/if}
	{/foreach}
{/if}
$(function(){ldelim}
	$('.id_state option[value={if isset($smarty.post.id_state)}{$smarty.post.id_state|intval}{else}{if isset($address)}{$address->id_state|escape:'htmlall':'UTF-8'}{/if}{/if}]').attr('selected', 'selected');
{rdelim});
/** _agile_]]> _agile_ **/
{if $vat_management}
	{literal}
	$(document).ready(function() {
		$('#company').blur(function(){
			vat_number();
		});
		vat_number();
		function vat_number()
		{
			if ($('#company').val() != '')
				$('#vat_number').show();
			else
				$('#vat_number').hide();
		}
	});
	{/literal}
{/if}
</script>
<table id="sellerinformation" name="sellerinformation" cellpadding="15" style="width: 100%;border:dotted 0px gray;"align="center">
{if in_array('company', $display_fields)}
<tr>
	<td style="width:150px"><p><label>{l s='Company:' mod='agilemultipleseller'}</p></label></td>
	<td> 
		<input type="text" style="width:300px;" id="company" name="company" value="{if isset($smarty.post.company)}{$smarty.post.company}{else}{$sellerinfo->company|escape:'htmlall':'UTF-8'}{/if}"/>
	</td>
</tr>
{/if}
{if in_array('address1', $display_fields)}
<tr>
	<td style="width:150px;"><p><label>{l s='Address1:' mod='agilemultipleseller'}</p></label></td>
	<td> 
		<input type="text" style="width:300px;" name="address1" id="address1" value="{if isset($smarty.post.address1)}{$smarty.post.address1}{else}{$sellerinfo->address1|escape:'htmlall':'UTF-8'}{/if}"/>
	</td>
</tr>
{/if}
{if in_array('address2', $display_fields)}
<tr>
	<td style="width:150px;"><p><label>{l s='Address2:' mod='agilemultipleseller'}</p></label></td>
	<td nowrap> 
		<input type="text" style="width:300px;" name="address2" id="address2" value="{if isset($smarty.post.address2)}{$smarty.post.address2}{else}{$sellerinfo->address2|escape:'htmlall':'UTF-8'}{/if}"/>
	</td>
</tr>
{/if}
{if in_array('city', $display_fields)}
<tr>
	<td style="width:150px;"><p><label>{l s='city:' mod='agilemultipleseller'}</p></label></td>
	<td nowrap> 
		<input type="text" style="width:300px;" name="city" id="city" value="{if isset($smarty.post.city)}{$smarty.post.city}{else}{$sellerinfo->city|escape:'htmlall':'UTF-8'}{/if}"/>
	</td>
</tr>
{/if}
{if in_array('postcode', $display_fields)}
<tr>
	<td style="width:150px;"><p><label>{l s='Zip/Postal Code:' mod='agilemultipleseller'}</p></label></td>
	<td>  
		<input type="text" style="width:200px;" id="postcode" name="postcode" value="{if isset($smarty.post.postcode)}{$smarty.post.postcode}{else}{$sellerinfo->postcode|escape:'htmlall':'UTF-8'}{/if}" />
	</td>
</tr>
{/if}
{if in_array("id_state", $display_fields)}
<tr>
	<td style="width:150px;"><p class="id_state"><label for="id_state">{l s='State:' mod='agilemultipleseller'}</label></p></td>
	<td nowrap>
		<p class="id_state">
			<select name="id_state" id="id_state">
			</select>
		</p>
	</td>
</tr>
{/if}
{if in_array('id_country', $display_fields)}
<tr>
	<td style="width:150px;"><p><label for="id_country">{l s='Country:' mod='agilemultipleseller'}</p></label></td>
	<td>&nbsp; 
		<select name="id_country" id="id_country">
			{foreach from=$countries item=country}
				<option value="{$country.id_country}" {if isset($smarty.post.id_country)}{if $smarty.post.id_country == $country.id_country}selected="selected"{/if}{else}{if $sellerinfo->id_country == $country.id_country}selected="selected"{/if}{/if}>{$country.name|escape:'htmlall':'UTF-8'}</option>
			{/foreach}
		</select>
	</td>
</tr>
{/if}
{if in_array('phone', $display_fields)}
<tr>
	<td style="width:150px;"><p><label for="phone">{l s='Phone:' mod='agilemultipleseller'}</p></label></td>
	<td>&nbsp;<input type="text" name="phone" id="phone" value="{if isset($smarty.post.phone)}{$smarty.post.phone}{else}{$sellerinfo->phone|escape:'htmlall':'UTF-8'}{/if}"/>
	</td>
</tr>
{/if}
{include file="module:agilemultipleseller/custom/selllersummarybag/numberofbags.tpl"}
<input type="hidden" name="signin" id="signin" value="1"/>
</table>