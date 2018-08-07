function Snif() {
    this.state = null;
    const uA = navigator.userAgent.toLowerCase();
    const snifTests = { isIOS: /iphone|ipad|ipod/i.test(uA) };

    this.getSnifTests = () => snifTests;
}

Snif.prototype.isIOS = function isIOS() {
    return this.getSnifTests().isIOS;
};

module.exports = new Snif();
