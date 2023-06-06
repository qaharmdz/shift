/* jshint -W097, -W117 */
/* globals $, document, shift */

'use strict';

shift.codemirror = { instances: {} };

$(document).ready(function()
{
    /**
     * IIDE CodeMirror
     *
     * Usage:
     * - <textarea id="editor" data-codemirror='{"mode":"html"}'></textarea>
     * - <div data-codemirror='{"id":"editor", "mode":"php"}'><textarea id="editor"></textarea></div>
     */
    $('[data-codemirror]').each(function(e) {
        let el = this,
            opt = $.extend({
                id   : $(this).attr('id'),
                mode : 'html',
                saveEl : '.js-codemirror-save',
            }, $(el).data('codemirror'));

        if (opt.id === undefined) {
            return false;
        }

        switch (opt.mode) {
            case 'css':
                opt.mode = 'text/x-scss';
                break;
            case 'javascript':
                opt.mode = 'text/javascript';
                break;
            case 'php':
                opt.mode = 'application/x-httpd-php';
                break;
            case 'xml':
                opt.mode = 'application/xml';
                break;
            case 'html':
            default:
                opt.mode = 'text/html';
                break;
        }

        shift.codemirror.instances[opt.id] = CodeMirror.fromTextArea(document.getElementById(opt.id), {
            mode            : opt.mode,
            indentUnit      : 2,
            lineNumbers     : true,
            lineWrapping    : true,
            styleActiveLine : {nonEmpty: true},
            foldGutter      : true,
            gutters         : ['CodeMirror-linenumbers', 'CodeMirror-foldgutter'],
            matchBrackets   : true,
            matchClosing    : true,
            extraKeys       : {
                'Tab'    : cmSpaceTab,
                'Ctrl-S' : function(instance) {
                    setTimeout(function() {
                        $(opt.saveEl).trigger('click');
                    }, 200);
                },
                'Cmd-S'  : function(instance) {
                    setTimeout(function() {
                        $(opt.saveEl).trigger('click');
                    }, 200);
                },
            }
        });
    });
});
$(document).on('IIDE.form_submit.before', function() {
    $.each(shift.codemirror.instances, function (elid, instance) {
        $('#' + elid).val(instance.getDoc().getValue());
    });
});

/**
 * CodeMirror space indention
 */
function cmSpaceTab(editor) {
    if (editor.somethingSelected()) {
        editor.indentSelection('add');
    } else {
        editor.replaceSelection(editor.getOption('indentWithTabs')? '\t':
            Array(editor.getOption('indentUnit') + 1).join(' '), 'end', '+input');
    }
}
