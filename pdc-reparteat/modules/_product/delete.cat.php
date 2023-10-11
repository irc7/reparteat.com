<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	

	if (!allowed("design")) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}	
	if(isset($_GET["cat"]) && intval($_GET["cat"]) != 0) {	
		$cat = intval($_GET["cat"]);
		
		$q = "select TITLE from ".preBD."products_cat WHERE ID = '" . $cat . "'";
		$result = checkingQuery($connectBD, $q);
		$row_cat = mysqli_fetch_object($result);
		
		$q = "select count(*) as total from ".preBD."products WHERE IDCAT = '" . $cat . "'";
		
		$result = checkingQuery($connectBD, $q);
		$imgs = mysqli_fetch_object($result);
		if($imgs->total > 0){
			$msg = "Debe vaciar el álbum para poder borrarlo";	
		} else {
			$q = "DELETE FROM ".preBD."products_cat WHERE ID = '" . $cat . "'";
			checkingQuery($connectBD, $q);
			 
			$q = "DELETE FROM ".preBD."url_web WHERE SEC_VIEW = '" . $cat . "' and TYPE = 'cat'";
			checkingQuery($connectBD, $q);
			
			
			$msg = "Categoría " . $row_cat->TITLE." borrada correctamente";
		}
		disconnectdb($connectBD);
		
	}else {
		$msg = "No existe la referencia de product que intenta eliminar.";
	}
	$location = "Location: ../../index.php?mnu=content&com=product&tpl=option&opt=cat&msg=".utf8_decode($msg);
	header($location);
?>