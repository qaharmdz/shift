// Default dataTables initialisation
// ================================================
$.extend($.fn.dataTable.defaults, {
    dom             : "<'dataTables-top'<'uk-grid uk-grid-small'<'uk-width-2-3'fi>'<'uk-width-1-3 dt-top-right'lB>>><'dataTables-content't><'dataTables-bottom'<'uk-grid'<'uk-width-1-3'i><'uk-width-2-3 uk-text-right'p>>>r",
    serverSide      : true,
    processing      : true,
    searchDelay     : 1000,
    orderCellsTop   : true,
    orderMulti      : true, // use "shift+"
    autoWidth       : false,
    orderClasses    : true, // addition of the "sorting" classes to column cell vertically
    lengthMenu      : [ [25, 50, 100, 250, -1], [25, 50, 100, 250, shift.i18n.all] ],
    pageLength      : 25,
    pagingType      : 'full_numbers',
    renderer        : { 'pageButton' : 'uikit' }, // custom pagination
    buttons         : {
        buttons : [
            {
                extend : 'colvis',
                text : '<span uk-tooltip title="Columns visual">' + shift.i18n.columns + '</span>',
                background : false,
                columns : ':not(.noVisualColumn)'
            }
        ],
        dom : {
            button : {
                tag : 'button',
                className : 'uk-button uk-button-default uk-button-small'
            },
            buttonLiner : {
                tag: null
            }
        }
    },
    language : {
        emptyTable          : shift.i18n.no_result,
        info                : shift.i18n.show_x_data,
        infoEmpty           : shift.i18n.no_result,
        infoFiltered        : shift.i18n.filter_x_data,
        infoPostFix         : '<a data-dtReload uk-tooltip title="' + shift.i18n.reload_data + '"><i class="bi bi-arrow-repeat"></i></a>',
        thousands           : ',',
        lengthMenu          : '_MENU_',
        loadingRecords      : shift.i18n.loading,
        search              : '',
        searchPlaceholder   : shift.i18n.search_all,
        zeroRecords         : shift.i18n.no_result,
        paginate            : {
            first    : '<i class="bi bi-chevron-double-left"></i>',
            previous : '<i class="bi bi-chevron-left"></i>',
            next     : '<i class="bi bi-chevron-right"></i>',
            last     : '<i class="bi bi-chevron-double-right"></i>',
        },
    },
});

// Default class modification
// ================================================
$.extend($.fn.dataTableExt.oStdClasses, {
    sWrapper        : 'dataTables_wrapper',
    sFilter         : 'dataTables_filter uk-width-2-5',
    sInfo           : 'dataTables_info',
    sFilterInput    : 'uk-input uk-form-small',
    sLengthSelect   : 'dataTables_length_select uk-select uk-form-small'
});

