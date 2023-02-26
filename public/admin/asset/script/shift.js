/*
 * Table of Content:
 *
 * # Global Defaults
 *   - Form change confirmation
 *   - AJAX setup
 *   - UIkit components
 *   - Select2
 *
 * # jQuery Plugins - IIFE (Immediately Invoked Function Expression)
 *   - $.fn.shift.notify()
 *   - $.fn.shift.goNotify()
 *   - $.fn.shift.confirm()
 *   - $.fn.shift.dtAction()
 *
 * # IIDE (Immediate Invoked Data Expressions)
 *   - data-form-monitor
 *   - data-form-submit
 *   - data-editor
 *   - data-datepicker
 *   - data-selecttwo
 *   - data-format-date
 *
 * # Functions
 *   - formatDate()
 *
 * ======================================================================== */

if (typeof shift !== 'undefined' && shift.env.development === 1) {
    console.log(shift);
}


/*
 * Global Defaults
 * ======================================================================== */

 //=== Form change confirmation
window.onbeforeunload = function() {
    if (shift.formChanged) {
        return shift.i18n.confirm_change;
    }
};

//=== AJAX Setup
$.ajaxSetup({
    cache: false,
});
$(document).ajaxComplete(function(event, jqxhr, options) {
    let data = jqxhr.responseJSON ? jqxhr.responseJSON : JSON.parse(jqxhr.responseText);

    if (shift.env.development === 1 && 'debug' in data) {
        console.log('# Shift CMS debug\n', options.url + '\n', data.debug);
    }
});
$(document).ajaxError(function(event, jqxhr, settings, exception) {
    let data = jqxhr.responseJSON ? jqxhr.responseJSON : JSON.parse(jqxhr.responseText);

    if (shift.env.development === 1) {
        console.error('# Shift CMS error\n', jqxhr.status + ' ' + exception + '\n', jqxhr);
    }

    if ('redirect' in data) {
        window.location.replace(data.redirect);
    } else if ('title' in data) {
        let output = '<div>' + data.title + '</div>';

        if (data.message) {
            output += '<div class="uk-text-base-color uk-text-break uk-margin-small-top">' + data.message + '</div>';
        }
        if (shift.env.development === 1) {
            output += '<div class="uk-text-meta uk-text-break uk-margin-small-top">' + settings.url.split(/[?#]/)[0] + '</div>'; // Ajax URL
        }

        $.fn.shift.goNotify('danger', output);
    } else if ('response' in data) {
        let output = '<div>' + data.response + '</div>';

        if (shift.env.development === 1) {
            output += '<div class="uk-text-meta uk-text-break uk-margin-small-top">' + settings.url.split(/[?#]/)[0] + '</div>'; // Ajax URL
        }

        $.fn.shift.goNotify('danger', output);
    } else {
        $.fn.shift.goNotify('danger', '<span style="font-size:20px;line-height:1em;margin: 0 5px;display:block;">' + jqxhr.status + ' ' + exception + '</span>');
    }
});

//=== UIkit components
UIkit.mixin({
    data: {
        mode: 'click',
        animation: ['uk-animation-slide-bottom-small'],
    }
}, 'dropdown');
UIkit.mixin({
    data: {
        animation: ['uk-animation-fade uk-animation-fast']
    }
}, 'tab');

//=== Select2
if (jQuery().select2) {
    $.fn.select2.defaults.set('language', {
        noResults : function() { return shift.i18n.no_results; },
    });
}


/*
 * jQuery Plugins - IIFE (Immediately Invoked Function Expression)
 * ======================================================================== */

(function($) {
    $.fn.shift = {}; // global namespace

    /**
     * @depedency UIkit.notification
     *
     * # Usage
     * $.fn.shift.notify({
     *      message : 'Question..',
     *      icon    : '<span uk-icon="icon:question;ratio:1.5"></span>'
     * });
     *
     * # Global setter
     * $.fn.shift.notify.defaults.timeout = 3500; // 3.5 second close
     */
    $.fn.shift.notify = function(options) {
        let opt = $.extend({}, $.fn.shift.notify.defaults, options);

        if (opt.clear) { UIkit.notification.closeAll(); }
        if (!opt.message) { return; }

        UIkit.notification({
            message : opt.icon + ' <div>' + opt.message + '</div>',
            status  : opt.status + ' uk-icon-emphasis',
            timeout : opt.timeout,
            group   : opt.group,
            pos     : opt.pos
        });
    };

    $.fn.shift.notify.defaults = {
        message : '',
        icon    : '<span uk-spinner></span>',
        status  : '',   // primary, success, warning, danger
        timeout : 3000,
        pos     : 'top-center',
        group   : null,
        clear   : false
    };

    $.fn.shift.goNotify = function(mode, message) {
        switch(mode) {
            case 'success':
                $.fn.shift.notify({
                    message : message !== undefined ? message : shift.i18n.success_save,
                    icon    : '<span uk-icon="icon:check;ratio:1.5"></span>',
                    status  : mode,
                    clear   : true
                });
                break;
            case 'warning':
                $.fn.shift.notify({
                    message : message !== undefined ? message : shift.i18n.error_form,
                    icon    : '<span uk-icon="icon:warning;ratio:1.5"></span>',
                    status  : mode,
                    timeout : 15000,
                    clear   : true
                });
                break;
            case 'danger':
                $.fn.shift.notify({
                    message : message !== undefined ? message : shift.i18n.error_general,
                    icon    : '<span uk-icon="icon:close;ratio:1.5"></span>',
                    status  : mode,
                    timeout : 15000,
                    clear   : true
                });
                break;
            case 'redirect':
                $.fn.shift.notify({
                    message : message !== undefined ? message : shift.i18n.redirecting,
                    icon    : '<span uk-icon="icon:link;ratio:1.5"></span>',
                    status  : 'primary',
                    timeout : 15000,
                    clear   : false
                });
                break;
            case 'process':
            default:
                message = message !== undefined ? message : shift.i18n.processing;
                $.fn.shift.notify({
                    message : message,
                    icon    : '<span uk-spinner></span>',
                    timeout : 120000, // 2 minutes
                    clear   : true
                });
        }
    };

    /**
     * @depedency UIkit.modal
     *
     * # Usage
     * $.fn.shift.confirm({
     *     title        : 'Heading',
     *     message      : 'Message here',
     *     onConfirm    : function() { ... }
     * });
     *
     * # Override global setter
     * $.extend($.fn.shift.confirm.defaults, {
     *     labelOk      : 'Yes, I'm sure',
     *     labelCancel  : 'Cancel',
     *     onConfirm    : function() {}
     * });
     * - or -
     * $.fn.shift.confirm.defaults.onConfirm = function() {};
     */
    $.fn.shift.confirm = function(options) {
        let opt     = $.extend({}, $.fn.shift.confirm.defaults, options),
            content = '<div class="uk-text-center">' + (opt.title ? '<h2 class="uk-modal-title">' + opt.title + '</h2>' : '') + '<div>' + opt.message + '</div></div>';

        UIkit.notification.closeAll();
        UIkit.modal.confirm(content, {
            bgClose     : false,
            escClose    : false,
            labels      : {
                ok      : opt.labelOk,
                cancel  : opt.labelCancel
            }
        }).then(opt.onConfirm, opt.onCancel);
    };

    $.fn.shift.confirm.defaults = {
        title       : '',
        message     : shift.i18n.are_you_sure,
        labelOk     : shift.i18n.yes_sure,
        labelCancel : shift.i18n.cancel,
        onConfirm   : function() { return true; },
        onCancel    : function() { return false; }
    };

    /**
     * Bulk action AJAX processing (designed for dataTables)
     *
     * # Usage
     * # Override global setter
     * $.extend($.fn.shift.dtAction.defaults, {
     *     msgValidate : 'Select min 1 item to continue!',
     *     msgBefore   : 'Processing..',
     *     msgSuccess  : 'Successfully executed!',
     *     msgError    : 'Error occured, try again later!',
     * });
     */
    $.fn.shift.dtAction = function(options) {
        let opt = $.extend({}, $.fn.shift.dtAction.defaults, options);

        if (!opt.url) { return; }
        if (!opt.data.item) {
            opt.data.item = $('input:checkbox[name="' + opt.target + '"]:checked').map(function() {
                if ($(this).is(':checked')) {
                    return $(this).val();
                }
            }).get().join(',');
        }

        // Check items
        if (!opt.data.item) {
            $.fn.shift.goNotify('warning', opt.msgValidate);
            return false;
        }

        opt.validate(dtActionProceed);

        function dtActionProceed() {
            $.ajax({
                type    : 'POST',
                url     : opt.url,
                data    : opt.data,
                dataType: 'json',
                beforeSend: function() {
                    $.fn.shift.goNotify('process', opt.msgBefore);
                },
                success: function(data) {
                    opt.data.item = ''; // reset
                    $.fn.shift.goNotify('success', data.message ? data.message : opt.msgSuccess);

                    opt.onSuccess(this, data);

                    // changed row glow effect
                    if (opt.glow) {
                        setTimeout(function() {
                            $.each(data.items, function(i) {
                                $('.dt-row-' + data.items[i]).addClass(opt.glowClass);
                                setTimeout(function () {
                                    $('.dt-row-' + data.items[i]).removeClass(opt.glowClass);
                                }, 1000);
                            });
                        }, 750);
                    }
                }
            });
        }
    };

    $.fn.shift.dtAction.defaults = {
        url         : '',
        data        : [],
        target      : 'dtaction[]', // input:checkbox name
        msgValidate : shift.i18n.select_min_one,
        msgBefore   : shift.i18n.processing,
        msgSuccess  : shift.i18n.success_update,
        msgError    : shift.i18n.error_general,
        glow        : true,
        glowClass   : 'uk-active',
        validate    : function(dtActionProceed) { dtActionProceed(); },
        onSuccess   : function() {}
    };
})(jQuery);


/*
 * Immediate Invoked Data Expressions (IIDE)
 * ======================================================================== */

/**
 * To retrigger IIDE on dynamically created element,
 * ex: $(document).trigger('IIDE.form_monitor');
 */
$(document).ready(function()
{
    $(document).trigger('IIDE.init');
});

$(document).on('IIDE.init IIDE.form_monitor', function(event)
{
    /**
     * Monitor form input change
     *
     * @usage
     * <form data-form-monitor>..</form>
     * <div data-form-monitor='{"target":"input"}'>..</div>
     */
    $('[data-form-monitor]').each(function() {
        let el  = this,
            opt = $.extend({
                target : 'input, select, textarea',
            }, $(el).data('formMonitor'));

        $(el).on('input change paste', opt.target, function() {
            shift.formChanged = true;
        });
    });
});

$(document).on('IIDE.init IIDE.form_submit', function(event)
{
    /**
     * Form submit
     *
     * @usage
     * <a data-form-submit='{"form":"#form-id", "action":"apply", "extra_key":"extra_val"}'>...</a>
     */
    $('[data-form-submit]').each(function() {
        let el  = this,
            opt = $.extend({
                form : '',
                action: 'save',
            }, $(el).data('formSubmit')),
            form = $(opt.form);

        if (!form.length) { return; }

        opt.timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;

        $(el).on('click', function() {
            $(document).trigger('IIDE.form_submit.before');
            $(el).prop('disabled', true).prepend('<span uk-spinner="ratio:0.6" class="js-data-form-submit" style="margin-left:-5px;margin-right:8px;"></span>');

            if (shift.editor.instances) {
                $.each(shift.editor.instances, function(elid, editor) {
                    $('#' + elid).text(editor.getData());
                });
            }

            $(form).ajaxSubmit({
                dataType : 'json',
                data : opt,
                beforeSend: function(data) {
                    $('.main-content *').removeClass('uk-form-danger');
                    $('.uk-text-meta.uk-text-danger').remove();
                    $.fn.shift.goNotify('process', shift.i18n.saving);
                },
                complete: function() {
                    $(el).prop('disabled', false);
                    $('.js-data-form-submit').remove();
                },
                success: function(data) {
                    shift.formChanged = false;

                    setTimeout(function() {
                        $.fn.shift.goNotify('success', data.message);

                        if (data.redirect) {
                            $.fn.shift.goNotify('redirect');

                            setTimeout(function() {
                                window.location.replace(data.redirect);
                            }, 1000);
                        }
                    }, 200);
                },
                error: function(xhr) {
                    let data = xhr.responseJSON;

                    if (data !== undefined) {
                        $.each(data.items, function(name, errorMsg) {
                            $(form[0][name]).closest('.uk-margin').addClass('uk-form-danger');
                            $('a[href="#' + $(form[0][name]).closest('.uk-switcher div[id]').attr('id') +'"]').addClass('uk-form-danger');

                            if (errorMsg) {
                                $(form[0][name]).closest('.uk-form-controls').append('<div class="uk-text-meta uk-text-danger">' + errorMsg + '</div>');
                            }
                        });
                    }
                }
            });
        });
    });
});

$(document).on('IIDE.init IIDE.editor', function(event)
{
    /**
     * CKEditor
     *
     * @usage
     * <textarea data-editor></textarea>
     * <textarea data-editor='{"mode":"basic"}'></textarea>
     */
    $('[data-editor]').each(function(i) {
        let el   = this,
            elid = euid('ckeditor-xxxxxx'),
            opt  = $.extend({
                mode    : 'default', // basic, default, all, custom
                wrapper : 'wrapper-' + elid,
                toolbar : [],
            }, $(el).data('editor'));

        $(el).attr('id', elid);

        switch (opt.mode) {
            case 'basic':
                opt.toolbar = shift.editor.mode_basic;
                break;
            case 'all':
                opt.toolbar = shift.editor.mode_all;
                break;
            case 'custom':
                // Do nothing
                break;
            default:
                opt.toolbar = shift.editor.mode_default;
        }

        ClassicEditor
            .create(document.getElementById(elid), {
                toolbar: {
                    items: opt.toolbar,
                }
            })
            .then(function(editor) {
                shift.editor.instances[elid] = editor;

                $('#' + elid).parent().addClass(opt.wrapper).addClass('ckeditor-mode-' + opt.mode);

                let wordCount = editor.plugins.get('WordCount');
                $('.' + opt.wrapper).append('<div class="ckeditor-wordcount"></div>')
                $('.' + opt.wrapper + ' .ckeditor-wordcount').html(wordCount.wordCountContainer);
            })
            .catch(function(error) {
                console.log(error);
            });
    });
});

$(document).on('IIDE.init IIDE.datepicker', function(event)
{
    /**
     * Add datepicker and optional time picker to input
     *
     * @usage
     * <input type="text" data-datepicker>
     * <input type="text" data-datepicker='{"time":true}'>
     */
    $('[data-datepicker]').each(function(i) {
        let el  = this,
            opt = $.extend({
                time : false,
            }, $(el).data('datepicker'));

        $(el).wrap('<div class="uk-inline"></div>')
             .before('<i class="uk-form-icon bi bi-calendar3"></i>');

        flatpickr(el, {
            allowInput: true,
            enableTime: opt.time,
            enableSeconds: opt.time,
            time_24hr: true,
        });
    });
});

$(document).on('IIDE.init IIDE.data-selecttwo', function(event)
{
    /**
     * Select2
     *
     * @usage
     * <select data-selecttwo><option>...<option></select>
     * <select data-selecttwo='{"tags":true}'><option>...<option></select>
     */
    $('[data-selecttwo]').each(function(i) {
        let el  = this,
            opt = $.extend({
                tags : false,
                placeholder : shift.i18n['-select-']
            }, $(el).data('selecttwo'));

        console.log(opt);
        $(el).select2({
            tags : opt.tags,
            closeOnSelect : opt.tags ? false : true,
            tokenSeparators : opt.tags ? [','] : [],
            dropdownCssClass : opt.tags ? 'select2-tags-dropdown' : '',
        });
    });
});

$(document).on('IIDE.init IIDE.format_date', function(event)
{
    /**
     * Format date from UTC to local browser timezone
     *
     * @usage
     * <div data-format-date="Y-m-d H:i:s"></div>
     */
    $('[data-format-date]').each(function() {
        let el   = this,
            date = $(el).data('formatDate');

        $html = date  ? '<span title="' + date + ' UTC">' + formatDate(date) + '</span>' : '<i>n/a</i>';
        $(el).html($html);
    });
});


/*
 * Functions
 * ======================================================================== */

 /**
 * Datetime UTC to local timezone
 *
 * @param  string   datetime  Y-m-d H:i:s UTC
 * @return string
 */
function formatDate(datetime) {
    if (!datetime) { return ''; }

    let dateUTC = new Date(datetime + ' UTC'),
        options = {
            day: '2-digit', month: 'short', year: 'numeric',
            hour: '2-digit', minute: '2-digit', hour12: false,
            timeZone: Intl.DateTimeFormat().resolvedOptions().timeZone,
            timeZoneName: 'short',
        };

    return new Intl.DateTimeFormat('en-US', options).format(dateUTC).replace (/,/g, '');
}

/**
 * Element unique id
 * https://stackoverflow.com/a/2117523
 */
function euid(format) {
    var euid = format ? format : 'sh-xxx-xxxxxx';

    return euid.replace(new RegExp('x', 'g'), function() {
        return (Math.floor(Math.random() * 10));
    });
}
