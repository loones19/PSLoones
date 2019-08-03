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
{if $ps_version eq '1.7'}
<section id="correos-tracking" class="box">
      <h3>{l s='Correos package information' mod='correos'}</h3>
{if $has_tracking}
      <table class="table table-striped table-bordered table-labeled hidden-xs-down">
        <thead class="thead-default">
          <tr>
            <th>{l s='Date' mod='correos'}</th>
            <th>{l s='State' mod='correos'}</th>
          </tr>
        </thead>
        <tbody>
        {foreach from=$tracking item=t} 
            <tr>
              <td>{$t->Fecha|escape:'htmlall':'UTF-8'}</td>
              <td>
                  {$t->Estado|escape:'htmlall':'UTF-8'}
              </td>
            </tr>
        {/foreach}
        </tbody>
      </table>
      <div class="hidden-sm-up history-lines">
        {foreach from=$tracking item=t} 
      <div class="history-line">
            <div class="date">{$t->Fecha|escape:'htmlall':'UTF-8'}</div>
            <div class="state">
                 {$t->Estado|escape:'htmlall':'UTF-8'}
            </div>
          </div>
    {/foreach}
        </div>
              {/if}
    </section>
    
{if $rma}
{assign var="rma_shipping_code_array" value=","|explode:$rma.shipment_code}
<section id="correos-rma" class="box">
      <h3>{l s='Correos RMA information' mod='correos'}</h3>
      <ul>
        {foreach $rma_shipping_code_array as $index => $shipping_code}
        <li>
        {l s='Parcel' mod='correos'} {if $rma_shipping_code_array|count > 1}{$index|intval +1}{/if}: {$shipping_code|escape:'htmlall':'UTF-8'}

        {if file_exists('modules/correos/pdftmp/'|cat:$shipping_code|lower|cat:'.pdf') and 'modules/correos/pdftmp/'|cat:$shipping_code|lower|cat:'.pdf'|@filesize > 0}
        <a href="{$cr_module_dir|escape:'htmlall':'UTF-8'}pdftmp/{$shipping_code|lower|escape:'htmlall':'UTF-8'}.pdf" style="text-decoration:underline" target="_blank">
                  {l s='Download Label' mod='correos'}
        </a>
        {/if}
                  
         {if file_exists('modules/correos/pdftmp/customs_'|cat:$shipping_code|lower|cat:'.pdf') and 'modules/correos/pdftmp/customs_'|cat:$shipping_code|lower|cat:'.pdf'|@filesize > 0}
          <a href="{$cr_module_dir|escape:'htmlall':'UTF-8'}pdftmp/customs_{$shipping_code|lower|escape:'htmlall':'UTF-8'}.pdf" style="text-decoration:underline; padding-left: 10px; padding-right: 10px;" target="_blank">
           {l s='Content Declaration' mod='correos'}
          </a>
         {/if}
        </li>
        {/foreach}
        {if file_exists('modules/correos/pdftmp/customs_rma_dcaf_'|cat:$id_order|cat:'.pdf')}
         <li>
         <a href="{$cr_module_dir|escape:'htmlall':'UTF-8'}pdftmp/customs_rma_dcaf_{$id_order|escape:'htmlall':'UTF-8'}.pdf" style="text-decoration:underline" target="_blank">
            {l s='Download DUA' mod='correos'}
         </a>
        </li>
        {/if}
      </ul>
</section>
{/if}

{else}
<h1 class="page-heading">{l s='Correos package information' mod='correos'}</h1>
<div class="table_block">
	<table class="detail_step_by_step table table-bordered">
{if $has_tracking}
		<thead>
			<tr>
				<th class="first_item">{l s='Date' mod='correos'}</th>
				<th class="last_item">{l s='State' mod='correos'}</th>
			</tr>
		</thead>
		<tbody>
            {foreach from=$tracking item=t} 
                <tr class="alternate_item">
                    <td>{$t->Fecha|escape:'htmlall':'UTF-8'}</td>
                    <td>{$t->Estado|escape:'htmlall':'UTF-8'}</td>
                    
                </tr>   
            {/foreach}
    
		</tbody>
{else}
 <tr>
	<th colspan="2">{$tracking|escape:'htmlall':'UTF-8'}</th>
</tr>
{/if}
	</table>
</div>

{if $rma}
{assign var="rma_shipping_code_array" value=","|explode:$rma.shipment_code}
<h1 class="page-heading">{l s='Correos RMA information' mod='correos'}</h1>
<div class="info-order box">
<ul>
        {foreach $rma_shipping_code_array as $index => $shipping_code}
        <li>
        {l s='Parcel' mod='correos'} {if $rma_shipping_code_array|count > 1}{$index|intval +1}{/if}: {$shipping_code|escape:'htmlall':'UTF-8'}

        {if file_exists('modules/correos/pdftmp/'|cat:$shipping_code|lower|cat:'.pdf') and 'modules/correos/pdftmp/'|cat:$shipping_code|lower|cat:'.pdf'|@filesize > 0}
        <a href="{$cr_module_dir|escape:'htmlall':'UTF-8'}pdftmp/{$shipping_code|lower|escape:'htmlall':'UTF-8'}.pdf" style="text-decoration:underline" target="_blank">
                {l s='Download Label' mod='correos'}
        </a>
        {/if}
                  
         {if file_exists('modules/correos/pdftmp/customs_'|cat:$shipping_code|lower|cat:'.pdf') and 'modules/correos/pdftmp/customs_'|cat:$shipping_code|lower|cat:'.pdf'|@filesize > 0}
          <a href="{$cr_module_dir|escape:'htmlall':'UTF-8'}pdftmp/customs_{$shipping_code|lower|escape:'htmlall':'UTF-8'}.pdf" style="text-decoration:underline; padding-left: 10px; padding-right: 10px;" target="_blank">
           {l s='Content Declaration' mod='correos'}
          </a>
         {/if}
        </li>
        {/foreach}
        {if file_exists('modules/correos/pdftmp/customs_rma_dcaf_'|cat:$id_order|cat:'.pdf')}
         <li>
         <a href="{$cr_module_dir|escape:'htmlall':'UTF-8'}pdftmp/customs_rma_dcaf_{$id_order|escape:'htmlall':'UTF-8'}.pdf" style="text-decoration:underline" target="_blank">
            {l s='Download DUA' mod='correos'}
         </a>
        </li>
        {/if}
      </ul>
      
</div>
{/if}

{/if}
