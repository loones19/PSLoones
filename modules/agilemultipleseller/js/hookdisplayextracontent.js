$(document).ready(function () {
    /* find the tab content ID */
    var idanchor = $('#idTab19').parent().attr("id");

    /* hook click of the tab */
    $('[href="#' + idanchor + '"]').on('click', function () {
        initializeMap(sellerloclat, sellerloclng, 12, "map_canvas");
        var loc = new google.maps.LatLng(sellerloclat, sellerloclng);
        addMarker('0', loc, null);

        setTimeout("refreshGoogleMap()", 600);
    });
});

