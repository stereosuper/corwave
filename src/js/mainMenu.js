const $ = require('jquery-slim');

module.exports = function( header, menu ){

    if( !menu.length ) return;
    const menuLi = menu.find('> li');
    
    menuLi.each(function(){
        if($(this).find('.sub-menu-wrap').length){
            $(this).addClass('has-sub-menu');
        }
    });

    menu.on('mouseenter', '.has-sub-menu', function(e){
        e.preventDefault();
        header.addClass('on');
    }).on('mouseleave', '.has-sub-menu', function(e){
        e.preventDefault();
        header.removeClass('on');
    });
}
