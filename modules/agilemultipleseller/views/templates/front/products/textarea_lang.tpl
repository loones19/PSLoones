{*
* 2007-2013 PrestaShop
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
*  @copyright  2007-2013 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{foreach from=$languages item=language}
<div class="translatable-field lang-{$language.id_lang}">
	<div class="agile-col-sm-9 agile-col-md-9 agile-col-lg-9 agile-col-xl-9">
		<textarea
			id="{$input_name}_{$language.id_lang}"
			name="{$input_name}_{$language.id_lang}"
			{if isset($default_row) && $default_row>0} row="{$default_row}" {/if}
			class="{if isset($autosize_js)}textarea-autosize{else}{$class}{/if}">{if isset($input_value[$language.id_lang])}{$input_value[$language.id_lang]}{/if}</textarea>
	</div>
{if $languages|count > 1}
	<div class="agile-col-sm-2 agile-col-md-2 agile-col-lg-2 agile-col-xl-2">
		<button type="button" class="agile-btn agile-btn-default agile-dropdown-toggle" data-toggle="agile-dropdown">
			{$language.iso_code}
			<span class="caret"></span>
		</button>
		<ul class="dropdown-menu" role="menu">
			{foreach from=$languages item=language}
			<li><a href="javascript:hideOtherLanguage({$language.id_lang});">{$language.name}</a></li>
			{/foreach}
		</ul>
	</div>
{/if}
</div>
<span class="counter" max="{if isset($max)}{$max}{else}none{/if}"></span>
{/foreach}
