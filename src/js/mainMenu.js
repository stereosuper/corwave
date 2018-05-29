const $ = require('jquery-slim');

module.exports = function( header, menu ){

    if( !menu.length ) return;
    const menuLi = menu.find('> li');

    menu.on('mouseenter', '.menu-item-has-children', function(e){
        e.preventDefault();
        header.addClass('on');
    }).on('mouseleave', '.menu-item-has-children', function(e){
        e.preventDefault();
        header.removeClass('on');
    });
}
