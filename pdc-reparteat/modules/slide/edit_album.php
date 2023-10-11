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
		$title = stripslashes(trim($_POST["title"]));
		$description = stripslashes(trim($_POST["description"]));
			$q="UPDATE ".preBD."slider_gallery SET TITLE = '" . $title . "', DESCRIPTION = '". $description . "' WHERE ID = '" . $id . "'";
			checkingQuery($connectBD, $q);
  			
			$msg = "Banner ".$title." modificado.";
			
		disconnectdb($connectBD);
	}
	$location = "Location: ../../index.php?mnu=design&com=slide&tpl=option&opt=section&msg=".utf8_decode($msg);
	header($location);
?>