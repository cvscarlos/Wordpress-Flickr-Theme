<?php get_header(); ?>

<div class="vsf-post-wrapper">
	<div class="row">
		<div class="col-xs-24 col-md-20 col-md-offset-2">
			<?php
			$post = get_post();
			echo ($post->post_content);
			?>
		</div>
	</div>
</div>

<?php get_template_part("disqus"); ?>

<?php /*get_sidebar();*/ ?>
<?php get_footer(); ?>