<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	$record = $_GET["record"];
	$recordxpage = $_GET["recodsperpage"];
	$pagina = $_GET["page"];

	if (!allowed("design")) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}	
	$q = "SELECT IDALBUM, POSITION FROM ".preBD."slider WHERE ID='".$record."'";
	$result = checkingQuery($connectBD, $q);
	
	$row = mysqli_fetch_object($result);
	$position = $row->POSITION;
	$album = $row->IDALBUM;
	
	$q1 = "UPDATE ".preBD."slider SET POSITION= (POSITION-1) WHERE POSITION>'".$position."' and IDALBUM = " . $album;
	checkingQuery($connectBD, $q1);
	
	$q_count = "select * from ".preBD."slider WHERE IDALBUM = " . $album;
	$result = checkingQuery($connectBD, $q_count);
	$numero = mysqli_num_rows($result);
	
	$q2 = "UPDATE ".preBD."slider SET POSITION='".$numero."' WHERE ID='".$record."' and IDALBUM = " . $album;
	checkingQuery($connectBD, $q2);
	
	$msg = "Posición de la imagen ".$record." modificada a la última posición";
	disconnectdb($connectBD);
	
	$location = "Location: ../../index.php?mnu=design&com=slide&tpl=option&filteralbum=".$album."&recodsperpage=".$recordxpage."&page=".$pagina."&msg=".utf8_decode($msg);
	header($location);
?>