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
                console.log($(this));
            }
        });
    };
    $.fn.shift.layout.add = function(options) {
    };
    $.fn.shift.layout.save = function(options) {
    };
})(jQuery);

$(document).ready(function() {
    $('[data-click]').on('click', function(e) {
        console.log(this)
    });

    $.fn.shift.layout.construct();

    $('[data-layout-id]').each(function() {
        // console.log($(this).data('layout-id'));
    });
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
