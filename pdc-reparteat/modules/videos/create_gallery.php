<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	$V_PHP = explode(".", phpversion());

	if (!allowed("content")) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}	
	if ($_POST) {
	
		$Title = addslashes(trim($_POST["Title"]));
		$Title_seo = addslashes(trim($_POST["Title_seo"]));
		if($Title_seo == ""){
			$Title_seo = $Title;
		}
		$Description = addslashes(trim($_POST["Description"]));
		
		$q = "INSERT INTO ".preBD."videos_gallery (TITLE, TITLE_SEO, DESCRIPTION) VALUES ('" . $Title . "', '" . $Title_seo . "', '" . $Description . "')";
		checkingQuery($connectBD, $q);
		$idNew = mysqli_insert_id($connectBD);
			
		$q = "INSERT INTO ".preBD."videos_gallery_style (IDGALLERY) VALUES ('" . $idNew . "')";
		checkingQuery($connectBD, $q);
			
		$msg = $Title." creado correctamente";
		$action = "new_section";
		$msg_alt = construcIndexSitemap($action);
		
		
		disconnectdb($connectBD);
	}
	$location = "Location: ../../index.php?mnu=content&com=videos&tpl=option&opt=gallery&msg=".utf8_decode($msg);
	header($location);
?>