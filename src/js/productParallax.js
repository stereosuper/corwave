require('gsap/TweenLite');
const $ = require('jquery-slim');

const scroll = require('./Scroll');

module.exports = function productParallax() {
    const MULT = 4;

    const productImage = $('.js-product-header-image');
    const productTexts = $('.js-product-header-texts');
    const productMainTitle = productTexts.find('h1');
    const productSecondaryTitle = productTexts.find('h2');

    const simpleParallax = (intensity, element) => {
        const { scrollTop } = scroll;
        const velocity = intensity * MULT;
        const imgPos = `${scrollTop / velocity}px`;
        TweenLite.to(element, 0.1, { y: imgPos, force3D: true });
    };

    scroll.addScrollFunction(() => {
        simpleParallax(1, productImage);
        simpleParallax(6, productMainTitle);
        simpleParallax(3, productSecondaryTitle);
    });
};
