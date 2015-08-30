<?php
$photoId = get_post_custom_values("vsFlickrPhotoId", get_the_ID())[0];
$photo = get_flickr_photo_info($photoId);
check_photo_post($photo);
get_header();
?>
<h2><?php echo $photo->photo->title->_content;?></h2>

<p><?php echo $photo->photo->description->_content;?></p>

<p>
	<?php echo "<img src='https://farm{$photo->photo->farm}.staticflickr.com/{$photo->photo->server}/{$photo->photo->id}_{$photo->photo->secret}_b.jpg' alt='{$photo->photo->title->_content}' class='center-block img-responsive' />"; ?>
</p>

<?php get_footer(); ?>