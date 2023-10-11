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
	$connectBD = connectdb();
	if($_GET) {
		$mnu = trim($_GET["mnu"]);
		if(!isset($_GET["mnu"]) || !allowed($_GET["mnu"])) { 	
			disconnectdb($connectBD);
			$msg = "No tiene permisos para realizar esta acción";
			$location = "Location: ../../index.php?msg=".utf8_decode($msg);
			header($location);
		} else {
		
			$id = intval($_GET["record"]);
			$filteragenda = intval($_GET["filtersection"]);
			$recodsperpage = intval($_GET["recodsperpage"]);
			$page = intval($_GET["page"]);
			$action = trim($_GET["action"]);
			
			$q = "select * from ".preBD."agenda where ID = " . $id;
			$r = checkingQuery($connectBD, $q);
			$eventBD = mysqli_fetch_object($r);
			
			if($id >= 0) {
				switch($action) {
					case "publish":
						$qs = "UPDATE `".preBD."agenda` 
								SET 
							`STATUS`='1' WHERE ID = " . $id;
						checkingQuery($connectBD, $qs);
						$msgAlert = "publicado";
					break;
					case "unpublish":
						$qs = "UPDATE `".preBD."agenda` 
								SET 
							`STATUS`='0' WHERE ID = " . $id;
						checkingQuery($connectBD, $qs);
						$msgAlert = "pasado a borrador";
					break;
					case "delete":
						//eliminar archivo
							if($eventBD->URL != "" && $eventBD->URL != NULL) {
								$url = "../../../files/agenda/doc/".$eventBD->URL;
								deleteFile($url);
							}
						$qD2 = "DELETE FROM `".preBD."agenda` WHERE ID = " . $id;
						checkingQuery($connectBD, $qD2);
						$msgAlert = "eliminado";
						
					break;
				}
			}
		
			disconnectdb($connectBD);
			$msg = "Evento <em>".$eventBD->TITLE."</em> ".$msgAlert." correctamente.";
			$location = "Location: ../../index.php?mnu=".$mnu."&com=agenda&tpl=option&msg=".utf8_decode($msg)."&recodsperpage=".$recodsperpage."&page=".$page;
			header($location);
			
		}	
	}else {
		disconnectdb($connectBD);
		$msg = "Se ha producido un error, si el problema persiste, póngase en contacto con el administrador.";
		$location = "Location: ../../index.php?mnu=".$mnu."&com=agenda&tpl=option&msg=".utf8_decode($msg)."&recodsperpage=".$recodsperpage."&page=".$page;
		header($location);
	}
?>