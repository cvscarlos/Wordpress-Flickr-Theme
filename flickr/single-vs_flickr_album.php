<?php
/*
Template Name: Gallery
*/
$albumId = get_post_custom_values("vsFlickrAlbumId", get_the_ID())[0];

$albumInfo = get_flickr_album_info($albumId);

check_album_post($albumInfo);

$albumJson = get_flickr_album($albumId);
$album = "";
foreach ($albumJson->photoset->photo as $v) {
	$album .= '<li><a href="' . get_site_url() . '/_r/photo/' . sanitize_title($v->title) . '?p=' . $v->id . '" title="' . $v->title . '">';
	$album .= "<img src='https://farm{$v->farm}.staticflickr.com/{$v->server}/{$v->id}_{$v->secret}_m.jpg' alt='{$v->title->_content}' />";
	$album .= '</a></li>';
}


get_header();
?>
<h2><?php echo $albumInfo->photoset->title->_content;?></h2>

<p><?php echo $albumInfo->photoset->description->_content;?></p>

<ul>
	<?php echo $album; ?>
</ul>

<?php get_footer(); ?>