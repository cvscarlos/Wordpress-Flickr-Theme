<?php
// ícones: https://www.iconfinder.com/iconsets/fugue

include_once get_template_directory() . "/helpers/rain.tpl.class.php";
raintpl::configure("tpl_dir", get_template_directory() . "/tpl/admin/");
raintpl::configure("cache_dir", get_template_directory() . "/tmp/");
raintpl::configure( "path_replace", false );

function setup_theme_admin_menus() {
	add_menu_page("Flickr Theme Config", "Flickr Config", "manage_options", "vs_flickr_theme", "theme_settings_page", get_template_directory_uri() . "/admin/config16.png");
	add_submenu_page("vs_flickr_theme", "Flickr Theme Config", "Front Page", "manage_options", "vs_flickr_theme", "themeConfigPage");
	add_submenu_page("", "Flickr Theme Config", "Front Page", "manage_options", "vs_flickr_theme_iframe", "themeConfigPageFrame");
}

// Página de configuração do thema
function themeConfigPage() {
	// Check that the user is allowed to update options
	if (!current_user_can("manage_options"))
		wp_die("Você não tem permissões suficientes para acessar esta página.");

	$tpl = new raintpl();
	$tpl->draw("config");
}
// Iframe da página de configuração do iframe
function themeConfigPageFrame() {
	// Check that the user is allowed to update options
	if (!current_user_can("manage_options"))
		wp_die("Você não tem permissões suficientes para acessar esta página.");

	if(count($_POST))
		return themeConfigPagePost();

	$tpl = new raintpl();
	$tpl->assign(array(
		"templateUri" => get_template_directory_uri(),
		"flickrUserId" => get_option("vs_flickr_user_id")? get_option("vs_flickr_user_id"): ""
	));
	$tpl->draw("configFrame");
}
// Configurações de POST da página de config do admin
function themeConfigPagePost() {
	try {
		if(get_option("vs_flickr_user_id") === false)
			add_option("vs_flickr_user_id", $_POST["userid"]);
		else
			update_option("vs_flickr_user_id", $_POST["userid"]);
	} catch (Exception $e) {
		echo "Exceção pega: ",  $e->getMessage();
	}
}

// We also need to add the handler function for the top level menu
function theme_settings_page() {}

// Customizando o estilo do Admin
function admin_styles() {
	wp_enqueue_style("VS Flickr Styles", get_template_directory_uri() . "/admin/style.css");

	wp_enqueue_script("VS Flickr Scripts", get_template_directory_uri() . "/admin/script.js");
}

// Post Customizado: Álbum do Flickr
function custom_post_flickr_album() {
	$args = array(
		"public" => true,
		"label"  => "Albums",
		"menu_icon" => get_template_directory_uri() . "/admin/album16.png",
		"supports" => array("title", "editor", "custom-fields", "page-attributes"),
		"hierarchical" => true,
		"rewrite" => array("slug" => "album")
	);
	register_post_type("vs_flickr_album", $args);
}

// Adicionando ações ao WP
add_action("init", "custom_post_flickr_album");
add_action("admin_menu", "setup_theme_admin_menus");
add_action("admin_enqueue_scripts", "admin_styles");
// add_action("login_enqueue_scripts", "my_admin_theme_style");