// Pipelining function for DataTables. To be used to the `ajax` option of DataTables
// ================================================
$.fn.dataTable.pipeline = function (opts) {
    // configiguration options
    let config = $.extend({
        url    : '',     // script url
        method : 'POST', // Ajax HTTP method
        pages  : 5,      // number of pages to cache
        data   : null,   // function or object with parameters to send to the server
                         // matching how `ajax.data` works in DataTables
    }, opts);

    // Private variables for storing the cache
    let cacheLower        = -1;
    let cacheUpper        = null;
    let cacheLastRequest  = null;
    let cacheLastJson     = null;

    return function (request, drawCallback, settings) {
        let ajax          = false;
        let requestStart  = request.start;
        let drawStart     = request.start;
        let requestLength = request.length;
        let requestEnd    = requestStart + requestLength;

        if (settings.clearCache) {
            // API requested that the cache be cleared
            ajax = true;
            settings.clearCache = false;
        } else if (cacheLower < 0 || requestStart < cacheLower || requestEnd > cacheUpper) {
            // outside cached data - need to make a request
            ajax = true;
        } else if (
            JSON.stringify(request.order) !== JSON.stringify(cacheLastRequest.order) ||
            JSON.stringify(request.columns) !== JSON.stringify(cacheLastRequest.columns) ||
            JSON.stringify(request.search) !== JSON.stringify(cacheLastRequest.search)
        ) {
            // properties changed (ordering, columns, searching)
            ajax = true;
        }

        // Store the request for checking next time around
        cacheLastRequest = $.extend( true, {}, request );

        if (ajax) {
            // Need data from the server
            if (requestStart < cacheLower) {
                requestStart = requestStart - (requestLength * (config.pages - 1));

                if (requestStart < 0) {
                    requestStart = 0;
                }
            }

            cacheLower = requestStart;
            cacheUpper = requestStart + (requestLength * config.pages);

            request.start = requestStart;
            request.length = requestLength * config.pages;

            // Provide the same `data` options as DataTables.
            if (typeof config.data === 'function') {
                // As a function it is executed with the data object as an arg for manipulation.
                // If an object is returned, it is used as the data object to submit
                let d = config.data(request, settings);
                if (d) {
                    $.extend(request, d);
                }
            } else if ($.isPlainObject(config.data )) {
                // As an object, the data given extends the default
                $.extend(request, config.data);
            }

            settings.jqXHR = $.ajax({
                'type'      : config.method,
                'url'       : config.url,
                'data'      : request,
                'dataType'  : 'json',
                'cache'     : false,
                'success'   : function(json) {
                    cacheLastJson = $.extend(true, {}, json);

                    if (cacheLower != drawStart) {
                        json.data.splice(0, drawStart - cacheLower);
                    }
                    if (requestLength >= -1) {
                        json.data.splice(requestLength, json.data.length);
                    }

                    drawCallback(json);
                }
            });
        }
        else {
            json = $.extend( true, {}, cacheLastJson );
            json.draw = request.draw; // Update the echo for each response
            json.data.splice( 0, requestStart - cacheLower );
            json.data.splice( requestLength, json.data.length );

            drawCallback(json);
        }
    };
};

// Register an API method that will empty the pipelined data, forcing an Ajax
// fetch on the next draw (i.e. `table.clearPipeline().draw()`)
// ================================================
$.fn.dataTable.Api.register('clearPipeline()', function () {
    return this.iterator('table', function(settings) {
        settings.clearCache = true;
    });
});

// Clear search
// ================================================
$.fn.dataTable.Api.register('clearSearch()', function () {
    return this.iterator('table', function (settings) {
        // clear pre-search
        settings.oPreviousSearch.sSearch = '';
        for (iCol = 0; iCol < settings.aoPreSearchCols.length; iCol++) {
            if (typeof settings.aoPreSearchCols[iCol].search !== 'undefined') { // set back to initial search
                settings.aoPreSearchCols[iCol].sSearch = settings.aoPreSearchCols[iCol].search;
            } else {
                settings.aoPreSearchCols[iCol].sSearch = '';
            }
        }

        // clear search inputs
        $('#'+settings.nTable.id+'_filter, #'+settings.nTable.id+' thead input').val('');
        $('#'+settings.nTable.id+' .thead select').prop('selectedIndex', 0);

        // clear pipeline cache
        settings.clearCache = true;
    });
});

