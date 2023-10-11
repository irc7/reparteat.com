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

	$now = new DateTime();
	if(isset($_GET["ref"]) && intval($_GET["ref"]) > 0) {
		
		$ref = intval($_GET["ref"]);
		
		$order = $orderObj->infoOrderByRef($ref);
		
		if($orderObj->checkViewOrder($_SESSION[nameSessionZP]->ID, $order->ID) || $orderObj->checkViewOrderZone($_SESSION[nameSessionZP], $order)) {
			$address = $orderObj->orderAddress($order->IDADDRESS);
			$idZone = $address->IDZONE;
			$methodPay = $orderObj->orderMethodPay($order->IDMETHODPAY);
			$userOrder = $userObj->infoUserWebById($order->IDUSER);
			$supplierCart = $supObj->infoSupplierById($order->IDSUPPLIER);
			$products = $orderObj->listProductOrder($order->ID);
			if($order->IDREPARTIDOR>0){
				$repOrder = $userObj->infoUserWebById($order->IDREPARTIDOR);
			}else{
				$rep = false;
			}
			require("template/modules/order/view.order.tpl.php");
		}else{
			require("template/modules/error.tpl.php");
		}
	}else {
		$orders = array();
		if($_SESSION[nameSessionZP]->IDTYPE == 5 && $tpl == "zone") {
			if($idZone > 0 && $zObj->isUserWebZone($idZone, $_SESSION[nameSessionZP])) {
				$orders = array();
				$filter = "";
				$msgError = NULL;
				$Suppliers = $zObj->listSupplierZone($idZone);		
				if(isset($_GET['filter']) && trim($_GET['filter']) != "") {
					$filter = trim($_GET['filter']);
				}else{
					$filter = "all";
				}
				if(count($Suppliers) > 0) {
					if($filter == "follow") {
						$orders = $orderObj->followOrderZone($Suppliers);
						require("template/modules/order/orderszonefollow.tpl.php");
					}else {
						//$orderObj->orderByZone($Suppliers, "");
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
							$dateCheckFinish = new DateTime();
							$dateCheckSeg = $dateCheckFinish->getTimestamp();
							
							$dateCheckStart = new DateTime();
							$segundos = 2592000; //30 dias
							$dateCheckStart->setTimestamp($dateCheckSeg-$segundos);
							
							$filterstringStart = $dateCheckStart->format("Y-m-d");
							$filterstringFinish = $dateCheckFinish->format("Y-m-d");
						}
						if($idZone != 0){
							$orders = $orderObj->orderByIdZoneFilter($Suppliers, "", $filterstringStart, $filterstringFinish, $idZone);
						}else{
							$orders = $orderObj->orderByZoneFilter($Suppliers, "", $filterstringStart, $filterstringFinish);
						}
						
						require("template/modules/order/orderszone.tpl.php");
					}
				}else {
					$msgError = "No existen restaurantes activos para esta zona";	
				}
				require("template/modules/order/error.tpl.php");	
			}else {
				require("template/modules/error.tpl.php");	
			}
		}else if($_SESSION[nameSessionZP]->IDTYPE == 2 && $tpl == "supplier"){ 
			$idSupplier = intval($_GET["sup"]);
			if(isset($_GET["sup"]) && intval($_GET["sup"]) > 0 && $supObj->isUserWebSupplier($idSupplier, $_SESSION[nameSessionZP])) {
				$orders = array();
				$filter = "";
				if(isset($_GET["filter"]) && trim($_GET["filter"]) != "") {
					$filter = trim($_GET["filter"]);
					if($filter == "pending") {
						$filter = "2-3";
					}
				}
				if(isset($_GET["filter"]) && trim($_GET["filter"]) == "pending") {
					$orders = $orderObj->orderBySupplierSend($idSupplier, $filter);
				}else {
					$orders = $orderObj->orderBySupplier($idSupplier, $filter);
				}
				require("template/modules/order/orders.tpl.php");
			}else {
				require("template/modules/error.tpl.php");	
			}
		}else if($_SESSION[nameSessionZP]->IDTYPE == 3 && $tpl == "delivery"){
			$filter = "";
			$orders = array();
			if(isset($_GET["filter"]) && trim($_GET["filter"]) != "") {
				$filter = trim($_GET["filter"]);
				if($filter == "to-deliver") { 
					$filter = "3-4-5";
					$orders = $orderObj->orderByRep($_SESSION[nameSessionZP]->ID, $filter);
					require("template/modules/order/orders-delivery.tpl.php");
				}elseif($filter == "no-shipping") {
					$filter = -1;
					$orders = array();
					$q = "select DISTINCT IDZONE from ".preBD."user_web_supplier_assoc where IDUSER = " .$_SESSION[nameSessionZP]->ID." and TYPE = 'repartidor'";
					$r = checkingQuery($connectBD, $q);
					$zonesRep = array();
					while($row = mysqli_fetch_object($r)) {
						$zonesRep[] = $row->IDZONE;
					}
					
					foreach($zonesRep as $zoneRep) {
						$infoZone = $zObj->infoZone($zoneRep);
						
						$ind = 0;
						$dateNow = new DateTime();
						
						$startTime = new DateTime();
						$timeStimed = $startTime->getTimestamp();

						$startTimeHour = new DateTime($startTime->format('Y-m-d H:00:00'));
						$timeInitSeg = $startTimeHour->getTimestamp();
						
						$totalFranjas = 0;
						
						$finishTimeHour = new DateTime($startTime->format('Y-m-d 23:59:59'));
						$timeFinishSeg = 3600+$finishTimeHour->getTimestamp();//+ una hora
						$cont = 0;
						$orders[$zoneRep]["info"] = $infoZone;
						$orders[$zoneRep]["view"] = false;
						while(($timeInitSeg - $infoZone->TIME_ORDERS_ZONES) < $timeFinishSeg) {
							if($timeInitSeg > $timeStimed) {	
								$franjaStart = new DateTime();
								$franjaStart->setTimestamp($timeInitSeg - $infoZone->TIME_ORDERS_ZONES);
								$franjaFinish = new DateTime();
								$franjaFinish->setTimestamp($timeInitSeg);
								$orders[$zoneRep]["items"][$ind]["start"] = $franjaStart;
								$orders[$zoneRep]["items"][$ind]["finish"] = $franjaFinish;
								$orders[$zoneRep]["items"][$ind]["orders"] = $orderObj->orderByRepGroupByFranjasZones($_SESSION[nameSessionZP]->ID, $zoneRep, $franjaStart->format("Y-m-d H:i:s"), $franjaFinish->format("Y-m-d H:i:s"));
								if(count($orders[$zoneRep]["items"][$ind]["orders"])>1) {
									$orders[$zoneRep]["view"] = true;
								}
								$ind++;
							}
							$timeInitSeg = $timeInitSeg + $infoZone->TIME_ORDERS_ZONES;
							$cont++;
						}
					}
					//pre($orders);
					/* Agrupados por zona
					$allOrders = $orderObj->orderByRepList1($_SESSION[nameSessionZP]->ID); 
					pre($allOrders);
					foreach($allOrders as $keyZone => $zoneOrder) {
						$zoneInfo = $zObj->infoZone($keyZone);
						$orders[$keyZone]["title"] = $zoneInfo->CITY."(".$zoneInfo->CP.")";
						$orders[$keyZone]["pendientes"] = 0;
						$orders[$keyZone]["cocina"] = 0;
						$orders[$keyZone]["supplier"] = array();
						foreach($zoneOrder as $keySup => $ord) {
							$orders[$keyZone]["supplier"][$keySup]["info"] = $supObj->infoSupplierById($keySup);
							$orders[$keyZone]["supplier"][$keySup]["order"] = array();
							foreach($ord as $item) {
								if($item->IDREPARTIDOR == 0 && $item->STATUS == 2) {
									$orders[$keyZone]["pendientes"]++;
								}elseif($item->IDREPARTIDOR == $_SESSION[nameSessionZP]->ID && $item->STATUS == 3){
									$orders[$keyZone]["cocina"]++;
								}
								//agrupar por franjas horarias
								$start = new DateTime($item->SEND_START);
								$finish = new DateTime($item->SEND_FINISH);

								$startSeg = $start->getTimestamp();
								$finishSeg = $finish->getTimestamp();
								$enc = false;
								
								foreach($orders[$keyZone]["supplier"][$keySup]["order"] as $keyCheck => $check) {
									$startCheck = new DateTime($check->SEND_START);
									$finishCheck = new DateTime($check->SEND_FINISH);

									$startCheckSeg = $startCheck->getTimestamp();
									$finishCheckSeg = $finishCheck->getTimestamp();

									if($startSeg == $startCheckSeg && $finishSeg == $finishCheckSeg){
										$ind = $keyCheck;
										$enc = true;
										break;
									}
								}
								if($enc) {
									$orders[$keyZone]["supplier"][$keySup]["order"][$ind][] = $item;
								}else{
									$orders[$keyZone]["supplier"][$keySup]["order"][$startSeg][] = $item;
								}
							}
							ksort($orders[$keyZone]["supplier"][$keySup]["order"]);
						}
					}
					*/
					require("template/modules/order/orders-delivery-list1.tpl.php");
						
				}else if($filter == "sumary") { 
					if (isset($_GET['dateStart']) && isset($_GET['dateEnd'])) {
						$dateStartG = trim($_GET['dateStart'].' 09:00:00');
						$dateStart = new DateTime($dateStartG);
						
						$dateEndG = trim($_GET['dateEnd']).' 23:59:59';
						$dateEnd = new DateTime($dateEndG);
					}else {
						$dateEnd = new DateTime($now->format("Y-m-d 23:59:59"));
						$daysSeg = 3*24*60*60;//tres dias en seg
						$startSeg = $dateEnd->getTimestamp()-$daysSeg;
						$dateStart_aux = new DateTime();
						$dateStart_aux->setTimestamp($startSeg);
						$dateStart = new DateTime($dateStart_aux->format('Y-m-d 09:00:00'));
					}
					$dateStartString = $dateStart->format("Y-m-d");
					$dateEndString = $dateEnd->format("Y-m-d");

					$orders = $orderObj->infoOrderByRepThreeDay($dateStart, $dateEnd, $_SESSION[nameSessionZP]->ID);
					
					require("template/modules/order/orders-delivery-sumary.tpl.php");
				}
			}else{
				$orders = $orderObj->orderByRep($_SESSION[nameSessionZP]->ID, $filter);
				require("template/modules/order/orders.tpl.php");
			}
			
		}else if($_SESSION[nameSessionZP]->IDTYPE == 4 && $tpl == "user"){
			
			$filter = "";
			if(isset($_GET["filter"]) && trim($_GET["filter"]) != "") {
				$filter = trim($_GET["filter"]);
				if($filter == "pending") {
					$filter = "2-3-4-5";
				}elseif($filter == "finish") {
					$filter = "6-7-8-9-10-11-12-13-14-15";
				}
			}
			$orders = array();
			$orders = $orderObj->orderByUser($_SESSION[nameSessionZP]->ID, $filter);
			require("template/modules/order/orders.tpl.php");
		
		}else if($_SESSION[nameSessionZP]->IDTYPE == 4 && $tpl == "order-follow"){
			$q = "select * from ".preBD."order_status where true order by ID asc";
			$r = checkingQuery($connectBD,$q);
			$statusList = array();
			$statusList[] = "";
			while($row = mysqli_fetch_object($r)) {
				$statusList[] = $row;
			}
			$orders = array();
			$orders = $orderObj->orderFollow($_SESSION[nameSessionZP]->ID);
			
			require("template/modules/order/follow.tpl.php");
			
		}else{
			require("template/modules/error.tpl.php");
		}		
	}

	
?>