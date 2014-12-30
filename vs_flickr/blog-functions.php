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
	$json = json_decode(UrlCache::getData($url, 60, get_template_directory() . "/cache"));
	return $json;
}// Função para obter os detalhes da foto
function get_flickr_photo_info($p_id) {
	$url = "https://api.flickr.com/services/rest/?method=flickr.photos.getInfo&api_key=fc5b65c11b4095f23c57019fd394c95f&photo_id=" . $p_id . "&format=json&nojsoncallback=1";
	$json = json_decode(UrlCache::getData($url, 60, get_template_directory() . "/cache"));
	return $json;
}
// Função para obter os detalhes da galeira
function get_flickr_album($ps_id) {
	$url = "https://api.flickr.com/services/rest/?method=flickr.photosets.getPhotos&api_key=fc5b65c11b4095f23c57019fd394c95f&photoset_id=" . $ps_id . "&per_page=500&format=json&nojsoncallback=1";
	$json = json_decode(UrlCache::getData($url, 60, get_template_directory() . "/cache"));
	return $json;
}
// Verifica se o álbum esta disponível no Flickr
function check_album_post($json) {
	if($json->stat == "ok")
		$post = vs_flickr_post($isPhoto, $json->photoset->title->_content, $json->photoset->description->_content, $json->photoset->date_create, get_the_ID());
	else
		$post = vs_flickr_post($isPhoto, $json->photoset->title->_content, $json->photoset->description->_content, $json->photoset->date_create, get_the_ID(), 0, "draft");
}
// Verifica se o álbum esta disponível no Flickr
function check_photo_post($json) {
	if($json->stat == "ok")
		$post = vs_flickr_post($isPhoto, $json->photo->title->_content, $json->photo->description->_content, $json->photo->dates->posted, get_the_ID());
	else
		$post = vs_flickr_post($isPhoto, $json->photo->title->_content, $json->photo->description->_content, $json->photo->dates->posted, get_the_ID(), 0, "draft");
}
// Adiciona ou atualiza um post existente
function vs_flickr_post($isPhoto, $title, $desc, $time, $id = 0, $parent = 0, $postStatus = "publish") {
	$post = array(
		"post_title"		=> $title,
		"post_content"		=> $desc,
		"post_date"			=> date("Y-m-d H:i:s", $time),
		"post_type"			=> $isPhoto? "vs_flickr_photo" : "vs_flickr_album",
		"post_status"		=> $postStatus
	);

	// Id do post, caso seja um update
	if($id)
		$post["ID"] = $id;
	// Id do pai, caso seja um filho
	if($parent)
		$post["post_parent"] = $parent;

	return wp_insert_post($post);
}


/*
REDIRECIONAMENTO
*/
// Buscando os post de album ou foto
function vs_flickr_post_query($isPhoto = false) {
	$args = array(
		"meta_key"		=> $isPhoto? "vsFlickrPhotoId"	: "vsFlickrAlbumId",
		"meta_value"	=> $isPhoto? $_GET["p"]			: $_GET["ps"],
		"post_type"		=> $isPhoto? "vs_flickr_photo"	: "vs_flickr_album",
		"post_status"	=> array("publish", "draft")
	);
	return new WP_Query($args);
}
// Verifica se o post existe, cria e redireciona
function vs_flickr_post_query_manage($isPhoto, $json, $id, $query, $parent = 0) {
	$title =	$isPhoto? $json->photo->title->_content			: $json->photoset->title->_content;
	$desc =		$isPhoto? $json->photo->description->_content	: $json->photoset->description->_content;
	$time =		$isPhoto? $json->photo->dates->posted			: $json->photoset->date_create;

	// Criando post quando ele não existe mas esta acessível no Flickr
	if(!$query->have_posts() && $json->stat == "ok"){
		$post = vs_flickr_post($isPhoto, $title, $desc, $time, 0, $parent);
		add_post_meta($post, ($isPhoto? "vsFlickrPhotoId" : "vsFlickrAlbumId"), $id, true) || update_post_meta($post, ($isPhoto? "vsFlickrPhotoId" : "vsFlickrAlbumId"), $id);
		wp_redirect(get_permalink($post), 301);
		exit;
	}
	// Caso o post exista, esteja como rascunho e o Flickr tenha retornado sucesso
	elseif($query->have_posts() && $query->post->post_status == "draft" && $json->stat == "ok"){
		vs_flickr_post($isPhoto, $title, $desc, $time, $query->post->ID, $parent);
		wp_redirect(get_permalink($query->post->ID), 301);
		exit;
	}
	// Caso o post já exista
	elseif ($query->have_posts()) {
		wp_redirect(get_permalink($query->post->ID), 301);
		exit;
	}
}
// Redireciona quando é um álbum
function vs_flickr_album_redirect(){
	$query = vs_flickr_post_query();
	$albumInfo = get_flickr_album_info($_GET["ps"]);
	
	vs_flickr_post_query_manage(false, $albumInfo, $albumInfo->photoset->id, $query);
}
// Redireciona quando é uma foto
function vs_flickr_photo_redirect(){
	$query = vs_flickr_post_query(true);
	$photoInfo = get_flickr_photo_info($_GET["p"]);

	vs_flickr_post_query_manage(true, $photoInfo, $photoInfo->photo->id, $query);
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