<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	//pre($_GET);die();
	$module = $_GET["module"];
	$modulexpage = $_GET["recodsperpage"];
	$pagina = $_GET["page"];

	if (!allowed("design") && ($_SESSION[PDCLOG]['Login'] == "webmaster@ismaelrc.es") && ($_SESSION[PDCLOG]['Type'] == 4)) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}	
	$q = "SELECT * FROM ".preBD."configuration_modules WHERE ID='".$module."'";
	$result = checkingQuery($connectBD, $q);
	
	$row = mysqli_fetch_object($result);
	$position = $row->POSITION;
	$menu = $row->IDMENU;
	
	$q1 = "UPDATE ".preBD."configuration_modules SET POSITION='".$position."' WHERE POSITION='".($position - 1)."' and IDMENU = " . $menu;
	checkingQuery($connectBD, $q1);
	
	$q2 = "UPDATE ".preBD."configuration_modules SET POSITION='".($position - 1)."' WHERE ID='".$module."' and IDMENU = " . $menu;
	checkingQuery($connectBD, $q2);
	
	$msg = "Posición del módulo ".$row->MODULE." modificado";
	disconnectdb($connectBD);
	
	$location = "Location: ../../index.php?mnu=configuration&com=module&tpl=option&filtersection=".$menu."&recodsperpage=".$modulexpage."&page=".$pagina."&msg=".utf8_decode($msg);
	header($location);
?>