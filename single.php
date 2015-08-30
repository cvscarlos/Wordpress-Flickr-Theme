<?php 
$post = get_post();
get_header();
echo nl2br($post->post_content);
?>

-- SINGLE --

<?php get_sidebar(); ?>
<?php get_footer(); ?>