const $ = require('jquery-slim');

const fallbacks = {
    isSafari:
        (!!navigator.userAgent.match(/safari/i) &&
            !navigator.userAgent.match(/chrome/i) &&
            typeof document.body.style.webkitFilter !== 'undefined' &&
            !window.chrome) ||
        /a/.__proto__ == '//',
    isFF: 'MozAppearance' in document.documentElement.style,
    isIE:
        '-ms-scroll-limit' in document.documentElement.style &&
        '-ms-ime-align' in document.documentElement.style,
    mixBlendModeSupport:
        'CSS' in window &&
        'supports' in window.CSS &&
        window.CSS.supports('mix-blend-mode', 'multiply'),
};

const init = function init(body, html) {
    if (fallbacks.isSafari) html.addClass('is-safari');

    if (fallbacks.isFF) html.addClass('is-ff');

    if (fallbacks.isIE) {
        html.addClass('is-ie');
        module.exports.objectFitFallback();
    }

    if (!fallbacks.mixBlendModeSupport) {
        body.addClass('no-mix-blend-mode-support');
    }
};

const objectFitFallback = function objectFitFallback(lol) {
    if (document.documentElement.classList.contains('is-ie')) {
        const objectFit = [].slice.call(document.getElementsByClassName('object-fit'),);
        $(objectFit).each((index, image) => {
            const { src, style, parentElement } = image;
            if (src && !parentElement.classList.contains('object-fit-fixed')) {
                style.display = 'none';

                parentElement.classList.add('object-fit-fixed');

                const bg = document.createElement('span');
                bg.classList.add('object-fit-fix');
                bg.style.display = 'block';
                bg.style.position = 'absolute';
                bg.style.top = '0';
                bg.style.left = '0';
                bg.style.width = '100%';
                bg.style.height = '100%';
                bg.style.backgroundImage = `url('${src}')`;
                bg.style.backgroundSize = 'cover';
                bg.style.backgroundPosition = 'center center';
                bg.style.backgroundRepeat = 'no-repeat';

                parentElement.insertBefore(bg, image);
            }
        });
    }
};

const eventFallback = (eventName) => {
    let e;
    if (typeof Event === 'function') {
        e = new Event(eventName);
    } else {
        e = document.createEvent('Event');
        e.initEvent(eventName, true, true);
    }
    return e;
};

const lazyLoadIEFallback = () => {
    if (document.documentElement.classList.contains('is-ie')) {
        // Images
        const lazyImages = [].slice.call(document.getElementsByClassName('lazy-image'),);

        $(lazyImages).each((index, imageSource) => {
            const image = imageSource;
            const { src } = image;
            const dataSrc = image.getAttribute('data-src');
            const dataSrcset = image.getAttribute('data-srcset');
            if (
                !src &&
                (dataSrc || dataSrcset) &&
                image.classList.contains('object-fit')
            ) {
                image.setAttribute('src', dataSrc);
                module.exports.objectFitFallback();
            }
        });
    }
};

module.exports = {
    init,
    objectFitFallback,
    eventFallback,
    lazyLoadIEFallback,
};
