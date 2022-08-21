if (typeof shift !== 'undefined' && shift.env.development === 1) {
    console.log(shift);
}

//=== UIkit components
UIkit.mixin({
    data: {
        mode: 'click',
        animation: ['uk-animation-slide-bottom-small']
    }
}, 'dropdown');
