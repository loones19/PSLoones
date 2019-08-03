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
 <script type="text/javascript">
CorreosConfig.showCustomsMessage = {$show_customs_message|escape:'htmlall':'UTF-8'};
</script>

<div id="aduana_content" style="display:none; font-weight:bold; color:red">{l s='The shipment involves customs procedures. Shipping price may increase' mod='correos'}</div>
<div id="timetable" style="display:none;font-weight:bold">
{if $S0236_enabletimeselect eq '1'}
	<div id="timetable_inner">
	{l s='Select delivery time' mod='correos'}: 
	<input type="radio" name="cr_timetable" id="9_12" value="01" onchange="Correos.updateHoursSelect('01');"/><label for="9_12">09:00 - 12:00 </label>
	<input type="radio" name="cr_timetable" id="12_15" value="02" onchange="Correos.updateHoursSelect('02');"/><label for="12_15">12:00 - 15:00</label>
	<input type="radio" name="cr_timetable" id="15_18" value="03" onchange="Correos.updateHoursSelect('03');"/><label for="15_18">15:00 - 18:00</label> 
	<input type="radio" name="cr_timetable" id="18_21" value="04" onchange="Correos.updateHoursSelect('04');"/><label for="18_21">18:00 - 21:00</label>
	</div>
{/if}
</div>
<div id="cr_internacional" style="display:none">
      <p>{l s='Check your mobile phone' mod='correos'}: <input type="text" id="cr_international_mobile" name="cr_international_mobile" value="{$cr_client_mobile|escape:'htmlall':'UTF-8'}" onchange="Correos.updateInternationalMobile();" onkeyup="Correos.tooglePaymentModules();" /> </p>
</div>

<div id="correospaq" style="display:none;">

{if $correos_config.presentation_mode == 'popup'} 
<div style="text-align: right;">
<span id="correos_popup_selected_paq{$id_carrier|intval}">
  {if isset($request_data->homepaq_code)}
    {foreach $request_data->homepaqs as $homepaq}
      {if $homepaq->code == $request_data->homepaq_code}{$homepaq->alias|escape:'htmlall':'UTF-8'}{/if}
    {/foreach}
  {/if}
</span>
<a data-toggle="modal" href="#paqmodal-correos{$id_carrier|intval}" class="correos_popuplink"><span class="arrow_rightbefore"></span><span class="correos_popuplinktxt office">{l s='Find Terminal' mod='correos'}</span><span class="arrow_rightafter"></span></a>
</div>


<div class="modal fade modal-correos" id="paqmodal-correos{$id_carrier|intval}"  role="dialog">
   <div class="modal-dialog">
      <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">{l s='Select your terminal' mod='correos'} CityPaq</h4>
        </div>
        <div class="modal-body">
{/if}

<span id="paq_search{$id_carrier|intval}" class="paq_search">


