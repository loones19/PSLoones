$(document).ready(function () {
    $("a#seller_terms").fancybox({
        'type': 'iframe',
        'width': 600,
        'height': 600
    });


    $("a#show_messagebox").fancybox({
        'transitionIn': 'elastic',
        'transitionOut': 'elastic',
        'speedIn': 600,
        'speedOut': 200,
        'overlayShow': false,
        'autoDimensions': false,
        'width': 400,
        'height': 100
    });
    /** _agile_  It causes issue in some themes, so you can comment it out _agile_  **/
    if (membership_module_integrated > 0) get_my_membership_info();

});


function check_terms(id_cms_terms) {
    if (id_cms_terms <= 0) {
        $("#frmSellerSummary").submit();
        return;
    }
    if ($('#iagree').is(':checked')) {
        $("#frmSellerSummary").submit();
        return;
    }
    agile_show_message(msg);
    return;
}

function is_valid_amount(amount) {
    var amount = amount.replace(/^\s+|\s+$/g, '');
    if (amount == "") return false;
    if (isNaN(amount)) return false;
    return true;
}

function validate_message(req, req_text) {
    /** _agile_ set request command and request message content _agile_  **/
    $("#submitRequest").val(req);
    $("[id^='msg_request_']").text(req_text);
    /** _agile_  _agile_  **/
    if (((req == 'B2T' || req == 'MPR') && !is_valid_amount($("#amount_to_convert").val())) ||
			(req == 'T2B' && !is_valid_amount($("#tokens_to_convert").val()))) {
        $("#msg_comfirm").hide();
        $("#msg_error").show();
        $("#btnOK").show();
        $("#btnYes").hide();
        $("#btnNo").hide();
    }
    else {
        $("#msg_comfirm").show();
        $("#msg_error").hide();
        $("#btnOK").hide();
        $("#btnYes").show();
        $("#btnNo").show();
    }

    $("#show_messagebox_B2T").click();
}

function fb_yesclick() {
    $.fancybox.close();
    $("#frmConvertingPayment").submit();
}

function fb_noclick() {
    $.fancybox.close();
}

function fb_okclick() {
    $.fancybox.close();
}


function get_my_membership_info() {
    $("#fsMymembershipInfo").show();
    $.ajax({
        url: mymembership_url,
        type: "POST",
        data: {},
        success: function (data) {
            /** _agile_ alert(data); _agile_  **/
            $("#divMymembershipInfo").html(data);
        }
    });
}
