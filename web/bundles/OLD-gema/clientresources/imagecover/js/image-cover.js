;(function($) {

  /*! modernizr 3.3.1 (Custom Build) | MIT *
  * http://modernizr.com/download/?-objectfit-setclasses-cssclassprefix:supports- !*/
  !function(e,n,t){function r(e,n){return typeof e===n}function o(){var e,n,t,o,i,s,a;for(var f in y)if(y.hasOwnProperty(f)){if(e=[],n=y[f],n.name&&(e.push(n.name.toLowerCase()),n.options&&n.options.aliases&&n.options.aliases.length))for(t=0;t<n.options.aliases.length;t++)e.push(n.options.aliases[t].toLowerCase());for(o=r(n.fn,"function")?n.fn():n.fn,i=0;i<e.length;i++)s=e[i],a=s.split("."),1===a.length?Modernizr[a[0]]=o:(!Modernizr[a[0]]||Modernizr[a[0]]instanceof Boolean||(Modernizr[a[0]]=new Boolean(Modernizr[a[0]])),Modernizr[a[0]][a[1]]=o),C.push((o?"":"no-")+a.join("-"))}}function i(e){var n=_.className,t=Modernizr._config.classPrefix||"";if(w&&(n=n.baseVal),Modernizr._config.enableJSClass){var r=new RegExp("(^|\\s)"+t+"no-js(\\s|$)");n=n.replace(r,"$1"+t+"js$2")}Modernizr._config.enableClasses&&(n+=" "+t+e.join(" "+t),w?_.className.baseVal=n:_.className=n)}function s(e,n){return!!~(""+e).indexOf(n)}function a(){return"function"!=typeof n.createElement?n.createElement(arguments[0]):w?n.createElementNS.call(n,"http://www.w3.org/2000/svg",arguments[0]):n.createElement.apply(n,arguments)}function f(){var e=n.body;return e||(e=a(w?"svg":"body"),e.fake=!0),e}function l(e,t,r,o){var i,s,l,u,p="modernizr",d=a("div"),c=f();if(parseInt(r,10))for(;r--;)l=a("div"),l.id=o?o[r]:p+(r+1),d.appendChild(l);return i=a("style"),i.type="text/css",i.id="s"+p,(c.fake?c:d).appendChild(i),c.appendChild(d),i.styleSheet?i.styleSheet.cssText=e:i.appendChild(n.createTextNode(e)),d.id=p,c.fake&&(c.style.background="",c.style.overflow="hidden",u=_.style.overflow,_.style.overflow="hidden",_.appendChild(c)),s=t(d,e),c.fake?(c.parentNode.removeChild(c),_.style.overflow=u,_.offsetHeight):d.parentNode.removeChild(d),!!s}function u(e){return e.replace(/([A-Z])/g,function(e,n){return"-"+n.toLowerCase()}).replace(/^ms-/,"-ms-")}function p(n,r){var o=n.length;if("CSS"in e&&"supports"in e.CSS){for(;o--;)if(e.CSS.supports(u(n[o]),r))return!0;return!1}if("CSSSupportsRule"in e){for(var i=[];o--;)i.push("("+u(n[o])+":"+r+")");return i=i.join(" or "),l("@supports ("+i+") { #modernizr { position: absolute; } }",function(e){return"absolute"==getComputedStyle(e,null).position})}return t}function d(e){return e.replace(/([a-z])-([a-z])/g,function(e,n,t){return n+t.toUpperCase()}).replace(/^-/,"")}function c(e,n,o,i){function f(){u&&(delete E.style,delete E.modElem)}if(i=r(i,"undefined")?!1:i,!r(o,"undefined")){var l=p(e,o);if(!r(l,"undefined"))return l}for(var u,c,m,v,h,y=["modernizr","tspan"];!E.style;)u=!0,E.modElem=a(y.shift()),E.style=E.modElem.style;for(m=e.length,c=0;m>c;c++)if(v=e[c],h=E.style[v],s(v,"-")&&(v=d(v)),E.style[v]!==t){if(i||r(o,"undefined"))return f(),"pfx"==n?v:!0;try{E.style[v]=o}catch(g){}if(E.style[v]!=h)return f(),"pfx"==n?v:!0}return f(),!1}function m(e,n){return function(){return e.apply(n,arguments)}}function v(e,n,t){var o;for(var i in e)if(e[i]in n)return t===!1?e[i]:(o=n[e[i]],r(o,"function")?m(o,t||n):o);return!1}function h(e,n,t,o,i){var s=e.charAt(0).toUpperCase()+e.slice(1),a=(e+" "+S.join(s+" ")+s).split(" ");return r(n,"string")||r(n,"undefined")?c(a,n,o,i):(a=(e+" "+j.join(s+" ")+s).split(" "),v(a,n,t))}var y=[],g={_version:"3.3.1",_config:{classPrefix:"supports-",enableClasses:!0,enableJSClass:!0,usePrefixes:!0},_q:[],on:function(e,n){var t=this;setTimeout(function(){n(t[e])},0)},addTest:function(e,n,t){y.push({name:e,fn:n,options:t})},addAsyncTest:function(e){y.push({name:null,fn:e})}},Modernizr=function(){};Modernizr.prototype=g,Modernizr=new Modernizr;var C=[],_=n.documentElement,w="svg"===_.nodeName.toLowerCase(),x="Moz O ms Webkit",S=g._config.usePrefixes?x.split(" "):[];g._cssomPrefixes=S;var b={elem:a("modernizr")};Modernizr._q.push(function(){delete b.elem});var E={style:b.elem.style};Modernizr._q.unshift(function(){delete E.style});var j=g._config.usePrefixes?x.toLowerCase().split(" "):[];g._domPrefixes=j,g.testAllProps=h;var z=function(n){var r,o=prefixes.length,i=e.CSSRule;if("undefined"==typeof i)return t;if(!n)return!1;if(n=n.replace(/^@/,""),r=n.replace(/-/g,"_").toUpperCase()+"_RULE",r in i)return"@"+n;for(var s=0;o>s;s++){var a=prefixes[s],f=a.toUpperCase()+"_"+r;if(f in i)return"@-"+a.toLowerCase()+"-"+n}return!1};g.atRule=z;var N=g.prefixed=function(e,n,t){return 0===e.indexOf("@")?z(e):(-1!=e.indexOf("-")&&(e=d(e)),n?h(e,n,t):h(e,"pfx"))};Modernizr.addTest("objectfit",!!N("objectFit"),{aliases:["object-fit"]}),o(),i(C),delete g.addTest,delete g.addAsyncTest;for(var P=0;P<Modernizr._q.length;P++)Modernizr._q[P]();e.Modernizr=Modernizr}(window,document);

  $.fn.cover = function(options) {

    var defaults = {
      target: 'img',
      delay: 100,
      scale: 'fill',
      align: {
        h: 'center',
        v: 'center'
      },
      breakpoints: []
    };

    var o = $.extend(defaults, options);

    // Instance of Cover
    var c = this;

    // Store original options
    c.o = o;
    c.oo = $.extend({}, c.o);
    c.container = $(this);

    // Check Modernizr
    if (typeof Modernizr == 'object') {
      if(Modernizr.objectfit) {
        c.objectfit = true;
      }
    }

    /*=========================
    Breakpoints
    =========================*/

    c.curBreakpoint = undefined;

    // Get the Active Breakpoint
    c.getActiveBreakpoint = function() {
      if (!c.o.breakpoints) return false; // If there are no breakpoints, leave the function
      var breakpoint = null;
      var breakpoints = [];
      $.each(c.o.breakpoints, function(k, v) {
        breakpoints.push(k);
      });

      for (var i = 0; i < breakpoints.length; i++) { // Loop through the breakpoints array and check the current viewport against breakpoint criteria
        bp = breakpoints[i];
        if(bp >= window.innerWidth && !breakpoint) {
          breakpoint = bp;
        }
      }
      return breakpoint || 'max';
    };

    // Set the breakpoint
    c.setBreakpoint = function() {
      var breakpoint = c.getActiveBreakpoint();
      if(breakpoint && c.curBreakpoint !== breakpoint) { // If breakpoint exists and the current breakpoint is not equal to breakpoint then
        // var breakpointOptions = (breakpoint in c.o.breakpoints) ? c.o.breakpoints[breakpoint] : breakpointOptions = c.oo; // If breakpoint is in the breakpoint object, use those options, otherwise use the default options
        if (breakpoint in c.o.breakpoints) {
          breakpointOptions = c.o.breakpoints[breakpoint];
        }
        else {
          breakpointOptions = c.oo;
        }
        for (var option in breakpointOptions) {
          c.o[option] = breakpointOptions[option];
        }
        c.curBreakpoint = breakpoint;
      }
    };

    /*=========================
    Methods
    =========================*/

    c.methods = {

      // Various different scale methods
      scale: {
        fill: function(r) {
          r.image.removeAttr('style');
          if (c.objectfit) {
            r.image.css({
              'height': '100%',
              'object-fit': 'cover',
              'object-position': c.o.align.h + ' ' + c.o.align.v,
              'width': '100%'
            });
          }
          else {
            if (r.container.ratio < r.image.ratio) {
              r.image.css({
                height: r.container.height,
                position: 'absolute',
                // position: 'relative',
                width: 'auto'
              });
              var posLeft = '-' + ((r.image.width() - r.container.width) / 2) + 'px';
              if (c.o.align.h !== 'center') {
                posLeft = (c.o.align.h == 'right') ? 'auto': '0';
              };
              r.image.css({
                bottom: 0,
                left: posLeft,
                right: 0,
                top: 0
              });
            }
            else {
              r.image.css({ // width to 100% and top margin offset
                height: 'auto',
                position: 'absolute',
                width: r.container.width
              });
              var posTop = '-' + ((r.image.height() - r.container.height) / 2) + 'px';
              if (c.o.align.v !== 'center') {
                posTop = (c.o.align.v == 'bottom') ? 'auto': '0';
              };
              r.image.css({ // width to 100% and top margin offset
                bottom: 0,
                left: 0,
                right: 0,
                top: posTop
              });
            }
          }
        },

        fillHeight: function(r) {
          r.image.removeAttr('style');
          if (c.objectfit) {
            r.image.css({
              'height': '100%',
              'object-fit': 'contain',
              'object-position': c.o.align.h + ' ' + c.o.align.v,
            });
          }
          else {
            r.image.css({ // height to 100% and left margin offset
              height: r.container.height,
              position: 'relative',
              width: 'auto'
            });
            var posLeft = '-' + ((r.image.width() - r.container.width) / 2) + 'px';
            if (c.o.align.h !== 'center') {
              posLeft = (c.o.align.h == 'right') ? '-' + (r.image.width() - r.container.width) + 'px': '0';
            };
            r.image.css({
              bottom: 0,
              left: posLeft,
              right: 0,
              top: 0
            });
          }
        },

        fillWidth: function(r) {
          r.image.removeAttr('style');
          if (c.objectfit) {
            r.image.css({
              'object-fit': 'contain',
              'object-position': c.o.align.h + ' ' + c.o.align.v,
              'width': '100%'
            });
          }
          else {
            r.image.css({ // width to 100% and top margin offset
              height: 'auto',
              position: 'relative',
              width: r.container.width
            });
          }
        },

        stretch: function(r) {
          r.image.removeAttr('style');
          r.image.css({
            'height': '100%',
            position: 'relative',
            'width': '100%'
          });
        },
      }
    };

    /*=========================
    Position
    =========================*/

    function position(container) {
      var image = container.find(c.o.target);

      // Establish ratios and dimensions and pass to the relevant scale method
      var r = {};

      image.css({
        position: 'absolute',
      });

      r.container = {
        height: container.height(),
        ratio: (container.width() / container.height()),
        width: container.width(), 
      };

      r.image = image;
      r.image.ratio = (image.width() / image.height());

      // If the method exists, call it, otherwise go to fill method
      (c.o.scale in c.methods.scale) ? c.methods.scale[c.o.scale](r) : c.methods.scale.fill(r);
    }

    /*=========================
    Resize Refresh
    =========================*/

    $(window).on('resize', function() {
      var findPosition = function() {
        if (c.o.breakpoints) { // If breakpoints exist, requery the current breakpoint on resize
          c.setBreakpoint();
        }
        c.container.each(function() {
          position($(this));
        });
      };
      if (c.objectfit) {
        findPosition();
      }
      else {
        clearTimeout(c.timer);
        c.timer = setTimeout(function() {
          findPosition();
        }, c.o.delay);
      }
    });

    /*=========================
    Inital Run
    =========================*/

    //  Set breakpoint on load
    if (c.o.breakpoints) {
      c.setBreakpoint();
    }

    return c.container.each(function() {
      var container = $(this);
      var image = container.find(c.o.target);

      image.load(function() { // when the image is loaded, run the position function
        position(container);
      });

      if (image.complete || image.complete === undefined) { // if the image is cached, run the position function
        position(container);
      }
    });
  };

}(jQuery, window, document));