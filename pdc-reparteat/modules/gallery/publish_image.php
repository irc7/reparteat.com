<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	
	$mnu = $_GET["mnu"];
	if (allowed($mnu)) {
		if (isset($_GET["image"])) {
			$mnu = $_GET["mnu"];
			$record = intval($_GET["image"]);
			$q = "select TITLE, IDGALLERY from ".preBD."images where ID = '".$record."'";
			
			$result = checkingQuery($connectBD, $q);
			$row = mysqli_fetch_assoc($result);
		
				$q = "UPDATE ".preBD."images SET STATUS='1' WHERE ID='".$record."'";
				checkingQuery($connectBD, $q);
				
				$msg = "Imagen <em>".stripslashes($row["TITLE"])."</em> pasada a publicada";
			
			disconnectdb($connectBD);
		}
		$location = "Location: ../../index.php?mnu=".$mnu."&com=gallery&tpl=option&filtergallery=".$row["IDGALLERY"]."&msg=".utf8_decode($msg);
		header($location);
	} else {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
?>