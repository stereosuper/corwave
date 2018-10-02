const $ = require('jquery-slim');
require('gsap');

const scroll = require('./Scroll');
const win = require('./Window');
const Sprite = require('./Sprite.js');
const Snif = require('./Snif.js');

const HomeVideo = function HomeVideo(wrapper) {

    this.dom = wrapper;
    this.imgRatio = 640/720;
    this.vidRatio = 1280/720;

    this.domVideos = this.dom.find('video').sort(function(a, b){
        return $(a).data('step') > $(b).data('step');
    });

    this.containerVideo = this.dom.find('#videoHomeContainer');
    this.loader = this.dom.find('#videoLoader');

    this.ready = 0;

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
        if(scroll.scrollTop + 350 > self.dom.offset().top && !self.middleStarted){
            self.middleStarted = true;
            self.firstSpriteLoop.stopAtEnd();
        }
        if(scroll.scrollTop <= 0 && self.ended){
            self.reset();
        }
    }, true)

    this.firstSpriteLoop = this.createPartSprite($('#spritesFirst'), 4, 6);

    this.secondSpriteLoop = this.createPartSprite($('#spritesSecond'), 9, 11, 3);
    
    this.videoMiddle = this.createPartVideo(this.domVideos[1], {
        startCallback: function(){
            self.ready++;
            if(self.ready === 3) self.start();
        },
        endedCallback: function(v){
            if(self.videoIntroEnded) return;
            self.videoIntroEnded = true;
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
    const spUrl = Snif.isMobile() ? dom.attr('data-src').replace('.png', '_bd.png') : dom.attr('data-src');    
    const spImage = new Image();
    spImage.src = spUrl;
    const self = this;

    function noDecodeApi(){
        dom.css('background-image', `url(${spImage.src})`);
        self.ready++;        
        if(self.ready === 3) self.start();
    }

    if(Image.prototype.decode){
        spImage.decode().then(function() {
            dom.css('background-image', `url(${spImage.src})`);
            self.ready++;
            if(self.ready === 3) self.start()
        }).catch(function(error) {
            noDecodeApi();
        });
    }else{
        noDecodeApi();
    }

    
    return new Sprite(dom, cols, rows, 0.04, this, {loop: true, numberEmpty: numberEmpty})
}

HomeVideo.prototype.createPartVideo = function createPartVideo(v, {startCallback = false, endedCallback = false, autoplay = false}){

    const self = this;
    const now = new Date().getMilliseconds();
    const source = $(v).find('source');
    const vUrl = Snif.isMobile() ? source.attr('data-src').replace('.mp4', '-bd.mp4') : source.attr('data-src');
    source.attr('src', vUrl + '?t=' + now).removeAttr('data-src');

    if(endedCallback){
        v.addEventListener('ended', function(){
            endedCallback(v)
        }, false);
    }

    v.addEventListener('canplaythrough', function(){
        if(!self.firstPlay && autoplay) v.play();
        if(startCallback){
            startCallback(v);
        }
    }, false);

    v.load();
    return v;
}

HomeVideo.prototype.startVideoMiddle = function startVideoMiddle(){
    this.videoMiddle.classList.remove('hidden');
    this.firstSpriteLoop.image.css('opacity', 0);
    this.firstSpriteLoop.image.css('z-index', 0);
    this.videoMiddle.play();
}

HomeVideo.prototype.reset = function reset(){
    const self = this;
    
    TweenLite.to(this.secondSpriteLoop.image, 0.3, {opacity: 0, onComplete: function(){
        self.secondSpriteLoop.image.css('z-index', 0);
        self.videoIntro.play();
        self.firstSpriteLoop.shouldStop = false;
        self.firstPlay = false;
        self.middleStarted = false;
        self.ended = false;
        self.videoIntroEnded = false;
        self.videoMiddleEnded = false;
        self.ready = 0;
        self.secondSpriteLoop.reInit();
        self.secondSpriteLoop.image.css('opacity', 1);

    }})
}

HomeVideo.prototype.start = function start(){
    const self = this;
    this.videoIntro = this.createPartVideo(this.domVideos[0], {
        startCallback: function(v){
            self.containerVideo.removeClass('hide');
            self.loader.addClass('hide');
        },
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
