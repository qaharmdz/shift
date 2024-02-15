/* jshint -W097, -W117 */
/* globals $, document, shift */

"use strict";

/**
 * Depend on https://github.com/SortableJS/Sortable
 */
(function ($) {
    $.fn.shift.layout = {};

    $.fn.shift.layout.defaults = {
        animation: 200,
        handle: ".layout-handle",
    };

    $.fn.shift.layout.construct = function () {
        $("[data-layout-sortable]").each(function () {
            if (!$(this).is("[data-layout-is-sortable]")) {
                let el = this,
                    opt = $.extend(
                        $.fn.shift.layout.defaults,
                        $(el).data("layout-sortable")
                    );

                opt.onEnd = function (evt) {
                    $.fn.shift.layout.save();
                };

                new Sortable(el, opt);
                $(this).attr("data-layout-is-sortable", "");
            }
        });
    };

    $.fn.shift.layout.add = function (el) {
        let template = "",
            elid = "",
            opt = $.extend(
                {
                    type: "",
                    target: "",
                },
                $(el).data("layout-add")
            );

        if (!opt.type || !opt.target) {
            return null;
        }

        if (opt.type == "row") {
            elid = euid("row-xxxxxxxxxxxx");
            template = $("#template-row").html().replaceAll("{-node-}", elid);

            $(template).appendTo(opt.target).hide().fadeIn(500);
            $.fn.shift.layout.construct();
        }
        if (opt.type == "column") {
            elid = euid("col-xxxxxxxxxxxx");
            template = $("#template-column")
                .html()
                .replaceAll("{-node-}", elid);

            $(template).appendTo(opt.target).hide().fadeIn(500);
            $.fn.shift.layout.construct();
        }
        if (opt.type == "module") {
            elid = euid("mod-xxxxxxxxxxxx");
            template = $("#template-module")
                .html()
                .replaceAll("{-node-}", elid);

            $(template).appendTo(opt.target).hide().fadeIn(500);
        }

        $.fn.shift.layout.save();
    };

    $.fn.shift.layout.delete = function (el) {
        let opt = $.extend(
            {
                type: "",
            },
            $(el).data("layout-delete")
        );

        if (!opt.type) {
            return null;
        }

        if (opt.type == "row") {
            $(el)
                .closest(".row-wrapper")
                .fadeOut(250, function () {
                    $(this).remove();
                });
        }
        if (opt.type == "column") {
            $(el)
                .closest(".column-wrapper")
                .fadeOut(250, function () {
                    $(this).remove();
                });
        }
        if (opt.type == "module") {
            $(el)
                .closest(".module-wrapper")
                .fadeOut(250, function () {
                    $(this).remove();
                });
        }

        $.fn.shift.layout.save();
    };

    $.fn.shift.layout.save = function () {
        let data = {};

        $("[data-layout-position]").each(function () {
            let position = $(this).data("layout-position");
            data[position] = {
                setting: $(this).data("layout-setting"),
                rows: {},
            };

            let isChildRow = $(this).find("[data-layout-row]").length;

            if (isChildRow) {
                $(this)
                    .find("[data-layout-row]")
                    .each(function () {
                        let row = $(this).data("layout-row");
                        data[position].rows[row] = {
                            setting: $(this).data("layout-setting"),
                            columns: {},
                        };

                        $(this)
                            .find("[data-layout-column]")
                            .each(function () {
                                let column = $(this).data("layout-column");
                                data[position].rows[row].columns[column] = {
                                    setting: $(this).data("layout-setting"),
                                    modules: {},
                                };

                                $(this)
                                    .find("[data-layout-module]")
                                    .each(function () {
                                        let module =
                                            $(this).data("layout-module");
                                        data[position].rows[row].columns[
                                            column
                                        ].modules[module] =
                                            $(this).data("layout-setting");

                                        if (
                                            data[position].rows[row].columns[
                                                column
                                            ].modules[module].module_id === 0
                                        ) {
                                            delete data[position].rows[row]
                                                .columns[column].modules[
                                                module
                                            ];
                                        }
                                    });

                                if (
                                    Object.keys(
                                        data[position].rows[row].columns[column]
                                            .modules
                                    ).length === 0
                                ) {
                                    delete data[position].rows[row].columns[
                                        column
                                    ];
                                }
                            });

                        if (
                            Object.keys(data[position].rows[row].columns)
                                .length === 0
                        ) {
                            delete data[position].rows[row];
                        }
                    });
            } else {
                $(this)
                    .find("[data-layout-module]")
                    .each(function () {
                        let module = $(this).data("layout-module");
                        data[position].rows[module] =
                            $(this).data("layout-setting");

                        if (data[position].rows[module].module_id === 0) {
                            delete data[position].rows[module];
                        }
                    });
            }

            if (Object.keys(data[position].rows).length === 0) {
                delete data[position];
            }
        });

        $(".js-layout-placements").text(JSON.stringify(data));
        shift.formChanged = true;
    };
})(jQuery);

$(document).ready(function () {
    $("#layout-routes").on("click", "[data-layout-route]", function (e) {
        let el = this,
            opt = $.extend(
                {
                    action: "",
                    target: "",
                },
                $(el).data("layout-route")
            );

        if (opt.action == "add") {
            let elid = euid("route-xxxxxxxx"),
                template = $("#template-layout-routes")
                    .html()
                    .replaceAll("{-i-}", elid);

            $(template).appendTo(opt.target).hide().fadeIn(500);
        }

        if (opt.action == "delete") {
            $(this)
                .closest("tr")
                .fadeOut(250, function () {
                    $(this).remove();
                });
        }

        shift.formChanged = true;
    });

    $("[data-layout-position]").on("click", "[data-layout-add]", function (e) {
        $.fn.shift.layout.add(this);
    });
    $("[data-layout-position]").on(
        "click",
        "[data-layout-delete]",
        function (e) {
            $.fn.shift.layout.delete(this);
        }
    );

    $("[data-layout-position]").on("click", ".js-module-trove", function (e) {
        let el = this;

        UIkit.modal("#layout-module-modal").show();
        UIkit.util.on("#layout-module-modal", "hidden", function () {
            $.fn.shift.layout.save();
        });

        $(".js-select-module")
            .off("click")
            .on("click", function () {
                let module = $(this).data("module-info");

                $(el).html(
                    "<code>" + module.codename + "</code> " + module.name
                );
                $(el)
                    .closest("[data-layout-setting]")
                    .data("layout-setting", module);

                UIkit.modal("#layout-module-modal").hide();
            });
    });

    $("#layout-module-list").DataTable(
        $.extend($.fn.dataTable.configBasic, {
            pageLength: 12,
            sorting: [
                [1, "asc"],
                [2, "asc"],
            ], // code, name
        })
    );

    $.fn.shift.layout.construct();
});
