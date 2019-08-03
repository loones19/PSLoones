{*
* 2015-2016 YDRAL.COM
*
* NOTICE OF LICENSE
*
*  @author YDRAL.COM <info@ydral.com>
*  @copyright 2015-2016 YDRAL.COM
*  @license GNU General Public License version 2
*
* You can not resell or redistribute this software.
*}
<div class="correos-parcels">
<fieldset  class="form-group">
    <legend>
    {if $is_multipackage}
    {l s='Parcel' mod='correos'}: <span class="correos-parcel-number">1</span>
    {else}
    {l s='Parcel description' mod='correos'}
    {/if}
    </legend>

    <div class="form-group">
      <label class="control-label col-md-2">{l s='Package reference' mod='correos'}: </label>
      <div class="col-md-2">
        <input type="text" name="correos_package_reference[]" class="form-control" value="{$order->reference|escape:'htmlall':'UTF-8'}" readonly>
      </div>
      <label class="control-label col-md-2">{l s='Observations' mod='correos'}: </label>
      <div class="col-md-5">
        <input type="text" name="correos_package_observations[]" class="form-control">
      </div>
    </div>

  <div class="form-group">
      <label class="control-label col-md-2 required">{l s='Weight' mod='correos'}: </label>
      <div class="col-md-1" style="width:90px">
        <div class="input-group">
           <input type="text" name="correos_package_weight[]" class="form-control parcel-weight correos_package_weight required" value="{$weight|escape:'htmlall':'UTF-8'}">
           <span class="input-group-addon">Kg</span>
        </div>
      </div>

      <label class="control-label col-md-1 {if $is_correospaq}required{/if}" style="width:75px">{l s='Long' mod='correos'}: </label>
      <div class="col-md-1" style="width:90px">
        <div class="input-group">
           <input type="text" name="correos_package_long[]" class="form-control correos_package_long {if $is_correospaq}required{/if}" value="0">
           <span class="input-group-addon">cm</span>
        </div>
      </div>

      <label class="control-label col-md-1 {if $is_correospaq}required{/if}" style="width:75px">{l s='Width' mod='correos'}: </label>
      <div class="col-md-1" style="width:90px">
        <div class="input-group">
           <input type="text" name="correos_package_width[]" class="form-control correos_package_width {if $is_correospaq}required{/if}" value="0">
           <span class="input-group-addon">cm</span>
        </div>
      </div>

      <label class="control-label col-md-1 {if $is_correospaq}required{/if}" style="width:75px">{l s='height' mod='correos'}: </label>
      <div class="col-md-1" style="width:90px">
        <div class="input-group">
           <input type="text" name="correos_package_height[]" class="form-control correos_package_height {if $is_correospaq}required{/if}" value="0">
           <span class="input-group-addon">cm</span>
        </div>
      </div>
    

      <label class="control-label col-md-1" style="width:130px">{l s='Calculated Bulk' mod='correos'}: </label>
      <div class="col-md-1" style="width:90px">
        <div class="input-group">
          <input type="text" name="correos_package_bult" value="10" title="{l s='Bult' mod='correos'}" class="form-control correos_package_bult" disabled/>
         <span class="input-group-addon">Kg</span>
       </div>
     </div>
     
    </div>
    
    {if $require_customs}
    <div class="form-group">
      <label class="control-label col-md-2">{l s='Customs package description' mod='correos'}: </label>
      <div class="col-md-2">
        <select  name="goods_type[]">
          {foreach from=$customs_categories key='id' item='name'}
            <option value="{$id|escape:'htmlall':'UTF-8'}"{if $correos_config.customs_default_category eq $id} selected{/if}>{$name|escape:'htmlall':'UTF-8'}</option>
          {/foreach}
        </select>
      </div>
      <label class="control-label col-md-2 required"> {l s='First product value' mod='correos'}: </label>
      <div class="input-group col-md-2">
        <input type="text" class="form-control required" name="customs_firstproductvalue[]" value="{$first_prduct.unit_price_tax_excl|number_format:2|replace:".":","|escape:'htmlall':'UTF-8'}">
        <span class="input-group-addon">€<span></span></span>
       </div>
    </div>
    {/if}
    </fieldset>
</div>

<div id="correos-parcels-cloned"></div>