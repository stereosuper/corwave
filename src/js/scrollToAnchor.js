require('gsap/TweenLite');
require('gsap/ScrollToPlugin');

module.exports = function scrollToAnchor(anchor) {
    if (anchor.length !== 0) {
        TweenLite.to(window, 1, {
            ease: Power3.easeOut,
            scrollTo: {
                y: anchor,
                offsetY: 100,
                autoKill: false,
            },
        });
    }
}
;