<?php
	$supObj = new Supplier();
	$zObj = new Zone();
	$userObj = new UserWeb();
	$zones = array(); 
	$zones = $zObj->listZones(); 
	
	$catSupObj = new CategorySup();
	
	$sup = intval($_GET["sup"]);
	
	if(isset($_GET["z"]) && intval($_GET["z"]) > 0) {
		$idZone = intval($_GET["z"]);
	}else {
		$address = $supObj->supplierAddressAll($sup);
		$idZone = intval($address->IDZONE);
	}
	if(isset($_GET["sup"]) && intval($_GET["sup"]) > 0 && $supObj->isUserWebSupplier($sup, $_SESSION[nameSessionZP])) {
		$supplier = $supObj->infoSupplierById($sup);
		$address = $supObj->allSupplierAddress($sup);
		$cats = $supObj->infoCategories($sup);
		$userRep = array(); 
		$userRep = $userObj->listUserWebByType(3); 
		if(isset($_GET["tpl"]) && trim($_GET["tpl"]) == "profile") {
			$userSup = array(); 
			$userSup = $userObj->listUserWebByType(2); 

			$categories = array(); 
			$categories = $catSupObj->allCategories(); 
			
			$idPro = $supObj->infoSupplierUser($sup ,'proveedor');
			$idRep = $supObj->infoSupplierUserPosition($sup, 'repartidor');

			if($_SESSION[nameSessionZP]->IDTYPE == 2){
				require("template/modules/supplier/profile.tpl.php");
			}elseif($_SESSION[nameSessionZP]->IDTYPE == 5){
				require("template/modules/supplier/profile-all.tpl.php");
			}
		}else if(isset($_GET["tpl"]) && trim($_GET["tpl"]) == "delivery") {
			$action = trim($_GET["action"]);
			$zones = array(); 
			if($action == "create" && $_SESSION[nameSessionZP]->IDTYPE == 5) {
				$zones = $zObj->zonesByUserDisponibles($sup, $_SESSION[nameSessionZP]->ID); 
				
				require("template/modules/supplier/create.delivery.tpl.php");
			}else if ($action == "edit" && $zObj->isUserWebZone(intval($_GET["z"]), $_SESSION[nameSessionZP])) {
				$idzone = intval($_GET["z"]);
				$idRep = $supObj->infoSupplierUserPositionZone($sup, $idzone, 'repartidor');
				$addressBD = $supObj->supplierAddressZone($sup, $idzone);
				$zoneBD = $zoneObj->infoById($idzone);
					
				$timeControl = $supObj->supplierTimeControlZone($sup, $idzone);
				
				require("template/modules/supplier/edit.delivery.tpl.php");
			}
		}
	}else if(isset($_GET["tpl"]) && trim($_GET["tpl"]) == "create" && isset($_GET["z"]) && intval($_GET["z"]) > 0 && $zObj->isUserWebZone(intval($_GET["z"]), $_SESSION[nameSessionZP])) {
		if($zone = $zObj->infoZone($idZone)){
			$suppliers = $zObj->listSupplierZone($idZone);
			$categories = array(); 
			$categories = $catSupObj->allCategories(); 
			$userObj = new UserWeb();
			$userSup = array(); 
			$userSup = $userObj->listUserWebByType(2); 
			$userRep = array(); 
			$userRep = $userObj->listUserWebByType(3); 

			require("template/modules/supplier/create.tpl.php");
		}else{
			require("template/modules/error.tpl.php");
		}
	}else{
		require("template/modules/error.tpl.php");
	}
	
?>		
	