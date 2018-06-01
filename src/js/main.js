

const $ = require('jquery-slim');

// require('gsap');
require('gsap/CSSPlugin');
const TweenLite = require('gsap/TweenLite');


$(() => {
    const requestAnimFrame = require('./requestAnimFrame.js');
    const throttle = require('./throttle.js');
    const noTransition = require('./noTransition.js');
    const mainMenu = require('./mainMenu.js');

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
    let windowWidth = window.outerWidth;
    let windowHeight = $(window).height();
    const header = $('.js-header');
    const menu = $('.js-menu-main');


    function resizeHandler() {
        windowWidth = window.outerWidth;
        windowHeight = $(window).height();
    }

    function loadHandler() {
        fallback(body, html);
        win.noTransitionElts = $('#main-menu, #headerWrapper, .js-header-sub-menu, .js-first-level-item > a',);
        win.init();
        scroll.init();
        io.init();

        gallery();
    }


    // isMobile.any ? body.addClass('is-mobile') : body.addClass('is-desktop');
    mainMenu(header, menu);

    // Since script is loaded asynchronously, load event isn't always fired !!!
    document.readyState === 'complete'
        ? loadHandler()
        : $(window).on('load', loadHandler);
});
