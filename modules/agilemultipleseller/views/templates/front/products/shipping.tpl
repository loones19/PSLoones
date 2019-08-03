{*
 This source file is subject to the Software License Agreement that is bundled with this 
 package in the file license.txt, or you can get it here
 http://addons-modules.com/en/content/3-terms-and-conditions-of-use

 @copyright  2009-2013 Addons-Modules.com
*}


<div id="product-shipping" class="panel product-tab">
	<input type="hidden" name="submitted_tabs[]" value="Shipping" />
	<h3>{l s='Shipping' mod='agilemultipleseller'}</h3>

	{if isset($display_common_field) && $display_common_field}
		<div class="alert alert-info">{l s='Warning, if you change the value of fields with an orange bullet %s, the value will be changed for all other shops for this product' sprintf=[$bullet_common_field] mod='agilemultipleseller'}</div>
	{/if}

	{*
	<div class="form-group ">
		<label class="control-label agile-col-md-3 agile-col-lg-3 agile-col-xl-3" for="width">{$bullet_common_field} {l s='Width (package):' mod='agilemultipleseller'}</label>
		<div class="input-group agile-col-md-4 agile-col-lg-3 agile-col-xl-2">
			<span class="input-group-addon">{$ps_dimension_unit}</span>
			<input maxlength="14" style="width:90px;" id="width" name="width" type="text" value="{if $product->width>0}{$product->width}{/if}" onKeyUp="if (isArrowKey(event)) return ;this.value = this.value.replace(/,/g, '.');" />			
		</div>
	</div>
	*}
	{*
	<div class="form-group ">
		<label class="control-label agile-col-md-3 agile-col-lg-3 agile-col-xl-3" for="height">{$bullet_common_field} {l s='Height (package):' mod='agilemultipleseller'}</label>
		<div class="input-group agile-col-md-4 agile-col-lg-3 agile-col-xl-2">
			<span class="input-group-addon">{$ps_dimension_unit}</span>
			<input maxlength="14" style="width:90px;" id="height" name="height" type="text" value="{if $product->height>0}{$product->height}{/if}" onKeyUp="if (isArrowKey(event)) return ;this.value = this.value.replace(/,/g, '.');" />
		</div>
	</div>
	*}
	{*
	<div class="form-group ">
		<label class="control-label agile-col-md-3 agile-col-lg-3 agile-col-xl-3" for="depth">{$bullet_common_field} {l s='Depth (package):' mod='agilemultipleseller'}</label>
		<div class="input-group agile-col-md-4 agile-col-lg-3 agile-col-xl-2">
			<span class="input-group-addon">{$ps_dimension_unit}</span>
			<input maxlength="14" id="depth" style="width:90px;" name="depth" type="text" value="{if $product->depth>0}{$product->depth}{/if}" onKeyUp="if (isArrowKey(event)) return ;this.value = this.value.replace(/,/g, '.');" />
		</div>
	</div>
	*}
	{* LOONES *}
	
	<div class="form-group ">
		<label class="control-label agile-col-md-3 agile-col-lg-3 agile-col-xl-3" for="transporte">
			{$bullet_common_field} {l s='Package Description:' mod='agilemultipleseller'}
		</label>
		 <div class="input-group agile-col-md-5 agile-col-lg-4 agile-col-xl-3" > 
			<select id="id_trans_op1" name="id_trans_op1" onchange="transOp2()">
					<option value="0">{l s='Select One' mod='agilemultipleseller'}</option>
				{foreach $trans_op1 as $op1}
				
					<option value="{$op1['id_trans_op1']}" {if $product->id_trans_op1==$op1['id_trans_op1']}selected{/if} >
						{l s=$op1['name'] mod='agilemultipleseller'}
					</option>
				{/foreach}
			</select> 
		</div>
	</div>


	<div class="form-group " id="id-op2" {if $product->id_trans_op2 == 0}style="display:none"{/if}>
		<label class="control-label agile-col-md-3 agile-col-lg-3 agile-col-xl-3" for="transporte">
			{$bullet_common_field} {l s='Package format:' mod='agilemultipleseller'}
		</label>
		<div class="input-group agile-col-md-5 agile-col-lg-4 agile-col-xl-3" > 
			<select id="id_trans_op2" name="id_trans_op2" onchange="questions()" >
					
					{if $product->id_trans_op2 !=0}
						{foreach $trans_op2 as $op2}
							{if $op2['id_trans_op1']==$product->id_trans_op1}
								<option value="{$op2['id_trans_op2']}" {if $product->id_trans_op2==$op2['id_trans_op2']}selected{/if} >
									{l s=$op2['name'] mod='agilemultipleseller'}
								</option>
								
							{/if}}

						{/foreach}

					{/if}
			</select> 
		</div>
	</div>


	<div class="form-group " id="question1" {if $product->trans_op2_n1 == 0}style="display:none"{/if}>
		<label class="control-label agile-col-md-3 agile-col-lg-3 agile-col-xl-3" for="trans_op2_n1">
			{if $product->trans_op2_n1 != 0}
				{foreach $trans_op2 as $item}
					{if $product->id_trans_op2==$item['id_trans_op2'] && $product->id_trans_op1==$item['id_trans_op1']}
						{$item['question1']}
					{/if}
				{/foreach} 
			{/if}
		</label>
		<div class="input-group agile-col-md-4 agile-col-lg-3 agile-col-xl-2">
			<input type="text" id="trans_op2_n1" style="width:90px;" name="trans_op2_n1" value="{$product->trans_op2_n1|htmlentities}" />
		</div>
	</div>

	<div class="form-group " id="question2" {if $product->trans_op2_n2 == 0}style="display:none"{/if}>
		<label class="control-label agile-col-md-3 agile-col-lg-3 agile-col-xl-3" for="trans_op2_n2">
			{if $product->trans_op2_n2 != 0}
				{foreach $trans_op2 as $item}
					{if $product->id_trans_op2==$item['id_trans_op2'] && $product->id_trans_op1==$item['id_trans_op1']}
						{$item['question2']}
					{/if}
				{/foreach} 
			{/if}
		</label>
		<div class="input-group agile-col-md-4 agile-col-lg-3 agile-col-xl-2">
			<input type="text" id="trans_op2_n2" style="width:90px;" name="trans_op2_n2" value="{$product->trans_op2_n2|htmlentities}" />
		</div>
	</div>
	<div class="form-group " id="question3" {if $product->trans_op2_n3 == 0}style="display:none"{/if}>
		<label class="control-label agile-col-md-3 agile-col-lg-3 agile-col-xl-3" for="trans_op2_n3">
			{if $product->trans_op2_n3 != 0}
				{foreach $trans_op2 as $item}
					{if $product->id_trans_op2==$item['id_trans_op2'] && $product->id_trans_op1==$item['id_trans_op1']}
						{$item['question3']}
					{/if}
				{/foreach} 
			{/if}
		</label>
		<div class="input-group agile-col-md-4 agile-col-lg-3 agile-col-xl-2">
			<input type="text" id="trans_op2_n3" style="width:90px;" name="trans_op2_n3" value="{$product->trans_op2_n3|htmlentities}" />
		</div>
	</div>

	{*$product|@print_r*}
	{* LOONES FIN *}

	{*
	<div class="form-group ">
		<label class="control-label agile-col-md-3 agile-col-lg-3 agile-col-xl-3" for="weight">{$bullet_common_field} {l s='Weight (package):' mod='agilemultipleseller'}</label>
		<div class="input-group agile-col-md-4 agile-col-lg-3 agile-col-xl-2">
			<span class="input-group-addon">{$ps_weight_unit}</span>
			<input maxlength="14" id="weight" style="width:90px;" name="weight" type="text" value="{if $product->weight>0}{$product->weight}{/if}" onKeyUp="if (isArrowKey(event)) return ;this.value = this.value.replace(/,/g, '.');" />
		</div>
	</div>
	*}
	{*
	<div class="form-group ">
		<label class="control-label agile-col-md-3 agile-col-lg-3 agile-col-xl-3" for="additional_shipping_cost">
			<span class="label-tooltip" data-toggle="tooltip"
				title="{l s='A carrier tax will be applied.' mod='agilemultipleseller'}">
				{l s='Additional shipping cost (per quantity):' mod='agilemultipleseller'}
			</span>
			
		</label>
		<div class="input-group agile-col-md-4 agile-col-lg-3 agile-col-xl-2">
			<span class="input-group-addon">{$currency->prefix}{$currency->suffix} {if $country_display_tax_label}({l s='tax excl.' mod='agilemultipleseller'}){/if}</span>
			<input type="text" id="additional_shipping_cost" style="width:90px;" name="additional_shipping_cost" onchange="this.value = this.value.replace(/,/g, '.');" value="{$product->additional_shipping_cost|htmlentities}" />
		</div>
	</div>

	<div class="form-group ">
		<label class="control-label agile-col-md-3 agile-col-lg-3 agile-col-xl-3" for="availableCarriers">{l s='Carriers:'  mod='agilemultipleseller'}</label>
		<div class="agile-col-md-9 agile-col-lg-9 agile-col-xl-9">
			<div class="form-control-static row">
				<div class="agile-agile-col-md-6 agile-col-lg-6 agile-col-xl-6 agile-padding-left4">
					<p>{l s='Available carriers'  mod='agilemultipleseller'}</p>
					<select multiple id="availableCarriers" name="availableCarriers">
						{foreach $carrier_list as $carrier}
							{if !isset($carrier.selected) || !$carrier.selected}
								<option value="{$carrier.id_reference}">{$carrier.name}</option>
							{/if}
						{/foreach}
					</select>
					<a href="#" id="addCarrier" class="agile-btn agile-btn-default btn-block">{l s='Add'  mod='agilemultipleseller'}&nbsp;<i class="icon-arrow-right"></i></a>
				</div>
				<div class="agile-agile-col-md-6 agile-col-lg-6 agile-col-xl-6">
					<p>{l s='Selected carriers' mod='agilemultipleseller'}</p>
					<select multiple id="selectedCarriers" name="carriers[]">
						{foreach $carrier_list as $carrier}
							{if isset($carrier.selected) && $carrier.selected}
								<option value="{$carrier.id_reference}" selected="selected">{$carrier.name}</option>
							{/if}
						{/foreach}
					</select>
					<a href="#" id="removeCarrier" class="agile-btn agile-btn-default btn-block"><i class="icon-arrow-left"></i>&nbsp;{l s='Remove' mod='agilemultipleseller'}</a>
				</div>
			</div>
			<div class="row">
				&nbsp;* {l s='If no carrier is selected then all the carriers will be available for customers orders.' mod='agilemultipleseller'}
				{if $is_seller_shipping_installed} 
					<br>
					** {l s='If you choose to restrict carriers for the product, the Default Carrier must be included.' mod='agilemultipleseller'}
				{/if}
			</div>
		</div>
	</div>
	*}


	<div class="form-group agile-align-center">
		<input type="hidden" class="agile-btn agile-btn-default" name="submitShipping" id="submitShipping" value="0">
		<button type="button" class="agile-btn agile-btn-default" name="btnSubmitShipping" id="btnSubmitShipping" value="{l s='Save' mod='agilemultipleseller'}">
		<i class="icon-save"></i>&nbsp;<span>{l s='Save' mod='agilemultipleseller'}</span></button >
   </div>
