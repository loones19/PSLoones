{*
* 2007-2017 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2017 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}


<div class="special-banner">
  <div class="special-banner-inner">
     {hook h='displaySpecialBanner'}
  </div>

<section class="special-products">
<div class="special_inner">
<h1 class="products-section-title text-uppercase">{l s='Deal of the day' d='Shop.Theme.Global'}</h1>
  <div id="spe_res">
   <div class="products">
      {assign var='sliderFor' value=1} <!-- Define Number of product for SLIDER -->
      {if $slider == 1 && $no_prod >= $sliderFor}
         <div class="product-carousel">   

            <ul id="special-carousel" class="tm-carousel product_list">
        
      {else}
      <ul id="special-grid" class="special_grid product_list grid row gridcount">
          
      {/if}
  
      {foreach from=$products item="product"}
         
         <li class="{if $slider == 1 && $no_prod >= $sliderFor}item{else}product_item col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12{/if}">    
           
            {include file="catalog/_partials/miniatures/product-specialproduct.tpl" product=$product}

         </li>
        
      {/foreach}
  
      </ul>
      

      
      {if $slider == 0 && $no_prod >= $sliderFor}
      <a class="all-product-link float-xs-left pull-md-right h4" href="{$allSpecialProductsLink}">
         {l s='View More Products' mod='pst_specials'}
      </a>
      {/if}
      {if $slider == 1 && $no_prod >= $sliderFor}
     </div>
     {/if}
   </div>
 </div>

</div>
{if $slider == 1 && $no_prod >= $sliderFor}
         <div class="customNavigation">
            <a class="btn prev special_prev"></a>
            <a class="btn next special_next"></a>
         </div>
      {/if}
</section>
</div>