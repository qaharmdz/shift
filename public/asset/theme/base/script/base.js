/* jshint -W097, -W117 */
/* globals $, document, shift */

'use strict';

if (typeof shift !== 'undefined' && shift.env.development === 1) {
    console.log(shift);
}

//=== UIkit components
UIkit.mixin({
    data: {
        offset: 10,
        mode: 'click',
    }
});
UIkit.mixin({
    data: {
        offset: 8,
        mode: 'click',
        animation: ['uk-animation-slide-bottom-small'],
    }
}, 'dropdown');
UIkit.mixin({
    data: {
        animation: ['uk-animation-fade uk-animation-fast']
    }
}, 'tab');
