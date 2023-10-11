<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	$V_PHP = explode(".", phpversion());
	
	$mnu = $_POST["mnu"];
	if (allowed($mnu)) {
		if ($_POST) {
			$mnu = $_POST["mnu"];
			$Section = trim($_POST["Section"]);
			$Title_seo = trim($_POST["Title_seo"]);
			if($Title_seo == ""){
				$Title_seo = $Section;
			}
			
				$q = "INSERT INTO ".preBD."images_gallery_sections (TITLE, TITLE_SEO ) VALUES ('" . $Section . "', '" . $Title_seo . "')";
				checkingQuery($connectBD, $q);
				$msg = $Section." creado correctamente";
				
			disconnectdb($connectBD);
		}
		$location = "Location: ../../index.php?mnu=".$mnu."&com=gallery&tpl=option&opt=album&msg=".utf8_decode($msg);
		header($location);
	} else {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
?>