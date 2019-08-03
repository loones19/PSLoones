{extends file="helpers/form/form.tpl"}
{block name="input"}
	{if $input.type == 'placeholder'}
		<div class="panel col-sm-12 col-md-6 col-lg-6">
			<div id="{$input.name}"></div>
			<div class="panel-footer" style="text-align:center">
				<button type="submit" value="1" id="{$input.submit_name}" name="{$input.submit_name}" class="btn btn-default">
					<i class="process-icon-save"></i> {$input.submit_label}
				</button>
			</div>
		</div>   
	{elseif $input.type == 'criterions'}
		<div class="panel col-sm-12 col-md-6 col-lg-6">
		<table class="table">
		<thead><tr>
		<th>{$input.title_actions}</th><th>{$input.title_Criterion}</th>
		</tr></thead>
		<tbody>
		{foreach $input.values as $criterion}
		<tr><td>
			<a href="javascript:;" onclick="fill_criteria_edit_form({$criterion.id});" class="edit btn btn-default">
				<i class="icon-pencil"></i>{$input.edit_action}
			</a>
			<a href="javascript:;" onclick="deleteCriterion({$criterion.id},'{$input.delete_warning_msg}');" class="delete  btn btn-default">
				<i class="icon-trash"></i>{$input.delete_action}
			</a>
		</td><td>{$criterion.name}</td></tr>
		{/foreach}
		</tbody>
		</table>
		</div>   
	{else}
		{$smarty.block.parent}
	{/if}
{/block}
