<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	
	$V_PHP = explode(".", phpversion());
	if($V_PHP[0]>=5){
		date_default_timezone_set("Europe/Paris");
	}
	$mnu = trim($_POST["agenda"]);
	if (!allowed($mnu)) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}	

	if ($_POST) {
		$id = $_POST["agenda"];
		
		$title = stripslashes(trim($_POST["title"]));
		$title_seo = stripslashes(trim($_POST["title_seo"]));
		$description = stripslashes(trim($_POST["description"]));		
		if($title_seo == ""){
			$title_seo = $title;
		}			
		$codeColor = trim($_POST["codeColor"]);
		
		$q="UPDATE ".preBD."agenda_sections SET TITLE = '" . $title . "', TITLE_SEO = '". $title_seo . "', DESCRIPTION = '". $description . "', COLOR = '". $codeColor . "' 
			WHERE ID = '" . $id . "'";
		checkingQuery($connectBD, $q);
		$msg = "Agenda ".$title." modificada.";
		
		disconnectdb($connectBD);
	}
	$location = "Location: ../../index.php?mnu=".$mnu."&com=agenda&tpl=option&opt=section&msg=".utf8_decode($msg);
	header($location);
?>