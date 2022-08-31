/*
 * Table of Content:
 *
 * # Global Defaults
 *   - Form change confirmation
 *
 * # IIDE (Immediate Invoked Data Expressions)
 *   - data-form-monitor
 *
 * ======================================================================== */

if (typeof shift !== 'undefined' && shift.env.development === 1) {
    console.log(shift);
}


/*
 * Global Defaults
 * ======================================================================== */

 //=== Form change confirmation
window.onbeforeunload = function() {
    if (shift.formChanged) {
        return shift.i18n.confirm_change;
    }
};

//=== UIkit components
UIkit.mixin({
    data: {
        mode: 'click',
        animation: ['uk-animation-slide-bottom-small']
    }
}, 'dropdown');
/*
 * Immediate Invoked Data Expressions (IIDE)
 * ======================================================================== */

/**
 * For new created element retrigger IIDE
 * Ex: $(document).trigger('IIDE.form_monitor');
 *
 */
$(document).ready(function()
{
    $(document).trigger('IIDE.init');
});

$(document).on('IIDE.init IIDE.form_monitor', function(event)
{
    /**
     * Monitor form input change
     *
     * @usage
     * <form data-form-monitor>..</form>
     * <div data-form-monitor='{"target":"input"}'>..</div>
     */
    $('[data-form-monitor]').each(function() {
        let el  = this,
            opt = $.extend({
                target : 'input, select, textarea',
            }, $(el).data('formMonitor'));

        $(el).on('input change paste', opt.target, function() {
            shift.formChanged = true;
        });
    });
});

