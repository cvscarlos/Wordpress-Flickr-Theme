(function($){
	var isFrame = (location !== parent.location);

	window.vsFlickrFrameResize = function(height){
		$("#vsFlickrFrame").css("min-height", height);
	}

	var inFrame = {
		init: function() {
			inFrame.setHeight();
			inFrame.setHeightWatching();
			inFrame.saveForm();
			inFrame.loadFlickrInfo();
			inFrame.clearCache();
			inFrame.saveGeneralConfig();
		},
		setHeight: function() {
			if(typeof parent.vsFlickrFrameResize === "function")
				parent.vsFlickrFrameResize($(document).height());
		},
		setHeightWatching: function() {
			var wTime = 0;
			$(window).bind("scroll.vsFlickr", function() {
				clearTimeout(wTime);
				wTime = setTimeout(inFrame.setHeight, 20);
			});
		},
		loadFlickrInfo: function() {
			userid = $("#flickrUserId").val() || "";
			wrapper = $('.vsFlickrUserInfo dl');

			$.jsonp({
				url: "https://api.flickr.com/services/rest/?method=flickr.people.getInfo&api_key=fc5b65c11b4095f23c57019fd394c95f&user_id=" + userid + "&format=json&jsoncallback=?",
				dataType: "jsonp",
				callback: "loadFlickrInfoCallback",
				success: function(data) {
					try{
						wrapper.append('<dt>User ID:</dt><dd>' + data.person.id + '</dd>');
						wrapper.append('<dt>Username:</dt><dd>' + data.person.username._content + '</dd>');
						wrapper.append('<dt>Real Name:</dt><dd>' + data.person.realname._content + '</dd>');
						wrapper.append('<dt>Photos URL:</dt><dd><a href="' + data.person.photosurl._content + '">' + data.person.photosurl._content + '</a></dd>');
						wrapper.append('<dt>Profile URL:</dt><dd><a href="' + data.person.profileurl._content + '">' + data.person.profileurl._content + '</a></dd>');
						wrapper.parent().slideDown(function(){
							$(window).trigger("scroll.vsFlickr");
						});
					}
					catch(e){
						if(typeof console === "object" && typeof console.warn === "function")
							console.warn("Não foi possível obter os dados do usuário. Detalhes: " + e.message);
					}
				}
			});
		},
		saveForm: function(){
			$(".vs-flickr-config").submit(function(e) {
				e.preventDefault();
				var $t = $(this);
				var username = $t.find("#flickrUsername").val() || "";
				var email = $t.find("#flickrEmail").val() || "";

				if(!username.length && !email.length)
					return alert("Please, insert your username ou e-mail.");

				if(username.length)
					var url = "https://api.flickr.com/services/rest/?method=flickr.people.findByUsername&api_key=fc5b65c11b4095f23c57019fd394c95f&username=" + username + "&format=json&jsoncallback=?";
				else
					var url = "https://api.flickr.com/services/rest/?method=flickr.people.findByEmail&api_key=fc5b65c11b4095f23c57019fd394c95f&find_email=" + email + "&format=json&jsoncallback=?";

				$(".vsFlickrLoading").show();
				$.jsonp({
					url: url,
					dataType: "jsonp",
					success: function(data) {
						if(data.stat !== "ok")
							return alert(data.message);

						$t.find("#flickrUserId").val(data.user.id);
						$.ajax({
							url: location.href,
							data: {userid: data.user.id},
							dataType: "html",
							type: "POST",
							success: function(data){
								alert('Data saved successfully! :D');

								inFrame.loadFlickrInfo();
							},
							error: function() {
								alert("Error while trying to save your information in the database.");
							}
						});
					},
					error: function() {
						alert("Unable to get your user id. Please try again or contact support!");
					},
					complete: function() {
						$(".vsFlickrLoading").hide();
					}
				});
			});
		},
		saveGeneralConfig: function(){
			$(".vs-flickr-general-config").submit(function(e) {
				e.preventDefault();
				var $form = $(this);

				var button = $form.find('button[type="submit"]');
				var icon = button.find("i").removeClass('fa-floppy-o').addClass('fa-spinner fa-spin');
				button.attr('disabled', 'disabled');
				
				$.ajax({
					url: location.href,
					data: $form.serialize(),
					dataType: "html",
					type: "POST",
					success: function(data){
						alert('Data saved successfully! :D');
					},
					error: function() {
						alert("Error while trying to save your information in the database.");
					},
					complete: function() {
						icon.addClass('fa-floppy-o').removeClass('fa-spinner fa-spin');
						button.removeAttr('disabled');
					}
				});
			});
		},
		clearCache: function(){
			$(".vs-flickr-clear-cache").click(function(e) {
				e.preventDefault();
				var $t = $(this);
				var icon = $t.find("i").removeClass('fa-refresh').addClass('fa-spinner fa-spin');
				$t.attr('disabled', 'disabled');

				$.ajax({
					url: location.href,
					data: {clearCache: true},
					dataType: "html",
					type: "POST",
					success: function(data){
						alert('Cache clean! :D');
					},
					error: function() {
						alert("Error while trying to clean the cache. Check the directory is writable.");
					},
					complete: function() {
						icon.addClass('fa-refresh').removeClass('fa-spinner fa-spin');
						$t.removeAttr('disabled');
					}
				});
			});
		}
	};

	var outFrame = {
		init: function() {
			outFrame.createFrame();
		},
		createFrame: function() {
			frame = $('<iframe id="vsFlickrFrame" frameborder="0"></iframe>');
			frame.attr("src", location.href.replace("vs_flickr_theme", "vs_flickr_theme_iframe"));
			$("#vsFlickrWrapper").append(frame);
		}
	}

	$(function() {
		if(isFrame)
			inFrame.init();
		else
			outFrame.init();
	});

	$(document).ajaxStart(function (){$(".vsFlickrLoading").show();}).ajaxStop(function (){$(".vsFlickrLoading").hide();});
})(jQuery);


