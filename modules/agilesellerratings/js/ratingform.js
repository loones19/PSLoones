$(document).ready(function () {
    $("img[id^='stars_']").click(function () {
        title = $(this).attr('title');
        $('input#' + title).val($(this).attr('alt'));
        na = $(this).attr('name');
        setStartImage(na, title);
    });

    $("img[id^='stars_']").mouseover(function () {
        na = $(this).attr('name');
        ex = "img[id^='" + na + "_']";
        $(ex).attr("style", "margin:-32px 0px 0px 0px;cursor:pointer");
    });

    $("img[id^='stars_']").mouseout(function () {
        na = $(this).attr('name');
        title = $(this).attr('title');
        setStartImage(na, title);
    });
});


function setStartImage(na, title) {
    ex = "img[id^='" + na + "_']";
    rank = $('input#' + title).val();
    $.each($(ex), function (index, obj) {
        /** _agile_         alert(index + ': ' + obj); _agile_  **/
        if ((index + 1) > rank)
            $(obj).attr("style", "margin:0px 0px 0px 0px;cursor:pointer");
        else
            $(obj).attr("style", "margin:-16px 0px 0px 0px;cursor:pointer");
    });
}


function showRatingForm(id_order, id_owner) {
    $("input#rating_id_order").val(id_order);
    $("input#rating_id_owner").val(id_owner);
    $("form#sendRatingForm").show();
    $("textarea#content").focus();
}

function validateRatingForm() {
    if (trim($('textarea#content').val()).length < 1) {
        agile_show_message(asr_pleaseentercomment);
        return false;
    }
            
    var ret = true;
    $.each($("input[id$='_rating_grade']"),function(index, obj){
        if($(obj).val() ==0)ret = false;
    });
            
    if(!ret)
    {
        agile_show_message(asr_pleaseenterrating);
        return false;
    }
    return true;
}
