<?php
// ícones: https://www.iconfinder.com/iconsets/fugue

include_once get_template_directory() . "/helpers/rain.tpl.class.php";
raintpl::configure("tpl_dir", get_template_directory() . "/tpl/admin/");
raintpl::configure("cache_dir", get_template_directory() . "/tmp/");
raintpl::configure( "path_replace", false );

function setup_theme_admin_menus() {
	add_menu_page("Flickr Theme Config", "Flickr Config", "manage_options", "vs_flickr_theme", "theme_settings_page", get_template_directory_uri() . "/admin/config16.png");
	add_submenu_page("vs_flickr_theme", "Flickr Theme Config", "Front Page", "manage_options", "vs_flickr_theme", "theme_config_page");
	add_submenu_page("", "Flickr Theme Config", "Front Page", "manage_options", "vs_flickr_theme_iframe", "theme_config_page_frame");
}

// Página de configuração do thema
function theme_config_page() {
	// Check that the user is allowed to update options
	if (!current_user_can("manage_options"))
		wp_die("Você não tem permissões suficientes para acessar esta página.");

	$tpl = new raintpl();
	$tpl->draw("config");
}
// Iframe da página de configuração do iframe
function theme_config_page_frame() {
	// Check that the user is allowed to update options
	if (!current_user_can("manage_options"))
		wp_die("Você não tem permissões suficientes para acessar esta página.");

	if(count($_POST) && isset($_POST["userid"]))
		return theme_config_page_post();
	elseif(count($_POST) && isset($_POST["clearCache"]))
		return theme_clear_cache();
	elseif(count($_POST) && isset($_POST["disqusid"]))
		return theme_config_disqus_id();

	$tpl = new raintpl();
	$tpl->assign(array(
		"templateUri" => get_template_directory_uri(),
		"flickrUserId" => get_option("vs_flickr_user_id")? get_option("vs_flickr_user_id"): "",
		"disqusId" => get_option("vs_flickr_disqus_id")? get_option("vs_flickr_disqus_id"): ""
		));
	$tpl->draw("configFrame");
}
// Configurações de POST da página de config do admin
function theme_config_page_post() {
	try {
		if(get_option("vs_flickr_user_id") === false)
			add_option("vs_flickr_user_id", $_POST["userid"]);
		else
			update_option("vs_flickr_user_id", $_POST["userid"]);
	} catch (Exception $e) {
		echo "Exceção pega: ",  $e->getMessage();
	}
}
// POST para configurar a conta do DISQUS
function theme_config_disqus_id() {
	try {
		if(get_option("vs_flickr_disqus_id") === false)
			add_option("vs_flickr_disqus_id", $_POST["disqusid"]);
		else
			update_option("vs_flickr_disqus_id", $_POST["disqusid"]);
	} catch (Exception $e) {
		echo "Exceção pega: ",  $e->getMessage();
	}
}
// POST para limpar o cache
function theme_clear_cache() {
	try {
		function clear($dir){
			if ($handle = opendir($dir)) {
				while (false !== ($file = readdir($handle))) {
					if ($file == "." || $file == "..")
						continue;
					if(is_dir($dir . $file . "/")){
						clear($dir . $file . "/");
						rmdir($dir . $file . "/");
					}
					else
						unlink($dir . $file);
				}
				closedir($handle);
			}
		};

		clear(get_template_directory() . '/cache/');

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


// Requisitando plugins para o tema
function show_required_plugins_messages() {
	$plugin_messages = array();
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

	// Download the Disqus comment system
	if(!is_plugin_active( 'disqus-comment-system/disqus.php' ))
		$plugin_messages[] = 'This theme requires you to install the Disqus Comment System plugin, download it <a href="http://wordpress.org/extend/plugins/disqus-comment-system/">here</a> or <a href="' . admin_url() . 'plugin-install.php?tab=search&s=Disqus+Comment+System">here</a>.';

	if(count($plugin_messages) > 0) {
		echo '<div id="message" class="error">';
		foreach($plugin_messages as $message)
			echo '<p><strong>'.$message.'</strong></p>';
		echo '</div>';
	}
}


// Adicionando ações ao WP
add_action("admin_menu", "setup_theme_admin_menus");
add_action("admin_enqueue_scripts", "admin_styles");
// add_action("login_enqueue_scripts", "my_admin_theme_style");
add_action('admin_notices', 'show_required_plugins_messages');