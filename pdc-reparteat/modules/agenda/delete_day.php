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
	if(isset($_GET["id"])) {
		$msg = "";
		$id = $_GET["id"];
		$title = utf8_encode($_GET["title"]);
//BORRADO
		$q = "DELETE FROM ".preBD."agenda_days WHERE ID='".$id."'";
		checkingQuery($connectBD, $q);
		
		$msg = "Festividad <em>".$title."</em> eliminado definitivamente.";
		$location = "Location: ../../index.php?mnu=".$mnu."&com=agenda&tpl=option&opt=days&day=".$id."&msg=".utf8_decode($msg);
		header($location);
	} else {
		disconnectdb($connectBD);
		$msg = "No se ha encontrado la festividad en la base de datos, vuelva a intentarlo gracias.";
		$location = "Location: ../../index.php?mnu=".$mnu."&com=agenda&tpl=option&opt=days&msg=".utf8_decode($msg);
		header($location);
	}
?>	