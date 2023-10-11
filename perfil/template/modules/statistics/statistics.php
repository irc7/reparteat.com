<?php	
//La session se comprueba en el index	
	$supObj = new Supplier();
	$proObj = new Product();
	$orderObj = new Order();
	$userObj = new UserWeb();
	$zObj = new Zone();
	if(isset($_GET["z"])) {
		$idZone = intval($_GET["z"]);
	}else {
		$idZone = 0;
	}
	if(isset($_GET["sup"])) {
		$idSupplier = intval($_GET["sup"]);
	}else {
		$idSupplier = 0;
	}

	$now = new DateTime();
	
	if (isset($_GET['filterstart']) && isset($_GET['filterfinish']) && trim($_GET['filterstart']) != "" && trim($_GET['filterfinish']) != "") {
		$filterstart = trim($_GET['filterstart']);
		$dateCheckStart = new DateTime($filterstart);
		$filterstringStart = $dateCheckStart->format("Y-m-d");
		
		$filterfinish = trim($_GET['filterfinish']);
		$dateCheckFinish = new DateTime($filterfinish);
		$filterstringFinish = $dateCheckFinish->format("Y-m-d");
	}else {
		$filterstart = "";
		$filterfinish = "";
		$dateCheck = new DateTime();
		$filterstringStart = $dateCheck->format("Y-m-d");
		$filterstringFinish = $dateCheck->format("Y-m-d");
	}
	
	$orders = array();
	if($_SESSION[nameSessionZP]->IDTYPE == 5 && $tpl == "day" && $idZone > 0 && $zObj->isUserWebZone($idZone, $_SESSION[nameSessionZP])) {
		$msgError = NULL;
		if(isset($filterstringStart) && trim($filterstringStart) != "" && isset($filterstringFinish) && trim($filterstringFinish) != "") {
			$Suppliers = $zObj->listSupplierZone($idZone);
			if($idZone != 0){
				$orders = $orderObj->infoOrderZoneByFilterRango($filterstringStart, $filterstringFinish, $tpl, $Suppliers, $idZone);
			}else{
				$orders = $orderObj->infoOrderByFilterRango($filterstringStart, $filterstringFinish, $tpl, $Suppliers);
			}	
			
			require("template/modules/statistics/orderszoneday.tpl.php");
		}else {
			require("template/modules/error.tpl.php");	
		}
			
	}else if($_SESSION[nameSessionZP]->IDTYPE == 2 && $tpl == "day" && isset($_GET["sup"]) && intval($_GET["sup"]) > 0 && $supObj->isUserWebSupplier(intval($_GET["sup"]), $_SESSION[nameSessionZP])){
		$msgError = NULL;
		if(isset($filterstringStart) && trim($filterstringStart) != "" && isset($filterstringFinish) && trim($filterstringFinish) != "") {
			$Suppliers[] = $supObj->infoSupplierById(intval($_GET["sup"]));
			if($idZone != 0){
				$orders = $orderObj->infoOrderZoneByFilterRango($filterstringStart, $filterstringFinish, $tpl, $Suppliers, $idZone);
			}else{
				$orders = $orderObj->infoOrderByFilterRango($filterstringStart, $filterstringFinish, $tpl, $Suppliers);
			}
			if(isset($_GET["filter"]) && trim($_GET["filter"]) == "sumary") {
				require("template/modules/statistics/orderssupday.tpl.php");
			}else{
				require("template/modules/statistics/orderszoneday.tpl.php");
			}
		}else {
			require("template/modules/error.tpl.php");	
		}


	}else{
			require("template/modules/error.tpl.php");
	}		
	

	
?>