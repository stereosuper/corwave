const $ = require('jquery-slim');
const io = require('./io');

module.exports = function findContentImages(contentPage) {
    if (!contentPage.length) return;

    const contentImages = contentPage.find('img');
    contentImages.attr('data-io', 'revealContentImg');

    io.init();
};
