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

<div class="panel-heading">{l s='Query collections' mod='correos'}</div>
   <div class="panel-body">
   <form class="form clearfix" id="correos_req_orders" enctype="multipart/form-data" method="post">
        <table class="table order">
         <thead>
            <tr class="nodrag nodrop">
                <th class="center fixed-width-xs"></th>
               <th class="text-center">
                  <span class="title_box"> {l s='Confirmation code' mod='correos'} </span>
               </th>
               <th class="text-center">
                  <span class="title_box"> {l s='Reference code' mod='correos'} </span>
               </th>
               <th class="text-center fixed-width-xl">
                  <span class="title_box"> {l s='Date Requested' mod='correos'} </span>
               </th>
               <th class="text-center fixed-width-xl">
                  <span class="title_box"> {l s='Collection Date' mod='correos'} </span>
               </th>
               <th class="text-center fixed-width-xl">
                  <span class="title_box"> {l s='State' mod='correos'} </span>
               </th>
               <th class="center fixed-width-xl"></th>
            </tr>
            <tr class="nodrag nodrop filter row_hover">
                <th></th>
               <th></th>
               <th></th>
               <th class="text-right">
                  <div class="date_range row">
                      <div class="col-md-12">
                       <div class="input-group">
                          <input id="local_collectionFilter_dateFrom" class="filter datepicker date-input form-control" type="text" placeholder="{l s='From' mod='correos'}" 
                          autocomplete="off"
                          value="{if isset($smarty.post.local_collectionFilter_dateFrom) && isset($smarty.post['form-search_collection_filter'])} {$smarty.post.local_collectionFilter_dateFrom|escape:'htmlall':'UTF-8'}{/if}" name="local_collectionFilter_dateFrom">
                          <input type="hidden" id="collectionFilter_dateFrom" name="collectionFilter_dateFrom" value="{if isset($smarty.post.collectionFilter_dateFrom) && isset($smarty.post['form-search_collection_filter'])}{$smarty.post.collectionFilter_dateFrom|escape:'htmlall':'UTF-8'}{/if}">
                          
                          <span class="input-group-addon">
                             <i class="icon-calendar"></i>
                          </span>
                       </div>
                     </div>
                     <div class="col-md-12">
                     <div class="input-group">
                  
                        <input id="local_collectionFilter_dateTo" class="filter datepicker date-input form-control" type="text" placeholder="{l s='To' mod='correos'}"
                           autocomplete="off"
                           value="{if isset($smarty.post.local_collectionFilter_dateTo) && isset($smarty.post['form-search_collection_filter'])}{$smarty.post.local_collectionFilter_dateTo|escape:'htmlall':'UTF-8'}{/if}"                         
                           name="local_collectionFilter_dateTo">
                        <input type="hidden" id="collectionFilter_dateTo" value="{if isset($smarty.post.collectionFilter_dateTo) && isset($smarty.post['form-search_collection_filter'])}{$smarty.post.collectionFilter_dateTo|escape:'htmlall':'UTF-8'}{/if}" name="collectionFilter_dateTo" value="">
                        <span class="input-group-addon">
                           <i class="icon-calendar"></i>
                        </span>
                     </div>
                     </div>
                  </div>
               </th>
               <th class="text-right">
               
                  <div class="date_range row">
                      <div class="col-md-12">
                       <div class="input-group">
                          <input id="local_collectionDateFilter_dateFrom" class="filter datepicker date-input form-control" type="text" placeholder="{l s='From' mod='correos'}" 
                          autocomplete="off"
                          value="{if isset($smarty.post.local_collectionDateFilter_dateFrom) && isset($smarty.post['form-search_collection_filter'])} {$smarty.post.local_collectionDateFilter_dateFrom|escape:'htmlall':'UTF-8'}{/if}" name="local_collectionDateFilter_dateFrom">
                          <input type="hidden" id="collectionDateFilter_dateFrom" name="collectionDateFilter_dateFrom" 
                          value="{if isset($smarty.post.collectionDateFilter_dateFrom) && isset($smarty.post['form-search_collection_filter'])}{$smarty.post.collectionDateFilter_dateFrom|escape:'htmlall':'UTF-8'}{/if}">
                          
                          <span class="input-group-addon">
                             <i class="icon-calendar"></i>
                          </span>
                       </div>
                     </div>
                     <div class="col-md-12">
                     <div class="input-group">
                  
                        <input id="local_collectionDateFilter_dateTo" class="filter datepicker date-input form-control" type="text" placeholder="{l s='To' mod='correos'}" 
                            autocomplete="off"
                           value="{if isset($smarty.post.local_collectionDateFilter_dateTo) && isset($smarty.post['form-search_collection_filter'])}{$smarty.post.local_collectionDateFilter_dateTo|escape:'htmlall':'UTF-8'}{/if}"                         
                           name="local_collectionDateFilter_dateTo">
                        <input type="hidden" id="collectionDateFilter_dateTo" value="{if isset($smarty.post.collectionDateFilter_dateTo) && isset($smarty.post['form-search_collection_filter'])}{$smarty.post.collectionDateFilter_dateTo|escape:'htmlall':'UTF-8'}{/if}" name="collectionDateFilter_dateTo" value="">
                        <span class="input-group-addon">
                           <i class="icon-calendar"></i>
                        </span>
                     </div>
                     </div>
                  </div>
               </th>
               <th class="text-center">
                  <select class="filter fixed-width-sm" name="collectionFilter_status">
                     <option value="">-</option>
                     <option {if isset($smarty.post.collectionFilter_status) && isset($smarty.post['form-search_collection_filter']) && $smarty.post.collectionFilter_status == 'Solicitada' } selected="true" {/if} value="Solicitada">Solicitada</option>
                     <option {if isset($smarty.post.collectionFilter_status) && isset($smarty.post['form-search_collection_filter']) && $smarty.post.collectionFilter_status == 'Anulada' } selected="true" {/if} value="Anulada">Anulada</option>
                  </select>
               </th>
               <th class="actions">
                  <span class="pull-right">
                     <button id="submitFilterButtoncollection" class="btn btn-default" name="form-search_collection_filter" type="submit">
                     <i class="icon-search"></i>
                     {l s='Search' mod='correos'}
                     </button>
                     {if isset($smarty.post['form-search_collection_filter'])}
                     <button class="btn btn-warning" name="form-search_collection_reset" type="submit">
						<i class="icon-eraser"></i>
                        {l s='Reset' mod='correos'}
					</button>
                    {/if}
         
                  </span>
               </th>
            </tr>
         </thead>
         <tbody>
         {foreach from=$collections item=collection}
         <tr>
            <td class="row-selector">
                <input type="radio" class="collection-id" name="collection-id" id="collection-code-{$collection.id|intval}" 
                       value="{$collection.id|intval}" data-collectioncode="{$collection.confirmation_code|escape:'htmlall':'UTF-8'}">
            </td>
            <td class="text-center"><label for="collection-code-{$collection.id|intval}">{$collection.confirmation_code|escape:'htmlall':'UTF-8'}</label></td>
            <td class="text-center">{$collection.reference_code|escape:'htmlall':'UTF-8'}</td>
            <td class="text-center">{dateFormat date=$collection.date_requested full=1}</td>
            <td class="text-center">{dateFormat date=$collection.collection_date}</td>
            <td class="text-center">
                {$collection.status|escape:'htmlall':'UTF-8'}
            </td>
         </tr>
         {/foreach}
         </tbody>
         </table>
         {if $collections}
        <div class="row">
          <div class="col-md-6">
            <div class="btn-group bulk-actions dropup">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                   {l s='Selected Option' mod='correos'} <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a href="javascript:void(0);" class="view-collection-details" data-target="more-info">
                        {l s='More info' mod='correos'}
                        </a>
                    <li>
                        <a href="javascript:void(0);" id="btn-collection-repeat">
                        {l s='Repeat' mod='correos'}
                        </a>
                    <li>
                        <a href="javascript:void(0);" class="view-collection-details" data-target="cancel">
                        {l s='Cancel Collection' mod='correos'}
                        </a>
                    <li>
                        <a href="javascript:void(0);" class="view-collection-details" data-target="export">
                        {l s='Export' mod='correos'}
                        </a>
                    </li>
                </ul>
            </div>
          </div>
          <div class="col-md-6">
          <div class="pagination">
			{l s='Show' mod='correos'}
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
				{$collection_pagination.collection_rows|escape:'htmlall':'UTF-8'}
				<i class="icon-caret-down"></i>
			</button>
			<ul class="dropdown-menu">
							<li>
					<a href="javascript:void(0);" class="pagination-collection-items-page" data-items="20">20</a>
				</li>
							<li>
					<a href="javascript:void(0);" class="pagination-collection-items-page" data-items="50">50</a>
				</li>
							<li>
					<a href="javascript:void(0);" class="pagination-collection-items-page" data-items="100">100</a>
				</li>
							<li>
					<a href="javascript:void(0);" class="pagination-collection-items-page" data-items="300">300</a>
				</li>
							<li>
					<a href="javascript:void(0);" class="pagination-collection-items-page" data-items="1000">1000</a>
				</li>
						</ul>
			/ {$collection_pagination.total_rows|escape:'htmlall':'UTF-8'} {l s='results' mod='correos'}
			<input id="collection_rows" name="collection_rows" value="{$collection_pagination.collection_rows|escape:'htmlall':'UTF-8'}" type="hidden">
		</div>
		<script type="text/javascript">
			$('.pagination-collection-items-page').on('click',function(e){
				e.preventDefault();
				$('#collection_rows').val($(this).data("items")).closest("form").submit();
			});

		</script>
   
      <ul class="pagination pull-right">
						<li {if $collection_pagination.page <= 1}class="disabled"{/if}>
							<a href="javascript:void(0);" class="collection-pagination-link" data-page="1">
								<i class="icon-double-angle-left"></i>
							</a>
						</li>
						<li {if $collection_pagination.page <= 1}class="disabled"{/if}>
							<a href="javascript:void(0);" class="collection-pagination-link" data-page="{$collection_pagination.page|escape:'htmlall':'UTF-8' - 1}">
								<i class="icon-angle-left"></i>
							</a>
						</li>
						{assign p 0}
						{while $p++ < $collection_pagination.total_pages}
							{if $p < $collection_pagination.page-2}
								<li class="disabled">
									<a href="javascript:void(0);">&hellip;</a>
								</li>
								{assign p $collection_pagination.page-3}
							{else if $p > $collection_pagination.page+2}
								<li class="disabled">
									<a href="javascript:void(0);">&hellip;</a>
								</li>
								{assign p $collection_pagination.total_pages}
							{else}
								<li {if $p == $collection_pagination.page}class="active"{/if}>
									<a href="javascript:void(0);" class="collection-pagination-link" data-page="{$p|escape:'htmlall':'UTF-8'}">{$p|escape:'htmlall':'UTF-8'}</a>
								</li>
							{/if}
						{/while}
						<li {if $collection_pagination.page >= $collection_pagination.total_pages}class="disabled"{/if}>
							<a href="javascript:void(0);" class="collection-pagination-link" data-page="{$collection_pagination.page|escape:'htmlall':'UTF-8' + 1}">
								<i class="icon-angle-right"></i>
							</a>
						</li>
						<li {if $collection_pagination.page >= $collection_pagination.total_pages}class="disabled"{/if}>
							<a href="javascript:void(0);" class="collection-pagination-link" data-page="{$collection_pagination.total_pages|escape:'htmlall':'UTF-8'}">
								<i class="icon-double-angle-right"></i>
							</a>
						</li>
					</ul>
            
               
               
      <input id="collection-page" name="collection_page" type="hidden">
		<script type="text/javascript">
			$('.collection-pagination-link').on('click',function(e){
				e.preventDefault();

				if (!$(this).parent().hasClass('disabled'))
					$('#collection-page').val($(this).data("page")).closest("form").submit();
			});
		</script>
    
          </div>
        </div>
         {else}
            <p class="text-center">{l s='No results found' mod='correos'}</p>
        {/if}
         
    </form>
   </div>
   

