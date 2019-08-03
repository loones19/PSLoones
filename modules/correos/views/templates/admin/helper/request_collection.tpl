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
<div class="panel-heading">{l s='Request collection' mod='correos'}</div>
   <div class="panel-body">
   

         <div class="form-horizontal">
         <form method="post" id="form-request-collection">
         <input type="hidden" name="orders_collection" id="orders_collection" value=""/>
         <div class="form-group">
               <div class="col-xs-3 text-right">
               <label for="collection_req_name" class="control-label required">
                   {l s='Select Recipient' mod='correos'}
               </label>
               </div>
               <div class="col-xs-8">
                    {if isset($sender_form.options.select_sender.data)}
                        <select name="collection_sender" id="collection_sender" class="form-control" autocomplete="off">
                            {foreach from=$correos_config.senders key=sender_key item=sender}
                              <option value="{$sender_key|escape:'htmlall':'UTF-8'}" {if isset($sender->sender_default) && $sender->sender_default == '1'}selected{/if}> 
                              {if $sender->nombre != ''}
                                {$sender->nombre|escape:'htmlall':'UTF-8'} {$sender->apellidos|escape:'htmlall':'UTF-8'}
                              {else}
                                {$sender->presona_contacto|escape:'htmlall':'UTF-8'} 
                              {/if}
                              </option>
                           {/foreach}
                        </select>
               {/if}
              </div>
            </div>
            

            <div class="form-group">
               <div class="col-xs-3 text-right">
               <label for="collection_req_name" class="control-label required">
                   {l s='Name and surname' mod='correos'}
               </label>
               </div>
               <div class="col-xs-8">
                  <input type="text" value="{$sender_form.options.sender_nombre.value|escape:'htmlall':'UTF-8'} {$sender_form.options.sender_apellidos.value|escape:'htmlall':'UTF-8'}" id="collection_req_name" name="collection_req_name" class="form-control required" autocomplete="off">
               </div>
            </div>
            
            <div class="form-group">
               <div class="col-xs-3 text-right">
               <label for="collection_req_address" class="control-label required">
                   {l s='Address' mod='correos'}
               </label>
               </div>
               <div class="col-xs-8">
                  <input type="text" value="{$sender_form.options.sender_direccion.value|escape:'htmlall':'UTF-8'}" id="collection_req_address" name="collection_req_address" class="form-control required" autocomplete="off">
               </div>
            </div>
     
     
            <div class="form-group">
               <div class="col-xs-3 text-right">
                  <label for="collection_req_city" class="control-label required">
                     {l s='City' mod='correos'}
                  </label>
               </div>
               <div class="col-xs-8">
                  <input type="text" value="{$sender_form.options.sender_localidad.value|escape:'htmlall':'UTF-8'}" id="collection_req_city" name="collection_req_city" class="form-control required" autocomplete="off">
               </div>
            </div>
            
            <div class="form-group">
               <div class="col-xs-3 text-right">
                  <label for="collection_req_postalcode" class="control-label required">
                  {l s='Postal Code' mod='correos'}
                  </label>
               </div>
               <div class="col-xs-8">
                  <input type="text" value="{$sender_form.options.sender_cp.value|escape:'htmlall':'UTF-8'}" id="collection_req_postalcode" name="collection_req_postalcode" class="form-control required" autocomplete="off">
               </div>   
            </div>

            
            <div class="form-group">
               <div class="col-xs-3 text-right">
                  <label for="collection_req_mobile_phone" class="control-label required">
                     {l s='Mobile phone' mod='correos'}
                  </label>
               </div>
               <div class="col-xs-8">
                  <input type="text" value="{$sender_form.options.sender_movil.value|escape:'htmlall':'UTF-8'}" id="collection_req_mobile_phone" name="collection_req_mobile_phone" class="form-control required" autocomplete="off">
               </div>
            </div>

            <div class="form-group">
               <div class="col-xs-3 text-right">
                  <label class="control-label required">
                     {l s='E-mail' mod='correos'}
                  </label>
               </div>
               <div class="col-xs-8">
                  <input type="text" value="{$sender_form.options.sender_email.value|escape:'htmlall':'UTF-8'}" id="collection_req_email" name="collection_req_email" class="form-control required" autocomplete="off">
               </div>
            </div> 
          <div class="form-group">
               <div class="col-xs-3 text-right">
                  <label class="control-label">
                     {l s='Collection reference' mod='correos'}
                  </label>
               </div>
               <div class="col-xs-8">
                  <input type="text" name="collection_req_reference" id="collection_reference" class="form-control"  maxlength="100" autocomplete="off">
                  <p class="help-block"><label class="control-label required" style="padding-top:0; color: #959595;font-size: inherit;">{l s='Can not be repeated' mod='correos'}</label></p>
               </div>
            </div> 
          
        <div class="row">
             <div class="col-xs-4">
                <label class="control-label">
                   {l s='Time of collection' mod='correos'}
                </label>
                <select name="collection_req_time" class="form-control" autocomplete="off">
                         <option value="morning">{l s='Morning' mod='correos'}</option>
                         <option value="afternoon">{l s='Afternoon' mod='correos'}</option>
                </select>
             </div> 
             <div class="col-xs-4">
                <label class="control-label required">
                   {l s='Date of collection' mod='correos'}
                </label>
                <input type="text" value="" id="collection_req_date" name="collection_req_date" class="datepicker date-input form-control required" autocomplete="off">
                
             </div> 
             <div class="col-xs-4">
                <label class="control-label required">
                   {l s='Number of pieces' mod='correos'}  
                </label>
                <input type="text" id="collection_req_pieces" name="collection_req_pieces" class="form-control required" autocomplete="off">
             </div>
         </div>
         <div class="row">
         <div class="col-xs-4">
         
                <label class="control-label required">
                   {l s='Size' mod='correos'} 
                </label>
          
           
                <select name="collection_req_size" id="collection_size" class="form-control required" autocomplete="off">
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
            
            
                 <select name="collection_req_label_print" id="label_print" class="form-control" autocomplete="off">
                    <option  value="N"></option>
                    <option  value="S">{l s='Yes' mod='correos'} ({l s='Max. 5 labels' mod='correos'})</option>
                    <option  value="N">{l s='No' mod='correos'}</option>
                  </select>
                  <p class="help-block">{l s='Do you need Correos to print the labels?' mod='correos'}</p>
         </div>
         
        <div class="col-xs-4" style="display:none">
         
                <label class="control-label">
                {l s='Arrange Collection' mod='correos'} 
                </label>
            
            
                 <select name="collection_req_arrange_collection" id="arrange_collection" class="form-control" autocomplete="off">
                    <option  value="N"></option>
                    <option  value="S">{l s='Yes' mod='correos'}</option>
                    <option  value="N">{l s='No' mod='correos'}</option>
                  </select>

         </div>
         
      </div>
         <div class="row">
             <div class="form-group">
             <div id="select-orders-message" style="display:none">
                {l s='Please select max. 5 shipents from' mod='correos'} 
                <a href="#" id="goto-tab-request_collection">{l s='Search shipping Tab' mod='correos'}.</a>
                {l s='Then from Bulk Actions select' mod='correos'} '{l s='Add to "Request Collection" form' mod='correos'}'
             </div>
             <table id="collection-orders-table"></table>
             </div>
             <div class="form-group">
                <label class="control-label">
                   {l s='Comments' mod='correos'} ({l s='Max. 100 characters' mod='correos'})
                </label>
                <textarea class="form-control" name="collection_req_comments" maxlength="100" class="form-control" rows="4"></textarea>
             </div>
         </div>

         <hr>
         <button class="btn btn-primary pull-right has-action btn-save-general" id="btn-request_collection" name="form-request_collection" type="submit">
            <i class="fa fa-save nohover"></i>
            {l s='Request collection' mod='correos'}
        </button>
        </form>
      </div>

   </div>
