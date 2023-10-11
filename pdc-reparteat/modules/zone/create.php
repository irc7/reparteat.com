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
	
	require_once("../../includes/classes/Zone/class.Zone.php");
	$mnu = trim($_POST["mnu"]);

	if (!allowed($mnu)) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
	$com = trim($_POST["com"]);
	$tpl = trim($_POST["tpl"]);
	$opt = trim($_POST["opt"]);
	
	if ($_POST) {
		$msg = "";
		$error = NULL;
		$zone = new Zone();
		
		$zone->status = trim($_POST["status"]);
		$zone->city = trim($_POST["City"]);
		$zone->cp = trim($_POST["CP"]);
		$zone->province = trim($_POST["Province"]);
		$zone->country = 'ESP';
		$zone->orderLimit = intval($_POST["OrderLimit"]);
		$zone->repLimit = intval($_POST["RepLimit"]);
		$zone->idsresponsables = $_POST["Responsable"];
		$zone->shipping = floatval($_POST["Shipping"]);
		$zone->type = trim($_POST["Type"]);
		$zone->time_delivery = $_POST["time_delivery"];
		$zone->time_check_order = floatval($_POST["time_check_order"]);
		$zone->time_orders_zones = trim($_POST["time_orders_zones"]);
		
		if($error == NULL) {

			$idNew = $zone->add();

			if($idNew > 0) {
				
				disconnectdb($connectBD);
				$msg .= "Zona de reparto registrado correctamente";
				$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=edit&opt=".$opt."&id=".$idNew."&msg=".utf8_decode($msg);
				header($location);
			} else {
				disconnectdb($connectBD);
				$msg .= "Error al registrar la zona de reparto.";
				$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=create&opt=".$opt."&msg=".utf8_decode($msg);
				header($location);
			}
		} else {
			disconnectdb($connectBD);
			$msg .= "Error al registrar la zona de reparto.";
			$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=create&opt=".$opt."&msg=".utf8_decode($msg);
			header($location);
		}
		
	}else {
		disconnectdb($connectBD);
		$msg .= "Error al registrar la zona de reparto.";
		$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=create&opt=".$opt."&msg=".utf8_decode($msg);
		header($location);
	}
?>