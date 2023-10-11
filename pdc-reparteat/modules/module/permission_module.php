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
	//pre($_GET); die();	
	if (allowed("configuration") && ($_SESSION[PDCLOG]['Login'] == "webmaster@ismaelrc.es") && ($_SESSION[PDCLOG]['Type'] == 4)) {	
		if((isset($_GET["id_module"])) && (isset($_GET["level_permission"]))) {
			$module = intval($_GET["id_module"]);			
			$level_permission = intval($_GET["level_permission"]);
			
			$q="UPDATE ".preBD."configuration_modules SET PERMISSION = '".$level_permission."' WHERE ID = '" . $module . "'";
			checkingQuery($connectBD, $q);
			$msg = "Módulo ".$module." cambiado de nivel de permiso correctamente";
					
			disconnectdb($connectBD);
		}
		$location = "Location: ../../index.php?mnu=configuration&com=module&tpl=option&msg=".utf8_decode($msg);
		header($location);
	} else {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
?>