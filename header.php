<?php global $vsf_tpl; ?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<title><?php echo $vsf_tpl["title"];?></title>
	<meta charset="<?php bloginfo('charset'); ?>" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<div class="vsf-m-overlay"></div>
	<div class="vsf-full-wrapper">
		<div class="visible-xs">
			<div class="vsf-mobile-bar vsf-table">
				<?php if(is_front_page()):?>
					<h1 class="vsf-logo-mobile vsf-cell"><a href="<?php echo get_bloginfo("url");?>"><?php echo get_bloginfo("name");?></a></h1>
				<?php else:?>
					<h2 class="vsf-logo-mobile vsf-cell h1"><a href="<?php echo get_bloginfo("url");?>"><?php echo get_bloginfo("name");?></a></h2>
				<?php endif;?>
				<div class="vsf-menu-button vsf-cell"><button><i class="fa fa-bars"></i></button></div>
			</div>
		</div>
		<div class="">
			<div class="vsf-side-menu">
				<div class="vsf-side-menu-bg"> </div>
				<div class="container-fluid">
					<div class="row">
						<div class="col-xs-24">
							<?php if(is_front_page()):?>
								<?php if (function_exists('jetpack_has_site_logo') && jetpack_has_site_logo()):?>
									<h1 class="vsf-logo vsf-logo-img"><?php jetpack_the_site_logo();?></h1>
								<?php else:?>
									<h1 class="vsf-logo"><a href="<?php echo get_bloginfo("url");?>"><?php echo get_bloginfo("name");?></a></h1>
								<?php endif;?>
							<?php else:?>
								<?php if (function_exists('jetpack_has_site_logo') && jetpack_has_site_logo()):?>
									<h2 class="vsf-logo vsf-logo-img h1"><?php jetpack_the_site_logo();?></h2>
								<?php else:?>
									<h2 class="vsf-logo h1"><a href="<?php echo get_bloginfo("url");?>"><?php echo get_bloginfo("name");?></a></h2>
								<?php endif;?>
							<?php endif;?>
							<div class="vsf-side-menu-wrapper">
								<?php wp_nav_menu($vsf_tpl["menu_args"]); ?>
							</div>
							<div class="vsf-social-menu">
								<?php wp_nav_menu($vsf_tpl["social_menu_args"]); ?>
							</div>
							<?php if(get_option("vs_flickr_copyright")): ?>
								<div class="vsf-copyright">
									<p><?php echo get_option("vs_flickr_copyright"); ?></p>
								</div>
							<?php endif;?>
						</div>
					</div>
				</div>
			</div>
			<div class="vsf-main">
				<div class="container-fluid">
					<div class="row">
						<div class="col-xs-24">