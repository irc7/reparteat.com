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
		$error = NULL;
		$msg = "";
	
	if (allowed("configuration")) {	
		if ($_FILES["favicon"]["error"] == 0) {
			preg_match("|\.([a-z0-9]{2,4})$|i", $_FILES["favicon"]["name"], $ext);
			$ext[0] = str_replace(".", "", $ext[0]);
			$file = checkingExtFile($ext[0]);
			//pre($file);die();
			if($file["upload"] == 1){
				if ($_FILES["favicon"]["type"] == "image/x-icon") {
					$url = "../../../favicon.ico";
					if(file_exists($url)) {
						unlink($url);	
					}
					move_uploaded_file($_FILES["favicon"]["tmp_name"],$url);
					$msg .= "Icono cambiado correctamente";
				} else {
					$msg .= "No es un archivo correcto, (favicon.ico). ";
				}
			}else{
				$error = "Favicon";
				$msg .= $file["msg"];
			}	
			
		} else {
			$msg .= "No ha seleccionado ningún archivo";
		}
		disconnectdb($connectBD);
		$location = "Location: ../../index.php?mnu=configuration&com=configuration&tpl=favicon&msg=".utf8_decode($msg);
		header($location);
	}else{
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
?>