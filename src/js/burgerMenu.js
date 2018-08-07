const $ = require('jquery-slim');
const scroll = require('./Scroll');
const snif = require('./Snif');

module.exports = function burgerMenu(header, burger, { elements = {} } = {}) {
    if (!burger.length) return;
    const { html } = elements;
    const isIOS = snif.isIOS();
    const isSafari = snif.isSafari();

    burger.on('click', e => {
        e.preventDefault();
        const { scrollTop } = scroll;

        if (header.hasClass('menu-open')) {
            const menuItemsWithChildren = $('.menu-item-has-children');

            TweenLite.to(
                $('.menu-item-has-children.on').find('.sub-menu-wrap'),
                0.3,
                {
                    css: { maxHeight: 0 },
                    ease: Power4.easeOut,
                    onComplete: () => {
                        header.toggleClass('menu-open');
                        if (isIOS && isSafari) {
                            $(window).scrollTop(html.attr('data-scroll'));
                        }
                        html.removeClass('overflow-y-hidden');
                    },
                }
            );

            menuItemsWithChildren.removeClass('on').removeClass('was-on');
        } else {
            header.toggleClass('menu-open');
            if (isIOS && isSafari) {
                html.attr('data-scroll', scrollTop);
            }
            html.addClass('overflow-y-hidden');
        }
    });
};
