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
	
	$mnu = trim($_GET["mnu"]);
	if (!allowed($mnu)) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}	
	
	if ($_GET["id"]) {
		$msg = "";
		$id = $_GET["id"];
		$action = $_GET["action"];
		$title = utf8_encode($_GET["title"]);
		
		
		$q = "UPDATE ".preBD."agenda_days set";
		if($action == "unpublish") {
			$q .= " `STATUS`='0'";	
		} else {
			$q .= " `STATUS`='1'";
		}
		$q .= " where ID = " . $id;
		
		checkingQuery($connectBD, $q);
		$id = mysqli_insert_id($connectBD); 
		disconnectdb($connectBD);
		$msg .= "<em>" . $title . "</em> modificado correctamente";
		$location = "Location: ../../index.php?mnu=".$mnu."&com=agenda&tpl=option&opt=days&day=".$id."&msg=".utf8_decode($msg);
		header($location);
	}else {
		disconnectdb($connectBD);
		$msg = "Se ha producido un error inesperado, vuelva a intertarlo.";
		$location = "Location: ../../index.php?mnu=".$mnu."&com=agenda&tpl=option&opt=days&msg=".utf8_decode($msg);
		header($location);
	}
	
	
?>