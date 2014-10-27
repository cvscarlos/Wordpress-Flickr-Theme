<?php
include_once get_template_directory() . "/helpers/rain.tpl.class.php";
raintpl::configure("tpl_dir", get_template_directory() . "/tpl/admin/");
raintpl::configure("cache_dir", get_template_directory() . "/tmp/");
raintpl::configure( 'path_replace', false );

function setup_theme_admin_menus() {
	add_menu_page('Flickr Theme Config', 'Flickr Config', 'manage_options', 'vs_Flickr_Theme', 'theme_settings_page', get_template_directory_uri() . '/admin/config16.png');
	add_submenu_page('vs_Flickr_Theme', 'Flickr Theme Config', 'Front Page', 'manage_options', 'vs_Flickr_Theme', 'themeConfigPage');
	add_submenu_page('', 'Flickr Theme Config', 'Front Page', 'manage_options', 'vs_Flickr_Theme_iframe', 'themeConfigPageFrame');
}

// Página de configuração do thema
function themeConfigPage() {
	// Check that the user is allowed to update options
	if (!current_user_can('manage_options'))
		wp_die('Você não tem permissões suficientes para acessar esta página.');

	$tpl = new raintpl();
	$tpl->draw("config");
}
// Iframe da página de configuração do iframe
function themeConfigPageFrame() {
	// Check that the user is allowed to update options
	if (!current_user_can('manage_options'))
		wp_die('Você não tem permissões suficientes para acessar esta página.');

	$tpl = new raintpl();
	$tpl->assign(array(
		"templateUri" => get_template_directory_uri()
	));
	$tpl->draw("configFrame");
}

// We also need to add the handler function for the top level menu
function theme_settings_page() {}

// Customizando o estilo do Admin
function adminStyles() {
    wp_enqueue_style('VS Flickr Styles', get_template_directory_uri() . "/admin/style.css");

    wp_enqueue_script('VS Flickr Scripts', get_template_directory_uri() . "/admin/script.js");
}

// Adicionando ações ao WP
add_action("admin_menu", "setup_theme_admin_menus");
add_action('admin_enqueue_scripts', 'adminStyles');
// add_action('login_enqueue_scripts', 'my_admin_theme_style');