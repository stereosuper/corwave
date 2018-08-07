const $ = require('jquery-slim');

module.exports = function contactFormFile(contactFormFileInput) {
    if (!contactFormFileInput.length) return;
    const label = contactFormFileInput.siblings('label');

    contactFormFileInput.on('change', function checkInput() {
        if ($(this).val()) {
            label.find('.cta').addClass('has-media');
        } else {
            label.find('.cta').removeClass('has-media');
        }
    });
};
