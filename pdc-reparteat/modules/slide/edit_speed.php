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
		$id = $_POST["album"];
		$speed = stripslashes(trim($_POST["speed"]));
		$description = stripslashes(trim($_POST["description"]));
			$q="UPDATE ".preBD."slider_gallery SET SPEED = '" . $speed . "' WHERE ID = '" . $id . "'";
			checkingQuery($connectBD, $q);
  			$msg = "Velocidad de transición de ".$title." modificado.";
			
		disconnectdb($connectBD);
	}
	$location = "Location: ../../index.php?mnu=design&com=slide&tpl=option&opt=section&msg=".utf8_decode($msg);
	header($location);
?>