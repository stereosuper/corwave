const $ = require('jquery-slim');
const TweenLite = require('gsap/TweenLite');

const requestAnimFrame = require('./requestAnimFrame.js');
const throttle = require('./throttle.js');
const win = require('./Window');

module.exports = function( header, menu ){

    if( !menu.length ) return;
    let maxH, liParent;

    menu.on('mouseenter', '.menu-item-has-children', function(e){
        e.preventDefault();
        if(win.w > 960){
            header.addClass('on');
        }
    }).on('mouseleave', '.menu-item-has-children', function(e){
        e.preventDefault();
        header.removeClass('on');
    }).on('click', '.menu-item-has-children > span', function(e){
        if(win.w <= 960){
            e.preventDefault();
            liParent = $(this).parents('.menu-item-has-children');
            liParent.toggleClass('on');
            if(liParent.hasClass('on')){
                maxH = 500;
            }else{
                maxH = 0;
            }
            TweenLite.to(liParent.find('.sub-menu-wrap'), 0.5, {css: {maxHeight: maxH}});
        }
    });

    const resizeHandler = () => {

        if(win.h == $(window).height()){
            TweenLite.set($('.menu-item-has-children.on .sub-menu-wrap'), {clearProps: 'all'});
            $('.menu-item-has-children.on').removeClass('on');
            $('header.menu-open').removeClass('menu-open');
        }

    };

    win.addResizeFunction(resizeHandler);

}
