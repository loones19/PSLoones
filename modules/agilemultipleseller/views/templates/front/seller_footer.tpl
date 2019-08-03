{if $isSeller && $seller_back_office}
<form action="{$base_dir_ssl}{$admin_folder_name}/index.php?controller=adminlogin" id="seller_login_form" method="post">
<input type="hidden" name="ams_seller_email" value="{$selleremail}" />
<input type="hidden" name="ams_seller_token" value="{$sellertoken}" />
<input type="hidden" name="seller_login" value="1" />
<input type="hidden" name="submitLogin" value="1" />
</form>
{/if}

<ul class="footer_links clearfix">
    <li><a href="{$base_dir_ssl}"><i class="icon-home"></i>&nbsp;{l s='Home' mod='agilemultipleseller'}</a></li>
    {if $is_multiple_shop_installed}
    <li><a href="{$link->getAgileSellerLink($sellerinfo->id_seller,'')}"><i class="icon-home"></i>&nbsp;{l s='My Virtual Shop' mod='agilemultipleseller'}</a></li>
    {/if}
    <li><a href="{$link->getPageLink('my-account', true)}"><i class="icon-circle-arrow-left"></i>&nbsp;{l s='My Account' mod='agilemultipleseller'}</a></li>
    {if $isSeller && $seller_back_office}
      <li><a href="#" onclick="seller_login()" title="{l s='Access Seller Account from back office' mod='agilemultipleseller'}"><i class="icon-user"></i>&nbsp;{l s='My Back office' mod='agilemultipleseller'}</a></li>
    {/if}
</ul>

