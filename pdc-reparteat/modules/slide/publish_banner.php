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
	$record = $_GET["record"];
	$recordperpage = $_GET["recordperpage"];
	$page = $_GET["page"];
	if(isset($_GET["filteralbum"])){
		$filteralbum = $_GET["filteralbum"];
	}else{
		$filteralbum = 0;
	}
		$q = "select IDALBUM from ".preBD."slider WHERE ID='".$record."'";
		$r = checkingQuery($connectBD, $q);
		$alb = mysqli_fetch_object($r);
		
		if($alb->IDALBUM == 1) {
			$q="UPDATE ".preBD."slider SET STATUS='0' WHERE IDALBUM = 1";
			checkingQuery($connectBD, $q);
		}
		
		$q="UPDATE ".preBD."slider SET STATUS='1' WHERE ID='".$record."'";
		checkingQuery($connectBD, $q);
		if($alb->IDALBUM == 1) {
			$msg = "Popup ".$record." publicado";
		}else {
			$msg = "Imagen ".$record." publicado";
		}
	
	disconnectdb($connectBD);
	
	$location = "Location: ../../index.php?mnu=design&com=slide&tpl=option&filteralbum=".$filteralbum."&recordperpage=".$recordperpage."&page=".$page."&msg=".utf8_decode($msg);
	header($location);
?>