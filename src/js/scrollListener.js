import $ from 'jquery';
import requestAnimFrame from './requestAnimFrame.js';
import throttle from './throttle.js';

module.exports = () => {
    const menu = $('#burger-menu');
    const popUp = $('.js-pop-up');
    const iframe = popUp.find('iframe');
    let scrollEvent;

    function theySeeMeRollin(event) {
        const wScroll = $(window).scrollTop() || window.scrollY;
        const src = iframe.attr('src');

        if (wScroll > 0) {
            $('.js-main-header').addClass('solid-header');
        } else {
            $('.js-main-header').removeClass('solid-header');
        }

        if (popUp.hasClass('shown')) {
            const stopAutoplay = src.replace('&autoplay=1', '&autoplay=0');
            const oldDataScroll = parseInt(popUp.attr('data-scroll'), 10);
            const difference = wScroll - oldDataScroll;

            if (oldDataScroll === 0) {
                popUp.attr('data-scroll', wScroll);
            } else if (difference > 100) {
                iframe.attr('src', stopAutoplay);
                popUp.removeClass('shown');
                popUp.attr('data-scroll', 0);
            }
        }
    }

    theySeeMeRollin();

    $(window).on(
        'scroll',
        throttle((e) => {
            scrollEvent = e;
            requestAnimFrame(theySeeMeRollin);
        }, 60),
    );
};
