<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	
	$mnu = trim($_GET["mnu"]);
	$com = trim($_GET["com"]);
	$tpl = trim($_GET["tpl"]);
	$opt = trim($_GET["opt"]);
	if (!allowed($mnu)) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}	
	if(isset($_GET["id"]) && intval($_GET["id"]) != 0) {	
		$id = intval($_GET["id"]);
		
		$q = "select NAME from ".preBD."cm_specialities WHERE ID = '" . $id . "'";
		$result = checkingQuery($connectBD, $q);
		$row = mysqli_fetch_object($result);
		
		$q = "DELETE FROM ".preBD."cm_specialities WHERE ID = '" . $id . "'";
		checkingQuery($connectBD, $q);
			 
		$q = "DELETE FROM ".preBD."cm_ds WHERE IDSPECIALITY = '" . $id ."'";
		checkingQuery($connectBD, $q);
			
			
		$msg = "Especialidad " . $row->NAME." borrada correctamente.";
		disconnectdb($connectBD);
		
	}else {
		$msg = "La especialidad que intenta eliminar no existe.";
	}
	$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=".$tpl."&opt=".$opt."&msg=".utf8_decode($msg);
	header($location);
?>