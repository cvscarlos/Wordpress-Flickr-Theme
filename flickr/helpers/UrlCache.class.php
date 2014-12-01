<?php
/**
* @author: Carlos Vinicius
* @version: 1.0 2014-11-30
* @license: This work is licensed under the Creative Commons Attribution 3.0 Unported License. To view a copy of this license, visit http://creativecommons.org/licenses/by/3.0/ or send a letter to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
* @description: Classe para gerir cache de conteúdos retornados a partir de uma URL
*
*/

class UrlCache{
	private $url;
	private $cacheTime;

	public static function getData(string $url, int $cacheMinutes, string $cacheDir = null){

	}

	private function checkDir($dir){
		try {
			// Verificando se foi passado uma string
			if(!is_string($dir))
				$dir = getcwd() . "/cache";

			// Verificando se a string passada é um diretório
			if(!is_dir($dir))
				throw new Exception($dir . ' - Not a dir!');

			// Verificando se o diretório tem permissão de escrita, caso não tenha, tenta adicionar
			if(!is_writable($dir))
				chmod($dir, 0755);

		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}
}
}