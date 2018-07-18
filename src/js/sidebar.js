const $ = require('jquery-slim');

const win = require('./Window');

module.exports = function sidebar(sidebarElement) {
    if (!sidebarElement.length) return;

    const headerPage = $('.header-page');
    const headerPageHeight = headerPage.innerHeight();
    const anchorsList = sidebarElement.find('.anchors-list');

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
    win.addResizeFunction(resize);
};
