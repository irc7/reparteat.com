<?php	
//La session se comprueba en el index	
	$supObj = new Supplier();
	$proObj = new Product();
	$orderObj = new Order();
	$userObj = new UserWeb();
	$zObj = new Zone();
	$reportObj = new Report();
	
	$now = new DateTime();
	if (isset($_GET['filter'])) {
		$filter = trim($_GET['filter']);
		$dateStartDay = new DateTime($filter." ".REPDAYS);
		$dateFinishDay = new DateTime($filter." ".REPDAYF);
		$dateStartNight = new DateTime($filter." ".REPNIGHTS);
		$dateFinishNight = new DateTime($filter." ".REPNIGHTF);
		
	}else {
		$filter = $now->format("Y-m-d");
		$dateStartDay = new DateTime($now->format("Y-m-d")." ".REPDAYS);
		$dateFinishDay = new DateTime($now->format("Y-m-d")." ".REPDAYF);
		$dateStartNight = new DateTime($now->format("Y-m-d")." ".REPNIGHTS);
		$dateFinishNight = new DateTime($now->format("Y-m-d")." ".REPNIGHTF);
	}
	$filterstring = $filter; 
	$dateCheck = new DateTime($filter. " 00:00:00");
	$orders = array();

	if($_SESSION[nameSessionZP]->IDTYPE == 3) {
		$reports = array();
		$ind = 0;
		$reportBD = $reportObj->checkingReportUser($filter, $_SESSION[nameSessionZP]->ID);
		$orders = array();
		$ordersDay = array();
		$ordersNight = array();
		$msgError = NULL;
		$ordersDay = $orderObj->infoOrderByRepThreeDay($dateStartDay, $dateFinishDay, $_SESSION[nameSessionZP]->ID);
		$ordersNight = $orderObj->infoOrderByRepThreeDay($dateStartNight, $dateFinishNight, $_SESSION[nameSessionZP]->ID);

		$orders = array_merge($ordersDay, $ordersNight);
		$totals = array();
		$totals = $orderObj->calculateTotalOrders($orders);
		
		if($reportBD) {
			$reports[0]['id'] = $reportBD->ID;
			$reports[0]['idRep'] = $reportBD->IDREP;
			$reports[0]['name'] = $reportBD->NAME;
			$reports[0]['date'] = new DateTime($reportBD->DATECREATE);
			if(count($ordersDay)>0){
				$reports[0]['day'] = 1;
				$reports[0]['orderDay'] = count($ordersDay);
			}else {
				$reports[0]['day'] = 0;
				$reports[0]['orderDay'] = 0;
			}
			if(count($ordersNight)>0){ 
				$reports[0]['night'] = 1;
				$reports[0]['orderNight'] = count($ordersNight);
			}else {
				$reports[0]['night'] = 0;
				$reports[0]['orderNight'] = 0;
			}
			$reports[0]['salaryDay'] = $reportBD->SALARYDAY;
			$reports[0]['salaryNight'] = $reportBD->SALARYNIGHT;
			$reports[0]['payCash'] = $totals['cash'];
			$reports[0]['payTPV'] = $totals['tpv'];
			$reports[0]['cost'] = $reportBD->COST;
			$reports[0]['text'] = $reportBD->TEXT;
			$reports[0]['total'] = $totals['total'] - $reportBD->COST - $reportBD->SALARYDAY - $reportBD->SALARYNIGHT;
			$reports[0]['type'] = "bd";
		}else {
			$reports[0]['id'] = 0;
			$reports[0]['idRep'] = $_SESSION[nameSessionZP]->ID;
			$reports[0]['name'] = $_SESSION[nameSessionZP]->NAME ." ". $_SESSION[nameSessionZP]->SURNAME;
			$reports[0]['date'] = $dateCheck;
			if(count($ordersDay)>0){
				$reports[0]['day'] = 1;
				$reports[0]['orderDay'] = count($ordersDay);
			}else {
				$reports[0]['day'] = 0;
				$reports[0]['orderDay'] = 0;
			}
			if(count($ordersNight)>0){ 
				$reports[0]['night'] = 1;
				$reports[0]['orderNight'] = count($ordersNight);
			}else {
				$reports[0]['night'] = 0;
				$reports[0]['orderNight'] = 0;
			}
			$reports[0]['salaryDay'] = 0;
			$reports[0]['salaryNight'] = 0;
			$reports[0]['payCash'] = $totals['cash'];
			$reports[0]['payTPV'] = $totals['tpv'];
			$reports[0]['cost'] = 0;
			$reports[0]['text'] = "";
			$reports[0]['total'] = $totals['total'] - $reports[0]['cost'] - $reports[0]['salaryDay'] - $reports[0]['salaryNight'];
			$reports[0]['type'] = "new";
		}
		//pre($reports);
		
		require("template/modules/report/report.tpl.php");
	}else if($_SESSION[nameSessionZP]->IDTYPE == 5 || $_SESSION[nameSessionZP]->IDTYPE == 1) {
		if(isset($_GET["z"])) {
			$idZone = intval($_GET["z"]);
		}else {
			$idZone = 0;
		}
		if(($_SESSION[nameSessionZP]->IDTYPE == 5 && $idZone > 0 && $zObj->isUserWebZone($idZone, $_SESSION[nameSessionZP])) || $_SESSION[nameSessionZP]->IDTYPE == 1) {
			
			$reps = $orderObj->repartidoresDay($filter, $idZone);
		
			
			$reports = array();
			$ind = 0;
			for($i=0;$i<count($reps);$i++) {
				$userRep = $userObj->infoUserWebById($reps[$i]);

				$reportBD = $reportObj->checkingReportUser($filter, $userRep->ID);
				$orders = array();
				$ordersDay = array();
				$ordersNight = array();
				$msgError = NULL;
				$ordersDay = $orderObj->infoOrderByRepThreeDay($dateStartDay, $dateFinishDay, $userRep->ID);
				$ordersNight = $orderObj->infoOrderByRepThreeDay($dateStartNight, $dateFinishNight, $userRep->ID);
				$orders = array_merge($ordersDay, $ordersNight);
				$totals = array();
				$totals = $orderObj->calculateTotalOrders($orders);
				
				if($reportBD) {
					$reports[$i]['id'] = $reportBD->ID;
					$reports[$i]['idRep'] = $reportBD->IDREP;
					$reports[$i]['name'] = $reportBD->NAME;
					$reports[$i]['date'] = new DateTime($reportBD->DATECREATE);
					if(count($ordersDay)>0){
						//$reports[$i]['day'] = 1;
						//$reports[$i]['orderDay'] = count($ordersDay);
						$reports[$i]['day'] = 1;
						$reports[$i]['orderDay'] = count($ordersDay);
					}else {
						//$reports[$i]['day'] = $reportBD->DAY;
						//$reports[$i]['orderDay'] = $reportBD->ORDERDAY;
						$reports[$i]['day'] = 0;
						$reports[$i]['orderDay'] = 0;
					}
					if(count($ordersNight)>0){
						//$reports[$i]['night'] = 1;
						//$reports[$i]['orderNight'] = count($ordersNight);
						$reports[$i]['night'] = 1;
						$reports[$i]['orderNight'] = count($ordersNight);
					}else{
						//$reports[$i]['night'] = $reportBD->NIGHT;
						//$reports[$i]['orderNight'] = $reportBD->ORDERNIGHT;
						$reports[$i]['night'] = 0;
						$reports[$i]['orderNight'] = 0;
					}
					$reports[$i]['salaryDay'] = $reportBD->SALARYDAY;
					$reports[$i]['salaryNight'] = $reportBD->SALARYNIGHT;
					//$reports[$i]['payCash'] = $reportBD->PAYCASH;
					//$reports[$i]['payTPV'] = $reportBD->PAYTPV;
					$reports[$i]['payCash'] = $totals['cash'];
					$reports[$i]['payTPV'] = $totals['tpv'];
					$reports[$i]['cost'] = $reportBD->COST;
					$reports[$i]['text'] = $reportBD->TEXT;
					$reports[$i]['total'] = $totals['total'] - $reportBD->COST - $reportBD->SALARYDAY - $reportBD->SALARYNIGHT;
					$reports[$i]['type'] = "bd";
					
				}else {
					$reports[$i]['id'] = 0;
					$reports[$i]['idRep'] = $userRep->ID;
					$reports[$i]['name'] = $userRep->NAME ." ". $userRep->SURNAME;
					$reports[$i]['date'] = $dateCheck;
					if(count($ordersDay)>0){
						$reports[$i]['day'] = 1;
						$reports[$i]['orderDay'] = count($ordersDay);
					}else {
						$reports[$i]['day'] = 0;
						$reports[$i]['orderDay'] = 0;
					}
					if(count($ordersNight)>0){
						$reports[$i]['night'] = 1;
						$reports[$i]['orderNight'] = count($ordersNight);
					}else {
						$reports[$i]['night'] = 0;
						$reports[$i]['orderNight'] = 0;
					}
					$reports[$i]['salaryDay'] = 0;
					$reports[$i]['salaryNight'] = 0;
					$reports[$i]['payCash'] = $totals['cash'];
					$reports[$i]['payTPV'] = $totals['tpv'];
					$reports[$i]['cost'] = 0;
					$reports[$i]['text'] = "";
					$reports[$i]['total'] = $totals['total'] - $reports[$i]['cost'] - $reports[$i]['salaryDay'] - $reports[$i]['salaryNight'];
					$reports[$i]['type'] = "new";
				}
			}
			require("template/modules/report/report.tpl.php");
		} else {
			require("template/modules/error.tpl.php");	
		}
	}else{
		require("template/modules/error.tpl.php");
	}		
	

	
?>