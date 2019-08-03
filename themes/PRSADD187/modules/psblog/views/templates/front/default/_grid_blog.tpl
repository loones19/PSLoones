{**
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
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2017 PrestaShop SA
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
* International Registred Trademark & Property of PrestaShop SA
*}

<article class="blog-item">
    {if $config->get('listing_show_title','1')}
			<h4 class="title"><a href="{$blog.link|escape:'html':'UTF-8'}" title="{$blog.title|escape:'html':'UTF-8'}">{$blog.title|escape:'html':'UTF-8'}</a></h4>
	{/if}

	{if $blog.image && $config->get('listing_show_image',1)}
		<div class="blog-image">
			<img src="{$blog.preview_url|escape:'html':'UTF-8'}" title="{$blog.title|escape:'html':'UTF-8'}" alt="" class="img-fluid" />
			<span class="blogicons">
		       <a title="Click to view Full Image" href="{$blog.preview_url|escape:'html':'UTF-8'}" data-lightbox="example-set" class="icon zoom"></a> 
    	       <a title="Click to view Read More" href="{$blog.link|escape:'html':'UTF-8'}" class="icon readmore_link"></a>
	        </span>
		</div>
		
	{/if}
	
	
	<div class="blog-meta">
		{if $config->get('listing_show_author','1')&&!empty($blog.author)}
		<span class="blog-author">
			<i></i>{l s='Posted By' d='Modules.PsBlog'}: 
			<a href="{$blog.author_link|escape:'html':'UTF-8'}"  class="fa fa-user" title="{$blog.author|escape:'html':'UTF-8'}"> {$blog.author|escape:'html':'UTF-8'}</a> 
		</span>
		{/if}
		
		{if $config->get('listing_show_category','1')}
		<span class="blog-cat"> 
			<i class="fa fa-list"></i>{l s='In' d='Modules.PsBlog'}: 
			<a href="{$blog.category_link|escape:'html':'UTF-8'}" title="{$blog.category_title|escape:'html':'UTF-8'}">{$blog.category_title|escape:'html':'UTF-8'}</a>
		</span>
		{/if}
		
		{if $config->get('listing_show_created','1')}
		<span class="blog-created">
			<i class="fa fa-calendar"></i>{l s='On' d='Modules.PsBlog'}: 
			<time class="date" datetime="{strtotime($blog.date_add)|date_format:"%Y"|escape:'html':'UTF-8'}">
				{l s=strtotime($blog.date_add)|date_format:"%A"|escape:'html':'UTF-8' d='Modules.PsBlog'},	<!-- day of week -->
				{l s=strtotime($blog.date_add)|date_format:"%B"|escape:'html':'UTF-8' d='Modules.PsBlog'}		<!-- month-->
				{l s=strtotime($blog.date_add)|date_format:"%e"|escape:'html':'UTF-8' d='Modules.PsBlog'},	<!-- day of month -->
				{l s=strtotime($blog.date_add)|date_format:"%Y"|escape:'html':'UTF-8' d='Modules.PsBlog'}		<!-- year -->
			</time>
		</span>
		{/if}
		
		{if isset($blog.comment_count)&&$config->get('listing_show_counter','1')}	
		<span class="blog-ctncomment">
			<i class="fa fa-comments-o"></i> {l s='Comment' d='Modules.PsBlog'}:
			{$blog.comment_count|intval}
		</span>
		{/if}

		{if $config->get('listing_show_hit','1')}	
		<span class="blog-hit">
			<i class="fa fa-heart"></i>{l s='Hit' d='Modules.PsBlog'}: 
			{$blog.hits|intval}
		</span>
		{/if}
	</div>
	
	<div class="blog-content-wrap">
		
		<div class="blog-shortinfo">
			{if $config->get('listing_show_description','1')}
			{$blog.description|strip_tags:'UTF-8'|truncate:160:'...' nofilter}{* HTML form , no escape necessary *}
			{/if}
			{if $config->get('listing_show_readmore',1)}
				<div class="readmore">
					<a href="{$blog.link|escape:'html':'UTF-8'}" title="{$blog.title|escape:'html':'UTF-8'}" class="btn">{l s='Read more' d='Modules.PsBlog'}</a>
				</div>
			{/if}
		</div>

	</div>

</article>
	
<!---
	Translation Day of Week - NOT REMOVE
	{l s='Sunday' d='Modules.PsBlog.Shop'}
	{l s='Monday' d='Modules.PsBlog.Shop'}
	{l s='Tuesday' d='Modules.PsBlog.Shop'}
	{l s='Wednesday' d='Modules.PsBlog.Shop'}
	{l s='Thursday' d='Modules.PsBlog.Shop'}
	{l s='Friday' d='Modules.PsBlog.Shop'}
	{l s='Saturday' d='Modules.PsBlog.Shop'}
-->
<!---
	Translation Month - NOT REMOVE
		{l s='January' d='Modules.PsBlog.Shop'}
		{l s='February' d='Modules.PsBlog.Shop'}
		{l s='March' d='Modules.PsBlog.Shop'}
		{l s='April' d='Modules.PsBlog.Shop'}
		{l s='May' d='Modules.PsBlog.Shop'}
		{l s='June' d='Modules.PsBlog.Shop'}
		{l s='July' d='Modules.PsBlog.Shop'}
		{l s='August' d='Modules.PsBlog.Shop'}
		{l s='September' d='Modules.PsBlog.Shop'}
		{l s='October' d='Modules.PsBlog.Shop'}
		{l s='November' d='Modules.PsBlog.Shop'}
		{l s='December' d='Modules.PsBlog.Shop'}
-->