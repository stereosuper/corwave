require('gsap/TweenLite');
require('gsap/ScrollToPlugin');

const $ = require('jquery-slim');

module.exports = function scrollToLinks() {
    const links = $('a.scroll-to');

    links.on('click', (e) => {
        e.preventDefault();
        if (e.target && e.target.hash !== '') {
            const targetId = document.getElementById(e.target.hash.replace('#', ''));
            if (targetId) {
                TweenMax.to(window, 1, { ease: Power3.easeOut, scrollTo: { y: e.target.hash, offsetY: 100 } });
            }
        }
    });
};
