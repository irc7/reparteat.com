<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	
	header ('Content-type: text/html; charset=utf-8');
	$V_PHP = explode(".", phpversion());
	if($V_PHP[0]>=5){
		date_default_timezone_set("Europe/Paris");
	}
	
	$mnu = trim($_POST["mnu"]);
	
	if (!allowed($mnu)) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
	if($_POST){ //se cerrará despues del body
		if(isset($_POST["record"])) {
			$id = intval($_POST["record"]);
			$action = trim($_POST["action"]);
			
			$q = "select SUBJECT, SEND_OK, SEND_OFF from ".preBD."newsletter where ID = " . $id;
			$r = checkingQuery($connectBD, $q);
			$newsletter = mysqli_fetch_object($r);
			$msg = "";
			switch($action) {
				case "send":
					if($newsletter->SEND_OK == 0) {
						cleanSend($id);
						$msg .= "Enviados limpiados correctamente.";
					}
				break;
				case "error":
					if($newsletter->SEND_OFF == 0) {
						cleanError($id);
						$msg .= "Errores de envio limpiados correctamente.";
					}
				break;
				case "trail":
					cleanTrail($id);
					$msg .= "Cola eliminada correctamente.";
				break;
				case "all":
					if($newsletter->SEND_OK == 0) {
						cleanSend($id);
						$msg .= "Enviados limpiados correctamente.<br/>";
					}
					if($newsletter->SEND_OFF == 0) {
						cleanError($id);
						$msg .= "Errores de envio limpiados correctamente.<br/>";
					}
					cleanTrail($id);
					$msg .= "Cola eliminada correctamente.";
				break;
			}
			$location = "Location: ../../index.php?mnu=mailing&com=newsletter&tpl=detail&record=".$id."&msg=".utf8_decode($msg);
		}else {
			$msg = "Ha ocurrido un error inesperado, vuelva a intentarlo, sí el error persiste póngase en contacto con el administrador.";
			$location = "Location: ../../index.php?mnu=mailing&com=newsletter&tpl=list&opt=mailer&msg=".utf8_decode($msg);
		}
		disconnectdb($connectBD);
		header($location);
	} else {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
?>