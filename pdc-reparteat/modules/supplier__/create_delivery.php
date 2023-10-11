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
		$supplier = new Supplier();
		$idSup = intval($_POST["idsupplier"]);
		$idZone = intval($_POST["Zone"]);
		if($idSup > 0) {
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
			if(count($repartidor) > 0) {
				$supplier->addUserRep($idSup, $repartidor,$idZone);
			}

			disconnectdb($connectBD);
			$msg .= "Zona de reparto creada correctamente correctamente";
			$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=edit&opt=delivery&supplier=".$idSup."&zone=".$idZone."&msg=".utf8_decode($msg);
			header($location);
		} else {
			disconnectdb($connectBD);
			$msg .= "No se ha seleccionado ningún proveedor.";
			$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=create&opt=".$opt."&msg=".utf8_decode($msg);
			header($location);
		}
		
	}else {
		disconnectdb($connectBD);
		$msg .= "Error al registrar el proveedor.";
		$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=create&opt=".$opt."&msg=".utf8_decode($msg);
		header($location);
	}
?>