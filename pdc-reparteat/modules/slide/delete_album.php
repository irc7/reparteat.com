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
	if(isset($_GET["album"]) && intval($_GET["album"]) != 0) {	
		$album = intval($_GET["album"]);
		
		$q = "select TITLE from ".preBD."slider_gallery WHERE ID = '" . $album . "'";
		$result = checkingQuery($connectBD, $q);
		$row_album = mysqli_fetch_object($result);
		
		$q = "select count(*) as total from ".preBD."slider WHERE IDALBUM = '" . $album . "'";
		
		$result = checkingQuery($connectBD, $q);
		$imgs = mysqli_fetch_object($result);
		if($imgs->total > 0){
			$msg = "Debe vaciar el álbum para poder borrarlo";	
		} else {
			$q = "DELETE FROM ".preBD."slider_gallery WHERE ID = '" . $album . "'";
			checkingQuery($connectBD, $q);
			
			
			$msg = "Banner " . $row_album->TITLE." borrado correctamente";
		}
		disconnectdb($connectBD);
		
	}else {
		$msg = "No existe la referencia de banner que intenta eliminar.";
	}
	$location = "Location: ../../index.php?mnu=design&com=slide&tpl=option&opt=section&msg=".utf8_decode($msg);
	header($location);
?>