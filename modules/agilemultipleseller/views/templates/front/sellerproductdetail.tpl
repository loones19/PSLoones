{extends file='page.tpl'}

{block name='page_content'}

	{capture name=path}<a href="{$link->getPageLink('my-account', true)}">{l s='My Account' mod='agilemultipleseller'}</a><span class="navigation-pipe">{$navigationPipe}</span>{l s='My Seller Account'  mod='agilemultipleseller'}{/capture}
	<div id="agile">
	<h1>{l s='My Seller Account' mod='agilemultipleseller'}</h1>
	{include file="module:agilemultipleseller/views/templates/front/seller_tabs.tpl"}
	<br />
	{if isset($isSeller) AND $isSeller AND ($id_product>0 OR !$is_list_limited)}
		{include file="module:agilemultipleseller/views/templates/front/products/product_top.tpl"}
		<div  class="row" {if $hasOwnerShip}{else}style="display:none;"{/if}>
			{include file="module:agilemultipleseller/views/templates/front/products/product_nav.tpl"}
			<form id="product_form" name="product" action="{$link->getModuleLink('agilemultipleseller', 'sellerproductdetail', ['id_product'=>$id_product,'product_menu'=>$product_menu], true)}" 
			enctype="multipart/form-data" method="post" class="form-horizontal agile-col-md-9 agile-col-lg-9 agile-col-xl-9">
			{if $product_menu == 1}
			{include file="module:agilemultipleseller/views/templates/front/products/informations.tpl"}
			{else if $product_menu == 2}
			{include file="module:agilemultipleseller/views/templates/front/products/images.tpl"}
			{else if $product_menu == 3}
			{include file="module:agilemultipleseller/views/templates/front/products/features.tpl"}   
			{else if $product_menu == 4}
			{include file="module:agilemultipleseller/views/templates/front/products/associations.tpl"}
			{else if $product_menu == 5}
			{include file="module:agilemultipleseller/views/templates/front/products/prices.tpl"}
			{else if $product_menu == 6}
			{include file="module:agilemultipleseller/views/templates/front/products/quantites.tpl"}
			{else if $product_menu == 7}
			{include file="module:agilemultipleseller/views/templates/front/products/combinations.tpl"}
			{else if $product_menu == 8}
			{include file="module:agilemultipleseller/views/templates/front/products/virtualproduct.tpl"}
			{else if $product_menu == 9}
			{include file="module:agilemultipleseller/views/templates/front/products/shipping.tpl"}
			{else if $product_menu == 10}
			{include file="module:agilemultipleseller/views/templates/front/products/attachments.tpl"}
			{else if $product_menu == 11}
			{include file="module:agilemultipleseller/views/templates/front/products/producttags.tpl"}
			{/if}

			</form>
		</div>
		<br />
	{/if}

	</div>
	{include file="module:agilemultipleseller/views/templates/front/seller_footer.tpl"}
	
{/block}

