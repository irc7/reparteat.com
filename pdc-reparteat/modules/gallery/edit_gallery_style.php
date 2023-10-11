<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	$V_PHP = explode(".", phpversion());
	
	$mnu = $_POST["mnu"];
	if (allowed($mnu)) {
		if ($_POST) {
				$mnu = $_POST["mnu"];
				$gallery = $_POST["gallery"];
				
				$ancho_gal = $_POST["ancho_gal"];
				$alto_gal = $_POST["alto_gal"];
				$ancho_image = $_POST["ancho_image"];
				$ancho_min_image = $_POST["ancho_min_image"];
				$alto_min_image = $_POST["alto_min_image"];
				$padding = $_POST["padding"];
				$margin = $_POST["margin"];
				$bordes = $_POST["bordes"];
				$bordes_rad = $_POST["bordes_rad"];
				$fondo = $_POST["fondo"];
				
				$q = "UPDATE `".preBD."images_gallery_style` SET";
				$q.= " `WIDTH_BOX`='" . $ancho_gal;
				$q.= "', `HEIGHT_BOX`='" . $alto_gal;
				$q.= "', `WIDTH_IMAGE`='".$ancho_image;
				$q.= "', `WIDTH_THUMB`='".$ancho_min_image;
				$q.= "', `HEIGHT_THUMB`='".$alto_min_image;
				$q.= "', `PADDING`='".$padding;
				$q.= "', `MARGIN`='".$margin;
				$q.= "', `BORDER`='".$bordes;
				$q.= "', `BORDER_RADIUS`='".$bordes_rad;
				$q.= "', `BACKGROUND`='".$fondo;
				$q.="' WHERE IDGALLERY = " . $gallery;
					
				if(checkingQuery($connectBD, $q)) {
					$msg = "Estilos cambiados correctamente";
				}else {
					$msg = "Se ha producido un error al guardar los estilos";
				}	
			
			disconnectdb($connectBD);
			
		}
		$location = "Location: ../../index.php?mnu=".$mnu."&com=gallery&tpl=option&opt=gallery&msg=".utf8_decode($msg);
		header($location);
	} else {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
?>