<?php	
	
	
	function converMinusc($texto){
		$find = array('Á','É','Í','Ó','Ú','Ñ');
		$replac = array('á','é','í','ó','ú','ñ');
		$text = str_replace ($find, $replac, $texto); 
		$text = strtolower($text);
		return ($text);
	}
	function converMayusc($texto){
		$find = array('á','é','í','ó','ú','ñ');
		$replac = array('Á','É','Í','Ó','Ú','Ñ');
		$text = str_replace ($find, $replac, $texto); 
		$text = strtoupper($text);
		return ($text);
	}
	function conversorSegundosHoras($tiempo_en_segundos) {
		$horas = floor($tiempo_en_segundos / 3600);
		$minutos = floor(($tiempo_en_segundos - ($horas * 3600)) / 60);
		$segundos = $tiempo_en_segundos - ($horas * 3600) - ($minutos * 60);

		return $horas . ':' . $minutos . ":" . $segundos;
	}
?>