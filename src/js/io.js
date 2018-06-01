const $ = require('jquery-slim');
require('gsap');
require('intersection-observer');

const requestAnimFrame = require('./requestAnimFrame.js');
const throttle = require('./throttle.js');
const SplitInLines = require('./plugins/splitInLines');


const io = function io() {
    let visibleInner;
    let visibleShadow;

    this.resized = true;

    this.init = () => {
        const objectsToIO = [].slice.call(document.querySelectorAll('[data-io]'));

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.intersectionRatio > 0.15) {
                    this[`${entry.target.dataset.io}In`](entry.target);
                    if (entry.target.hasAttribute('data-io-single')) observer.unobserve(entry.target);
                } else if (entry.intersectionRatio < 0.15) {
                    this[`${entry.target.dataset.io}Out`](entry.target);
                }
            });
        }, {
            threshold: 0.15,
            rootMargin: '-100px 0px',
        });

        objectsToIO.forEach((obj) => {
            if (!obj.hasAttribute('data-io-observed')) {
                observer.observe(obj);
                $(obj).attr('data-io-observed', '');
            }
        });
    };


    const isInViewport = (img, parent, out = false) => (el, index, array) => {
        const offset = out ? 0 : 0;
        const elementLeft = img ? $(el).find('img').offset().left : $(el).offset().left;
        const elementRight = img ? elementLeft + $(el).find('img').outerWidth() : elementLeft + $(el).outerWidth();


        const elementTop = img ? $(el).find('img').offset().top : $(el).offset().top;
        const elementBottom = img ? elementTop + $(el).find('img').outerHeight() : elementTop + $(el).outerHeight();

        const parentTop = $(parent).offset().top;
        const parentBottom = parentTop + $(parent).outerHeight();

        return elementRight > 0 && elementBottom + offset > parentTop && elementTop + offset < parentBottom;
    };

    this.contractIn = (el) => {
        const wrapper = el;
        const title = el.querySelector('h2');
        const titleSplit = new SplitInLines(title);
        const lines = el.querySelectorAll('.split-lines-inner');
        const texts = el.querySelectorAll('.contract-text');


        TweenMax.killChildTweensOf(title);

        if (this.resized) {
            visibleInner = [].slice.call(el.querySelectorAll('.contract-inner')).filter(isInViewport(true, wrapper, false));
            visibleShadow = [].slice.call(el.querySelectorAll('.contract-shadow')).filter(isInViewport(false, wrapper, false));
            this.resized = false;
        }


        if (window.matchMedia('(min-width: 961px)').matches) {
            TweenMax.staggerTo(visibleInner, 0.9, {
                force3D: true, ease: Back.easeOut.config(1.2), y: 0, opacity: 1,
            }, 0.025);

            TweenMax.staggerTo(visibleShadow, 0.9, {
                force3D: true, ease: Back.easeOut.config(1.2), scale: 1, opacity: 1,
            }, 0.025);
            TweenMax.set(title, { opacity: 1, delay: 0.6 });
            TweenMax.to(texts, 0.7, {
                delay: 0.9, ease: Power4.easeOut, opacity: 1, y: 0,
            });
            TweenMax.staggerFromTo(lines, 1, { yPercent: 100 }, { delay: 1.1, ease: Power4.easeOut, yPercent: 0 }, 0.1, () => {
                titleSplit.clear();
            });
        } else {
            TweenMax.set(title, { opacity: 1 });
            TweenMax.to(texts, 0.7, {
                delay: 0.3, ease: Power4.easeOut, opacity: 1, y: 0,
            });
            TweenMax.staggerFromTo(lines, 1, { yPercent: 100 }, { delay: 0.5, ease: Power4.easeOut, yPercent: 0 }, 0.1, () => {
                titleSplit.clear();
            });
        }
    };

    this.contractOut = (el) => {
        const wrapper = el;
        const title = el.querySelector('h2');
        const texts = el.querySelectorAll('.contract-text');


        if (this.resized) {
            visibleInner = [].slice.call(el.querySelectorAll('.contract-inner')).filter(isInViewport(true, wrapper, true));
            visibleShadow = [].slice.call(el.querySelectorAll('.contract-shadow')).filter(isInViewport(false, wrapper, true));
            this.resized = false;
        }

        TweenMax.to(title, 0.1, { opacity: 0 });

        TweenMax.staggerTo(visibleShadow, 0.3, { force3D: true, scale: 1.3, opacity: 0 }, 0.01);
        TweenMax.to(texts, 0.3, { opacity: 0, y: 50 });
        TweenMax.staggerTo(visibleInner, 0.3, { force3D: true, y: -150, opacity: 0 }, 0.01);
    };

    this.featureIn = (el) => {
        const boxes = $(el).find('.feature-item');
        const shadows = $(el).find('.feature-shadow');
        const title = el.querySelector('h2');
        const titleSplit = new SplitInLines(title);
        const lines = el.querySelectorAll('.split-lines-inner');
        const texts = el.querySelectorAll('.features-text');

        TweenMax.killChildTweensOf(title);
        const tl = new TimelineMax();


        if (window.matchMedia('(min-width: 961px)').matches) {
            tl.to(boxes.eq(0), 1.2, { ease: Power4.easeOut, y: 0, opacity: 1 })
                .to(boxes.eq(1), 1.2, {
                    ease: Power4.easeOut, delay: -1, x: 0, opacity: 1,
                })
                .to(boxes.eq(5), 1.2, {
                    ease: Power4.easeOut, delay: -1, y: 0, opacity: 1,
                })
                .to(boxes.eq(2), 1.2, {
                    ease: Power4.easeOut, delay: -1.4, x: 0, opacity: 1,
                })
                .to(boxes.eq(3), 1.2, {
                    ease: Power4.easeOut, delay: -1.2, x: 0, opacity: 1,
                })
                .to(boxes.eq(4), 1.2, {
                    ease: Power4.easeOut, delay: -1, y: 0, opacity: 1,
                })
                .to(shadows.eq(0), 0.6, {
                    ease: Power4.easeOut, y: 0, delay: -0.4, x: 0, opacity: 0.06,
                })
                .add([
                    TweenMax.to(shadows.eq(1), 0.05, { delay: -0.5, opacity: 0.04 }),
                    TweenMax.to(shadows.eq(1), 0.5, {
                        delay: -0.5, ease: Power4.easeOut, y: 0, x: 0,
                    }),
                    TweenMax.to(shadows.eq(2), 0.05, { delay: -0.5, opacity: 0.02 }),
                    TweenMax.to(shadows.eq(2), 0.5, {
                        delay: -0.5, ease: Power4.easeOut, y: 0, x: 0,
                    }),
                ]);


            TweenMax.set(title, { opacity: 1, delay: 1 });
            TweenMax.to(texts, 0.7, {
                delay: 1.3, ease: Power4.easeOut, opacity: 1, y: 0,
            });
            TweenMax.staggerFromTo(lines, 1, { yPercent: 100 }, { delay: 1.5, ease: Power4.easeOut, yPercent: 0 }, 0.1, () => {
                titleSplit.clear();
            });
        } else {
            TweenMax.set(title, { opacity: 1 });
            TweenMax.to(texts, 0.7, {
                delay: 0.3, ease: Power4.easeOut, opacity: 1, y: 0,
            });
            TweenMax.staggerFromTo(lines, 1, { yPercent: 100 }, { delay: 0.5, ease: Power4.easeOut, yPercent: 0 }, 0.1, () => {
                titleSplit.clear();
            });
        }
    };

    this.featureOut = (el) => {
        const title = el.querySelector('h2');
        const texts = el.querySelectorAll('.features-text');


        const boxes = $(el).find('.feature-item');
        const shadows = $(el).find('.feature-shadow');

        TweenMax.to(title, 0.1, { opacity: 0 });

        TweenMax.to(texts, 0.3, { opacity: 0, y: 50 });

        TweenMax.to(boxes.eq(0), 0.3, { y: -300, opacity: 0 });
        TweenMax.to(boxes.eq(1), 0.3, { x: 300, opacity: 0 });
        TweenMax.to(boxes.eq(2), 0.3, { x: -300, opacity: 0 });
        TweenMax.to(boxes.eq(3), 0.3, { x: 300, opacity: 0 });
        TweenMax.to(boxes.eq(4), 0.3, { y: 300, opacity: 0 });
        TweenMax.to(boxes.eq(5), 0.3, { delay: 0.2, y: 300, opacity: 0 });
        TweenMax.to(shadows.eq(0), 0.2, { y: -21, x: 12, opacity: 0 });
        TweenMax.to(shadows.eq(1), 0.2, { y: -21, x: 12, opacity: 0 });
        TweenMax.to(shadows.eq(2), 0.2, { y: -42, x: 24, opacity: 0 });
    };

    this.listItemIn = (el) => {
        el.classList.add('visible');
    };

    this.listItemOut = (el) => {
        el.classList.remove('visible');
    };


    const filterElements2 = (elts) => {
        let elements = [elts];

        if (elts.children.length > 0) {
            elements = [];
            const domElements = [].slice.call(elts.children);
            domElements.filter((el) => {
                elements.push(el);
            });
        }
        return elements;
    };

    const filterElements = (parent) => {
        const lasts = [];
        const getLasts = (el) => {
            if (el.childNodes.length > 1 || (el.childNodes.length === 1 && el.childNodes[0].nodeName !== '#text')) {
                el.childNodes.forEach((child) => {
                    getLasts(child);
                });
            } else if (el.childNodes.length === 1 && el.childNodes[0].nodeName === '#text') {
                lasts.push(el);
            }
        };
        getLasts(parent);
        return lasts;
    };

    this.ohTitleIn = (element) => {
        const elements = filterElements(element);


        elements.forEach((el) => {
            const titleSplit = new SplitInLines(el);
            const lines = el.querySelectorAll('.split-lines-inner');
            const tl = new TimelineMax({
                onComplete() {
                    titleSplit.clear();
                },
            });
            TweenMax.killChildTweensOf(el);
            tl.set(el, { opacity: 1 })
                .staggerFromTo(lines, 1, { yPercent: 100 }, { ease: Power4.easeOut, yPercent: 0 }, 0.1);
        });
    };

    this.ohTitleOut = (elts) => {
        const elements = filterElements(elts);
        TweenMax.to(elements, 0.1, { opacity: 0 });
    };
};

module.exports = new io();
