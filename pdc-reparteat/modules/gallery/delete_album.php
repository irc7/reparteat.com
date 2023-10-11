<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	
	$mnu = $_GET["mnu"];
	if (allowed($mnu)) {
		if (isset($_GET["album"])) {
			
			$Album = $_GET["album"];
			$mnu = $_GET["mnu"];

			$q1 = "SELECT * FROM ".preBD."users WHERE Login='".$_SESSION[PDCLOG]['Login']."'";
			
			$result1 = checkingQuery($connectBD, $q1);
			$row1 = mysqli_fetch_array($result1);
			$pwdhash1 = $row1['Pwd'];
				
				$q = "select TITLE from ".preBD."images_gallery_sections WHERE ID = '" . $Album . "'";
				
				$result = checkingQuery($connectBD, $q);
				$row = mysqli_fetch_object($result);
				
				$q = "DELETE FROM ".preBD."images_gallery_sections WHERE ID = '" . $Album . "'";
				checkingQuery($connectBD, $q);
				
				$msg = $row->TITLE . " borrado correctamente";
			
		}
		disconnectdb($connectBD);
		$location = "Location: ../../index.php?mnu=".$mnu."&com=gallery&tpl=option&opt=album&msg=".utf8_decode($msg);
		header($location);
	} else {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
?>