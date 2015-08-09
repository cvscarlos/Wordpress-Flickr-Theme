<?php
$albumId = get_post_custom_values("vsFlickrAlbumId", get_the_ID())[0];
$albumInfo = get_flickr_album_info($albumId);
check_album_post($albumInfo);

$albumJson = get_flickr_album($albumId);
$album = "";
foreach ($albumJson->photoset->photo as $v) {
	$photoSize = get_flickr_photo_sizes($v->id)->sizes->size;

	// $album .= '<figure itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject" style="width:'. ($photoSize[4]->width + 10) . 'px; height:' . ($photoSize[4]->height + 10) . ';" >';
	// $album .= '<figure itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject" style="width:'. $photoSize[4]->width . 'px; height:' . $photoSize[4]->height . 'px;" >';
	$album .= '<figure itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">';
	$album .= '<a href="' . $photoSize[9]->source . '" itemprop="contentUrl" data-size="' . $photoSize[9]->width . 'x' . $photoSize[9]->height . '">';
	$album .= '<img src="' . $photoSize[4]->source . '" itemprop="thumbnail" alt="' . $v->title . '" width="'. $photoSize[4]->width . '" height="' . $photoSize[4]->height . '" />';
	$album .= '</a>';
	$album .= '<figcaption itemprop="caption description">' . $v->title . '</figcaption>';
	$album .= '</figure>';
}

get_header();
?>

<div class="vsf-album-wrapper">
	<h2><?php echo $albumInfo->photoset->title->_content;?></h2>

	<p><?php echo $albumInfo->photoset->description->_content;?></p>

	<div class="vsf-gallery" itemscope itemtype="http://schema.org/ImageGallery">
		<?php echo $album; ?>
	</div>
</div>

<?php comments_template(); ?>

<!-- Root element of PhotoSwipe. Must have class pswp. -->
<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
	<!-- Background of PhotoSwipe.
		 It's a separate element as animating opacity is faster than rgba(). -->
	<div class="pswp__bg"></div>
	<!-- Slides wrapper with overflow:hidden. -->
	<div class="pswp__scroll-wrap">
		<!-- Container that holds slides.
			PhotoSwipe keeps only 3 of them in the DOM to save memory.
			Don't modify these 3 pswp__item elements, data is added later on. -->
		<div class="pswp__container">
			<div class="pswp__item"></div>
			<div class="pswp__item"></div>
			<div class="pswp__item"></div>
		</div>
		<!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
		<div class="pswp__ui pswp__ui--hidden">
			<div class="pswp__top-bar">
				<!--  Controls are self-explanatory. Order can be changed. -->
				<div class="pswp__counter"></div>
				<button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
				<button class="pswp__button pswp__button--share" title="Share"></button>
				<button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
				<button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
				<!-- Preloader demo http://codepen.io/dimsemenov/pen/yyBWoR -->
				<!-- element will get class pswp__preloader--active when preloader is running -->
				<div class="pswp__preloader">
					<div class="pswp__preloader__icn">
					  <div class="pswp__preloader__cut">
						<div class="pswp__preloader__donut"></div>
					  </div>
					</div>
				</div>
			</div>
			<div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
				<div class="pswp__share-tooltip"></div>
			</div>
			<button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
			</button>
			<button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
			</button>
			<div class="pswp__caption">
				<div class="pswp__caption__center"></div>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>