var Rollover = function() {
  this.imgs = document.getElementsByTagName('img');
  this.preimage = new Array;
  this.init();
  this.preload();
}

Rollover.prototype = {
  init: function() {
    var len = this.imgs.length;
    var is_ie = /msie/.test(window.navigator.userAgent.toLowerCase());
    for (var i=0; i<len; i++) {
      var obj = this.imgs[i];
      overmode = (is_ie) ? obj.getAttribute('overmode') : null;
      oversrc = obj.getAttribute('oversrc');
      select = obj.getAttribute('select');
      if (oversrc) obj.setAttribute('orgsrc', obj.src);
      if (oversrc == obj.src) continue;

      switch (overmode) {
        case 'gray2color' :
          obj.style.filter = 'gray';
          obj.onmouseover = function() { this.style.filter = ''; }
          obj.onmouseout = function() { this.style.filter = 'gray'; }
          break;
        case 'color2gray' :
          obj.onmouseover = function() { this.style.filter = 'gray'; }
          obj.onmouseout = function() { this.style.filter = ''; }
          break;
        case 'overlay' :
          if (oversrc) {
            obj.style.filter = "blendTrans(duration=0.3)";
            obj.onmouseover = function() { this.filters.blendTrans.Stop(); this.filters.blendTrans.Apply(); this.src = this.getAttribute('oversrc'); this.filters.blendTrans.Play(); }
            obj.onmouseout = function() { this.filters.blendTrans.Stop(); this.filters.blendTrans.Apply(); this.src = this.getAttribute('orgsrc'); this.filters.blendTrans.Play(); }
            this.preimage[this.preimage.length] = oversrc;
          }
          break;
        default :
          if (oversrc) {
            if (select != null) {
              obj.src = oversrc;
            }
            else {
              obj.onmouseover = function() { this.src = this.getAttribute('oversrc'); }
              obj.onmouseout = function() { this.src = this.getAttribute('orgsrc'); }
            }
            this.preimage[this.preimage.length] = oversrc;
          }
      }
    }
  },

  preload: function() {
    var len = this.preimage.length;
    var preload_image = new Image;
    for (var i=0; i<len; i++) {
      preload_image.src = this.preimage[i];
    }
  }
}

if (window.addEventListener) window.addEventListener("load", function() { new Rollover; }, false);
else window.attachEvent("onload", function() { new Rollover; });