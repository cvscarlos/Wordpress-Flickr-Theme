<?php
/*
Template Name: Gallery
*/
$albums = get_flickr_albuns();
$album = "";
foreach ($albums->photosets->photoset as $v) {
	$album .= '<li class="col-xs-6"><div class="vsf-gallery-cover-wrapper">';
	$album .= '<a href="' . get_site_url() . '?album=' . sanitize_title($v->title->_content) . '&amp;f_ps=' . $v->id . '&amp;_r=1" title="' . $v->title->_content . '">';
	$album .= "<img src='" . get_template_directory_uri() . "/img/vsf-image-size-250x250.png' class='vsf-img-size img-responsive' />";
	$album .= "<img src='https://farm{$v->farm}.staticflickr.com/{$v->server}/{$v->primary}_{$v->secret}.jpg' alt='{$v->title->_content}' class='vsf-gallery-cover' onload='window.vsfSetImageOrientation(this)' />";
	$album .= "<p class='vsf-gallery-title'><span>{$v->title->_content}</span><b></b></p>";
	$album .= '</a></div></li>';
}

$post = get_post();

get_header();

echo nl2br($post->post_content);
?>

<div class="vsf-photo-galleries clearfix">
	<ul class="row">
		<?php echo $album; ?>
	</ul>
</div>

<?php get_footer();