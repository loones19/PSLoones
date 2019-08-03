$(document).ready(function () {
    $(document).on('click', '#seller_tab', function (e) {
        hideOrDisplaySellerInfoThemeStyle();
    });

});

function hideOrDisplaySellerInfoThemeStyle() {
    if ($("input[id='seller_tab']").attr('checked') == 'checked')
    {
        $('div.seller_info_tab_style').removeClass('hidden');
        $("#vertical").attr('checked', true);
        $("#horizonal").attr('checked', false);

        $('div.seller_info_tab_gmap').removeClass('hidden');
        $("#gmap_yes").attr('checked', true);
        $("#gmap_no").attr('checked', false);

    } else {
        $('div.seller_info_tab_style').addClass('hidden');
        $("#vertical").attr('checked', false);
        $("#horizonal").attr('checked', false);

        $('div.seller_info_tab_gmap').addClass('hidden');
        $("#gmap_yes").attr('checked', false);
        $("#gmap_no").attr('checked', false);
    }
}