// UIkit Pagination
// ================================================
$.fn.dataTable.ext.renderer.pageButton.uikit = function (settings, host, idx, buttons, page, pages) {
    let api     = new $.fn.dataTable.Api(settings);
    let classes = settings.oClasses;
    let lang    = settings.oLanguage.oPaginate;
    let btnDisplay, btnClass;

    let attach = function(container, buttons) {
        let i, ien, node, button;
        let clickHandler = function (e) {
            e.preventDefault();
            if (!$(e.currentTarget).hasClass('uk-disabled')) {
                api.page(e.data.action).draw(false);
            }
        };

        for (i = 0, ien = buttons.length ; i < ien ; i++) {
            button = buttons[i];

            if ($.isArray(button)) {
                attach(container, button);
            }
            else {
                btnDisplay = '';
                btnClass = '';

                switch (button) {
                    case 'ellipsis':
                        btnDisplay  = '&hellip;';
                        btnClass    = 'uk-disabled';
                        break;

                    case 'first':
                        btnDisplay  = lang.sFirst;
                        btnClass    = button + (page > 0 ? '' : ' uk-disabled');
                        break;

                    case 'previous':
                        btnDisplay  = lang.sPrevious;
                        btnClass    = button + (page > 0 ? '' : ' uk-disabled');
                        break;

                    case 'next':
                        btnDisplay  = lang.sNext;
                        btnClass    = button + (page < pages-1 ? '' : ' uk-disabled');
                        break;

                    case 'last':
                        btnDisplay  = lang.sLast;
                        btnClass    = button + (page < pages-1 ? '' : ' uk-disabled');
                        break;

                    default:
                        btnDisplay  = button + 1;
                        btnClass    = page === button ? 'uk-active' : '';
                        break;
                }

                if (btnDisplay) {
                    node = $('<li>', {
                            'class': classes.sPageButton+' '+btnClass,
                            'aria-controls': settings.sTableId,
                            'tabindex': settings.iTabIndex,
                            'id': idx === 0 && typeof button === 'string' ?
                                settings.sTableId +'_'+ button :
                                null
                        });

                    if (btnClass === 'uk-active' || btnClass.indexOf('uk-disabled') >= 0) {
                        node = node.append($('<span>').html(btnDisplay));
                    } else {
                        node = node.append($('<a>', { 'href': '#' }).html(btnDisplay));
                    }

                    node = node.appendTo(container);

                    settings.oApi._fnBindAction(
                        node, {action: button}, clickHandler
                    );
                }
            }
        }
    };

    attach(
        $(host).empty().html('<ul class="uk-pagination uk-flex-right"/>').children('ul'),
        buttons
   );
};

// Shift DataTables utilities
// ================================================

function dtShiftColumnFilter(d) {
    $('[data-dtColumnFilter] td').each(function(i) {
        let colFilter = $(this).data('filter');
        if (colFilter) {
            let forms = $('input, select', this),
                index = forms.data('index');

            $.extend(d.columns[index]['search'], colFilter || []);

            if (forms.length == 1) {
                d.columns[index]['search']['value'] = $('input, select', this).val() || '';
            } else if (forms.length == 2) {
                forms.each(function(ci, el) {
                    if (ci == 0) {
                        d.columns[index]['search']['value'] = $(el).val() || '';
                        d.columns[index]['search']['value'] += '~';
                    } else {
                        d.columns[index]['search']['value'] += $(el).val() || '';
                    }
                });
            }
        }
    });

    return d;
}

function dtShiftUtility(dtTable, colsHide) {
    // Refresh record results
    $('.main-content').on('click', '[data-dtReload]', function() {
        dtTable.clearPipeline().draw();
    });

    // Search inputs
    $('.dataTables_filter input').off('keypress keyup search input paste cut').typeWatch({
        allowSubmit: true,
        captureLength: 0,
        callback: function(value) {
            dtTable.search(value).draw();
        },
    });
    $('[data-dtColumnFilter] input:not([data-dtDatePicker])').typeWatch({
        allowSubmit: true,
        captureLength: 0,
        callback: function(value) {
            dtTable.column($(this).data('index')).search(value).draw();
        },
    });
    $('[data-dtColumnFilter] input[data-dtDatePicker]').datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        yearRange: '-6:+3',
        prevText: '<',
        nextText: '>',
        onSelect: function(formattedDate, date, inst) {
            dtTable.column($(this).data('index')).search(formattedDate).draw();
        }
    });
    $('[data-dtColumnFilter] select').on('change', function() {
        dtTable.column($(this).data('index')).search($(this).find(':selected').val()).draw();
    });

    // Clear all search
    $('[data-dtClearSearch]').on('click', function() {
        $('[data-dtColumnFilter] input').val('');
        $('[data-dtColumnFilter] select').find('option:first').prop('selected', true);
        dtTable.clearSearch().draw();
    });
    // Clear datepicker column
    $('[data-dtDatePicker-clear]').on('click', function() {
        let el = $(this).parent().find('input');
        el.val('');
        dtTable.column(el.data('index')).search('').draw();
    });

    // Hide columns after all event delegated
    dtTable.columns(colsHide).visible(false);
}
