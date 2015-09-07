<?php get_header(); ?>

<div class="vsf-category-wrapper">
	<div class="row">
		<div class="col-xs-24 col-md-20 col-md-offset-2">
			<?php if (have_posts()) : ?>
				<h1 class="archive-title"><?php echo single_cat_title( '', false );?></h1>
				<?php if (category_description()) echo category_description(); ?>
				<?php while(have_posts()): the_post(); ?>
					<div class="vsf-category-item">
						<div class="row">
							<div class="col-xs-24">
								<?php if(has_post_thumbnail()): ?>
									<a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>">
										<?php the_post_thumbnail(); ?>
									</a>
								<?php endif; ?>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-24">
								<h2><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-24">
								<?php the_excerpt(); ?>
							</div>
						</div>
						<div class="row">
							<!-- <div class="col-xs-24 col-sm-12"> <i class="fa fa-user"></i> <span class="vsf-item-info"><?php the_author(); ?></span> </div> -->
							<div class="col-xs-24 col-sm-12"> <i class="fa fa-calendar-o"></i> <span class="vsf-item-info"><?php echo get_the_date(); ?></span> </div>
						</div>
					</div>
				<?php endwhile;?>

				<!-- Add the pagination functions here. -->
				<div class="vsf-navigation"><?php the_posts_pagination(array("mid_size" => 2)); ?></div>
			<?php else : ?>
				<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
			<?php endif; ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>