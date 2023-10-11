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
	
	require_once("../../includes/classes/Supplier/class.Supplier.php");
	
	$mnu = trim($_GET["mnu"]);
	
	if (!allowed($mnu)) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
	$com = trim($_GET["com"]);
	$tpl = trim($_GET["tpl"]);
	$opt = trim($_GET["opt"]);

	if (isset($_GET["supplier"]) && intval($_GET["supplier"]) > 0 && isset($_GET["zone"]) && intval($_GET["zone"]) > 0) {
		
		$idSup = intval($_GET["supplier"]);
		$idZone = intval($_GET["zone"]);
		
		$supObj = new Supplier();	
		
		if($supObj->deleteZoneDelivery($idSup, $idZone)) {
			$msg = "Zona de reparto eliminada correctamente.";
		}else{
			$msg = "Se ha producido un error al borrar la zona de reparto, por favor vuelva a intentarlo, si el problema persiste consulte con el administrador.";
		}
		
	}
	disconnectdb($connectBD);
	$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=edit&id=".$idSup."&msg=".utf8_decode($msg);
	header($location);
?>