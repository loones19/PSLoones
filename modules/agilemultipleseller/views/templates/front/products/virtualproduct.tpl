{*
*}

<div id="agile">
	<div id="product-virtual" class="panel product-tab">
		<h3>{l s='Virtual Product (services, booking and downloadable products)' mod='agilemultipleseller'}</h3>
		{if empty($product->cache_default_attribute)}
		<div class="form-group ">
			<label class="control-label agile-col-md-3 agile-col-lg-3 agile-col-xl-3" for="is_virtual_good">
				<span>{l s='Product Type' mod='agilemultipleseller'}</span>
			</label>
			<div class="agile-col-md-9 agile-col-lg-9 agile-col-xl-9">
				<p class="checkbox">
					<input type="checkbox" id="is_virtual_good" onchange="is_virtual_goods_onchange()" name="is_virtual_good" value="true" {if $product->is_virtual && $product->productDownload->active}checked="checked"{/if} />&nbsp;&nbsp;
					<label for="is_virtual_good" class="t bold">{l s='This is a virtual product' mod='agilemultipleseller'}</label>
				</p>
			</div>
		</div>

		<div id="tr_downloadable" class="form-group "  style="display:{if $product->is_virtual && $product->productDownload->active}{else}none{/if};">
			<label for="active" class="control-label agile-col-md-3 agile-col-lg-3 agile-col-xl-3 ">
				{l s='Downloadable?' mod='agilemultipleseller'}
			</label>
			<div class="agile-col-md-9 agile-col-lg-9 agile-col-xl-9 ">
				<div class="row">
					<div class="input-group agile-col-md-6 agile-col-lg-6 agile-col-xl-6">
						<span>
							<input type="radio" value="1" onclick="is_virtual_file_onclick()" name="is_virtual_file" {if $product_downloaded}checked="checked"{/if} />
							<label>{l s='Yes' mod='agilemultipleseller'}</label>
							<input type="radio" value="0" onclick="is_virtual_file_onclick()" name="is_virtual_file" {if !$product_downloaded}checked="checked"{/if} />
							<label for="active_off">{l s='No' mod='agilemultipleseller'}</label>
						</span>
					</div>
				</div>
			</div>
		</div>

		{* [begin] virtual product *}
		<div id="virtual_good" {if !$product->productDownload->id OR !$product->is_virtual}style="display:none"{/if}> {*   {if !$product->productDownload->id || $product->productDownload->active}style="display:none"{/if} *}
			<div class="row">
				<p class="alert" id="file_missing" style="color:red;{if empty($download_product_file_missing)}none;{/if}">
					{$download_product_file_missing}
				</p>
			</div>

			<div id="is_virtual_file_product" > {*    style="display:none;" *}
				{if !$download_dir_writable}
					<p class="alert">
						{l s='Your download repository is not writable.' mod='agilemultipleseller'}<br/>
						{$smarty.const._PS_DOWNLOAD_DIR_}
					</p>
				{/if}
				{* Don't display file form if the product has combinations *}
				{if empty($product->cache_default_attribute)}
					{if $product->productDownload->id}
						<input type="hidden" id="virtual_product_id" name="virtual_product_id" value="{$product->productDownload->id}" />
					{/if}
					{* table *}
					<div class="form-group ">
						<label class="control-label agile-col-md-3 agile-col-lg-3 agile-col-xl-3" for="virtual_product_file">
							<span data-original-title="{l s='Your server\'s maximum upload file size is' mod='agilemultipleseller'}:&nbsp;{$upload_max_filesize} {l s='MB' mod='agilemultipleseller'}" 
								class="label-tooltip" data-toggle="tooltip" title="">
								 {l s='Upload a file' mod='agilemultipleseller'}
							</span>
						</label>
						<div class="agile-col-md-9 agile-col-lg-9 agile-col-xl-9">
							<input type="file" id="virtual_product_file" name="virtual_product_file" onchange="uploadFile();" maxlength="{$upload_max_filesize}" />
							<img id="imgProcessing" src="{$base_dir_ssl}/modules/agilekernel/img/processing.gif" style="display:none;">
						</div>
					</div>
					<div id="upload-error" class="form-group " style="display:none"></div>
					<div id="upload-confirmation" class="form-group " style="display:{if isset($is_agilevirtual_products_installed) && $is_agilevirtual_products_installed==1}none;{/if}">
						<label class="agile-col-md-3 agile-col-lg-3 agile-col-xl-3">
							{if strlen($up_filename)>0 && !$is_file}
								<input type="hidden" id="virtual_product_filename" name="virtual_product_filename" value="{$up_filename}" />
							{/if}
						</label>
						<div class="conf agile-col-md-9 agile-col-lg-9 agile-col-xl-9">
							{if strlen($up_filename)>0 && !$is_file}
							<a class="delete_virtual_product" id="delete_downloadable_product" onclick="return ajax_delete_virtual_downloadfile();" href="" class="red">
								<img src="{$base_dir_ssl}img/admin/delete.gif" alt="{l s='Delete this file' mod='agilemultipleseller'}"/>
							</a>
							{/if}
						</div>
					</div>
					{if $is_file}
						<div id="tr_link_to_file" class="form-group ">
							<label class="control-label agile-col-md-3 agile-col-lg-3 agile-col-xl-3" for="virtual_product_filename">
								<input type="hidden" id="virtual_product_filename" name="virtual_product_filename" value="{$product->productDownload->filename}" />
								{l s='Link to the file:' mod='agilemultipleseller'}
							</label>
							<div class="agile-col-md-9 agile-col-lg-9 agile-col-xl-9">
								{$product->productDownload->getHtmlLinkFrontSeller(false, false) nofilter}
								<a onclick="return ajax_delete_virtual_downloadfile()" href="" class="red delete_virtual_product">
									<img src="{$base_dir_ssl}img/admin/delete.gif" alt="{l s='Delete this file' mod='agilemultipleseller'}"/>
								</a>
							</div>
						</div>
					{/if}

					<div class="form-group ">
						<label class="control-label agile-col-md-3 agile-col-lg-3 agile-col-xl-3" for="virtual_product_name">
							<span data-original-title="{l s='The full filename with its extension (e.g. Book.pdf)' mod='agilemultipleseller'}" 
								class="label-tooltip" data-toggle="tooltip" title="">
								 {l s='Filename' mod='agilemultipleseller'}
							</span>
						</label>
						<div class="agile-col-md-9 agile-col-lg-9 agile-col-xl-9">
							<input type="text" id="virtual_product_name" name="virtual_product_name" value="{$product->productDownload->display_filename|escape:'htmlall':'UTF-8'}" />
						</div>
					</div>

					<div class="form-group ">
						<label class="control-label agile-col-md-3 agile-col-lg-3 agile-col-xl-3" for="virtual_product_nb_downloable">
							<span data-original-title="{l s='Number of allowed downloads per customer - (Set to 0 for unlimited downloads)' mod='agilemultipleseller'}" 
								class="label-tooltip" data-toggle="tooltip" title="">
								{l s='Allowed downloads' mod='agilemultipleseller'}
							</span>
						</label>
						<div class="agile-col-md-9 agile-col-lg-9 agile-col-xl-9">
							<input type="text" id="virtual_product_nb_downloable" name="virtual_product_nb_downloable" value="{$product->productDownload->nb_downloadable|htmlentities}" size="6" />
						</div>
					</div>

					<div class="form-group ">
						<label class="control-label agile-col-md-3 agile-col-lg-3 agile-col-xl-3" for="virtual_product_expiration_date">
							<span data-original-title="{l s='If set, the file will not be downloadable anymore after this date. Leave this blank for no expiration date' mod='agilemultipleseller'}" 
								class="label-tooltip" data-toggle="tooltip" title="">
								{l s='Expiration date' mod='agilemultipleseller'}
							</span>
						</label>
						<div class="agile-col-md-9 agile-col-lg-9 agile-col-xl-9">
							<input class="datepicker" type="text" id="virtual_product_expiration_date" name="virtual_product_expiration_date" value="{$product->productDownload->date_expiration}" size="11" maxlength="10" autocomplete="off" /> {l s='Format: YYYY-MM-DD' mod='agilemultipleseller'}
						</div>
					</div>

					<div class="form-group ">
						<label class="control-label agile-col-md-3 agile-col-lg-3 agile-col-xl-3" for="virtual_product_nb_days">
							<span data-original-title="{l s='How many days this file can be accessed by customers' mod='agilemultipleseller'} - <em>({l s='Set to zero for unlimited access' mod='agilemultipleseller'})</em>" 
								class="label-tooltip" data-toggle="tooltip" title="">
								{l s='Number of days' mod='agilemultipleseller'}
							</span>
						</label>
						<div class="agile-col-md-9 agile-col-lg-9 agile-col-xl-9">
							<input type="text" id="virtual_product_nb_days" name="virtual_product_nb_days" value="{$product->productDownload->nb_days_accessible|htmlentities}" class="" size="4" />
						</div>
					</div>
					{* Feature not implemented *}

				{/if}
			</div>
		</div>


	  <div class="form-group agile-align-center">
		<button type="submit" class="agile-btn agile-btn-default" name="submitVirtualProduct" value="{l s='Save' mod='agilemultipleseller'}">
		<i class="icon-save "></i>&nbsp;<span>{l s='Save' mod='agilemultipleseller'}</span></button >
	   </div>
	   {else}
		<div id="error_edit_file" class="alert alert-warning">
			{l s='You cannot edit your file here because you used combinations. Please edit it in the Combinations tab' mod='agilemultipleseller'}
		</div>
		{/if}
	</div> <!-- End of panel -->
</div> <!-- End of bootstrap -->


