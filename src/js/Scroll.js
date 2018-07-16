const $ = require('jquery-slim');
const requestAnimFrame = require('./requestAnimFrame.js');
const throttle = require('./throttle.js');

const scroll = function () {
    this.scrollTop = $(window).scrollTop() || window.scrollY;
    this.event = null;

    this.scrollHandler = () => {
        this.scrollTop = $(window).scrollTop() || window.scrollY;
        if (this.scrollTop > 0) {
            $('.js-header').addClass('solid-header');
        } else {
            $('.js-header').removeClass('solid-header');
        }
    };


    this.init = () => {
        this.scrollHandler();
        $(window).on('scroll', throttle((e) => {
            this.event = e;
            requestAnimFrame(this.scrollHandler);
        }, 60));
    };
};

module.exports = new scroll();
