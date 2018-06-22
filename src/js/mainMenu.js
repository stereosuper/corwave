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
    }).on('click', '.menu-item-has-children > a, .menu-item-has-children > span', function itemClick(e) {
        const parents = $('.menu-item-has-children');
        const easing = Power4.easeInOut;

        if (win.w <= 960) {
            e.preventDefault();
            liParent = $(this).parents('.menu-item-has-children');

            parents.each((index, parent) => {
                if ($(parent).not(liParent).hasClass('on')) {
                    $(parent).addClass('was-on');
                    $(parent).removeClass('on');
                }
            });

            liParent.toggleClass('on');
            if (liParent.hasClass('on')) {
                maxH = 500;
                // easing = Power4.easeIn;
            } else {
                maxH = 0;
                // easing = Power4.easeOut;
            }

            TweenLite.to($('.menu-item-has-children.was-on').find('.sub-menu-wrap'), 0.5, {
                css: { maxHeight: 0 },
                ease: Power4.easeInOut,
                onComplete: () => {
                    TweenLite.to(liParent.find('.sub-menu-wrap'), 0.5, { css: { maxHeight: maxH }, ease: easing });
                },
            });

            parents.removeClass('was-on');
        }
    });

    const resizeHandler = () => {
        if (win.h === $(window).height()) {
            TweenLite.set($('.menu-item-has-children.on .sub-menu-wrap'), { clearProps: 'all' });
            $('.menu-item-has-children.on').removeClass('on');
            $('header.menu-open').removeClass('menu-open');

            if (win.w > 960) {
                $('.menu-item-has-children').find('.sub-menu-wrap').css({ maxHeight: 'none' });
            } else {
                $('.menu-item-has-children').find('.sub-menu-wrap').css({ maxHeight: '0' });
            }
        }
    };

    win.addResizeFunction(resizeHandler);
};
