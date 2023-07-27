/* jshint -W097, -W117 */
/* globals $, document, shift */

'use strict';

(function($) {
    $.fn.shift.layout = {};

    $.fn.shift.layout.defaults = {
        animation  : 200,
        dataIdAttr : 'data-layout-id',
        handle     : '.layout-handle'
    };
    $.fn.shift.layout.construct = function() {
        $('[data-layout-node]').each(function() {
            if (!$(this).is('[data-layout-is-sortable]')) {
                let el  = this,
                    opt = $.extend(
                        $.fn.shift.layout.defaults,
                        $(el).data('layout-node')
                    );

                    new Sortable(el, opt);
                    $(this).attr('data-layout-is-sortable', '');
            }
        });
    };
    $.fn.shift.layout.add = function(el) {
        let template   = '',
            position   = $(el).closest('[data-layout-position]').data('layout-position'),
            innerItems = 0,
            nodeTarget = 'js-node-',
            opt = $.extend(
                {
                    type   : '',
                    target : '',
                },
                $(el).data('layout-add')
            );

        if (!opt.type || !opt.target) {
            return null;
        }

        if (opt.type == 'row') {
            innerItems = $(opt.target).find('.row-wrapper').length + 1;
            template = $('#template-row')
                            .html()
                            .replaceAll(
                                '{-node-target-}',
                                nodeTarget + position + '-row-' + innerItems
                            );

            $(opt.target).append(template);
            $.fn.shift.layout.construct();
        }
        if (opt.type == 'column') {
            innerItems = $(opt.target).find('.column-wrapper').length + 1;
            nodeTarget = opt.target.replace('.', '');
            template = $('#template-column')
                            .html()
                            .replaceAll(
                                '{-node-target-}',
                                nodeTarget + '-column-' + innerItems
                            );

            $(opt.target).append(template);
            $.fn.shift.layout.construct();
        }
        if (opt.type == 'module') {
            innerItems = $(opt.target).find('.module-wrapper').length + 1;
            template = $('#template-module').html();

            $(opt.target).append(template);
        }
    };
    $.fn.shift.layout.save = function(options) {
        $('[data-layout-position]').each(function() {
            // console.log($(this));
        });
    };
})(jQuery);

$(document).ready(function() {
    $('[data-layout-position]').on('click', '[data-layout-add]', function(e) {
        $.fn.shift.layout.add(this)
    });

    $.fn.shift.layout.construct();
});

/**
 * Element unique id
 */
function euid(format, type) {
    let euid = format ? format : 'xxxxxxxx-xxxx-xxxx-xxxxxxxxxxxx',
        chars = type == 'alnum' ? '0123456789abcdefghijklmnopqrstuvwxyz' : '1234567890',
        length = type == 'alnum' ? 32 : 10;

    return euid.replace(new RegExp('x', 'g'), function() {
        return chars[(Math.floor(Math.random() * length))];
    });
}
