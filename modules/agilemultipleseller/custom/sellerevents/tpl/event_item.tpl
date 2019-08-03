<div class="row event-container">
	<div class="col-md-2">
		{if !empty($logo)}
			<img src="{$logo}">
		{/if}
		<p class="add-by">{l s='Added by' mod='agilemultipleseller'} {if !empty($company)}{$company}{else}apps4erp{/if} <br/> on {date("d.m.Y H:i:s",strtotime($create_date))}</p>
	</div>
	<div class="col-md-8">
		<h3 class="event-title">
			<a href="{$href}">
				{$title}
			</a>
		</h3>
		{$content}
		
		<br>
		
		{if !empty($pdffile_url)} See PDF file in details: <a href="{$pdffile_url}">{$pdffile_url}</a>{/if}
		<div style="clear: both;"></div>
		
	</div>
	<div style="clear: both;"></div>
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-2">
			</div>
			<div class="col-xs-3">
				<p><strong>{l s='Place' mod='agilemultipleseller'}: {$place}</strong></p>
			</div>
			<div class="col-xs-3">
				<p><strong>{l s='Start date' mod='agilemultipleseller'}: {$start_date}</strong></p>
			</div>
			<div class="col-xs-3">
				<p><strong>{l s='End date' mod='agilemultipleseller'}: {$end_date}</strong></p>
			</div>
		</div>
		<div class="row">
			<div class="col-md-10 footer-event"></div>
		</div>
	</div>
</div>

