<table>
	<tr>
		<td class="col-left" style="width:140px;">
			<label>{l s='Item Shipping:' mod='agilemultipleseller'}</label>
		</td>
		<td>
	  	<table  style="width:100%;	border-style:solid;	border-width:1px;border-color:gray;">
	  	      <thead>
	            <tr>
		            <th style="padding: 3px;text-align:center;border-style:solid;border-width:1px;border-color:gray;" class="item" width="10%"></th>
		            <th style="padding: 3px;text-align:center;border-style:solid;border-width:1px;border-color:gray;" class="item" width="30%">{l s='Zone' mod='agilemultipleseller'}</th>
		            <th style="padding: 3px;text-align:center;border-style:solid;border-width:1px;border-color:gray;" class="item" width="30%">{l s='Single Item' mod='agilemultipleseller'}</th>
		            <th style="padding: 3px;text-align:center;border-style:solid;border-width:1px;border-color:gray;" class="item" width="30%">{l s='Per Additional Item' mod='agilemultipleseller'}</th>
	            </tr>
            </thead>
	  	  {foreach from=$itemShippingData key=k item=zone}
	        <tr>
	  	    	<td style="text-align:center;border-style:solid;border-width:1px;border-color:gray;">
    			<input type="checkbox" id="zone_{$zone.id_zone}"  name="zone_{$zone.id_zone}" value="1" {if $zone.single_item_fee or  $zone.additional_item_fee} checked="checked"{/if}  />
				</td>

                <td style="border-style:solid;	border-width:1px;border-color:gray;"> {$zone.name}</td>
                <td style="text-align:center;padding:5px 5px;border-style:solid;border-width:1px;border-color:gray;"> <input type="input" id="sitm_{$zone.id_zone}" name="sitm_{$zone.id_zone}" value="{$zone.single_item_fee}" /></td>
                <td style="text-align:center;padding:5px 5px;border-style:solid;border-width:1px;border-color:gray;"> <input type="input" id="aitm_{$zone.id_zone}" name="aitm_{$zone.id_zone}" value="{$zone.additional_item_fee}" /></td>
	            <script type="text/javascript">
				$(document).ready(function()
				{
						if ($('#zone_{$zone.id_zone}').is(':checked'))
						{
							$('#sitm_{$zone.id_zone}').removeAttr('disabled');
							$('#aitm_{$zone.id_zone}').removeAttr('disabled');
							$('#sitm_{$zone.id_zone}').attr('style', 'background-color:white');
							$('#aitm_{$zone.id_zone}').attr('style', 'background-color:white');
						}
						else
						{
							$('#sitm_{$zone.id_zone}').attr('disabled', 'disabled');
							$('#aitm_{$zone.id_zone}').attr('disabled', 'disabled');
							$('#sitm_{$zone.id_zone}').attr('style', 'background-color:#E6E6E6');
							$('#aitm_{$zone.id_zone}').attr('style', 'background-color:#E6E6E6');
						}
				
				
					$("#zone_{$zone.id_zone}").click(function(){
						if ($(this).is(':checked'))
						{
							$('#sitm_{$zone.id_zone}').removeAttr('disabled');
							$('#aitm_{$zone.id_zone}').removeAttr('disabled');
							$('#sitm_{$zone.id_zone}').attr('style', 'background-color:white');
							$('#aitm_{$zone.id_zone}').attr('style', 'background-color:white');
						}
						else
						{
							$('#sitm_{$zone.id_zone}').attr('disabled', 'disabled');
							$('#aitm_{$zone.id_zone}').attr('disabled', 'disabled');
							$('#sitm_{$zone.id_zone}').attr('style', 'background-color:#E6E6E6');
							$('#aitm_{$zone.id_zone}').attr('style', 'background-color:#E6E6E6');
						}
					});
				});
				</script>
	        </tr>
            {/foreach}	
	  	</table>
		</td>	
	</tr>
</table>
