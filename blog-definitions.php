<?php
$vsf_tpl = array();

// Menu Lateral
$vsf_tpl["menu_args"] = array(
	"theme_location" => "side-menu"
);
// Menu Lateral - Redes Sociais
$vsf_tpl["social_menu_args"] = array(
	"theme_location" => "social-menu"
);

// Título do site
$vsf_tpl["title"] = wp_title('|', true, 'right');
if(!$vsf_tpl["title"])
	$vsf_tpl["title"] = get_bloginfo('name') . " | " . get_bloginfo('description');