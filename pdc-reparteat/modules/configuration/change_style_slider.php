<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	if (allowed("configuration")) {	
		if ($_POST) {
			
			$alto = $_POST["alto"];
			$ancho = $_POST["ancho"];
			$alto_2 = $_POST["alto_2"];
			$ancho_2 = $_POST["ancho_2"];		

			$var = 0;
			
			if($ancho_2 != $ancho){
				$q = "ALTER TABLE ".preBD."slider_gallery MODIFY COLUMN WIDTH int(4) NOT NULL DEFAULT '".$ancho."';";
				if (!checkingQuery($connectBD, $q)) {
					
				}
				$var = 1;
			}
			
			if($alto_2 != $alto){
				$q = "ALTER TABLE ".preBD."slider_gallery MODIFY COLUMN HEIGHT int(4) NOT NULL DEFAULT '".$alto."';";
				if (!checkingQuery($connectBD, $q)) {
					
				}
				$var = 1;
			}

			if($var == 1){
				$msg = "Valores modificados correctamente.";		
			}elseif($var == 0){
				$msg = "Los valores introducidos tienen los mismos valores que los guardados actualmente.";
			}
			disconnectdb($connectBD);
		}
		$location = "Location: ../../index.php?mnu=configuration&com=configuration&tpl=slide&msg=".utf8_decode($msg);
		header($location);
	}else{
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
?>