/**
 * @license Copyright (c) 2014-2023, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
 */
import ClassicEditor from '@ckeditor/ckeditor5-editor-classic/src/classiceditor.js';
import Alignment from '@ckeditor/ckeditor5-alignment/src/alignment.js';
import Autoformat from '@ckeditor/ckeditor5-autoformat/src/autoformat.js';
import AutoImage from '@ckeditor/ckeditor5-image/src/autoimage.js';
import AutoLink from '@ckeditor/ckeditor5-link/src/autolink.js';
import BlockQuote from '@ckeditor/ckeditor5-block-quote/src/blockquote.js';
import Bold from '@ckeditor/ckeditor5-basic-styles/src/bold.js';
import Code from '@ckeditor/ckeditor5-basic-styles/src/code.js';
import CodeBlock from '@ckeditor/ckeditor5-code-block/src/codeblock.js';
// import DataFilter from '@ckeditor/ckeditor5-html-support/src/datafilter.js';
// import DataSchema from '@ckeditor/ckeditor5-html-support/src/dataschema.js';
import Essentials from '@ckeditor/ckeditor5-essentials/src/essentials.js';
import FindAndReplace from '@ckeditor/ckeditor5-find-and-replace/src/findandreplace.js';
import FontBackgroundColor from '@ckeditor/ckeditor5-font/src/fontbackgroundcolor.js';
import FontColor from '@ckeditor/ckeditor5-font/src/fontcolor.js';
import FontFamily from '@ckeditor/ckeditor5-font/src/fontfamily.js';
import FontSize from '@ckeditor/ckeditor5-font/src/fontsize.js';
import GeneralHtmlSupport from '@ckeditor/ckeditor5-html-support/src/generalhtmlsupport.js';
import Heading from '@ckeditor/ckeditor5-heading/src/heading.js';
import HorizontalLine from '@ckeditor/ckeditor5-horizontal-line/src/horizontalline.js';
import Image from '@ckeditor/ckeditor5-image/src/image.js';
import ImageCaption from '@ckeditor/ckeditor5-image/src/imagecaption.js';
import ImageResize from '@ckeditor/ckeditor5-image/src/imageresize.js';
import ImageStyle from '@ckeditor/ckeditor5-image/src/imagestyle.js';
import ImageToolbar from '@ckeditor/ckeditor5-image/src/imagetoolbar.js';
import Indent from '@ckeditor/ckeditor5-indent/src/indent.js';
import Italic from '@ckeditor/ckeditor5-basic-styles/src/italic.js';
import Link from '@ckeditor/ckeditor5-link/src/link.js';
import LinkImage from '@ckeditor/ckeditor5-link/src/linkimage.js';
import List from '@ckeditor/ckeditor5-list/src/list.js';
import MediaEmbed from '@ckeditor/ckeditor5-media-embed/src/mediaembed.js';
import PageBreak from '@ckeditor/ckeditor5-page-break/src/pagebreak.js';
import Paragraph from '@ckeditor/ckeditor5-paragraph/src/paragraph.js';
import PasteFromOffice from '@ckeditor/ckeditor5-paste-from-office/src/pastefromoffice.js';
import RemoveFormat from '@ckeditor/ckeditor5-remove-format/src/removeformat.js';
import SourceEditing from '@ckeditor/ckeditor5-source-editing/src/sourceediting.js';
import Strikethrough from '@ckeditor/ckeditor5-basic-styles/src/strikethrough.js';
import Table from '@ckeditor/ckeditor5-table/src/table.js';
import TableCellProperties from '@ckeditor/ckeditor5-table/src/tablecellproperties';
import TableColumnResize from '@ckeditor/ckeditor5-table/src/tablecolumnresize.js';
import TableProperties from '@ckeditor/ckeditor5-table/src/tableproperties';
import TableToolbar from '@ckeditor/ckeditor5-table/src/tabletoolbar.js';
import TextTransformation from '@ckeditor/ckeditor5-typing/src/texttransformation.js';
import Underline from '@ckeditor/ckeditor5-basic-styles/src/underline.js';
import WordCount from '@ckeditor/ckeditor5-word-count/src/wordcount.js';

class Editor extends ClassicEditor {}

// =============================================================================

import Plugin from '@ckeditor/ckeditor5-core/src/plugin';
import ButtonView from '@ckeditor/ckeditor5-ui/src/button/buttonview';

