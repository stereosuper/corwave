const $ = require('jquery-slim');
const io = require('./io');

require('hammerjs');
require('gsap/TweenLite');
const CSSRulePlugin = require('gsap/CSSRulePlugin');
const CustomEase = require('./plugins/CustomEase');

module.exports = function slider(slider, auto = false) {
    if (!slider.length) return;

    const slideDelay = 7;
    let stopSlider = false;
    const firstBullet = slider.find('.bullet').eq(0);
    const length = slider.find('.slide').length;
    let swipeInstance = null;
    let index = 0;

    const showSlide = (bulletIndex) => {
        index =
            typeof bulletIndex === 'number'
                ? bulletIndex
                : bulletIndex.data('slide');

        const prevSlide = slider.find('.slide.active');
        const prevBullet = slider.find('.bullet.active');
        const slide = slider.find(`.slide[data-slide=${index}]`);
        const slideSide = slide.find('.left-side,.txt-side');
        const slideSideBackground = slideSide.find('.slide-layer-background');
        const sliderInnerTxtSide = slide.find('.inner-left-side, .inner-txt-side',);

        const bullet = slider.find(`.bullet[data-slide=${index}]`);

        if (bullet.index() === prevBullet.index()) return;
        const reverse = bullet.index() < prevBullet.index() ? -1 : 1;

        prevBullet.removeClass('active');
        prevSlide.removeClass('active');
        prevSlide.addClass('was-active');
        bullet.addClass('active');
        slide.removeClass('was-active');
        slide.addClass('active');

        TweenLite.set(slideSideBackground, {
            xPercent: 100 * reverse,
            skewX: 0,
        });
        TweenLite.set(sliderInnerTxtSide, { opacity: 0 });

        TweenLite.fromTo(
            slideSideBackground,
            0.8,
            {
                xPercent: 100 * reverse,
                transformOrigin: '0% 50%',
            },
            {
                xPercent: 0,
                ease: CustomEase.create("custom", "M0,0 C0.75,-0.03 0.85,0 1,1"),
                onComplete() {
                    TweenLite.to(sliderInnerTxtSide, 0.8, {
                        opacity: 1,
                        ease: CustomEase.create("custom", "M0,0 C0.15,1.02 0.25,1.03 1,1"),
                    });
                },
            },
        );

        if (auto && !stopSlider)
            {TweenLite.delayedCall(slideDelay, showSlide, [
                bullet.next().length ? bullet.next() : firstBullet
            ]);}
    };

    slider.on('click', '.bullet', function () {
        TweenLite.killDelayedCallsTo(showSlide);
        TweenLite.killTweensOf(showSlide);
        showSlide($(this));
    });

    slider.on('mouseenter', () => {
        TweenLite.killDelayedCallsTo(showSlide);
        stopSlider = true;
    });

    slider.on('mouseleave', () => {
        stopSlider = false;
        const newIndex =
            slider.find(`.slide[data-slide=${parseInt(index, 10) + 1}]`)
                .length !== 0
                ? slider.find(`.slide[data-slide=${parseInt(index, 10) + 1}]`)
                : 0;
        if (auto) {
            setTimeout(() => {
                showSlide(newIndex);
            }, slideDelay * 1000);
        }
    });

    if (auto && !stopSlider)
        {TweenLite.delayedCall(7, showSlide, [firstBullet.next()]);}

    const setSwipe = (slider) => {
        if (swipeInstance !== null) return;

        swipeInstance = new Hammer(slider);

        swipeInstance
            .on('swipeleft', () => {
                auto
                    ? TweenLite.killDelayedCallsTo(showSlide)
                    : TweenLite.killTweensOf(showSlide);

                if (parseInt(index) + 1 < length) {
                    showSlide(parseInt(index) + 1);
                }
            })
            .on('swiperight', () => {
                auto
                    ? TweenLite.killDelayedCallsTo(showSlide)
                    : TweenLite.killTweensOf(showSlide);

                if (parseInt(index) > 0) {
                    showSlide(parseInt(index) - 1);
                }
            });

        swipeInstance.on('press', () => {
            auto
                ? TweenLite.killDelayedCallsTo(showSlide)
                : TweenLite.killTweensOf(showSlide);
        });

        swipeInstance.on('pressup', () => {
            showSlide(parseInt(index));
        });
    };

    const destroySwipe = () => {
        if (!swipeInstance) return;

        swipeInstance.destroy();
        swipeInstance = null;
    };

    window.outerWidth > 780 ? destroySwipe() : setSwipe(slider[0]);
};
