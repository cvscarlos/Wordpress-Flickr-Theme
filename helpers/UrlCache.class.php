<?php
/**
* Classe para gerir cache de conteúdos retornados a partir de uma URL
*
* @author: Carlos Vinicius
* @version: 1.0 2014-12-24
* @license: This work is licensed under a Creative Commons Attribution-ShareAlike 4.0 International License.
*/

class UrlCache{
	private $url;
	private $cacheTime;

	public static function getData($url, $cacheMinutes, $cacheDir = null){
		// Verifico o diretório de cache
		$cachePath = self::checkDir($cacheDir);

		return self::getUrlData($cachePath, $url, $cacheMinutes);
	}

	private static function checkDir($dir){
		try {
			// Verificando se foi passado uma string
			if(!is_string($dir))
				$dir = getcwd() . "/cache";

			// Verificando se a string passada é um diretório, caso não seja tenta criar
			if(!is_dir($dir))
				mkdir($dir);

			// Verificando se o diretório tem permissão de escrita, caso não tenha, tenta adicionar
			if(!is_writable($dir))
				chmod($dir, 0755);

			return $dir;
		} catch (Exception $e) {
			header("HTTP/1.0 500 Internal Server Error");
			echo $e->getMessage();
		}
	}

	private static function getUrlData($cachePath, $url, $cacheMinutes){
		// Verifico se o diretório do arquivo existe
		$urlCode = md5($url);
		$fileDir = self::checkDir($cachePath . "/" . $urlCode);

		// Tento obter os dados a partir do diretório
		$cachedFile = null;
		if ($handle = opendir($fileDir)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != ".."){
					if(!$cachedFile && (float) $file > date("Ymd.His", time() - $cacheMinutes * 60))
						$cachedFile = $file;
					else
						unlink($fileDir . "/" . $file);
				}
			}
			closedir($handle);
		}

		// Caso o aquivo não seja localizado
		if(!$cachedFile){
			// Excluo o diretório desse arquivo
			rmdir($fileDir);
			// Crio novo diretório para esse arquivo
			$fileDir = self::checkDir($cachePath . "/" . $urlCode);
			// Obtenho os dados a partir da URL
			$urlData = file_get_contents($url);
			// Salvo os dados em um arquivo
			file_put_contents($fileDir . "/" . date("Ymd.His"), $urlData);
			// Retorno o conteúdo da URL
			return $urlData;
		}

		// Caso o arquivo seja encontrado
		return file_get_contents($fileDir . "/" . $cachedFile);
	}
}