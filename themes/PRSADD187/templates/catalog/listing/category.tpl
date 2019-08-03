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
{extends file='catalog/listing/product-list.tpl'}

{block name='product_list_header'}
    <div class="block-category card card-block ">
    {if $category.id eq 4 || $category.id eq 10 || $category.id eq 11 || $category.id eq 5 || $category.id eq 3  }
	 <STYLE type="text/css">
						.precio {
							font: 10px sans-serif;
						}
						
						.axis path,
						.axis line, 
						.axis1 path,
						.axis1 line {
							fill: none;
							stroke: #E6E7E8;
							shape-rendering: crispEdges;
						}
						
						.x.axis path, .x.axis1 path {
							display: none;
						}
						
						.line {
							fill: none;
							stroke-width: 1.5px;
						}
						
						.legend-box {
							cursor: pointer;  
						}
						
						#mouse-tracker {
							stroke: #E6E7E8;
							stroke-width: 1px;
						}
						
						.hover-line { 
							stroke: #E6E7E8;
							fill: none;
							stroke-width: 1px;
							left: 10px;
							shape-rendering: crispEdges;
							opacity: 1e-6;
						}
						
						.hover-text {
							stroke: none;
							font-size: 30px;
							font-weight: bold;
							fill: #000000;
						}
						
						.tooltip {
							font-weight: normal;
						}
						
						.brush .extent {
							stroke: #FFF;
							shape-rendering: crispEdges;
						}
	</STYLE>


	<script>
        const datos={$datos|@json_encode nofilter};
		console.log({$datos});
    </script>
	<div class="category-cover">
	<div class="precio">
	</div>
		</div>
    <script src="../js/d3.v3.min.js"></script>
    <script src="../js/precios.js"></script>

	
	<h1 class="h1">{$category.name}</h1>

	{else}
		<div class="category-cover">
			<img src="{$category.image.large.url}" alt="{$category.image.legend}">
		</div>
		<h1 class="h1">{$category.name}</h1>
		{if $category.description}
			<div id="category-description" class="text-muted">{$category.description nofilter}</div>
		{/if}
		{if isset($cate_seller_ratting) AND !empty($cate_seller_ratting)}
			<div><center>{$cate_seller_ratting}</center></div>
		{/if}
    </div>
	 {/if}

{/block}
