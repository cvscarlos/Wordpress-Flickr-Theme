<?php
/*
Template Name: Gallery
*/
$albums = get_flickr_albuns();
$album = "";
foreach ($albums->photosets->photoset as $v) {
	// $album .= '<li><a href="' . get_site_url() . '/_r/' . sanitize_title($v->title->_content) . '?f_ps=' . $v->id . '" title="' . $v->title->_content . '">';
	$album .= '<li><a href="' . get_site_url() . '?album=' . sanitize_title($v->title->_content) . '&amp;f_ps=' . $v->id . '&amp;_r=1" title="' . $v->title->_content . '">';
	$album .= "<img src='https://farm{$v->farm}.staticflickr.com/{$v->server}/{$v->primary}_{$v->secret}.jpg' alt='{$v->title->_content}' />";
	$album .= '</a></li>';
}

$post = get_post();

get_header();

echo nl2br($post->post_content);
?>

<div class="vsf-photo-galleries clearfix">
	<ul class="clearfix">
		<?php echo $album; ?>
	</ul>
</div>

<?php get_footer();