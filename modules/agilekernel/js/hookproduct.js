$(document).ready(function () {
    /** to disbale Prestashop original handler **/
    renameDataButtonActionClass();

    /** hook our own handler **/
    $('body').on('click', '[data-button-action="agile-add-to-cart"]', function (event) {
        event.preventDefault();
        var form = $(event.target).closest('form');
        var query = form.serialize() + '&add=1&action=update';
        var actionURL = form.attr('action');
        /** Disable the button **/
        $(event.currentTarget).prop("disabled", true);
        /** if it is not normal checkout mode - show processing, since it may take time to finish **/
        if (is_agileprepaidcredit_installed) {
            if(apc_pay_mode !== 1)agile_show_modal({ content: PleaseWaitWhileProcessYourRequest, showProcessing: true });
        }
        $.post(actionURL, query, null, 'json').then(function (resp) {
            /** we are using error message to pass One Click Checkout result **/
            if (!resp.hasError) prestashop.emit('updateCart', { reason: { idProduct: resp.id_product, idProductAttribute: resp.id_product_attribute, linkAction: 'add-to-cart' } });
            else handleErrorAsMessage(resp.errors);
            $(event.currentTarget).prop("disabled", false);
        }).fail(function (resp) {
            prestashop.emit('handleError', { eventType: 'addProductToCart', resp: resp });
            $(event.currentTarget).prop("disabled", false);
            agile_show_message(resp.responseText);
        });

    });

    prestashop.on('updateProduct', function (event) {
        setTimeout(renameDataButtonActionClass, 1000);
    });

});


function renameDataButtonActionClass() {
    /** to disbale Prestashop original handler and rewire our handler **/
    $('[data-button-action="add-to-cart"]').attr("data-button-action", "agile-add-to-cart");
}

function handleErrorAsMessage(results) {
    /** One Click Checkout success **/
    if (results[0] > 0) {
        if (results.length >= 2) agile_show_message(results[1]);
    }
    /** All other cases, show error message **/
    else {
        var msg = return_code + ": " + results[0] + "<BR>";
        for (idx = 1; idx < results.length ; idx++) msg = msg + results[idx] + "<BR>";
        agile_show_message(msg);
    }
}
