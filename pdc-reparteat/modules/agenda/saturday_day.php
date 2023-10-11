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
	$mnu = trim($_POST["mnu"]);
	if (!allowed($mnu)) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}

	$q = "UPDATE `".preBD."configuration` SET";
	if(isset($_POST["checkingSaturday"]) && intval($_POST["checkingSaturday"]) == 1) {
		$value = 1;
		$q.= " `VALUE`='1'";
	}else{
		$value = 0;
		$q.= " `VALUE`='0'";
	}
	$q.= " WHERE ID =11";
	if(!checkingQuery($connectBD, $q)) {
		disconnectdb($connectBD);
		$msg = "Se ha pruducido un error inesperado, vuelva a intentarlo gracias.";
		$location = "Location: ../../index.php?mnu=".$mnu."&com=agenda&tpl=option&opt=days&msg=".utf8_decode($msg);
		header($location);
	} else {
		disconnectdb($connectBD);
		$msg = "Los sábados se mostraran como ";
		if($value == 0) {
			$msg .= "días normales.";
		}else {
			$msg .= "días festivos.";
		}
		$location = "Location: ../../index.php?mnu=".$mnu."&com=agenda&tpl=option&opt=days&msg=".utf8_decode($msg);
		header($location);
	}