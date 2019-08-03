{**
 * 2007-2017 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
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
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
 {block name='product_miniature_item'}
  <article class="product-miniature js-product-miniature" data-id-product="{$product.id_product}" data-id-product-attribute="{$product.id_product_attribute}" itemscope itemtype="http://schema.org/Product">
  <div class="thumbnail-container">
    {block name='product_thumbnail'}
        {if $product.cover}
      <a href="{$product.url}" class="thumbnail product-thumbnail">
        <img
          src = "{$product.cover.bySize.home_default.url}"
            alt = "{if !empty($product.cover.legend)}{$product.cover.legend}{else}{$product.name|truncate:30:'...'}{/if}"
          data-full-size-image-url = "{$product.cover.large.url}"
        >
       {if count($product.images) > 1 }
         {hook h="displayTmHoverImage" id_product=$product.id_product home='home_default' large='large_default'}
       {/if}
      </a>
        {else}
          <a href="{$product.url}" class="thumbnail product-thumbnail">
            <img
              src = "{$urls.no_picture_image.bySize.home_default.url}"
            >
          </a>
        {/if}
    {/block}
	
	
		
	{block name='product_flags'}
	  <ul class="product-flags">
		{foreach from=$product.flags item=flag}
		  <li class="{$flag.type}">{$flag.label}</li>
		{/foreach}
	  </ul>
	{/block}
  

  {block name='product_buy'}
      {if !$configuration.is_catalog}       
        <div class="product-actions-main">
            <form action="{$urls.pages.cart}" method="post" class="add-to-cart-or-refresh">
            <input type="hidden" name="token" value="{$static_token}">
            <input type="hidden" name="id_product" value="{$product.id}" class="product_page_product_id">
            <input type="hidden" name="id_customization" value="0" class="product_customization_id">
            <button class="btn btn-primary add-to-cart" data-button-action="add-to-cart" type="submit" {if $product.availability == 'unavailable'}disabled{/if} data-toggle="tooltip" title="Add to cart">
              {l s='Add to cart' d='Shop.Theme.Actions'}
            </button>

          </form>

          {block name='quick_view'}
            <a class="quick-view" href="#" data-link-action="quickview">
             <i class="material-icons search"></i> <!-- {l s='Quick view' d='Shop.Theme.Actions'} --> 
            </a>
          {/block}
        </div>
      {/if}
    {/block}

      {block name='product_reviews'}
        {hook h='displayProductListReviews' product=$product}
      {/block}
		
 </div>

    <div class="product-description">

      {* Product Seller Information 
      {block name='product-seller'}
        {if isset($product.seller)}
          <p class="agile_sellername_onlist">
            {l s='Seller:' mod='agilemultipleseller'}
            {if isset($product.has_sellerlink) AND $product.has_sellerlink ==1}<a href="{$link->getAgileSellerLink({$product.id_seller})}">{/if}
            {$product.seller}
            {if isset($product.has_sellerlink)}</a>{/if}
          </p>
        {/if}
      {/block}
      *}

      {block name='product_name'}
        <span class="h2 product-title" itemprop="name"><a href="{$product.url}">{$product.name|truncate:100:'...'}</a></span>
        {*$product|@print_r*}
      {/block}


      {block name='product_price_and_shipping'}
        {if $product.show_price}
          <div class="product-price-and-shipping">


            {hook h='displayProductPriceBlock' product=$product type="before_price"}

            <span itemprop="price" class="price">{$product.price}/{if $product.id_tipo eq "1"}Kg{else}{l s='Item' d='Shop.Theme.Actions'}{/if}</span>
            {if $product.has_discount}
              {hook h='displayProductPriceBlock' product=$product type="old_price"}
            {if $product.has_discount}
              {hook h='displayProductPriceBlock' product=$product type="old_price"}
              {if $product.discount_type === 'percentage'}
              <div class="discount_type_flag">
                <span class="discount-percentage">{$product.discount_percentage}</span>
              </div>
              {/if}
            {/if}
              <span class="regular-price">{$product.regular_price}</span>
            {/if}
            {hook h='displayProductPriceBlock' product=$product type='unit_price'}

            {hook h='displayProductPriceBlock' product=$product type='weight'}
          </div>
        {/if}
      {/block}
    	
		
		
	<div class="highlighted-informations{if !$product.main_variants} no-variants{/if} hidden-sm-down">
	     
		  {block name='product_variants'}
			{if $product.main_variants}
			  {include file='catalog/_partials/variant-links.tpl' variants=$product.main_variants}
			{/if}
		  {/block}
		</div>

    </div>
  </article>
{/block}
