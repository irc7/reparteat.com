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
	if (allowed ("content")) { 	
		if (isset($_GET['down'])) {
			$id = $_GET['down'];
			$connectBD = connectdb();
			$q = "select IMAGE as url from ".preBD."downloads where ID = " . $id;
			
			$r=checkingQuery($connectBD, $q);)
			$image = mysqli_fetch_object($r);
			if($image->url != "") {
				$url = "../../../files/download/image/".$image->url;
				if(file_exists($url)) {
					unlink($url);
					$q = "UPDATE ".preBD."downloads SET `IMAGE`= '' WHERE ID = " . $id;
					checkingQuery($connectBD, $q);
					
					$msg = "Imagen eliminada correctamente";
				}else{
					$msg = "No existe la imagen que desea borrar.";
				}
			} else {
				$msg = "No existe la imagen que desea borrar.";
			}
			disconnectdb($connectBD);
		}
		$location = "Location: ../../index.php?mnu=content&com=download&tpl=edit&id=".$id."&msg=".utf8_decode($msg);		
		header($location);
	} else {
		$msg = "No tiene permisos para realizar esta acción";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);	
		header($location);
	}	
?>