<?php
// ícones: https://www.iconfinder.com/iconsets/fugue

include_once get_template_directory() . "/helpers/TGM-Plugin-Activation/class-tgm-plugin-activation.php";

include_once get_template_directory() . "/helpers/rain.tpl.class.php";
raintpl::configure("tpl_dir", get_template_directory() . "/tpl/admin/");
raintpl::configure("cache_dir", get_template_directory() . "/tmp/");
raintpl::configure("path_replace", false);

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
	elseif(count($_POST) && isset($_POST["copyright"]))
		return theme_config_copyright();

	$tpl = new raintpl();
	$tpl->assign(array(
		"templateUri" => get_template_directory_uri(),
		"flickrUserId" => get_option("vs_flickr_user_id")? get_option("vs_flickr_user_id"): "",
		"disqusId" => get_option("vs_flickr_disqus_id")? get_option("vs_flickr_disqus_id"): "",
		"copyright" => get_option("vs_flickr_copyright")? get_option("vs_flickr_copyright"): ""
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
// POST para inserir copyright
function theme_config_copyright() {
	try {
		if(get_option("vs_flickr_copyright") === false)
			add_option("vs_flickr_copyright", $_POST["copyright"]);
		else
			update_option("vs_flickr_copyright", $_POST["copyright"]);
	} catch (Exception $e) {
		echo "Exceção pega: ",  $e->getMessage();
	}
}
// POST para limpar o cache
function theme_clear_cache() {
	try {
		// Limpando o cache dos JSON (arquivos)
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

		// Limpando o banco de dados
		$mycustomposts = get_pages( array( 'post_type' => 'vs_flickr_album', 'number' => 1000) );
		foreach( $mycustomposts as $mypost ) {
			wp_delete_post( $mypost->ID, true);
			// Set to False if you want to send them to Trash.
		}
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


// Requisitando plugins exigidos pelo tema
function required_plugins() {
	/*
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(
		array(
			'name'      => 'Jetpack by WordPress.com',
			'slug'      => 'jetpack',
			'required'  => true
		),
		array(
			'name'      => 'Disqus Comment System',
			'slug'      => 'disqus-comment-system',
			'required'  => false
		)/*,
		array(
			'name'      => 'Contact Form 7',
			'slug'      => 'contact-form-7',
			'required'  => false
		)*/
	);

	/*
	 * Array of configuration settings. Amend each line as needed.
	 *
	 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
	 * strings available, please help us make TGMPA even better by giving us access to these translations or by
	 * sending in a pull-request with .po file(s) with the translations.
	 *
	 * Only uncomment the strings in the config array if you want to customize the strings.
	 */
	$config = array(
		'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'parent_slug'  => 'themes.php',            // Parent menu slug.
		'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
	);

	tgmpa( $plugins, $config );
}


// Exigindo a ativação de módulo do Jetpak
function show_required_jetpack_modules_messages() {
	$messages = array();

	if (class_exists('Jetpack') && !Jetpack::is_module_active("contact-form"))
		$messages[] = 'This theme requires that you enable the "Contact Form" module in <a href="' . admin_url("admin.php?page=jetpack_modules") . '">Jetpack settings</a>.';
	
	if(count($messages)) {
		echo '<div id="message" class="error">';
		foreach($messages as $message)
			echo '<p><strong>'.$message.'</strong></p>';
		echo '</div>';
	}
}



// Adicionando ações ao WP
add_action("admin_menu", "setup_theme_admin_menus");
add_action("admin_enqueue_scripts", "admin_styles");
// add_action("login_enqueue_scripts", "my_admin_theme_style");
add_action("admin_notices", "show_required_jetpack_modules_messages");
add_action('tgmpa_register', 'required_plugins');