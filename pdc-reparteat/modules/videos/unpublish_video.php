<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}

	if (!allowed("content")) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}	
	if ($_GET) {
	
		$record = intval($_GET["record"]);
		
		$q = "select TITLE, IDGALLERY from ".preBD."videos where ID = '".$record."'";
		$result = checkingQuery($connectBD, $q);
		
		$row = mysqli_fetch_assoc($result);
	
		$q = "UPDATE ".preBD."videos SET STATUS='0' WHERE ID='".$record."'";
		checkingQuery($connectBD, $q);
		
		$msg = "Vídeo ".stripslashes($row["TITLE"])." pasado a borrador";
/*		
		//GESTION DE SITEMAP
		$q = "select TITLE from ".preBD."videos_gallery where ID = '" . $row['IDGALLERY'] . "'";
		$result = checkingQuery($connectBD, $q);
		
		$row2 = mysqli_fetch_assoc($result);
		$sitemap = formatNameUrl(stripslashes($row2["TITLE"])) . ".xml";
		
		$msg_alt = construcSitemapVideos($row['IDGALLERY'], $sitemap);			
*/		
		disconnectdb($connectBD);
	}
	$location = "Location: ../../index.php?mnu=content&com=videos&tpl=option&filtergallery=".$row["IDGALLERY"]."&msg=".utf8_decode($msg);
	header($location);
?>