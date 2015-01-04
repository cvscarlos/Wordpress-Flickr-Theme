<?php global $vsf_tpl; ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<meta charset="<?php bloginfo('charset'); ?>" />
<title><?php echo $vsf_tpl["title"];?></title>
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<div class="vsf-table">
		<div class="vsf-cell vsf-side-menu">
			<div class="vsf-side-menu-bg"> </div>
			<div class="container-fluid">
				<div class="row">
					<div class="col-xs-12">
						<h1 class="vsf-logo">FLICKR THEME</h1>
						<?php wp_nav_menu($vsf_tpl["menu_args"]); ?>
					</div>
				</div>
			</div>
		</div>
		<div class="vsf-cell vsf-main">
			<div class="container-fluid">
				<div class="row">
					<div class="col-xs-12">