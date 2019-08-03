var map = null;
var geocoder = null;
var markersArray = [];
var infowindow = null;

function initializeMap(lat, lng, zoom, canvas) {
    var centerPos = new google.maps.LatLng(lat, lng);
    var mapOptions = {
        zoom: zoom,
        center: centerPos,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    var canvaselem = document.getElementById(canvas);
    if (canvaselem == null) return;
    map = new google.maps.Map(canvaselem, mapOptions);
}

function refreshGoogleMap() {
    if (map == null) return;
    x = map.getZoom();
    c = map.getCenter();
    google.maps.event.trigger(map, 'resize');
    map.setZoom(x);
    map.setCenter(c);
}

function setLocationByAddress() {
    var address;

    if (typeof has_address === "undefined" || typeof is_multilang_address === "undefined") return;
    if (!has_address) return;

    if (is_multilang_address) {
        address = $("input#address1_" + id_language).val() + " " +
                  $("input#city_" + id_language).val() + " " +
                  $("select#id_state option:selected").text() + "," +
                  $("input#postcode").val() + " " +
                  $("select#id_country option:selected").text();
    }
    else {
        address = $("input#address1").val() + " " +
                      $("input#city").val() + " " +
                      $("select#id_state option:selected").text() + "," +
                      $("input#postcode").val() + " " +
                      $("select#id_country option:selected").text();
    }

    if (geocoder === null) geocoder = new google.maps.Geocoder()

    geocoder.geocode({ "address": address }, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            $("input#latitude").val(results[0].geometry.location.lat());
            $("input#longitude").val(results[0].geometry.location.lng());
        } else {
            agile_show_message("Geocode was not successful for the following reason: " + status);
        }

        initializeMap($("input#latitude").val(), $("input#longitude").val(), 12, "map_canvas");
        loc = new google.maps.LatLng($("input#latitude").val(), $("input#longitude").val());
        addMarker("0", loc, null);
    });
}

function getMarkerByTitle(title) {
    for (idx = 0; idx < markersArray.length; idx++) {
        if (markersArray[idx].title == title) return markersArray[idx];
    }
    return null;
}

function addMarker(key, location, clickHandler) {
    marker = new google.maps.Marker({
        position: location,
        map: map,
        title: key
    });
    if (typeof clickHandler === "function") {
        google.maps.event.addListener(marker, 'click',clickHandler);
    }

    markersArray.push(marker);
}

/** _agile_  Removes the overlays from the map, but keeps them in the array _agile_  **/
function clearOverlays() {
    if (markersArray) {
        for (i in markersArray) {
            markersArray[i].setMap(null);
        }
    }
}

/** _agile_  Shows any overlays currently in the array _agile_  **/
function showOverlays() {
    if (markersArray) {
        for (i in markersArray) {
            markersArray[i].setMap(map);
        }
    }
}

/** _agile_  Deletes all markers in the array by removing references to them _agile_  **/
function deleteOverlays() {
    if (markersArray) {
        for (i in markersArray) {
            markersArray[i].setMap(null);
        }
        markersArray.length = 0;
    }
}

