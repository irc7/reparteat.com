<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	
	date_default_timezone_set("Europe/Madrid");

	if (allowed("design")) {	
		if ($_POST) {
			$msg = "";
			$Title = addslashes(trim($_POST["Title"]));
			$Parents = intval(abs($_POST["Parent"]));
			$q = "INSERT INTO ".preBD."menu (`TITLE` ,`PARENT`)";
			$q .= " VALUES ('".$Title."', '".$Parents."')";
			checkingQuery($connectBD, $q);
			
			$msg = "Se han creado el menu ".$Title." con ".$i. "elementos principales";	
			$menu = mysqli_insert_id($connectBD); 
			
			for($i=1;$i<=$Parents;$i++) {
				$q = "INSERT INTO ".preBD."menu_item (`IDMENU` ,`TITLE` ,`PARENT` ,`LEVEL` ,`TYPE` ,`IDVIEW` ,`TARGET` ,`POSITION`)";
				$q .= " VALUES ('".$menu."', 'Item ".$i."', '0', '0', '0', NULL , '_self', '".$i."')";
				checkingQuery($connectBD, $q);	
				
			}
			disconnectdb($connectBD);
			$location = "Location: ../../index.php?mnu=design&com=menu&tpl=option&filtermenu=".$menu."&msg=".$msg;
			header($location);
		}
	} else {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
?>