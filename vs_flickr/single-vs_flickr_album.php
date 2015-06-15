<?php
$albumId = get_post_custom_values("vsFlickrAlbumId", get_the_ID())[0];
$albumInfo = get_flickr_album_info($albumId);
check_album_post($albumInfo);

$albumJson = get_flickr_album($albumId);
$album = "";
foreach ($albumJson->photoset->photo as $v) {
	// $album .= '<li><a href="' . get_site_url() . '/_r/photo/' . sanitize_title($v->title) . '?f_p=' . $v->id . '" title="' . $v->title . '">';
	// $album .= '<li><a href="' . get_site_url() . '?photo=' . sanitize_title($v->title) . '&amp;f_p=' . $v->id . '&amp;_r=1" title="' . $v->title . '">';
	// $album .= "<img src='https://farm{$v->farm}.staticflickr.com/{$v->server}/{$v->id}_{$v->secret}_m.jpg' alt='{$v->title->_content}' />";
	$album .= "<img src='https://farm{$v->farm}.staticflickr.com/{$v->server}/{$v->id}_{$v->secret}_m.jpg' alt='{$v->title->_content}' />";
	// $album .= '</a></li>';
}

get_header();
?>
<div class="vsf-album-wrapper">
	<h2><?php echo $albumInfo->photoset->title->_content;?></h2>

	<p><?php echo $albumInfo->photoset->description->_content;?></p>

	<ul>
		<?php echo $album; ?>
	</ul>
</div>

<?php get_footer(); ?>