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


<div class="correos-carrer-content citypaq {if $params.correos_config.presentation_mode == 'popup'}citypaq-popup{/if}">

{if $params.correos_config.presentation_mode == 'popup'} 
<div style="text-align: right;">
<span id="correos_popup_selected_paq{$params.id_carrier|intval}">
  {if isset($params.request_data->homepaq_code)}
    {foreach $params.request_data->homepaqs as $homepaq}
      {if $homepaq->code == $params.request_data->homepaq_code}{$homepaq->alias|escape:'htmlall':'UTF-8'}{/if}
    {/foreach}
  {/if}
</span>
<a data-toggle="modal" href="#paqmodal-correos{$params.id_carrier|intval}" class="correos_popuplink"><span class="arrow_rightbefore"></span><span class="correos_popuplinktxt office">{l s='Find Terminal' mod='correos'}</span><span class="arrow_rightafter"></span></a>
</div>


<div class="modal fade modal-correos" id="paqmodal-correos{$params.id_carrier|intval}"  role="dialog">
   <div class="modal-dialog">
      <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">{l s='Select your terminal' mod='correos'} CityPaq</h4>
        </div>
        <div class="modal-body">
{/if}

<span id="paq_search{$params.id_carrier|intval}" class="paq_search">


<p>{l s='Introduce your User name if you are registered to find your favorites' mod='correos'}</p>
  <div class="paq_register_link">
	    <a href="http://www.correospaq.es/ss/Satellite?c=Page&cid=1363189180749&pagename=CorreosPaqSite/Page/A_Layout_PAQ" target="_blank" style="text-decoration:underline"> {l s='What is Homepaq and CityPaq?' mod='correos'}</a>
			<a href="https://online.correospaq.es/pages/registro.xhtml" class="paqurl" target="_blank">{l s='Free register!' mod='correos'}</a>
	</div>
    
  <span class="label">{l s='User name' mod='correos'}:</span> <input type="text" id="paquser{$params.id_carrier|intval}" name="paquser" class="paquser" value="{if isset($params.request_data->homepaq_user)}{$params.request_data->homepaq_user|escape:'htmlall':'UTF-8'}{/if}" /> 
  <span id="paq_loading{$params.id_carrier|intval}" style="display:none"> <img src="{if isset($urls)}{$urls.base_url|escape:'htmlall':'UTF-8'}{/if}modules/correos/views/img/opc-ajax-loader.gif" alt="" /></span>
	 <a href="#" class="paqsearch" id="paqsearch{$params.id_carrier|intval}" onclick="Correos.searchFavoritesPaqs({$params.id_carrier|intval});return false;" title="{l s='Favorites' mod='correos'}">{l s='Favorites' mod='correos'}</a>
	</span>
  
  
  <p style="margin: 5px 0">
    {l s='Or search a CitiPaq terminal' mod='correos'} 
    <span id="paq_result_fail_message{$params.id_carrier|intval}" class="paq_result_fail_message"></span>
  </p>
   

  <div class="row">
      
       <div class="col-md-7">
       
          <div>
            <label for="citypaqpostcode-radio{$params.id_carrier|intval}" class="citypaqsearchby-label">
              <input type="radio" name="citypaqsearchby{$params.id_carrier|intval}" class="citypaqsearchby{$params.id_carrier|intval}" value="postcode" id="citypaqpostcode-radio{$params.id_carrier|intval}" checked/> 
              {l s='Search by Post Code' mod='correos'}
            </label>
            <input type="text" id="citypaqpostcode{$params.id_carrier|intval}" class="citypaq-postcode" value="{$params.cr_client_postcode|escape:'htmlall':'UTF-8'}">

          </div>
          <div>
            <label for="citypaqprovince-radio{$params.id_carrier|intval}" class="citypaqsearchby-label">
              <input type="radio" name="citypaqsearchby{$params.id_carrier|intval}" class="citypaqsearchby{$params.id_carrier|intval}" value="province" id="citypaqprovince-radio{$params.id_carrier|intval}" onchange="Correos.getStatesWithCitypaq({$params.id_carrier|intval});"> 
              {l s='Search by Province' mod='correos'}
            </label>
            <span id="citypaqprovince-loading{$params.id_carrier|intval}" style="display:none"> 
              <img src="{if isset($urls)}{$urls.base_url|escape:'htmlall':'UTF-8'}{/if}modules/correos/views/img/opc-ajax-loader.gif" alt="" /> 
              {l s='Loading...' mod='correos'}
            </span>
             <select id="citypaq-province{$params.id_carrier|intval}" class="citypaq-province"></select>
          </div>
       </div>
       
       <div class="col-md-5 paqsearch_other-wrapper">
         
          <a href="#" class="paqsearch paqsearch_other" id="paqsearch_other{$params.id_carrier|intval}" onclick="Correos.cityPaqSearch({$params.id_carrier|intval});return false;" title="{l s='Search' mod='correos'}">{l s='Search' mod='correos'}</a>
          <span id="citypaq_searchtype_state_loading" style="display:none"> 
            <img src="{if isset($urls)}{$urls.base_url|escape:'htmlall':'UTF-8'}{/if}modules/correos/views/img/opc-ajax-loader.gif" alt="" /> 
            {l s='Loading...' mod='correos'}
          </span>
      </div>

  </div>


   <div id="favorites-result{$params.id_carrier|intval}" class="favorites-result row" style="display:none">
      {l s='Select your terminal' mod='correos'}:
			<select id="favorites-paqs{$params.id_carrier|intval}" name="favorites-paqs" class="correospaqs favorites-paqs" onchange="Correos.setSelectedPaq('favorites-paqs', {$params.id_carrier|intval});Correos.updatePaq({$params.id_carrier|intval})">
			</select>
   </div>
   
   

            <span id="citypaq_search_loading{$params.id_carrier|intval}" class="citypaq_search_loading" style="display:none"> 
              <img src="{if isset($urls)}{$urls.base_url|escape:'htmlall':'UTF-8'}{/if}modules/correos/views/img/opc-ajax-loader.gif" alt="" /> {l s='Loading...' mod='correos'}
            </span>

            <span id="citypaq_search_fail{$params.id_carrier|intval}" class="citypaq_search_fail" style="display:none"></span>
            
            <div id="citypaqs_map_options{$params.id_carrier|intval}" class="citypaqs_map_options" style="display:none">             
               <div id="citypaqs_map_wrapper{$params.id_carrier|intval}" class="citypaqs_map_wrapper">   
               <div id="citypaqs_map{$params.id_carrier|intval}" class="citypaqs_map"></div>
               </div>
               <div id="citypaqs_info{$params.id_carrier|intval}" class="citypaqs_info">
                  <p><strong>{l s='Terminal' mod='correos'}:</strong></p>
                  <select id="citypaqs{$params.id_carrier|intval}" name="citypaqs" class="citypaqs" onchange="Correos.CityPaq_setGoogleMaps({$params.id_carrier|intval});Correos.setSelectedPaq('citypaqs', {$params.id_carrier|intval});Correos.updatePaq({$params.id_carrier|intval})"></select>
                 <br>
                 
                  <p><strong>{l s='Address' mod='correos'}:</strong></p>
                  <p id="citypaqs_address{$params.id_carrier|intval}"></p>
                  <p><strong>{l s='Schedule' mod='correos'}: </strong><span id="citypaqs_schedule{$params.id_carrier|intval}"></span></p>
                  <!--
                  <a href="#" onclick="setselectedpaq('citypaqs');update_paq();return false;"><span class="arrow_leftbefore"></span><span class="correos_popuplinktxt">{l s='Select this Terminal' mod='correos'}</span><span class="arrow_leftafter"></span></a>
                  -->
                  <p style="font-size:13px; margin-top:10px;"><strong>{l s='Selected Terminal for the order' mod='correos'}:</strong> <span class="selected_paq"></span></p>
                  <br>
                  <div id="addtofavorites{$params.id_carrier|intval}" style="display:none">
                   <input type="text" id="paquser-addtofavorites{$params.id_carrier|intval}" name="paquser" class="paquser" value="" placeholder="{l s='User name' mod='correos'}">
                   <a href="#" onclick="Correos.addToFavorites({$params.id_carrier|intval});return false;" id="addtofavorites_btn{$params.id_carrier|intval}">
                     <span class="arrow_rightbefore"></span><span id="addtofavorites_txt" class="addtofavorites_txt">{l s='Add to favourites' mod='correos'}</span><span class="arrow_rightafter"></span> 
                   </a>
                    <span id="addtofavorites_loading{$params.id_carrier|intval}" style="display:none"> <img src="{if isset($urls)}{$urls.base_url|escape:'htmlall':'UTF-8'}{/if}modules/correos/views/img/opc-ajax-loader.gif" alt="" /> {l s='Loading...' mod='correos'}</span>
                 </div>
                   
               </div>
            
            </div>
            
    <div id="citypaq-contact-details{$params.id_carrier|intval}" class="citypaq-contact-details" {if !isset($params.request_data->homepaq_code)}style="display:none"{/if}>
      <p>{l s='Contact details to inform your when your package is ready to be picked up' mod='correos'}:</p>
         <span>E-mail:
         <input type="text" name="paq_email" id="paq_email{$params.id_carrier|intval}" class="paq_email" style="width:180px; margin-left:10px; margin-right:25px" value="{if isset($params.request_data->email) and $params.request_data->email != ''}{$params.request_data->email}{else}{$params.cr_client_email}{/if}" onchange="Correos.updatePaq({$params.id_carrier|intval})" onkeyup="Correos.tooglePaymentModules({$params.id_carrier|intval});" value="{$params.cr_client_email|escape:'htmlall':'UTF-8'}" />  
        {l s='Mobile number' mod='correos'}:
          <input type="text" name="paq_mobile"  class="paq_mobile" style="width:100px; margin-left:10px;"  id="paq_mobile{$params.id_carrier|intval}" value="{if isset($params.request_data->mobile->number) and $params.request_data->mobile->number != ''}{$params.request_data->mobile->number}{else}{$params.cr_client_mobile}{/if}" onchange="Correos.updatePaq({$params.id_carrier|intval})" onkeyup="Correos.tooglePaymentModules({$params.id_carrier|intval});" value="{$params.cr_client_mobile|escape:'htmlall':'UTF-8'}"/>
      </span>
    </div>
	{if $params.correos_config.presentation_mode == 'popup'}
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">{l s='Close' mod='correos'}</button>
        </div>
      </div>
   </div>
</div>
{/if}

    <input type="hidden" id="selectedpaq_code{$params.id_carrier|intval}" name="selectedpaq_code" value="{if isset($params.request_data->homepaq_code)}{$params.request_data->homepaq_code|escape:'htmlall':'UTF-8'}{/if}"/>
 
    <div class="cash-on-delivery-message">{l s='Not compatible with Cash on Delivery' mod='correos'}!</div>
</div>


<div class="modal fade modal-correos" id="iframemodal-correos{$params.id_carrier|intval}"  role="dialog">
   <div class="modal-dialog">
      <div class="modal-content">
     
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">{l s='Add CityPaq terminal to favorites' mod='correos'}</h4>
        </div>
        <div class="modal-body">
        <iframe id="iframe-correospaq{$params.id_carrier|intval}" class="iframe-correospaq"></iframe>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">{l s='Close' mod='correos'}</button>
        </div>
     </div>
     
     </div>
   </div> 
   
   
   
<style>
.paqsearch {
    color: #000;
    font-style: italic;
}
</style>