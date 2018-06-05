const $ = require('jquery-slim');
const TweenLite = require('gsap/TweenLite');

const requestAnimFrame = require('./requestAnimFrame.js');
const throttle = require('./throttle.js');
const win = require('./Window');

var init = function( header, menu ){

    if( !menu.length ) return;
    let maxH;
    this.w = window.outerWidth;
    this.h = $(window).height();

    menu.on('mouseenter', '.menu-item-has-children', function(e){
        e.preventDefault();
        if(win.w > 960){
            header.addClass('on');
        }
    }).on('mouseleave', '.menu-item-has-children', function(e){
        e.preventDefault();
        header.removeClass('on');
    }).on('click', '.menu-item-has-children', function(e){
        e.preventDefault();
        $(this).toggleClass('on');
        if($(this).hasClass('on')){
            maxH = 500;
        }else{
            maxH = 0;
        }
        TweenLite.to($(this).find('.sub-menu-wrap'), 0.5, {css: {maxHeight: maxH}});
    });


    const resizeHandler = () => {

        if(this.h == $(window).height()){
            TweenLite.set($('.menu-item-has-children.on .sub-menu-wrap'), {clearProps: 'all'});
            $('.menu-item-has-children.on').removeClass('on');
            $('header.menu-open').removeClass('menu-open');
        }

        this.w = window.outerWidth;
        this.h = $(window).height();

    };

    $(window).on('resize', throttle(() => {
        requestAnimFrame(resizeHandler);
    }, 60));
}

var as = function() {
    console.log('resize');
    
}


module.exports = {
    init: init,
    as: as
}
