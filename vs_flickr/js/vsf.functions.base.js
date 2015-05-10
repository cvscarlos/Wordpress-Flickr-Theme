/**
* Funções base
*/
"function"!==typeof String.prototype.trim&&(String.prototype.trim=function(){return this.replace(/^\s+|\s+$/g,"")});
"function"!==typeof String.prototype.replaceSpecialChars&&(String.prototype.replaceSpecialChars=function(){var b={"\u00e7":"c","\u00e6":"ae","\u0153":"oe","\u00e1":"a","\u00e9":"e","\u00ed":"i","\u00f3":"o","\u00fa":"u","\u00e0":"a","\u00e8":"e","\u00ec":"i","\u00f2":"o","\u00f9":"u","\u00e4":"a","\u00eb":"e","\u00ef":"i","\u00f6":"o","\u00fc":"u","\u00ff":"y","\u00e2":"a","\u00ea":"e","\u00ee":"i","\u00f4":"o","\u00fb":"u","\u00e5":"a","\u00e3":"a","\u00f8":"o","\u00f5":"o",u:"u","\u00c1":"A","\u00c9":"E", "\u00cd":"I","\u00d3":"O","\u00da":"U","\u00ca":"E","\u00d4":"O","\u00dc":"U","\u00c3":"A","\u00d5":"O","\u00c0":"A","\u00c7":"C"};return this.replace(/[\u00e0-\u00fa]/ig,function(a){return"undefined"!=typeof b[a]?b[a]:a})});

(function($) {
	// Verificando se o dispositivo suporta touch
	var isTouchDevice = false;
	try {
		isTouchDevice = 'ontouchstart' in window || navigator.MaxTouchPoints > 0 || navigator.msMaxTouchPoints > 0;
	}
	catch (e) {(typeof console !== "undefined" && typeof console.error === "function" && console.error("Problemas :( . Detalhes: " + e.message)); }

	try {
		var Common = {
			run: function() {
			},
			init: function() {
				Common.setTouchClass();
				Common.swipeMenu();
				Common.socialMenuClass();
				Common.mobileMenu();
			},
			ajaxStop: function() {},
			windowOnload: function() {},
			socialMenuClass: function() {
				$(".vsf-social-menu a").each(function() {
					var $t = $(this);
					$t.addClass(($t.attr("href") || "").replace(/[^0-9a-z\_\-]/ig, "-").replace(/\-+/ig, "-"));
					$t.attr({
						"title": $t.text(),
						"target": "_blank"
					});
				});
			},
			setTouchClass: function() {
				if(isTouchDevice)
					$(document.body).addClass('vsf-touch-device');
			},
			mobileMenu: function() {
				$(".vsf-m-overlay, .vsf-menu-button button").click(function() {
					$(document.body).toggleClass('vsf-m-menu-visible');
				});
			},
			swipeMenu: function() {
				$(document.body).addSwipeEvents().bind('swiperight', function(evt, touch) {
					$(document.body).addClass('vsf-m-menu-visible');
				});

				$(document.body).addSwipeEvents().bind('swipe', function(evt, touch) {
					$(document.body).removeClass('vsf-m-menu-visible');
				});
			}
		};

		var Gallery = {
			run: function() {},
			init: function() {
				Gallery.setImageOrientation();
			},
			ajaxStop: function() {},
			windowOnload: function() {},
			setImageOrientation: function(){
				var setOrientation = function(img) {
					$t = $(img);
					if($t.height() > $t.width())
						$t.addClass("vsf-landscape");
				};

				$(".vsf-photo-galleries .vsf-gallery-cover").each(function() {
					$(this).attr("src", $(this).attr("data-src")).load(function() {
						setOrientation(this);
					});
				});
			}
		};
	}
	catch (e) {(typeof console !== "undefined" && typeof console.error === "function" && console.error("Houve um erro nos objetos. Detalhes: " + e.message)); }

	try {
		(function() {
			var body, ajaxStop, windowLoad;

			windowLoad = function() {
				Common.windowOnload();
				if (body.is(".page-template-template-gallery")) Gallery.windowOnload();
			};

			ajaxStop = function() {
				Common.ajaxStop();
				if (body.is(".page-template-template-gallery")) Gallery.ajaxStop();
			};

			$(function() {
				body = $("body");
				Common.init();
				if (body.is(".page-template-template-gallery")) Gallery.init();
				$(document).ajaxStop(ajaxStop);
				$(window).load(windowLoad);
				body.addClass('jsFullLoaded');
			});

			Common.run();
		})();
	}
	catch (e) {(typeof console !== "undefined" && typeof console.error === "function" && $("body").addClass('jsFullLoaded jsFullLoadedError') && console.error("Houve um erro ao iniciar os objetos. Detalhes: " + e.message)); }
})(jQuery);

