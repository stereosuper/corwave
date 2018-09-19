const $ = require('jquery-slim');
require('gsap');

const scroll = require('./Scroll');
const win = require('./Window');
const Sprite = require('./Sprite.js');

const HomeVideo = function HomeVideo(wrapper) {

    this.dom = wrapper;
    this.imgRatio = 640/720;
    this.vidRatio = 1280/720;

    this.domVideos = this.dom.find('video').sort(function(a, b){
        return $(a).data('step') > $(b).data('step');
    });

    this.firstPlay = false;

    this.middleStarted = false;
    this.ended = false;

    this.videoIntro = null;
    this.videoMiddle = null;
    this.firstSpriteLoop = null;
    this.secondSpriteLoop = null;

    this.videoIntroEnded = false;
    this.videoMiddleEnded = false;

    this.nbSpriteReady = 0;

    const self = this;

    scroll.addScrollFunction(function(){
        if(scroll.scrollTop + 180 > self.dom.offset().top && !self.middleStarted){
            self.middleStarted = true;
            self.firstSpriteLoop.stopAtEnd();
        }
        if(scroll.scrollTop <= 60 && self.ended){
            self.reset();
        }
    }, true)

    this.firstSpriteLoop = this.createPartSprite($('#spritesFirst'), 4, 6);

    this.secondSpriteLoop = this.createPartSprite($('#spritesSecond'), 9, 11, 3);

    
    
    this.videoMiddle = this.createPartVideo(this.domVideos[1], {
        endedCallback: function(v){
            if(self.videoIntroEnded) return;
            self.videoIntroEnded = true;
            // self.secondSpriteLoop.image.removeClass('hidden');
            self.secondSpriteLoop.image.css('opacity', 1);
            self.secondSpriteLoop.image.css('z-index', 1);
            v.classList.add('hidden');
            self.secondSpriteLoop.play();
            v.currentTime = 0;
            self.ended = true;
        },
    });
    
    this.setSize(this);
    win.addResizeFunction(function(){
        self.setSize(self);
    });
    
};

HomeVideo.prototype.createPartSprite = function(dom, cols, rows, numberEmpty = 0) {
    const spUrl = dom.attr('data-src');    
    const spImage = new Image();
    spImage.src = spUrl;
    const self = this;

    function noDecodeApi(){
        dom.css('background-image', `url(${spImage.src})`);
        self.nbSpriteReady++;
        if(self.nbSpriteReady === 2) self.start();
    }

    if(Image.prototype.decode){
        spImage.decode().then(function() {
            dom.css('background-image', `url(${spImage.src})`);
            self.nbSpriteReady++;
            if(self.nbSpriteReady === 2) self.start()
        }).catch(function(error) {
            noDecodeApi();
        });
    }else{
        noDecodeApi();
    }

    
    return new Sprite(dom, cols, rows, 0.04, this, {loop: true, numberEmpty: numberEmpty})
}

HomeVideo.prototype.createPartVideo = function createPartVideo(v, {endedCallback = false, autoplay = false}){

    const self = this;
    const now = new Date().getMilliseconds();
    const source = $(v).find('source');
    source.attr('src', source.attr('data-src') + '?t=' + now).removeAttr('data-src');

    if(endedCallback){
        v.addEventListener('ended', function(){
            console.log('again');
            endedCallback(v)
        }, false);
    }

    if(autoplay){
        v.addEventListener('canplaythrough', function(){
            if(!self.firstPlay) v.play();
        }, false);
    }
    v.load();
    return v;
}

HomeVideo.prototype.startVideoMiddle = function startVideoMiddle(){
    this.videoMiddle.classList.remove('hidden');
    // this.firstSpriteLoop.image.addClass('hidden');
    this.firstSpriteLoop.image.css('opacity', 0);
    this.firstSpriteLoop.image.css('z-index', 0);
    this.videoMiddle.play();
}

HomeVideo.prototype.reset = function reset(){
    const self = this;
    
    TweenLite.to(this.secondSpriteLoop.image, 0.3, {opacity: 0, onComplete: function(){
        self.secondSpriteLoop.image.css('z-index', 0)
        // self.secondSpriteLoop.image.addClass('hidden')
        self.videoIntro.play();
        self.firstSpriteLoop.shouldStop = false;
        self.firstPlay = false;
        self.middleStarted = false;
        self.ended = false;
        self.videoIntroEnded = false;
        self.videoMiddleEnded = false;
        self.secondSpriteLoop.reInit();
    }})
}

HomeVideo.prototype.start = function start(){
    const self = this;
    this.videoIntro = this.createPartVideo(this.domVideos[0], {
        endedCallback: function(v){
            if(self.videoMiddleEnded) return;
            self.videoMiddleEnded = true;
            self.firstPlay = true;
            self.firstSpriteLoop.image.css('opacity', 1);
            self.firstSpriteLoop.image.css('z-index', 1);
            v.currentTime = 0;
            v.pause();
            self.firstSpriteLoop.play();
        },
        autoplay : true
    });
}

HomeVideo.prototype.setSize = function setSize(self){

    const newHeight = Math.round(win.h / 2);
    const newVideoWidth = Math.round(newHeight * self.vidRatio);
    const newSpriteWidth = Math.round(newHeight * self.imgRatio);

    self.firstSpriteLoop.image.css('height', newHeight);
    self.firstSpriteLoop.image.css('width', newSpriteWidth );
    self.secondSpriteLoop.image.css('height', newHeight);
    self.secondSpriteLoop.image.css('width', newSpriteWidth );

    $(self.videoIntro).css('height', newHeight);
    $(self.videoIntro).css('width', newVideoWidth);
    $(self.videoMiddle).css('height', newHeight);
    $(self.videoMiddle).css('width', newVideoWidth);
}

module.exports = HomeVideo;
