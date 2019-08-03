{capture name=path}<a href="{$link->getPageLink('my-account', true)}">{l s='My Account' mod='agilemultipleseller'}</a><span class="navigation-pipe">{$navigationPipe}</span>{l s='My Seller Account'  mod='agilemultipleseller'}{/capture}


<h1>{l s='My Seller Account' mod='agilemultipleseller'}</h1>

{include file="$tpl_dir./errors.tpl"}

<script type="text/javascript">

    var base_dir = "{$base_dir_ssl}";

</script>

<script type="text/javascript">

        var msg_select_one = "{l s='Please select at least one product.' mod='agilemultipleseller' js=1}";
        var msg_set_quantity = "{l s='Please set a quantity to add a product.' mod='agilemultipleseller' js=1}";


        {if isset($ps_force_friendly_product) && $ps_force_friendly_product}
        var ps_force_friendly_product = 1;
        {else}
        var ps_force_friendly_product = 0;
        {/if}


        {if isset($PS_ALLOW_ACCENTED_CHARS_URL) && $PS_ALLOW_ACCENTED_CHARS_URL}
        var PS_ALLOW_ACCENTED_CHARS_URL = 1;
        {else}
        var PS_ALLOW_ACCENTED_CHARS_URL = 0;
        {/if}
       

      var iso = "{$isoTinyMCE}";

      var pathCSS = "{$theme_css_dir}";

      var ad = "{$ad}";


      var currentmenuid = 0;


    </script>

    <script type="text/javascript">

    $(document).ready(function() {

    tinySetup(

    {

    selector: ".rte" ,

    toolbar1 : "code,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,|,blockquote,colorpicker,pasteword,|,bullist,numlist,|,outdent,indent,|,link,unlink,|,cleanup"

    });



    $("#available_for_order").click(function(){

    if ($(this).is(':checked'))

    {

    $('#show_price').attr('checked', 'checked');

    $('#show_price').attr('disabled', 'disabled');

    }

    else

    {

    $('#show_price').removeAttr('disabled');

    }

    });

    });



    function changeMyLanguage(field, fieldsString, id_language_new, iso_code)

    {

    changeLanguage(field, fieldsString, id_language_new, iso_code);

    $("img[id^='language_current_']").attr("src","{$base_dir}img/l/" + id_language_new + ".jpg");

    }

    $(document).ready(function(){
        //populate_attrs();
        $(".datepicker").datepicker({
            prevText: '',
            nextText: '',
            dateFormat: 'yy-mm-dd'
        });
    });
  </script>



<script language="javascript" type="text/javascript">

    $(document).ready(function() {

        $(".checker").removeClass("checker");



        $("[id^='cancellink_']").click(function() {

            return confirm("{l s='Are you sure want to cancel selected options?' mod='agilemultipleseller'}");

        });

    }); 

</script>

{if isset($check_product_association_ajax) && $check_product_association_ajax}

{assign var=class_input_ajax value='check_product_name '}

{else}

{assign var=class_input_ajax value=''}

{/if}



{include file="$agilemultipleseller_views./templates/front/seller_tabs.tpl"}

<br />