/* jQuery Cookie Plugin v1.4.1 // https://github.com/carhartl/jquery-cookie // Copyright 2013 Klaus Hartl // Released under the MIT license */
(function(){"function"!==typeof jQuery.cookie&&function(c){"function"===typeof define&&define.amd?define(["jquery"],c):"object"===typeof exports?c(require("jquery")):c(jQuery)}(function(c){function p(a){a=e.json?JSON.stringify(a):String(a);return e.raw?a:encodeURIComponent(a)}function n(a,g){var b;if(e.raw)b=a;else a:{var d=a;0===d.indexOf('"')&&(d=d.slice(1,-1).replace(/\\"/g,'"').replace(/\\\\/g,"\\"));try{d=decodeURIComponent(d.replace(l," "));b=e.json?JSON.parse(d):d;break a}catch(h){}b=void 0}return c.isFunction(g)?
g(b):b}var l=/\+/g,e=c.cookie=function(a,g,b){if(1<arguments.length&&!c.isFunction(g)){b=c.extend({},e.defaults,b);if("number"===typeof b.expires){var d=b.expires,h=b.expires=new Date;h.setTime(+h+864E5*d)}return document.cookie=[e.raw?a:encodeURIComponent(a),"=",p(g),b.expires?"; expires="+b.expires.toUTCString():"",b.path?"; path="+b.path:"",b.domain?"; domain="+b.domain:"",b.secure?"; secure":""].join("")}for(var d=a?void 0:{},h=document.cookie?document.cookie.split("; "):[],m=0,l=h.length;m<l;m++){var f=
h[m].split("="),k;k=f.shift();k=e.raw?k:decodeURIComponent(k);f=f.join("=");if(a&&a===k){d=n(f,g);break}a||void 0===(f=n(f))||(d[k]=f)}return d};e.defaults={};c.removeCookie=function(a,e){if(void 0===c.cookie(a))return!1;c.cookie(a,"",c.extend({},e,{expires:-1}));return!c.cookie(a)}})})();

// https://github.com/technoweenie/jquery.doubletap
(function(e){var f={swipeTolerance:40},d=function(a,c){this.target=e(a);this.touch=c;this.startX=this.currentX=c.screenX;this.startY=this.currentY=c.screenY;this.eventType=null};d.options={};d.latestTap=null;d.prototype.move=function(a){this.currentX=a.screenX;this.currentY=a.screenY};d.prototype.process=function(){var a=this.currentX-this.startX,c=this.currentY-this.startY;0==a&&0==c?this.checkForDoubleTap():Math.abs(c)>d.options.swipeTolerance&&Math.abs(c)>Math.abs(a)?(this.eventType=0<c?"swipedown":
"swipeup",this.target.trigger("swipe",[this])):Math.abs(a)>d.options.swipeTolerance&&(this.eventType=0<a?"swiperight":"swipeleft",this.target.trigger("swipe",[this]));this.eventType&&this.target.trigger(this.eventType,[this]);this.target.trigger("touch",[this])};d.prototype.checkForDoubleTap=function(){d.latestTap&&400>new Date-d.latestTap&&(this.eventType="doubletap");this.eventType||(this.eventType="tap");d.latestTap=new Date};var b=function(a,c){d.options=e.extend(f,c);a.bind("touchstart",this.touchStart);
a.bind("touchmove",this.touchMove);a.bind("touchcancel",this.touchCancel);a.bind("touchend",this.touchEnd)};b.prototype.touchStart=function(a){var c=this;b.eachTouch(a,function(a){b.touches[a.identifier]=new d(c,a)})};b.prototype.touchMove=function(a){b.eachTouch(a,function(a){var d=b.touches[a.identifier];d&&d.move(a)})};b.prototype.touchCancel=function(a){b.eachTouch(a,function(a){b.purge(a,!0)})};b.prototype.touchEnd=function(a){b.eachTouch(a,function(a){b.purge(a)})};b.touches={};b.purge=function(a,
c){if(!c){var d=b.touches[a.identifier];d&&d.process()}delete b.touches[a.identifier]};b.eachTouch=function(a,c){a=a.originalEvent;for(var d=a.changedTouches.length,b=0;b<d;b++)c(a.changedTouches[b])};e.fn.addSwipeEvents=function(a,c){!c&&jQuery.isFunction(a)&&(c=a,a=null);new b(this,a);c&&this.bind("touch",c);return this}})(jQuery);