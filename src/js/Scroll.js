const $ = require('jquery-slim');

const win = require('./Window');
const requestAnimFrame = require('./requestAnimFrame.js');
const throttle = require('./throttle.js');

const Scroll = function ScrollClass() {
    this.scrollTop = $(window).scrollTop() || window.scrollY;
    this.event = null;
    this.scrollFunctions = [];

    const gutter = 20;
    const header = $('.js-header');
    const main = $('main');

    const sidebar = $('.js-anchors-sidebar');
    const anchorsList = sidebar.length ? sidebar.find('.anchors-list') : false;

    const headerPage = main.find('.header-page');
    const headerPageInnerHeight = headerPage.innerHeight();
    const headerPagePaddingBottom = headerPage.length
        ? parseInt(headerPage.css('padding-bottom').replace('px', ''), 10)
        : false;

    this.scrollHandler = () => {
        this.scrollTop = $(window).scrollTop() || window.scrollY;
        if (this.scrollTop > 0) {
            header.addClass('solid-header');
        } else {
            header.removeClass('solid-header');
        }

        if (headerPage.length && main.find('.has-sidebar').length) {
            if (
                !sidebar.hasClass('bigger-than-screen') &&
                this.scrollTop >
                    headerPageInnerHeight - headerPagePaddingBottom * 3
            ) {
                sidebar.addClass('stick-top');
            } else {
                sidebar.removeClass('stick-top');
            }
            const sidebarOffsetWinBottom =
                win.h - (anchorsList.innerHeight() + header.innerHeight() + 50);
            if (
                this.scrollTop + win.h - sidebarOffsetWinBottom + gutter >
                main.innerHeight()
            ) {
                sidebar.addClass('under-footer');
            } else {
                sidebar.removeClass('under-footer');
            }
        }

        this.scrollFunctions.forEach((f) => {
            f();
        });
    };

    this.addScrollFunction = (f) => {
        this.scrollFunctions.push(f);
    };


    this.init = () => {
        this.scrollHandler();
        $(window).on(
            'scroll',
            throttle((e) => {
                this.event = e;
                requestAnimFrame(this.scrollHandler);
            }, 60),
        );
    };
};

module.exports = new Scroll();
