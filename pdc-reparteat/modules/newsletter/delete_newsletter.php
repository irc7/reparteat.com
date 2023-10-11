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
			
			$record = trim($_POST["record"]);
			
			$q = "DELETE FROM ".preBD."newsletter WHERE ID = '" . $record. "'";
			checkingQuery($connectBD, $q);
			
			
			//BORRADO DE COLA O ERRORES AL ENVIAR
			$q = "DELETE FROM ".preBD."newsletter_trail WHERE IDNEWSLETTER = '" . $record. "'";
			checkingQuery($connectBD, $q);
			
			
			//BORRADO DE COLA O ERRORES AL ENVIAR
			$q = "DELETE FROM ".preBD."newsletter_mailsend WHERE IDNEWSLETTER = '" . $record. "'";
			checkingQuery($connectBD, $q);
			
			
			//BORRADO DE ESTADISTICAS
			$q = "DELETE FROM ".preBD."statistics_newsletter_open WHERE IDNEWSLETTER = '" . $record. "'";
			checkingQuery($connectBD, $q);
			
			
			$q = "DELETE FROM ".preBD."statistics_newsletter WHERE IDNEWSLETTER = '" . $record. "'";
			checkingQuery($connectBD, $q);
			
			
			$msg = "Boletín eliminado correctamente.";	
			disconnectdb($connectBD);
		}
		$location = "Location: ../../index.php?mnu=mailing&com=newsletter&tpl=option&msg=".utf8_decode($msg);
		header($location);
	} else {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
?>