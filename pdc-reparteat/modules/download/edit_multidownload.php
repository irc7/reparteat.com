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
	if($_POST) {
		$mnu = trim($_POST["mnu"]);
		$submnu = abs(intval($_POST["submnu"]));
		if(!isset($_POST["mnu"]) || !allowed($_POST["mnu"])) { 	
			disconnectdb($connectBD);
			$msg = "No tiene permisos para realizar esta acción";
			$location = "Location: ../../index.php?msg=".utf8_decode($msg);
			header($location);
		} else {
		
			$id = intval($_POST["section"]);
			$multi_download = intval($_POST["select_multi_download"]);
			
			$q = "select * from ".preBD."download_sections where ID = " . $id;
			$r=checkingQuery($connectBD, $q);
			$download = mysqli_fetch_object($r);	
			//pre($download);die();
				
			$q = "UPDATE ".preBD."download_sections SET";
			$q.= " `MULTIDOWNLOAD` = '" . $multi_download;
			$q.= "' where ID = " . $id;
			checkingQuery($connectBD, $q);

			$msg = "Multidescarga de la sección <em>".$download->TITLE.".</em> modificada correctamente.";
			disconnectdb($connectBD);
			$location = "Location: ../../index.php?mnu=".$mnu."&submnu=".$submnu."&com=download&tpl=option&opt=section&section=".$id."&msg=".utf8_decode($msg);				
			header($location);
		}
	} else {
		disconnectdb($connectBD);
		$msg = "Se ha producido un error, si el problema persiste, póngase en contacto con el administrador.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);	
		header($location);
	}		
?>