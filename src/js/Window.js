const $ = require('jquery');
require('gsap');

const requestAnimFrame = require('./requestAnimFrame.js');
const throttle = require('./throttle.js');
const io = require('./io.js');

const app = function () {
    this.w = window.outerWidth;
    this.h = $(window).height();

    this.noTransitionElts = null;

    let rtime;
    let timeout = false;
    const delta = 200;

    const resizeend = () => {
        if (new Date() - rtime < delta) {
            setTimeout(resizeend, delta);
        } else {
            timeout = false;
            this.noTransitionElts.removeClass('no-transition');
        }
    };

    const resizeHandler = () => {
        if (!io.resized) io.resized = true;

        this.w = window.outerWidth;
        this.h = $(window).height();

        this.noTransitionElts.addClass('no-transition');
        rtime = new Date();

        if (timeout === false) {
            timeout = true;
            setTimeout(resizeend, delta);
        }
    };


    this.init = () => {
        $(window).on('resize', throttle(() => {
            requestAnimFrame(resizeHandler);
        }, 60));
    };
};

module.exports = new app();
