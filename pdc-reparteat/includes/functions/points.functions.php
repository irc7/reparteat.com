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
?>