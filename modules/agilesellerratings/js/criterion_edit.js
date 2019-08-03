$(document).ready(function () {
    fill_criteria_edit_form(0);
});


function fill_criteria_edit_form(id) {
    /** _agile_     alert(id);      _agile_  **/
    $.post(thePath + 'ajax_criterion_edit.php', { id: id },
    function (data) {
        document.getElementById('divCriterionForm').innerHTML = data;
    });
}

function getCriterionForm() {
    if (document.forms)
        return (document.forms['criterion_form']);
    else
        return (document.criterion_form);
}

function deleteCriterion(id, msg) {
    var form = getCriterionForm();
    agile_confirm_action(msg, function () {
        form.elements['id_agile_rating_criterion'].value = id;
        form.elements['criterion_action'].value = 'delete';
        form.submit();
    });
}


