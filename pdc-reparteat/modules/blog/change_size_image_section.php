<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	$V_PHP = explode(".", phpversion());
	if($V_PHP[0]>=5){
		date_default_timezone_set("Europe/Paris");
	}
	
	
	if (allowed("blog")) {
		if ($_POST) {
			$section = trim($_POST["section"]);
			$width_image = trim($_POST["width_image"]);
			$height_image = trim($_POST["height_image"]);
			if (allowed("blog") != 1) {
				$msg = "No tiene permisos para realizar esta operación. Usuario desconectado";
			}else if (!is_numeric($width_image)) {
				$msg = "Ancho imagen debe ser un número";
			}else if (!is_numeric($height_image)) {
				$msg = "Alto imagen debe ser un número";
			}else {
				$q = "UPDATE ".preBD."articles_sections SET WIDTH_IMAGE='" . $width_image ."' WHERE ID ='". $section . "'";
				checkingQuery($connectBD, $q);
				
				$q = "UPDATE ".preBD."articles_sections SET HEIGHT_IMAGE='" . $height_image . "' WHERE ID = '" . $section . "'";
				checkingQuery($connectBD, $q);
				
				$msg = "Sección modificada correctamente";

			}
			
		}
		disconnectdb($connectBD);
		$location = "Location: ../../index.php?mnu=blog&com=blog&tpl=option&opt=section&msg=".utf8_decode($msg);
		header($location);
	} else {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
?>