// jquery.jsonp 2.4.0 (c)2012 Julian Aubourg | MIT License
// https://github.com/jaubourg/jquery-jsonp
(function(e){function t(){}function n(e){C=[e]}function r(e,t,n){return e&&e.apply&&e.apply(t.context||t,n)}function i(e){return/\?/.test(e)?"&":"?"}function O(c){function Y(e){z++||(W(),j&&(T[I]={s:[e]}),D&&(e=D.apply(c,[e])),r(O,c,[e,b,c]),r(_,c,[c,b]))}function Z(e){z++||(W(),j&&e!=w&&(T[I]=e),r(M,c,[c,e]),r(_,c,[c,e]))}c=e.extend({},k,c);var O=c.success,M=c.error,_=c.complete,D=c.dataFilter,P=c.callbackParameter,H=c.callback,B=c.cache,j=c.pageCache,F=c.charset,I=c.url,q=c.data,R=c.timeout,U,z=0,W=t,X,V,J,K,Q,G;return S&&S(function(e){e.done(O).fail(M),O=e.resolve,M=e.reject}).promise(c),c.abort=function(){!(z++)&&W()},r(c.beforeSend,c,[c])===!1||z?c:(I=I||u,q=q?typeof q=="string"?q:e.param(q,c.traditional):u,I+=q?i(I)+q:u,P&&(I+=i(I)+encodeURIComponent(P)+"=?"),!B&&!j&&(I+=i(I)+"_"+(new Date).getTime()+"="),I=I.replace(/=\?(&|$)/,"="+H+"$1"),j&&(U=T[I])?U.s?Y(U.s[0]):Z(U):(E[H]=n,K=e(y)[0],K.id=l+N++,F&&(K[o]=F),L&&L.version()<11.6?(Q=e(y)[0]).text="document.getElementById('"+K.id+"')."+p+"()":K[s]=s,A&&(K.htmlFor=K.id,K.event=h),K[d]=K[p]=K[v]=function(e){if(!K[m]||!/i/.test(K[m])){try{K[h]&&K[h]()}catch(t){}e=C,C=0,e?Y(e[0]):Z(a)}},K.src=I,W=function(e){G&&clearTimeout(G),K[v]=K[d]=K[p]=null,x[g](K),Q&&x[g](Q)},x[f](K,J=x.firstChild),Q&&x[f](Q,J),G=R>0&&setTimeout(function(){Z(w)},R)),c)}var s="async",o="charset",u="",a="error",f="insertBefore",l="_jqjsp",c="on",h=c+"click",p=c+a,d=c+"load",v=c+"readystatechange",m="readyState",g="removeChild",y="<script>",b="success",w="timeout",E=window,S=e.Deferred,x=e("head")[0]||document.documentElement,T={},N=0,C,k={callback:l,url:location.href},L=E.opera,A=!!e("<div>").html("<!--[if IE]><i><![endif]-->").find("i").length;O.setup=function(t){e.extend(k,t)},e.jsonp=O})(jQuery)