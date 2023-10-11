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
			$thumb_width = trim($_POST["thumb_width"]);
			$thumb_height = trim($_POST["thumb_height"]);
			if (!is_numeric($thumb_width)) {
				$msg = "Ancho en píxeles debe ser un número";
			}			
			else if (!is_numeric($thumb_height)) {
				$msg = "Alto en píxeles debe ser un número";
			}			
			else {
				$q = "UPDATE ".preBD."articles_sections SET THUMB_WIDTH='" .$thumb_width . "' WHERE ID='" . $section . "'";
				checkingQuery($connectBD, $q);
				
				$q1 = "UPDATE ".preBD."articles_sections SET THUMB_HEIGHT = '" . $thumb_height . "' WHERE ID='" . $section. "'";
				checkingQuery($connectBD, $q1);
				
				$msg = "Sección modificada correctamente. Los cambios se aplicarán desde este momento";
				
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