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
	
	if (!allowed("design")) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}	
	if ($_POST) {
			$album = trim($_POST["album"]);
			$width = trim($_POST["width"]);
			$height = trim($_POST["height"]);
				
			$q = "UPDATE ".preBD."slider_gallery SET WIDTH='" .$width . "', HEIGHT='" .$height . "' WHERE ID='" . $album . "'";
			checkingQuery($connectBD, $q);
  			$msg = "Tamaño de las imágenes modificado correctamente. Los cambios se aplicarán desde este momento";
			
		disconnectdb($connectBD);
	}
	$location = "Location: ../../index.php?mnu=design&com=slide&tpl=option&opt=section&msg=".utf8_decode($msg);
	header($location);
?>