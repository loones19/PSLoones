{*
* 2007-2016 PrestaShop
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
*  @copyright 2007-2016 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div  class="tmcategorylist">

	<div class="tmcategory-container hb-animate-element left-to-right">

			<!-- <h2 class="h1 products-section-title ">{l s='Shop by category' d='Shop.Theme.Global'}</h2> -->
		{if isset($tmcategoryinfos) && $tmcategoryinfos}
		
		{assign var='sliderFor' value=4}
		{assign var='productCount' value=count($tmcategoryinfos)}
		{$categorycount=0}
		{if  $productCount >= $sliderFor}
							<div class="customNavigation">
								<a class="btn prev cat_prev">&nbsp;</a>
								<a class="btn next cat_next">&nbsp;</a>
							</div>
		{/if}

		{if $productCount >= $sliderFor}
							<ul id="tmcategorylist-carousel" class="tm-carousel product_list product_slider_grid">
						{else}
							<ul id="tmcategorylist" class="product_list grid row gridcount product_slider_grid">
						{/if}
						
			{foreach from=$tmcategoryinfos item=tmcategoryinfo}
				<li>
                <div class="categoryblock{$categorycount} categoryblock item">
					<div class="block_content">
						
						<div class="categoryimage_bg">
							<div class="categoryimage">
								<br/>
								<h1 style="color:green;">
								{if $tmcategoryinfo.name == "Virgen Extra"}
									2.445,96
								{elseif $tmcategoryinfo.name == "Virgen"}
									2.028,19
								{elseif $tmcategoryinfo.name == "Lampante"}
									1.880,65
								{elseif $tmcategoryinfo.name == "Tinto"}
									264,1
								{elseif $tmcategoryinfo.name == "Blanco"}
									374,7
								{elseif $tmcategoryinfo.name == "Blando Panificable"}
									1.912,1
								{elseif $tmcategoryinfo.name == "Cáscara"}
									1.799,7
								{elseif $tmcategoryinfo.name == "Pienso"}
									1.780,7
								{else}
									0.0
								{/if}
								<span><i class="material-icons">&#xe8e5;</i></span></h1>
								<p>(€/T)</p>
							</div>
						</div>
						{*$datoscat|@debug_print_var*}
                		{* LOONES 13/05/2019 - Temporal Changes
						{if isset($tmcategoryinfo.cate_id) && $tmcategoryinfo.cate_id}
							{if $tmcategoryinfo.id == $tmcategoryinfo.cate_id.id_category}
							<div class="categoryimage_bg">
								<div class="categoryimage">
								{foreach from=$datoscat item=datos}
									$datos|@print_r
									$tmcategoryinfo.id
									{if $datos.categoria eq $tmcategoryinfo.id}
										<br />
										
										{if $datos.sube }
											<h1 style="color:green;">{$datos['valor']}<span><i class="material-icons">&#xe8e5;</i></span></h1>
											
										{else}
											<h1 style="color:red;">{$datos['valor']}<span><i class="material-icons">&#xe8e3;</i> </span></h1>
										
										{/if}
											<p>(€/100 kg)</p>							
									{/if}
								{/foreach }
				
								</div>
							</div>


							{/if}
						{/if}
						*}
						<div class="categorylist">
							<div class="cate-heading">
								<a href="{$link->getCategoryLink($tmcategoryinfo.category->id_category, $tmcategoryinfo.category->link_rewrite)}">{$tmcategoryinfo.name}</a>
							</div>
                            <ul class="subcategory">
							{$categorychildcount = 1}
                            {foreach $tmcategoryinfo.child_cate item=child}
								{if $categorychildcount <=10}
                                <li>
									<a href="{$link->getCategoryLink({$child.id_category},{$child.link_rewrite})}">{$child.name}</a>
								</li>

                                 {/if}
                                 {$categorychildcount = $categorychildcount + 1}
							{/foreach}
							<li>
								<a href="{$link->getCategoryLink($tmcategoryinfo.category->id_category, $tmcategoryinfo.category->link_rewrite)}">
									{l s='View all' mod='tmcategorylist'}</a>
							</li>
						</ul>
						</div>
							<div class="cate-description">
								{$tmcategoryinfo.description|truncate:130:'...' nofilter}
							</div>
					</div>
				
				</div>
				</li>
               
				{$categorycount = $categorycount + 1}
			{/foreach}
			</ul>
			
			
		{else}
			<div class="alert alert-info">{l s='No Category is Selected.' mod='tmcategorylist'}</div>
		{/if}
	
</div>
</div>
