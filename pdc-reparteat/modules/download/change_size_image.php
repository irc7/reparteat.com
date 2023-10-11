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
			$title = addslashes(trim($_POST["title"]));
			$width = intval($_POST["width"]);
			$height = intval($_POST["height"]);
				
				
			$q = "UPDATE ".preBD."download_sections SET";
			$q .= " `WIDTH`=".$width;
			$q .= ", `HEIGHT`=".$height;
			$q .= " WHERE ID = " . $id;
			checkingQuery($connectBD, $q);
			
			disconnectdb($connectBD);
			$msg = "Tamaño de imágenes para la sección <em>".$title."</em> modificados correctamente";
			$location = "Location: ../../index.php?mnu=".$mnu."&submnu=".$submnu."&com=download&tpl=option&opt=section&msg=".utf8_decode($msg);
			header($location);
		
		
		}
	}else {
		disconnectdb($connectBD);
		$msg = "Se ha producido un error, si el problema persiste, póngase en contacto con el administrador.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
?>