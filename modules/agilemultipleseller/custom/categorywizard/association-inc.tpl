			<script language="javascript">
				var id_category_default =  {if isset($product->id_category_default) && $product->id_category_default>=2}{$product->id_category_default}{else}{$id_first_available_category}{/if};
				$(document).ready(function(){
					$("#id_category_default").val(id_category_default);
					update_agile_categories(id_category_default);
				});

				function oncategoryselected(ctrl)
				{
					var cid = $("#" + ctrl).val();
					$("#id_category_default").val(cid);
					update_agile_categories(cid);
				}
				
				function update_agile_categories(cid)
				{
					var url = "{$ajx_category_url}";
					$.post(url, { id_category: cid },
					function (data) {
						$("div#divAgileCategories").html(data);
					});
				}
			</script>
			<input type="hidden" name="id_category_default" id="id_category_default" value="{$id_category_default}">
			<div id="divAgileCategories">
			</div>