<p>{l s='Introduce your User name if you are registered to find your favorites' mod='correos'}</p>
  <div class="paq_register_link">
	    <a href="http://www.correospaq.es/ss/Satellite?c=Page&cid=1363189180749&pagename=CorreosPaqSite/Page/A_Layout_PAQ" target="_blank" style="text-decoration:underline"> {l s='What is Homepaq and CityPaq?' mod='correos'}</a>
			<a href="https://online.correospaq.es/pages/registro.xhtml" class="paqurl" target="_blank">{l s='Free register!' mod='correos'}</a>
	</div>
    
  <label>{l s='User name' mod='correos'}:</label> <input type="text" id="paquser{$id_carrier|intval}" name="paquser" class="paquser" value="{if isset($request_data->homepaq_user)}{$request_data->homepaq_user|escape:'htmlall':'UTF-8'}{/if}" /> 
  <span id="paq_loading{$id_carrier|intval}" style="display:none"> <img src="{$module_dir|escape:'htmlall':'UTF-8'}views/img/opc-ajax-loader.gif" alt="" /></span>
	 <a href="#" class="paqsearch" id="paqsearch{$id_carrier|intval}" onclick="Correos.searchFavoritesPaqs({$id_carrier|intval});return false;" title="{l s='Favorites' mod='correos'}">{l s='Favorites' mod='correos'}</a>
	</span>
  
  
  <p style="margin: 5px 0">
    {l s='Or search a CitiPaq terminal' mod='correos'} 
    <span id="paq_result_fail_message{$id_carrier|intval}" class="paq_result_fail_message"></span>
  </p>
   

  <div class="row">
      
       <div class="col-md-7 col-lg-5">
       
          <div>
            <label for="citypaqpostcode-radio{$id_carrier|intval}" class="citypaqsearchby-label">
              <input type="radio" name="citypaqsearchby{$id_carrier|intval}" class="citypaqsearchby{$id_carrier|intval}" value="postcode" id="citypaqpostcode-radio{$id_carrier|intval}" checked/> 
              {l s='Search by Post Code' mod='correos'}
            </label>
            <input type="text" id="citypaqpostcode{$id_carrier|intval}" class="citypaq-postcode" value="{$cr_client_postcode|escape:'htmlall':'UTF-8'}">

          </div>
          <div>
            <label for="citypaqprovince-radio{$id_carrier|intval}" class="citypaqsearchby-label">
              <input type="radio" name="citypaqsearchby{$id_carrier|intval}" class="citypaqsearchby{$id_carrier|intval}" value="province" id="citypaqprovince-radio{$id_carrier|intval}" onchange="Correos.getStatesWithCitypaq({$id_carrier|intval});"> 
              {l s='Search by Province' mod='correos'}
            </label>
            <span id="citypaqprovince-loading{$id_carrier|intval}" style="display:none"> 
              <img src="{$module_dir|escape:'htmlall':'UTF-8'}views/img/opc-ajax-loader.gif" alt="" /> 
              {l s='Loading...' mod='correos'}
            </span>
             <select id="citypaq-province{$id_carrier|intval}" class="citypaq-province"></select>
          </div>
       </div>
       
       <div class="col-md-5 paqsearch_other-wrapper">
         
          <a href="#" class="paqsearch paqsearch_other" id="paqsearch_other{$id_carrier|intval}" onclick="Correos.cityPaqSearch({$id_carrier|intval});return false;" title="{l s='Search' mod='correos'}">{l s='Search' mod='correos'}</a>
          <span id="citypaq_searchtype_state_loading" style="display:none"> 
            <img src="{$module_dir|escape:'htmlall':'UTF-8'}views/img/opc-ajax-loader.gif" alt="" /> 
            {l s='Loading...' mod='correos'}
          </span>
      </div>

  </div>


   <div id="favorites-result{$id_carrier|intval}" class="favorites-result row" style="display:none">
      {l s='Select your terminal' mod='correos'}:
			<select id="favorites-paqs{$id_carrier|intval}" name="favorites-paqs" class="correospaqs favorites-paqs" onchange="Correos.setSelectedPaq('favorites-paqs', {$id_carrier|intval});Correos.updatePaq({$id_carrier|intval})">
			</select>
   </div>
   
   

            <span id="citypaq_search_loading{$id_carrier|intval}" class="citypaq_search_loading" style="display:none"> 
              <img src="{$module_dir|escape:'htmlall':'UTF-8'}views/img/opc-ajax-loader.gif" alt="" /> {l s='Loading...' mod='correos'}
            </span>

            <span id="citypaq_search_fail{$id_carrier|intval}" class="citypaq_search_fail" style="display:none"></span>
            
            <div id="citypaqs_map_options{$id_carrier|intval}" class="citypaqs_map_options" style="display:none">             
               <div id="citypaqs_map_wrapper{$id_carrier|intval}" class="citypaqs_map_wrapper">   
               <div id="citypaqs_map{$id_carrier|intval}" class="citypaqs_map"></div>
               </div>
               <div id="citypaqs_info{$id_carrier|intval}" class="citypaqs_info">
                  <p><strong>{l s='Terminal' mod='correos'}:</strong></p>
                  <select id="citypaqs{$id_carrier|intval}" name="citypaqs" class="citypaqs" onchange="Correos.CityPaq_setGoogleMaps({$id_carrier|intval});Correos.setSelectedPaq('citypaqs', {$id_carrier|intval});Correos.updatePaq({$id_carrier|intval})"></select>
                 <br>
                 
                  <p><strong>{l s='Address' mod='correos'}:</strong></p>
                  <p id="citypaqs_address{$id_carrier|intval}"></p>
                  <p><strong>{l s='Schedule' mod='correos'}: </strong><span id="citypaqs_schedule{$id_carrier|intval}"></span></p>
                  <!--
                  <a href="#" onclick="setselectedpaq('citypaqs');update_paq();return false;"><span class="arrow_leftbefore"></span><span class="correos_popuplinktxt">{l s='Select this Terminal' mod='correos'}</span><span class="arrow_leftafter"></span></a>
                  -->
                  <p style="font-size:13px; margin-top:10px;"><strong>{l s='Selected Terminal for the order' mod='correos'}:</strong> <span class="selected_paq"></span></p>
                  <br>
                  <div id="addtofavorites{$id_carrier|intval}" style="display:none">
                   <input type="text" id="paquser-addtofavorites{$id_carrier|intval}" name="paquser" class="paquser" value="" placeholder="{l s='User name' mod='correos'}">
                   <a href="#" onclick="Correos.addToFavorites({$id_carrier|intval});return false;" id="addtofavorites_btn{$id_carrier|intval}">
                     <span class="arrow_rightbefore"></span><span id="addtofavorites_txt" class="addtofavorites_txt">{l s='Add to favourites' mod='correos'}</span><span class="arrow_rightafter"></span> 
                   </a>
                    <span id="addtofavorites_loading{$id_carrier|intval}" style="display:none"> <img src="{$module_dir|escape:'htmlall':'UTF-8'}views/img/opc-ajax-loader.gif" alt="" /> {l s='Loading...' mod='correos'}</span>
                 </div>
                   
               </div>
            
            </div>
            
    <div id="citypaq-contact-details{$id_carrier|intval}" class="citypaq-contact-details text-center" {if !isset($request_data->homepaq_code)}style="display:none"{/if}>
      <p class="text-center">{l s='Contact details to inform your when your package is ready to be picked up' mod='correos'}:</p>
         <span>E-mail:
         <input type="text" name="paq_email" id="paq_email{$id_carrier|intval}" class="paq_email" style="width:180px; margin-left:10px; margin-right:25px" value="{if isset($request_data->email) and $request_data->email != ''}{$request_data->email}{else}{$cr_client_email}{/if}" onchange="Correos.updatePaq({$id_carrier|intval})" onkeyup="Correos.tooglePaymentModules({$id_carrier|intval});" value="{$cr_client_email|escape:'htmlall':'UTF-8'}" />  
        {l s='Mobile number' mod='correos'}:
          <input type="text" name="paq_mobile"  class="paq_mobile" style="width:100px; margin-left:10px;"  id="paq_mobile{$id_carrier|intval}" value="{if isset($request_data->mobile->number) and $request_data->mobile->number != ''}{$request_data->mobile->number}{else}{$cr_client_mobile}{/if}" onchange="Correos.updatePaq({$id_carrier|intval})" onkeyup="Correos.tooglePaymentModules({$id_carrier|intval});" value="{$cr_client_mobile|escape:'htmlall':'UTF-8'}"/>
      </span>
    </div>
	{if $correos_config.presentation_mode == 'popup'}
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">{l s='Close' mod='correos'}</button>
        </div>
      </div>
   </div>
