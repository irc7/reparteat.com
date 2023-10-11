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
//	pre($_GET);
//	pre($_FILES);
//	die();
	if($_GET) {
		$mnu = trim($_GET["mnu"]);
		$submnu = abs(intval($_GET["submnu"]));
		if(!isset($_GET["mnu"]) || !allowed($_GET["mnu"])) { 	
			disconnectdb($connectBD);
			$msg = "No tiene permisos para realizar esta acción";
			$location = "Location: ../../index.php?msg=".utf8_decode($msg);
			header($location);
		} else {
			$error = 1;
			$id = abs(intval($_GET["id"]));
			$filtersection = abs(intval($_GET["filtersection"]));
			$recodsperpage = abs(intval($_GET["recodsperpage"]));
			$page = abs(intval($_GET["page"]));
			$urlReturn = "mnu=".$mnu."&submnu=".$submnu."&filtersection=".$filtersection."&recodsperpage=".$recodsperpage."&page=".$page;
			if (isset($_GET["id"]) && $id > 0) {
			
				$status = abs(intval($_GET["status"]));
				
				$q = "select TITLE from ".preBD."downloads where ID = " . $id;
				
				$r = checkingQuery($connectBD, $q);
				
				$down = mysqli_fetch_object($r);
				
				$q = "UPDATE ".preBD."downloads SET `STATUS`= ".$status." where ID = " . $id;
				checkingQuery($connectBD, $q);	
				$error = 0;
				if($status == 1){
					$msg = "Descarga <em>".$down->TITLE."</em> publicada correctamente.";
				}else{
					$msg = "Descarga <em>".$down->TITLE."</em> pasada a borrador.";
				}
				disconnectdb($connectBD);
				$location = "Location: ../../index.php?".$urlReturn."&com=download&tpl=option&id=".$id."&msg=".utf8_decode($msg);
				header($location);
			} else {
				disconnectdb($connectBD);
				$msg = "Descarga desconocido.";
				$location = "Location: ../../index.php?".$urlReturn."&com=download&tpl=list&msg=".utf8_decode($msg);
				header($location);
			}
		}
	}else {
		disconnectdb($connectBD);
		$msg = "Se ha producido un error, si el problema persiste, póngase en contacto con el administrador.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
?>