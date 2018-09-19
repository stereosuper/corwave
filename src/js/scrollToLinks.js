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
        if (e.currentTarget.pathname !== window.location.pathname) {
            return;
        }
        e.preventDefault();

        if (e.currentTarget && e.currentTarget.hash !== '') {
            let targetId = document.getElementById(
                e.currentTarget.hash.replace('#', '')
            );
            let targetHash = e.currentTarget.hash;

            if (!targetId) {
                targetId = document.getElementById(
                    `${e.currentTarget.hash.replace('#', '')}-will-scroll`
                );
                targetHash = `${e.currentTarget.hash}-will-scroll`;
                setHash(e.currentTarget.hash);
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
