<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	
	$mnu = trim($_POST["mnu"]);
	$com = trim($_POST["com"]);
	$tpl = trim($_POST["tpl"]);
	if (allowed($mnu)) {
		if ($_POST) {
			$dest = intval($_POST["dest"]);
			$dest1 = intval($_POST["dest1"]);
			$dest2 = intval($_POST["dest2"]);
			$numNews = intval($_POST["numNews"]);
			$numSts = intval($_POST["numSts"]);

			$q = "UPDATE `".preBD."home` SET 
					`PRINCIPAL` = ".$dest.",
					`DESTACADA1` = ".$dest1.",
					`DESTACADA2` = ".$dest2.",
					`NUMNEWS` = ".$numNews.", 
					`NUMSTATISTICS` = ".$numSts." 
					WHERE ID = 1";
			checkingQuery($connectBD, $q);
			
			disconnectdb($connectBD);
		}
		$msg = "Configuración de la Home guardada correctamente.";
		$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=".$tpl."&msg=".utf8_decode($msg);
		header($location);
	} else {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
?>
