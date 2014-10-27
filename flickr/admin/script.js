(function($){
	var isFrame = (location !== parent.location);

	window.vsFlickrFrameResize = function(height){
		$("#vsFlickrFrame").css("min-height", height);
	}

	var inFrame = {
		init: function() {
			inFrame.setHeight();
			inFrame.setHeightWatching();
		},
		setHeight: function() {
			if(typeof parent.vsFlickrFrameResize === "function")
				parent.vsFlickrFrameResize($(document).height());
		},
		setHeightWatching: function() {
			var wTime = 0;
			$(window).bind("scroll", function() {
				clearTimeout(wTime);
				wTime = setTimeout(inFrame.setHeight, 20);
			});
		}
	};

	var outFrame = {
		init: function() {
			outFrame.createFrame();
		},
		createFrame: function() {
			frame = $('<iframe id="vsFlickrFrame" frameborder="0"></iframe>');
			frame.attr("src", location.href.replace("vs_Flickr_Theme", "vs_Flickr_Theme_iframe"));
			$("#vsFlickrWrapper").append(frame);
		}
	}

	$(function() {
		if(isFrame)
			inFrame.init();
		else
			outFrame.init();
	});
})(jQuery);