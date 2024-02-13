/* jshint -W097, -W117 */
/* globals $, document, shift */

"use strict";

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
 *   - $.fn.shift.prompt()
 *   - $.fn.shift.dtAction()
 *
 * # IIDE (Immediate Invoked Data Expressions)
 *   - data-form-monitor
 *   - data-form-submit
 *   - data-editor
 *   - data-mediamanager
 *   - data-datepicker
 *   - data-select-s2
 *   - data-select-switcher
 *   - data-format-date
 *
 * # Functions
 *   - formatDate()
 *
 * ======================================================================== */

if (typeof shift !== "undefined" && shift.env.development === 1) {
    console.log(shift);
}

/*
 * Global Defaults
 * ======================================================================== */

//=== Form change confirmation
window.onbeforeunload = function () {
    if (shift.formChanged) {
        return shift.i18n.confirm_change;
    }
};

//=== AJAX Setup
$.ajaxSetup({
    cache: false,
});
$(document).ajaxComplete(function (event, jqxhr, options) {
    if (shift.env.development === 1) {
        let data = jqxhr.responseJSON ? jqxhr.responseJSON : {};

        if (!Object.keys(data).length) {
            try {
                data = JSON.parse(jqxhr.responseText);
            } catch (e) {}
        }

        if ("debug" in data) {
            console.log("# Shift CMS debug\n", options.url + "\n", data.debug);
        }
    }
});
$(document).ajaxError(function (event, jqxhr, settings, exception) {
    let data = jqxhr.responseJSON
        ? jqxhr.responseJSON
        : JSON.parse(jqxhr.responseText);

    if (shift.env.development === 1) {
        console.error(
            "# Shift CMS error\n",
            jqxhr.status + " " + exception + "\n",
            jqxhr
        );
    }

    // Close all modal
    $(document)
        .find(
            ".uk-modal-close,.uk-modal-close-default,.uk-modal-close-outside,.uk-modal-close-full"
        )
        .trigger("click");

    if ("redirect" in data) {
        window.location.replace(data.redirect);
    } else if ("title" in data) {
        let output = "<div>" + data.title + "</div>";

        if (data.message) {
            output +=
                '<div class="uk-text-base-color uk-text-break uk-margin-small-top">' +
                data.message +
                "</div>";
        }
        if (shift.env.development === 1) {
            output +=
                '<div class="uk-text-meta uk-text-break uk-margin-small-top">' +
                settings.url.split(/[?#]/)[0] +
                "</div>";
        }

        $.fn.shift.goNotify("danger", output);
    } else if ("response" in data) {
        let output = "<div>" + data.response + "</div>";

        if (shift.env.development === 1) {
            output +=
                '<div class="uk-text-meta uk-text-break uk-margin-small-top">' +
                settings.url.split(/[?#]/)[0] +
                "</div>";
        }

        $.fn.shift.goNotify("danger", output);
    } else {
        $.fn.shift.goNotify(
            "danger",
            '<span style="font-size:20px;line-height:1em;margin: 0 5px;display:block;">' +
                jqxhr.status +
                " " +
                exception +
                "</span>"
        );
    }
});

//=== UIkit components
UIkit.mixin({
    data: {
        offset: 10,
        mode: "click",
    },
});
UIkit.mixin(
    {
        data: {
            offset: 8,
            mode: "click",
            animation: ["uk-animation-slide-bottom-small"],
        },
    },
    "dropdown"
);
UIkit.mixin(
    {
        data: {
            animation: ["uk-animation-fade uk-animation-fast"],
        },
    },
    "tab"
);

//=== Select2
if (jQuery().select2) {
    $.fn.select2.defaults.set("language", {
        noResults: function () {
            return shift.i18n.no_results;
        },
    });
}

/*
 * jQuery Plugins - IIFE (Immediately Invoked Function Expression)
 * ======================================================================== */

(function ($) {
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
    $.fn.shift.notify = function (options) {
        let opt = $.extend({}, $.fn.shift.notify.defaults, options);

        if (!opt.message) {
            return;
        }
        if (opt.clear) {
            UIkit.notification.closeAll();
        }

        UIkit.notification({
            message: opt.icon + " <div>" + opt.message + "</div>",
            status: opt.status + " uk-icon-emphasis",
            timeout: opt.timeout,
            group: opt.group,
            pos: opt.pos,
        });
    };

    $.fn.shift.notify.defaults = {
        message: "",
        icon: "<span uk-spinner></span>",
        status: "", // primary, success, warning, danger
        timeout: 3000,
        pos: "top-center",
        group: null,
        clear: false,
    };

    $.fn.shift.goNotify = function (mode, message) {
        switch (mode) {
            case "success":
                $.fn.shift.notify({
                    message:
                        message !== undefined
                            ? message
                            : shift.i18n.success_save,
                    icon: '<span uk-icon="icon:check;ratio:1.5"></span>',
                    status: mode,
                    clear: true,
                });
                break;
            case "warning":
                $.fn.shift.notify({
                    message:
                        message !== undefined ? message : shift.i18n.error_form,
                    icon: '<span uk-icon="icon:warning;ratio:1.5"></span>',
                    status: mode,
                    timeout: 15000,
                    clear: true,
                });
                break;
            case "danger":
                $.fn.shift.notify({
                    message:
                        message !== undefined
                            ? message
                            : shift.i18n.error_general,
                    icon: '<span uk-icon="icon:close;ratio:1.5"></span>',
                    status: mode,
                    timeout: 15000,
                    clear: true,
                });
                break;
            case "redirect":
                $.fn.shift.notify({
                    message:
                        message !== undefined
                            ? message
                            : shift.i18n.redirecting,
                    icon: '<span uk-icon="icon:link;ratio:1.5"></span>',
                    status: "primary",
                    timeout: 15000,
                    clear: false,
                });
                break;
            case "process":
            default:
                message =
                    message !== undefined ? message : shift.i18n.processing;
                $.fn.shift.notify({
                    message: message,
                    icon: "<span uk-spinner></span>",
                    timeout: 120000, // 2 minutes
                    clear: true,
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
    $.fn.shift.confirm = function (options) {
        let opt = $.extend({}, $.fn.shift.confirm.defaults, options),
            content =
                '<div class="uk-text-center shift-modal-confirm">' +
                (opt.title
                    ? '<h2 class="uk-modal-title">' + opt.title + "</h2>"
                    : "") +
                "<div>" +
                opt.message +
                "</div></div>";

        if (opt.clear) {
            UIkit.notification.closeAll();
        }

        UIkit.modal
            .confirm(content, {
                bgClose: false,
                escClose: false,
                labels: {
                    ok: opt.labelOk,
                    cancel: opt.labelCancel,
                },
            })
            .then(opt.onConfirm, opt.onCancel);
    };

    $.fn.shift.confirm.defaults = {
        title: "",
        message: shift.i18n.are_you_sure,
        labelOk: shift.i18n.yes_sure,
        labelCancel: shift.i18n.cancel,
        onConfirm: function () {
            return true;
        },
        onCancel: function () {
            return false;
        },
        clear: false,
    };

    /**
     * @depedency UIkit.modal
     *
     * # Usage
     * $.fn.shift.prompt({
     *     message      : 'Label',
     *     value        : 'Placeholder',
     *     onAction     : function() { ... }
     * });
     *
     * # Override global setter
     * $.extend($.fn.shift.prompt.defaults, {
     *     labelOk      : 'Submit',
     *     labelCancel  : 'Cancel',
     *     onAction     : function(value) {}
     * });
     *
     */
    $.fn.shift.prompt = function (options) {
        let opt = $.extend({}, $.fn.shift.prompt.defaults, options);

        if (opt.clear) {
            UIkit.notification.closeAll();
        }

        UIkit.modal
            .prompt(opt.message, opt.value, {
                bgClose: false,
                escClose: false,
                stack: true,
                labels: {
                    ok: opt.labelOk,
                    cancel: opt.labelCancel,
                },
            })
            .then(opt.onAction);
    };

    $.fn.shift.prompt.defaults = {
        message: "",
        value: "",
        labelOk: shift.i18n.submit,
        labelCancel: shift.i18n.cancel,
        onAction: function (value) {},
        clear: false,
    };

    /**
     * DataTables bulk action AJAX processing
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
    $.fn.shift.dtAction = function (options) {
        let opt = $.extend({}, $.fn.shift.dtAction.defaults, options);

        if (!opt.url) {
            return;
        }
        if (!opt.data.item) {
            opt.data.item = $(
                'input:checkbox[name="' + opt.target + '"]:checked'
            )
                .map(function () {
                    if ($(this).is(":checked")) {
                        return $(this).val();
                    }
                })
                .get()
                .join(",");
        }

        // Check items
        if (!opt.data.item) {
            $.fn.shift.goNotify("warning", opt.msgValidate);
            return false;
        }

        opt.validate(dtActionProceed);

        function dtActionProceed() {
            $.ajax({
                type: "POST",
                url: opt.url,
                data: opt.data,
                dataType: "json",
                beforeSend: function () {
                    $.fn.shift.goNotify("process", opt.msgBefore);
                },
                success: function (data) {
                    $.fn.shift.goNotify(
                        "success",
                        data.message ? data.message : opt.msgSuccess
                    );

                    opt.onSuccess(this, data);
                },
                complete: function (jqxhr) {
                    let uiDropdown = $(".uk-dropdown.uk-open");
                    if (uiDropdown.length) {
                        UIkit.dropdown(uiDropdown).hide(0);
                    }

                    opt.onComplete(this, jqxhr);
                },
            });
        }
    };

    $.fn.shift.dtAction.defaults = {
        url: "",
        data: [],
        target: "dtaction[]", // input:checkbox name
        msgValidate: shift.i18n.select_min_one,
        msgBefore: shift.i18n.processing,
        msgSuccess: shift.i18n.success_update,
        msgError: shift.i18n.error_general,
        validate: function (dtActionProceed) {
            dtActionProceed();
        },
        onSuccess: function () {},
        onComplete: function () {},
    };
})(jQuery);

/*
 * Immediate Invoked Data Expressions (IIDE)
 * ======================================================================== */

/**
 * To retrigger IIDE on dynamically created element,
 * ex: $(document).trigger('IIDE.form_monitor');
 */
$(document).ready(function () {
    $(document).trigger("IIDE.init");
});

$(document).on("IIDE.init IIDE.form_monitor", function (event) {
    /**
     * Monitor form input change
     *
     * @usage
     * <form data-form-monitor>..</form>
     * <div data-form-monitor='{"target":"input"}'>..</div>
     */
    $("[data-form-monitor]").each(function () {
        let el = this,
            opt = $.extend(
                {
                    target: "input, select, textarea",
                },
                $(el).data("formMonitor")
            );

        $(el).on("input change paste", opt.target, function () {
            shift.formChanged = true;
        });
    });
});

$(document).on("IIDE.init IIDE.form_submit", function (event) {
    /**
     * Form submit
     *
     * @usage
     * <a data-form-submit='{"form":"#form-id", "action":"apply", "extra_key":"extra_val"}'>...</a>
     */
    $("[data-form-submit]").each(function () {
        let el = this,
            opt = $.extend(
                {
                    form: "",
                    action: "save",
                },
                $(el).data("formSubmit")
            ),
            form = $(opt.form);

        if (!form.length) {
            return;
        }

        opt.timezone = getVisitorTimezone();

        $(el).on("click", function () {
            $(document).trigger("IIDE.form_submit.before");
            $(el)
                .prop("disabled", true)
                .prepend(
                    '<span uk-spinner="ratio:0.6" class="js-form-submit-spinner" style="margin-left:-5px;margin-right:8px;"></span>'
                );

            $(form).ajaxSubmit({
                dataType: "json",
                data: opt,
                beforeSend: function (data) {
                    $(".main-content *").removeClass("uk-form-danger");
                    $(".uk-text-meta.uk-text-danger").remove();
                    $.fn.shift.goNotify("process", shift.i18n.saving);
                },
                success: function (data) {
                    shift.formChanged = false;

                    // TODO: data.items to update the inputs, ex: content/post url-alias

                    setTimeout(function () {
                        $.fn.shift.goNotify("success", data.message);

                        if (data.redirect) {
                            $.fn.shift.goNotify("redirect");

                            setTimeout(function () {
                                window.location.replace(data.redirect);
                            }, 1000);
                        }
                    }, 200);
                },
                error: function (xhr) {
                    let data = xhr.responseJSON;

                    if (data !== undefined) {
                        $.each(data.items, function (name, errorMsg) {
                            $(form[0][name])
                                .closest(".uk-margin")
                                .addClass("uk-form-danger");
                            $(
                                'a[href="#' +
                                    $(form[0][name])
                                        .closest(".uk-switcher div[id]")
                                        .attr("id") +
                                    '"]'
                            ).addClass("uk-form-danger");

                            if (errorMsg) {
                                $(form[0][name])
                                    .closest(".uk-form-controls")
                                    .append(
                                        '<div class="uk-text-meta uk-text-danger">' +
                                            errorMsg +
                                            "</div>"
                                    );
                            }
                        });
                    }
                },
                complete: function () {
                    $(el).prop("disabled", false);
                    $(".js-form-submit-spinner").remove();
                },
            });
        });
    });
});

$(document).on("IIDE.init IIDE.editor", function (event) {
    /**
     * CKEditor
     *
     * @usage
     * <textarea data-editor></textarea>
     * <textarea data-editor='{"mode":"basic"}'></textarea>
     */
    $("[data-editor]").each(function (i) {
        let el = this,
            elid = euid("ckeditor-xxxxxxxxxxxx"),
            opt = $.extend(
                {
                    mode: "default", // basic, default, all, custom
                    wrapper: "wrapper-" + elid,
                    toolbar: [],
                },
                $(el).data("editor")
            );

        $(el).attr("id", elid);

        switch (opt.mode) {
            case "basic":
                opt.toolbar = shift.ckeditor.mode_basic;
                break;
            case "all":
                opt.toolbar = shift.ckeditor.mode_all;
                break;
            case "custom":
                // Do nothing, use the opt.toolbar
                break;
            default:
                opt.toolbar = shift.ckeditor.mode_default;
        }

        ClassicEditor.create(document.getElementById(elid), {
            toolbar: {
                items: opt.toolbar,
            },
        })
            .then(function (editor) {
                shift.ckeditor.instances[elid] = editor;

                $("#" + elid)
                    .parent()
                    .addClass(opt.wrapper)
                    .addClass("ckeditor-mode-" + opt.mode);

                let wordCount = editor.plugins.get("WordCount");
                $("." + opt.wrapper).append(
                    '<div class="ckeditor-wordcount"></div>'
                );
                $("." + opt.wrapper + " .ckeditor-wordcount").html(
                    wordCount.wordCountContainer
                );

                if (
                    $.inArray(
                        "shiftMediaManager",
                        editor.config._config.toolbar.items
                    ) > 0
                ) {
                    let htmlModal =
                        '<div class="uk-modal-dialog uk-modal-body mediamanager-modal">';
                    htmlModal +=
                        '    <button class="uk-modal-close-outside" type="button" uk-close></button>';
                    htmlModal +=
                        '    <div class="mediamanager-modal-wrapper"></div>';
                    htmlModal +=
                        '    <input type="hidden" class="mediamanager-image-source" value="">';
                    htmlModal +=
                        '    <input type="hidden" class="mediamanager-image-alt" value="">';
                    htmlModal += "</div>";

                    $("." + opt.wrapper).append(
                        '<div id="mediamanager-' +
                            elid +
                            '" class="ckeditor-mediamanager uk-modal uk-modal-container" uk-modal>' +
                            htmlModal +
                            "</div>"
                    );

                    UIkit.util.on(
                        "#mediamanager-" + elid,
                        "beforeshow",
                        function () {
                            $(
                                "#mediamanager-" +
                                    elid +
                                    " .mediamanager-modal-wrapper"
                            ).load(
                                shift.env.url_app +
                                    "r/tool/mediamanager&modal=1&access_token=" +
                                    shift.env.access_token
                            );
                        }
                    );
                }
            })
            .catch(function (error) {
                console.log(error);
            });
    });
});

$(document).on("IIDE.init IIDE.mediamanager", function (event) {
    /**
     * CKEditor
     *
     * @usage
     * <input data-mediamanager>
     */
    $("[data-mediamanager]").each(function (i) {
        let el = this,
            elid = euid("mm-xxxxxxxxxxxx"),
            opt = $.extend(
                {
                    wrapper: "wrapper-" + elid,
                    noImage: "image/no-image.png",
                },
                $(el).data("editor")
            );

        $(el).hide().attr("id", elid).parent().addClass(opt.wrapper);

        // Button
        let htmlThumb =
            '<div class="uk-card uk-card-default card-thumbnail uk-width-150">';
        htmlThumb +=
            '    <div class="card-media uk-card-media-top uk-cover-container"><img src="' +
            shift.env.url_media +
            $(el).val() +
            '" uk-cover style="cursor:pointer;"></div>';
        htmlThumb += '    <div class="uk-card-body uk-text-center">';
        htmlThumb +=
            '        <a class="uk-button uk-button-primary uk-button-small" data-mm-show>' +
            shift.i18n.select +
            "</a>";
        htmlThumb +=
            '        <a class="uk-button uk-button-secondary uk-button-small" data-mm-clear>' +
            shift.i18n.clear +
            "</a>";
        htmlThumb += "    </div>";
        htmlThumb += "</div>";
        $("." + opt.wrapper).prepend(htmlThumb);

        $(el)
            .parent()
            .on("click", "img, [data-mm-show]", function () {
                UIkit.modal("#mediamanager-" + elid).show();
            });

        $(el)
            .parent()
            .on("click", "[data-mm-clear]", function () {
                $("#" + elid).val(opt.noImage);
                $("." + opt.wrapper + " .card-media img").attr(
                    "src",
                    shift.env.url_media + opt.noImage
                );
            });

        // Media Manager modal
        let htmlModal =
            '<div class="uk-modal-dialog uk-modal-body mediamanager-modal">';
        htmlModal +=
            '    <button class="uk-modal-close-outside" type="button" uk-close></button>';
        htmlModal += '    <div class="mediamanager-modal-wrapper"></div>';
        htmlModal +=
            '    <input type="hidden" class="mediamanager-image-source" value="">';
        htmlModal += "</div>";

        $("." + opt.wrapper).append(
            '<div id="mediamanager-' +
                elid +
                '" class="input-mediamanager uk-modal uk-modal-container" uk-modal>' +
                htmlModal +
                "</div>"
        );

        UIkit.util.on("#mediamanager-" + elid, "beforeshow", function () {
            $("#mediamanager-" + elid + " .mediamanager-modal-wrapper").load(
                shift.env.url_app +
                    "r/tool/mediamanager&modal=1&access_token=" +
                    shift.env.access_token
            );
        });

        // Apply selected image
        UIkit.util.on("#mediamanager-" + elid, "beforehide", function () {
            imageSrc = $(
                "#mediamanager-" + elid + " input.mediamanager-image-source"
            ).val();

            if (imageSrc) {
                $("#" + elid).val(imageSrc);
                $("." + opt.wrapper + " .card-media img").attr(
                    "src",
                    shift.env.url_media + imageSrc
                );
            }

            setTimeout(function () {
                $(
                    "#mediamanager-" + elid + " input.mediamanager-image-source"
                ).val("");
                $(
                    "#mediamanager-" + elid + " .mediamanager-modal-wrapper"
                ).html("");
            }, 100);
        });
    });
});

$(document).on("IIDE.init IIDE.datepicker", function (event) {
    /**
     * Add datepicker and optional time picker to input
     *
     * @usage
     * <input type="text" data-datepicker>
     * <input type="text" data-datepicker='{"time":true}'>
     */
    $("[data-datepicker]").each(function (i) {
        let el = this,
            opt = $.extend(
                {
                    time: false,
                },
                $(el).data("datepicker")
            );

        $(el)
            .wrap('<div class="uk-inline"></div>')
            .before('<i class="uk-form-icon bi bi-calendar3"></i>');

        flatpickr(el, {
            allowInput: true,
            enableTime: opt.time,
            enableSeconds: opt.time,
            time_24hr: true,
        });
    });
});

$(document).on("IIDE.init IIDE.data-select-s2", function (event) {
    /**
     * Select2
     *
     * @usage
     * <select data-select-s2><option>...<option></select>
     * <select data-select-s2='{"tags":true}'><option>...<option></select>
     */
    $("[data-select-s2]").each(function (i) {
        let el = this,
            opt = $.extend(
                {
                    tags: false,
                    placeholder: shift.i18n["-select-"],
                },
                $(el).data("selectS2")
            );

        $(el).select2({
            tags: opt.tags,
            closeOnSelect: opt.tags ? false : true,
            tokenSeparators: opt.tags ? [","] : [],
            dropdownCssClass: opt.tags ? "select2-tags-dropdown" : "",
        });
    });
});

$(document).on("IIDE.init IIDE.data-select-switcher", function (event) {
    /**
     * Select2
     *
     * @usage
     * <select data-select-switcher='{"class":"switch-value"}'><option>...<option></select>
     * <div class="switch-value uk-switcher"></div>
     */
    $("[data-select-switcher]").each(function (i) {
        let el = this,
            opt = $.extend(
                {
                    class: "select-switcher",
                },
                $(el).data("selectSwitcher")
            );

        $(el).on("change", function () {
            $("." + opt.class + " > div").removeClass("uk-active");
            $("." + opt.class + "_" + $(el).val()).addClass("uk-active");
        });

        // Initial
        $("." + opt.class + " > div").removeClass("uk-active");
        $("." + opt.class + "_" + $(el).val()).addClass("uk-active");
    });
});

$(document).on("IIDE.init IIDE.format_date", function (event) {
    /**
     * Format date from UTC to local browser timezone
     *
     * @usage
     * <div data-format-date="Y-m-d H:i:s"></div>
     */
    $("[data-format-date]").each(function () {
        let el = this,
            date = $(el).data("formatDate"),
            chunk = "";

        chunk = date
            ? '<span title="' + date + ' UTC">' + formatDate(date) + "</span>"
            : "<i>n/a</i>";
        $(el).html(chunk);
    });
});

/*
 * Functions
 * ======================================================================== */

/**
 * Get browser timezone
 *
 * @return string
 */
function getVisitorTimezone() {
    return Intl.DateTimeFormat().resolvedOptions().timeZone;
}

/**
 * Datetime UTC to visitor browser timezone
 *
 * @param   string  datetime  Y-m-d H:i:s UTC
 * @return  string
 */
function formatDate(datetime) {
    if (!datetime) {
        return "";
    }

    return new Intl.DateTimeFormat("en-GB", {
        day: "2-digit",
        month: "short",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
        hour12: false,
        timeZone: getVisitorTimezone(),
        timeZoneName: "short",
    }).format(new Date(datetime + " UTC"));
}

/**
 * Element unique id
 */
function euid(format, type) {
    let euid = format ? format : "xxxxxxxx-xxxx-xxxx-xxxxxxxxxxxx",
        chars =
            type == "number"
                ? "1234567890"
                : "0123456789abcdefghijklmnopqrstuvwxyz",
        length = type == "number" ? 10 : 32;

    return euid.replace(new RegExp("x", "g"), function () {
        return chars[Math.floor(Math.random() * length)];
    });
}
