
/**
 * Navigation management
 */
var nav = (function () {
    return {
        'init': function () {
            /** Manage tabls hash routes */
            var hash = document.location.hash;
            var formNav = $("#form-nav");
            var prefix = 'tab-';
            if (hash) {
                formNav.find("a[href='" + hash.replace(prefix, '') + "']").tab('show');
            }

            formNav.find("a").on('shown.bs.tab', function (e) {
                if (e.target.hash) {
                    onTabSwitch(e.target.hash);
                    window.location.hash = e.target.hash.replace('#', '#' + prefix);
                }
            });

            /** on tab switch */
            function onTabSwitch(currentTab) {
                if (currentTab === '#step2') {
                    /** each switch to price tab, reload combinations into specific price form */
                    specificPrices.refreshCombinationsList();
                }
            }
        }
    };
})();



/**
 * Form management
 */
var form = (function () {
    var elem = $('#form');

    function send(redirect, target) {
        // target value by default
        if (typeof (target) == 'undefined') {
            target = false;
        }
        var data = $('input, textarea, select', elem).not(':input[type=button], :input[type=submit], :input[type=reset]').serialize();
        $.ajax({
            type: 'POST',
            data: data,
            beforeSend: function () {
                $('#submit', elem).attr('disabled', 'disabled');
                $('.btn-submit', elem).attr('disabled', 'disabled');
                $('ul.text-danger').remove();
                $('*.has-danger').removeClass('has-danger');
            },
            success: function (response) {
                /** alert("debug info:" + response); **/
                if (redirect) {
                    if (target) {
                        window.open(redirect, target);
                    } else {
                        window.location = redirect;
                    }
                }
                showSuccessMessage(translate_javascripts['Form update success']);
            },
            error: function (response) {
                var tabsWithErrors = [];
                showErrorMessage(translate_javascripts['Form update errors']);

                $.each(jQuery.parseJSON(response.responseText), function (key, errors) {
                    tabsWithErrors.push(key);

                    var html = '<ul class="list-unstyled text-danger">';
                    $.each(errors, function (key, error) {
                        html += '<li>' + error + '</li>';
                    });
                    html += '</ul>';

                    $('#form_' + key).parent().append(html);
                    $('#form_' + key).parent().addClass('has-danger');
                });

                /** find first tab with error, then switch to it */
                var tabIndexError = tabsWithErrors[0].split('_')[0];
                $('#form-nav li a[href="#' + tabIndexError + '"]').tab('show');

                /** scroll to 1st error */
                $('html, body').animate({
                    scrollTop: $('.has-danger').first().offset().top - $('.page-head').height() - $('.navbar-header').height()
                }, 500);
            },
            complete: function () {
                $('#submit', elem).removeAttr('disabled');
                $('.btn-submit', elem).removeAttr('disabled');
            }
        });
    }

    function switchLanguage(iso_code) {
        $('div.translations.tabbable > div > div.tab-pane:not(.translation-label-' + iso_code + ')').removeClass('active');
        $('div.translations.tabbable > div > div.tab-pane.translation-label-' + iso_code).addClass('active');
    }

    return {
        'init': function () {
            /** prevent form submit on ENTER keypress */
            jwerty.key('enter', function (e) {
                e.preventDefault();
            });

            /** create keyboard event for save */
            jwerty.key('ctrl+S', function (e) {
                e.preventDefault();
                send();
            });

            /** create keyboard event for save & duplicate */
            jwerty.key('ctrl+D', function (e) {
                e.preventDefault();
                send($('.product-footer .duplicate').attr('data-redirect'));
            });

            /** create keyboard event for save & new */
            jwerty.key('ctrl+P', function (e) {
                e.preventDefault();
                send($('.product-footer .new-product').attr('data-redirect'));
            });

            /** create keyboard event for save & go catalog */
            jwerty.key('ctrl+Q', function (e) {
                e.preventDefault();
                send($('.product-footer .go-catalog').attr('data-redirect'));
            });

            elem.submit(function (event) {
                event.preventDefault();
                send();
            });

            elem.find('#form_switch_language').change(function (event) {
                event.preventDefault();
                switchLanguage(event.target.value);
            });

            /** on save with duplicate|new */
            $('.btn-submit', elem).click(function () {
                send($(this).attr('data-redirect'), $(this).attr('target'));
            });

            /** on active field change, send form */
            $('#form_step1_active', elem).on('change', function () {
                var active = $(this).prop('checked');
                $('.for-switch.online-title').toggle(active);
                $('.for-switch.offline-title').toggle(!active);
                // update link preview
                var urlActive = $('#product_form_preview_btn').attr('data-redirect');
                var urlDeactive = $('#product_form_preview_btn').attr('data-url_deactive');
                $('#product_form_preview_btn').attr('data-redirect', urlDeactive);
                $('#product_form_preview_btn').attr('data-url_deactive', urlActive);
                // update product
                send();
            });

            /** on delete product */
            $('.product-footer .delete', elem).click(function (e) {
                e.preventDefault();
                var _this = $(this);
                modalConfirmation.create(translate_javascripts['Are you sure to delete this?'], null, {
                    onContinue: function () {
                        window.location = _this.attr('href');
                    }
                }).show();
            });

            /** show rendered form after page load */
            $(window).load(function () {
                $('#form-loading').fadeIn();
                //imagesProduct.expander();
            });
        },
        'send': function () {
            send();
        },
        'switchLanguage': function (iso_code) {
            switchLanguage(iso_code);
        }
    };
})();



/**
 * modal confirmation management
 */
var modalConfirmation = (function () {
    var modal = $('#confirmation_modal');
    var actionsCallbacks = {
        onCancel: function () {
            return;
        },
        onContinue: function () {
            return;
        }
    };

    modal.find('button.cancel').click(function () {
        if (typeof actionsCallbacks.onCancel === 'function') {
            actionsCallbacks.onCancel();
        }
        modalConfirmation.hide();
    });

    modal.find('button.continue').click(function () {
        if (typeof actionsCallbacks.onContinue === 'function') {
            actionsCallbacks.onContinue();
        }
        modalConfirmation.hide();
    });

    return {
        'create': function (content, title, callbacks) {
            if (title != null) {
                modal.find('.modal-title').html(title);
            }
            if (content != null) {
                modal.find('.modal-body').html(content);
            }

            actionsCallbacks = callbacks;
            return this;
        },
        'show': function () {
            modal.modal('show');
        },
        'hide': function () {
            modal.modal('hide');
        }
    };
})();
