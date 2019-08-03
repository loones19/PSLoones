var readyToExpand = true;
var needCheckAll = false;
var needUncheckAll = false;
var interval = null;
var intervalCheck = null;
var id_tree = 0;
var arrayCatToExpand = new Array();
var id_category_root = 0;

$(document).ready(function () {
    $('#category_block > input[name="categoryBox[]"]:hidden').each(function () {
        if ($(this).val() > 0) {
            getParentCategoriesIdAndOpen($(this).val());
        }
    });
});


function buildTreeView() {
    use_shop_context = 0;
    if (buildTreeView.arguments[0] && buildTreeView.arguments[0] == 1)
        use_shop_context = 1;
    $("#categories-treeview").treeview({
        url: base_dir_ssl + 'modules/agilemultipleseller/ajax.php',
        toggle: function () { callbackToggle($(this)); },
        ajax: {
            type: 'POST',
            async: false,
            data: {
                getChildrenCategories: true,
                use_shop_context: use_shop_context,
                selectedCat: selectedCat
            }
        }
    });
    id_category_root = $('#categories-treeview li:first').attr('id');
    $('#categories-treeview li#' + id_category_root + ' span').trigger('click');
    $('#categories-treeview li#' + id_category_root).children('div').remove();
    $('#categories-treeview li#' + id_category_root).
		removeClass('collapsable lastCollapsable').
		addClass('last static');

    disabled = $('#categories-treeview li:first input[type=checkbox]').attr('disabled');
    $('#categories-treeview input[type=checkbox]').attr('disabled', disabled);

    $('#expand_all').click(function () {
        expandAllCategories();
        return false;
    });

    $('#collapse_all').click(function () {
        collapseAllCategories();
        return false;
    });

    $('#check_all').click(function () {
        needCheckAll = true;
        checkAllCategories();
        return false;
    });

    $('#uncheck_all').click(function () {
        needUncheckAll = true;
        uncheckAllCategories();
        return false;
    });

    /** _agile_  check if checkboxes need to be disabled or not _agile_  **/
    $('.expandable').click(function () {
        disabled = $('#categories-treeview li:first input[type=checkbox]').attr('disabled');
        $('#categories-treeview input[type=checkbox]').attr('disabled', disabled);
    });
}

function callbackToggle(element) {
    if (!element.is('.expandable'))
        return false;

    if (element.children('ul').children('li.collapsable').length != 0)
        closeChildrenCategories(element);
}

function closeChildrenCategories(element) {
    var arrayLevel = new Array();

    if (element.children('ul').find('li.collapsable').length == 0)
        return false;

    element.children('ul').find('li.collapsable').each(function () {
        var level = $(this).children('span.category_level').html();
        if (arrayLevel[level] == undefined)
            arrayLevel[level] = new Array();

        arrayLevel[level].push($(this).attr('id'));
    });

    for (i = arrayLevel.length - 1; i >= 0; i--)
        if (arrayLevel[i] != undefined)
            for (j = 0; j < arrayLevel[i].length; j++) {
                $('#categories-treeview').find('li#' + arrayLevel[i][j] + '.collapsable').children('span.category_label').trigger('click');
                $('#categories-treeview').find('li#' + arrayLevel[i][j] + '.expandable').children('ul').hide();
            }
}

function setCategoryToExpand() {
    var ret = false;

    id_tree = 0;
    arrayCatToExpand = new Array();
    $('#categories-treeview').find('li.expandable:visible').each(function () {
        arrayCatToExpand.push($(this).attr('id'));
        ret = true;
    });

    return ret;
}

function needExpandAllCategories() {
    return $('li').is('.expandable');
}

function expandAllCategories() {
    /** _agile_  if no category to expand, no action _agile_  **/
    if (!needExpandAllCategories())
        return;
    /** _agile_  force to open main category _agile_  **/
    if ($('li#' + id_category_root).is('.expandable'))
        $('li#' + id_category_root).children('span.folder').trigger('click');
    readyToExpand = true;
    if (setCategoryToExpand())
        interval = setInterval(openCategory, 10);
}

function openCategory() {
    /** _agile_  Check readyToExpand in order to don't clearInterval if AJAX request is in progress _agile_  **/
    /** _agile_  readyToExpand = category has been expanded, go to next ;) _agile_  **/
    if (id_tree >= arrayCatToExpand.length && readyToExpand) {
        if (!setCategoryToExpand()) {
            clearInterval(interval);
            /** _agile_  delete interval value _agile_  **/
            interval = null;
            readyToExpand = false;
            if (needCheckAll) {
                checkAllCategories();
                needCheckAll = false;
            }
            else if (needUncheckAll) {
                uncheckAllCategories();
                needUncheckAll = false;
            }
        }
        else
            readyToExpand = true;
    }

    if (readyToExpand) {
        if ($('#categories-treeview').find('li#' + arrayCatToExpand[id_tree] + '.hasChildren').length > 0)
            readyToExpand = false;
        $('#categories-treeview').find('li#' + arrayCatToExpand[id_tree] + '.expandable:visible span.category_label').trigger('click');
        id_tree++;
    }
}

