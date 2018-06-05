

const $ = require('jquery-slim');

// require('gsap');
require('gsap/CSSPlugin');
const TweenLite = require('gsap/TweenLite');


$(() => {
    const mainMenu = require('./mainMenu.js');
    const burgerMenu = require('./burgerMenu.js');

    /** 
     * NOTE: Lot of changes, addition of a lot of scripts...
     * Concord's main.js and pasted as main-concord.js if you need it
     */
    const win = require('./Window.js');
    const io = require('./io.js');
    const fallback = require('./fallback.js');
    const scroll = require('./Scroll.js');
    const gallery = require('./gallery.js');

    const body = $('body');
    const html = $('html');
    // window.outerWidth returns the window width including the scroll, but it's not working with $(window).outerWidth
    const header = $('.js-header');
    const menu = $('.js-menu-main');
    const burger = $('.js-burger');


    function loadHandler() {
        fallback(body, html);
        win.noTransitionElts = $('#main-menu, #headerWrapper, .js-header-sub-menu, .js-first-level-item > a, .wrapper-nav-lang, .menu-main .sub-menu, .menu-main a, .menu-main span, .menu-main>li .icon-arrow-down');
        win.init();
        scroll.init();
        io.init();

        gallery();
    }


    // isMobile.any ? body.addClass('is-mobile') : body.addClass('is-desktop');
    mainMenu.init(header, menu);
    
    burgerMenu(header, burger);

    // Since script is loaded asynchronously, load event isn't always fired !!!
    document.readyState === 'complete'
        ? loadHandler()
        : $(window).on('load', loadHandler);
});
