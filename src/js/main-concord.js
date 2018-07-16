const $ = require('jquery-slim');

// require('gsap');
require('gsap/CSSPlugin');

$(() => {
    const menuHover = require('./rolloverMenu.js');
    const burger = require('./burger.js');
    const requestAnimFrame = require('./requestAnimFrame.js');
    const throttle = require('./throttle.js');
    const win = require('./Window.js'); // SEE: paste on corwave
    const io = require('./io.js'); // SEE: paste on corwave
    const fallback = require('./fallback.js'); // SEE: paste on corwave
    const slider = require('./slider.js');
    const scrollToLinks = require('./scrollToLinks.js');
    const scrollListener = require('./scrollListener.js'); // SEE: paste on corwave
    const companyMosaic = require('./companyMosaic.js');
    const loadListItems = require('./loadListItems.js');
    const shares = require('./shares.js');
    const gallery = require('./gallery.js'); // SEE: paste on corwave
    const popUp = require('./pop-up.js');

    const body = $('body');
    const html = $('html');

    // window.outerWidth returns the window width including the scroll, but it's not working with $(window).outerWidth
    let windowWidth = window.outerWidth,
        windowHeight = $(window).height();

    const loadHandler = () => {
        fallback(body, html);
        scrollListener();
        scrollToLinks();

        win.noTransitionElts = $('#main-menu, #headerWrapper, .js-header-sub-menu, .js-first-level-item > a');
        win.init();
        io.init();

        $('.js-slider').each(function () {
            slider($(this));
        });

        companyMosaic($('.js-company-mosaic'));
        menuHover($('.js-main-header'));
        burger($('#burger-menu'));
        loadListItems(io);
        shares(body);
        gallery();
        popUp();
    };

    // Since script is loaded asynchronously, load event isn't always fired !!!
    document.readyState === 'complete'
        ? loadHandler()
        : $(window).on('load', loadHandler);
});
