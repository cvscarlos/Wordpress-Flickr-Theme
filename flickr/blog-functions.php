<?php
// Adicionando classe de cache
include_once get_template_directory() . "/helpers/UrlCache.class.php";


/*
MÉTODOS PARA O FLICKR
*/
// Função para obter a galeira do Flickr do usuário
function get_flickr_albuns() {
	$url = "https://api.flickr.com/services/rest/?method=flickr.photosets.getList&api_key=fc5b65c11b4095f23c57019fd394c95f&user_id=" . get_option("vs_flickr_user_id") . "&page=1&per_page=500&format=json&nojsoncallback=1";
	$json = json_decode(UrlCache::getData($url, 60, get_template_directory() . "/cache"));
	return $json;
}
// Função para obter os detalhes da galeira
function get_flickr_album_info($ps_id) {
	$url = "https://api.flickr.com/services/rest/?method=flickr.photosets.getInfo&api_key=fc5b65c11b4095f23c57019fd394c95f&photoset_id=" . $ps_id . "&format=json&nojsoncallback=1";
	$albumInfo = json_decode(UrlCache::getData($url, 60, get_template_directory() . "/cache"));
	return $albumInfo;
}
// Função para obter os detalhes da galeira
function get_flickr_album($ps_id) {
	$url = "https://api.flickr.com/services/rest/?method=flickr.photosets.getPhotos&api_key=fc5b65c11b4095f23c57019fd394c95f&photoset_id=" . $ps_id . "&per_page=500&format=json&nojsoncallback=1";
	$json = json_decode(UrlCache::getData($url, 60, get_template_directory() . "/cache"));
	return $json;
}
// Verifica se o álbum esta disponível no Flickr
function check_album_post($albumInfo) {
	if($albumInfo->stat == "ok")
		$post = vs_flickr_post($albumInfo, get_the_ID());
	else
		$post = vs_flickr_post($albumInfo, get_the_ID(), "draft");
}
// Adiciona ou atualiza um post existente
function vs_flickr_post($albumInfo, $id = 0, $postStatus = "publish") {
	$post = array(
		"post_title"		=> $albumInfo->photoset->title->_content,
		"post_content"		=> $albumInfo->photoset->description->_content,
		"post_date"			=> date("Y-m-d H:i:s", $albumInfo->photoset->date_create),
		"post_type"			=> "vs_flickr_album",
		"post_status"		=> $postStatus
	);

	if($id)
		$post["ID"] = $id;

	return wp_insert_post($post);
}


/*
REDIRECIONAMENTO
*/
// Redireciona quando é um álbum
function vs_flickr_album_redirect(){
	$args = array(
		"meta_key"		=> "vsFlickrAlbumId",
		"meta_value"	=> $_GET["ps"],
		"post_type"		=> "vs_flickr_album",
		"post_status"	=> array("publish", "draft")
	);
	$query = new WP_Query($args);

	$albumInfo = get_flickr_album_info($_GET["ps"]);

	// Criando post quando ele não existe mas esta acessível no Flickr
	if(!$query->have_posts() && $albumInfo->stat == "ok"){
		$post = vs_flickr_post($albumInfo);
		add_post_meta($post, 'vsFlickrAlbumId', $albumInfo->photoset->id, true) || update_post_meta($post, 'vsFlickrAlbumId', $albumInfo->photoset->id);

		wp_redirect(get_permalink($post), 301);
		exit;
	}
	// Caso o post exista, esteja como rascunho e o Flickr tenha retornado sucesso
	elseif($query->have_posts() && $query->post->post_status == "draft" && $albumInfo->stat == "ok"){
		vs_flickr_post($albumInfo, $query->post->ID);
		wp_redirect(get_permalink($query->post->ID), 301);
		exit;
	}
	// Caso o post já exista
	elseif ($query->have_posts()) {
		wp_redirect(get_permalink($query->post->ID), 301);
		exit;
	}
}
// Redireciona quando é uma foto
function vs_flickr_photo_redirect(){
	var_dump("foto /o/");
	exit;

	$args = array(
		"meta_key"		=> "vsFlickrPhotoId",
		"meta_value"	=> $_GET["p"],
		"post_type"		=> "vs_flickr_photo",
		"post_status"	=> array("publish", "draft")
	);
	$query = new WP_Query($args);

	$albumInfo = get_flickr_photo_info($_GET["p"]);

	// Criando post quando ele não existe mas esta acessível no Flickr
	if(!$query->have_posts() && $albumInfo->stat == "ok"){
		$post = vs_flickr_post($albumInfo);
		add_post_meta($post, 'vsFlickrPhotoId', $albumInfo->photoset->id, true) || update_post_meta($post, 'vsFlickrPhotoId', $albumInfo->photoset->id);

		wp_redirect(get_permalink($post), 301);
		exit;
	}
	// Caso o post exista, esteja como rascunho e o Flickr tenha retornado sucesso
	elseif($query->have_posts() && $query->post->post_status == "draft" && $albumInfo->stat == "ok"){
		vs_flickr_post($albumInfo, $query->post->ID);
		wp_redirect(get_permalink($query->post->ID), 301);
		exit;
	}
	// Caso o post já exista
	elseif ($query->have_posts()) {
		wp_redirect(get_permalink($query->post->ID), 301);
		exit;
	}
}
// Redirecionando quando esta em "_r"
function vs_flickr_redirect(){
	$site_path = parse_url(get_site_url(), PHP_URL_PATH);

	if(!(!is_null($site_path) && $site_path != "/"))
		$site_path = "";
	$site_path = preg_replace('/\/$/i', "", $site_path);

	$fragment = substr(str_replace($site_path, "", $_SERVER["REQUEST_URI"]), 0, 4);
	if($fragment === "/_r/"){
		if(isset($_GET["ps"]))
			vs_flickr_album_redirect();
		elseif(isset($_GET["p"]))
			vs_flickr_photo_redirect();
	}
}
// fazendo uma simples verificação se é uma url de redirecionamento e então aplica a função de redirect
if(strpos($_SERVER["REQUEST_URI"], "/_r/") !== false)
	vs_flickr_redirect();