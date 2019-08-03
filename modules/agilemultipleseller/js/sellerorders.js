$(document).ready(function () {
    $('.datepicker').datepicker({
        prevText: '',
        nextText: '',
        dateFormat: 'yy-mm-dd'
    });
});

function goOnClick() {
    var url = listurl + "?p=" + (p <= 0 ? 1 : p) + "&n=" + n + "&orderBy=" + orderBy + "&orderWay=" + orderWay + getExtra();
    window.location.href = url;
}

function ResetOnClick() {
    $("[id^='filter_']").val("");
}

function CSVOnClick() {
    var url = listurl + "?export=CSV" + "&p=" + (p <= 0 ? 1 : p) + "&n=" + n + "&orderBy=" + orderBy + "&orderWay=" + orderWay + getExtra();
    window.location.href = url;
}

function getExtra() {
    var ex = "";
    var filter_customer = $("#filter_customer").val();
    var filter_reference = $("#filter_reference").val();
    var filter_id_order = $("#filter_id_order").val();
    var filter_id_order_state = $("#filter_id_order_state").val();
    var filter_total = $("#filter_total").val();
    var filter_payment = $("#filter_payment").val();
    var filter_date_add_from = $("#filter_date_add_from").val();
    var filter_date_add_to = $("#filter_date_add_to").val();

    if (filter_customer.length > 0) ex = ex + "&filter_customer=" + filter_customer;
    if (filter_reference.length > 0) ex = ex + "&filter_reference=" + filter_reference;
    if (filter_id_order.length > 0) ex = ex + "&filter_id_order=" + filter_id_order;
    if (filter_id_order_state.length > 0) ex = ex + "&filter_id_order_state=" + filter_id_order_state;
    if (filter_total.length > 0) ex = ex + "&filter_total=" + filter_total;
    if (filter_payment.length > 0) ex = ex + "&filter_payment=" + filter_payment;
    if (filter_date_add_from.length > 0) ex = ex + "&filter_date_add_from=" + filter_date_add_from;
    if (filter_date_add_to.length > 0) ex = ex + "&filter_date_add_to=" + filter_date_add_to;

    return ex;
}

function orderByWay(by, way) {
    var url = listurl + "?p=" + (p <= 0 ? 1 : p) + "&n=" + n + "&orderBy=" + by + "&orderWay=" + way + getExtra();
    window.location.href = url;
    return false;
}
