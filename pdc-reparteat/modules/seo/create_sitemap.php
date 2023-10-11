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
	$error = NULL;
	
	if (!allowed("seo")) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
	if($_POST) {
		$action = $_POST["action"];
		$msg = construcIndexSitemap($action);
	} else {
		$msg = "Se ha producido un error al crear los sitemaps, intentelo de nuevo";
	}
		
	disconnectdb($connectBD);
	$location = "Location: ../../index.php?mnu=seo&com=seo&tpl=sitemaps&msg=".utf8_decode($msg);
	header($location);

?>