function collapseAllCategories() {
    closeChildrenCategories($('li#' + id_category_root));
}

function checkAllCategories() {
    if (needExpandAllCategories())
        expandAllCategories();
    else {
        $('input[name="categoryBox[]"]').not(':checked').each(function () {
            $(this).prop('checked', true); 
            clickOnCategoryBox($(this));
        });
    }
}

function uncheckAllCategories() {
    if (needExpandAllCategories())
        expandAllCategories();
    else {
        $('input[name="categoryBox[]"]:checked').each(function () {
            $(this).prop('checked', false); 
            clickOnCategoryBox($(this));
        });
    }
}

function clickOnCategoryBox(category) {
    if (category.is(':checked')) {
        $('select#id_category_default').append('<option value="' + category.val() + '">' + (category.val() != 2 ? category.parent().find('span').html() : home) + '</option>');
        updateNbSubCategorySelected(category, true);
        if ($('select#id_category_default option').length > 0) {
            $('select#id_category_default').show();
            $('#no_default_category').hide();
        }
    }
    else {
        $('select#id_category_default option[value=' + category.val() + ']').remove();
        updateNbSubCategorySelected(category, false);
        if ($('select#id_category_default option').length == 0) {
            $('select#id_category_default').hide();
            $('#no_default_category').show();
        }
    }
}

function updateNbSubCategorySelected(category, add) {
    var currentSpan = category.parent().parent().parent().children('.nb_sub_cat_selected');
    var parentNbSubCategorySelected = currentSpan.children('.nb_sub_cat_selected_value').html();

    if (use_radio) {
        $('.nb_sub_cat_selected').hide();
        return false;
    }

    if (add)
        var newValue = parseInt(parentNbSubCategorySelected) + 1;
    else
        var newValue = parseInt(parentNbSubCategorySelected) - 1;

    currentSpan.children('.nb_sub_cat_selected_value').html(newValue);
    currentSpan.children('.nb_sub_cat_selected_word').html(selectedLabel);

    if (newValue == 0)
        currentSpan.hide();
    else
        currentSpan.show();

    if (currentSpan.parent().children('.nb_sub_cat_selected').length != 0)
        updateNbSubCategorySelected(currentSpan.parent().children('input'), add);
}

function searchCategory() {
    var category_to_check;
    if ($('#search_cat').length) {
        $('#search_cat').autocomplete(base_dir_ssl + 'modules/agilemultipleseller/ajax.php?searchCategory=1', {
            delay: 100,
            minChars: 3,
            autoFill: true,
            max: 20,
            matchContains: true,
            mustMatch: true,
            scroll: false,
            cacheLength: 0,
            multipleSeparator: '||',
            formatItem: function (item) {
                return item[1] + ' - ' + item[0];
            }
        }).result(function (event, item) {
            parent_ids = getParentCategoriesIdAndOpen(item[1]);
        });
    }
}

function getParentCategoriesIdAndOpen(id_category) {
    category_to_check = id_category;
    $.ajax({
        type: 'POST',
        url: base_dir_ssl + 'modules/agilemultipleseller/ajax.php',
        async: true,
        dataType: 'json',
        data: 'ajax=true&getParentCategoriesId=true&id_category=' + id_category,
        success: function (jsonData) {
            for (var i = 0; i < jsonData.length; i++)
                if (jsonData[i].id_category != 2)
                    arrayCatToExpand.push(jsonData[i].id_category);
            readyToExpand = true;
            interval = setInterval(openParentCategories, 10);
            intervalCheck = setInterval(checkCategory, 20);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            agile_show_message("TECHNICAL ERROR: \n\nDetails:\nError thrown: " + XMLHttpRequest + "\n" + 'Text status: ' + textStatus);
        }
    });
}

function openParentCategories() {
    if (id_tree >= arrayCatToExpand.length && !readyToExpand) {
        clearInterval(interval);
        /** _agile_  delete interval value _agile_  **/
        interval = null;
        readyToExpand = false;
    }

    if (readyToExpand) {
        if ($('li#' + arrayCatToExpand[id_tree] + '.hasChildren').length > 0)
            readyToExpand = false;

        $('li#' + arrayCatToExpand[id_tree] + '.expandable span').trigger('click');
        id_tree++;
    }
}

function checkCategory() {
    if ($('li#' + category_to_check + ' > input[type=checkbox]').prop('checked')) {
        clearInterval(intervalCheck);
        intervalCheck = null;
    }
    else {
        $('li#' + category_to_check + ' > input').prop('checked', true);
        updateNbSubCategorySelected($('li#' + category_to_check + ' > input[type=checkbox]'), true);
    }
}
