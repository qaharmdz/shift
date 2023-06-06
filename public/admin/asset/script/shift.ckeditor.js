/* jshint -W097, -W117 */
/* globals $, document, shift */

'use strict';

shift.ckeditor = { instances: {} };

/**
 * Editor configuration.
 *
 * Reff:
 * - https://ckeditor.com/docs/ckeditor5/latest/examples/builds/classic-editor.html
 * - https://ckeditor.com/docs/ckeditor5/latest/installation/getting-started/quick-start.html#sample-implementation-2
 */
shift.ckeditor.mode_basic = [
    'bold', 'italic', 'underline', 'strikethrough', 'removeFormat', '|',
    'bulletedList', 'numberedList', 'outdent', 'indent', '|', 'blockQuote', 'link', '|',
    'undo', 'redo', '|', 'sourceEditing'
];
shift.ckeditor.mode_default = [
    'heading', '|', 'bold', 'italic', 'underline', 'strikethrough', 'removeFormat', '|',
    'fontColor', 'fontBackgroundColor', 'alignment', '|',
    'bulletedList', 'numberedList', 'outdent', 'indent', '|',
    'blockQuote', 'link', 'shiftMediaManager', 'insertTable', '|',
    'undo', 'redo', '|', 'sourceEditing',
];
shift.ckeditor.mode_all = [
    'heading', '|', 'bold', 'italic', 'underline', 'strikethrough', 'removeFormat', '|',
    'fontColor', 'fontBackgroundColor', 'alignment', '|',
    'bulletedList', 'numberedList', 'outdent', 'indent', '|',
    'blockQuote', 'link', 'shiftMediaManager', 'mediaEmbed', 'insertTable', '|', 'sourceEditing',
    '-',
    'findAndReplace', '|', 'fontFamily','fontSize', 'fontColor', 'fontBackgroundColor', '|',
    'horizontalLine', 'pageBreak', 'code', 'codeBlock', '|', 'undo', 'redo'
];
// shift.ckeditor.mode_default = shift.ckeditor.mode_all;

ClassicEditor.defaultConfig =  {
    language: 'en',
    placeholder: '',
    toolbar: {
        items: shift.ckeditor.mode_default,
        shouldNotGroupWhenFull: true
    },
    image: {
        toolbar: [
            'imageStyle:inline', 'imageStyle:block', 'imageStyle:side', '|',
            'imageTextAlternative', 'toggleImageCaption', 'linkImage'
        ]
    },
    // https://ckeditor.com/docs/ckeditor5/latest/features/table.html
    table: {
        contentToolbar: [
            'tableColumn', 'tableRow', 'mergeTableCells', 'tableCellProperties', 'tableProperties'
        ]
    },
    // https://ckeditor.com/docs/ckeditor5/latest/features/headings.html#configuration
    heading: {
        options: [
            { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
            { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
            { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
            { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
            { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
            { model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5' },
            { model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6' }
        ]
    },
    // https://ckeditor.com/docs/ckeditor5/latest/features/font.html#configuring-the-font-size-feature
    fontSize: {
        options: [ 10, 12, 14, 'default', 18, 20, 22 ],
        supportAllValues: true
    },
    // https://ckeditor.com/docs/ckeditor5/latest/features/general-html-support.html
    htmlSupport: {
        allow: [
            {
                name: /^(div|span|section|table|ul|ol|p|b|i|u)$/,
                attributes: true,
                classes: true,
                styles: true,
            },
        ],
        disallow: [ /* HTML features to disallow */ ]
    }
};

$(document).on('IIDE.form_submit.before', function() {
    $.each(shift.ckeditor.instances, function(elid, instance) {
        $('#' + elid).text(instance.getData());
    });
});
