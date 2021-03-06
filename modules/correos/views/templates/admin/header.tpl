{*
* 2015-2016 YDRAL.COM
*
* NOTICE OF LICENSE
*
*  @author YDRAL.COM <info@ydral.com>
*  @copyright 2015-2016 YDRAL.COM
*  @license GNU General Public License version 2
*
* You can not resell or redistribute this software.
*}
 
{foreach from=$paramsBack.JS_FILES item="file"}
    <script type="text/javascript" src="{$file|escape:'htmlall':'UTF-8'}"></script>
{/foreach}
{foreach from=$paramsBack.CSS_FILES item="file"}
    <link type="text/css" rel="stylesheet" href="{$file|escape:'htmlall':'UTF-8'}"/>
    {/foreach}
<script>
   var customer_orders;
   var customer_addresses;
   var customer_id_addresses = [];
   var customer_mail;
   var rma_labels = [{$paramsBack.RMA_LABLES|escape:'htmlall':'UTF-8'}];
   
$(function() {
  senders_json = jQuery.parseJSON( $("#senders_json").val() );
  $('#want-be-client').click(function () {
      $("#CorreosModal h4.modal-title").html("{l s='Customer Application Form' mod='correos'}")
      $("#CorreosModalContent").html($("#correos-intro-form"));
      $("#CorreosModal").modal();
      
   });
   $("#correos-intro-form").validate({
      showErrors: function(errorMap, errorList) {
        $("#correos-intro-form .form-errors").html("<div class='bootstrap'> " +
         "<div class='alert alert-warning text-center'><i class='icon-warning-sign'></i> " +
         "{l s='All fields must be completed before you submit the form' mod='correos'}</div>");
      }
   });
   
   $('#tab-sender .btn-save-general').click(function () {
      $("#tab-sender form.sender").validate({
          rules: {
            sender_nombre: {
              required: { 
                  depends: function(element) {
                      return ($('#tab-sender form.sender input[name="sender_presona_contacto"]').val() == '' ||  $('#tab-sender form.sender input[name="sender_empresa"]').val() == '');
                  }
              }
            },
            sender_apellidos: {
               required: { 
                  depends: function(element) {
                      return ($('#tab-sender form.sender input[name="sender_presona_contacto"]').val() == '' ||  $('#tab-sender form.sender input[name="sender_empresa"]').val() == '');
                  }
              }
            },
            sender_empresa: {
               required: { 
                  depends: function(element) {
                      return ($('#tab-sender form.sender input[name="sender_nombre"]').val() == '' ||  $('#tab-sender form.sender input[name="sender_apellidos"]').val() == '');
                  }
              }
            },
            sender_presona_contacto: {
               required: { 
                  depends: function(element) {
                      return ($('#tab-sender form.sender input[name="sender_nombre"]').val() == '' ||  $('#tab-sender form.sender input[name="sender_apellidos"]').val() == '');
                  }
              }
            },
            sender_dni: {
              required: true
            },
            sender_direccion: {
              required: true
            },
            sender_localidad: {
              required: true
            },
            sender_cp: {
              required: true
            },
            sender_provincia: {
              required: true
            }, 
          },
        showErrors: function(errorMap, errorList) {
          if (errorList.length) {
            $("#tab-sender .sender .form-errors").html("<div class='bootstrap'> " +
             "<div class='alert alert-warning text-center'><i class='icon-warning-sign'></i> " +
             "{l s='All fields must be completed before you submit the form' mod='correos'}</div>");
          } else {
            $("#tab-sender .sender .form-errors").html("");
          }
        },
         errorPlacement: function(error, element) {
          console.log(element);
        }
     });
   });


   /* Sender */
   $("#add_sender, #remove_sender").parent('div').addClass('pull-right');
   
   
   $('#add_sender').click(function () {
        var sender_html = $("#tab-sender .sender").clone();
       $("#CorreosModalContent").html(sender_html);
    
       $("#CorreosModal h4.modal-title").html($(this).html())
       
       $("#CorreosModalContent .sender .form-group:first").remove();
       $("#CorreosModalContent .sender .form-group:first").remove();
       $("#CorreosModalContent .sender .form-group:first").remove();
       $("#CorreosModalContent .sender .form-group:first").remove();
       $('#CorreosModalContent .sender input[type=text]').each(function() {
         $(this).val('');
       })

       $("#CorreosModal").modal();
       $("#CorreosModal form.sender").validate({
       
          rules: {
            sender_nombre: {
              required: { 
                  depends: function(element) {
                      return ($('#CorreosModal form.sender input[name="sender_presona_contacto"]').val() == '' ||  
                      $('#CorreosModal form.sender input[name="sender_empresa"]').val() == '');
                  }
              }
            },
            sender_apellidos: {
               required: { 
                  depends: function(element) {
                      return ($('#CorreosModal form.sender input[name="sender_presona_contacto"]').val() == '' ||  
                      $('#CorreosModal form.sender input[name="sender_empresa"]').val() == '');
                  }
              }
            },
            sender_empresa: {
               required: { 
                  depends: function(element) {
                      return ($('#CorreosModal form.sender input[name="sender_nombre"]').val() == '' ||  
                      $('#CorreosModal form.sender input[name="sender_apellidos"]').val() == '');
                  }
              }
            },
            sender_presona_contacto: {
               required: { 
                  depends: function(element) {
                      return ($('#CorreosModal form.sender input[name="sender_nombre"]').val() == '' ||  
                      $('#CorreosModal form.sender input[name="sender_apellidos"]').val() == '');
                  }
              }
            },
            sender_dni: {
              required: true
            },
            sender_direccion: {
              required: true
            },
            sender_localidad: {
              required: true
            },
            sender_cp: {
              required: true
            },
            sender_provincia: {
              required: true
            }, 
          },
        showErrors: function(errorMap, errorList) {
            if (errorList.length) {
            $("#CorreosModal .sender .form-errors").html("<div class='bootstrap'> " +
           "<div class='alert alert-warning text-center'><i class='icon-warning-sign'></i> " +
           "{l s='All fields must be completed before you submit the form' mod='correos'}</div>");
          } else {
            $("#CorreosModal .sender .form-errors").html('');
          }


        },
         errorPlacement: function(error, element) {
          console.log(element);
        }
     });
     
     /*
     $( "#CorreosModal form.sender input[type='text']" ).blur(function() {
      $("#CorreosModal form.sender").resetForm();
     
    });
*/

  
    });
    $('#remove_sender').click(function () {
      if(confirm("{l s='Are you sure you want to delete selected Sender?' mod='correos'}" + "\n" + $('#select_sender option:selected').text())) {
        return true;
      } else {
        return false;
      }
    });
     $("#CorreosModal").on('hide.bs.modal', function () {
        $('.modal-backdrop').remove();
    });
    
    
    $('#label_print').change(function () {
      if ($("#collection-orders-table tr").length == 0 && $(this).val() == 'S') {
        $("#select-orders-message").show();
      } else {
        $("#select-orders-message").hide();
      }
    });
    $('#select_sender').change(function () {
      var selected_key =  $(this).val();
      jQuery.each(senders_json, function(key, sender) {
            if(key == selected_key) {
              
               $("#sender_nombre").val(sender.nombre);
               $("#sender_apellidos").val(sender.apellidos);
               $("#sender_dni").val(sender.dni);
               $("#sender_empresa").val(sender.empresa);
               $("#sender_presona_contacto").val(sender.presona_contacto);
               $("#sender_direccion").val(sender.direccion);
               $("#sender_localidad").val(sender.localidad);
               $("#sender_cp").val(sender.cp);
               $("#sender_provincia").val(sender.provincia);
               $("#sender_tel_fijo").val(sender.tel_fijo);
               $("#sender_movil").val(sender.movil);
               $("#sender_email").val(sender.email);
               if(sender.sender_default == '1') {
                $("#sender_default_0").attr('checked', false);
                $("#sender_default_1").attr('checked', true);
               } else {
                $("#sender_default_0").attr('checked', true);
                $("#sender_default_1").attr('checked', false);
               }
            }
         });
    });
    $('#select_sender').change();
    
    $('#recipient_sender').change(function () {
      var selected_key =  $(this).val();
      jQuery.each(senders_json, function(key, sender) {
            if(key == selected_key) {
              
               $("#recipient_nombre").val(sender.nombre);
               $("#recipient_apellidos").val(sender.apellidos);
               $("#recipient_dni").val(sender.dni);
               $("#recipient_empresa").val(sender.empresa);
               $("#recipient_presona_contacto").val(sender.presona_contacto);
               $("#recipient_direccion").val(sender.direccion);
               $("#recipient_localidad").val(sender.localidad);
               $("#recipient_cp").val(sender.cp);
               $("#recipient_provincia").val(sender.provincia);
               $("#recipient_tel_fijo").val(sender.tel_fijo);
               $("#recipient_movil").val(sender.movil);
               $("#recipient_email").val(sender.email);
            }
            
         });
    });
    $('#recipient_sender').change();

    $('#collection_sender').change(function () {
      var selected_key =  $(this).val();
      jQuery.each(senders_json, function(key, sender) {
            if(key == selected_key) {
              
                if(sender.nombre != '' && sender.apellidos != '') {
                    $("#collection_req_name").val(sender.nombre + ' ' + sender.apellidos);
                } else {
                    $("#collection_req_name").val(sender.empresa + ' ' + sender.presona_contacto);
                }
               $("#collection_req_address").val(sender.direccion);
               $("#collection_req_city").val(sender.localidad);
               $("#collection_req_postalcode").val(sender.cp);
               $("#collection_req_state").val(sender.provincia);
               $("#collection_req_phone").val(sender.tel_fijo);
               $("#collection_req_mobile_phone").val(sender.movil);
               $("#collection_req_email").val(sender.email);
               
               
            }
            
         });
    });
    $("#collection_sender").change();

    

    /*Search shipping orders*/
   var dateStart = parseDate($("#orderFilter_dateFrom").val());
   var dateEnd = parseDate($("#orderFilter_dateTo").val());
            
   $("#local_orderFilter_dateFrom").datepicker({
      altField:"#orderFilter_dateFrom",
      altFormat: 'yy-mm-dd'
   });
                 
   $("#local_orderFilter_dateTo").datepicker({
      altField:"#orderFilter_dateTo",
      altFormat: 'yy-mm-dd'
   });
                
   if (dateStart !== null){
      $("#local_orderFilter_dateFrom").datepicker("setDate", dateStart);
   }
   if (dateEnd !== null){
      $("#local_orderFilter_dateTo").datepicker("setDate", dateEnd);
   }
   
   /*Collections*/
   var collectionReqDateStart = parseDate($("#collectionFilter_dateFrom").val());
   var collectionReqDateEnd = parseDate($("#collectionFilter_dateTo").val());
   
   $("#collection_date, #collection-detail-date").datepicker();
   
   {if $smarty.now|date_format:"%H" > 13}  
    $("#collection_req_date").datepicker().datepicker('setDate', new Date().getDay+2);
   {else}
    $("#collection_req_date").datepicker().datepicker('setDate', new Date().getDay+1);
   {/if}
    $("#local_collectionFilter_dateFrom").datepicker({
      altField:"#collectionFilter_dateFrom",
      altFormat: 'yy-mm-dd'
   });
                 
   $("#local_collectionFilter_dateTo").datepicker({
      altField:"#collectionFilter_dateTo",
      altFormat: 'yy-mm-dd'
   });
                
   if (collectionReqDateStart !== null){
      $("#local_collectionFilter_dateFrom").datepicker("setDate", collectionReqDateStart);
   }
   if (collectionReqDateEnd !== null){
      $("#local_collectionFilter_dateTo").datepicker("setDate", collectionReqDateEnd);
   }

   
   var collectionDateStart = parseDate($("#collectionDateFilter_dateFrom").val());
   var collectionDateEnd = parseDate($("#collectionDateFilter_dateTo").val());

    $("#local_collectionDateFilter_dateFrom").datepicker({
      altField:"#collectionDateFilter_dateFrom",
      altFormat: 'yy-mm-dd'
   });
                 
   $("#local_collectionDateFilter_dateTo").datepicker({
      altField:"#collectionDateFilter_dateTo",
      altFormat: 'yy-mm-dd'
   });
                
   if (collectionDateStart !== null){
      $("#local_collectionDateFilter_dateFrom").datepicker("setDate", collectionDateStart);
   }
   if (collectionDateEnd !== null){
      $("#local_collectionDateFilter_dateTo").datepicker("setDate", collectionDateEnd);
   }
   
   $('#customer').typeWatch({
			captureLength: 1,
			highlight: true,
			wait: 100,
			callback: function(){ searchCustomers(); }
			});          

   $('#customers').on('click','button.setup-customer',function(e){
			e.preventDefault();
         $("#customer_name").val($(this).data('customer_name'));
         $("#customer_id").val($(this).data('customer'));
         customer_mail = $(this).data('customer_mail');
			setupCustomer($(this).data('customer'));
         
	
			$(this).closest('.customerCard').addClass('selected-customer');

		
			
		});
      
      $('#lastOrders').on('click','.fill_address',function(e){
         e.preventDefault();
         var order_index = $(this).data('index');
         var customer_order = customer_orders[order_index];
         var id_carrier = customer_order.id_carrier;
         var id_address = 0;
         $("#customer_id_order").val(customer_order.id_order);
   
         if (customer_id_addresses.indexOf(customer_order.id_address_delivery) >= 0) {
            id_address = customer_order.id_address_delivery;
               
         } else {
            id_address = customer_order.id_address_invoice;
            
         }
         
     
         $.each(customer_addresses, function() {
            if(id_address == this.id_address) {
              
               $("#customer_sender_nombre").val(this.firstname);
               $("#customer_sender_apellidos").val(this.lastname);
               $("#customer_sender_dni").val(this.dni);
               $("#customer_sender_empresa").val(this.company);
               $("#customer_sender_presona_contacto").val(this.firstname + " " + this.lastname);
               var address = this.address1;
               if(this.address2 != '')
                  address += " " + this.address2;
               $("#customer_sender_direccion").val(address);
               $("#customer_sender_localidad").val(this.city);
               $("#customer_sender_cp").val(this.postcode);
               $("#customer_sender_provincia").val(this.state);
               $("#customer_sender_tel_fijo").val(this.phone);
               $("#customer_sender_movil").val(this.phone_mobile);
               $("#customer_sender_email").val(customer_mail);
               
               $("#rma_form").show();
               $.scrollTo($('#rma_form'), 1000);
            }
         });
               
      });
      $('#lastOrders').on('click','.request_collection',function(e){
         e.preventDefault();
         var order_index = $(this).data('index');
         var customer_order = customer_orders[order_index];
         var id_carrier = customer_order.id_carrier;
         var id_address = 0;
         $("#customer_id_order").val(customer_order.id_order);
   
         if (customer_id_addresses.indexOf(customer_order.id_address_delivery) >= 0) {
            id_address = customer_order.id_address_delivery;
               
         } else {
            id_address = customer_order.id_address_invoice;
            
         }
         
     
         $.each(customer_addresses, function() {
            if(id_address == this.id_address) {
              
               $("#CorreosModalRmaCollection #collection_clientname").val(this.firstname + " " + this.lastname);
               
               var address = this.address1;
               if(this.address2 != '')
                  address += " " + this.address2;
               $("#CorreosModalRmaCollection #collection_address").val(address);
               $("#CorreosModalRmaCollection #collection_city").val(this.city);
               $("#CorreosModalRmaCollection #collection_postalcode").val(this.postcode);
               $("#CorreosModalRmaCollection #collection_state").val(this.state);
               $("#CorreosModalRmaCollection #collection_phone").val(this.phone);
               $("#CorreosModalRmaCollection #collection_mobile_phone").val(this.phone_mobile);
               
            }
         });
         
         $("#CorreosModalRmaCollection").modal();
      });

      $('#request_collection').on('click',function(e){
        
        var shipents = 0;
        $('#correos_orders  .id_order:checked').each(function() {
          var array = $(this).val().split(",");
          shipents = shipents + array.length;
        });
        /*
        if (shipents > 5) {
          alert("{l s='Please select max. 5 shipents' mod='correos'}");
          return false;
        }
      */
        $('#collection-orders-table tr').remove();
        $('#correos_orders  .id_order:checked').each(function() {

          $('#collection-orders-table').append($('<tr>')
            .append($('<td>')
              .append($(this).clone())
             )
            .append($('<td>')
              .append($(this).closest('tr').find("td:nth-child(3)").html())
            )
            .append($('<td>')
              .append($(this).closest('tr').find("td:nth-child(4)").html())
            )
            .append($('<td>')
              .append($(this).closest('tr').find("td:nth-child(5)").html())
            )
            .append($('<td>')
              .append("{l s='Parcels' mod='correos'}:" + $(this).closest('tr').find("td:nth-child(6)").html())
            )
          );
        });
        

        $("#select-orders-message").hide();
        $("#collection_sender").change();
        $('.nav-tabs a[href="#tab-request_collection"]').tab('show');
        /*
          var order_reference = $('.id_order:checked:visible:first').data('reference');
          if(order_reference != 'undefined') {
            $('#collection_reference').val(order_reference);
          }
          $("#CorreosModalCollection").modal();
          */
      });
      
       $("#btn-request_collection").on("click", function(event){

          var valid = true;
          var collection_error = "";
          var arr_orders = [];
          var shipents = 0;
          
          $("#form-request-collection:input.redborder").each(function() {
            $(this).removeClass("redborder");
          });
          
          {literal}
          var cp = /(^([0-9]{5,5})|^)$/;
          {/literal}
          if ($('#collection_req_postalcode').val().substr(0, 2).toLowerCase() != 'ad') {
              if (!(cp.test($('#collection_req_postalcode').val()))) { 
                $('#collection_req_postalcode').addClass("redborder");
                collection_error += "{l s='Postal Code is not valid' mod='correos'}";
              }
          }
          
          $('#collection-orders-table .id_order:checked').each(function(){
              var array = $(this).val().split(",");
              shipents = shipents + array.length;

              var obj = {
                'order_id': $(this).data('orderid'),
                'order_reference': $(this).data('reference'),
                'order_date': $(this).data('orderdate'),
                'order_customer': $(this).data('ordercustomer'),
                'expedition_code': $(this).data('expedition'),
                'shipping_code': $(this).val()
              }
              arr_orders.push(obj);
          });
          $('#orders_collection').val(JSON.stringify(arr_orders));
          var selectedDate = $('#collection_req_date').datepicker('getDate');
          var today = new Date();
          var in_2_days = new Date(today.getFullYear(), today.getMonth(), today.getDate()+2);
          if(Date.parse(selectedDate) <= Date.parse(today)) {
            collection_error = "{l s='Selected date is not valid' mod='correos'}\n";
          }
          if(Date.parse(selectedDate) > Date.parse(today) && Date.parse(selectedDate) <= Date.parse(in_2_days)) {
             if(!confirm("{l s='Requested service is not guaranteed for selected date. Do you wish to continue?' mod='correos'}"))
              return false;

          }

          if ($("#label_print").val() == 'S' && $("#collection-orders-table tr").length == 0) {
            collection_error = "{l s='Please select max. 5 shipents from Search shipping Tab' mod='correos'}\n";
          }
          if ($("#label_print").val() == 'S' && shipents > 5) {
            collection_error = "{l s='If you request label print, the maximum is 5 labels' mod='correos'}\n";
          }

          

          $("#form-request-collection :input.required").each(function() {
              if ($.trim($(this).val()) == "" || $.trim($(this).val()) == "0"  ) {
                  $(this).addClass("redborder");
                  collection_error = "{l s='Please fill out all required fields' mod='correos'}\n";
              }
          });
          if(collection_error) {
           
          }
        
          
           
           
          if(collection_error != '') {
              alert(collection_error);
              return false;
          }
        return true;
			
				
							
    });
    var collection_size = {
       '10':"{l s='Envelopes' mod='correos'}",
       '20':"{l s='Small (box shoes)' mod='correos'}",
       '30':"{l s='Medium (box with packs folios)' mod='correos'}",
       '40':"{l s='Large (box 80x80x80 cm)' mod='correos'}",
       '50':"{l s='Very large (larger than box 80x80x80 cm)' mod='correos'}",
       '60':"Palet",
    };
    $("#btn-collection-detail-cancel").on("click", function() {
        if(!confirm("{l s='Do you wish to cancel Collection' mod='correos'}" + ' ' + $('.collection-id:checked').data('collectioncode') + '?'))
          return false;
    });
    $(".view-collection-details").on("click", function(){
      if (!$('.collection-id:checked').val()) {
        alert("{l s='Please select one collecton' mod='correos'}");
        return false;
      }
      
        /*
        $('#collection-detail-confirmation-code').html($(this).data('confirmation_code'));
        $('#collection-detail-reference').html($(this).data('reference_code'));
        $('#collection-detail-date-requested').html($(this).data('date_requested'));
        $('#form-collection-details .collection-detail-control').attr("readonly", false);
        $('#form-collection-details .collection-detail-control').attr("tabindex", '1');
        if($(this).data('target') == 'export') {
          $('#form-collection-details .collection-detail-control').attr("readonly", true);
          $('#form-collection-details .collection-detail-control').css("pointer-events", 'none');
          $('#form-collection-details .collection-detail-control').attr("tabindex", '-1');


        }
        */
        $('#form-collection-details .collection-detail-control').attr("disabled", false);
        
        if ($(this).data('target') == 'more-info' || $(this).data('target') == 'cancel' || $(this).data('target') == 'export') {
          $('#form-collection-details .collection-detail-control').attr("disabled", "disabled");
        }
        
        $('#form-collection-details .btn-primary').hide();
        if ($(this).data('target') == 'cancel') {
          $('#btn-collection-detail-cancel').show();
        }

        if ($(this).data('target') == 'export') {
          $('#btn-collection-detail-export').show();
        }
        var target = $(this).data('target');
      $.ajax(
        {
          type: 'POST',
          url: "{$link->getAdminLink('AdminCorreos')|escape:'javascript':'UTF-8'}",
          data: {
            ajax: true,
            action: 'getCollectionDetails',
            id_collection: $('.collection-id:checked').val()
          }
          ,dataType: "json",
          async: true,
          success: function(result) 
          {
            $('#hidden-collection-detail-id').val($('.collection-id:checked').val());
            $('#collection-detail-date').val(result.date);
            $('#collection-detail-time').val(result.time);
            
            $('#collection-detail-sender-name').val(result.sender.name);
            $('#collection-detail-sender-address').val(result.sender.address);
            $('#collection-detail-sender-city').val(result.sender.city);
            $('#collection-detail-sender-email').val(result.sender.email);
            $('#collection-detail-sender-phone').val(result.sender.phone);
            $('#collection-detail-sender-postalcode').val(result.sender.postalcode);
            $('#collection-detail-reference').val(result.reference);
            $('#collection-detail-confirmation-code').val(result.confirmation_code);
            $('#hidden-collection-detail-confirmation-code').val(result.confirmation_code);
            $('#collection-detail-pieces').val(result.pieces);
            $('#collection-detail-size').val(result.size);
            $('#collection-detail-label_print').val(result.label_print);
            $('#collection-detail-comments').val(result.comments);

            
            $('#collection-details-orders-table tr').remove();


              if(typeof result.orders != "undefined"){
                jQuery.each(result.orders, function() {
                  $('#collection-details-orders-table').append($('<tr>')
                    .append($('<td>')
                      .append(this.order_customer)
                    )
                    .append($('<td>')
                      .append(this.order_date)
                    )
                    .append($('<td>')
                      .append(this.expedition_code)
                    )
                    .append($('<td>')
                      .append("{l s='Parcels' mod='correos'}:" + this.shipping_code.split(",").length)
                    )
                  );
                });
              }


            $('#collectionDetails').modal({
              show:true
            });
          }
        });
      
      });
      $("#btn-collection-repeat").on("click", function(e){
        $('.nav-tabs a[href="#tab-request_collection"]').tab('show');
         {if $smarty.now|date_format:"%H" > 13}  
          $("#collection_req_date").datepicker().datepicker('setDate', new Date().getDay+2);
         {else}
          $("#collection_req_date").datepicker().datepicker('setDate', new Date().getDay+1);
         {/if}
        $.ajax(
        {
          type: 'POST',
          url: "{$link->getAdminLink('AdminCorreos')|escape:'javascript':'UTF-8'}",
          data: {
            ajax: true,
            action: 'getCollectionDetails',
            id_collection: $('.collection-id:checked').val()
          }
          ,dataType: "json",
          async: true,
          success: function(result) 
          {
                  
            $('#collection_req_name').val(result.sender.name);
            $('#collection_req_address').val(result.sender.address);
            $('#collection_req_city').val(result.sender.city);
            $('#collection_req_email').val(result.sender.email);
            $('#collection_req_mobile_phone').val(result.sender.phone);
            $('#collection_req_postalcode').val(result.sender.postalcode);


            $('#collection_req_pieces').val('');
            $('#collection_size').val('');
            $('#collection_req_label_print').val('N');
            $('#collection_req_comments').val(result.comments);

            
            $('#collection-details-orders-table tr').remove();

          }
        });
        
        
      /*
          var collection_error = '';
          var selectedDate = $('#collection-detail-date').datepicker('getDate');
          var today = new Date();
          var in_2_days = new Date(today.getFullYear(), today.getMonth(), today.getDate()+2);
          if(Date.parse(selectedDate) <= Date.parse(today)) {
            collection_error = "{l s='Selected date is not valid' mod='correos'}\n";
          }
          if(Date.parse(selectedDate) > Date.parse(today) && Date.parse(selectedDate) <= Date.parse(in_2_days)) {
             if(!confirm("{l s='Requested service is not guaranteed for selected date. Do you wish to continue?' mod='correos'}"))
              return false;
          }
          if(collection_error != '') {
              alert(collection_error);
              return false;
          }
          */
        return true;
      });
      
      $(".btn-rma-collection-request").on("click", function(e){
      
        $('.nav-tabs a[href="#tab-request_collection"]').tab('show');
         {if $smarty.now|date_format:"%H" > 13}  
          $("#collection_req_date").datepicker().datepicker('setDate', new Date().getDay+2);
         {else}
          $("#collection_req_date").datepicker().datepicker('setDate', new Date().getDay+1);
         {/if}
         
          $('#collection_req_name').val($(this).data('name'));
          $('#collection_req_address').val($(this).data('address'));
          $('#collection_req_city').val($(this).data('city'));
          $('#collection_req_email').val($(this).data('email'));
          $('#collection_req_mobile_phone').val($(this).data('phone'));
          $('#collection_req_postalcode').val($(this).data('postcode'));
          $('#collection_req_pieces').val('');
          $('#collection_size').val('');
          $('#collection_req_label_print').val('N');
          $('#collection_req_comments').val('');

          $('#collection-orders-table tr').remove();
          
          $('#collection-orders-table').append($('<tr>')
            .append($('<td>')
              .append('<input checked type="checkbox" class="id_order" ' + 
                'name="id_order['+ $(this).data('idorder') +']" value="' + $(this).data('shipmentcode') + '" data-orderid="'+ $(this).data('idorder') +'" ' +
                'data-reference="" data-expedition="' + $(this).data('shipmentcode') + '" data-orderdate="" data-ordercustomer="' + $(this).data('name') + '">')
             )
            .append($('<td>')
              .append('RMA: ' + $(this).data('name'))
            )
            .append($('<td>')
              .append($(this).data('dateresponse'))
            )
            .append($('<td>')
              .append($(this).data('shipmentcode'))
            )
            .append($('<td>')
              .append("{l s='Parcels' mod='correos'}:" + $(this).data('shipmentcode').split(",").length)
            )
          );
            
            
      });
      
      $("#goto-tab-request_collection").on("click", function(e){
        e.preventDefault();
        $('.nav-tabs a[href="#tab-search_shipping"]').tab('show');
      });
      

});
function searchCustomers()
	{
      $("#loading-mask").show();
		$.ajax({
			type:"POST",
			url : "{$link->getAdminLink('AdminCustomers')|escape:'javascript':'UTF-8'}",
			async: true,
			dataType: "json",
			data : {
				ajax: "1",
				tab: "AdminCustomers",
				action: "searchCustomers",
				customer_search: $('#customer').val()},
			success : function(res)
			{
                if(res.found)
				{
					var html = '';
					$.each(res.customers, function() {
						html += '<div class="customerCard col-lg-4">';
						html += '<div class="panel message-item">';
						html += '<div class="panel-heading"><span class="pull-right">#'+this.id_customer+'</span>';
						html += this.firstname+' '+this.lastname + '</div>';
						html += '<button type="button" data-customer_name="'+this.firstname+' '+this.lastname +'" data-customer="'+this.id_customer+'" data-customer_mail="'+this.email+'" class="setup-customer btn btn-default pull-right"><i class="icon-arrow-right"></i> {l s='Choose' mod='correos'}</button>';
						html += '</div>';
						html += '</div>';
					});
				}
				else
					html = '<div class="alert alert-warning"><i class="icon-warning-sign"></i>&nbsp;{l s='No customers found' mod='correos'}</div>';
				$('#customers').html(html);
            
            $("#loading-mask").hide();
			}
		});
	}
