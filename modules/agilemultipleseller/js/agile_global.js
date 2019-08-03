var base_display = window.display;
if (!id_language)
    var id_language = Number(1);

window.display = function (v) {
    agile_display(v);
}

$(document).ready(function () {
    if (AGILE_MS_CUSTOMER_SELLER == 1) $(".user-info").append(sellwithus_link);
});


function agile_display(view) {
    var sellernames = [];
    $('.product_list > li').each(function (index, element) {
        if ($(element).find('.agile_sellername_onlist').html() == undefined)
            sellernames[index] = '<p class="agile_sellername_onlist"></p>';
        else
            sellernames[index] = '<p class="agile_sellername_onlist">' + $(element).find('.agile_sellername_onlist').html() + '</p>';
    });

    base_display(view);

    $('.product_list > li').each(function (index, element) {
        var html = $(element).html();
        html = sellernames[index] + html;
        $(element).html(html);
    });
}

