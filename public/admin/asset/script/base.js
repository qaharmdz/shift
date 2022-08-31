/*
 * Table of Content:
 *
 * # Global Defaults
 *   - Form change confirmation
 *   - AJAX setup
 *   - UIkit components
 *
 * # Plugins
 *   - $.fn.shift.notify()
 *   - $.fn.shift.goNotify
 *
 * # IIDE (Immediate Invoked Data Expressions)
 *   - data-form-monitor
 *   - data-form-submit
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
    } else if ('response' in data) {
        if (jqxhr.status === 404) {
            data.response += '<div class="uk-text-meta uk-text-break uk-margin-small-top">' + settings.url.split(/[?#]/)[0] + '</div>';
        }

        $.fn.shift.goNotify('danger', data.response);
    } else {
        $.fn.shift.goNotify('danger', '<span style="font-size:20px;line-height:1em;margin: 0 5px;display:block;">' + jqxhr.status + ' ' + exception + '</span>');
    }
});

//=== UIkit components
UIkit.mixin({
    data: {
        mode: 'click',
        animation: ['uk-animation-slide-bottom-small']
    }
}, 'dropdown');


/*
 * Plugins
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
                    timeout : 15000
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
})(jQuery);


/*
 * Immediate Invoked Data Expressions (IIDE)
 * ======================================================================== */

/**
 * For new created element retrigger IIDE
 * Ex: $(document).trigger('IIDE.form_monitor');
 *
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
                action: 'apply',
            }, $(el).data('formSubmit')),
            form = $(opt.form);

        opt.timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;

        $(el).on('click', function() {
            $(document).trigger('IIDE.form_submit.before');

            $(form).ajaxSubmit({
                dataType : 'json',
                data : opt,
                beforeSend : function(data) {
                    $('.main-content *').removeClass('uk-form-danger');
                    $('.uk-text-meta.uk-text-danger').remove();
                    $.fn.shift.goNotify('process', shift.i18n.saving);
                },
                success : function(data) {
                    shift.formChanged = false;

                    setTimeout(function() {
                        $.fn.shift.goNotify('success', data.message);
                    }, 200);

                    if (data.redirect) {
                        $.fn.shift.goNotify('redirect');

                        setTimeout(function() {
                            window.location.replace(data.redirect);
                        }, 1000);
                    }
                },
                error : function(xhr) {
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
