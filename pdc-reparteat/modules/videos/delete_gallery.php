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
		
		$Gallery = intval($_GET["gallery"]);

		$q1 = "SELECT * FROM ".preBD."users WHERE Login='".$_SESSION[PDCLOG]['Login']."'";
		$result1 = checkingQuery($connectBD, $q1);
		
		$row1 = mysqli_fetch_array($result1);
		$pwdhash1 = $row1['Pwd'];
		
			$q = "select TITLE from ".preBD."videos_gallery WHERE ID = '" . $Gallery . "'";
			$result = checkingQuery($connectBD, $q);
			$galleryBD = mysqli_fetch_object($result);
			
			$q = "select ID from ".preBD."videos WHERE IDGALLERY = '" . $Gallery . "'";
			$result = checkingQuery($connectBD, $q);
			
			if($row = mysqli_fetch_assoc($result)){
				$msg = "Debe vaciar el álbum para poder borrarlo";	
			} else {
				$sitemap = formatNameUrl(stripslashes($row["TITLE"])) . ".xml";
				
				$q = "DELETE FROM ".preBD."videos_gallery WHERE ID = '" . $Gallery . "'";
				checkingQuery($connectBD, $q);
				
				$q = "DELETE FROM ".preBD."videos_gallery_style WHERE IDGALLERY = '" . $Gallery . "'";
				checkingQuery($connectBD, $q);
				
				$msg = "Galería " . stripslashes($galleryBD->TITLE)." borrada correctamente.";
				//Gestion Sitemap
				deleteSitemap($sitemap);
				$action = "delete_section";
				$msg_alt = construcIndexSitemap($action);
				//Fin Gestion Sitemap			
			}
		
	}
	$location = "Location: ../../index.php?mnu=content&com=videos&tpl=option&opt=gallery&msg=".utf8_decode($msg);
	header($location);
?>