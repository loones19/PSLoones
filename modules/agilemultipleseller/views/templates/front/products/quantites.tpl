{*
*}

{if isset($product->id)}
	<div id="product-quantities" class="panel product-tab">
		<input type="hidden" name="submitted_tabs[]" value="Quantities" />
		<h3>{l s='Available quantities for sale' mod='agilemultipleseller'}</h3>
		{if !$ps_stock_management}
			<div class="alert alert-info">{l s='The stock management is disabled' mod='agilemultipleseller'}</div>
		{else}
			{include file="module:agilemultipleseller/views/templates/front/multishop/check_fields.tpl" product_tab="Quantities"}
			<div class="alert alert-info" style="display:none">
				{l s='This interface allows you to manage available quantities for sale for products. It also allows you to manage product combinations in the current shop.' mod='agilemultipleseller'}<br/>
				{l s='You can choose whether or not to use the advanced stock management system for this product.' mod='agilemultipleseller'}<br/>
				{l s='You can manually specify the quantities for the product/each product combination, or you can choose to automatically determine these quantities based on your stock (if advanced stock management is activated).' mod='agilemultipleseller'}<br/>
				{l s='In this case, quantities correspond to the real-stock quantities in the warehouses connected with the current shop, or current group of shops.' mod='agilemultipleseller'}<br/>
				{l s='For packs: If it has products that use advanced stock management, you have to specify a common warehouse for these products in the pack.' mod='agilemultipleseller'}<br/>
				{l s='Also, please note that when a product has combinations, its default combination will be used in stock movements.' mod='agilemultipleseller'}
			</div>

			{if $show_quantities == true}

				<div style="background:beige;color:yellow;width:100%;padding:5px;display: none;" id="available_quantity_ajax_msg"></div>
				<div style="background:beige;color:red;width:100%;padding:5px;display: none;" id="available_quantity_ajax_error_msg" ></div>
				<div style="background:beige;color:blue;width:100%;padding:5px;display: none;" id="available_quantity_ajax_success_msg"></div>

				<div class="form-group " {if $product->is_virtual || $product->cache_is_pack}style="display:none;" {else}style="display:none;"{/if} class="row stockForVirtualProduct">
					<label class="agile-col-md-3 agile-col-lg-3 agile-col-xl-3"></label>
					<div class="agile-col-md-9 agile-col-lg-9 agile-col-xl-9 ">
						<p class="checkbox">
							<label for="advanced_stock_management">
								<input type="checkbox" name="advanced_stock_management" class="advanced_stock_management" id="advanced_stock_management" 
									{if $product->advanced_stock_management == 1 && $stock_management_active == 1}
										value="1" checked="checked"
									{else}
										value="0"
									{/if} 
									{if $stock_management_active == 0 || $product->cache_is_pack}
										disabled="disabled" 
									{/if} 
								/>
								{l s='I want to use the advanced stock management system for this product.' mod='agilemultipleseller'}
							</label>
						</p>
							{if $stock_management_active == 0 && !$product->cache_is_pack}
								<p class="text-info"><i class="icon-info-sign"></i>&nbsp;{l s='This requires you to enable advanced stock management.' mod='agilemultipleseller'}</p>
							{else if $product->cache_is_pack}
								<p class="text-info">{l s='This parameter depends on the product(s) in the pack.' mod='agilemultipleseller'}</p>
							{/if}
					</div>
				</div>

				<div {if $product->is_virtual || $product->cache_is_pack}style="display:none;" {else}style="display:none;"{/if} class="row stockForVirtualProduct">
					<label class="control-label agile-col-md-3 agile-col-lg-3 agile-col-xl-3" for="depends_on_stock_1">{l s='Available quantities:' mod='agilemultipleseller'}</label>
					<div class="agile-col-md-9 agile-col-lg-9 agile-col-xl-9">
						<p class="radio">
							<label for="depends_on_stock_1">
								<input type="radio" name="depends_on_stock" class="depends_on_stock" id="depends_on_stock_1" value="1"
									{if $product->depends_on_stock == 1 && $stock_management_active == 1}
										checked="checked" 
									{/if} 
									{if $stock_management_active == 0 || $product->advanced_stock_management == 0 || $product->cache_is_pack}
										disabled="disabled" 
									{/if} 
								/>
								{l s='Available quantities for current product and its combinations are based on warehouse stock. ' mod='agilemultipleseller'} 
								{if ($stock_management_active == 0 || $product->advanced_stock_management == 0) && !$product->cache_is_pack} &nbsp;-&nbsp;{l s='This requires you to enable advanced stock management globally or for this product.' mod='agilemultipleseller'}
								{else if $product->cache_is_pack} &nbsp;-&nbsp;{l s='This parameter depends on the product(s) in the pack.' mod='agilemultipleseller'}
								{/if}
							</label>
						</p>
						<p class="radio">
							<label for="depends_on_stock_0" for="depends_on_stock_0">
								<input type="radio" name="depends_on_stock" class="depends_on_stock" id="depends_on_stock_0" value="0"
									{if $product->depends_on_stock == 0 || $stock_management_active == 0}
										checked="checked" 
									{/if} 
								/>
								{l s='I want to specify available quantities manually.' mod='agilemultipleseller'}
							</label>
						</p>
					</div>
				</div>

				{if isset($pack_quantity)}
					<div class="alert alert-info">
						<p>{l s='When a product has combinations, quantities will be based on the default combination.' mod='agilemultipleseller'}</p>
						<p>{l s='Given the quantities of the products in this pack, the maximum quantity should be:' mod='agilemultipleseller'} {$pack_quantity}</p>
					</div>	
				{/if}
				<div class="form-group ">
					<label class="agile-col-md-3 agile-col-lg-3 agile-col-xl-3"></label>
					<div class="agile-col-md-9 agile-col-lg-9 agile-col-xl-9">
						<table class="table">
							<thead>
								<tr>
									<th><span class="title_box">{l s='Quantity' mod='agilemultipleseller'}</span></th>
									<th><span class="title_box">{l s='Designation' mod='agilemultipleseller'}</span></th>
								</tr>
							</thead>
							{foreach from=$attributes item=attribute}
								<tr>
									<td class="available_quantity" id="qty_{$attribute['id_product_attribute']}">
										<!-- span>{$available_quantity[$attribute['id_product_attribute']]}</span> -->
										<input type="text" class="fixed-width-sm" value="{$available_quantity[$attribute['id_product_attribute']]|htmlentities}"/>
									</td>
									<td>{$product_designation[$attribute['id_product_attribute']]}</td>
								</tr>
							{/foreach}
						</table>
					</div>
				</div>
				<div id="when_out_of_stock" class="form-group ">
					<label class="control-label agile-col-md-3 agile-col-lg-3 agile-col-xl-3">{l s='When out of stock:' mod='agilemultipleseller'}</label>
					<div class="agile-col-md-9 agile-col-lg-9 agile-col-xl-9">
						<p class="radio">
							<label id="label_out_of_stock_1" for="out_of_stock_1">
								<input {if $product->out_of_stock == 0} checked="checked" {/if} id="out_of_stock_1" type="radio" checked="checked" value="0" class="out_of_stock" name="out_of_stock">
								{l s='Deny orders' mod='agilemultipleseller'}
							</label>
						</p>
						<p class="radio">
							<label id="label_out_of_stock_2" for="out_of_stock_2">
								<input {if $product->out_of_stock == 1} checked="checked" {/if} id="out_of_stock_2" type="radio" value="1" class="out_of_stock" name="out_of_stock">
								{l s='Allow orders' mod='agilemultipleseller'}
							</label>
						</p>
						<p class="radio">
							<label id="label_out_of_stock_3" for="out_of_stock_3">
								<input {if $product->out_of_stock == 2} checked="checked" {/if} id="out_of_stock_3" type="radio" value="2" class="out_of_stock" name="out_of_stock">
								{l s='Default' mod='agilemultipleseller'}:
								{if $order_out_of_stock == 1}
								{l s='Allow orders' mod='agilemultipleseller'}
								{else}
								{l s='Deny orders' mod='agilemultipleseller'}
								{/if} 
							</label>
						</p>
					</div>
				</div>

			{else}
				<div class="alert alert-warning">
					<p>{l s='It is not possible to manage quantities when:' mod='agilemultipleseller'}</p>
					<ul>
						<li>{l s='You are currently managing all of your shops.' mod='agilemultipleseller'}</li>
						<li>{l s='You are currently managing a group of shops where quantities are not shared between every shop in this group.' mod='agilemultipleseller'}</li>
						<li>{l s='You are currently managing a shop that is in a group where quantities are shared between every shop in this group.' mod='agilemultipleseller'}</li>
					</ul>
				</div>
			{/if}
		{/if}

	</div>
	<div class="panel">
		<h3>{l s='Availability settings' mod='agilemultipleseller'}</h3>

		{if !$has_attribute}
			<div class="form-group ">
				<label class="control-label agile-col-md-3 agile-col-lg-3 agile-col-xl-3" for="minimal_quantity">{l s='Minimum quantity:' mod='agilemultipleseller'}</label>
				<div class="agile-col-md-9 agile-col-lg-9 agile-col-xl-9">
					<div class="row">
						<div class="agile-col-md-9 agile-col-lg-9 agile-col-xl-9">
							<input class="form-control fixed-width-sm" maxlength="6" name="minimal_quantity" id="minimal_quantity" type="text" value="{$product->minimal_quantity|default:1}" />
							<p class="help-block">{l s='The minimum quantity to buy this product (set to 1 to disable this feature)' mod='agilemultipleseller'}</p>
						</div>
					</div>
				</div>
			</div>
		{/if}

		{if $ps_stock_management}			
			<div class="form-group ">
				<label class="control-label agile-col-md-3 agile-col-lg-3 agile-col-xl-3" for="available_now_{$id_language}">
					{include file="module:agilemultipleseller/views/templates/front/multishop/checkbox.tpl" field="available_now" type="default" multilang="true"}
					<span class="label-tooltip" data-toggle="tooltip"
						title="{l s='Forbidden characters:' mod='agilemultipleseller'} &#60;&#62;;&#61;#&#123;&#125;">
						{l s='Displayed text when in-stock:' mod='agilemultipleseller'}
					</span>
				</label>
				<div class="agile-col-md-9 agile-col-lg-9 agile-col-xl-9">
					{include file="module:agilemultipleseller/views/templates/front/products/input_text_lang.tpl"
						languages=$all_languages
						input_value=$product->available_now
						input_name='available_now'}
				</div>
			</div>
			<div class="form-group ">
				<label class="control-label agile-col-md-3 agile-col-lg-3 agile-col-xl-3" for="available_later_{$id_language}">
					{include file="module:agilemultipleseller/views/templates/front/multishop/checkbox.tpl" field="available_later" type="default" multilang="true"}
					<span class="label-tooltip" data-toggle="tooltip"
						title="{l s='Forbidden characters:' mod='agilemultipleseller'} &#60;&#62;;&#61;#&#123;&#125;">
						{l s='Displayed text when back-ordereding is allowed:' mod='agilemultipleseller'}
					</span>
					
				</label>
				<div class="agile-col-md-9 agile-col-lg-9 agile-col-xl-9">
					{include file="module:agilemultipleseller/views/templates/front/products/input_text_lang.tpl"
						languages=$all_languages
						input_value=$product->available_later
						input_name='available_later'}
				</div>
			</div>
			
			{if !$countAttributes}
			<div class="form-group ">
				<label class="control-label agile-col-md-3 agile-col-lg-3 agile-col-xl-3" for="available_date">{l s='Available date:' mod='agilemultipleseller'}</label>
				<div class="agile-col-md-9 agile-col-lg-9 agile-col-xl-9">
					<div class="row">
						<div class="agile-col-md-12 agile-col-lg-12 agile-col-xl-12">
							<div class="input-group fixed-width-md">
								<input id="available_date" name="available_date" value="{$product->available_date}" class="datepicker" type="text" class="form-control" />
								<div class="input-group-addon">
									<i class="icon-calendar-empty"></i>
								</div>

							</div>
							<p class="help-block">{l s='The available date when this product is out of stock.' mod='agilemultipleseller'}</p>
						</div>
					</div>
				</div>
			</div>
			{/if}
		{/if}

	<div class="form-group  agile-align-center">
		<button type="submit" class="agile-btn agile-btn-default" name="submitQuantities" value="{l s='Save' mod='agilemultipleseller'}">
		<i class="icon-save "></i>&nbsp;<span>{l s='Save' mod='agilemultipleseller'}</span></button >
	</div>

{/if}
