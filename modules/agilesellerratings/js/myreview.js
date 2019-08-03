function openMessageForm(id_replyto) {
    $("tr#trMessageForm_" + id_replyto).show();
}

function closeMessageForm(id_replyto) {
    $("tr#trMessageForm_" + id_replyto).hide();
}
function onSubmitMessageForm(id_replyto) {
    msg = $("#response_" + id_replyto).val();
    if (msg == "") {
        agile_show_message(asr_pleaseenterreply);
        return false;
    }
    return true;
}