</div>
{/if}

    <input type="hidden" id="selectedpaq_code{$id_carrier|intval}" name="selectedpaq_code" value="{if isset($request_data->homepaq_code)}{$request_data->homepaq_code|escape:'htmlall':'UTF-8'}{/if}"/>
 
    <div class="correos-cash-on-delivery-message">{l s='Not compatible with Cash on Delivery' mod='correos'}!</div>
    
    
</div><!-- correospaq-->
<span style="display:none">
<div id="correos_popuplinkcontent">
<span id="correosOfficeName_"></span> <a href="#correos_content" id="correos_popuplink"><span class="arrow_rightbefore"></span><span class="correos_popuplinktxt office">{l s='Select office' mod='correos'}</span><span class="arrow_rightafter"></span></a>
</div>
<div id="correos_popuplinkcontentpaq">
<span class="selected_paq"></span> <a href="#correospaq" id="correos_popuplinkpaq"><span class="arrow_rightbefore"></span><span class="correos_popuplinktxt citypaq">Buscar Terminal</span><span class="arrow_rightafter"></span></a>
</div>
</span>
{if $ps_version eq '1.4'}
<tr id="correos_content">
<td colspan="4">
	<div>
{else}
<div id="correos_content">	
{/if}
	
	<div id="message_no_office_error" style="display: none;">{l s='We are sorry, this postcode has no offices nearby. Please try another postcode' mod='correos'}</div> 
	<div style="display: none;" id="correosOffices_content">

		<div class="correos_actions" >
			<div class="correos_button_search">
			{l s='Enter Post Code to find office' mod='correos'}: 
			<input type="text" id="correos_postcode" value="{$cr_client_postcode|escape:'htmlall':'UTF-8'}" />     	
			<input type="button" class="btn_correos" value="Buscar" id="btn_office_search" />
			</div>

		</div>
		<select id="correosOfficesSelect" name="correosOfficesSelect" onchange="Correos.setOfficeInfo();Correos.updateOfficeInfo();"></select>
		<input type="hidden" id="registered_correos_postcode" name="registered_correos_postcode" value="{$cr_client_postcode|escape:'htmlall':'UTF-8'}" />
		<br clear="left">
		<div id="correo_info_nombre">
			<p><strong>{l s='Office' mod='correos'}</strong></p>
			<p id="correosOfficeName"></p>
		</div>
		<div>
			<p><strong>{l s='Address' mod='correos'}</strong></p>
			<p id="correosOfficeAddress"></p>
		</div>
		<div id="correosInfoMap"></div>
		<div id="correos_info_horarios">
			<p><strong>{l s='Opening hours' mod='correos'}</strong></p>
			<p>{l s='Opening hours Monday to Friday' mod='correos'}: <span id="correosOfficeHoursMonFri"></span></p>
			<p>{l s='Opening hours Saturday' mod='correos'}: <span id="correosOfficeHoursSat"></span></p>
			<p>{l s='Opening hours Sunday' mod='correos'}: <span id="correosOfficeHoursSun"></span></p>
		</div>
		<br clear="left" />
		<p id="correos_maplink_info">
			<a id="correos_maplink" style="text-decoration:underline" href="" target="_blank">{l s='Open in new window' mod='correos'}</a>
		</p>
		<br clear="left" />
		<p style="font-size:13px; font-weight:bold">{l s='Contact details to inform your when your package is ready to be picked up' mod='correos'}:</p>
		<p class="cr_contact_details correos_email"><strong>E-mail:</strong> <input type="text" name="correos_email" id="correos_email" style="width:180px;" onchange="Correos.updateOfficeInfo();" onkeyup="Correos.tooglePaymentModules();" value="{$cr_client_email|escape:'htmlall':'UTF-8'}" />  </p>
		<button class="closepopup btn_correos" onclick="$.fancybox.close()">{l s='Accept' mod='correos'}</button>
		<p class="cr_contact_details"> <strong>{l s='Mobile number' mod='correos'}:</strong> <input type="text" style="width:100px; " name="correos_mobile" id="correos_mobile" onchange="Correos.updateOfficeInfo();" onkeyup="Correos.tooglePaymentModules();" value="{$cr_client_mobile|escape:'htmlall':'UTF-8'}" /></p>
		
		<p class="cr_contact_details"><strong>{l s='Language SMS' mod='correos'}:</strong> 
			<select style="width:95px" name="correos_mobile_lang" id="correos_mobile_lang" onchange="Correos.updateOfficeInfo();">
				<option value="1">Castellano</option>
				<option value="2">Catalá</option>
				<option value="3">Euskera</option>
				<option value="4">Gallego</option>
			</select>
		</p>
		<br clear="left">
		
	</div>
{if $ps_version eq '1.4'}
	</div>
<style>
{literal}
#correos_info_map {width:350px}
#correosOfficeHoursMonFri, #correosOfficeHoursSat, #correosOfficeHoursSun {display:block}
{/literal}
</style>
	</td>
</tr>

{else}
</div>
{/if}
{if $ps_version eq '1.6'}
<style>
{literal}
#correosOffices { float:left; margin:10px 20px 0 0}
#correos_info_map {
  
    height: 270px;
    margin: 15px 20px 15px 0;
    width:400px;
}

#correosOffices_content p {padding: 0 5px 10px 0}
#correos_email, #correos_mobile{margin-right:20px}
{/literal}

</style>
{/if}
<script type="text/javascript" src="{$module_dir|escape:'htmlall':'UTF-8'}views/js/front/correos_jq_v502.js"></script>