function setupCustomer(idCustomer)
	{
		$('#lastOrders').hide();
      $("#loading-mask").show();
		
		id_customer = idCustomer;
		id_cart = 0;
	
		$.ajax({
			type:"POST",
			url : "{$link->getAdminLink('AdminCarts')|escape:'javascript':'UTF-8'}",
			async: false,
			dataType: "json",
			data : {
				ajax: "1",
				token: "{getAdminToken tab='AdminCarts'}",
				tab: "AdminCarts",
				action: "searchCarts",
				id_customer: id_customer,
				id_cart: id_cart
			},
			success : function(res)
			{
				if(res.found)
				{
				
					var html_orders = '';
				
               customer_orders = res.orders;
               customer_addresses = res.addresses;
               customer_id_addresses = [];
               $.each(res.addresses, function() {
                  customer_id_addresses.push(this.id_address);
               });
               
					$.each(res.orders, function(index, order) {
						html_orders += '<tr>';
						html_orders += '<td>'+order.id_order+'</td><td>'+order.date_add+'</td><td>'+(order.nb_products ? order.nb_products : '0')+'</td><td>'+order.total_paid_real+'</span></td><td>'+order.order_state+'</td>';
						if (rma_labels.indexOf(parseInt(order.id_order)) >= 0) {
                     html_orders += '<td><a href="../modules/correos/pdftmp/d-'+order.id_order+'.pdf" target="_blank">{l s='Download RMA Label' mod='correos'}</a> <span style="padding:0 10px">|</span>';
                     html_orders += '<a class="request_collection" data-index="'+index+'" data-id_order="'+order.id_order+'" title="{l s='Request collection' mod='correos'}" href="#"> {l s='Request collection' mod='correos'}</a></td>';
                  } else {
                  html_orders += '<td>-</td>';
                  }
                     
                  html_orders += '<td class="text-right">';
                  html_orders += '<a href="#" "title="{l s='Use' mod='correos'}" class="fill_address btn btn-default" data-index="'+index+'" data-id_order="'+order.id_order+'"><i class="icon-arrow-right"></i>&nbsp;{l s='Use' mod='correos'}</a>';
						html_orders += '</td>';
						html_orders += '</tr>';
					});

					$('#lastOrders table tbody').html(html_orders);
               
               $('#lastOrders').show();
               $("#loading-mask").hide();
				}
            
			}
		});
	}

</script>
<style>
#form label.error {
color:red;
}
#form input.error {
border:1px solid red;
}
</style>