<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	

	if (!allowed("design")) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}	

	if(isset($_GET["agenda"]) && intval($_GET["agenda"]) != 0) {	
		$agenda = intval($_GET["agenda"]);
		
		$q = "select TITLE from ".preBD."agenda_sections WHERE ID = '" . $agenda . "'";
		
		$result = checkingQuery($connectBD, $q);
		$row_agenda = mysqli_fetch_object($result);
		
		$q = "select count(*) as total from ".preBD."agenda WHERE ID_AGENDA_SECTION = '" . $agenda . "'";
		$result = checkingQuery($connectBD, $q);
		
		$events = mysqli_fetch_object($result);
		if($events->total > 0){
			$msg = "Debe vaciar la agenda para poder borrarla";	
		} else {
			$q = "DELETE FROM ".preBD."agenda_sections WHERE ID = '" . $agenda . "'";
			checkingQuery($connectBD, $q);
						
			$msg = "Agenda " . $row_agenda->TITLE." borrada correctamente";
		}
		disconnectdb($connectBD);
		
	}else {
		$msg = "No existe la referencia de la agenda que intenta eliminar.";
	}
	$location = "Location: ../../index.php?mnu=content&com=agenda&tpl=option&opt=section&msg=".utf8_decode($msg);
	header($location);
?>