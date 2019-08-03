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

<!-- Block links module -->
<div id="links_block_top" class="block links">
	<h3 class="h3 title_block ">
		<i class="material-icons"></i>
	</h3>
	
		
	<ul id="tm_toplink" class="block_content">
	{foreach from=$tm_toplink_links item=tm_toplink_link}
		{if isset($tm_toplink_link.$lang)} 
			<li>
				<a href="{$tm_toplink_link.url}" title="{$tm_toplink_link.$lang}" {if $tm_toplink_link.newWindow} onclick="window.open(this.href);return false;"{/if}>{$tm_toplink_link.$lang}</a></li>
		{/if}
	{/foreach}
	</ul>
</div>
<!-- /Block links module -->
