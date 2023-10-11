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
	
	if (allowed("mailing")) {
		if ($_POST) {
			$group = trim($_POST["group"]);
			
				$q = "INSERT INTO ".preBD."groups_subscriptions (TITLE) VALUES ('" . $group . "')";
				checkingQuery($connectBD, $q);
				$msg = "Grupo ".$group." creado correctamente";
			
			disconnectdb($connectBD);
		}
		$location = "Location: ../../index.php?mnu=mailing&com=newsletter&tpl=option&opt=groupsuscription&msg=".utf8_decode($msg);
		header($location);
	} else {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
?>