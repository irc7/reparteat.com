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
			$image_lr = trim($_POST["image_lr"]);
			$image_c = trim($_POST["image_c"]);
			if (allowed("blog") != 1) {
				$msg = "No tiene permisos para realizar esta operación. Usuario desconectado";
			}else if (!is_numeric($image_lr)) {
				$msg = "Ancho l/r debe ser un número";
			}else if (!is_numeric($image_c)) {
				$msg = "Ancho c debe ser un número";
			}else {
				$q = "UPDATE ".preBD."articles_sections SET IMAGE_LR='" . $image_lr ."' WHERE ID ='". $section . "'";
				checkingQuery($connectBD, $q);
				
				$q = "UPDATE ".preBD."articles_sections SET IMAGE_C='" . $image_c . "' WHERE ID = '" . $section . "'";
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