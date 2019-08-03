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
<div class="panel-heading">{l s='Search Returns' mod='correos'}</div>
   <div class="panel-body" style="width:100%">
      <form class="form clearfix" id="correos-rma-form"  enctype="multipart/form-data" method="post">
        <table class="table order">
         <thead>
            <tr class="nodrag nodrop">
               <th class=" text-center fixed-width-xs">
                  <span class="title_box"> {l s='ID Order' mod='correos'} </span>
               </th>
               <th class=" text-center">
                  <span class="title_box"> {l s='Customer' mod='correos'} </span>
               </th>
               <th class=" text-center">
                  <span class="title_box"> {l s='Date' mod='correos'} </span>
               </th>
               <th class=" text-center">
                  <span class="title_box"> {l s='Shipping code' mod='correos'} </span>
               </th>
                <th class=" text-center">
                  <span class="title_box"> {l s='RMA Label' mod='correos'} </span>
               </th>
               <th class=" text-center">
                  <span class="title_box"> {l s='Content Declaration' mod='correos'} </span>
               </th>
               <th class=" text-center">
                  <span class="title_box"> {l s='Request Collection' mod='correos'} </span>
               </th>
            </tr>
         </thead>
         {if $returns}
         <tbody>
         {foreach from=$returns item=return}
            {assign var="rma_shipping_code_array" value=","|explode:$return.shipment_code}
            <tr>
               <td>{$return.id_order|escape:'htmlall':'UTF-8'}</td>
               <td>{$return.firstname|escape:'htmlall':'UTF-8'} {$return.lastname|escape:'htmlall':'UTF-8'}</td>
               <td>{dateFormat date=$return.date_response full=1} </td>
               <td>
                {foreach $rma_shipping_code_array as $index => $shipping_code}
                    {$shipping_code|escape:'htmlall':'UTF-8'}
                    <br>
                {/foreach}
               </td>
               <td>
                  
                   {foreach $rma_shipping_code_array as $index => $shipping_code}
                   <a href="#" style="text-decoration:underline" class="preventDefault"
                  onClick="window.open('{$correos_dir|escape:'htmlall':'UTF-8'}/get_label.php?order={$return.id_order|escape:'htmlall':'UTF-8'}&codenv={$shipping_code|escape:'htmlall':'UTF-8'}&correos_token={$correos_token|escape:'htmlall':'UTF-8'}','mywindow','width=500,height=500')">{l s='Download Label' mod='correos'}</a>
                   <br>
                   {/foreach}
 
               </td>
               <td class="text-center">
                  {foreach $rma_shipping_code_array as $index => $shipping_code}
                  {if file_exists('../modules/correos/pdftmp/customs_'|cat:$shipping_code|lower|cat:'.pdf') and '../modules/correos/pdftmp/customs_'|cat:$shipping_code|lower|cat:'.pdf'|@filesize > 0}
                      <a href="#" style="text-decoration:underline; padding-left: 10px; padding-right: 10px;" class="preventDefault" onClick="window.open('{$correos_dir|escape:'htmlall':'UTF-8'}/pdftmp/customs_{$shipping_code|lower|escape:'htmlall':'UTF-8'}.pdf','mywindow','width=500,height=500')">
                        {l s='Content Declaration' mod='correos'}
                      </a>
                      <br>
                  {/if}
                  {/foreach}
               </td>
               <td>
                    <a class="btn btn-default btn-rma-collection-request" 
                        data-idorder="{$return.id_order|escape:'htmlall':'UTF-8'}"
                        data-name="{$return.firstname|escape:'htmlall':'UTF-8'} {$return.lastname|escape:'htmlall':'UTF-8'}"
                        data-email="{$return.email|escape:'htmlall':'UTF-8'}"
                        data-address="{$return.address|escape:'htmlall':'UTF-8'}"
                        data-postcode="{$return.postcode|escape:'htmlall':'UTF-8'}"
                        data-city="{$return.city|escape:'htmlall':'UTF-8'}"
                        data-phone="{$return.phone|escape:'htmlall':'UTF-8'}"
                        data-dateresponse="{$return.date_response|escape:'htmlall':'UTF-8'}"
                        data-shipmentcode="{$return.shipment_code|escape:'htmlall':'UTF-8'}"
                        title="{l s='Request Collection' mod='correos'}" href="javascript:void(0);">
                    <i class="icon-truck"></i>
                      {l s='Request Collection' mod='correos'}
                  </a>
               </td>
               
            </tr>
         
         {/foreach}
         </tbody>
         {/if}
         </table>
         
         
         
   {if $returns}      
   <div class="row">
      <div class="col-lg-6">
			
			</div>
		<div class="col-lg-6">
		
		<div class="pagination">
			{l s='Show' mod='correos'}
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
				{$search_rma_pagination.rma_rows|escape:'htmlall':'UTF-8'}
				<i class="icon-caret-down"></i>
			</button>
			<ul class="dropdown-menu">
							<li>
					<a href="javascript:void(0);" class="rma-pagination-items-page" data-items="20" data-list-id="order">20</a>
				</li>
							<li>
					<a href="javascript:void(0);" class="rma-pagination-items-page" data-items="50" data-list-id="order">50</a>
				</li>
							<li>
					<a href="javascript:void(0);" class="rma-pagination-items-page" data-items="100" data-list-id="order">100</a>
				</li>
							<li>
					<a href="javascript:void(0);" class="rma-pagination-items-page" data-items="300" data-list-id="order">300</a>
				</li>
							<li>
					<a href="javascript:void(0);" class="rma-pagination-items-page" data-items="1000" data-list-id="order">1000</a>
				</li>
						</ul>
			/ {$search_rma_pagination.total_rows|escape:'htmlall':'UTF-8'} {l s='results' mod='correos'}
			<input id="rma_rows" name="rma_rows" value="{$search_rma_pagination.rma_rows|escape:'htmlall':'UTF-8'}" type="hidden">
		</div>
		
   
      <ul class="pagination pull-right">
						<li {if $search_rma_pagination.page <= 1}class="disabled"{/if}>
							<a href="javascript:void(0);" class="rma-pagination-link" data-page="1">
								<i class="icon-double-angle-left"></i>
							</a>
						</li>
						<li {if $search_rma_pagination.page <= 1}class="disabled"{/if}>
							<a href="javascript:void(0);" class="rma-pagination-link" data-page="{$search_rma_pagination.page|escape:'htmlall':'UTF-8' - 1}">
								<i class="icon-angle-left"></i>
							</a>
						</li>
						{assign p 0}
						{while $p++ < $search_rma_pagination.total_pages}
							{if $p < $search_rma_pagination.page-2}
								<li class="disabled">
									<a href="javascript:void(0);">&hellip;</a>
								</li>
								{assign p $search_rma_pagination.page-3}
							{else if $p > $search_rma_pagination.page+2}
								<li class="disabled">
									<a href="javascript:void(0);">&hellip;</a>
								</li>
								{assign p $search_rma_pagination.total_pages}
							{else}
								<li {if $p == $search_rma_pagination.page}class="active"{/if}>
									<a href="javascript:void(0);" class="rma-pagination-link" data-page="{$p|escape:'htmlall':'UTF-8'}">{$p|escape:'htmlall':'UTF-8'}</a>
								</li>
							{/if}
						{/while}
						<li {if $search_rma_pagination.page >= $search_rma_pagination.total_pages}class="disabled"{/if}>
							<a href="javascript:void(0);" class="rma-pagination-link" data-page="{$search_rma_pagination.page|escape:'htmlall':'UTF-8' + 1}">
								<i class="icon-angle-right"></i>
							</a>
						</li>
						<li {if $search_rma_pagination.page >= $search_rma_pagination.total_pages}class="disabled"{/if}>
							<a href="javascript:void(0);" class="rma-pagination-link" data-page="{$search_rma_pagination.total_pages|escape:'htmlall':'UTF-8'}">
								<i class="icon-double-angle-right"></i>
							</a>
						</li>
					</ul>
            
               
               
      <input id="rma-page" name="rma_page" type="hidden">
		<script type="text/javascript">
			
		</script>
	</div>
     
	</div>
        {else}
     <p class="text-center">{l s='No results found' mod='correos'}</p>
   {/if}  

       </form>
      
   </div>
<script type="text/javascript">
			$('.rma-pagination-items-page').on('click',function(e){
				e.preventDefault();
				$('#rma_rows').val($(this).data("items"));
                $("#correos-rma-form").submit();
			});
            $('.rma-pagination-link').on('click',function(e){
				e.preventDefault();

				if (!$(this).parent().hasClass('disabled')) {
					$('#rma-page').val($(this).data("page"));
                    $("#correos-rma-form").submit();
                }
                
			});
		</script>