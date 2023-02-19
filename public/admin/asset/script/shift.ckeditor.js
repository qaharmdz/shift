/**
 * Editor configuration.
 *
 * Reff:
 * - https://ckeditor.com/docs/ckeditor5/latest/installation/getting-started/quick-start.html#sample-implementation-2
 * - https://ckeditor.com/docs/ckeditor5/latest/examples/builds/classic-editor.html
 */
shift.editor.mode_basic = [
    'bold', 'italic', 'underline', 'alignment', 'removeFormat', '|',
    'bulletedList', 'numberedList', '|', 'blockQuote', 'link', '|',
    'undo', 'redo', '|', 'sourceEditing'
];
shift.editor.mode_default = [
    'heading', '|', 'bold', 'italic', 'underline', 'strikethrough', 'removeFormat', '|',
    'fontColor', 'fontBackgroundColor', 'alignment', '|',
    'bulletedList', 'numberedList', 'outdent', 'indent', '|',
    'blockQuote', 'link', 'insertTable', 'mediaEmbed', '|',
    'undo', 'redo', 'findAndReplace', '|', 'sourceEditing',
];

ClassicEditor.defaultConfig =  {
    language: 'en',
    placeholder: '',
    toolbar: {
        items: shift.editor.mode_default,
        shouldNotGroupWhenFull: true
    },
    image: {
        toolbar: [
            'imageStyle:inline', 'imageStyle:block', 'imageStyle:side', '|',
            'imageTextAlternative', 'toggleImageCaption', 'linkImage'
        ]
    },
    table: {
        contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells', 'tableCellProperties', 'tableProperties']
    },
    htmlSupport: {
        allow: [
            {
                name: /^(div|section|p|span|table|ul|ol)$/,
                attributes: true,
                classes: true,
                styles: true,
            },
        ],
        disallow: [ /* HTML features to disallow */ ]
    }
};
