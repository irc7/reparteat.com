<?php
	if(isset($_GET["idn"]) && isset($_GET["subs"])) {
		
		$idn = intval($_GET["idn"]);
		$subs = intval($_GET["subs"]);
		
		/*/Control de estadiscas
		$totalSusc = mysqli_num_rows(checkingQuery($connectBD,"select ID from ".preBD."subscriptions where ID = " . $subs . " and STATUS = 1"));
		$totalNews = mysqli_num_rows(checkingQuery($connectBD,"select ID from ".preBD."newsletter where ID = " . $idn));
		
		if($totalSusc > 0 && $totalNews > 0) {
			$q = "select * from ".preBD."statistics_newsletter_open where IDNEWSLETTER = " . $idn . " and IDSUBSCRIPTION = " . $subs;
			
			$result = checkingQuery($connectBD,$q);
			if($row = mysqli_fetch_object($result)) {
				$q = "UPDATE ".preBD."statistics_newsletter_open SET"; 
				$q .= " CONT = '" . (intval($row->CONT) + 1) . "'";
				$q .= " where ID = " . $row->ID;
			} else {
				$q = "INSERT INTO `".preBD."statistics_newsletter_open`(`DATE`, `IDSUBSCRIPTION`, `IDNEWSLETTER`, `CONT`)";
				$q.= " VALUES";
				$q.= " (NOW(),".$subs.",".$idn.",'1')";
			}
			checkingQuery($connectBD,$q)
		}
		*/
		$totalNews = mysqli_num_rows(checkingQuery($connectBD,"select ID from ".preBD."newsletter where ID = " . $idn));
		if($totalNews > 0) {
			$existNews = mysqli_num_rows(checkingQuery($connectBD,"select ID from ".preBD."statistics_newsletter where IDNEWSLETTER = " . $idn));
			if($existNews) {
				$q = "UPDATE `".preBD."statistics_newsletter` SET `CONT`= CONT + 1, `DATE` = NOW() WHERE IDNEWSLETTER = " . $idn;
				checkingQuery($connectBD,$q);
			}
		}
		$totalSusc = mysqli_num_rows(checkingQuery($connectBD,"select ID from ".preBD."subscriptions where ID = " . $subs));
		if($totalSusc > 0) {
			$existSt = mysqli_num_rows(checkingQuery($connectBD,"select ID from ".preBD."statistics_subscription where IDSUBSCRIPTION = " . $subs));
			if($existSt > 0) {
				$q = "UPDATE `".preBD."statistics_subscription` SET `CONT`= CONT + 1, `DATE` = NOW() WHERE IDSUBSCRIPTION = " . $subs;
				checkingQuery($connectBD,$q);
			}
		}
	}	
?>