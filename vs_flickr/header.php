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
	<div class="vsf-table">
		<div class="vsf-trow visible-xs">
			<div class="vsf-mobile-bar">
				<h1 class="vsf-logo-mobile"><a href="<?php echo get_bloginfo("url");?>"><?php echo get_bloginfo("name");?></a></h1>
			</div>
		</div>
		<div class="vsf-trow">
			<div class="vsf-cell vsf-side-menu">
				<div class="vsf-side-menu-bg"> </div>
				<div class="container-fluid">
					<div class="row">
						<div class="col-xs-24">
							<h1 class="vsf-logo"><a href="<?php echo get_bloginfo("url");?>"><?php echo get_bloginfo("name");?></a></h1>
							<div class="vsf-side-menu-wrapper">
								<?php wp_nav_menu($vsf_tpl["menu_args"]); ?>
							</div>
							<div class="vsf-social-menu">
								<?php wp_nav_menu($vsf_tpl["social_menu_args"]); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="vsf-cell vsf-main">
				<div class="container-fluid">
					<div class="row">
						<div class="col-xs-24">