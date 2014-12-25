<?php
// Adicionando classe de cache
include_once get_template_directory() . "/helpers/UrlCache.class.php";

// Função para obter a galeira do Flickr do usuário
function get_flickr_albuns() {
	$url = "https://api.flickr.com/services/rest/?method=flickr.photosets.getList&api_key=fc5b65c11b4095f23c57019fd394c95f&user_id=" . get_option("vs_flickr_user_id") . "&page=1&per_page=500&format=json&nojsoncallback=1";
	$json = json_decode(UrlCache::getData($url, 60, get_template_directory() . "/cache"));

	return $json->photosets;
}

// Redirecionando quando esta em "_r"
function flickr_redirect(){
	$site_path = parse_url(get_site_url(), PHP_URL_PATH);

	if(!(!is_null($site_path) && $site_path != "/"))
		$site_path = "";
	$site_path = preg_replace('/\/$/i', "", $site_path);

	$fragment = substr(str_replace($site_path, "", $_SERVER["REQUEST_URI"]), 0, 4);

	if($fragment === "/_r/" && isset($_GET["ps"])){
		var_dump($_GET["ps"]);

		$args = array(
			"meta_key" => "vsFlickrAlbumId",
			"meta_value" => $_GET["ps"],
			"post_type" => "vs_flickr_album"
		);
		$query = new WP_Query($args);

		$url = "https://api.flickr.com/services/rest/?method=flickr.photosets.getInfo&api_key=fc5b65c11b4095f23c57019fd394c95f&photoset_id=" . $_GET["ps"] . "&format=json&nojsoncallback=1";
		$albumInfo = json_decode(UrlCache::getData($url, 60, get_template_directory() . "/cache"));

		// Criando post quando ele não existe mas esta acessível no Flickr
		if(!$query->have_posts() && $albumInfo->stat == "ok"){
			var_dump("vou criar o post: " . $albumInfo->photoset->title->_content);
			$post = wp_insert_post(array(
				"post_title" => $albumInfo->photoset->title->_content,
				"post_content" => ""
			));
			var_dump($post);
		}
		var_dump($query);

		// wp_redirect(get_site_url() . "/uhuuuuu", 301);
		// exit;
	}
}