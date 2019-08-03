$(document).ready(function() {
	form.init();
	imagesProduct.init();
});


/**
 * images product management
 */
var imagesProduct = (function () {
    var id_product = $('#form_id_product').val();

    return {
        'expander': function () {
            var closedHeight = $('#product-images-dropzone').outerHeight();
            var realHeight = $('#product-images-dropzone')[0].scrollHeight;

            if (realHeight > closedHeight) {
                $('#product-images-container .dropzone-expander').addClass('expand').show();
            }

            $(document).on('click', '#product-images-container .dropzone-expander', function () {
                if ($('#product-images-container .dropzone-expander').hasClass('expand')) {
                    $('#product-images-dropzone').css('height', 'auto');
                    $('#product-images-container .dropzone-expander').removeClass('expand').addClass('compress');
                } else {
                    $('#product-images-dropzone').css('height', closedHeight + 'px');
                    $('#product-images-container .dropzone-expander').removeClass('compress').addClass('expand');
                }
            });
        },
        'init': function () {
            Dropzone.autoDiscover = false;
            var dropZoneElem = $('#product-images-dropzone');
            var errorElem = $('#product-images-dropzone-error');

            //on click image, display custom form
            $(document).on('click', '#product-images-dropzone .dz-preview', function () {
                if (!$(this).attr('data-id')) {
                    return;
                }
                formImagesProduct.form($(this).attr('data-id'));
            });

            var dropzoneOptions = {
                url: dropZoneElem.attr('url-upload') + '/' + id_product,
                paramName: 'form[file]',
                maxFilesize: dropZoneElem.attr('data-max-size'),
                addRemoveLinks: true,
                clickable: true,
                thumbnailWidth: 130,
                thumbnailHeight: null,
                acceptedFiles: 'image/*',
                dictDefaultMessage: '<i class="material-icons">perm_media</i><br/>' + translate_javascripts['Drop images here'] + '<br/>' + translate_javascripts['or select files'] + '<br/><small>' + translate_javascripts['files recommandations'] + '<br/>' + translate_javascripts['files recommandations2'] + '</small></div>',
                dictRemoveFile: translate_javascripts['Delete'],
                dictFileTooBig: translate_javascripts['ToLargeFile'],
                dictCancelUpload: translate_javascripts['Delete'],
                sending: function (file, response) {
                    $('#product-images-container .dropzone-expander').addClass('expand').click();
                    errorElem.html('');
                },
                queuecomplete: function () {
                    dropZoneElem.sortable('enable');
                },
                processing: function () {
                    dropZoneElem.sortable('disable');
                },
                success: function (file, response) {
                    //manage error on uploaded file
                    if (response.error !== 0) {
                        errorElem.append('<p>' + file.name + ': ' + response.error + '</p>');
                        this.removeFile(file);
                        return;
                    }

                    //define id image to file preview
                    $(file.previewElement).attr('data-id', response.id);
                    $(file.previewElement).addClass('ui-sortable-handle');
                    if (response.cover === 1) {
                        imagesProduct.updateDisplayCover(response.id);
                    }

                    combinations.refreshImagesCombination();
                },
                error: function (file, response) {
                    var message = '';
                    if ($.type(response) === 'string') {
                        message = response;
                    } else if (response.message) {
                        message = response.message;
                    }

                    if (message === '') {
                        return;
                    }

                    //append new error
                    errorElem.append('<p>' + file.name + ': ' + message + '</p>');

                    //remove uploaded item
                    this.removeFile(file);
                },
                init: function () {
                    //if already images uploaded, mask drop file message
                    if (dropZoneElem.find('.dz-preview').length) {
                        dropZoneElem.addClass('dz-started');
                    }

                    dropZoneElem.find('.openfilemanager').click(function () {
                        dropZoneElem.click();
                    });

                    //init sortable
                    dropZoneElem.sortable({
                        items: "div.dz-preview:not(.disabled)",
                        opacity: 0.9,
                        containment: 'parent',
                        distance: 32,
                        tolerance: 'pointer',
                        cursorAt: { left: 64, top: 64 },
                        cancel: '.disabled',
                        stop: function (event, ui) {
                            var sort = {};
                            $.each(dropZoneElem.find('.dz-preview:not(.disabled)'), function (index, value) {
                                if (!$(value).attr('data-id')) {
                                    sort = false;
                                    return;
                                }
                                sort[$(value).attr('data-id')] = index + 1;
                            });

                            //if sortable ok, update it
                            if (sort) {
                                $.ajax({
                                    type: 'POST',
                                    url: dropZoneElem.attr('url-position'),
                                    data: { json: JSON.stringify(sort) }
                                });
                            }
                        },
                        start: function (event, ui) {
                            //init zindex
                            dropZoneElem.find('.dz-preview').css('zIndex', 1);
                            ui.item.css('zIndex', 10);
                        }
                    });

                    dropZoneElem.disableSelection();
                }
            };

            dropZoneElem.dropzone(jQuery.extend(dropzoneOptions));
        },
        'updateDisplayCover': function (id_image) {
            $('#product-images-dropzone .dz-preview .iscover').remove();
            $('#product-images-dropzone .dz-preview[data-id="' + id_image + '"]')
				.append('<div class="iscover">' + translate_javascripts['Cover'] + '</div>');
        }
    };
})();


var formImagesProduct = (function () {
    var dropZoneElem = $('#product-images-dropzone');
    var formZoneElem = $('#product-images-form-container');

    formZoneElem.magnificPopup({ delegate: 'a.open-image', type: 'image' });

    return {
        'form': function (id) {
            $.ajax({
                url: dropZoneElem.attr('url-update') + '/' + id,
                success: function (response) {
                    formZoneElem.find('#product-images-form').html(response);
                },
                complete: function () {
                    formZoneElem.show();
                }
            });
        },
        'send': function (id) {
            $.ajax({
                type: 'POST',
                url: dropZoneElem.attr('url-update') + '/' + id,
                data: formZoneElem.find('input').serialize(),
                beforeSend: function () {
                    formZoneElem.find('.actions button').prop('disabled', 'disabled');
                    formZoneElem.find('ul.text-danger').remove();
                    formZoneElem.find('*.has-danger').removeClass('has-danger');
                },
                success: function () {
                    if (formZoneElem.find('#form_image_cover:checked').length) {
                        imagesProduct.updateDisplayCover(id);
                    }
                },
                error: function (response) {
                    if (response && response.responseText) {
                        $.each(jQuery.parseJSON(response.responseText), function (key, errors) {
                            var html = '<ul class="list-unstyled text-danger">';
                            $.each(errors, function (key, error) {
                                html += '<li>' + error + '</li>';
                            });
                            html += '</ul>';

                            $('#form_image_' + key).parent().append(html);
                            $('#form_image_' + key).parent().addClass('has-danger');
                        });
                    }
                },
                complete: function () {
                    formZoneElem.find('.actions button').removeAttr('disabled');
                }
            });
        },
        'delete': function (id) {
            modalConfirmation.create(translate_javascripts['Are you sure to delete this?'], null, {
                onContinue: function () {
                    $.ajax({
                        url: dropZoneElem.attr('url-delete') + '/' + id,
                        complete: function () {
                            formZoneElem.find('.close').click();
                            dropZoneElem.find('.dz-preview[data-id="' + id + '"]').remove();
                            combinations.refreshImagesCombination();
                        }
                    });
                }
            }).show();
        },
        'close': function () {
            formZoneElem.find('#product-images-form').html('');
            formZoneElem.hide();
        }
    };
})();
