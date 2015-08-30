<?php get_header(); ?>

<div class="vsf-category-wrapper">
	<div class="row">
		<div class="col-xs-24 col-md-20 col-md-offset-2">
			<?php if ( have_posts() ) : ?>
				<h1 class="archive-title"><?php echo single_cat_title( '', false );?></h1>
				<?php if (category_description()) echo category_description(); ?>
					<?php while(have_posts()): the_post(); ?>
						<div class="vsf-category-item">
							<div class="row">
								<div class="col-xs-24">
									<?php if(has_post_thumbnail()) the_post_thumbnail(); ?>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-24">
									<h2><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-24">
									<?php if(has_excerpt()) the_excerpt(); ?>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-24 col-sm-12"> <i class="fa fa-user"></i> <?php the_author(); ?> </div>
								<div class="col-xs-24 col-sm-12"> <i class="fa fa-calendar-o"></i> <?php the_date(); ?> </div>
							</div>
						</div>
					<?php endwhile;?>
				<?php else : ?>
				<?php get_template_part( 'content', 'none' ); ?>
			<?php endif; ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>