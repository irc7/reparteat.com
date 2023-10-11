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
	if(isset($_GET["record"]) && abs(intval($_GET["record"])) > 0) {
		
		$id = abs(intval($_GET["record"]));
		$q = "DELETE FROM ".preBD."blog_comment WHERE ID = '" . $id . "'";
		checkingQuery($connectBD, $q);
		$msg = "Comentario eliminado correctamente.";
	} else {
		$msg = "El comentario que intenta eliminar no existe.";
	}
	$location = "Location: ../../index.php?mnu=blog&com=blog&tpl=option&opt=comment&msg=".utf8_decode($msg);
	disconnectdb($connectBD);	
	header($location);
?>