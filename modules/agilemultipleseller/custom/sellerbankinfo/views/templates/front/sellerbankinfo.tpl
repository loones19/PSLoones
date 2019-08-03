{capture name=path}<a href="{$link->getPageLink('my-account.php')}">{l s='My Account' mod='agilemultipleseller'}</a><span class="navigation-pipe">{$navigationPipe}</span>{l s='My Seller Account'  mod='agilemultipleseller'}{/capture}
<div id="agile">
<div class="panel">
<h1>{l s='My Seller Account' mod='agilemultipleseller'}</h1>
{include file="$tpl_dir./errors.tpl"}
{include file="$agilemultipleseller_views./templates/front/seller_tabs.tpl"}

{if isset($seller_exists) AND $seller_exists}
    <form action="{$link->getModuleLink('agilemultipleseller', 'sellerbankinfo', [], true)}" enctype="multipart/form-data" method="post" class="form-horizontal std">
    <h3>{l s='Your bank information' mod='agilemultipleseller'}</h3>
    <input type="hidden" name="token" value="{$token}" />
	<input type="hidden" name="id_sellerbankinfo" value="{$sellerbankinfo->id|intval}" />
	<input type="hidden" name="verify_passwd_encrypt" value="{$verify_passwd_encrypt}" />

	{if !empty($sellerbankinfo->passwd) && $sellerbankinfo->passwd != $verify_passwd_encrypt}
		{l s='Bank Info is password protected. Please enter password to access the bank info.' mod='agilemultipleseller'}<br><br>
		<div class="form-group">
			<label class="control-label agile-col-sm-3 agile-col-md-3 agile-col-lg-3 agile-col-xl-3" for="verify_passwd">
				<span>
					{l s='Password' mod='agilemultipleseller'}
				</span>
			</label>
			<div class="agile-col-sm-7 agile-col-md-7 agile-col-lg-7 agile-col-xl-7">
				<input type="password" name="verify_passwd" value="">
			</div>
		</div>
		<div class="form-group agile-align-center">
			<button type="submit" class="agile-btn agile-btn-default" name="submitPassword" value="{l s='Submit' mod='agilemultipleseller'}">
			<i class="icon-save"></i>&nbsp;<span>{l s='Save' mod='agilemultipleseller'}</span></button>
		</div>
		
	{else}
		<div class="form-group">
			<label class="control-label agile-col-sm-3 agile-col-md-3 agile-col-lg-3 agile-col-xl-3" for="id_seller">
				<span>
					{l s='Seller ID' mod='agilemultipleseller'}
				</span>
			</label>
			<div class="agile-col-sm-7 agile-col-md-7 agile-col-lg-7 agile-col-xl-7">
				<input type="text" name="id_seller" readonly value="{$sellerbankinfo->id_seller}">
			</div>
		</div>

		<div class="form-group">
			<label class="control-label agile-col-sm-3 agile-col-md-3 agile-col-lg-3 agile-col-xl-3" for="shop_name">
				<span>
					{l s='Shop Name' mod='agilemultipleseller'}
				</span>
			</label>
			<div class="agile-col-sm-7 agile-col-md-7 agile-col-lg-7 agile-col-xl-7">
				<input type="text" name="shop_name" value="{$sellerbankinfo->shop_name}">
			</div>
		</div>

		<div class="form-group">
			<label class="control-label agile-col-sm-3 agile-col-md-3 agile-col-lg-3 agile-col-xl-3" for="business_name">
				<span>
					{l s='Business Name' mod='agilemultipleseller'}
				</span>
			</label>
			<div class="agile-col-sm-7 agile-col-md-7 agile-col-lg-7 agile-col-xl-7">
				<input type="text" name="business_name" value="{$sellerbankinfo->business_name}">
			</div>
		</div>

		{*
		<div class="form-group">
			<label class="control-label agile-col-sm-3 agile-col-md-3 agile-col-lg-3 agile-col-xl-3" for="business_address1">
				<span>
					{l s='Business Address 1' mod='agilemultipleseller'}
				</span>
			</label>
			<div class="agile-col-sm-7 agile-col-md-7 agile-col-lg-7 agile-col-xl-7">
				<input type="text" name="business_address1" value="{$sellerbankinfo->business_address1}">
			</div>
		</div>

		<div class="form-group">
			<label class="control-label agile-col-sm-3 agile-col-md-3 agile-col-lg-3 agile-col-xl-3" for="business_address2">
				<span>
					{l s='Business Address 2' mod='agilemultipleseller'}
				</span>
			</label>
			<div class="agile-col-sm-7 agile-col-md-7 agile-col-lg-7 agile-col-xl-7">
				<input type="text" name="business_address2" value="{$sellerbankinfo->business_address2}">
			</div>
		</div>
		*}

		<div class="form-group">
			<label class="control-label agile-col-sm-3 agile-col-md-3 agile-col-lg-3 agile-col-xl-3 required" for="account_name">
				<span>
					{l s='Beneficiary Account Name' mod='agilemultipleseller'}
				</span>
			</label>
			<div class="agile-col-sm-7 agile-col-md-7 agile-col-lg-7 agile-col-xl-7">
				<input type="text" name="account_name" value="{$sellerbankinfo->account_name}">
			</div>
		</div>

		<div class="form-group">
			<label class="control-label agile-col-sm-3 agile-col-md-3 agile-col-lg-3 agile-col-xl-3 required" for="bank_name">
				<span>
					{l s='Bank Name' mod='agilemultipleseller'}
				</span>
			</label>
			<div class="agile-col-sm-7 agile-col-md-7 agile-col-lg-7 agile-col-xl-7">
				<input type="text" name="bank_name" value="{$sellerbankinfo->bank_name}">
			</div>
		</div>

		<div class="form-group">
			<label class="control-label agile-col-sm-3 agile-col-md-3 agile-col-lg-3 agile-col-xl-3 required" for="bank_address">
				<span>
					{l s='Bank Address' mod='agilemultipleseller'}
				</span>
			</label>
			<div class="agile-col-sm-7 agile-col-md-7 agile-col-lg-7 agile-col-xl-7">
				<input type="text" name="bank_address" value="{$sellerbankinfo->bank_address}">
			</div>
		</div>

		<div class="form-group">
			<label class="control-label agile-col-sm-3 agile-col-md-3 agile-col-lg-3 agile-col-xl-3 required" for="account_number">
				<span>
					{l s='Bank Account No.' mod='agilemultipleseller'}
				</span>
			</label>
			<div class="agile-col-sm-7 agile-col-md-7 agile-col-lg-7 agile-col-xl-7">
				<input type="text" name="account_number" value="{$sellerbankinfo->account_number}">
			</div>
		</div>
		<hr>
		<div class="form-group">
			<label class="control-label agile-col-sm-3 agile-col-md-3 agile-col-lg-3 agile-col-xl-3" for="passwd">
			</label>
			<div class="agile-col-sm-7 agile-col-md-7 agile-col-lg-7 agile-col-xl-7">
				{if empty($sellerbankinfo->passwd)}
					{l s='You can set a password to protect this bank info' mod='agilemultipleseller'}<br>
					{l s='You will need to enter password first before you can see bank info on this tab once you set password protection.' mod='agilemultipleseller'}
				{else}
					{l s='A password has been set to protect this bank info.' mod='agilemultipleseller'}<br>
					{l s='Leave it empty if you do not want to change password. Or please enter new password and old password to make change.' mod='agilemultipleseller'}
				{/if}
			</div>
		</div>

		<div class="form-group" style="display:{if !empty($sellerbankinfo->passwd)}{else}none;{/if}">
			<label class="control-label agile-col-sm-3 agile-col-md-3 agile-col-lg-3 agile-col-xl-3" for="passwd_old">
				<span>
					{l s='Old Password' mod='agilemultipleseller'}
				</span>
			</label>
			<div class="agile-col-sm-7 agile-col-md-7 agile-col-lg-7 agile-col-xl-7">
				<input type="password" name="passwd_old" value="">
			</div>
		</div>


		<div class="form-group">
			<label class="control-label agile-col-sm-3 agile-col-md-3 agile-col-lg-3 agile-col-xl-3" for="passwd">
				<span>
					{l s='Password' mod='agilemultipleseller'}
				</span>
			</label>
			<div class="agile-col-sm-7 agile-col-md-7 agile-col-lg-7 agile-col-xl-7">
				<input type="password" name="passwd" value="">
			</div>
		</div>
		<div class="form-group">
			<label class="control-label agile-col-sm-3 agile-col-md-3 agile-col-lg-3 agile-col-xl-3" for="passwd2">
				<span>
					{l s='Password Input Confirmation' mod='agilemultipleseller'}
				</span>
			</label>
			<div class="agile-col-sm-7 agile-col-md-7 agile-col-lg-7 agile-col-xl-7">
				<input type="password" name="passwd2" value="">
			</div>
		</div>


		<div class="form-group">
			<label class="control-label agile-col-sm-3 agile-col-md-3 agile-col-lg-3 agile-col-xl-3" for="required_hints">
				<span>
				</span>
			</label>
			<div class="agile-col-sm-7 agile-col-md-7 agile-col-lg-7 agile-col-xl-7">
				<span class="label-tooltip pull-right" name="required_hints"> <sup>*</sup> {l s='Required field' mod='agilemultipleseller'}</span>
			</div>
		</div>
		<div class="form-group agile-align-center">
			<button type="submit" class="agile-btn agile-btn-default" name="submitSellerBankinfo" value="{l s='Save' mod='agilemultipleseller'}">
			<i class="icon-save"></i>&nbsp;<span>{l s='Save' mod='agilemultipleseller'}</span></button>
		</div>
	{/if}
</form>

{/if}
</div> <!-- panel -->
</div> <!-- bootstrap -->
{include file="$agilemultipleseller_views./templates/front/seller_footer.tpl"}

