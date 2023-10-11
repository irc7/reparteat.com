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
	if ($_POST) {
		
		$com = trim($_POST["com"]);
		$tpl = trim($_POST["tpl"]);
		$opt = trim($_POST["opt"]);
		
		$Name = mysqli_real_escape_string($connectBD, trim($_POST["Name"]));
		$Text = mysqli_real_escape_string($connectBD, trim($_POST["Text"]));
		
		$q = "INSERT INTO ".preBD."cm_specialities (NAME, TEXT) VALUES ('" . $Name . "', '" . $Text . "')";
		checkingQuery($connectBD, $q);
		$msg = "Especialidad <em>".trim($_POST["Name"])."</em> creada correctamente.";
		
		$idNew = mysqli_insert_id($connectBD); 
		disconnectdb($connectBD);
	}
	$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=".$tpl."&opt=".$opt."&id=".$idNew."&msg=".utf8_decode($msg);
	header($location);
?>