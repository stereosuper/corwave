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
                rootMargin: '-200px 0px -100px 0px',
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
        const isScrollingDown =
            entry.boundingClientRect.y > entry.rootBounds.top;
        const htmlElement = entry.target;

        htmlElement.classList.remove('reveal-up');
        htmlElement.classList.remove('reveal-down');
        if (isScrollingDown) {
            // Apparition bas vers haut
        } else {
            // Apparition haut vers bas
        }
    };

    this.revealUpOut = entry => {
        const isScrollingDown =
            entry.boundingClientRect.y > entry.rootBounds.top;
        const htmlElement = entry.target;

        if (isScrollingDown) {
            // Disparition bas vers haut
            htmlElement.classList.remove('reveal-up');
            htmlElement.classList.add('reveal-down');
        } else {
            // Disparition haut vers bas
            htmlElement.classList.remove('reveal-down');
            htmlElement.classList.add('reveal-up');
        }
    };
};

module.exports = new io();
