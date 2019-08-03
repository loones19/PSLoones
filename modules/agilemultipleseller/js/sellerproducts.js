$(document).ready(function () {
    $('.datepicker').datepicker({
        prevText: '',
        nextText: '',
        dateFormat: 'yy-mm-dd'
    });



    $('#product_autocopy_input').autocomplete(base_dir_ssl + "modules/agilemultipleseller/ajax_products_list.php?includepublicproduct=" + allowCopyMainStoreProduct, {
        minChars: 1,
        autoFill: true,
        max: 20,
        matchContains: true,
        mustMatch: true,
        scroll: false,
        cacheLength: 0,
        formatItem: function (item) {
            return item[1] + ' - ' + item[0];
        }
    }).result(copyProduct);


    $("#chkAll").click(function () {
        var checked = $("#chkAll").prop('checked');
        $("[id^=chkProd_]").prop('checked', checked);
    });

    $("#bulkAction").change(function () {
        var act = $("#bulkAction").val();
        var data = [];
        $.each($("[id^=chkProd_]"), function (idx, item) {
            if ($(item).prop('checked')) data.push($(item).val());
        });
        $("#bulkActionData").val(data);
        if (act == "delete") alert(bulkmsg_delete);
        else if (act == "enable") alert(bulkmsg_enable);
        else if (act == "disable") alert(bulkmsg_disable);
        $("#frmBulkAction").submit();
    });

});


function onClickConfirm(act, id_product) {
    var msg = '';
    if (act == "delete") msg = msgDelete;
    else msg = msgDuplicate;

    if (confirm(msg)) {
        actionOnProduct(act, id_product);
    }
    else {
        event.stopPropagation();
        event.preventDefault();
    }
}

function ResetOnClick() {
    $("[id^='filter_']").val("");
}

function goOnClick() {
    var url = listurl + "?p=" + (p <= 0 ? 1 : p) + "&n=" + n + "&orderBy=" + orderBy + "&orderWay=" + orderWay + getExtra();
    window.location.href = url;
}

function CSVOnClick() {
    var url = listurl + "?export=CSV" + "&p=" + (p <= 0 ? 1 : p) + "&n=" + n + "&orderBy=" + orderBy + "&orderWay=" + orderWay + getExtra();
    window.location.href = url;
}


function orderByWay(by, way) {
    var url = listurl + "?p=" + (p <= 0 ? 1 : p) + "&n=" + n + "&orderBy=" + by + "&orderWay=" + way + getExtra();
    window.location.href = url;
    return false;
}

function actionOnProduct(action, id_product) {
    var url = listurl + "?process=" + action + "&id_product=" + id_product + "&p=" + (p <= 0 ? 1 : p) + "&n=" + n + "&orderBy=" + orderBy + "&orderWay=" + orderWay + getExtra();
    window.location.href = url;
}

function getExtra() {
    var ex = "";
    var filter_active = $("#filter_active").val();

    var filter_approved = $("#filter_approved").val();
    var filter_name = $("#filter_name").val();
    var filter_category = $("#filter_category").val();
    var filter_id_product = $("#filter_id_product").val();
    var filter_quantity = $("#filter_quantity").val();
    var filter_price = $("#filter_price").val();
    var filter_date_add_from = $("#filter_date_add_from").val();
    var filter_date_add_to = $("#filter_date_add_to").val();

    if (filter_active.length > 0) ex = ex + "&filter_active=" + filter_active;
    if (filter_approved && filter_approved.length > 0) ex = ex + "&filter_approved=" + filter_approved;
    if (filter_name.length > 0) ex = ex + "&filter_name=" + filter_name;
    if (filter_category.length > 0) ex = ex + "&filter_category=" + filter_category;
    if (filter_id_product.length > 0) ex = ex + "&filter_id_product=" + filter_id_product;
    if (filter_quantity.length > 0) ex = ex + "&filter_quantity=" + filter_quantity;
    if (filter_price.length > 0) ex = ex + "&filter_price=" + filter_price;
    if (filter_date_add_from.length > 0) ex = ex + "&filter_date_add_from=" + filter_date_add_from;
    if (filter_date_add_to.length > 0) ex = ex + "&filter_date_add_to=" + filter_date_add_to;

    return ex;
}


function copyProduct(event, data, formatted) {
    if (data == null) return false;
    var productId = data[1];
    var productName = data[0];
    if (confirm(msgDuplicate + productName + "(id=" + productId + ")"))
        window.location.href = duplicateURL + "&id_product=" + productId;
}

function categorias() {
    //console.log(cates)
    
    if (categorias){
        id_parent=$('#id_category_parent option:selected').val();
        //console.log(id_parent)
        $('#id_category_default').children().remove().end();
        $('#id_category_default').append('<option value="0">Select one</option>')

        cates.forEach(element => {
            if (element.id_parent==id_parent ) {
                //console.log(element.id_parent+"-----"+element.id_category)
                $('#id_category_default').append('<option value="'+element.id_category+'">'+element.name+'</option>')
                
            }
        });
    }
}
