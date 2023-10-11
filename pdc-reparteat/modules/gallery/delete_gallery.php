<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	
	$mnu = $_GET["mnu"];
	if (allowed($mnu)) {
		if (isset($_GET["gallery"])) {
			
			$mnu = $_GET["mnu"];
			$gallery = intval($_GET["gallery"]);
			

			$q1 = "SELECT * FROM ".preBD."users WHERE Login='".$_SESSION[PDCLOG]['Login']."'";
			
			$result1 = checkingQuery($connectBD, $q1);
			$row1 = mysqli_fetch_array($result1);
			$pwdhash1 = $row1['Pwd'];
		
				$q = "select TITLE from ".preBD."images_gallery WHERE ID = '" . $gallery . "'";
				$result = checkingQuery($connectBD, $q);
				
				$galleryBD = mysqli_fetch_assoc($result);
				
				$q = "select ID from ".preBD."images WHERE IDGALLERY = '" . $gallery . "'";
				
				$result = checkingQuery($connectBD, $q);
				
				if($row = mysqli_fetch_assoc($result)){
					$msg = "Debe vaciar la galería para poder borrarla";	
				} else {
					
					$q = "DELETE FROM ".preBD."images_gallery WHERE ID = '" . $gallery . "'";
					checkingQuery($connectBD, $q);
					
					$q = "DELETE FROM ".preBD."images_gallery_style WHERE IDGALLERY = '" . $gallery . "'";
					checkingQuery($connectBD, $q);
					
					$msg = "Galería <em>" . $galleryBD->TITLE."</em> borrado correctamente.";
				}
			
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