const LazyLoad = require('vanilla-lazyload');

const fallback = require('./fallback');

module.exports = function lazyLoadImages() {
    const lazy = new LazyLoad({
        elements_selector: '.lazy-image, .lazy-background',
        threshold: 400,
    });
    fallback.lazyLoadIEFallback();

    document.addEventListener('LazyLoadAjax', () => {
        lazy.update();
        fallback.lazyLoadIEFallback();
    });
};
