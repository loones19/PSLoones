<span style="margin-left:5px;">
    <input type="checkbox" name="create_seller_account" id="create_seller_account" value="1" {if isset($id_sellerinfo) && $id_sellerinfo>0}checked disabled="disabled"{/if}>&nbsp;&nbsp;{l s='Create Seller Account' mod='agilemultipleseller'}
	{if isset($id_sellerinfo) && $id_sellerinfo>0}<a href="?tab=AdminSellerinfos&id_sellerinfo={$id_sellerinfo}&updatesellerinfo&&token={$tokenSellerinfo}">Seller Info</a>{/if}	
</span>

