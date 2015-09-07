<?php
get_header();
$post = get_post();
?>

<div class="vsf-post-wrapper vsf-text-wrapper">
	<div class="row">
		<div class="col-xs-24 col-md-20 col-md-offset-2">
			<h1 class="post-title"><?php echo single_post_title(); ?></h1>
			<div class="row">
				<div class="col-xs-24">
					<div class="vsf-post-info pull-right">
						<?php foreach(get_the_category() as $value): ?>
							<span class="vsf-item-info"><i class="fa fa-folder-o"></i> <a href="<?php echo esc_url(get_category_link($value->cat_ID)); ?>" title="<?php echo $value->name; ?>"><?php echo $value->name; ?></a></span>
						<?php endforeach; ?>
						<span class="vsf-item-info"><i class="fa fa-calendar-o"></i> <?php echo get_the_date(); ?></span>
					</div>
				</div>
			</div>
			<?php echo wpautop($post->post_content); ?>
		
			<?php get_template_part("disqus"); ?>
		</div>
	</div>
</div>

<?php /*get_sidebar();*/ ?>
<?php get_footer(); ?>