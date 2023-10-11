<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	if (allowed("configuration")) {	
		if ($_POST) {
			$ancho_gal = $_POST["ancho_gal"];
			$ancho_gal_2 = $_POST["ancho_gal_2"];
			$alto_gal = $_POST["alto_gal"];
			$alto_gal_2 = $_POST["alto_gal_2"];	
			$ancho_image = $_POST["ancho_image"];
			$ancho_image_2 = $_POST["ancho_image_2"];		
			$ancho_min_image = $_POST["ancho_min_image"];
			$ancho_min_image_2 = $_POST["ancho_min_image_2"];		
			$alto_min_image = $_POST["alto_min_image"];
			$alto_min_image_2 = $_POST["alto_min_image_2"];
			$paddingg = $_POST["paddingg"];
			$paddingg_2 = $_POST["paddingg_2"];	
			$marging = $_POST["marging"];
			$marging_2 = $_POST["marging_2"];			
			$bordes = $_POST["bordes"];
			$bordes_2 = $_POST["bordes_2"];	
			$bordes_rad = $_POST["bordes_rad"];
			$bordes_rad_2 = $_POST["bordes_rad_2"];			
			$fondo = $_POST["fondo"];
			$fondo_2 = $_POST["fondo_2"];			

			$var = 0;
			
			if($ancho_gal_2 != $ancho_gal){
				$q = "ALTER TABLE ".preBD."images_gallery_style MODIFY COLUMN WIDTH_BOX int(4) NOT NULL DEFAULT '".$ancho_gal."';";
				if (!$res = checkingQuery($connectBD, $q)) {
					
				}elseif($res && $var == 0) {
					$var = 1;
				}
			}
			
			if($alto_gal_2 != $alto_gal){
				$q = "ALTER TABLE ".preBD."images_gallery_style MODIFY COLUMN HEIGHT_BOX int(4) NOT NULL DEFAULT '".$alto_gal."';";
				if (!$res = checkingQuery($connectBD, $q)) {
					
				}elseif($res && $var == 0) {
					$var = 1;
				}
			}
			
			if($ancho_image_2 != $ancho_image){
				$q = "ALTER TABLE ".preBD."images_gallery_style MODIFY COLUMN WIDTH_IMAGE int(4) NOT NULL DEFAULT '".$ancho_image."';";
				if (!$res = checkingQuery($connectBD, $q)) {
					
				}elseif($res && $var == 0) {
					$var = 1;
				}
			}
			
			if($ancho_min_image_2 != $ancho_min_image){
				$q = "ALTER TABLE ".preBD."images_gallery_style MODIFY COLUMN WIDTH_THUMB int(4) NOT NULL DEFAULT '".$ancho_min_image."';";
				if (!$res = checkingQuery($connectBD, $q)) {
					
				}elseif($res && $var == 0) {
					$var = 1;
				}
			}
			
			if($alto_min_image_2 != $alto_min_image){
				$q = "ALTER TABLE ".preBD."images_gallery_style MODIFY COLUMN HEIGHT_THUMB int(4) NOT NULL DEFAULT '".$alto_min_image."';";
				if (!$res = checkingQuery($connectBD, $q)) {
					
				}elseif($res && $var == 0) {
					$var = 1;
				}
			}
			
			if($paddingg_2 != $paddingg){
				$q = "ALTER TABLE ".preBD."images_gallery_style MODIFY COLUMN PADDING int(4) NOT NULL DEFAULT '".$paddingg."';";
				if (!$res = checkingQuery($connectBD, $q)) {
					
				}elseif($res && $var == 0) {
					$var = 1;
				}
			}						
			
			if($marging_2 != $marging){
				$q = "ALTER TABLE ".preBD."images_gallery_style MODIFY COLUMN MARGIN int(4) NOT NULL DEFAULT '".$marging."';";
				if (!$res = checkingQuery($connectBD, $q)) {
					
				}elseif($res && $var == 0) {
					$var = 1;
				}
			}						
			
			if($bordes_2 != $bordes){
				$q = "ALTER TABLE ".preBD."images_gallery_style MODIFY COLUMN BORDER int(4) NOT NULL DEFAULT '".$bordes."';";
				if (!$res = checkingQuery($connectBD, $q)) {
					
				}elseif($res && $var == 0) {
					$var = 1;
				}
			}						
			
			if($bordes_rad_2 != $bordes_rad){
				$q = "ALTER TABLE ".preBD."images_gallery_style MODIFY COLUMN BORDER_RADIUS int(4) NOT NULL DEFAULT '".$bordes_rad."';";
				if (!$res = checkingQuery($connectBD, $q)) {
					
				}elseif($res && $var == 0) {
					$var = 1;
				}
			}						
			
			if($fondo_2 != $fondo){
				$q = "ALTER TABLE ".preBD."images_gallery_style MODIFY COLUMN BACKGROUND varchar(40) NOT NULL DEFAULT '".$fondo."';";
				if (!$res = checkingQuery($connectBD, $q)) {
					
				}elseif($res && $var == 0) {
					$var = 1;
				}
			}						
									
			if($var == 1){
				$msg = "Valores modificados correctamente.";		
			}elseif($var == 0){
				$msg = "Los valores introducidos tienen los mismos valores que los guardados actualmente.";
			}	
			disconnectdb($connectBD);
		}
		
		$location = "Location: ../../index.php?mnu=configuration&com=configuration&tpl=gallery&msg=".utf8_decode($msg);
		header($location);
	}else{
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
?>