<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	
	date_default_timezone_set("Europe/Paris");
	
	$mnu = $_GET["mnu"];
	if (allowed($mnu)) {
		if (isset($_GET["image"])) {
			$id = intval($_GET["image"]);
			
			
				$q = "SELECT * FROM ".preBD."images WHERE ID='" . $id . "'";	
				$result = checkingQuery($connectBD, $q);
				
				$row = mysqli_fetch_array($result);
				$position = $row['POSITION'];
				$Gallery = $row['IDGALLERY'];
				
				
				if($row["URL"] != "") {
					$urlDelete = "../../../files/gallery/image/" . $row["URL"];
					$thumbDelete = "../../../files/gallery/thumb/" . $row["URL"];
					deleteFile($urlDelete);
					deleteFile($thumbDelete);
				}

				$q = "DELETE FROM ".preBD."images WHERE ID='".$id."'";
				checkingQuery($connectBD, $q);
				
				/*Actualizamos las posiciones del resto de imágenes*/
				$q_s_update = "SELECT * FROM ".preBD."images WHERE IDGALLERY = ".$Gallery." and POSITION > ".$position;
				
				$result_s_update = checkingQuery($connectBD, $q_s_update);
				while ($row_update = mysqli_fetch_assoc($result_s_update)) {
					$q_up = "UPDATE ".preBD."images SET POSITION = '".($row_update["POSITION"] - 1)."' WHERE ID = ".$row_update["ID"];
					checkingQuery($connectBD, $q_up);
					
				}			
				
				$msg = "Imagen <em>".$row["TITLE"]."</em> eliminada definitivamente";	
				
			
		}
		disconnectdb($connectBD);
		$location = "Location: ../../index.php?mnu=".$mnu."&com=gallery&tpl=option&filtergallery=".$row["IDGALLERY"]."&msg=".utf8_decode($msg);
		header($location);
	} else {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
?>