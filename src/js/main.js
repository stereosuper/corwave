

const $ = require('jquery-slim');

// require('gsap');
require('gsap/CSSPlugin');
const TweenLite = require('gsap/TweenLite');


$(() => {
    const mainMenu = require('./mainMenu.js');
    const burgerMenu = require('./burgerMenu.js');
    const win = require('./Window.js');
    const io = require('./io.js');
    const fallback = require('./fallback.js');
    const slider = require('./slider.js');
    const scroll = require('./Scroll.js');
    const gallery = require('./gallery.js');

    const body = $('body');
    const html = $('html');
    const header = $('.js-header');
    const menu = $('.js-menu-main');
    const burger = $('.js-burger');


    function loadHandler() {
        fallback(body, html);

        $('.js-slider').each(function (index, el) {
            const auto = !!$(el).attr('data-auto-slide');
            slider($(this), auto);
        });

        win.noTransitionElts = $('#main-menu, #headerWrapper, .js-header-sub-menu, .js-first-level-item > a, .wrapper-nav-lang, .menu-main .sub-menu, .menu-main a, .menu-main span, .menu-main>li .icon-arrow-down');
        win.init();
        scroll.init();
        io.init();

        mainMenu(header, menu);
        burgerMenu(header, burger);
        gallery();
    }


    // isMobile.any ? body.addClass('is-mobile') : body.addClass('is-desktop');

    // Since script is loaded asynchronously, load event isn't always fired !!!
    document.readyState === 'complete'
        ? loadHandler()
        : $(window).on('load', loadHandler);
});
