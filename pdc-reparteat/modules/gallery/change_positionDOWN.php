<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	
	$mnu = $_GET["mnu"];
	if (allowed($mnu)) {
		
		$mnu = $_GET["mnu"];
		$record = $_GET["record"];
		$gallery = $_GET["filtergallery"];
		
		$q = "SELECT POSITION, TITLE FROM ".preBD."images WHERE ID='".$record."' AND IDGALLERY = '" . $gallery . "'";
		$result = checkingQuery($connectBD, $q);
		$row = mysqli_fetch_array($result);
		$position = $row['POSITION'];
		
		$q1 = "UPDATE ".preBD."images SET POSITION='".$position."' WHERE POSITION='".($position + 1)."' AND IDGALLERY = '" . $gallery . "'";
		checkingQuery($connectBD, $q1);
		
		$q2 = "UPDATE ".preBD."images SET POSITION='".($position + 1)."' WHERE ID='".$record."'";
		checkingQuery($connectBD, $q2);
		$msg = "Posición de la imagen <em>".stripslashes($row["TITLE"])."</em> modificada";
		disconnectdb($connectBD);
		
		$location = "Location: ../../index.php?mnu=".$mnu."&com=gallery&tpl=option&filtergallery=".$gallery."&msg=".utf8_decode($msg);
		header($location);
	} else {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
?>