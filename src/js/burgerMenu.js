const $ = require('jquery-slim');

module.exports = function burgerMenu(header, burger) {
    if (!burger.length) return;

    burger.on('click', (e) => {
        e.preventDefault();

        if (header.hasClass('menu-open')) {
            const menuItemsWithChildren = $('.menu-item-has-children');

            TweenLite.to($('.menu-item-has-children.on').find('.sub-menu-wrap'), 0.3, {
                css: { maxHeight: 0 },
                ease: Power4.easeOut,
                onComplete: () => {
                    header.toggleClass('menu-open');
                },
            });

            menuItemsWithChildren.removeClass('on').removeClass('was-on');
        } else {
            header.toggleClass('menu-open');
        }
    });
};
