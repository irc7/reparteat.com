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
			$Title = trim($_POST["Title"]);
			$Title_seo = trim($_POST["Title_seo"]);
			if($Title_seo == ""){
				$Title_seo = $Title;
			}
			$Section = trim($_POST["Section"]);
			$Description = trim($_POST["Description"]);
			
			$q = "INSERT INTO ".preBD."images_gallery (TITLE, TITLE_SEO, IDGALLERYSECTION, DESCRIPTION) VALUES ('" . addslashes($Title) . "', '" . addslashes($Title_seo) . "', '" . $Section . "', '" . addslashes($Description) . "')";
			checkingQuery($connectBD, $q);
			
			$idNew = mysqli_insert_id($connectBD);
			$q = "INSERT INTO ".preBD."images_gallery_style (IDGALLERY) VALUES ('" . $idNew . "')";
			checkingQuery($connectBD, $q);
			
			$msg = "Galería <em>" . $Title."</em> creado correctamente.";
			$action = "new_section";
			$msg_alt = construcIndexSitemap($action);
			
			disconnectdb($connectBD);
		}
		$location = "Location: ../../index.php?mnu=".$mnu."&com=gallery&tpl=option&opt=gallery&msg=".utf8_decode($msg);
		header($location);
	} else {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
?>