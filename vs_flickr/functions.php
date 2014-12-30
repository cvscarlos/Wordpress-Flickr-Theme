<?php
// FUNÇÕES CUSTOMIZADAS PARA O ADMIN
if ( is_admin() )
	include_once get_template_directory() . "/admin/admin-functions.php";
// FUNÇÕES CUSTOMIZADAS PARA O BLOG
else
	include_once get_template_directory() . "/blog-functions.php";

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
// Post Customizado: Foto do Flickr
function custom_post_flickr_photo() {
	$args = array(
		"public" => true,
		"label"  => "Photos",
		"menu_icon" => get_template_directory_uri() . "/admin/photos16.png",
		"supports" => array("title", "editor", "custom-fields"),
		"hierarchical" => false,
		"rewrite" => array("slug" => "photo")
	);
	register_post_type("vs_flickr_photo", $args);
}

// Adicionando ações ao WP
add_action("init", "custom_post_flickr_album");
add_action("init", "custom_post_flickr_photo");