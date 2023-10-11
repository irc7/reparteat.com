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
		$dateC = trim($_POST["date_day"]);
		$Title = mysqli_real_escape_string($connectBD,trim($_POST["title"]));
		$Repeat = $_POST["repeat"];
		
		$dateDay = new DateTime($dateC);
		 
		
		if ($error == NULL) {
			$q = "INSERT INTO ".preBD."agenda_days (`ID` ,`TITLE` ,`DAY` ,`MONTH` ,`YEAR` ,`STATUS` ,`REPEAT`, `DATE`) 
					VALUES 
				(NULL ,  '".$Title."',  '".$dateDay->format("d")."',  '".$dateDay->format("m")."',  '".$dateDay->format("Y")."',  '1',  '".$Repeat."',  '".$dateDay->format("Ymd")."')";
			echo $q;
			checkingQuery($connectBD, $q);
			
			$id = mysqli_insert_id($connectBD); 
			
			$msg .= "Festividad <em>".$Title."</em> creada correctamente.";
			disconnectdb($connectBD);
			$location = "Location: ../../index.php?mnu=".$mnu."&com=agenda&tpl=option&opt=days&day=".$id."&msg=".utf8_decode($msg);
			header($location);
		}else{
			$msg .= "Se ha producido un error inesperado, por favor vuelva a intentarlo.";
			disconnectdb($connectBD);
			$location = "Location: ../../index.php?mnu=".$mnu."&com=agenda&tpl=option&msg=".utf8_decode($msg);
			header($location);
		}
	}else {
		disconnectdb($connectBD);
		$msg = "Se ha producido un error inesperado, vuelva a intertarlo.";
		$location = "Location: ../../index.php?mnu=".$mnu."&com=agenda&tpl=option&opt=days&msg=".utf8_decode($msg);
		header($location);
	}
	
?>