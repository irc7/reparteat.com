<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	
	if (!allowed("configuration")) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}	
	if (isset($_GET["reg"])) {
		$id = abs(intval($_GET["reg"]));
		
		$q = "DELETE FROM ".preBD."users_log WHERE ID='".$id."'";
		checkingQuery($connectBD, $q);
		
		$msg = "Registro número ".$id." eliminado correctamente.";
		
		disconnectdb($connectBD);
	} else {
		$msg = "Ha ocurrido un error con el registro deseado, si el problema persiste, póngase en contacto con el administrador.";
	}
	$location = "Location: ../../index.php?mnu=configuration&com=user&tpl=list&opt=log&msg=".utf8_decode($msg);
	header($location);
?>