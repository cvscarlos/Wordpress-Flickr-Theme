<?php get_header(); ?>

<div class="col-md-20 col-md-offset-2">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<h1><?php the_title(); ?></h1>

		<?php the_content(); ?>
	<?php endwhile; else: ?>
		<h2>Error 404 Not Found!</h2>
	<?php endif; ?>
</div>

<?php get_footer(); ?>