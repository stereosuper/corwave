const $ = require('jquery-slim');

module.exports = function( header, burger ){

    if( !burger.length ) return;

    burger.on('click', function(e){
        e.preventDefault();
        header.toggleClass('menu-open');
    });
}
