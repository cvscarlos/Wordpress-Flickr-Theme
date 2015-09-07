/*
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
			run: function() {},
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
				// $(".vsf-side-menu").on('swipeleft', function(evt, touch) {
				// 	$(document.body).removeClass('vsf-m-menu-visible');
				// });

				$(document.body).on('swiperight', function(evt, touch) {
					$(document.body).addClass('vsf-m-menu-visible');
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

		var Album = {
			run: function() {},
			init: function() {
				Album.showPhoto();
				Album.photoGrid();
			},
			ajaxStop: function() {},
			windowOnload: function() {},
			showPhoto: function() {
				initPhotoSwipeFromDOM('.vsf-gallery');
			},
			photoGrid: function() {
				function collage() {
					jQuery('.vsf-gallery').collagePlus({
						allowPartialLastRow: true,
						targetHeight : 310
					});
				};
				collage();

				var resizeTimer = null;
				$(window).bind('resize', function() {
					if (resizeTimer)
						clearTimeout(resizeTimer);
					resizeTimer = setTimeout(collage, 200);
				});
			}
		};

		var Page = {
			run: function() {},
			init: function() {
				// Post.imgResponsive();
				Page.contactForm7SetClass();
			},
			ajaxStop: function() {},
			windowOnload: function() {},
			contactForm7SetClass: function() {
				var formItem = $(".wpcf7-form").children("p").addClass("form-group row");
				formItem.find("input:not([type=submit]), textarea, select").addClass("form-control");
				formItem.find("input[type=submit]").addClass("btn btn-primary");
				formItem.children("span").addClass("col-md-18");
			}
		};

		var Post = {
			run: function() {},
			init: function() {
				// Post.imgResponsive();
			},
			ajaxStop: function() {},
			windowOnload: function() {},
			/*imgResponsive: function() {
				$(".vsf-post-wrapper img.size-full").addClass('img-responsive');
			}*/
		};

		var Category = {
			run: function() {},
			init: function() {
				Category.paginate();
			},
			ajaxStop: function() {},
			windowOnload: function() {},
			paginate: function() {
				$(".nav-links").find("a, span").wrapAll('<ul class="pagination"></ul>').wrap('<li></li>').filter(".current").parent().addClass('active');
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
				if (body.is(".single-vs_flickr_album")) Album.windowOnload();
				if (body.is(".page-template-default")) Page.windowOnload();
				if (body.is(".single-post")) Post.windowOnload();
				if (body.is(".category")) Category.windowOnload();
			};

			ajaxStop = function() {
				Common.ajaxStop();
				if (body.is(".page-template-template-gallery")) Gallery.ajaxStop();
				if (body.is(".single-vs_flickr_album")) Album.ajaxStop();
				if (body.is(".page-template-default")) Page.ajaxStop();
				if (body.is("..single-post")) Post.ajaxStop();
				if (body.is("..category")) Category.ajaxStop();
			};

			$(function() {
				body = $(document.body);
				Common.init();
				if (body.is(".page-template-template-gallery")) Gallery.init();
				if (body.is(".single-vs_flickr_album")) Album.init();
				if (body.is(".page-template-default")) Page.init();
				if (body.is(".single-post")) Post.init();
				if (body.is(".category")) Category.init();
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

	var initPhotoSwipeFromDOM = function(gallerySelector) {

	// parse slide data (url, title, size ...) from DOM elements
	// (children of gallerySelector)
	var parseThumbnailElements = function(el) {
		var thumbElements = el.childNodes,
			numNodes = thumbElements.length,
			items = [],
			figureEl,
			linkEl,
			size,
			item;

		for(var i = 0; i < numNodes; i++) {

			figureEl = thumbElements[i]; // <figure> element

			// include only element nodes
			if(figureEl.nodeType !== 1) {
				continue;
			}

			linkEl = figureEl.children[0]; // <a> element

			size = linkEl.getAttribute('data-size').split('x');

			// create slide object
			item = {
				src: linkEl.getAttribute('href'),
				w: parseInt(size[0], 10),
				h: parseInt(size[1], 10)
			};



			if(figureEl.children.length > 1) {
				// <figcaption> content
				item.title = figureEl.children[1].innerHTML;
			}

			if(linkEl.children.length > 0) {
				// <img> thumbnail element, retrieving thumbnail url
				item.msrc = linkEl.children[0].getAttribute('src');
			}

			item.el = figureEl; // save link to element for getThumbBoundsFn
			items.push(item);
		}

		return items;
	};

	// find nearest parent element
	var closest = function closest(el, fn) {
		return el && ( fn(el) ? el : closest(el.parentNode, fn) );
	};

	// triggers when user clicks on thumbnail
	var onThumbnailsClick = function(e) {
		e = e || window.event;
		e.preventDefault ? e.preventDefault() : e.returnValue = false;

		var eTarget = e.target || e.srcElement;

		// find root element of slide
		var clickedListItem = closest(eTarget, function(el) {
			return (el.tagName && el.tagName.toUpperCase() === 'FIGURE');
		});

		if(!clickedListItem) {
			return;
		}

		// find index of clicked item by looping through all child nodes
		// alternatively, you may define index via data- attribute
		var clickedGallery = clickedListItem.parentNode,
			childNodes = clickedListItem.parentNode.childNodes,
			numChildNodes = childNodes.length,
			nodeIndex = 0,
			index;

		for (var i = 0; i < numChildNodes; i++) {
			if(childNodes[i].nodeType !== 1) {
				continue;
			}

			if(childNodes[i] === clickedListItem) {
				index = nodeIndex;
				break;
			}
			nodeIndex++;
		}



		if(index >= 0) {
			// open PhotoSwipe if valid index found
			openPhotoSwipe( index, clickedGallery );
		}
		return false;
	};

	// parse picture index and gallery index from URL (#&pid=1&gid=2)
	var photoswipeParseHash = function() {
		var hash = window.location.hash.substring(1),
		params = {};

		if(hash.length < 5) {
			return params;
		}

		var vars = hash.split('&');
		for (var i = 0; i < vars.length; i++) {
			if(!vars[i]) {
				continue;
			}
			var pair = vars[i].split('=');
			if(pair.length < 2) {
				continue;
			}
			params[pair[0]] = pair[1];
		}

		if(params.gid) {
			params.gid = parseInt(params.gid, 10);
		}

		return params;
	};

	var openPhotoSwipe = function(index, galleryElement, disableAnimation, fromURL) {
		var pswpElement = document.querySelectorAll('.pswp')[0],
			gallery,
			options,
			items;

		items = parseThumbnailElements(galleryElement);

		// define options (if needed)
		options = {

			// define gallery index (for URL)
			galleryUID: galleryElement.getAttribute('data-pswp-uid'),

			getThumbBoundsFn: function(index) {
				// See Options -> getThumbBoundsFn section of documentation for more info
				var thumbnail = items[index].el.getElementsByTagName('img')[0], // find thumbnail
					pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
					rect = thumbnail.getBoundingClientRect();

				return {x:rect.left, y:rect.top + pageYScroll, w:rect.width};
			}

		};

		// PhotoSwipe opened from URL
		if(fromURL) {
			if(options.galleryPIDs) {
				// parse real index when custom PIDs are used
				// http://photoswipe.com/documentation/faq.html#custom-pid-in-url
				for(var j = 0; j < items.length; j++) {
					if(items[j].pid == index) {
						options.index = j;
						break;
					}
				}
			} else {
				// in URL indexes start from 1
				options.index = parseInt(index, 10) - 1;
			}
		} else {
			options.index = parseInt(index, 10);
		}

		// exit if index not found
		if( isNaN(options.index) ) {
			return;
		}

		if(disableAnimation) {
			options.showAnimationDuration = 0;
		}

		// Pass data to PhotoSwipe and initialize it
		gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options);
		gallery.init();
	};

	// loop through all gallery elements and bind events
	var galleryElements = document.querySelectorAll( gallerySelector );

	for(var i = 0, l = galleryElements.length; i < l; i++) {
		galleryElements[i].setAttribute('data-pswp-uid', i+1);
		galleryElements[i].onclick = onThumbnailsClick;
	}

	// Parse URL and open gallery if it contains #&pid=3&gid=1
	var hashData = photoswipeParseHash();
	if(hashData.pid && hashData.gid) {
		openPhotoSwipe( hashData.pid ,  galleryElements[ hashData.gid - 1 ], true, true );
	}
};