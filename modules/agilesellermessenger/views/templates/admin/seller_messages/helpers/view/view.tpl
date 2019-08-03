{*
*}
{extends file="helpers/view/view.tpl"}

{block name="override_tpl"}
 <form action="?controller=AdminSellerMessages&id_agile_sellermessage={$the_message->id}&token={$token}" method="post" enctype="multipart/form-data">
	<input type="hidden" name="id_agile_sellermessage" value="{$the_message->id}" />
	<input type="hidden" name="id_seller" value="{$the_message->id_seller}">

	<fieldset><legend><img src="../img/admin/nav-user.gif" />{l s='Message Details' mod='agilesellermessenger'}</legend>
		<label>{l s='From name:' mod='agilesellermessenger'}</label>
		<div class="margin-form">{$the_message->from_name}</div>
		{if !$hide_email OR !$is_seller}
			<label>{l s='From email' mod='agilesellermessenger'}</label>
			<div class="margin-form">{$the_message->from_email}</div>
		{/if} 

		<label>{l s='Date' mod='agilesellermessenger'}</label>
		<div class="margin-form">{$product->date_add}</div>

		<label>{l s='Product' mod='agilesellermessenger'}</label>
		<div class="margin-form">{$product->name}</div>

		<label>{l s='IP address:' mod='agilesellermessenger'}</label>
		<div class="margin-form">
			{$the_message->ip_address}
		</div><div class="clear">&nbsp;</div>
		<label>{l s='Subject' mod='agilesellermessenger'}</label>
		<div class="margin-form">
			{$the_message->subject}
		</div><div class="clear">&nbsp;</div>

		<label>{l s='Message:' mod='agilesellermessenger'}</label>
		<div class="margin-form">
			{$the_message->message|nl2br}
		</div><div class="clear">&nbsp;</div>
		{if !empty($the_message->attpsname1) OR !empty($the_message->attpsname2) OR !empty($the_message->attpsname3) }
		<label>{l s='Attachments:' mod='agilesellermessenger'}</label>
		<div class="margin-form">
			<a href="index.php?tab=AdminSellerMessages&id_agile_sellermessage={$the_message->id}&viewagile_sellermessage&token={$token}&filename={$the_message->attpsname1}&id_seller={$the_message->id_seller}" title="{l s='View file' mod='agilesellermessenger'}">{$the_message->attshname1}</a><br>
			<a href="index.php?tab=AdminSellerMessages&id_agile_sellermessage={$the_message->id}&viewagile_sellermessage&token={$token}&filename={$the_message->attpsname2}&id_seller={$the_message->id_seller}" title="{l s='View file' mod='agilesellermessenger'}">{$the_message->attshname2}</a><br>
			<a href="index.php?tab=AdminSellerMessages&id_agile_sellermessage={$the_message->id}&viewagile_sellermessage&token={$token}&filename={$the_message->attpsname3}&id_seller={$the_message->id_seller}" title="{l s='View file' mod='agilesellermessenger'}">{$the_message->attshname3}</a><br>
		</div><div class="clear">&nbsp;</div>
		{/if}
			{if $the_message->is_customer_message}		
				<label>{l s='Reply Message:' mod='agilesellermessenger'}</label>
				<div class="margin-form">
				    <textarea name="reply_message" rows="5" cols="80"></textarea>
				</div>
				<label>{l s='Attach File :' mod='agilesellermessenger'}</label>
				<div class="margin-form">
    			    <input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
					<input type="file" size="50" name="fileUpload1" id="fileUpload1" /><br/>
					<input type="file" size="50" name="fileUpload2" id="fileUpload2" /><br/>
					<input type="file" size="50" name="fileUpload3" id="fileUpload3" />
				</div>	
				<center>
					<input type="submit" value="{l s='  Reply  ' mod='agilesellermessenger'}" name="submitReply" class="btn btn-primary" />
				</center><div class="clear">&nbsp;</div>
			{else}
				<center>
					<input type="submit" value="{l s='   OK   ' mod='agilesellermessenger'}" name="submitView" class="btn btn-primary" />
				</center><div class="clear">&nbsp;</div>
			{/if}
	</fieldset>
</form>
{/block}
