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
			if(isset($_GET["section"]) && abs(intval($_GET["section"])) > 0) {
				$id = abs(intval($_GET["section"]));
				$totalDown = checkingSection("downloads", "IDSECTION", $id);
				
				$q = "select TITLE from ".preBD."download_sections where ID = " . $id;
				$r = checkingQuery($connectBD, $q);
				
				$sec = mysqli_fetch_object($r);
				
				if($totalDown == 0) { 
					$qD = "delete from ".preBD."download_sections where ID = " . $id;
					
					$r = checkingQuery($connectBD, $qD);
					disconnectdb($connectBD);
					$msg = "Sección <em>".$sec->TITLE."</em> eliminada correctamente.";
					$location = "Location: ../../index.php?mnu=".$mnu."&submnu=".$submnu."&com=download&tpl=option&opt=section&msg=".utf8_decode($msg);
					header($location);
				
				}else{
					disconnectdb($connectBD);
					$msg = "Debe eliminar los informes pertenecientes a <em>".$sec->TITLE."</em>.";
					$location = "Location: ../../index.php?mnu=".$mnu."&submnu=".$submnu."&com=download&tpl=option&opt=section&msg=".utf8_decode($msg);
					header($location);
				}
			} else {
				disconnectdb($connectBD);
				$msg = "Sección de descargas desconocida.";
				$location = "Location: ../../index.php?mnu=".$mnu."&submnu=".$submnu."&com=download&tpl=option&opt=section&msg=".utf8_decode($msg);
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