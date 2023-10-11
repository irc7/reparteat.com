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
	if(isset($_GET["record"]) && abs(intval($_GET["record"])) > 0){
		$msg = null;
		
		$record = abs(intval($_GET["record"]));
		$status = abs(intval($_GET["status"]));
		
		$q = "update ".preBD."blog_comment set";
		$q .= " STATUS = '" . $status;
		$q .= "' where ID = " . $record;
		
		$res = checkingQuery($connectBD, $q);
		if($status == 0) {
			$msg = "Comentario despublicado correctamente.";
		}elseif($status == 1) {
			$msg = "Comentario publicado correctamente.";
		}
	}
	
	$location = "Location: ../../index.php?mnu=blog&com=blog&tpl=option&opt=comment&msg=".utf8_decode($msg);
	header($location);
?>