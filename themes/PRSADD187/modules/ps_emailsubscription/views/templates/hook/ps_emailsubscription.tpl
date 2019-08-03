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

<div class="block_newsletter links col-lg-12 col-md-12 col-sm-12">

  <div class="title h3 block_title hidden-lg-up" data-target="#block_email_toggle" data-toggle="collapse">
  <span class="">News Letter</span>
  <span class="pull-xs-right">
    <span class="navbar-toggler collapse-icons">
    <i class="material-icons add">&#xE313;</i>
    <i class="material-icons remove">&#xE316;</i>
    </span>
  </span>
  </div>

 
    <div class="newsletter_title">
      <div class="col-md-5 col-xs-12 title">{l s='sign up newsletter' d='Shop.Theme.Global'}</div>
      <span class="subtitle">{l s='We will keep you informed of your markets of interest.' d='Shop.Theme.Global'}</span>
    </div>
    <div class="col-md-7 col-xs-12 collapse" id="block_email_toggle">
      <form action="{$urls.pages.index}#footer" method="post">
   
          <div class="col-xs-12">
            <input
              class="btn btn-primary pull-xs-right hidden-xs-down"
              name="submitNewsletter"
              type="submit"
              value="{l s='Subscribe' d='Shop.Theme.Actions'}"
            >
            <input
              class="btn btn-primary pull-xs-right hidden-sm-up"
              name="submitNewsletter"
              type="submit"
              value="{l s='OK' d='Shop.Theme.Actions'}"
            >
            <div class="input-wrapper">
              <input
                name="email"
                type="email"
                value="{$value}"
                placeholder="{l s='Enter your email address' d='Shop.Forms.Labels'}"
              >
            </div>
            <input type="hidden" name="action" value="0">
            <div class="clearfix"></div>
          </div>
          <div class="col-xs-12">
              {if $conditions}
                <!-- <p>{$conditions}</p> -->
              {/if}
              {if $msg}
                <p class="alert {if $nw_error}alert-danger{else}alert-success{/if}">
                  {$msg}
                </p>
              {/if}
          </div>
        </div>
      </form>
  
</div>
