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
                rootMargin: '-100px 0px',
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

    const isInViewport = (img, parent, out = false) => (el, index, array) => {
        const offset = out ? 0 : 0;
        const elementLeft = img
            ? $(el)
                  .find('img')
                  .offset().left
            : $(el).offset().left;
        const elementRight = img
            ? elementLeft +
              $(el)
                  .find('img')
                  .outerWidth()
            : elementLeft + $(el).outerWidth();

        const elementTop = img
            ? $(el)
                  .find('img')
                  .offset().top
            : $(el).offset().top;
        const elementBottom = img
            ? elementTop +
              $(el)
                  .find('img')
                  .outerHeight()
            : elementTop + $(el).outerHeight();

        const parentTop = $(parent).offset().top;
        const parentBottom = parentTop + $(parent).outerHeight();

        return (
            elementRight > 0 &&
            elementBottom + offset > parentTop &&
            elementTop + offset < parentBottom
        );
    };

    const revealUpIn = el => {};

    const revealUpOut = el => {};
};

module.exports = new io();
