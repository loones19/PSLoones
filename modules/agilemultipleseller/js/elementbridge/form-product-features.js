/**
* Subset of following file
       \adminDEV\themes\default\js\bundle\product\form.js
*/

$(document).ready(function () {
	form.init();
	featuresCollection.init();
});


/**
 * Feature collection management
 */
var featuresCollection = (function () {

    var collectionHolder = $('.feature-collection');

    /** Add a feature */
    function add() {
        var newForm = collectionHolder.attr('data-prototype').replace(/__name__/g, collectionHolder.children().length);
        collectionHolder.append(newForm);
    }

    return {
        'init': function () {
            /** Click event on the add button */
            $('#features a.add').on('click', function (e) {
                e.preventDefault();
                add();
                $('#features-content').removeClass('hide');
            });

            /** Click event on the remove button */
            $(document).on('click', '.feature-collection .delete', function (e) {
                e.preventDefault();
                var _this = $(this);

                modalConfirmation.create(translate_javascripts['Are you sure to delete this?'], null, {
                    onContinue: function () {
                        _this.parent().parent().parent().remove();
                    }
                }).show();
            });

            /** On feature selector event change, refresh possible values list */
            $(document).on('change', '.feature-collection select.feature-selector', function () {
                var selector = $(this).parent().parent().parent().find('.feature-value-selector');
                $.ajax({
                    url: $(this).attr('data-action') + '/' + $(this).val(),
                    success: function (response) {
                        selector.empty();
                        $.each(response, function (key, val) {
                            selector.append($('<option></option>').attr('value', key).text(val));
                        });
                    }
                });
            });
        }
    };
})();

