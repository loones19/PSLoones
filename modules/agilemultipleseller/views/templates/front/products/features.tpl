{if isset($product->id)}
<div id="product-features" class="panel product-tab">
  <input type="hidden" name="submitted_tabs[]" value="Features" />
  <h3>{l s='Assign features to this product:' mod='agilemultipleseller'}</h3>

  <div class="alert alert-info">
    {l s='You can specify a value for each relevant feature regarding this product. Empty fields will not be displayed.' mod='agilemultipleseller'}<br/>
    {l s='You can either create a specific value, or select among the existing pre-defined values you\'ve previously added.' mod='agilemultipleseller'}
  </div>

  <div class="table-responsive">
    <table class="table">
      <thead>
        <tr>
          <th>
            <span class="title_box">{l s='Feature' mod='agilemultipleseller'}</span>
          </th>
          <th>
            <span class="title_box">{l s='Pre-defined value' mod='agilemultipleseller'}</span>
          </th>
          <th>
            <span class="title_box">
              <u>{l s='or' mod='agilemultipleseller'}</u> {l s='Customized value' mod='agilemultipleseller'}
            </span>
          </th>
        </tr>
      </thead>

      <tbody>
        {foreach from=$available_features item=available_feature name=myLoop}
        {assign var="middle" value=($available_features|count)/2}
        <tr>
          <td>{$available_feature.name}</td>
          <td>
            {if sizeof($available_feature.featureValues)}
              <select id="feature_{$available_feature.id_feature}_value" name="feature_{$available_feature.id_feature}_value"
                onchange="$('.custom_{$available_feature.id_feature}_').val('');">
                <option value="0">---</option>
                {foreach from=$available_feature.featureValues item=value}
                <option value="{$value.id_feature_value}" {if $available_feature.current_item == $value.id_feature_value}selected="selected"{/if} >
                {$value.value|truncate:40}
                </option>
                {/foreach}
              </select>
            {else}
              <input type="hidden" name="feature_{$available_feature.id_feature}_value" value="0" />
              <span>
                {l s='N/A'  mod='agilemultipleseller'}
              </span>
            {/if}
          </td>
          <td>
            {foreach from=$all_languages key=k item=language name="language_loop"}
            {if $all_languages|count > 1}
            <div class="row translatable-field lang-{$language.id_lang}">
                  <div class="agile-col-md-9 agile-col-lg-9 agile-col-xl-9">
              {/if}
                    <textarea
                      class="custom_{$available_feature.id_feature}_{$language.id_lang} textarea-autosize"
                      name="custom_{$available_feature.id_feature}_{$language.id_lang}"
                      cols="40"
                      rows="1"
                      onkeyup="if (isArrowKey(event)) return ;$('#feature_{$available_feature.id_feature}_value').val(0);" >{$available_feature.val[$k].value|escape:'html':'UTF-8'|default:""}</textarea>
              {if $all_languages|count > 1}
                  </div>
                  <div class="agile-col-md-3 agile-col-lg-3 agile-col-xl-3 {if $smarty.foreach.myLoop.iteration > $middle} btn-group dropup {/if}">
                    <button type="button" class="agile-btn agile-btn-default agile-dropdown-toggle" data-toggle="agile-dropdown">
                      {$language.iso_code}
                      <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                      {foreach from=$all_languages item=language}
                      <li>
                          <a href="javascript:hideOtherLanguage({$language.id_lang});">{$language.iso_code}</a>
                        </li>
                      {/foreach}
                    </ul>
                  </div>
                </div>
              {/if}
            {/foreach}
          </td>
        </tr>
        {foreachelse}
        <tr>
          <td colspan="3" style="text-align:center;">
            <i class="icon-warning-sign"></i>&nbsp;{l s='No features have been defined' mod='agilemultipleseller'}
          </td>
        </tr>
        {/foreach}
      </tbody>
    </table>
  </div>
  <!-- table-responsive -->
</div> <!-- product-features -->

<div class="form-group agile-align-center">
  <button type="submit" class="agile-btn agile-btn-default" name="submitFeatures" value="{l s='Save' mod='agilemultipleseller'}">
    <i class="icon-save "></i>&nbsp;<span>{l s='Save' mod='agilemultipleseller'}</span>
  </button >
</div>
{/if}
