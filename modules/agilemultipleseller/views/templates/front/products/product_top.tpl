<input type="hidden" name="id_product" value="{$product->id}" />
<div class="row">
  <h3>
    <span class="agile-pull-left">
      {l s='Product' mod='agilemultipleseller'} <span class="color-myaccount">{l s='#' mod='agilemultipleseller'}{$product->id|string_format:"%06d"}</span> - {$product->name[$id_language]}
    </span>
    <span class="agile-pull-right">
      <a class="agile-btn agile-btn-default" href="{$link->getModuleLink('agilemultipleseller', 'sellerproducts', [], true)}">
        <i class="icon-th-list"></i>{l s=' Back to product list' mod='agilemultipleseller'}
      </a>
    </span>
  </h3>


  <h3 class="row agile-align-center">
    {if $id_product>0}
    {else}
    <span>
      <h4>{l s='Adding a new product - other menus will be available once you save the basic information' mod='agilemultipleseller'}</h4>
    </span>
    {/if}
  </h3>
</div>