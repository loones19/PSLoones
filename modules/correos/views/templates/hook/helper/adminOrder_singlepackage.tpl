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
<div id="form-singlepackage">
<div class="form-group">
 <label class="control-label col-lg-3 required"> {l s='Weight' mod='correos'}: </label>
   <div class="input-group col-lg-2">
     <input type="text" id="correos_package_weight" name="correos_package_weight" 
      value="{if isset($request_data->parcel_details->weight)}{$request_data->parcel_details->weight|escape:'htmlall':'UTF-8'}{else}{$weight|escape:'htmlall':'UTF-8'}{/if}" class="form-control required" title="{l s='Weight' mod='correos'} (Kg)"/>
     <span class="input-group-addon">Kg<span></span>
     </span>
   </div>
</div>
<div class="form-group">
   <label class="control-label col-lg-3 {if $is_correospaq}required{/if}"> {l s='Long' mod='correos'}: </label>
   <div class="input-group col-lg-2">
      <input type="text" id="correos_package_long" name="correos_package_long" 
       {if isset($request_data->parcel_details->long)}
           value="{$request_data->parcel_details->long|escape:'htmlall':'UTF-8'}"
           {elseif $order_prducts|@count eq 1}
           {assign var=first_prduct_key value = $order_prducts|@key}
								
          value="{$order_prducts.$first_prduct_key.depth|intval}"
       {else}
        value="0"
        {/if}
      class="form-control {if $is_correospaq}required{/if}" title="{l s='Long' mod='correos'} (cm.)" />
   <span class="input-group-addon">cm<span></span></span>
   </div>
</div>
<div class="form-group">
   <label class="control-label col-lg-3 {if $is_correospaq}required{/if}"> {l s='Width' mod='correos'}: </label>
   <div class="input-group col-lg-2">
      <input type="text" id="correos_package_width" name="correos_package_width" 
       {if isset($request_data->parcel_details->width)}
         value="{$request_data->parcel_details->width|escape:'htmlall':'UTF-8'}"
       {elseif  $order_prducts|@count eq 1}
         {assign var=first_prduct_key value = $order_prducts|@key}
								
          value="{$order_prducts.$first_prduct_key.width|intval}"
       {else}
        value="0"
       {/if}
       class="form-control {if $is_correospaq}required{/if}" title="{l s='Width' mod='correos'} (cm.)" />
 <span class="input-group-addon">cm<span></span></span>
 </div>
</div>
                    <div class="form-group">
                    <label class="control-label col-lg-3 {if $is_correospaq}required{/if}"> {l s='height' mod='correos'}: </label>
   <div class="input-group col-lg-2">
      <input type="text" id="correos_package_height"  name="correos_package_height" 
         {if isset($request_data->parcel_details->height)}
           value="{$request_data->parcel_details->height|escape:'htmlall':'UTF-8'}"
         {elseif $order_prducts|@count eq 1}
           {assign var=first_prduct_key value = $order_prducts|@key}
         value="{$order_prducts.$first_prduct_key.height|intval}"
         {else}
          value="0"
         {/if}
       title="{l s='High' mod='correos'}" class="form-control {if $is_correospaq}required{/if}" />
     <span class="input-group-addon">cm<span></span></span>
   </div>
</div>
<div class="form-group">
   <label class="control-label col-lg-3"> {l s='Calculated Bulk' mod='correos'}:</label>
   <div class="input-group col-lg-2">
      <input type="text" id="correos_package_bult"  name="correos_package_bult" value="10" title="{l s='Bult' mod='correos'}" class="form-control" disabled/>
     <span class="input-group-addon">Kg<span></span></span>
   </div>
</div>

                    <div class="form-group">
 <label class="control-label col-lg-3"> {l s='Observations' mod='correos'}: </label>
   <div class="col-lg-7">
      <input type="text" id="correos_package_observations"  name="correos_package_observations" value="{if isset($request_data->parcel_details->observations)}{$request_data->parcel_details->observations|escape:'htmlall':'UTF-8'}{/if}" maxlength="45" title="{l s='All-risks insurance' mod='correos'}" class="form-control" />
      <p class="help-block">
       {l s='For the shipping label' mod='correos'}. {l s='Max. 45 characters' mod='correos'}
     </p>
   </div>
</div>
</div>