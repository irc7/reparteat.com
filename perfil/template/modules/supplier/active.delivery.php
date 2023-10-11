<?php
	
	session_start();
	header ('Content-type: text/html; charset=utf-8');
	$V_PHP = explode(".", phpversion());
	if($V_PHP[0]>=5){
		date_default_timezone_set("Europe/Paris");
	}
	require_once ("../../../../pdc-reparteat/includes/database.php");
	$connectBD = connectdb();
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	require_once ("../../../../pdc-reparteat/includes/config.inc.php");
	require_once("../../../../includes/functions.inc.php");
	require_once("../../../includes/functions.php");
	require_once("../head/strings.php");
	
	if(!isset($_SESSION[nameSessionZP]) || $_SESSION[nameSessionZP]->ID == 0) {
		header("Location: iniciar-sesion");
	}
	
	require_once("../../../../includes/class/class.System.php");
	
	require_once("../../../../includes/class/Supplier/class.Supplier.php");
	require_once("../../../../includes/class/Supplier/class.TimeControl.php");
	require_once("../../../../includes/class/Address/class.Address.php");
	require_once("../../../../includes/class/Zone/class.Zone.php");
	
	require_once ("../../../../includes/lib/Util/class.Util.php");
	require_once ("../../../../includes/lib/FileAccess/class.FileAccess.php");
	
	$msg = "";
	$error = 0;
	$idZone = intval($_GET["zone"]);
	$zObj = new Zone();
	
	$view = trim($_GET["view"]);
	$mod = trim($_GET["mod"]);
	$tpl = trim($_GET["tpl"]);
	
	if($_GET) {
		$supObj = new Supplier();
		$addressObj = new Address();
		$idSup = intval($_GET["supplier"]);
		$idZone = intval($_GET["zone"]);
		$idAddress = intval($_GET["address"]);
		$action = trim($_GET["action"]);
		
		if($supObj->isUserWebSupplier($idSup, $_SESSION[nameSessionZP])) {
			
			if($action == "close"){
				$active = 0;
				$msgAct = "no disponible";
			}else if($action == "open") {
				$active = 1;
				$msgAct = "disponible para pedidos";
			}
			if($addressObj->actionZoneActive($idZone, $idSup, $idAddress, $active)) {
				$msg = "Zona de reparto pasada a " . $msgAct;
			}else{
				$msg = "Se ha producido un error al actualizar la zona de reparto, por favor vuelva a intentarlo, si el problema persiste consulte con el administrador.";
			}
		}else{
			$error = 1;
			$msg.= NOACCESS;
		}
	}else{
		$error = 1;
		$msg.= NOPOST;
	}
	$_SESSION[msgError]["result"] = $error;
	$_SESSION[msgError]["msg"] = $msg;

	disconnectdb($connectBD);
	$location = "Location: " . DOMAINZP . "?view=".$view."&mod=".$mod."&tpl=".$tpl."&sup=".$idSup."&z=".$idZone;
	header($location);
	
?>