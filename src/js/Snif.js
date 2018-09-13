function Snif() {
    const uA = navigator.userAgent.toLowerCase();
    const snifTests = {
        isIOS: /iphone|ipad|ipod/i.test(uA),
        isSafari:
            (!!navigator.userAgent.match(/safari/i) &&
                !navigator.userAgent.match(/chrome/i) &&
                typeof document.body.style.webkitFilter !== 'undefined' &&
                !window.chrome) ||
            /a/.__proto__ == '//',
        isFF: 'MozAppearance' in document.documentElement.style,
        isMS:
            '-ms-scroll-limit' in document.documentElement.style &&
            '-ms-ime-align' in document.documentElement.style,
        mixBlendModeSupport:
            'CSS' in window &&
            'supports' in window.CSS &&
            window.CSS.supports('mix-blend-mode', 'multiply'),
        isMobileAndroid: /android.*mobile/.test(uA),
        safari: uA.match(/version\/[\d\.]+.*safari/),
    };
    snifTests.isAndroid =
        snifTests.isMobileAndroid ||
        (!snifTests.isMobileAndroid && /android/i.test(uA));

    snifTests.isSafari = !!snifTests.safari && !snifTests.isAndroid;

    this.getSnifTests = () => snifTests;
}

Snif.prototype.isIOS = function isIOS() {
    return this.getSnifTests().isIOS;
};

Snif.prototype.isSafari = function isSafari() {
    return this.getSnifTests().isSafari;
};

Snif.prototype.isFF = function isFF() {
    return this.getSnifTests().isFF;
};

Snif.prototype.isMS = function isMS() {
    return this.getSnifTests().isMS;
};

Snif.prototype.mixBlendModeSupport = function mixBlendModeSupport() {
    return this.getSnifTests().mixBlendModeSupport;
};

Snif.prototype.isIe11 = function isIe11() {
    
    return document.body.style.msTouchAction !== undefined;
}


module.exports = new Snif();
