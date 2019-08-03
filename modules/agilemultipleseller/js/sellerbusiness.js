$(document).ready(function () {
    tinySetup({
        selector: ".rte",
        toolbar1: "code,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,|,blockquote,colorpicker,pasteword,|,bullist,numlist,|,outdent,indent,|,link,unlink,|,cleanup"
    });

    $('.datepicker').datepicker({
        prevText: '',
        nextText: '',
        dateFormat: 'yy-mm-dd',
    });

    setLocationByAddress();

});

