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
<div class="panel-heading">{l s='Search shipping' mod='correos'}</div>
   <div class="panel-body" style="width:100%">
      <form class="form clearfix" id="correos_orders" enctype="multipart/form-data" method="post">
        <table class="table order">
         <thead>
            <tr class="nodrag nodrop">
               <th class="center fixed-width-xs"></th>
               <th class=" text-center fixed-width-xs">
                  <span class="title_box"> ID </span>
               </th>
               <th class=" text-center">
                  <span class="title_box"> {l s='Customer' mod='correos'} </span>
               </th>
               <th class=" text-center">
                  <span class="title_box"> {l s='Date' mod='correos'} </span>
               </th>
               <th class=" text-center">
                  <span class="title_box"> {l s='Expedition code' mod='correos'} </span>
               </th>
                <th class=" text-center">
                  <span class="title_box"> {l s='Parcel Num' mod='correos'} </span>
               </th>
               <th class=" text-center">
                  <span class="title_box"> {l s='Collection requested' mod='correos'} </span>
               </th>
               <th class=" text-center">
                  <span class="title_box"> {l s='Exported' mod='correos'} </span>
               </th>
               <th class=" text-center">
                  <span class="title_box"> {l s='Printed' mod='correos'} </span>
               </th>
               <th class=" text-center">
                  <span class="title_box"> {l s='Manifest' mod='correos'} </span>
               </th>
               <th class="center fixed-width-xs"></th>
            </tr>
            <tr class="nodrag nodrop filter row_hover">
               <th class="text-center"> -- </th>
               <th class="text-center">
                  <input class="filter" type="text" value="{if isset($smarty.post.orderFilter_id_order) && isset($smarty.post['form-search_shipping_filter'])}{$smarty.post.orderFilter_id_order|escape:'htmlall':'UTF-8'}{/if}" name="orderFilter_id_order">
               </th>
               <th>
                  <input class="filter" type="text" value="{if isset($smarty.post.orderFilter_customer) && isset($smarty.post['form-search_shipping_filter'])}{$smarty.post.orderFilter_customer|escape:'htmlall':'UTF-8'}{/if}" name="orderFilter_customer">
               </th>
               <th class="text-right">
                  <div class="date_range row">
                     <div class="input-group fixed-width-md">
                        <input id="local_orderFilter_dateFrom" class="filter datepicker date-input form-control" type="text" placeholder="{l s='Date' mod='correos'}" 
                        value="{if isset($smarty.post.local_orderFilter_dateFrom) && isset($smarty.post['form-search_shipping_filter'])}{$smarty.post.local_orderFilter_dateFrom|escape:'htmlall':'UTF-8'}{/if}" name="local_orderFilter_dateFrom">
                        <input type="hidden" id="orderFilter_dateFrom" name="orderFilter_dateFrom" value="{if isset($smarty.post.orderFilter_dateFrom) && isset($smarty.post['form-search_shipping_filter'])}{$smarty.post.orderFilter_dateFrom|escape:'htmlall':'UTF-8'}{/if}">
                        
                        <span class="input-group-addon">
                           <i class="icon-calendar"></i>
                        </span>
                     </div>
                     <div class="input-group fixed-width-md">
                  
                        <input id="local_orderFilter_dateTo" class="filter datepicker date-input form-control" type="text" placeholder="Hasta" 
                           value="{if isset($smarty.post.local_orderFilter_dateTo) && isset($smarty.post['form-search_shipping_filter'])}{$smarty.post.local_orderFilter_dateTo|escape:'htmlall':'UTF-8'}{/if}"                         
                           name="local_orderFilter_dateTo">
                        <input type="hidden" id="orderFilter_dateTo" value="{if isset($smarty.post.orderFilter_dateTo) && isset($smarty.post['form-search_shipping_filter'])}{$smarty.post.orderFilter_dateTo|escape:'htmlall':'UTF-8'}{/if}" name="orderFilter_dateTo" value="">
                        <span class="input-group-addon">
                           <i class="icon-calendar"></i>
                        </span>
                     </div>
                  </div>
               </th>
               <th class="text-center"> -- </th>
               <th class="text-center">
                  <select class="filter fixed-width-sm" name="orderFilter_parcel_number">
                     <option value="">-</option>
                     {for $i=1 to 10}
                     <option {if isset($smarty.post.orderFilter_parcel_number) && isset($smarty.post['form-search_shipping_filter']) && $smarty.post.orderFilter_parcel_number == $i } selected="true" {/if} value="{$i|intval}">{$i|intval}</option>
                     {/for}
                  </select>
               </th>
               <th class="text-center">
                  <select class="filter fixed-width-sm" name="orderFilter_collected">
                     <option value="">-</option>
                     <option {if isset($smarty.post.orderFilter_collected) && isset($smarty.post['form-search_shipping_filter']) && $smarty.post.orderFilter_collected == '1' } selected="true" {/if} value="1">{l s='Yes' mod='correos'}</option>
                     <option {if isset($smarty.post.orderFilter_collected) && isset($smarty.post['form-search_shipping_filter']) && $smarty.post.orderFilter_collected == '0' } selected="true" {/if} value="0">{l s='No' mod='correos'}</option>
                  </select>
               </th>
               <th class="text-center">
                  <select class="filter fixed-width-sm" name="orderFilter_exported">
                     <option value="">-</option>
                     <option {if isset($smarty.post.orderFilter_exported) && isset($smarty.post['form-search_shipping_filter']) && $smarty.post.orderFilter_exported == '1' } selected="true" {/if} value="1">{l s='Yes' mod='correos'}</option>
                     <option {if isset($smarty.post.orderFilter_exported) && isset($smarty.post['form-search_shipping_filter']) && $smarty.post.orderFilter_exported == '0' } selected="true" {/if} value="0">{l s='No' mod='correos'}</option>
                  </select>
               </th>
               <th class="text-center">
                  <select class="filter fixed-width-sm" name="orderFilter_printed">
                     <option value="">-</option>
                     <option {if isset($smarty.post.orderFilter_printed) && isset($smarty.post['form-search_shipping_filter']) && $smarty.post.orderFilter_printed == '1' } selected="true" {/if} value="1">{l s='Yes' mod='correos'}</option>
                     <option {if isset($smarty.post.orderFilter_printed) && isset($smarty.post['form-search_shipping_filter']) && $smarty.post.orderFilter_printed == '0' } selected="true" {/if} value="0">{l s='No' mod='correos'}</option>
                  </select>
               </th>
               <th class="text-center">
                  <select class="filter fixed-width-sm" name="orderFilter_manifest">
                     <option value="">-</option>
                     <option {if isset($smarty.post.orderFilter_manifest) && isset($smarty.post['form-search_shipping_filter']) && $smarty.post.orderFilter_manifest == '1' } selected="true" {/if} value="1">{l s='Yes' mod='correos'}</option>
                     <option {if isset($smarty.post.orderFilter_manifest) && isset($smarty.post['form-search_shipping_filter']) && $smarty.post.orderFilter_manifest == '0' } selected="true" {/if} value="0">{l s='No' mod='correos'}</option>
                  </select>
               </th>
               <th class="actions">
                  <span class="pull-right">
                     <button id="submitFilterButtonorder" class="btn btn-default" data-list-id="order" name="form-search_shipping_filter" type="submit">
                     <i class="icon-search"></i>
                     {l s='Search' mod='correos'}
                     </button>
                     {if isset($smarty.post['form-search_shipping_filter'])}
                     <button class="btn btn-warning" name="form-search_shipping_reset" type="submit">
						<i class="icon-eraser"></i>
                        {l s='Reset' mod='correos'}
					</button>
                    {/if}
         
                  </span>
               </th>
            </tr>
         </thead>
         {if $orders}
         <tbody>
         {foreach from=$orders item=order}
            <tr>
               <td class="row-selector">
                <input type="checkbox" class="id_order" name="id_order[{$order.id_order|escape:'htmlall':'UTF-8'}]" value="{$order.shipment_code|escape:'htmlall':'UTF-8'}" 
                        data-orderid="{$order.id_order|escape:'htmlall':'UTF-8'}" 
                        data-reference="{$order.reference|escape:'htmlall':'UTF-8'}" 
                        data-expedition="{$order.code_expedition|escape:'htmlall':'UTF-8'}"
                        data-orderdate="{dateFormat date=$order.date_add full=1}"
                        data-ordercustomer="{$order.firstname|escape:'htmlall':'UTF-8'} {$order.lastname|escape:'htmlall':'UTF-8'}">
               </td>
               <td>{$order.id_order|escape:'htmlall':'UTF-8'}</td>
               <td>{$order.firstname|escape:'htmlall':'UTF-8'} {$order.lastname|escape:'htmlall':'UTF-8'}</td>
               <td>{dateFormat date=$order.date_add full=1} </td>
               <td>
                  {$order.code_expedition|escape:'htmlall':'UTF-8'} 
               </td>
               <td class="text-center">
                  {','|explode:$order.shipment_code|count}  
               </td>
               <td>
                {dateFormat date=$order.collection_date full=1}
               </td>
               <td>
                    {dateFormat date=$order.exported full=1}
               </td>
               <td>
                   {dateFormat date=$order.label_printed full=1}
               </td>
               <td>
                  {dateFormat date=$order.manifest full=1}
               </td>
               <td>
                  
               </td>
            </tr>
         
         {/foreach}
         </tbody>
         {/if}
         </table>
         
         
         
   {if $orders}      
   <div class="row">
      <div class="col-lg-6">
			<div class="btn-group bulk-actions dropup">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
               {l s='Selected Option' mod='correos'} <span class="caret"></span>
            </button>
			<ul class="dropdown-menu">
				<li>
					<a onclick="javascript:$('#correos_orders input:checkbox').attr ( 'checked' , true );return false;" href="#">
						<i class="icon-check-sign"></i>&nbsp;{l s='Check all' mod='correos'}
					</a>
				</li>
				<li>
					<a onclick="javascript:$('#correos_orders input:checkbox').attr ( 'checked' , false );return false;" href="#">
						<i class="icon-check-empty"></i>&nbsp;{l s='Uncheck all' mod='correos'}
					</a>
				</li>
				<li class="divider"></li>
				<li>
					<a href="javascript:void(0);" onclick="$('#option_order').val('generate_label_a4');$('#form-search_shipping_action').trigger( 'click' )">
					{l s='Generate labels - A4' mod='correos'}
				</a>
            <li>
					<a href="javascript:void(0);" onclick="$('#option_order').val('generate_label_printer');$('#form-search_shipping_action').trigger( 'click' )">
					{l s='Generate labels - Label printer ' mod='correos'}
				</a>
            <li>
					<a href="javascript:void(0);" onclick="$('#option_order').val('generate_manifest');$('#form-search_shipping_action').trigger( 'click' )">
					{l s='Generate Manifest' mod='correos'}
				</a>
            <li>
					<a href="javascript:void(0);" onclick="$('#option_order').val('export');$('#form-search_shipping_action').trigger( 'click' )">
					{l s='Export' mod='correos'}
				</a>
            <li>
					<a href="javascript:void(0);" id="request_collection">
					{l s='Add to "Request Collection" form' mod='correos'}
				</a>
				</li>
			</ul>
		</div>
			</div>
		<div class="col-lg-6">
		
		<div class="pagination">
			{l s='Show' mod='correos'}
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
				{$search_shipping_pagination.order_rows|escape:'htmlall':'UTF-8'}
				<i class="icon-caret-down"></i>
			</button>
			<ul class="dropdown-menu">
							<li>
					<a href="javascript:void(0);" class="orders-pagination-items-page" data-items="20" data-list-id="order">20</a>
				</li>
							<li>
					<a href="javascript:void(0);" class="orders-pagination-items-page" data-items="50" data-list-id="order">50</a>
				</li>
							<li>
					<a href="javascript:void(0);" class="orders-pagination-items-page" data-items="100" data-list-id="order">100</a>
				</li>
							<li>
					<a href="javascript:void(0);" class="orders-pagination-items-page" data-items="300" data-list-id="order">300</a>
				</li>
							<li>
					<a href="javascript:void(0);" class="orders-pagination-items-page" data-items="1000" data-list-id="order">1000</a>
				</li>
						</ul>
			/ {$search_shipping_pagination.total_rows|escape:'htmlall':'UTF-8'} {l s='results' mod='correos'}
			<input id="order_rows" name="order_rows" value="{$search_shipping_pagination.order_rows|escape:'htmlall':'UTF-8'}" type="hidden">
		</div>
      <ul class="pagination pull-right">
						<li {if $search_shipping_pagination.page <= 1}class="disabled"{/if}>
							<a href="javascript:void(0);" class="orders-pagination-link" data-page="1">
								<i class="icon-double-angle-left"></i>
							</a>
						</li>
						<li {if $search_shipping_pagination.page <= 1}class="disabled"{/if}>
							<a href="javascript:void(0);" class="orders-pagination-link" data-page="{$search_shipping_pagination.page|escape:'htmlall':'UTF-8' - 1}">
								<i class="icon-angle-left"></i>
							</a>
						</li>
						{assign p 0}
						{while $p++ < $search_shipping_pagination.total_pages}
							{if $p < $search_shipping_pagination.page-2}
								<li class="disabled">
									<a href="javascript:void(0);">&hellip;</a>
								</li>
								{assign p $search_shipping_pagination.page-3}
							{else if $p > $search_shipping_pagination.page+2}
								<li class="disabled">
									<a href="javascript:void(0);">&hellip;</a>
								</li>
								{assign p $search_shipping_pagination.total_pages}
							{else}
								<li {if $p == $search_shipping_pagination.page}class="active"{/if}>
									<a href="javascript:void(0);" class="orders-pagination-link" data-page="{$p|escape:'htmlall':'UTF-8'}">{$p|escape:'htmlall':'UTF-8'}</a>
								</li>
							{/if}
						{/while}
						<li {if $search_shipping_pagination.page >= $search_shipping_pagination.total_pages}class="disabled"{/if}>
							<a href="javascript:void(0);" class="orders-pagination-link" data-page="{$search_shipping_pagination.page|escape:'htmlall':'UTF-8' + 1}">
								<i class="icon-angle-right"></i>
							</a>
						</li>
						<li {if $search_shipping_pagination.page >= $search_shipping_pagination.total_pages}class="disabled"{/if}>
							<a href="javascript:void(0);" class="orders-pagination-link" data-page="{$search_shipping_pagination.total_pages|escape:'htmlall':'UTF-8'}">
								<i class="icon-double-angle-right"></i>
							</a>
						</li>
					</ul>
            
               
               
      <input id="order-page" name="order_page" type="hidden">
		
	</div>
     
	</div>
      <input type="hidden" name="option_order" id="option_order"/>
      <button style="display:none" id="form-search_shipping_action" name="form-search_shipping_action" type="submit"></button>
     
      <div class="row col-lg-3">
      <p class="pull-left" style="margin: 5px 5px 0 0 ">{l s='Choose print position (only A4 Format)' mod='correos'}</p>
      <select name="print_position" class="fixed-width-xs">
         <option>1</option>
         <option>2</option>
         <option>3</option>
         <option>4</option>
      </select>
      <br style="clear:left"> 
       <img  src="{$img_dir|escape:'htmlall':'UTF-8'}admin/print_position.gif"/>
      </div>
      {else}
     <p class="text-center">{l s='No results found' mod='correos'}</p>
   {/if} 
      
      
      

  
       </form>
      
   </div>
<script type="text/javascript">
    $('.orders-pagination-link').on('click',function(e){
        e.preventDefault();

		if (!$(this).parent().hasClass('disabled')) {
            $('#order-page').val($(this).data("page"));
            $('#correos_orders').submit();
        }
    });
    $('.orders-pagination-items-page').on('click',function(e){
		e.preventDefault();
		$('#order_rows').val($(this).data("items"));
        $('#correos_orders').submit(); 
	});
</script>
   
   

  
 