</div>
{* The selected carrier id in V1.5 is carriers_restriction *}

	<script>
		var op1={$trans_op1|@json_encode nofilter};
		var op2={$trans_op2|@json_encode nofilter};
		//console.log(op2);
		function transOp2() {
			var selectedOp1 = $('#id_trans_op1').val();
			$('#id_trans_op2').find('option').remove()

			$('#question1').hide();
			$('#question2').hide();
			$('#question3').hide();
			//console.log(selectedOp1);
			var contador=0;
			var ultimo=[];
			op2.forEach(function(i) {
				if (i.id_trans_op1==selectedOp1) {
					$('#id_trans_op2').append('<option value="'+i.id_trans_op2+'">'+i.name+'</option> ')
					contador++;
					ultimo=i;
				}
			});
			//console.log(ultimo);
			if (contador>1) {
				$('#id_trans_op2').prepend('<option value="0" selected>select one</option> ')
				$('#id-op2').show();
			} else if (contador==1) {
				//hay que mostrar la pregunta ya
				$('#id-op2').show();
				$('#question1 label').html(ultimo.question1)
				$('#trans_op2_n1').val("")
				$('#question1').show();
				if (ultimo.question2 != null) {
					$('#trans_op2_n2').val("")
					$('#question2 label').html(ultimo.question2)
					$('#question2').show();
				}
				if (ultimo.question3 != null) {
					$('#question3 label').html(ultimo.question3)

					$('#trans_op2_n3').val("")

					$('#question3').show();
				}
			} else {
					$('#id-op2').hide();
			}
			

		}
		function questions() {
			var selectedOp2 = $('#id_trans_op2').val();
			var selectedOp1 = $('#id_trans_op1').val();
			$('#question1').hide();
			$('#question2').hide();
			$('#question3').hide()
			var ultimo=0;
			op2.forEach(function(i) {
				if (i.id_trans_op1==selectedOp1 &&  i.id_trans_op2==selectedOp2) {
					ultimo=i;
				}
			});
			if  (ultimo) {
				$('#question1 label').html(ultimo.question1)
				$('#trans_op2_n1').val("")
				$('#question1').show();
				if (ultimo.question2 != null) {
					$('#question2 label').html(ultimo.question2)
					$('#trans_op2_n2').val("")
					$('#question2').show();
				}
				if (ultimo.question3 != null) {
					$('#question3 label').html(ultimo.question3)
					$('#trans_op2_n3').val("")
					$('#question3').show();
				}
			}



		}


	</script>


