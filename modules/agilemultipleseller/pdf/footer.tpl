<table>
	<tr>
		<td style="text-align: center; font-size: 6pt; color: #444">
			{if $available_in_your_account}
				{l s='An electronic version of this invoice is available in your account. To access it, log in to our website using your e-mail address and password (which you created when placing your first order).' pdf='true'}
				<br />
			{/if}
			{$shop_address|escape:'html':'UTF-8'}<br />

			{if !empty($shop_phone) OR !empty($shop_fax)}
				{l s='For more assistance, contact Support:' pdf='true'}<br />
				{if !empty($shop_phone)}
					{l s='Tel: %s' sprintf=[$shop_phone|escape:'html':'UTF-8'] pdf='true'}
				{/if}

				{if !empty($shop_fax)}
					{l s='Fax: %s' sprintf=[$shop_fax|escape:'html':'UTF-8'] pdf='true'}
				<br />
				{/if}
			{/if}
			
			{if isset($shop_details)}
				{$shop_details|escape:'html':'UTF-8'}<br />
			{/if}

			{if isset($free_text)}
				{$free_text|escape:'html':'UTF-8'}<br />
			{/if}

			{* === from here Seller info === *}
			{if isset($seller_name) && !empty($seller_name)}
				{$seller_name} - 
				{$seller_address|escape:'htmlall':'UTF-8'}<br />

				{if !empty($seller_phone) OR !empty($seller_fax)}
					{if !empty($seller_phone)}
						Tel: {$seller_phone|escape:'htmlall':'UTF-8'}
					{/if}

					{if !empty($seller_fax)}
						Fax: {$seller_fax|escape:'htmlall':'UTF-8'}
					{/if}
					<br />
				{/if}
			{/if}
			{* === end seller info === *}
		</td>
	</tr>
</table>

