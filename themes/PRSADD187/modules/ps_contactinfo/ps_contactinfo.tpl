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
*  @author PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2017 PrestaShop SA
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div class="block-contact footer-block col-xs-12 col-sm-4 links wrapper hb-animate-element right-to-left">
  
   		<h3 class="text-uppercase block-contact-title hidden-md-down"><a href="{$urls.pages.stores}">{l s='Contact us' d='Shop.Theme.Global'}</a></h3>
      
		<div class="title h3 hidden-lg-up" data-target="#block-contact_list" data-toggle="collapse">
		  <span class="">{l s='Contact us' d='Shop.Theme.Global'}</span>
		  <span class="pull-xs-right">
			  <span class="navbar-toggler collapse-icons">
				<i class="material-icons add">&#xE313;</i>
				<i class="material-icons remove">&#xE316;</i>
			  </span>
		  </span>
		</div>
	  
	  <ul id="block-contact_list" class="collapse">
	  <li>
	  	<i class="material-icons">&#xE55F;</i>
	 <span class="contactdiv"> {$contact_infos.address.formatted nofilter}</span>
	  </li>
	 
      {if $contact_infos.phone}
	   <li>
       
        {* [1][/1] is for a HTML tag. *}
		<i class="material-icons">&#xE324;</i>
        {l s='[1]%phone%[/1]'
          sprintf=[
          '[1]' => '<span>',
          '[/1]' => '</span>',
          '%phone%' => $contact_infos.phone
          ]
          d='Shop.Theme.Global'
        }
		</li>
      {/if}
      {if $contact_infos.fax}
	   <li>
       
        {* [1][/1] is for a HTML tag. *}
        {l
          s='Fax: [1]%fax%[/1]'
          sprintf=[
            '[1]' => '<span>',
            '[/1]' => '</span>',
            '%fax%' => $contact_infos.fax
          ]
          d='Shop.Theme.Global'
        }
		</li>
      {/if}
      {if $contact_infos.email}
	  <li>
       
        {* [1][/1] is for a HTML tag. *}
		<i class="material-icons">&#xE554;</i>
        {l
          s='[1]%email%[/1]'
          sprintf=[
            '[1]' => '<a href="mailto:'|cat:$contact_infos.email|cat:'" class="dropdown">',
            '[/1]' => '</a>',
            '%email%' => $contact_infos.email
          ]
          d='Shop.Theme.Global'
        }
       
		</li>
      {/if}
	  </ul>
  
</div>