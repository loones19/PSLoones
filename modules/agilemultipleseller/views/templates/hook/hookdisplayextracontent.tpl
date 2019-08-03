
<div id="idTab19" class="rte">
  <div class="row">
		<div class="margin-form clearfix" style="float:left;margin-left:20px;">
			<h3>
				{if !empty($HOOK_SELLER_RATINGS)}
					{$HOOK_SELLER_RATINGS nofilter}
				{else}
			         {$sellerInfo->company}
				{/if}
			</h3>

   	    <table cellpadding="2" cellspacing="5">
		    <tr>
		    <td valign="top" align="middle" style="padding:10px;">				
				{if $show_seller_store_link==1}
					<a href="{$link->getAgileSellerLink($sellerInfo->id_seller,$sellerInfo->company)}" class="btn btn-default button button-small">
				{/if}
			    <img src="{$sellerInfo->get_seller_logo_url()}" width="120" />
				{if $show_seller_store_link==1}
					</a>
					<br>
				{/if}
				
				{if $show_seller_store_link==1}
					<br>
					<p><a href="{$link->getAgileSellerLink($sellerInfo->id_seller,$sellerInfo->company)}" class="btn btn-primary">
						<span>{l s='Visit Seller Store' mod='agilemultipleseller'}</span>
						</a>
					</p>
				{/if}
		    </td>
	    
		    <td valign="top" style="padding:10px;">
		        <b>{l s='Address:' mod='agilemultipleseller'}</b><br />
		        	{$sellerInfo->address1}<br />
		        	{if $sellerInfo->address2}{$sellerInfo->address2}<br />{/if}
		        	{$sellerInfo->city}, {$sellerInfo->state} {$sellerInfo->postcode}<br />
		        	{$sellerInfo->country} <br /><br />
		        
				{* LOONES 26/07/2019 - Remove phone from vendor
				{if $sellerInfo->phone}

    		    <b>{l s='Phone:' mod='agilemultipleseller'}</b><br />{$sellerInfo->phone}<br />
		        {/if}
                <br />
				*}
		        {$sellerInfo->description nofilter}
		    </td>
		    </tr>
		    </table>
		</div>


		{if isset($google_map_show) && $google_map_show == 1}
		<div class="margin-form" style="float:right;">
    	    <div id="map_canvas" style="width:450px;height:250px;padding:0px;margin:0px;"></div>
		</div>
		{/if}
 </div>       
</div>

{if isset($google_map_show) && $google_map_show == 1}

<script type="text/javascript">
	var sellerloclat = {$sellerInfo->latitude};
	var sellerloclng = {$sellerInfo->longitude};
	var is_multilang_address = true;
	var has_address = false;
</script>


{/if}
