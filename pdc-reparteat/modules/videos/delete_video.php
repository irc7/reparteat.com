<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	
	date_default_timezone_set("Europe/Paris");

	if (!allowed("content")) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}	
	if (isset($_GET["record"])) {
		$id = $_GET["record"];
	
		$q = "SELECT * FROM ".preBD."videos WHERE ID='" . $id . "'";	
		$result = checkingQuery($connectBD, $q);
		
		$row = mysqli_fetch_object($result);
		
		$position = $row->POSITION;
		$Gallery = $row->IDGALLERY;

		
		if($row->TYPEVIDEO != "youtube") {
			$url = "../../../files/videos/video/".$videoBD->CODE;
			deleteFile($urlImgOld);
		}
		if($row->TYPEIMAGE != "youtube") {
			if($videoBD->IMAGE != "") {
				//borramos en antiguo	
				$urlImg = "../../../files/videos/image/".$row->IMAGE;
				deleteFile($urlImg);
				$urlThumb = "../../../files/videos/thumb/".$row->IMAGE;
				deleteFile($urlThumb);
			}
		}
		$q = "DELETE FROM ".preBD."videos WHERE ID='".$id."'";
		checkingQuery($connectBD, $q);
		
/*Actualizamos las posiciones del resto de imágenes*/
		$q_s_update = "SELECT * FROM ".preBD."videos WHERE IDGALLERY = ".$Gallery." and POSITION > ".$position;
		$result_s_update = checkingQuery($connectBD, $q_s_update);
		
		while ($row_update = mysqli_fetch_object($result_s_update)) {
			
			$q_up = "UPDATE ".preBD."videos SET POSITION = '".($row_update->POSITION - 1)."' WHERE ID = ".$row_update->ID;
			checkingQuery($connectBD, $q_up);
		}			
		$msg = "Vídeo ".$row->TITLE." eliminado definitivamente.";	
		
		/*/GESTION DE SITEMAP
		if($Gallery != 0) {
			
			$q_sec = "select TITLE from ".preBD."videos_gallery where ID = '" . $Gallery . "'";
			$result_sec = checkingQuery($connectBD, $q_sec);
			
			$row_sec = mysqli_fetch_assoc($result_sec);
			$sitemap = formatNameUrl(stripslashes($row_sec["TITLE"])) . ".xml";
			$msg_alt = construcSitemapVideos($Gallery, $sitemap);
		}
		//FIN		
		*/
	}
	disconnectdb($connectBD);
	$location = "Location: ../../index.php?mnu=content&com=videos&tpl=option&filtergallery=".$row->IDGALLERY."&msg=".utf8_decode($msg);
	header($location);
?>