<?php 

function getRealIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP']))
        return $_SERVER['HTTP_CLIENT_IP'];
       
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
   
    return $_SERVER['REMOTE_ADDR'];
}
$ip = getRealIP();
//echo $ip;
$Sday = date('j');
$Smonth = date('n');
$Syear = date('Y');

//TODAS LAS TABLAS TRUNCADAS
$q = "select * from ".preBD."statistics";

$result = checkingQuery($connectBD,$q);
$star = mysqli_num_rows($result);

if($star == 0) {
	$start_statistics = true;	
} else {
	$start_statistics = false;	
}

$q = "select * from ".preBD."statistics where MONTH = " . $Smonth . " and YEAR = " . $Syear;

$result_s = checkingQuery($connectBD,$q);
$register = mysqli_fetch_assoc($result_s);

if($register) {
	$q = "select DAY from ".preBD."statistics_day order by ID desc limit 0,1";
	
	$result_day = checkingQuery($connectBD,$q);
	$web_day = mysqli_fetch_assoc($result_day);
	$last_day = $web_day["DAY"];
	if($Sday != $last_day) {
		$q = "insert into ".preBD."statistics_day (`DAY`, `VISITS`, `PAGES`) VALUES";
		$q.= " ('".$Sday."', '1', '1')";
		checkingQuery($connectBD,$q);
		
		$last_day = $Sday; 
	}
	
	$q = "select * from ".preBD."statistics_ip where IP = " . "'". $ip . "'";
	$q .= " and MONTH = " . $Smonth;
	$q .= " order by ID desc limit 0,1";
	
	$result_v = checkingQuery($connectBD,$q);
	$web_user = mysqli_fetch_assoc($result_v);
	
	$q = "update ".preBD."statistics_day SET"; 
	if($web_user["DAY"] != $Sday) {
		$q .= " VISITS=VISITS+1,";
	}
	$q .= " PAGES=PAGES+1";
	$q .= " WHERE DAY = " . $last_day;
	checkingQuery($connectBD,$q);
	
	if($web_user) {
		$visitors_increment = false;
		if($web_user["DAY"] != $Sday)  {
			$q = "update ".preBD."statistics_ip SET"; 
			$q .= " CONT = CONT + 1,";
			$q .= " DAY = " . $Sday;
			$q .= " WHERE ID = " . $web_user["ID"];
			checkingQuery($connectBD,$q);
		}else{
			$ultimo_acceso = $web_user["LAST_ACCESS"];
			$hora_fin = date('Y-n-j H:i:s', strtotime($ultimo_acceso." + 1 hours"));
			$ahora = date("Y-n-j H:i:s");
			$resultado = strtotime($ahora)-strtotime($ultimo_acceso);

			if($resultado > 3600){
				$q = "update ".preBD."statistics_ip SET"; 
				$q .= " CONT = CONT + 1,";
				$q .= " LAST_ACCESS = '" . $ahora."'";
				$q .= " WHERE ID = " . $web_user["ID"];
				checkingQuery($connectBD,$q);
			}else{
				$q = "update ".preBD."statistics_ip SET"; 
				$q .= " LAST_ACCESS = '" . $ahora."'";
				$q .= " WHERE ID = " . $web_user["ID"];
				checkingQuery($connectBD,$q);
			}
		}
	} else {
		$visitors_increment = true;
		$ahora = date("Y-n-j H:i:s");
		$q = "insert into ".preBD."statistics_ip (`IP`, `DAY`, `MONTH`, `CONT`, `LAST_ACCESS`) VALUES";
		$q.= " ('".$ip."', '".$Sday."', '".$Smonth."', '1','".$ahora."')";
		checkingQuery($connectBD,$q);
	}
	
	$q = "update ".preBD."statistics SET"; 
	if($visitors_increment) {
		$q .= " VISITORS=VISITORS+1,";
	}
	$q .= " PAGES=PAGES+1";
	$q .= " WHERE MONTH = " . $Smonth . " and YEAR = " . $Syear;
	checkingQuery($connectBD,$q);
} else { //NO EXISTE REGISTRO MENSUAL
	$q = "select CONT from ".preBD."statistics_ip";
	
	$result = checkingQuery($connectBD,$q);
	$visits = 0;
	while($web_users = mysqli_fetch_assoc($result)){
		$visits = $web_users["CONT"] + $visits;
	}
	$q = "TRUNCATE TABLE ".preBD."statistics_ip";
	checkingQuery($connectBD,$q);
	
	
	$q = "insert into ".preBD."statistics_ip (`IP`, `DAY`, `MONTH`) VALUES";
	$q.= " ('".mysqli_real_escape_string($connectBD,$ip)."', '".$Sday."', '".$Smonth."')";
	checkingQuery($connectBD,$q);
	
	
	$q = "TRUNCATE TABLE ".preBD."statistics_day";
	checkingQuery($connectBD,$q);
	$q = "insert into ".preBD."statistics_day (`DAY`, `VISITS`, `PAGES`) VALUES";
	$q.= " ('".$Sday."', '1', '1')";
	checkingQuery($connectBD,$q);
	
	
	if(!$start_statistics) {	
		$q = "select max(ID) as id from ".preBD."statistics";
		
		$result = checkingQuery($connectBD,$q);
		$max = mysqli_fetch_assoc($result);
		
		$q = "update ".preBD."statistics SET"; 
		$q .= " VISITS=".$visits;
		$q .= " WHERE ID = ".$max["id"];
		//echo $q;
		checkingQuery($connectBD,$q);
		
	}
	$q = "insert into ".preBD."statistics (`MONTH`, `YEAR`, `PAGES`, `VISITORS`) VALUES";
	$q.= " ('".$Smonth."', '".$Syear."', '1', '1')";
	checkingQuery($connectBD,$q);
}
require_once("template/modules/statistics/statistic.content.php");
require_once("template/modules/statistics/statistics_rrss.php");
require_once("template/modules/statistics/statistics_newsletter.php");
?>