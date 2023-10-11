<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	$item = intval($_GET["item"]);
	$Menu = intval($_GET["filtermenu"]);

	if (allowed("design")) {
		
		$q = "SELECT * FROM ".preBD."menu_item WHERE ID='".$item."'";
		$result = checkingQuery($connectBD, $q);
		$row = mysqli_fetch_array($result);
		$title = $row['TITLE'];
		$position = $row['POSITION'];
		$parent = $row['PARENT'];
		
		$q1 = "UPDATE ".preBD."menu_item SET POSITION = '".$position."' WHERE POSITION='".($position + 1)."' and IDMENU = '".$row["IDMENU"]."' and PARENT = " . $parent;
		checkingQuery($connectBD, $q1);
			
		$q2 = "UPDATE ".preBD."menu_item SET POSITION='".($position + 1)."' WHERE ID = '".$item."' and PARENT = " . $parent;
		checkingQuery($connectBD, $q2);
		
		$msg = "Posición del elemento de menú ".$title." modificada. ";
		disconnectdb($connectBD);
		
		$location = "Location: ../../index.php?mnu=design&com=menu&tpl=option&filtermenu=".$Menu."&msg=".$msg;
		header($location);
	} else {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
?>