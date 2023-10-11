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
	$mnu = trim($_GET["mnu"]);
	if(allowed($mnu)) {
		$article = intval($_GET["record"]);
		if (isset($_GET["record"])) {
			$typeArticle = trim($_GET["type"]);
	
			$fundacion = intval($_GET["fundacion"]);
			
			$q1 = "SELECT Author, IDSECTION FROM ".preBD."articles WHERE ID = '" . $article . "'";
			
			$result1 = checkingQuery($connectBD, $q1);
			$row1 = mysqli_fetch_array($result1);
			$author = $row1['AUTHOR'];
			$section = $row1['IDSECTION'];
			
				$q="UPDATE ".preBD."articles SET VIEW_FUNDATION = '".$fundacion."' WHERE ID = '" . $article . "'";
				checkingQuery($connectBD, $q);
				
				if($fundacion == 1) {
					$msg = "El artículo ".$article." se ha publicado correctamente en Fundación IHP";
				}elseif($fundación == 0) {
					$msg = "El artículo ".$article." se ha despublicado correctamente de Fundación IHP";
				}
			
			
		}
		disconnectdb($connectBD);
		$location = "Location: ../../index.php?mnu=".$mnu."&com=articles&tpl=option&record=".$article."&type=article&msg=".utf8_decode($msg);
		header($location);
	} else {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
?>