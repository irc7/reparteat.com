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
	require_once("../../includes/classes/Image/class.Image.php");
	require_once("../../includes/classes/Supplier/class.Supplier.php");
	require_once("../../includes/classes/Supplier/class.Category.php");
	require_once("../../includes/classes/Supplier/class.TimeControl.php");
	require_once("../../includes/classes/Address/class.Address.php");
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
		
		$idSup = intval($_POST["supplier"]);
		$idZone = intval($_POST["Zone"]);
		
		$supplier = new Supplier();
		
		$supBD = $supplier->infoSupplierById($idSup);
		
		$addressBD = array();
		$addressBD = $supplier->supplierAddressZone($idSup, $idZone);
		$addressObj = new Address();
		$addressObj->active = intval($_POST["Active"]);
		if($addressBD && $addressBD != null) {
			$addressObj->street = trim($_POST["Street"]);
			$addressObj->idzone = $idZone;
			
			$addressObj->update($addressBD->ID);
		} else {
			$addressObj->street = trim($_POST["Street"]);
			$addressObj->type = "proveedor";
			$addressObj->idassoc = $idSup;
			$addressObj->fav = 1;
			$addressObj->idzone = $idZone;
			
			if($addressObj->street != "" && $addressObj->idzone > 0) {
				$addressObj->add();
			}
		}
			
		$timeControl = new TimeControl();
		$timeControl->idassoc = $idSup;
		$timeControl->idzone = $idZone;
		$timeControl->type = "proveedor";
		$timeControlBD = $supplier->supplierTimeControlZone($idSup, $idZone);
		foreach($timeControlBD as $time) {
			if(isset($_POST["day-id-".$time->ID]) && isset($_POST["start-h-id-".$time->ID]) && isset($_POST["start-m-id-".$time->ID]) && isset($_POST["finish-h-id-".$time->ID]) && isset($_POST["finish-m-id-".$time->ID])) {
				$timeControl->day = $_POST["day-id-".$time->ID];
				$timeControl->start_h = $_POST["start-h-id-".$time->ID];
				$timeControl->start_m = $_POST["start-m-id-".$time->ID];
				$timeControl->finish_h = $_POST["finish-h-id-".$time->ID];
				$timeControl->finish_m = $_POST["finish-m-id-".$time->ID];
				$msg .= $timeControl->update($time->ID, $idSup);
			}else {
				$timeControl->deleteTimeControl($time->ID);
			}
		}
		
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
		$supplier->updateUserRep($idSup, $repartidor, $idZone);
		
		disconnectdb($connectBD);
		$msg .= "Zona de reparto modificada correctamente";
		$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=edit&opt=delivery&supplier=".$idSup."&zone=".$idZone."&msg=".utf8_decode($msg);
		header($location);
		
	}else {
		disconnectdb($connectBD);
		$msg .= "Error al modificar la zona de reparto.";
		$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=edit&opt=".$opt."&supplier=".$idSup."&zone=".$idZone."&msg=".utf8_decode($msg);
		header($location);
	}
?>