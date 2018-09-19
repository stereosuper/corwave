const $ = require('jquery-slim');
const snif = require('./Snif');

const init = function init(body, html) {
    if (snif.isIOS()) html.addClass('is-ios');

    if (snif.isSafari()) html.addClass('is-safari');

    if (snif.isFF()) html.addClass('is-ff');

    if (snif.isMS()) {
        html.addClass('is-ms');
        module.exports.objectFitFallback();
    }

    if (snif.isIe11()) html.addClass('is-ie');

    if (!snif.mixBlendModeSupport()) {
        body.addClass('no-mix-blend-mode-support');
    }
};

const objectFitFallback = function objectFitFallback() {
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
        const lazyImages = [].slice.call(document.getElementsByClassName('lazy-image'));

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
                objectFitFallback();
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
