{capture name=path}<a href="{$link->getPageLink('my-account', true)}">{l s='My Account' mod='agilemultipleseller'}</a><span class="navigation-pipe">{$navigationPipe}</span>{l s='My Seller Account'  mod='agilemultipleseller'}{/capture}

<h1>{l s='My Seller Account' mod='agilemultipleseller'}</h1>

<script type="text/javascript">
	var msgDelete = "Do you want delete this event?";

	
	function onClickConfirm(act)

	{

		var msg = '';

		if(act == "delete")msg = msgDelete;

		else msg = msgDuplicate;



		if (confirm(msg))

		{ 

			return true; 

		}

		else

		{

			event.stopPropagation(); 

			event.preventDefault();

		}

	}

</script>

{include file="$tpl_dir./errors.tpl"}


{include file="$agilemultipleseller_views./templates/front/seller_tabs.tpl"}

{if isset($isSeller) AND $isSeller}
<div id="agile">

	<div class="block-center clearfix" id="block-history">
		<div class="row">
			<div class="agile-col-sm-2">

				<a class="agile-btn agile-btn-default" href="{$link->getModuleLink('agilemultipleseller', 'sellereventdetail', ['id_event' =>0], true)}">
						<i class="icon-plus-sign"></i>&nbsp;{l s='Add Event' mod='agilemultipleseller'}
				</a>

			</div>
		</div>
	</div>

	<div class="block-center" id="block-history">

	    {if $events && count($events)}

		{include file="$tpl_dir./pagination.tpl"}

		<div class="table-responsive clearfix">
			<table id="order-list" class="table">
	    		<thead>

			        <tr>

				        <th class="first_item">{l s='Event' mod='agilemultipleseller'}</th>

				        <th class="item">{l s='Title' mod='agilemultipleseller'}</th>

				        <th class="item">{l s='Start date' mod='agilemultipleseller'}</th>

				        <th class="item">{l s='End date' mod='agilemultipleseller'}</th>

				        <th class="item">{l s='Date Created' mod='agilemultipleseller'}</th>

				        <th class="item">{l s='Place' mod='agilemultipleseller'}</th>				        

				        <th class="item" style="width:80px">{l s='Active' mod='agilemultipleseller'}</th>

				        <th class="item"></th>

				        <th class="last_item" style="width:5px">&nbsp;</th>

			        </tr>

		        </thead>

		        <tbody>
		        	 {foreach from=$events item=event name=myLoop}

			        <tr class="{if $smarty.foreach.myLoop.first}first_item{elseif $smarty.foreach.myLoop.last}last_item{else}item{/if} {if $smarty.foreach.myLoop.index % 2}alternate_item{/if}">

				        <td class="history_link bold">

					        <a class="color-myaccount" href="{$link->getModuleLink('agilemultipleseller', 'sellereventdetail', ['id_event' => $event.id_event], true)}">{l s='#' mod='agilemultipleseller'}{$event.id_event}</a>

				        </td>

				        <td>{$event.title}</td>

				        <td>{$event.start_date}</td>

				        <td>{$event.end_date}</td>

				        <td>{$event.create_date}</td>

				        <td>{$event.place}</td>

				        <td class="center">

				            {if $event.active == 1}

								<a href="{$link->getModuleLink('agilemultipleseller', 'sellerevents', ['process' => 'inactive', 'id_event'=>$event.id_event,'p'=>$p], true)}" ><img src="{$base_dir_ssl}img/admin/enabled.gif" /></a>

				            {else}

								<a href="{$link->getModuleLink('agilemultipleseller', 'sellerevents', ['process' => 'active', 'id_event'=>$event.id_event,'p'=>$p], true)}" ><img src="{$base_dir_ssl}img/admin/disabled.gif" /></a>

				            {/if}

				        </td>

				        <td class="history_detail">
				        	<a href="{$link->getModuleLink('agilemultipleseller', 'sellereventdetail', ['id_event' => $event.id_event], true)}" title="{l s='Edit' mod='agilemultipleseller'}">
				        		<i class="icon-edit"></i>
				        	</a>
				        	&nbsp;
				        	<a href="{$link->getModuleLink('agilemultipleseller', 'sellerevents', ['process' => 'delete', 'id_event'=>$event.id_event], true)}" title="{l s='Delete' mod='agilemultipleseller'}" onclick="onClickConfirm('delete')">
				        		<i class="icon-trash"></i>
				        	</a>
				        </td>
				        <td></td>
			        </tr>
			         {/foreach}
		        </tbody>

	    	</table>
	    </div>
	    {/if}
	</div>

</div>
{/if}


{include file="$agilemultipleseller_views./templates/front/seller_footer.tpl"}