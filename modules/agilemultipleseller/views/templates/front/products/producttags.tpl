{if isset($product->id)}
  <div id="product_tags" class="panel product-tab">
    <h3>{l s='Product Tags' mod='agilemultipleseller'}</h3>
    {if $configuration}
    <div class="form-wrapper">
        <input type='hidden' id='id_product' name='id_product' value='{$id_product}' />
        <input type='hidden' id='site_url' name='site_url' value='{$site_url}' />
        {foreach from=$tags item=v}
            <div class="form-group">
                <div class='col-lg-3'></div>
                <div class="checkbox col-lg-9">
                    <label><input type="checkbox" name='id_tag_{$v['id_agile_tag']}' class='agile_tag' {if $v['id_product'] != 0}checked{/if}>{$v['name']}</label>
                </div>
            </div>
        {/foreach}
        <div class="form-group">
            <div class='col-lg-3'></div>
            <div class="succes_save_tag alert alert-success hidden col-lg-9">
                {l s='You successfully save product tags' mod='agilemultipleseller'}
            </div>
        </div>
    </div>
    
    <div class="form-group  agile-align-center agile-padding">
      <span type="submit" class="agile-btn agile-btn-default" name="submitProductTags" id="product_tag_save" value="{l s='Save' mod='agilemultipleseller'}">
        <i class="icon-save "></i>&nbsp;<span>{l s='Save' mod='agilemultipleseller'}</span>
      </span >
    </div>
    {else}
        <div class="succes_save_tag alert alert-success">
                {l s='You don\'t have permission to change product tags' mod='agilemultipleseller'}
            </div>
    {/if}

  </div>
{/if}

