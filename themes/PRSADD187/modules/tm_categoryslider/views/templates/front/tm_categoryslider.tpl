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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2017 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div id="tmcategorytabs" class="tabs products_block clearfix"> 
	 <div class="hb-animate-element top-to-bottom"><div class="title-before"><h2 class="h1 products-section-title">{l s='Category Product' d='Shop.Theme.Global'}</h2> </div></div>
	 <div class="hb-animate-element right-to-left">
	 <div class="nav-tab-contentnav">
	 	
	<ul id="tmcategory-tabs" class="nav nav-tabs clearfix tmcategory-tabs-carousel mobiletab">
		
		{$count=0}
		{foreach from=$tmcategorysliderinfos item=tmcategorysliderinfo}
			<li class="nav-item">
				<a href="#tab_{$tmcategorysliderinfo.id}" data-toggle="tab" class="nav-link {if $count == 0}active{/if}">{$tmcategorysliderinfo.name}</a>
			</li>
			{$count= $count+1}
		{/foreach}
		
	</ul>
	<ul id="tmcategory-tabs" class="nav nav-tabs clearfix desktoptab">
		
		{$count=0}
		{foreach from=$tmcategorysliderinfos item=tmcategorysliderinfo}
			<li class="nav-item">
				<a href="#tab_{$tmcategorysliderinfo.id}" data-toggle="tab" class="nav-link {if $count == 0}active{/if}">{$tmcategorysliderinfo.name}</a>
			</li>
			{$count= $count+1}
		{/foreach}
		
	</ul>
	<div class="customNavigation tabpane-nav">
			<a class="btn prev tabpane_prev">&nbsp;</a>
			<a class="btn next tabpane_next">&nbsp;</a>
		</div>
	</div>

	</div>
	<div class="hb-animate-element left-to-right">
		<div id="spe_res">
	<div class="tab-content">
		{$tabcount=0}
		{foreach from=$tmcategorysliderinfos item=tmcategorysliderinfo}
			<div id="tab_{$tmcategorysliderinfo.id}" class="tab-pane {if $tabcount == 0}active{/if}">
				{if isset($tmcategorysliderinfo.product) && $tmcategorysliderinfo.product}

					{assign var='sliderFor' value=5}
					{assign var='productCount' value=count($tmcategorysliderinfo.product)}
					
					{if isset($tmcategorysliderinfo.cate_id) && $tmcategorysliderinfo.cate_id}
                        {if $tmcategorysliderinfo.id == $tmcategorysliderinfo.cate_id.id_category}
                            <div class="categoryimage">
                                <img src="{$image_url}/{$tmcategorysliderinfo.cate_id.image}" alt="" class="category_img"/>
                            </div>
                        {/if}
                    {/if}
					
					<div class="products">
						{if $slider == 1 && $productCount >= $sliderFor}
							
						
							<ul id="tmcategory{$tmcategorysliderinfo.id}-carousel" class="tm-carousel product_list product_slider_grid" data-catid="{$tmcategorysliderinfo.id}">
						{else}
							<ul id="tmcategory{$tmcategorysliderinfo.id}" class="product_list grid product_slider_grid" data-catid="{$tmcategorysliderinfo.id}">
						{/if}
						
							{foreach from=$tmcategorysliderinfo.product item='product'}
								
								<li class="{if $slider == 1 && $productCount >= $sliderFor}item{else}product_item col-xs-12 col-sm-6 col-md-4 col-lg-3{/if}">
								{include file="catalog/_partials/miniatures/product.tpl" product=$product}
								</li>
								
							{/foreach}
							{if $slider == 0}
			<li class="loadmore">
				<div class="tm-message"><i class="material-icons">&#xE811;</i>{l s='No more products found!' d='Shop.Theme.Global'}</div>
				<button class="btn btn-default gridcount">{l s='view more products' d='Shop.Theme.Global'}</button>
			</li>
		{/if}
						</ul>
										
					</div>
					
				{else}
					<div class="alert alert-info">{l s='No Products in current tab at this time.' d='Shop.Theme.Global'}</div>
				{/if}
			</div> 
		{$tabcount= $tabcount+1}
		{/foreach}
	</div>
	{if $slider == 1 && $productCount >= $sliderFor}
		<div class="customNavigation">
			<a class="btn prev tmcategory_prev">&nbsp;</a>
			<a class="btn next tmcategory_next">&nbsp;</a>
		</div>
	{/if}
	</div> 
</div>

</div>