{if !is_null($event)}
  <div id="agile">
    <div class="panel">
		<div class="row">
        <form id="event_form" name="event" action="{$link->getModuleLink('agilemultipleseller', 'sellereventdetail', ['id_event'=>$id_event], true)}" 

            enctype="multipart/form-data" method="post" class="form-horizontal agile-col-md-9 agile-col-lg-10 agile-col-xl-10">
            <div id="event-informations" class="panel event-tab">

              <input type="hidden" name="action" value="save_event" />
              <input type="hidden" name="active" value="{$event->active}" />

              <h3 class="tab">{l s='' mod='agilemultipleseller'}</h3>

              {* Title *}

              <div class="form-group">

                <label class="control-label agile-col-md-3 agile-col-lg-3 agile-col-xl-3 required" for="title_{$id_language}">

                  <span class="label-tooltip" data-toggle="tooltip"

                            title="{l s='Invalid characters:' mod='agilemultipleseller'} &lt;&gt;;=#{}">

                    {l s='Title:' mod='agilemultipleseller'}

                  </span>

                </label>

                <div class="agile-col-md-7 agile-col-lg-5 agile-col-xl-5">

                  {include file="$agilemultipleseller_views/templates/front/products/input_text_lang.tpl"

                  languages=$languages

                  input_class="{$class_input_ajax}{if !$event->id || Configuration::get('PS_FORCE_FRIENDLY_PRODUCT')}copy2friendlyUrl{/if} updateCurrentText"

                  input_value=$event->title

                  input_name='title'

                  }

                </div>

              </div>

                
                {* Start Date *}

                <div class="form-group">

                    <label class="control-label agile-col-md-3 agile-col-lg-3 agile-col-xl-3 required" for="start_date">

                      <span>

                        {l s='Start date' mod='agilemultipleseller'}

                      </span>

                    </label>

                    <div class="agile-col-md-7 agile-col-lg-5 agile-col-xl-5">

                      <div class="row">

                        <div class="agile-col-md-9 agile-col-lg-9 agile-col-xl-9">

                          <input class="datepicker" size="255" type="text" name="start_date" value="{if !empty($event->start_date)}{date('Y-m-d',strtotime($event->start_date))}{/if}" class="form-control" required="true" autocomplete="off" readonly="true" />

                        </div>

                      </div>

                    </div>

                </div>

                {* End Date *}

                <div class="form-group">

                    <label class="control-label agile-col-md-3 agile-col-lg-3 agile-col-xl-3 required" for="end_date">

                      <span>

                        {l s='End date' mod='agilemultipleseller'}

                      </span>

                    </label>

                    <div class="agile-col-md-7 agile-col-lg-5 agile-col-xl-5">

                      <div class="row">

                        <div class="agile-col-md-9 agile-col-lg-9 agile-col-xl-9">

                          <input class="datepicker" size="255" type="text" name="end_date" value="{if !empty($event->end_date)}{date('Y-m-d',strtotime($event->end_date))}{/if}" class="form-control" required="true" autocomplete="off" readonly="true" />

                        </div>

                      </div>

                    </div>

                </div>

                {* Place *}

                <div class="form-group">

                    <label class="control-label agile-col-md-3 agile-col-lg-3 agile-col-xl-3 required" for="place">

                      <span>

                        {l s='Place' mod='agilemultipleseller'}

                      </span>

                    </label>

                    <div class="agile-col-md-7 agile-col-lg-7 agile-col-xl-7">

                      <div class="row">

                        <div class="agile-col-md-9 agile-col-lg-9 agile-col-xl-9">

                          <input size="255" type="text" name="place" value="{$event->place}" class="form-control"  required="true" />

                        </div>

                      </div>

                    </div>

                </div>

                {* description *}

                  <div class="form-group">

                    <label class="control-label agile-col-md-3 agile-col-lg-3 agile-col-xl-3 required" for="description_{$id_language}">

                      <span class="label-tooltip" data-toggle="tooltip"

                        title="{l s='Appears in the body of the event page' mod='agilemultipleseller'}">

                        {l s='Description:' mod='agilemultipleseller'}

                      </span>

                    </label>

                    <div class="agile-col-md-9 agile-col-lg-9 agile-col-xl-9">

                      {include file="$agilemultipleseller_views/templates/front/products/textarea_lang.tpl"

                        languages=$languages

                        input_name='description'

                        input_value=$event->description

                        default_row=10

                        class="rte"

                        max=400}

                    </div>

                  </div>
				  
                {* PDF file *}
				<div class="form-group">
					<label class="control-label agile-col-sm-3 agile-col-md-3 agile-col-lg-3 agile-col-xl-3 pdf_upload_label">
						<span class="label-tooltip" data-toggle="tooltip"
							title="{l s='Format:' mod='agilemultipleseller'} PDF">
						{l s='Add a PDF file'  mod='agilemultipleseller'}
						</span>
					</label>
					<div class="agile-col-md-9 agile-col-lg-9 agile-col-xl-9">
					   <div class="row">
							<div class="agile-col-sm-12 agile-col-md-12 agile-col-lg-9 agile-col-xl-9">
								<input type="file" name="pdffile" id="pdffile" accept=".pdf" class="form-control" />
								<br>
								{assign var="pdffile_url" value=$event->get_event_pdffile_url()}
								{if !empty($pdffile_url)}
								<a href="{$pdffile_url}" target="_new">{$pdffile_url}</a>&nbsp;&nbsp;<button type="submit" class="agile-btn agile-btn-default" name="deletePDF"><i class="icon-remove"></i>&nbsp;</button >
								{/if}
							</div>
						</div>
					</div>
				</div>				  
				  
                <div class="form-group agile-align-center">

                <button type="submit" class="agile-btn agile-btn-default" name="submitEvent" value="{l s='Save' mod='agilemultipleseller'}">

                <i class="icon-save "></i>&nbsp;<span>{l s='Save' mod='agilemultipleseller'}</span></button >

               </div>

               {*$default_language*}

                <script type="text/javascript">

                  hideOtherLanguage({$id_language});

                </script>
            </div>        
        </form>
        </div>
    </div>
  </div>
{/if}

{include file="$agilemultipleseller_views./templates/front/seller_footer.tpl"}