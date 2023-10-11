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
	$idZone = intval($_POST["Zone"]);
	$zObj = new Zone();
	
	$view = trim($_POST["view"]);
	$mod = trim($_POST["mod"]);
	$tpl = trim($_POST["tpl"]);
	
	if($_POST) {
		$supplier = new Supplier();
		$idSup = intval($_POST["idsupplier"]);
		$idZone = intval($_POST["Zone"]);
		
		if($zObj->isUserWebZone($idZone, $_SESSION[nameSessionZP])) {
		
			$address = new Address();
			$address->active = intval($_POST["Active"]);
			$address->street = trim($_POST["Street"]);
			$address->type = "proveedor";
			$address->idassoc = $idSup;
			$address->fav = 1;
			$address->idzone = $idZone;
			
			if($address->street != "" || $address->idzone == 0) {
				$address->add();
			}
			
			$timeControl = new TimeControl();
			$timeControl->idassoc = $idSup;
			$timeControl->idzone = $idZone;
			$timeControl->type = "proveedor";
			for($i=1;$i<=20;$i++) {
				if(isset($_POST["day-".$i]) && isset($_POST["start-h-".$i]) && isset($_POST["start-m-".$i]) && isset($_POST["finish-h-".$i]) && isset($_POST["finish-m-".$i])) {
					$timeControl->day = $_POST["day-".$i];
					$timeControl->start_h = $_POST["start-h-".$i];
					$timeControl->start_m = $_POST["start-m-".$i];
					$timeControl->finish_h = $_POST["finish-h-".$i];
					$timeControl->finish_m = $_POST["finish-m-".$i];
			
					$msg .= $timeControl->add();
				}
			}

			$repartidor = array();
			$ir = 0;
			foreach($_POST["Repartidor"] as $itemRep) {
				$repartidor[$ir]["id"] = $itemRep;
				$repartidor[$ir]["p"] = intval($_POST["PosRep-".$itemRep]);
				$ir++;
			}

			$supplier->addUserRep($idSup, $repartidor, $idZone);
				
			$msg .= "Zona de reparto creada correctamente correctamente";
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
	if($error == 1) {
		$location = "Location: " . DOMAINZP . "?view=supplier&mod=supplier&tpl=delivery&action=create&sup=".$idSup;
	}else{
		$location = "Location: " . DOMAINZP . "?view=supplier&mod=supplier&tpl=delivery&action=edit&sup=".$idSup."&z=".$idZone;
	}
	header($location);
?>