<div id="product-images" class="panel product-tab">
  <input type="hidden" name="submitted_tabs[]" value="Images" />

  <h3 class="tab" >
    {l s='Images' mod='agilemultipleseller'}
    <span class="badge" id="countImage">{$countImages}</span>
  </h3>

  <div class="row" id="divUploadControl" style="display:{if $image_number_limit > 0 && count($images) >= $image_number_limit}none{/if};">
    <div class="form-group">
      <label class="control-label agile-col-md-3 agile-col-lg-3 agile-col-xl-3 file_upload_label">
        <span class="label-tooltip" data-toggle="tooltip"
          title="{l s='Format:' mod='agilemultipleseller'} JPG, GIF, PNG. {l s='Filesize:' mod='agilemultipleseller'} {$max_image_size|string_format:"%.2f"} {l s='kB max.' mod='agilemultipleseller'}">
          {if isset($id_image)}{l s='Edit this product image' mod='agilemultipleseller'}{else}{l s='Add a new image to this product'  mod='agilemultipleseller'}{/if}
        </span>
      </label>
      <div class="agile-col-md-9 agile-col-lg-9 agile-col-xl-9">
			<table><tr><td>
				<input type="file" name="qqfile" size="55" />
			</td>
			<td>
				<button type="submit" class="agile-btn agile-btn-default" name="submitAddImage" value="{l s='   Upload   ' mod='agilemultipleseller'}">
				  <i class="icon-upload "></i>&nbsp;<span>{l s='Upload' mod='agilemultipleseller'}</span>
				</button >
			</td></tr>
			</table>
       </div>
    </div>

    <div class="form-group">
      <input type="hidden" name="resizer" value="auto" />
      {if Tools::getValue('id_image')}<input type="hidden" name="id_image" value="{Tools::getValue('id_image')}" />{/if}
    </div>

		<div class="form-group">
			<label class="control-label col-lg-3">
				<span class="label-tooltip" data-toggle="tooltip"
					title="{l s='Update all captions at once, or select the position of the image whose caption you wish to edit. Invalid characters: %s' mod='agilemultipleseller' sprintf=['<>;=#{}']}">
					{l s='Caption' mod='agilemultipleseller'}
				</span>
			</label>
			<div class="col-lg-4">
			{foreach from=$languages item=language}
				{if $languages|count > 1}
				<div class="translatable-field row lang-{$language.id_lang}">
					<div class="col-lg-8">
				{/if}
						<input type="text" id="legend_{$language.id_lang}"{if isset($input_class)} class="{$input_class}"{/if} name="legend_{$language.id_lang}" value="{if $images|count}{$images[0]->legend[$language.id_lang]|escape:'html':'UTF-8'}{else}{$product->name[$language.id_lang]|escape:'html':'UTF-8'}{/if}"{if !$product->id} disabled="disabled"{/if}/>
				{if $languages|count > 1}
					</div>
					<div class="col-lg-2">
						<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" tabindex="-1">
							{$language.iso_code}
							<span class="caret"></span>
						</button>
						<ul class="dropdown-menu">
							{foreach from=$languages item=language}
							<li>
								<a href="javascript:hideOtherLanguage({$language.id_lang});">{$language.name}</a>
							</li>
							{/foreach}
						</ul>
					</div>
				</div>
				{/if}
			{/foreach}
			</div>
			<div class="col-lg-2{if $images|count <= 1} hidden{/if}" id="caption_selection">
				<select name="id_caption">
					<option value="0">{l s='All captions' mod='agilemultipleseller'}</option>
					{foreach from=$images item=image}
					<option value="{$image->id_image|intval}">
						{l s='Position %d' mod='agilemultipleseller' sprintf=[$image->position|intval]}
					</option>
					{/foreach}
				</select>
			</div>
			<div class="col-lg-2">
				<button type="submit" class="agile-btn agile-btn-default" name="submitUpdateLegends" value="update_legends"><i class="icon-random"></i> {l s='Update' mod='agilemultipleseller'}</button>
			</div>
		</div>

  </div>

  <div class="table-responsive">
    <table cellspacing="0" cellpadding="0" class="table tableDnD" id="imageTable">
      <thead>
        <tr class="nodrag nodrop">
          <th>{l s='Image' mod='agilemultipleseller'}</th>
		  <th class="fixed-width-lg"><span class="title_box">{l s='Caption' mod='agilemultipleseller'}</span></th>
          <th>{l s='Position' mod='agilemultipleseller'}</th>
          {*
          {if $shops}
          {foreach from=$shops item=shop}
          <th>{$shop.name}</th>
          {/foreach}
          {/if}
          *}
          <th>{l s='Cover' mod='agilemultipleseller'}</th>
          <th>{l s='Action' mod='agilemultipleseller'}</th>
        </tr>
      </thead>
      <tbody id="imageList">
      </tbody>
    </table>
  </div>
  <div class="table-responsive"  style="display:none;">
    <table id="lineType">
      <tr id="image_id">
        <td style="padding: 4px;">
          <a href="{$smarty.const._THEME_PROD_DIR_}image_path.jpg" target="_blank">
              <img src="{$smarty.const._THEME_PROD_DIR_}en-default-small_default.jpg" alt="image_id" title="image_id" />
          </a>
        </td>
		<td>legend</td>
        <td id="td_image_id" class="pointer dragHandle center positionImage">
          image_position
        </td>
        {*
        {if $shops}
        {foreach from=$shops item=shop}
        <td class="center">
          <input type="checkbox" class="image_shop" name="id_image" id="{$shop.id_shop}image_id" value="{$shop.id_shop}" />
        </td>
        {/foreach}
        {/if}
        *}
        <td class="center cover">
          <a href="#">
            <img class="covered" src="{$base_dir_ssl}img/admin/blank.gif" alt="e" />
          </a>
        </td>
        <td class="center">
          <a href="#" class="delete_product_image" >
            <img src="{$base_dir_ssl}img/admin/delete.gif" alt="{l s='Delete this image' mod='agilemultipleseller'}" title="{l s='Delete this image' mod='agilemultipleseller'}" />
          </a>
        </td>
      </tr>
    </table>
  </div>
</div>
