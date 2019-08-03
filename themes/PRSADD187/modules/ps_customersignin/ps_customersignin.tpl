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
 
{* LOONES 01/05/2019 Edit Sign In/Sign Up front.
<div id="_desktop_user_info">
	<div class="tm_userinfotitle">{l s='Sign in' d='Shop.Theme.Actions'}
    <i class="hidden-md-down material-icons expand-more">&#xE5CF;</i>
  </div>
   
  <ul class="user-info">
    {if $logged}
      <a
        class="account"
        href="{$my_account_url}"
        title="{l s='View my customer account' d='Shop.Theme.Customeraccount'}"
        rel="nofollow"
      >
        <i class="material-icons hidden-lg-up logged">&#xE7FF;</i>
        <span class="hidden-md-down">{$customerName}</span>
      </a>
      <a
        class="logout"
        href="{$logout_url}"
        rel="nofollow"
      >
        <i class="material-icons">&#xE7FF;</i>
        {l s='Sign out' d='Shop.Theme.Actions'}
      </a>
    {else}
      <a
        href="{$my_account_url}"
        title="{l s='Log in to your customer account' d='Shop.Theme.Customeraccount'}"
        rel="nofollow"
      >
        <i class="material-icons">&#xE7FF;</i>
        <span class="hidden-md-down">{l s='Sign in' d='Shop.Theme.Actions'}</span>
      </a>
    {/if}
  </ul>
</div>
*}

{if $logged} 
<div id="_desktop_user_info">
  <div class="tm_userinfotitle">{l s='Profile' d='Shop.Theme.Actions'}
    <i class="hidden-md-down material-icons expand-more">&#xE5CF;</i>
  </div>    
  
  <ul class="user-info">
    {if $logged}
      <a
        class="account"
        href="{$my_account_url}"
        title="{l s='View my customer account' d='Shop.Theme.Customeraccount'}"
        rel="nofollow"
      >
        <i class="material-icons hidden-lg-up logged">&#xE7FF;</i>
        <span class="hidden-md-down">{$customerName}</span>
      </a>
      <a
        class="logout"
        href="{$logout_url}"
        rel="nofollow"
      >
        <i class="material-icons">&#xE7FF;</i>
        {l s='Sign out' d='Shop.Theme.Actions'}
      </a>
    {else}
      <a
        href="{$my_account_url}"
        title="{l s='Log in to your customer account' d='Shop.Theme.Customeraccount'}"
        rel="nofollow"
      >
        <i class="material-icons">&#xE7FF;</i>
        <span class="hidden-md-down">{l s='Login' d='Shop.Theme.Actions'}</span>
      </a>
    {/if}
  </ul>
</div>

{else}

<div id="_desktop_user_info">
  <a
        href="{$my_account_url}"
        title="{l s='Log in to your customer account' d='Shop.Theme.Customeraccount'}"
        rel="nofollow"
      >
        <i class="material-icons">vpn_key</i>
        <span class="hidden-md-down">{l s='Login' d='Shop.Theme.Actions'}</span>
      </a>
</div>

<div id="_desktop_user_info">
	<div class="tm_userinfotitle">{l s='Sign up' d='Shop.Theme.Actions'}
    <i class="hidden-md-down material-icons expand-more">&#xE5CF;</i>
  </div>
   
  <ul class="user-info">
    {if $logged}
      <a
        class="account"
        href="{$my_account_url}"
        title="{l s='View my customer account' d='Shop.Theme.Customeraccount'}"
        rel="nofollow"
      >
        <i class="material-icons hidden-lg-up logged">&#xE7FF;</i>
        <span class="hidden-md-down">{$customerName}</span>
      </a>
      <a
        class="logout"
        href="{$logout_url}"
        rel="nofollow"
      >
        <i class="material-icons">&#xE7FF;</i>
        {l s='Sign out' d='Shop.Theme.Actions'}
      </a>
    {else}
      <a
        href="https://loones.es/es/iniciar-sesion?create_account=1"
        title="{l s='Buyer Signup' d='Shop.Theme.Customeraccount'}"
        rel="nofollow"
      >
        <i class="material-icons">&#xE7FF;</i>
        <span class="hidden-md-down">{l s='Buyer Signup' d='Shop.Theme.Customeraccount'}</span>
      </a>
    {/if}
  </ul>
</div>

{/if}