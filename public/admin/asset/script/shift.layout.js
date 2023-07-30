/* jshint -W097, -W117 */
/* globals $, document, shift */

'use strict';

(function($) {
    $.fn.shift.layout = {};

    $.fn.shift.layout.defaults = {
        animation  : 200,
        handle     : '.layout-handle'
    };

    $.fn.shift.layout.construct = function() {
        $('[data-layout-sortable]').each(function() {
            if (!$(this).is('[data-layout-is-sortable]')) {
                let el  = this,
                    opt = $.extend(
                        $.fn.shift.layout.defaults,
                        $(el).data('layout-sortable')
                    );

                    opt.onEnd = function (evt) {
                        $.fn.shift.layout.save();
                    };

                    new Sortable(el, opt);
                    $(this).attr('data-layout-is-sortable', '');
            }
        });
    };

    $.fn.shift.layout.add = function(el) {
        let template = '',
            elid = '',
            opt  = $.extend(
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
            elid     = euid('row-xxxxxxxxxxxx');
            template = $('#template-row')
                            .html()
                            .replaceAll('{-node-}', elid);

            $(opt.target).append(template);
            $.fn.shift.layout.construct();
        }
        if (opt.type == 'column') {
            elid     = euid('col-xxxxxxxxxxxx');
            template = $('#template-column')
                            .html()
                            .replaceAll('{-node-}', elid);

            $(opt.target).append(template);
            $.fn.shift.layout.construct();
        }
        if (opt.type == 'module') {
            elid     = euid('mod-xxxxxxxxxxxx');
            template = $('#template-module')
                            .html()
                            .replaceAll('{-node-}', elid);

            $(opt.target).append(template);
        }

        $.fn.shift.layout.save();
    };

    $.fn.shift.layout.delete = function(el) {
        let opt  = $.extend(
                {
                    type   : '',
                },
                $(el).data('layout-delete')
            );

        if (!opt.type) {
            return null;
        }

        if (opt.type == 'row') {
            $(el).closest('.row-wrapper').remove();
        }
        if (opt.type == 'column') {
            $(el).closest('.column-wrapper').remove();
        }
        if (opt.type == 'module') {
            $(el).closest('.module-wrapper').remove();
        }

        $.fn.shift.layout.save();
    };

    $.fn.shift.layout.save = function() {
        let data = {};

        $('[data-layout-position]').each(function() {
            let position = $(this).data('layout-position');
            data[position] = {
                'setting' : $(this).data('layout-setting'),
                'rows' : {},
            };

            $(this).find('[data-layout-row]').each(function() {
                let row = $(this).data('layout-row');
                data[position].rows[row] = {
                    'setting' : $(this).data('layout-setting'),
                    'columns' : {},
                };

                $(this).find('[data-layout-column]').each(function() {
                    let column = $(this).data('layout-column');
                    data[position].rows[row].columns[column] = {
                        'setting' : $(this).data('layout-setting'),
                        'modules' : {},
                    };

                    $(this).find('[data-layout-module]').each(function() {
                        let module = $(this).data('layout-module');
                        data[position].rows[row].columns[column].modules[module] = $(this).data('layout-setting');
                    });
                });
            });
        });

        $('.js-layout-placements').text(JSON.stringify(data));
    };
})(jQuery);

$(document).ready(function() {
    $('[data-layout-position]').on('click', '[data-layout-add]', function(e) {
        $.fn.shift.layout.add(this);
    });
    $('[data-layout-position]').on('click', '[data-layout-delete]', function(e) {
        $.fn.shift.layout.delete(this);
    });

    $('[data-layout-position]').on('click', '.js-module-trove', function(e) {
        let el = this;

        UIkit.modal('#layout-module-list').show();

        $('.js-select-module').off('click').on('click', function() {
            let module = $(this).data('module-info');

            $(el).closest('[data-layout-setting]').attr('data-layout-setting', JSON.stringify(module));
            $(el).html('<code>' + module.codename + '</code> ' + module.name);

            UIkit.modal('#layout-module-list').hide();
            $.fn.shift.layout.save();
        });
    });

    $.fn.shift.layout.construct();
});
