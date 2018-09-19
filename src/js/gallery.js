const $ = require('jquery-slim');
require('gsap/TweenMax');

module.exports = function gallery({ elements = {} } = {}) {
    const { body } = elements;
    
    function getBiggestImage(image) {
        const srcset = image.attr('srcset');
        let biggestSrc = '';
        let lastsrcset = '';
        let lastsrc = '';
        let lastpuresrc = '';
        if (srcset && typeof srcset !== 'undefined') {
            lastsrcset = srcset.split(',');
            lastsrc = lastsrcset[lastsrcset.length - 1];
            lastpuresrc = lastsrc.split(' ');
            [, biggestSrc] = lastpuresrc;
        } else {
            biggestSrc = image.attr('src');
        }
        return biggestSrc;
    }

    function magnifyImage(ctx) {
        ctx.parent()
            .parent()
            .addClass('magnified');
        const image = ctx.find('img');

        // NOTE: Getting src of biggest image from srcset
        const biggestSrc = getBiggestImage(image);

        // Append all elements to DOM
        var magnifiedImageContainer = document.createElement('div');
        magnifiedImageContainer.classList.add('magnified-image-container');
        magnifiedImageContainer.id = 'magnified-image-container';
        body.append(magnifiedImageContainer);

        var magnifiedImageWrapper = document.createElement('div');
        magnifiedImageWrapper.classList.add('magnified-image-wrapper');
        magnifiedImageContainer.appendChild(magnifiedImageWrapper);

        // Image
        var magnifiedImage = document.createElement('img');
        magnifiedImage.src = biggestSrc;
        magnifiedImage.classList.add('magnified-image');
        magnifiedImageWrapper.appendChild(magnifiedImage);

        // Arrow Left
        const arrowLeftUse = document.createElementNS(
            'http://www.w3.org/2000/svg',
            'use',
        );
        arrowLeftUse.setAttributeNS(
            'http://www.w3.org/1999/xlink',
            'xlink:href',
            '#icon-arrow-light',
        );

        const arrowLeft = document.createElementNS(
            'http://www.w3.org/2000/svg',
            'svg',
        );
        arrowLeft.setAttribute('class', 'icon icon-arrow-light arrow-left');
        //arrowLeft.classList.add('icon', 'icon-arrow-light', 'arrow-left');

        // Arrow right
        const arrowRightUse = document.createElementNS(
            'http://www.w3.org/2000/svg',
            'use',
        );
        arrowRightUse.setAttributeNS(
            'http://www.w3.org/1999/xlink',
            'xlink:href',
            '#icon-arrow-light',
        );

        const arrowRight = document.createElementNS(
            'http://www.w3.org/2000/svg',
            'svg',
        );
        arrowRight.setAttribute('class', 'icon icon-arrow-light arrow-right');
        //arrowRight.classList.add('icon', 'icon-arrow-light', 'arrow-right');

        arrowLeft.appendChild(arrowLeftUse);
        arrowRight.appendChild(arrowRightUse);
        magnifiedImageWrapper.appendChild(arrowLeft);
        magnifiedImageWrapper.appendChild(arrowRight);

        // Cross
        const crossContainer = document.createElement('div');
        crossContainer.classList.add('cross-container');
        magnifiedImageWrapper.appendChild(crossContainer);

        const cross = document.createElement('span');
        cross.classList.add('cross');
        crossContainer.appendChild(cross);
    }

    function changeMagnifiedImage(status, magnified) {
        let index = magnified.index('.gallery-item');
        const galleryItems = magnified.parent().find('.gallery-item');
        let sign = status === 'next' ? 1 : 0;
        sign = status === 'previous' ? -1 : sign;

        if (index >= galleryItems.length - 1) {
            index = -1;
        }
        const toMignify = galleryItems.eq(index + sign);
        let biggestSrc = '';

        if (toMignify.length) {
            toMignify.addClass('magnified');
            magnified.removeClass('magnified');

            biggestSrc = getBiggestImage(toMignify.find('img'));
            $('#magnified-image-container')
                .find('img')
                .attr('src', biggestSrc);
        }
    }

    function closePreview() {
        $('.js-gallery').find('.magnified').removeClass('magnified');
        TweenMax.delayedCall(0.1, () => {
            $('#magnified-image-container').remove();
        });
    }

    $('.js-gallery').on(
        'click',
        '.gallery-icon > .gallery-image-wrapper ',
        function (e) {
            e.preventDefault();
            magnifyImage($(this));
        },
    );

    body.on(
        'click touchstart',
        '#magnified-image-container .cross-container',
        () => {
            closePreview();
        },
    );

    body.on(
        'click touchstart',
        '#magnified-image-container .magnified-image-wrapper',
        (e) => {
            if (e.currentTarget === e.target) {
                closePreview();
            }
        },
    );

    body.on(
        'click touchstart',
        '#magnified-image-container .arrow-left',
        () => {
            changeMagnifiedImage(
                'previous',
                $('.js-gallery').find('.magnified'),
            );
        },
    );

    body.on(
        'click touchstart',
        '#magnified-image-container .arrow-right',
        () => {
            changeMagnifiedImage('next', $('.js-gallery').find('.magnified'));
        },
    );
};
