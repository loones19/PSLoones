{extends file='page.tpl'}

{block name='page_content'}

	<h2>{l s='Awaiting feedback' mod='agilesellerratings'}</h2>
	{if $agile_feedbacks}
	<h3>{l s='Please leave feedback for the following transactions:' mod='agilesellerratings'}</h3>
	<div class="table-responsive clearfix">
		<table class="table">
			<thead>
			<tr>
			<th>{l s='Order No' mod='agilesellerratings'}</th>
			<th>{l s='Purchase Date' mod='agilesellerratings'}</th>
			<th>{l s='Seller Name' mod='agilesellerratings'}</th>
			<th>{l s='Action' mod='agilesellerratings'}</th>
			</tr>
			</thead>
			<tbody>
			{foreach from=$agile_feedbacks item=feedback}
				<tr>
					<td> {$feedback.id_order}</td>
					<td> {$feedback.date_add}</td>
					<td> {$feedback.company}</td>
					<td><input type="button" name="feedback" value="{l s='Leave Feedback' mod='agilesellerratings'}" onclick="javascript:showRatingForm({$feedback.id_order},{$feedback.id_owner})" class="btn btn-primary" /></td>
				</tr>
			{/foreach}
			</tbody>								
		</table>
    

	  <div >
		<form action="" method="post" onsubmit="return validateRatingForm()" class="std" id="sendRatingForm" style="display:none;">
			<input type="hidden" name="rating_id_order" id="rating_id_order" value="0" />
			<input type="hidden" name="rating_id_owner" id="rating_id_owner" value="0" />
			<fieldset>
			  <div class="row">
			  <div class="agile-colxs-12 agile-col-sm-8 agile-col-md-6 agile-col-lg-5 agile-col-xl-4">
				<p class="bold">{l s='Please leave your feedback' mod='agilesellerratings'}</p>
				<table class="std">

				  {section loop=$criterions name=i start=0 step=1}
				  <input type="hidden" name="{$criterions[i].id_agile_rating_criterion}_rating_grade" id="{$criterions[i].id_agile_rating_criterion}_rating_grade" value="0" />
				  <tr>
					<td>{$criterions[i].name}:</td>
					{section loop=6 step=1 start=1 name=img_review}
					<td width="18px">
					  <div style="width:16px;height:16px; border:solid 0px red; overflow:hidden;">
						<img src="{$base_dir_ssl}modules/agilesellerratings/img/star.png" name="stars_{$criterions[i].id_agile_rating_criterion}" style="margin:0px 0px 0px 0px;cursor:pointer" id="stars_{$criterions[i].id_agile_rating_criterion}_{$smarty.section.img_review.index}" title="{$criterions[i].id_agile_rating_criterion}_rating_grade" alt="{$smarty.section.img_review.index}" />
					  </div>
					</td>
					{/section}
				  </tr>
				  {/section}
				</table>
			  </div>
			  </div>
			  <div class="row">
				<div class="agile-colxs-11 agile-col-sm-11 agile-col-md-6 agile-col-lg-5 agile-col-xl-4">
				  <p class="bold">{l s='Your comment' mod='agilesellerratings'}</p>
				  <p>
					<textarea rows="6" name="content" id="content" style="width:100%;"></textarea>
				  </p>
				  <p class="submit">
					<button type="submit" name="submitFeedback" class="button agile-btn agile-btn-default">
					  <span>
						{l s='Send' mod='agilesellerratings'}&nbsp;<i class="icon-chevron-right right"></i>
					  </span>
					</button>
				  </p>
				</div>
			  </div>
			</fieldset>
		</form>
	  </div>  
    
    
	</div>
	{else}
	<p class="align_center">{l s='There are currently no transactions for which to leave feedback.' mod='agilesellerratings'}</p>
	{/if}

{/block}

{block name='javascript_bottom'}
    {$smarty.block.parent}  

{/block}
