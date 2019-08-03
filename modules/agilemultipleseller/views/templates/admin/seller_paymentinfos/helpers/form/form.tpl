{*
*}
{extends file="helpers/form/form.tpl"}

{block name="field"}
	{if $input.type == 'textarea'}
		<div class="col-lg-9 ">
		<textarea name="{$input.name}" id="{$input.name}" rows="3" cols="45">{if isset($fields_value[$input.name])}{$fields_value[$input.name]|escape:'htmlall':'UTF-8'}{/if}</textarea>
		</div>
	{elseif $input.name == 'elinks'}
		<div class="col-lg-9 ">
			<span id="elinks"></span>
		</div>
    {elseif $input.name=="module_name"}
		<script type="text/javascript">
			var current_id_seller = {$current_id_seller}; 
			var is_seller = ({$is_seller} == 1);

			$(document).ready(function() {
				$("#module_name").change(function() {
					set_field_labels($("#module_name").val());
				});

				if(is_seller)$("#id_seller").val(current_id_seller);

				set_field_labels($("#module_name").val());
			});

			{$labels}

			function set_field_labels(module)
			{
				if(module =='')return;
				for(idx=1;idx<=8;idx++)
				{
					field = "info" + idx;
					var label = labels[module][field].label;
					if(labels[module][field].tooltip)label = label + '<span title="'+ labels[module][field].tooltip  +'">[?]</span>';
					$('[name=' + field + ']').parent().prev().html(label);
					if(labels[module][field].label != 'N/A')
					{
						$('[name=' + field + ']').parent().parent().show();
					}
					else
					{
						$('[name=' + field + ']').parent().parent().hide();
					}
					if(labels[module][field].is_readonly)$('[name=' + field + ']').attr('readonly', true);
					else $('[name=' + field + ']').attr('readonly', false);
				}
				if(labels[module]['elinks'] && labels[module]['elinks'].length > 0)
				{
					var html = '';
					for(var idx=0; idx < labels[module]['elinks'].length; idx++)
					{
						html = html + '<a href="' + labels[module]['elinks'][idx].url + '" title="' + labels[module]['elinks'][idx].tooltip + '">' + labels[module]['elinks'][idx].label + '</a>&nbsp;&nbsp;&nbsp;';
					}
					$("#elinks").html(html);
				}
			}		

		</script>
		{$smarty.block.parent}
    {else}
		{$smarty.block.parent}
	{/if}
{/block}
