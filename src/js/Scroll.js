const $ = require('jquery-slim');

const win = require('./Window');
const requestAnimFrame = require('./requestAnimFrame.js');
const throttle = require('./throttle.js');

const Scroll = function ScrollClass() {
    this.scrollTop = $(window).scrollTop() || window.scrollY;
    this.event = null;
    this.scrollFunctions = [];
    this.endFunctions = [];
    this.timeout = null;

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
        clearTimeout( this.timeout );

        this.timeout = setTimeout(() => {
            this.onScrollEnd();
        }, 66);
        
        if (this.scrollTop > 0) {
            header.addClass('solid-header');
        } else {
            header.removeClass('solid-header');
        }

        this.scrollFunctions.forEach((f) => {
            f();
        });
    };

    this.addScrollFunction = (f, onEnd = false) => {
        this.scrollFunctions.push(f);
        if(onEnd) this.endFunctions.push(f);
    };

    this.addEndFunction = (f) => {
        this.endFunctions.push(f);
    };


    this.init = () => {
        this.scrollHandler();
        $(window).on(
            'scroll',
            throttle((e) => {
                this.event = e;
                requestAnimFrame(this.scrollHandler);
            })
        );
    };

    this.onScrollEnd = () => {
        this.endFunctions.forEach((f) => {
            f();
        });
    }
};

module.exports = new Scroll();
