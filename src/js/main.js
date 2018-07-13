const $ = require('jquery-slim');

$(() => {
    const win = require('./Window.js');
    const io = require('./io.js');
    const fallback = require('./fallback.js');
    const scrollToLinks = require('./scrollToLinks.js');
    const lazyLoadImages = require('./lazyLoadImages.js');

    const mainMenu = require('./mainMenu.js');
    const burgerMenu = require('./burgerMenu.js');
    const slider = require('./slider.js');
    const scroll = require('./Scroll.js');
    const gallery = require('./gallery.js');

    const body = $('body');
    const html = $('html');
    const header = $('.js-header');
    const menu = $('.js-menu-main');
    const burger = $('.js-burger');

    function loadHandler() {
        fallback.init(body, html);
        scrollToLinks();

        $('.js-slider').each(function (index, el) {
            const auto = !!$(el).attr('data-auto-slide');
            slider($(this), auto);
        });

        win.noTransitionElts = $('#main-menu, #headerWrapper, .js-header-sub-menu, .js-first-level-item > a, .wrapper-nav-lang, .menu-main .sub-menu, .menu-main a, .menu-main span, .menu-main>li .icon-arrow-down',);
        win.init();
        scroll.init();
        io.init();

        mainMenu(header, menu);
        burgerMenu(header, burger);
        gallery({ elements: { body } });

        lazyLoadImages();
    }

    // Since script is loaded asynchronously, load event isn't always fired !!!
    document.readyState === 'complete'
        ? loadHandler()
        : $(window).on('load', loadHandler);
});
