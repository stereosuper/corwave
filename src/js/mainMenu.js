const $ = require('jquery-slim');
require('gsap/TweenLite');

const CustomEase = require('./plugins/CustomEase');
const win = require('./Window');
const scrollToAnchor = require('./scrollToAnchor');

module.exports = function mainMenu(header, menu) {
    if (!menu.length) return;
    let maxH;
    let liParent;
    const { host } = location;

    const checkAnchorLink = (el) => {
        if (el === '' || el === host || el === ('http:' || 'https')) {
            return false;
        }
        return true;
    }

    menu.on('mouseenter', '.menu-item-has-children.is-parent', (e) => {
        e.preventDefault();
        if (win.w > 960) {
            header.addClass('on');
        }
    })
        .on('mouseleave', '.menu-item-has-children.is-parent', (e) => {
            e.preventDefault();
            header.removeClass('on');
        })
        .on(
            'click',
            '.menu-item-has-children.is-parent > a, .menu-item-has-children.is-parent > span',
            function itemClick(e) {
                const parents = $('.menu-item-has-children');
                const easing = CustomEase.create(
                    'custom',
                    'M0,0 C0.5,-0.1 0,1 1,1',
                );

                if (win.w <= 960) {
                    e.preventDefault();
                    liParent = $(this).parents('.menu-item-has-children.is-parent');

                    parents.each((index, parent) => {
                        if (
                            $(parent)
                                .not(liParent)
                        ) {
                            $(parent).addClass('was-on');
                            $(parent).removeClass('on');
                        }
                    });

                    liParent.toggleClass('on');
                    if (liParent.hasClass('on')) {
                        maxH = 500;
                    } else {
                        maxH = 0;
                    }
                    
                    TweenLite.to(
                        $('.menu-item-has-children.was-on.is-parent').find('.sub-menu-wrap.is-parent'),
                        0.5,
                        {
                            css: { maxHeight: 0 },
                            ease: Power4.easeInOut,
                            onComplete: () => {
                                TweenLite.to(
                                    liParent.find('.sub-menu-wrap.is-parent'),
                                    0.9,
                                    { css: { maxHeight: maxH }, ease: easing },
                                );
                            },
                        },
                    );

                    parents.removeClass('was-on');
                }
            },
        ).on('click', 'a', function anchorClick(e) {
            console.log(location);
            const hash = location.hash;
            // -will-scroll
            
            if ($(this).attr('href').split('/').filter(checkAnchorLink).join('') === location.href.split('/').filter(checkAnchorLink).join('')) {
                e.preventDefault();
                scrollToAnchor(`${hash}-will-scroll`);
            }
        });

    const resizeHandler = () => {
        if (win.h === $(window).height()) {
            if ($('.menu-item-has-children.on.is-parent .sub-menu-wrap.is-parent').length) {
                TweenLite.set($('.menu-item-has-children.is-parent.on .sub-menu-wrap.is-parent'), {
                    clearProps: 'all',
                });
            }
            $('.menu-item-has-children.is-parent.on').removeClass('on');
            $('header.menu-open').removeClass('menu-open');

            if (win.w > 960) {
                $('.menu-item-has-children')
                    .find('.sub-menu-wrap')
                    .css({ maxHeight: 'none' });
            } else {
                $('.menu-item-has-children.is-parent')
                    .find('.sub-menu-wrap.is-parent')
                    .css({ maxHeight: '0' });
            }
        }
    };

    win.addResizeFunction(resizeHandler);
};
