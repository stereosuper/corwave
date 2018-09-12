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
        $(filteredByHash.reverse()).each((index, el) => {
            const jqueryEl = $(el);
            const elTop = jqueryEl.offset().top;
            const elBottom = elTop + jqueryEl.height();
            const winTop = scroll.scrollTop;
            const winBottom = winTop + win.h;

            if (
                elBottom - OFFSET_ANCHOR > winTop &&
                elTop - OFFSET_ANCHOR < winBottom
            ) {
                const anchorIndex = jqueryEl.index('.js-custom-anchor');
                liAnchorSidebar.removeClass('active');
                liAnchorSidebar.eq(anchorIndex).addClass('active');
            }
        });
    };

    colle();
    activateAnchors();
    win.addResizeFunction(resize);
    scroll.addScrollFunction(activateAnchors);
};
