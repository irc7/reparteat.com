<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}

	if (!allowed("content")) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}	
	
	$record = $_GET["record"];
	$gallery = $_GET["filtergallery"];
	
	$q = "SELECT POSITION, TITLE FROM ".preBD."videos WHERE ID='".$record."' AND IDGALLERY = '" . $gallery . "'";
	$result = checkingQuery($connectBD, $q);
	
	$row = mysqli_fetch_array($result);
	$position = $row['POSITION'];
	
	$q1 = "UPDATE ".preBD."videos SET POSITION='".$position."' WHERE POSITION='".($position - 1)."' AND IDGALLERY = '" . $gallery . "'";
	checkingQuery($connectBD, $q1);
	
	$q2 = "UPDATE ".preBD."videos SET POSITION='".($position - 1)."' WHERE ID='".$record."'";
	checkingQuery($connectBD, $q2);
	
	$msg = "Posición del vídeo ".stripslashes($row["TITLE"])." modificado.";
	disconnectdb($connectBD);
	
	$location = "Location: ../../index.php?mnu=content&com=videos&tpl=option&filtergallery=".$gallery."&msg=".utf8_decode($msg);
	header($location);
?>