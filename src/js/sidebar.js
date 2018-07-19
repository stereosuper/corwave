const $ = require('jquery-slim');

const scroll = require('./Scroll');
const win = require('./Window');

module.exports = function sidebar(sidebarElement) {
    if (!sidebarElement.length) return;

    const headerPage = $('.header-page');
    const headerPageHeight = headerPage.innerHeight();
    const anchorsList = sidebarElement.find('.anchors-list');

    const contentAnchors = $('.js-custom-anchor');

    let windowYSave = win.h;

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

    const activateAnchors = () => {
        const liAnchorSidebar = sidebarElement.find('.js-anchor-link');

        contentAnchors.each((index, el) => {
            const elTop = $(el).offset().top;
            const elBottom = elTop + $(el).height();
            const winTop = scroll.scrollTop;
            const winBottom = winTop + win.h;

            if (elBottom > winTop && elTop < winBottom) {
                liAnchorSidebar.removeClass('active');
                const anchorIndex = $(el).index('.js-custom-anchor');
                liAnchorSidebar.eq(anchorIndex).addClass('active');
            }
        });
    };

    win.addResizeFunction(resize);
    scroll.addScrollFunction(activateAnchors);
};
