const $ = require('jquery-slim');
require('gsap');
require('intersection-observer');

const io = function io() {
    this.resized = true;

    this.init = () => {
        const objectsToIO = [].slice.call(
            document.querySelectorAll('[data-io]')
        );

        const observer = new IntersectionObserver(
            entries => {
                // this.allAnchorEntries = [];
                $(entries).each((index, entry) => {
                    if (entry.intersectionRatio > 0.15) {
                        if (this[`${entry.target.getAttribute('data-io')}In`]) {
                            this[`${entry.target.getAttribute('data-io')}In`](
                                entry
                            );
                        }

                        if (entry.target.hasAttribute('data-io-single'))
                            observer.unobserve(entry.target);
                    } else if (entry.intersectionRatio < 0.15) {
                        if (
                            this[`${entry.target.getAttribute('data-io')}Out`]
                        ) {
                            this[`${entry.target.getAttribute('data-io')}Out`](
                                entry
                            );
                        }
                    }
                });
            },
            {
                root: null,
                rootMargin: '-200px 0px -200px 0px',
                threshold: 0.15,
            }
        );

        $(objectsToIO).each(function observeObjects() {
            if (!$(this).attr('data-io-observed')) {
                observer.observe($(this)[0]);
                $(this).attr('data-io-observed', '');
            }
        });
    };

    this.revealUpIn = entry => {
        const htmlElements = [].slice.call(entry.target.children);

        htmlElements.forEach(el => {
            if (!el.classList.contains('reveal-item')) {
                el.classList.add('reveal-item');
            }
            el.classList.remove('reveal-up');
            el.classList.remove('reveal-down');
        });
    };

    this.revealUpOut = entry => {
        const isScrollingDown =
            entry.boundingClientRect.y > entry.rootBounds.top;
        const htmlElements = [].slice.call(entry.target.children);

        if (isScrollingDown) {
            // Disparition bas vers haut
            htmlElements.forEach(el => {
                if (!el.classList.contains('reveal-item')) {
                    el.classList.add('reveal-item');
                }
                el.classList.remove('reveal-up');
                el.classList.add('reveal-down');
            });
        } else {
            // Disparition haut vers bas
            htmlElements.forEach(el => {
                if (!el.classList.contains('reveal-item')) {
                    el.classList.add('reveal-item');
                }
                el.classList.remove('reveal-down');
                el.classList.add('reveal-up');
            });
        }
    };

    this.revealContentImgIn = entry => {
        const htmlElement = entry.target;
        htmlElement.classList.add('reveal-item');
    };

    this.revealContentImgOut = entry => {
        const htmlElement = entry.target;
        htmlElement.classList.remove('reveal-item');
    };
};

module.exports = new io();
