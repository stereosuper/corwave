const $ = require('jquery');

module.exports = function fallback(body, html) {
    if ((!!navigator.userAgent.match(/safari/i) && !navigator.userAgent.match(/chrome/i) && typeof document.body.style.webkitFilter !== 'undefined' && !window.chrome) || (/a/.__proto__ == '//')) html.addClass('is-safari');

    if ('-ms-scroll-limit' in document.documentElement.style && '-ms-ime-align' in document.documentElement.style) {
        html.addClass('is-ie');
        module.exports.objectFitFallback();
    }

    'CSS' in window && 'supports' in window.CSS && window.CSS.supports('mix-blend-mode', 'multiply') ? '' : body.addClass('no-mix-blend-mode-support');
};

module.exports.objectFitFallback = function objectFitFallback() {
    if (document.documentElement.classList.contains('is-ie')) {
        const objectFit = [].slice.call(document.getElementsByClassName('object-fit'));
        objectFit.forEach((image) => {
            const { src, style, parentElement } = image;
            if (!parentElement.classList.contains('object-fit-fixed')) {
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

