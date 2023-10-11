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
	
	if (!allowed("blog")) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
	if(isset($_GET["blog"]) && abs(intval($_GET["blog"])) > 0){
		$msg = null;
		$blog = abs(intval($_GET["blog"]));
		$section = abs(intval($_GET["section"]));
		$validation = abs(intval($_GET["validation"]));
		$q = "select TITLE from ".preBD."articles_sections where ID = " . $section;
		$r=checkingQuery($connectBD, $q);
		$secBD = mysqli_fetch_object($r);
		
		$q = "update ".preBD."blog set";
		$q .= " VALIDATION = '" . $validation;
		$q .= "' where ID = " . $blog;
		
		$res = checkingQuery($connectBD, $q);
		if($validation == 0) {
			$msg = "La publicación libre de los comentarios activada correctamente.";
		}elseif($validation == 1) {
			$msg = "La aprobación via mail de los comentarios activada correctamente.";
		}
	}
	
	$location = "Location: ../../index.php?mnu=blog&com=blog&tpl=option&opt=section&msg=".utf8_decode($msg);
	header($location);
?>