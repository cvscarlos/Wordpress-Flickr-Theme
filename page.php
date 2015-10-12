<?php get_header(); ?>

<div class="vsf-page-wrapper vsf-text-wrapper">
	<div class="row">
		<div class="col-xs-24 col-md-20 col-md-offset-2">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<h1 class="post-title"><?php the_title(); ?></h1>
				<?php echo do_shortcode(wpautop(get_the_content())); ?>
			<?php endwhile; else: ?>
			<h2>Error 404 Not Found!</h2>
		<?php endif; ?>
	</div>
</div>
</div>

<?php get_footer();