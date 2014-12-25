<?php
/*
Template Name: Gallery
*/
$albums = get_flickr_albuns();
$album = "";
foreach ($albums->photoset as $v) {
	$album .= '<li><a href="' . get_site_url() . '/_r/' . sanitize_title($v->title->_content) . '?ps=' . $v->id . '" title="' . $v->title->_content . '">';
	$album .= "<img src='https://farm{$v->farm}.staticflickr.com/{$v->server}/{$v->primary}_{$v->secret}_m.jpg' alt='{$v->title->_content}' />";
	$album .= '</a></li>';
}

get_header();

echo get_permalink(1);
?>

<ul>
	<?php echo $album; ?>
</ul>

<?php get_footer(); ?>