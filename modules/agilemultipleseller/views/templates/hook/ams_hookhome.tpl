{*
This source file is subject to the Software License Agreement that is bundled with this 
package in the file license.txt, or you can get it here
http://addons-modules.com/en/content/3-terms-and-conditions-of-use

@copyright  2009-2014 Addons-Modules.com
*}
<section  id="agilesellwithus" class="agilesellwithus" style="background-color:white;padding:15px;">
    <h2>{l s='Sell With Us' mod='agilemultipleseller'}</h2>
	<hr>
	<h4 class="title_block">{l s='Welcome to our marketplace' mod='agilemultipleseller'}</h3>
	<div>
		<p>
			{l s='Whether you are a professional with a growing business or an individual selling a few products, we provide you access to tools and features to help you get up and running quickly.' mod='agilemultipleseller'}
		</p>
		<p>
			{l s='Take advantage of our technology such as' mod='agilemultipleseller'}
			<ul>
				<li>{l s='Secure online transaction tools' mod='agilemultipleseller'}</li>
				<li>{l s='Prompt process order system' mod='agilemultipleseller'}</li>
				<li>{l s='World-class customer service' mod='agilemultipleseller'}</li>
				<li>{l s='And much more' mod='agilemultipleseller'}</li>
			</ul>
		</p>
		<p>
			{l s='All these features can help you sell your stuff online, start a business, or add a new sales channel.	' mod='agilemultipleseller'}	
		</p>
	</div>
	<br><br>
	<h3>{l s='Why sell with us?' mod='agilemultipleseller'}</h3>
	<p>
		{l s='Reach millions of customers by selling on our marketplace'  mod='agilemultipleseller'} 
	</p>
	<p>
		{l s='Selling on ur marketplace is an ecommerce service for sellers around the world - to list your products on our marketplace for sale. By selling on the our marketplace, you have the opportunity to reach millions of customers, while providing them with the familiar, trustworthy shopping experience that we are known for.'  mod='agilemultipleseller'}
	</p>

	{if isset($show_sellersignup) && $show_sellersignup== 1}
	<p>
			<a href="{$seller_signup_url}" class="btn btn-primary">{l s='Sign up now' mod='agilemultipleseller'}&nbsp;<i class="icon-chevron-right"></i></a>
	</p>
	{/if}
</section>

