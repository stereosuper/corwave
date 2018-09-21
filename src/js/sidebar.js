const $ = require('jquery-slim');
const collant = require('collant');

const scroll = require('./Scroll');
const win = require('./Window');

module.exports = function sidebar(sidebarElement) {
    if (!sidebarElement.length) return;

    const OFFSET_ANCHOR = 150;

    const headerPage = $('.header-page, .product-header');
    const headerPageHeight = headerPage.innerHeight();
    const anchorsList = sidebarElement.find('.anchors-list');

    const contentAnchors = $('.js-custom-anchor');
    const liAnchorSidebar = sidebarElement.find('.js-anchor-link');

    let windowYSave = win.h;

    const filteredByHash = (() => {
        const filtered = contentAnchors.get().filter(anchor => {
            const { id } = anchor;
            let match = false;
            liAnchorSidebar.each((index, el) => {
                const sidebarAnchorHref = $(el)
                    .find('a')
                    .attr('href');
                const hash = sidebarAnchorHref.substring(
                    sidebarAnchorHref.indexOf('#')
                );

                if (`#${id.replace('-will-scroll', '')}` === hash) {
                    match = true;
                }
            });
            return match;
        });
        return filtered;
    })();

    const sidebarMaxHeight = () => {
        if (anchorsList.innerHeight() > win.h - headerPageHeight) {
            sidebarElement.addClass('bigger-than-screen');
        }
    };

    sidebarMaxHeight();

    const resize = () => {
        if (windowYSave !== win.h) {
            if (anchorsList.innerHeight() > win.h - headerPageHeight) {
                sidebarElement.addClass('bigger-than-screen');
            } else {
                sidebarElement.removeClass('bigger-than-screen');
            }

            windowYSave = win.h;
        }
    };

    const colle = () => {
        if (headerPage.length && $('.js-has-sidebar').length) {
            collant(sidebarElement[0], 150, {
                mininumWidth: 580,
            });
        }
    };

    const activateAnchors = () => {
        const percentage = [];
        $(filteredByHash).each((index, el) => {
            const jqueryEl = $(el);
            const elHeight = jqueryEl.height();
            const elTop = jqueryEl.offset().top;
            const elBottom = elTop + elHeight;
            const winTop = scroll.scrollTop;
            const winBottom = winTop + win.h;

            let bottomPercent =
                ((elBottom - winTop + OFFSET_ANCHOR) / elHeight) * 100;

            let topPercent =
                ((winBottom - elTop - OFFSET_ANCHOR) / elHeight) * 100;

            if (bottomPercent < 0 || topPercent < 0) {
                bottomPercent = 0;
                topPercent = 0;
            }

            if (bottomPercent > 100) {
                bottomPercent = 100;
            }
            if (topPercent > 100) {
                topPercent = 100;
            }

            percentage[index] = (bottomPercent + topPercent) / 2;
        });

        const index = percentage.indexOf(Math.max(...percentage));
        liAnchorSidebar.removeClass('active');
        liAnchorSidebar.eq(index).addClass('active');
    };

    colle();
    activateAnchors();
    win.addResizeFunction(resize);
    scroll.addScrollFunction(activateAnchors);
};