{block name='javascript_bottom'}
    {$smarty.block.parent}

	{* common begin ---------------------------------------------------------------- *}
	<script type="text/javascript">
	    var currentmenuid = {$product_menu};

		var base_dir = "{$base_dir_ssl}";
		var id_product = {$id_product};
		var priceDisplayPrecision = {$smarty.const._PS_PRICE_DISPLAY_PRECISION_|intval};

       hideOtherLanguage({$id_language});

		function changeMyLanguage(field, fieldsString, id_language_new, iso_code){
			changeLanguage(field, fieldsString, id_language_new, iso_code);
			$("img[id^='language_current_']").attr("src","{$base_dir}img/l/" + id_language_new + ".jpg");
		}


	</script>
	{* common end ---------------------------------------------------------------- *}


	{* Information begin ---------------------------------------------------------- *}
	{if $product_menu == 1}
    <script type="text/javascript">
        var msg_select_one = "{l s='Please select at least one product.' mod='agilemultipleseller' js=1}";
        var msg_set_quantity = "{l s='Please set a quantity to add a product.' mod='agilemultipleseller' js=1}";

        {if isset($ps_force_friendly_product) && $ps_force_friendly_product}
        var ps_force_friendly_product = 1;
        {else}
        var ps_force_friendly_product = 0;
        {/if}

        {if isset($PS_ALLOW_ACCENTED_CHARS_URL) && $PS_ALLOW_ACCENTED_CHARS_URL}
        var PS_ALLOW_ACCENTED_CHARS_URL = 1;
        {else}
        var PS_ALLOW_ACCENTED_CHARS_URL = 0;
        {/if}
       
	    var iso = "{$isoTinyMCE}";
		var pathCSS = "{$theme_css_dir}";
		var ad = "{$ad}";



		{foreach from=$all_languages item=language}
			{literal}
			$().ready(function () {
				var input_id = '{/literal}tags_{$language.id_lang}{literal}';
				$('#'+input_id).tagify({delimiters: [13,44], addTagPrompt: '{/literal}{l s='Add tag' mod='agilemultipleseller' js=1}{literal}'});
				$({/literal}'#product_form{literal}').submit( function() {
					$(this).find('#'+input_id).val($('#'+input_id).tagify('serialize'));
				});
			});
			{/literal}
		{/foreach}


	$(document).ready(function() {
		tinySetup({
			selector: ".rte" ,
			toolbar1 : "code,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,|,blockquote,colorpicker,pasteword,|,bullist,numlist,|,outdent,indent,|,link,unlink,|,cleanup"
		});

		$("#available_for_order").click(function(){
			if ($(this).is(':checked')){
				$('#show_price').attr('checked', 'checked');
				$('#show_price').attr('disabled', 'disabled');
			}
			else{
				$('#show_price').removeAttr('disabled');
			}
		});

		$(".checker").removeClass("checker");

		$("[id^='cancellink_']").click(function() {
		    return confirm("{l s='Are you sure want to cancel selected options?' mod='agilemultipleseller'}");
		});

    });

	</script>

	{/if}
	{* Informaiton end --------------------------------------------------- *}


	{* Images begin --------------------------------------------------- *}
	{if $product_menu == 2}
    <script type="text/javascript">
		var upbutton = "{l s='Upload an image' mod='agilemultipleseller'}";
		var token = '{$token}';
		var come_from = '{$table}';
		var success_add =  "{l s='image has been successfully added' mod='agilemultipleseller'}";
		var id_tmp = 0;
		var ajax_products_url = "{$base_dir_ssl}modules/agilemultipleseller/ajax_products.php";
		var image_count = {count($images)};
		var image_number_limit = {$image_number_limit};
		{literal}
		/** _agile_ Ready Function _agile_ **/
		$(document).ready(function(){
			{/literal}
			{foreach from=$images item=image}
				assoc = {literal}"{"{/literal};
				{if $shops}
					{foreach from=$shops item=shop}
						assoc += '"{$shop.id_shop}" : {if $image->isAssociatedToShop($shop.id_shop)}1{else}0{/if},';
					{/foreach}
				{/if}
				if (assoc != {literal}"{"{/literal})
				{
					assoc = assoc.slice(0, -1);
					assoc += {literal}"}"{/literal};
					assoc = jQuery.parseJSON(assoc);
				}
				else
					assoc = false;
				imageLine({$image->id}, "{$image->getExistingImgPath()}", {$image->position}, "{if $image->cover}enabled{else}disabled{/if}", assoc,"{$image->legend[$id_language_current]|escape:'htmlall'}");
			{/foreach}
			{literal}
			$("#imageTable").tableDnD(
			{
				onDrop: function(table, row) {
				current = $(row).attr("id");
				stop = false;
				image_up = "{";
				$("#imageList").find("tr").each(function(i) {
					$("#td_" +  $(this).attr("id")).html(i + 1);
					if ($(this).attr("id") == current)
					{	
						image_up += '"' + $(this).attr("id") + '" : ' + (i + 1) + ',';
						stop = true;
					}
					if (!stop || (i + 1) == 2)
						image_up += '"' + $(this).attr("id") + '" : ' + (i + 1) + ',';
				});
				image_up = image_up.slice(0, -1);
				image_up += "}";
				updateImagePositon(image_up);
				}
			});
			var filecheck = 1;

			/**
			 * on success function 
			 */
			function afterDeleteProductImage(data)
			{
				data = $.parseJSON(data);
				if (data)
				{
					cover = 0;
					id = data.content.id;
					if(data.status == 'ok')
					{
						if ($("#" + id).find(".covered").attr("src") == "{$base_dir_ssl}img/admin/enabled.gif")
							cover = 1;
						$("#" + id).remove();
					}
					if (cover)
						$("#imageTable tr").eq(1).find(".covered").attr("src", "{$base_dir_ssl}img/admin/enabled.gif");
					$("#countImage").html(parseInt($("#countImage").html()) - 1);
					refreshImagePositions($("#imageTable"));
					
					image_count--;
					if(image_count < image_number_limit && image_number_limit>0)$("#divUploadControl").show();

					if(data.confirmations)agile_show_message(data.confirmations);

					if (parseInt($("#countImage").html()) <= 1)
						$('#caption_selection').addClass('hidden');

				}
			}

			$('.delete_product_image').off().on('click', function(e)
			{
				e.preventDefault();
				id = $(this).parent().parent().attr('id');
				if (confirm("{/literal}{l s='Are you sure?' mod='agilemultipleseller' js=1}{literal}"))
				doFrontAjax(ajax_products_url,
				        {
						    "action":"deleteProductImage",
						    "id_image":id,
						    "id_product" : {/literal}{$id_product}{literal},
						    "id_category" : {/literal}{$id_category_default}{literal},
						    "ajax" : 1 
						}, 
						afterDeleteProductImage
				);
			});
			
			$('.covered').off().on('click', function(e)
			{
				e.preventDefault();
				id = $(this).parent().parent().parent().attr('id');
				$("#imageList .cover img").each( function(i){
					$(this).attr("src", $(this).attr("src").replace("enabled", "disabled"));
				});
				$(this).attr("src", $(this).attr("src").replace("disabled", "enabled"));
				doFrontAjax(ajax_products_url,
				{
					"action":"UpdateCover",
					"id_image":id,
					"id_product" : {/literal}{$id_product}{literal},
					"ajax" : 1 }
				);
				
			});
			
			$('.image_shop').off().on('click', function()
			{
				active = false;
				if ($(this).attr("checked"))
					active = true;
				id = $(this).parent().parent().attr('id');
				id_shop = $(this).attr("id").replace(id, "");
				doFrontAjax(ajax_products_url,
				{
					"action":"UpdateProductImageShopAsso",
					"id_image":id,
					"id_shop": id_shop,
					"active":active,
					"token" : "{/literal}{$token}{literal}",
					"tab" : "AdminProducts",
					"ajax" : 1 
				});
			});
			
			/** _agile_ function	_agile_ **/
			function updateImagePositon(json)
			{
				doFrontAjax(ajax_products_url,
				{
					"action":"updateImagePosition",
					"json":json,
					"token" : "{/literal}{$token}{literal}",
					"tab" : "AdminProducts",
					"ajax" : 1
				});
	
			}
			
			function delQueue(id)
			{
				$("#img" + id).fadeOut("slow");
				$("#img" + id).remove();
			}
			
			function imageLine(id, path, position, cover, shops, legend)
			{
				line = $("#lineType").html();
				line = line.replace(/image_id/g, id);
    			line = line.replace(/en-default/g, path);
	    		line = line.replace(/image_path/g, path);
				line = line.replace(/image_position/g, position);
				line = line.replace(/legend/g, legend);
				line = line.replace(/blank/g, cover);
				line = line.replace("<tbody>", "");
				line = line.replace("</tbody>", "");
				if (shops != false)
				{
					$.each(shops, function(key, value){
						if (value == 1)
							line = line.replace('id="' + key + '' + id + '"','id="' + key + '' + id + '" checked=checked');
					});
				}
				$("#imageList").append(line);
			}

			function refreshImagePositions(imageTable)
			{
				var reg = /_[0-9]$/g;
				var up_reg  = new RegExp("imgPosition=[0-9]+&");

				imageTable.find("tbody tr").each(function(i,el) {
					$(el).find("td.positionImage").html(i + 1);
				});
				imageTable.find("tr td.dragHandle a:hidden").show();
				imageTable.find("tr td.dragHandle:first a:first").hide();
				imageTable.find("tr td.dragHandle:last a:last").hide();
			}

		});
		{/literal}

	</script>
	{/if}
	{* Images end --------------------------------------------------- *}


	{* Association begin --------------------------------------------------- *}
	{if $product_menu == 4}
	<script type="text/javascript">
		var base_dir_ssl = "{$base_dir_ssl}";

		searchCategory();

		$(document).ready(function(){
			buildTreeView();
			initAccessory({$accessories|json_encode nofilter});
			/** _agile_ Not allow product in home _agile_ **/
			if({$allow_register_athome} != 1)$("input[type='checkbox'][name='categoryBox[]'][value=2]").parent().remove();

		});
	</script>
	{/if}
	{* Association end --------------------------------------------------- *}



	{* Prices begin --------------------------------------------------- *}
	{if $product_menu == 5}
	<script type="text/javascript">
		var product_prices = new Array();
		{foreach from=$combinations item='combination'}
			product_prices['{$combination.id_product_attribute}'] = '{$combination.price}';
		{/foreach}
	</script>

	<script type="text/javascript">
		noTax = {if $tax_exclude_taxe_option}true{else}false{/if};
		taxesArray = new Array();
		{foreach $taxesRatesByGroup as $tax_by_group}
			taxesArray[{$tax_by_group.id_tax_rules_group}] = {$tax_by_group|json_encode nofilter};
		{/foreach}
		ecotaxTaxRate = {$ecotaxTaxRate / 100};
	</script>

	<script type="text/javascript">
	var Customer = new Object();
	var ecotax_tax_excl = parseFloat({$ecotax_tax_excl});
	var priceDisplayPrecision = {$smarty.const._PS_PRICE_DISPLAY_PRECISION_|intval};

	$(document).ready(function () {
		Customer = {
			"hiddenField": jQuery('#id_customer'),
			"field": jQuery('#customer'),
			"container": jQuery('#customers'),
			"loader": jQuery('#customerLoader'),
			"init": function() {
				jQuery(Customer.field).typeWatch({
					"captureLength": 1,
					"highlight": true,
					"wait": 50,
					"callback": Customer.search
				}).focus(Customer.placeholderIn).blur(Customer.placeholderOut);
			},
			"placeholderIn": function() {
				if (this.value == '{l s='All customers' mod='agilemultipleseller'}') {
					this.value = '';
				}
			},
			"placeholderOut": function() {
				if (this.value == '') {
					this.value = '{l s='All customers' mod='agilemultipleseller'}';
				}
			},
			"search": function()
			{
				Customer.showLoader();
				jQuery.ajax({
					"type": "POST",
					"url":  "{$base_dir_ssl}modules/agilemultipleseller/ajax_agile_getcustomers.php",
					"async": true,
					"dataType": "json",
					"data": {
						"ajax": "1",
						"tab": "AgileProductCustomers",
						"action": "searchCustomers",
						"customer_search": Customer.field.val()
					},
					"success": Customer.success
				});
			},
			"success": function(result)
			{
				if(result.found) {
					var html = '<ul class="list-unstyled">';
					jQuery.each(result.customers, function() {
						html += '<li>'+this.firstname+' '+this.lastname+(this.birthday ? ' - '+this.birthday:'');
						html += ' - '+this.email;
						html += '<a onclick="Customer.select('+this.id_customer+', \''+this.firstname+' '+this.lastname+'\'); return false;" href="#" class="btn btn-default">{l s='Choose' mod='agilemultipleseller'}</a></li>';
					});
					html += '</ul>';
				}
				else
					html = '<div class="alert alert-warning">{l s='No customers found' mod='agilemultipleseller'}</div>';
				Customer.hideLoader();
				Customer.container.html(html);
				jQuery('.fancybox', Customer.container).fancybox();
			},
			"select": function(id_customer, fullname)
			{
				Customer.hiddenField.val(id_customer);
				Customer.field.val(fullname);
				Customer.container.empty();
				return false;
			},
			"showLoader": function() {
				Customer.loader.fadeIn();
			},
			"hideLoader": function() {
				Customer.loader.fadeOut();
			}
		};
		Customer.init();
	});
	</script>

	<script type="text/javascript">
		$(document).ready(function(){
			product_prices['0'] = $('#sp_current_ht_price').html();

			$('#id_product_attribute').change(function() {
				$('#sp_current_ht_price').html(product_prices[$('#id_product_attribute option:selected').val()]);
			});

			$('#leave_bprice').click(function() {
				if (this.checked)
					$('#sp_price').attr('disabled', 'disabled');
				else
					$('#sp_price').removeAttr('disabled');
			});

			$('.datepicker').datetimepicker({
				prevText: '',
				nextText: '',
				dateFormat: 'yy-mm-dd',

				// Define a custom regional settings in order to use PrestaShop translation tools
				currentText: '{l s='Now' mod='agilemultipleseller'}',
				closeText: '{l s='Done' mod='agilemultipleseller'}',
				ampm: false,
				amNames: ['AM', 'A'],
				pmNames: ['PM', 'P'],
				timeFormat: 'hh:mm:ss tt',
				timeSuffix: '',
				timeOnlyTitle: '{l s='Choose Time' mod='agilemultipleseller'}',
				timeText: '{l s='Time' mod='agilemultipleseller'}',
				hourText: '{l s='Hour' mod='agilemultipleseller'}',
				minuteText: '{l s='Minute' mod='agilemultipleseller'}',
			});

			calcPriceTI();
			unitPriceWithTax('unit');

		});
	</script>
	{/if}
	{* Prices end --------------------------------------------------- *}

	{* Quantities begin --------------------------------------------------- *}
	{if $product_menu == 6}
	<script type="text/javascript">
		$(document).ready(function() {
			$('.datepicker').datepicker({
				prevText: '',
				nextText: '',
				dateFormat: 'yy-mm-dd',

				/** _agile_ Define a custom regional settings in order to use PrestaShop translation tools _agile_ **/
				currentText: '{l s='Now' mod='agilemultipleseller'}',
			});
		});

	</script>

			<script type="text/javascript">
			var quantities_ajax_success = '{l s='Data saved' mod='agilemultipleseller'}';
			var quantities_ajax_waiting = '{l s='Saving data...' mod='agilemultipleseller'}';
		</script>
		<div class="agile-padding"> </div>
	</div>

	<script type="text/javascript">
		$('.datepicker').datepicker({
			prevText: '',
			nextText: '',
			dateFormat: 'yy-mm-dd'
		});

		var showAjaxError = function(msg)
		{
			$('#available_quantity_ajax_error_msg').html(msg);
			$('#available_quantity_ajax_error_msg').show();
			$('#available_quantity_ajax_msg').hide();
			$('#available_quantity_ajax_success_msg').hide();
		};
	
		var showAjaxSuccess = function(msg)
		{
			$('#available_quantity_ajax_success_msg').html(msg);
			$('#available_quantity_ajax_error_msg').hide();
			$('#available_quantity_ajax_msg').hide();
			$('#available_quantity_ajax_success_msg').show();
		};
	
		var showAjaxMsg = function(msg)
		{
			$('#available_quantity_ajax_msg').html(msg);
			$('#available_quantity_ajax_error_msg').hide();
			$('#available_quantity_ajax_msg').show();
			$('#available_quantity_ajax_success_msg').hide();
		};
	
		var ajaxCall = function(data)
		{
			data.ajaxProductQuantity = 1;
			data.id_product = '{$product->id}';
			data.ajax = 1;
			data.action = "productQuantity";
			showAjaxMsg('{l s='Saving data...' mod='agilemultipleseller'}');
			$.ajax({
				type: "POST",
				url: "{$base_dir_ssl}modules/agilemultipleseller/ajax_products.php",
				data: data,
				dataType: 'json',
				async : true,
				success: function(msg)
				{
					if (msg.error)
					{
						showAjaxError(msg.error);
						return;
					}
					showAjaxSuccess('{l s='Data saved' mod='agilemultipleseller'}');
				},
				error: function(msg)
				{
					showAjaxError(msg.error);
				}
			});
		};
	
		var refreshQtyAvaibilityForm = function()
		{
			if ($('#depends_on_stock_0').attr('checked'))
			{
				$('.available_quantity').find('input').show();
				$('.available_quantity').find('span').hide();
			}
			else
			{
				$('.available_quantity').find('input').hide();
				$('.available_quantity').find('span').show();
			}
		};
	
		$('.depends_on_stock').click(function(e)
		{
			refreshQtyAvaibilityForm();
			ajaxCall( { actionQty: 'depends_on_stock', value: $(this).val() } );
			if($(this).val() == 0)
				$('.available_quantity input').trigger('change');
		});

		$('.advanced_stock_management').click(function(e)
		{
			var val = 0;
			if ($(this).attr('checked'))
				val = 1;
			
			ajaxCall( { actionQty: 'advanced_stock_management', value: val } );
			if (val == 1)
			{
				$(this).val(1);
				$('#depends_on_stock_1').attr('disabled', false);
			}
			else
			{
				$(this).val(0);
				$('#depends_on_stock_1').attr('disabled', true);
				$('#depends_on_stock_0').attr('checked', true);
				ajaxCall( { actionQty: 'depends_on_stock', value: 0} );
				refreshQtyAvaibilityForm();
			}
			refreshQtyAvaibilityForm();
		});
	
		/** _agile_ bind enter key event on search field _agile_ **/
		$('.available_quantity').find('input').bind('keypress', function(e) {
			var code = (e.keyCode ? e.keyCode : e.which);
			if(code == 13) { /** _agile_ Enter keycode  field _agile_ **/
				e.stopPropagation();/** _agile_ Stop event propagation  field _agile_ **/
				return false;
			}
		});
	
		$('.available_quantity').find('input').change(function(e, init_val)
		{
			ajaxCall( { actionQty: 'set_qty', id_product_attribute: $(this).parent().attr('id').split('_')[1], value: $(this).val() } );
		});
	
		$('.out_of_stock').click(function(e)
		{
			refreshQtyAvaibilityForm();
			ajaxCall( { actionQty: 'out_of_stock', value: $(this).val() } );
		});
	
		refreshQtyAvaibilityForm();
	</script>
	{/if}
	{* Quantities end --------------------------------------------------- *}


	{* Combination begin --------------------------------------------------- *}
	{if $product_menu == 7 && !$product->is_virtual}
	<script type="text/javascript">
		noTax = {if $tax_exclude_taxe_option}true{else}false{/if};
		taxesArray = new Array();
		{foreach $taxesRatesByGroup as $tax_by_group}
			taxesArray[{$tax_by_group.id_tax_rules_group}] = {$tax_by_group|json_encode nofilter};
		{/foreach}
		ecotaxTaxRate = {$ecotaxTaxRate / 100};
	</script>


	<script type="text/javascript">
		var msg_combination_1 = "{l s='Please choose an attribute' mod='agilemultipleseller'}";
		var msg_combination_2 = "{l s='Please choose a value' mod='agilemultipleseller'}";
		var msg_combination_3 = "{l s='You can only add one combination per type of attribute' mod='agilemultipleseller'}";
		var msg_new_combination = "{l s='New combination' mod='agilemultipleseller'}";
		var msg_cancel_combination = '{l s='Cancel combination'  mod='agilemultipleseller'}';
		$(document).ready(function(){
			populate_attrs();
			$(".datepicker").datepicker({
				prevText: '',
				nextText: '',
				dateFormat: 'yy-mm-dd'
			});
		});

		{$combinationImagesJs}
		var attrs = new Array();
		var modifyattributegroup = "{l s='Modify this attribute combination'  mod='agilemultipleseller' js=1}";
		attrs[0] = new Array(0, "---");
	    {foreach from=$attributeJs key=idgrp item=group}
		    attrs[{$idgrp}] = new Array(0
		    , '---'
		    {foreach from=$group key=idattr item=attrname}
			    , "{$idattr}", "{$attrname|addslashes}"
		    {/foreach}
		    );
	    {/foreach}
	    
	    
	    add_new_combination_form_visible = {if count($errors)>0}true{else}false{/if};
        ajax_products_url = "{$base_dir_ssl}modules/agilemultipleseller/ajax_products.php";
	    function toggle_add_new_combination_form()
	    {
			attr_selectall();
	        if(add_new_combination_form_visible)
	        {
	            $("#add_new_combination").hide();
	            $("#brnCancelNewComb").hide();
	            $("#submitCombinations").hide();
	            $("#btnAddNewComb").show();
	        }
	        else
	        {
	            $("#add_new_combination").show();
	            $("#brnCancelNewComb").show();
	            $("#submitCombinations").show();
	            $("#btnAddNewComb").hide();
	        }    
	        add_new_combination_form_visible = !add_new_combination_form_visible;    
	    }
	    
	    function delete_comb(id_product,id_product_attribute)
	    {
	        if(confirm("{l s='Are sure want to delete this combination?' mod='agilemultipleseller' js=1}"))
	        {
				doFrontAjax(ajax_products_url,
				        {
						    "action":"deleteProductAttribute",
						    "id_product":id_product,
						    "id_product_attribute":id_product_attribute,
						    "ajax" : 1 
						}, 
						function(data)
						{
                            data = $.parseJSON(data);
						    agile_show_message(data.message);
						    $("#tr_comb_" + id_product_attribute).remove();
						}
				);
	        }
	    }
	    	    
	    function set_default_comb(id_product, id_product_attribute)
	    {
			doFrontAjax(ajax_products_url,
			        {
					    "action":"defaultProductAttribute",
					    "id_product":id_product,
					    "id_product_attribute":id_product_attribute,
					    "ajax" : 1 
					}, 
					function(data)
					{
                        data = $.parseJSON(data);
					    agile_show_message(data.message);
                        $("img[id^='icon_default_']").show();
                        $("#icon_default_" + id_product_attribute).hide();
					}
			);
	    
	    }
	    
	    
		function edit_comb (id_product, id_product_attribute)
		{
			$.ajax({
				url: ajax_products_url,
				type: "POST",
				data: {
					"id_product":id_product,
					"id_product_attribute":id_product_attribute,
					ajax: true,
					action: 'editProductAttribute'
				},
				dataType: 'json',
				async: false,
				success: function(data) {
					// color the selected line
					add_new_combination_form_visible = false;
                    toggle_add_new_combination_form();
					$('#attribute_quantity').show();
					var wholesale_price = Math.abs(data[0]['wholesale_price']);
					var price = data[0]['price'];
					var weight = data[0]['weight'];
					var unit_impact = data[0]['unit_price_impact'];
					var reference = data[0]['reference'];
					var ean = data[0]['ean13'];
					var quantity = data[0]['quantity'];
					var image = false;
					var product_att_list = new Array();
					for(i=0;i<data.length;i++)
					{
						product_att_list.push(data[i]['group_name']+' : '+data[i]['attribute_name']);
						product_att_list.push(data[i]['id_attribute']);
					}

					var id_product_attribute = data[0]['id_product_attribute'];
					var default_attribute = data[0]['default_on'];
					var eco_tax = data[0]['ecotax'];
					var upc = data[0]['upc'];
					var minimal_quantity = data[0]['minimal_quantity'];
					var available_date = data[0]['available_date'];

					if (wholesale_price != 0 && wholesale_price > 0)
					{
						$("#attribute_wholesale_price_full").show();
						$("#attribute_wholesale_price_blank").hide();
					}
					else
					{
						$("#attribute_wholesale_price_full").hide();
						$("#attribute_wholesale_price_blank").show();
					}
					fillCombination(
						wholesale_price,
						price,
						weight,
						unit_impact,
						reference,
						ean,
						quantity,
						image,
						product_att_list,
						id_product_attribute,
						default_attribute,
						eco_tax,
						upc,
						minimal_quantity,
						available_date
					);
					calcImpactPriceTI();
				}
			});
		}
	    
	    
	function fillCombination(wholesale_price, price_impact, weight_impact, unit_impact, reference,
	ean, quantity, image, old_attr, id_product_attribute, default_attribute, eco_tax, upc, minimal_quantity, available_date)
	{
		var link = '';
		init_elems();
		$('#stock_mvt_attribute').show();
		$('#initial_stock_attribute').hide();
		$('#attribute_quantity').html(quantity);
		$('#attribute_quantity').show();
		$('#attr_qty_stock').show();

		$('#attribute_minimal_quantity').val(minimal_quantity);

		getE('attribute_reference').value = reference;

		getE('attribute_ean13').value = ean;
		getE('attribute_upc').value = upc;
		getE('attribute_wholesale_price').value = Math.abs(wholesale_price);
		getE('attribute_price').value = ps_round(Math.abs(price_impact), 2);
		getE('attribute_priceTEReal').value = Math.abs(price_impact);
		getE('attribute_weight').value = Math.abs(weight_impact);
		getE('attribute_unity').value = Math.abs(unit_impact);
		if ($('#attribute_ecotax').length != 0)
			getE('attribute_ecotax').value = eco_tax;

		if (default_attribute == 1)
			getE('attribute_default').checked = true;
		else
			getE('attribute_default').checked = false;

		if (price_impact < 0)
		{
			getE('attribute_price_impact').options[getE('attribute_price_impact').selectedIndex].value = -1;
			getE('attribute_price_impact').selectedIndex = 2;
		}
		else if (!price_impact)
		{
			getE('attribute_price_impact').options[getE('attribute_price_impact').selectedIndex].value = 0;
			getE('attribute_price_impact').selectedIndex = 0;
		}
		else if (price_impact > 0)
		{
			getE('attribute_price_impact').options[getE('attribute_price_impact').selectedIndex].value = 1;
			getE('attribute_price_impact').selectedIndex = 1;
		}
		if (weight_impact < 0)
		{
			getE('attribute_weight_impact').options[getE('attribute_weight_impact').selectedIndex].value = -1;
			getE('attribute_weight_impact').selectedIndex = 2;
		}
		else if (!weight_impact)
		{
			getE('attribute_weight_impact').options[getE('attribute_weight_impact').selectedIndex].value = 0;
			getE('attribute_weight_impact').selectedIndex = 0;
		}
		else if (weight_impact > 0)
		{
			getE('attribute_weight_impact').options[getE('attribute_weight_impact').selectedIndex].value = 1;
			getE('attribute_weight_impact').selectedIndex = 1;
		}
		if (unit_impact < 0)
		{
			getE('attribute_unit_impact').options[getE('attribute_unit_impact').selectedIndex].value = -1;
			getE('attribute_unit_impact').selectedIndex = 2;
		}
		else if (!unit_impact)
		{
			getE('attribute_unit_impact').options[getE('attribute_unit_impact').selectedIndex].value = 0;
			getE('attribute_unit_impact').selectedIndex = 0;
		}
		else if (unit_impact > 0)
		{
			getE('attribute_unit_impact').options[getE('attribute_unit_impact').selectedIndex].value = 1;
			getE('attribute_unit_impact').selectedIndex = 1;
		}

		$("#add_new_combination").show();

		/* Reset all combination images */
		combinationImages = $('#id_image_attr').find("input[id^=id_image_attr_]");
		combinationImages.each(function() {
			this.checked = false;
		});

		/* Check combination images */
		
		if (typeof(combination_images[id_product_attribute]) != 'undefined')
			for (i = 0; i < combination_images[id_product_attribute].length; i++)
				$('#id_image_attr_' + combination_images[id_product_attribute][i]).attr('checked', true);
	    
		check_impact();
		check_weight_impact();
		check_unit_impact();

		var elem = getE('product_att_list');

		for (var i = 0; i < old_attr.length; i++)
		{
			var opt = document.createElement('option');
			opt.text = old_attr[i++];
			opt.value = old_attr[i];
			try {
				elem.add(opt, null);
			}
			catch(ex) {
				elem.add(opt);
			}
		}
		getE('id_product_attribute').value = id_product_attribute;

		$('#available_date_attribute').val(available_date);
	}
	
	init_elems = function()
	{
		var impact = getE('attribute_price_impact');
		var impact2 = getE('attribute_weight_impact');
		var elem = getE('product_att_list');

		if (elem.length)
			for (i = elem.length - 1; i >= 0; i--)
				if (elem[i])
					elem.remove(i);

		$('input[name="id_image_attr[]"]').each(function (){
			$(this).attr('checked', false);
		});

		$('#attribute_default').attr('checked', false);

		getE('attribute_price_impact').selectedIndex = 0;
		getE('attribute_weight_impact').selectedIndex = 0;
		getE('attribute_unit_impact').selectedIndex = 0;
		$('#span_unit_impact').hide();
		$('#unity_third').html($('#unity_second').html());

		if ($('#unity').is())
			if ($('#unity').get(0).value.length > 0)
				$('#tr_unit_impact').show();
			else
				$('#tr_unit_impact').hide();
		try
		{
			if (impact.options[impact.selectedIndex].value == 0)
				$('#span_impact').hide();
			if (impact2.options[impact.selectedIndex].value == 0)
				getE('span_weight_impact').style.display = 'none';
		}
		catch (e)
		{
			$('#span_impact').hide();
			getE('span_weight_impact').style.display = 'none';
		}
	}


	function select_all()
	{
		$("#product_att_list option").attr("selected","selected");
	}
	
	
	function attribute_price_keyup()
	{
		var price = $('#attribute_price').val().replace(/,/g, '.');
		$('#attribute_priceTEReal').val(price);
		if (isArrowKey(event)) return ;
		 $('#attribute_price').val(price);
		calcImpactPriceTI();
	}


	function attribute_priceTI_keyup()
	{
		if (isArrowKey(event)) return;
		this.value = this.value.replace(/,/g, '.'); 
		calcImpactPriceTE();
	}
	</script>

	{/if}
	{* Combination end --------------------------------------------------- *}


	{* Virtual product begin --------------------------------------------------- *}
	{if $product_menu == 8}
	<script type="text/javascript">
		var newLabel = '{l s='New label' mod='agilemultipleseller'}';
		var choose_language = '{l s='Choose language:' mod='agilemultipleseller'}';
		var required = '{l s='required' mod='agilemultipleseller'}';
		var customizationUploadableFileNumber = '{$product->uploadable_files}';
		var customizationTextFieldNumber = '{$product->text_fields}';
		var uploadableFileLabel = 0;
		var textFieldLabel = 0;
		var cache_default_attribute =  {$product->cache_default_attribute};
		var base_dir_ssl = "{$base_dir_ssl}";

		$(document).ready(function () {
			show_hide_error();

			$('.datepicker').datepicker({
				prevText: '',
				nextText: '',
				dateFormat: 'yy-mm-dd'
			});

		});

		function uploadFile()
		{
			$("#imgProcessing").show();
			$.ajaxFileUpload (
				{
					url: base_dir_ssl + 'modules/agilemultipleseller/uploadProductFile.php',
					secureuri:false,
					fileElementId:'virtual_product_file',
					dataType: 'xml',
					success: function (data, status)
					{
						$("#imgProcessing").hide();
						data = data.getElementsByTagName('return')[0];
						var result = data.getAttribute("result");
						var msg = data.getAttribute("msg");
						var fileName = data.getAttribute("filename");
						if (result == "error")
						{
							$("#upload-confirmation").hide();
							$("#upload-error td").html('<div class="error">{l s='Error:' mod='agilemultipleseller'} ' + msg + '</div>');
							$("#upload-error").show();
						}
						else
						{
							$('#upload-error').hide();
							$('#file_missing').hide();
							$('#upload_input').hide();
							$('#virtual_product_name').attr('value', fileName);
							$("#upload-confirmation .error").remove();
							$('#upload-confirmation div').prepend('<span>{l s='The file' mod='agilemultipleseller'}&nbsp;"<a class="link" href="{$base_dir_ssl}index.php?controller=get-file&file='+msg+'&filename='+fileName+'">'+fileName+'</a>"&nbsp;{l s='has successfully been uploaded' mod='agilemultipleseller'}' +
								'<input type="hidden" id="virtual_product_filename" name="virtual_product_filename" value="' + msg + '" /></span>');
							$("#upload-confirmation").show();

							$("#virtual_product_file").parent().parent().remove();
						}
					}
				}
			);
		}

		function uploadFile2()
		{
				$("#imgProcessing").show();
				var link = '';
				$.ajaxFileUpload (
				{
					url:  base_dir_ssl +  'modules/agilemultipleseller/uploadProductFileAttribute.php',
					secureuri:false,
					fileElementId:'virtual_product_file_attribute',
					dataType: 'xml',
					success: function (data, status)
					{
						$("#imgProcessing").hide();

						data = data.getElementsByTagName('return')[0];
						var result = data.getAttribute("result");
						var msg = data.getAttribute("msg");
						var fileName = data.getAttribute("filename");
						if(result == "error")
							$("#upload-confirmation2").html('<p>error: ' + msg + '</p>');
						else
						{
							$('#virtual_product_file_attribute').remove();
							$('#virtual_product_file_label').hide();
							$('#file_missing').hide();
							$('#delete_downloadable_product_attribute').show();
							$('#upload-confirmation2').html(
								'<a class="link" href="{$base_dir_ssl}index.php?controller=get-file&file='+msg+'&filename='+fileName+'">{l s='The file' mod='agilemultipleseller'}&nbsp;"' + fileName + '"&nbsp;{l s='has successfully been uploaded' mod='agilemultipleseller'}</a>' +
								'<input type="hidden" id="virtual_product_filename_attribute" name="virtual_product_filename_attribute" value="' + msg + '" />');
							$('#virtual_product_name_attribute').attr('value', fileName);

							link = $("#delete_downloadable_product_attribute").attr('href');
							$("#delete_downloadable_product_attribute").attr('href', link+"&file="+msg);
						}
					}
				}
			);
		}


		function ajax_delete_virtual_downloadfile()
		{
			var ans = confirm("{l s='Are you sure want to delete the file?' mod='agilemultipleseller'}");
			if(!ans)return false;

			$.ajax({
				url: base_dir_ssl + '/modules/agilemultipleseller/ajax_products.php',
				type: "POST",
				data: {
					action: 'deleteVirtualProduct',
					id_product: {$product->id}
				},
				dataType: 'json',
				async: false,
				success: function(data) {
					if (data.status == 'ok') {
						$('#file_missing').show();
						$('#upload_input').show();
						$("#upload-confirmation").hide();
						$("#tr_link_to_file").remove();
					}
					else
						agile_show_message(data.message);
				}
			});


		
			return false;
		}

		function is_virtual_goods_onchange()
		{
			if($("#is_virtual_good").is(":checked"))
			{
				$("#tr_downloadable").show();
				is_virtual_file_onclick();
			}
			else 
			{
				$("#tr_downloadable").hide();
				$("#virtual_good").hide();
			}
			show_hide_error();
		}


		function is_virtual_file_onclick()
		{
			if($("[name=is_virtual_file]:checked").val() == 1)
			{
				$("#virtual_good").show();
			}
			else 
			{
				$("#virtual_good").hide();
			}
			show_hide_error();
		}

		function show_hide_error()
		{
			if(cache_default_attribute)
				$("#error_edit_file").show();
			else
				$("#error_edit_file").hide();
		}

	</script>
	{/if}
	{* Virtual product end --------------------------------------------------- *}

	{* Shipping begin --------------------------------------------------- *}
	{if $product_menu == 9}
	<script>
	$(document).ready(function() {
		$("#addCarrier").on('click', function() {
			$('#availableCarriers option:selected').each( function() {
	                $('#selectedCarriers').append("<option value='"+$(this).val()+"'>"+$(this).text()+"</option>");
	            $(this).remove();
	        });
	        $('#selectedCarriers option').prop('selected', true);
		});

		$("#removeCarrier").on('click', function() {
			$('#selectedCarriers option:selected').each( function() {
	            $('#availableCarriers').append("<option value='"+$(this).val()+"'>"+$(this).text()+"</option>");
	            $(this).remove();
	        });
			$('#selectedCarriers option').prop('selected', true);
		});

		$("#btnSubmitShipping").on('click', function() {
			$("#selectedCarriers").find("option").each(function () {
				$(this).prop("selected", "selected");
			});
			$("#submitShipping").val(1);
			$("#product_form").submit();
		});

	});
	</script>
	{/if}
	{* Shipping end --------------------------------------------------- *}


	{* Attachment begin --------------------------------------------------- *}
	{if $product_menu == 10}
    <script type="text/javascript">
      var iso = "{$isoTinyMCE}";
      var pathCSS = '{$smarty.const._THEME_CSS_DIR_}';
      var ad = "{$ad}";
      hideOtherLanguage({$id_language});
    </script>

	<script type="text/javascript">
	  $('document').ready(function() {
	  tinySetup(
	  {
	  selector: ".rte" ,
	  toolbar1 : "code,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,|,blockquote,colorpicker,pasteword,|,bullist,numlist,|,outdent,indent,|,link,unlink,|,cleanup"
	  });

	  $("#addAttachment").on('click', function() {
	  $("#selectAttachment2 option:selected").each(function(){
	  var val = $('#arrayAttachments').val();
	  var tab = val.split(',');
	  for (var i=0; i < tab.length; i++)
						if (tab[i] == $(this).val())
							return false;
					$('#arrayAttachments').val(val+$(this).val()+',');
				});
				return !$("#selectAttachment2 option:selected").remove().appendTo("#selectAttachment1");
			});
			$("#removeAttachment").on('click', function() {
				$("#selectAttachment1 option:selected").each(function(){
					var val = $('#arrayAttachments').val();
					var tab = val.split(',');
					var tabs = '';
					for (var i=0; i < tab.length; i++)
						if (tab[i] != $(this).val())
						{
							tabs = tabs+','+tab[i];
							$('#arrayAttachments').val(tabs);
						}
				});
				return !$("#selectAttachment1 option:selected").remove().appendTo("#selectAttachment2");
			});
			$("#product").submit(function() {
				$("#selectAttachment1 option").each(function(i) {
					$(this).attr("selected", "selected");
				});
			});
		});
				
		function changeMyLanguage(field, fieldsString, id_language_new, iso_code)
		{
			changeLanguage(field, fieldsString, id_language_new, iso_code);
			$("img[id^='language_current_']").attr("src","{$base_dir}img/l/" + id_language_new + ".jpg");
		}

	</script>
	{/if}
	{* Attachment begin --------------------------------------------------- *}


	{if $product_menu == 11}
	{* ProductTags begin --------------------------------------------------- *}
	<script>
    $(function(){
        $("#product_tag_save").on("click", function(e){
            $('.succes_save_tag').addClass('hidden');
            e.preventDefault();
            array_tags = [];
            $('#product_tags .agile_tag').each(function(){
                name = $(this).attr('name');
                id_tag = name.replace('id_tag_', '');
                array_tags.push({
                    'id_tag':id_tag,
                    'check':$(this).is(':checked')
                });
            })
            var url = $('#product_tags #site_url').val() + 'modules/agileproducttags/ajax.php'
            changeTags(array_tags, url, $('#product_tags #id_product').val());
        });

        $('#product_tags .agile_tag').on('change', function(){
            $('.succes_save_tag').addClass('hidden');
        });

        function changeTags(array_tags,url,id_product) {
            $.ajax({
                type: "post",
                url: url,
                cache: false,
                async: true,
                dataType: "json",
                data: {
                    'array_tags' : array_tags,
                    'id_product' : id_product,
                    'pageType': 'saveProductTags',
                },
                success: function(response){
                    $('.succes_save_tag').removeClass('hidden');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown){
                    console.log("TECHNICAL ERROR: unable to load form.\n\nDetails:\nError thrown: " + XMLHttpRequest + "\n" + 'Text status: ' + textStatus);
                }
            });
        }
    });
	</script>
	{/if}
	{* ProductTags End --------------------------------------------------- *}
{/block}
