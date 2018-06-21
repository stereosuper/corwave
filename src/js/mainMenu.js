const $ = require('jquery-slim');
const TweenLite = require('gsap/TweenLite');

const win = require('./Window');

module.exports = function mainMenu(header, menu) {
    if (!menu.length) return;
    let maxH;
    let liParent;

    menu.on('mouseenter', '.menu-item-has-children', (e) => {
        e.preventDefault();
        if (win.w > 960) {
            header.addClass('on');
        }
    }).on('mouseleave', '.menu-item-has-children', (e) => {
        e.preventDefault();
        header.removeClass('on');
    }).on('click', '.menu-item-has-children > span', function itemClick(e) {
        console.log(win.w);
        let easing = Expo.easeOut;

        if (win.w <= 960) {
            e.preventDefault();
            liParent = $(this).parents('.menu-item-has-children');
            liParent.toggleClass('on');
            if (liParent.hasClass('on')) {
                maxH = 500;
                easing = Expo.easeIn;
            } else {
                maxH = 0;
                easing = Expo.easeOut;
            }
            TweenLite.to(liParent.find('.sub-menu-wrap'), 0.3, { css: { maxHeight: maxH }, ease: easing });
        }
    });

    const resizeHandler = () => {
        if (win.h == $(window).height()) {
            TweenLite.set($('.menu-item-has-children.on .sub-menu-wrap'), { clearProps: 'all' });
            $('.menu-item-has-children.on').removeClass('on');
            $('header.menu-open').removeClass('menu-open');
        }
    };

    win.addResizeFunction(resizeHandler);
};
