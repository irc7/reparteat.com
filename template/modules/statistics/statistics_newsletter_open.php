<?php
	header ('Content-type: text/html; charset=utf-8');
	$V_PHP = explode(".", phpversion());
	if($V_PHP[0]>=5){
		date_default_timezone_set("Europe/Paris");
	}
	require_once ("../../../pdc-ihp/includes/database.php");
	connectdb();
	require_once ("../../../pdc-ihp/includes/config.inc.php");
	require_once ("../../../includes/functions.inc.php");
	
	$idn = intval($_GET["idn"]);
	$subs = intval($_GET["subs"]);
	
	connectdb();
	$totalSusc = mysqli_num_rows(checkingQuery($connectBD,"select ID from ".preBD."subscriptions where ID = " . $subs . " and STATUS = 1"));
	$totalNews = mysqli_num_rows(checkingQuery($connectBD,"select ID from ".preBD."newsletter where ID = " . $idn));
	
	if($totalSusc > 0 && $totalNews > 0) {
		$q = "select * from ".preBD."statistics_newsletter_open where IDNEWSLETTER = " . $idn . " and IDSUBSCRIPTION = " . $subs;
		
		$result = checkingQuery($connectBD,$q);
		if($row = mysqli_fetch_object($result)) {
			$q = "UPDATE ".preBD."statistics_newsletter_open SET"; 
			$q .= " CONT = CONT+1";
			$q .= " where ID = " . $row->ID;
		} else {
			$q = "INSERT INTO `".preBD."statistics_newsletter_open`(`DATE`, `IDSUBSCRIPTION`, `IDNEWSLETTER`, `CONT`)";
			$q.= " VALUES";
			$q.= " (NOW(),".$subs.",".$idn.",'1')";
		}
		
		checkingQuery($connectBD,$q);
		
	}
	disconnectdb();
	if($totalSusc > 0 && $totalNews > 0) {
		echo DOMAIN."template/modules/newsletter/images/img_statistics.png";
	}
	
	
?>