<div class="modal fade" id="collectionDetails" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{l s='Collection Details' mod='correos'}</h4>
            </div>
            <div class="modal-body">

            <div class="form-horizontal">
         <form method="post" id="form-collection-details">

            <div class="form-group">
               <div class="col-xs-4 text-right">
               <label class="control-label required">
                   {l s='Name and surname' mod='correos'}
               </label>
               </div>
               <div class="col-xs-8">
                  <input type="text" id="collection-detail-sender-name" name="collection-detail-sender-name" class="form-control required collection-detail-control" autocomplete="off">
               </div>
            </div>
            
            <div class="form-group">
               <div class="col-xs-4 text-right">
               <label class="control-label required">
                   {l s='Address' mod='correos'}
               </label>
               </div>
               <div class="col-xs-8">
                  <input type="text" id="collection-detail-sender-address" name="collection-detail-sender-address" class="form-control required collection-detail-control" autocomplete="off">
               </div>
            </div>
     
     
            <div class="form-group">
               <div class="col-xs-4 text-right">
                  <label class="control-label required">
                     {l s='City' mod='correos'}
                  </label>
               </div>
               <div class="col-xs-8">
                  <input type="text" id="collection-detail-sender-city" name="collection-detail-sender-city" class="form-control required collection-detail-control" autocomplete="off">
               </div>
            </div>
            
            <div class="form-group">
               <div class="col-xs-4 text-right">
                  <label class="control-label required">
                  {l s='Postal Code' mod='correos'}
                  </label>
               </div>
               <div class="col-xs-8">
                  <input type="text" id="collection-detail-sender-postalcode" name="collection-detail-sender-postalcode" class="form-control required collection-detail-control" autocomplete="off">
               </div>   
            </div>

            
            <div class="form-group">
               <div class="col-xs-4 text-right">
                  <label class="control-label required">
                     {l s='Mobile phone' mod='correos'}
                  </label>
               </div>
               <div class="col-xs-8">
                  <input type="text" id="collection-detail-sender-phone" name="collection-detail-sender-phone" class="form-control required collection-detail-control" autocomplete="off">
               </div>
            </div>

            <div class="form-group">
               <div class="col-xs-4 text-right">
                  <label class="control-label required">
                     {l s='E-mail' mod='correos'}
                  </label>
               </div>
               <div class="col-xs-8">
                  <input type="text" id="collection-detail-sender-email" name="collection-detail-sender-email" class="form-control required collection-detail-control" autocomplete="off">
               </div>
            </div> 
          <div class="form-group">
               <div class="col-xs-4 text-right">
                  <label class="control-label">
                     {l s='Collection reference' mod='correos'}
                  </label>
               </div>
               <div class="col-xs-8">
                  <input type="text" name="collection-detail-reference" id="collection-detail-reference" class="form-control collection-detail-control"  maxlength="100" autocomplete="off">
               </div>
          </div> 
          <div class="form-group">
               <div class="col-xs-4 text-right">
                  <label class="control-label required">
                     {l s='Confirmation code' mod='correos'}
                  </label>
               </div>
               <div class="col-xs-8">
                  <input type="text" name="collection-detail-confirmation-code" id="collection-detail-confirmation-code" class="form-control required collection-detail-control"  maxlength="100" autocomplete="off">
                    <input type="hidden" name="hidden-collection-detail-confirmation-code" id="hidden-collection-detail-confirmation-code">
               </div>
          </div> 
        <div class="row">
             <div class="col-xs-4">
                <label class="control-label">
                   {l s='Time of collection' mod='correos'}
                </label>
                <select name="collection-detail-time" id="collection-detail-time" class="form-control collection-detail-control" autocomplete="off">
                         <option value="morning">{l s='Morning' mod='correos'}</option>
                         <option value="afternoon">{l s='Afternoon' mod='correos'}</option>
                </select>
             </div> 
             <div class="col-xs-4">
                <label class="control-label required">
                   {l s='Date of collection' mod='correos'}
                </label>
                <input type="text" value="" id="collection-detail-date" name="collection-detail-date" class="datepicker date-input form-control required collection-detail-control" autocomplete="off">
                
             </div> 
             <div class="col-xs-4">
                <label class="control-label required">
                   {l s='Number of pieces' mod='correos'}  
                </label>
                <input type="text" id="collection-detail-pieces" name="collection-detail-pieces" class="form-control required collection-detail-control" autocomplete="off">
             </div>
         </div>
         <div class="row">
         <div class="col-xs-4">
         
                <label class="control-label required">
                   {l s='Size' mod='correos'} 
                </label>
                <select name="collection-detail-size" id="collection-detail-size" class="form-control required collection-detail-control" autocomplete="off">
                      <option value=""></option>
                     <option value="10">{l s='Envelopes' mod='correos'}</option>
                     <option value="20">{l s='Small (box shoes)' mod='correos'}</option>
                     <option value="30">{l s='Medium (box with packs folios)' mod='correos'}</option>
                     <option value="40">{l s='Large (box 80x80x80 cm)' mod='correos'}</option>
                     <option value="50">{l s='Very large (larger than box 80x80x80 cm)' mod='correos'}</option>
                     <option value="60">Palet</option>
               </select>
                <p class="help-block">{l s='If there are several sizes, it indicates the highest' mod='correos'}</p>
  
         </div>
         <div class="col-xs-4">
                <label class="control-label">
                {l s='Label printing' mod='correos'} 
                </label>
                 <select name="collection-detail-label_print" id="collection-detail-label_print" class="form-control collection-detail-control" autocomplete="off">
                    <option value="N"></option>
                    <option value="S">{l s='Yes' mod='correos'} ({l s='Max. 5 labels' mod='correos'})</option>
                    <option value="N">{l s='No' mod='correos'}</option>
                  </select>
                  <p class="help-block">{l s='Do you need Correos to print the labels?' mod='correos'}</p>
         </div>
        <div class="col-xs-4" style="display:none">
            <label class="control-label">
                {l s='Arrange Collection' mod='correos'} 
            </label>
            <select name="collection-detail-arrange_collection" id="collection-detail-arrange_collection" class="form-control collection-detail-control" autocomplete="off">
                <option  value="N"></option>
                <option  value="S">{l s='Yes' mod='correos'}</option>
                <option  value="N">{l s='No' mod='correos'}</option>
            </select>

        </div>
      </div>
         <div class="row">
            <div class="col-xs-12">
            <div class="form-group">
             <table id="collection-details-orders-table"></table>
             </div>
             
             <div class="form-group">
                <label class="control-label">
                   {l s='Comments' mod='correos'} ({l s='Max. 100 characters' mod='correos'})
                </label>
                <textarea class="form-control collection-detail-control" name="collection-detail-comments" id="collection-detail-comments" maxlength="100" class="form-control" rows="4"></textarea>
             </div>
            </div>
        </div>

          <div class="row">

            <button class="btn btn-primary pull-right" id="btn-collection-detail-repeat" name="btn-collection-detail-repeat" type="submit">
                <i class="fa fa-save nohover"></i>
                {l s='Repeat' mod='correos'}
            </button>
            <button class="btn btn-primary pull-right" id="btn-collection-detail-cancel" name="btn-collection-detail-cancel" type="submit">
                <i class="fa fa-save nohover"></i>
                {l s='Cancel Collection' mod='correos'}
            </button>
            <button class="btn btn-primary pull-right" id="btn-collection-detail-export" name="btn-collection-detail-export" type="submit">
                <i class="fa fa-save nohover"></i>
                {l s='Export' mod='correos'}
            </button>
            </div>
            <input type="hidden" name="hidden-collection-detail-id" id="hidden-collection-detail-id"/>
        </form>
      </div>
      
      
      
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{l s='Cerrar' mod='correos'}</button>
            </div>
        </div>
    </div>
</div>
