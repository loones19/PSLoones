<table width="100%" border="0">
		<tr><td style="height:10px;"></td></tr>
		<tr><td width="25%">
	        <label for="bags_received">{l s='Bags received' mod='agilemultipleseller'}:&nbsp;</label>
		</td><td colsoan="2">
	        <span id="bags_received">{$bags_received}</span>
		</td></tr>
		<tr><td style="height:10px;"></td></tr>
		<tr><td>
	        <label for="bags_sent">{l s='Bags sent' mod='agilemultipleseller'}:&nbsp;</label>
		</td><td width="10%">
			<span id="bags_sent">{$bags_sent}</span>
		</td><td>
			<input type="button" id="btnShowOrderbagsForm" onclick="toggle_order_bags_form()" class="btn btn-primary" value="{l s='Order Bags' mod='agilemultipleseller'}">
		</td></tr>
		<tr><td style="height:10px;"></td></tr>
		<tr><td>
		</td><td colspan="3">
			<form action="{$link->getModuleLink('agilemultipleseller', 'sellersummary', [], true)}" method="post" id="frmOrderBagsForm">
			<div id="orderbags_form" style="padding:15px;;display:none;border:solid 1px green;">
			<input type="hidden" name="submitBagsRequest" id="submitRequest_orderbags" value="1">
			<h3>{l s='Bags Order Form' mod='agilemultipleseller'}</h3>
			{include file="module:agilemultipleseller/custom/selllersummarybag/sellerinformation.tpl"}
			<center>
				<input type="button" class="btn btn-primary" name="btnCancelOrderBags" id="btnCancelOrderBags" onclick="orderbags_cancelclick()" value="{l s='Cancel' mod='agilemultipleseller'}">&nbsp;
				<input type="button" class="btn btn-primary" name="btnSubmitOrderBags" id="btnSubmitOrderBags" onclick="orderbags_submitclick()" value="{l s='Submit' mod='agilemultipleseller'}">&nbsp;
			</center>
			</div>
			</form>
			<script language="javascript" type="text/javascript">
        var order_bags_form_visible = false;
        function toggle_order_bags_form()
        {
        if(order_bags_form_visible)
        {
        $("div#orderbags_form").hide();
        }
        else
        {
        $("div#orderbags_form").show();
        }
        order_bags_form_visible = !order_bags_form_visible;
        }

        function orderbags_cancelclick()
        {
        $("div#orderbags_form").hide();
        order_bags_form_visible = false;
        }

        function orderbags_submitclick()
        {
        var answer = window.confirm('{l s='Are you sure want to order bags? The bag will be sent to you in 1-2 days'  mod='agilemultipleseller'}');
        if(answer)
        {
        $("form#frmOrderBagsForm").submit();
        }
        }
      </script>
		</td></tr>
	</table>

