<script type="text/javascript">
    var currentmenuid = {$product_menu};
</script>
<div class="productTabs agile-col-md-3 agile-col-lg-3 agile-col-xl-3">
	<div class="list-group">
		{foreach from=$product_menus item=menu}
			{if $id_product>0 || $menu.id == 1}
			<a class="list-group-item {if $product_menu==$menu.id}active{/if}" id="link-{$menu.name}" href="{$link->getModuleLink('agilemultipleseller', 'sellerproductdetail', ['id_product'=>$id_product,'product_menu'=>$menu.id,'token'=>$token], true)}">
				{$menu.name}
			 </a>
			 {else}
			<a class="list-group-item {if $product_menu==$menu.id}active{/if}" id="link-{$menu.name}">
				{$menu.name}
			 </a>
			{/if}
		{/foreach}
	</div>
</div>
