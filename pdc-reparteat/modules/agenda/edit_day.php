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
		$error = NULL;
		$msg = "";
		$id = $_POST["id_day"];
		$dateC = $_POST["date_day"];
		$Title = addslashes(trim($_POST["title"]));
		$Repeat = $_POST["repeat"];
		$dateDay = new DateTime($dateC);
	
		
	
		if ($error == NULL) {
			$q = "UPDATE ".preBD."agenda_days set `TITLE`='".$Title."'";
			$q .= ", `DAY`='".$dateDay->format("d")."'";
			$q .= ", `MONTH`='".$dateDay->format("m")."'";
			$q .= ", `YEAR`='".$dateDay->format("Y")."'";
			$q .= ", `REPEAT`='".$Repeat."'";
			$q .= ", `DATE`='".$dateDay->format("Ymd")."'";
			$q .= " where ID = " . $id;
			
			checkingQuery($connectBD, $q);
			
			if($msg!=NULL) {
				$msg .="<br/>";
			}
			$msg .= $Title . " modificado correctamente";
		} 
		disconnectdb($connectBD);
		$location = "Location: ../../index.php?mnu=".$mnu."&com=agenda&tpl=option&opt=days&day=".$id."&msg=".utf8_decode($msg);
		header($location);
	}else {
		disconnectdb($connectBD);
		$msg = "Se ha producido un error inesperado, vuelva a intertarlo.";
		$location = "Location: ../../index.php?mnu=".$mnu."&com=agenda&tpl=option&opt=days&day=".$id."&msg=".utf8_decode($msg);
		header($location);
	}
	
?>