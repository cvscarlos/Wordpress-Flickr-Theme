<?php
// FUNÇÕES CUSTOMIZADAS PARA O ADMIN
if ( is_admin() )
	include_once get_template_directory() . "/admin/admin-functions.php";
// FUNÇÕES CUSTOMIZADAS PARA O BLOG
else
	include_once get_template_directory() . "/blog-functions.php";