class ShiftMediaManager extends Plugin {
    init() {
        const editor = this.editor;

        // Register button to editor UI components to be displayed in the toolbar
        editor.ui.componentFactory.add('shiftMediaManager', () => {
            // The button will be an instance of ButtonView.
            // https://ckeditor.com/docs/ckeditor5/latest/api/module_ui_button_buttonview-ButtonView.html
            const button = new ButtonView();

            button.set({
                label: 'TODO: Media Manager',
                // https://icons.getbootstrap.com/icons/images/
                icon: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-images" viewBox="0 0 16 16"><path d="M4.502 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/><path d="M14.002 13a2 2 0 0 1-2 2h-10a2 2 0 0 1-2-2V5A2 2 0 0 1 2 3a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v8a2 2 0 0 1-1.998 2zM14 2H4a1 1 0 0 0-1 1h9.002a2 2 0 0 1 2 2v7A1 1 0 0 0 15 11V3a1 1 0 0 0-1-1zM2.002 4a1 1 0 0 0-1 1v8l2.646-2.354a.5.5 0 0 1 .63-.062l2.66 1.773 3.71-3.71a.5.5 0 0 1 .577-.094l1.777 1.947V5a1 1 0 0 0-1-1h-10z"/></svg>',
                class: 'cks-icon cks-media-manager-button',
                withText: false,
                tooltip: true,
            });

            // Execute a callback function when the button is clicked.
            button.on('execute', () => {
                const now = new Date();

                // $('.uk-form-editor').addClass('test');

                // Change the model using the model writer.
                // https://ckeditor.com/docs/ckeditor5/latest/api/module_engine_model_writer-Writer.html
                editor.model.change(writer => {
                    // Insert the text at the user's current position.
                    editor.model.insertContent( writer.createText( now.toString() ) );

                    // Add image: https://stackoverflow.com/questions/53208435/how-to-insert-an-image-with-a-caption-into-a-custom-element-in-ckeditor-5
                });
            });

            return button;
        });
    }
}

// =============================================================================

// Plugins to include in the build.
Editor.builtinPlugins = [
	Alignment,
	Autoformat,
	AutoImage,
	AutoLink,
	BlockQuote,
	Bold,
	Code,
	CodeBlock,
	// DataFilter,
	// DataSchema,
	Essentials,
	FindAndReplace,
	FontBackgroundColor,
	FontColor,
	FontFamily,
	FontSize,
	GeneralHtmlSupport,
	Heading,
	HorizontalLine,
	Image,
	ImageCaption,
	ImageResize,
	ImageStyle,
	ImageToolbar,
	Indent,
	Italic,
	Link,
	LinkImage,
	List,
	MediaEmbed,
	PageBreak,
	Paragraph,
	PasteFromOffice,
	RemoveFormat,
	SourceEditing,
	Strikethrough,
	Table,
	TableCellProperties,
	TableColumnResize,
	TableProperties,
	TableToolbar,
	TextTransformation,
	Underline,
	WordCount,
    ShiftMediaManager,
];

// Editor configuration.
Editor.defaultConfig = {
	toolbar: {
		items: [
			'heading',
			'|',
			'removeFormat',
			'bold',
			'italic',
			'underline',
			'strikethrough',
			'alignment',
			'|',
			'bulletedList',
			'numberedList',
			'outdent',
			'indent',
			'|',
			'blockQuote',
			'link',
			'insertTable',
			'mediaEmbed',
			'|',
			'undo',
			'redo',
			'-',
			'findAndReplace',
			'|',
			'fontFamily',
			'fontSize',
			'fontColor',
			'fontBackgroundColor',
			'|',
			'horizontalLine',
			'pageBreak',
			'code',
			'codeBlock',
			'|',
			'sourceEditing'
		],
		shouldNotGroupWhenFull: true
	},
	language: 'en',
	image: {
		toolbar: [
			'imageTextAlternative',
			'toggleImageCaption',
			'imageStyle:inline',
			'imageStyle:block',
			'imageStyle:side',
			'linkImage'
		]
	},
	table: {
		contentToolbar: [
			'tableColumn',
			'tableRow',
			'mergeTableCells',
			'tableCellProperties',
			'tableProperties'
		]
	}
};

export default Editor;
