{**
 * 2007-2017 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
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
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2017 PrestaShop SA
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}

{block name='product_miniature_item'}

<div class="product-miniature1 js-product-miniature" data-id-product="{$product.id_product}" data-id-product-attribute="{$product.id_product_attribute}" itemscope itemtype="http://schema.org/Product">
   
   <div class="thumbnail-container col-sm-12 col-md-5">
    
      <div class="special_block">
      
       <div class="image-block">
         {block name='product_thumbnail'}
         <a href="{$product.url}" class="thumbnail product-thumbnail">
          <img
            class = "primary-image js-modal-product-cover product-cover-modal"
             src = "{$product.cover.bySize.special_default.url}"
            alt = "{$product.cover.legend}"
            data-full-size-image-url = "{$product.cover.large.url}"
             title="{$product.cover.legend}" 
            >     

         </a>
         {/block} 
      </div>
   
      {block name='product_flags'}
      <ul class="product-flags">
         {foreach from=$product.flags item=flag}
         <li class="{$flag.type}">{$flag.label}</li>
         {/foreach}
      </ul>
      {/block}  
        
   </div>
   <div class="product-description col-sm-12 col-md-7">
  
      {block name='product_name'}
      <span class="h3 product-title" itemprop="name"><a href="{$product.url}" title="{$product.name}">{$product.name|truncate:35:'...'}</a></span>
      {/block}

       <!-- <div class="product-detail" itemprop="description">{$product.description|truncate:200:'...' nofilter}</div> -->


      


       <div class="highlighted-informations{if !$product.main_variants} no-variants{/if} hidden-sm-down">
         {block name='product_variants'}
         {if $product.main_variants}
         {include file='catalog/_partials/variant-links.tpl' variants=$product.main_variants}
         {/if}
         {/block}
      </div>

       
      {block name='product_description_short'}
      {if $product.description}
     
      {/if}
      {/block}  


      {block name='product_price_and_shipping'}
    
      {if $product.show_price}
      <div class="product-price-and-shipping">
         <span itemprop="price" class="price">{$product.price}</span>
         {if $product.has_discount}
              {hook h='displayProductPriceBlock' product=$product type="old_price"}
              {if $product.discount_type === 'percentage'}
                <span class="discount-percentage">{$product.discount_percentage}</span>
              {/if}
        {/if}
           {if $product.has_discount}
                  {hook h='displayProductPriceBlock' product=$product type="old_price"}               
              <span class="sr-only">{l s='Regular price' d='Shop.Theme.Catalog'}</span>
                  <span class="regular-price">{$product.regular_price}</span>
                {/if}
         {hook h='displayProductPriceBlock' product=$product type="before_price"}           
         {hook h='displayProductPriceBlock' product=$product type='unit_price'}
         {hook h='displayProductPriceBlock' product=$product type='weight'}
      </div>
      {/if}
      {/block}
      
      {block name='product_reviews'}
        {hook h='displayProductListReviews' product=$product}
      {/block}

      
<div id="product-description-offer">{l s='Hurry Up! Offer Ends in' d='Shop.Theme.Global'}</div>
      {hook h='PSProductCountdown' id_product=$product.id_product} 


   {block name='product_buy'}
    {if !$configuration.is_catalog}       
      <div class="product-actions-main">
          <form action="{$urls.pages.cart}" method="post" class="add-to-cart-or-refresh">
          <input type="hidden" name="token" value="{$static_token}">
          <input type="hidden" name="id_product" value="{$product.id}" class="product_page_product_id">
          <input type="hidden" name="id_customization" value="0" class="product_customization_id">
          <button class="btn btn-primary add-to-cart" data-button-action="add-to-cart" type="submit" {if $product.availability == 'unavailable'}disabled{/if} title="{l s='Add to cart' d='Shop.Theme.Actions'}">           
        {l s='Add to cart' d='Shop.Theme.Actions'}
           
          </button>
        </form>
        <a href="#" class="quick-view" data-link-action="quickview" title="{l s='Quick View' d='Shop.Theme.Actions'}">
          </i>{l s='Quick view' d='Shop.Theme.Actions'}
          <span class="pst-product-quickview pst-product-tooltip">{l s='Quick View' d='Shop.Theme.Actions'}</span>
       </a>  
      </div>
    {/if}
  {/block}

       
</div>
</div>

</div>
{/block}