require('gsap/TweenLite');
require('gsap/ScrollToPlugin');

const $ = require('jquery-slim');
const scrollToAnchor = require('./scrollToAnchor');

module.exports = function scrollToLinks() {
    const links = $('a.scroll-to');

    const hash = $(window.location)
        .attr('hash')
        .replace('/', '');

    if ($(`${hash}-will-scroll`).length) {
        scrollToAnchor(`${hash}-will-scroll`);
    }

    const setHash = newHash => {
        window.location.hash = newHash;
    };

    links.on('click', e => {
        e.preventDefault();
        if (e.target && e.target.hash !== '') {
            let targetId = document.getElementById(
                e.target.hash.replace('#', '')
            );
            let targetHash = e.target.hash;

            if (!targetId) {
                targetId = document.getElementById(
                    `${e.target.hash.replace('#', '')}-will-scroll`
                );
                targetHash = `${e.target.hash}-will-scroll`;
                setHash(e.target.hash);
            }

            if (targetId) {
                TweenMax.to(window, 1, {
                    ease: Power3.easeOut,
                    scrollTo: {
                        y: targetHash,
                        offsetY: 100,
                        autoKill: false,
                    },
                });
            }
        }
    });
};
