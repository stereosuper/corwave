const $ = require('jquery-slim');
require('gsap/TweenLite');

module.exports = function initVideo() {
    const wrapperVideos = $('.js-video'),
        players = [];

    if (!wrapperVideos && !wrapperVideos.length) return;

    const tag = document.createElement('script'),
        firstScriptTag = document.getElementsByTagName('script')[0];

    function onPlayerReady(wrapperVideoParent) {
        wrapperVideoParent
            .on('click', function() {
                $(this)
                    .find('.cover-video')
                    .removeClass('on');
                players[$(this).index('.inner-video')].playVideo();
            })
            .find('.cover-video')
            .addClass('on');
    }

    global.onYouTubeIframeAPIReady = function() {
        wrapperVideos.each(function(i) {
            players[i] = new YT.Player(
                $(this)
                    .find('.iframe')
                    .get(0),
                {
                    videoId: $(this).data('id'),
                    playerVars: {
                        modestbranding: 1,
                        color: 'white',
                        rel: 0,
                        showinfo: 0,
                    },
                    events: {
                        onReady: onPlayerReady($(this)),
                    },
                }
            );
        });
    };

    tag.src = 'https://www.youtube.com/iframe_api';
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
};
