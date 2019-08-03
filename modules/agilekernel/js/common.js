function agile_show_modal(options) {
    if (typeof options === "object") {
        if (options.title) $("#agile-modal-title").html(options.title);

        if (options.content) $("#agile-modal-content").html(options.content);
        else $("#agile-modal-content").html("");

        if (options.showProcessing) $("#agile-modal-processing").show();
        else $("#agile-modal-processing").hide();

        if (options.showOkButton) $("#agile-modal-ok").show();
        else $("#agile-modal-ok").hide();

        if (options.showCancelButton) $("#agile-modal-cancel").show();
        else $("#agile-modal-cancel").hide();

        if (options.showYesButton) $("#agile-modal-yes").show();
        else $("#agile-modal-yes").hide();

        if (options.showNoButton) $("#agile-modal-no").show();
        else $("#agile-modal-no").hide();

        $("#agile-modal-ok").off("click").on("click", typeof options.okClicked === "function" ? options.okClicked : null);
        $("#agile-modal-cancel").off("click").on("click", typeof options.cancelClicked === "function" ? options.cancelClicked : null);
        $("#agile-modal-yes").off("click").on("click", typeof options.yesClicked === "function" ? options.yesClicked : null);
        $("#agile-modal-no").off("click").on("click", typeof options.noClicked === "function" ? options.noClicked : null);

    }
    /** move the agile modal just after the body element so that it will not be disabled **/
    $('#agile-modal').appendTo("body").modal({ backdrop: 'static', keyboard: false });
}

function agile_hide_modal() {
    $("#agile-modal-trigger").modal().hide()
}

function agile_show_message(message) {
    if(message)agile_show_modal({content: message, showOkButton: true, showProcessing: false});
}

function agile_confirm_action(message, okClicked, cancelClicked) {
    agile_show_modal({content:message, showProcessing: false, showOkButton:true, showCancelButton:true, okClicked: okClicked, cancelClicked: cancelClicked});
}

/** _agile_  Removes leading whitespaces _agile_  **/
function LTrim(value) {

    var re = /\s*((\S+\s*)*)/;
    return value.replace(re, "$1");

}

/** _agile_  Removes ending whitespaces _agile_  **/
function RTrim(value) {

    var re = /((\s*\S+)*)\s*/;
    return value.replace(re, "$1");

}

/** _agile_  Removes leading and ending whitespaces _agile_  **/
function trim(value) {

    return LTrim(RTrim(value));

}

function getQuerystringParam(url, key, default_) {
    if (default_ == null) default_ = "";
    key = key.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    var regex = new RegExp("[\\?&]" + key + "=([^&#]*)");
    var qs = regex.exec(url);
    if (qs == null)
        return default_;
    else
        return qs[1];
}