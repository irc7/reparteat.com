<?php
	session_start();
	header ('Content-type: text/html; charset=utf-8');
	$V_PHP = explode(".", phpversion());
	
	if($V_PHP[0]>=5){
		date_default_timezone_set("Europe/Paris");
	}
	require_once ("../pdc-reparteat/includes/database.php");
	$connectBD = connectdb();
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	require_once ("../pdc-reparteat/includes/config.inc.php");
	require_once ("../includes/functions.inc.php");
	
	$codeCookie = $_SESSION[nameSessionZP]->PASS . "%#-#%" . $_SESSION[nameSessionZP]->ID;
	$dateEnd = new DateTime();
	$dateEndSeg = $dateEnd->getTimestamp() - timeCookie;
	setcookie(nameCookie, $codeCookie, $dateEndSeg,  "/");
	//unset($_SESSION[nameCartReparteat]);
	unset($_SESSION[nameSessionZP]);
	
	header("Location: iniciar-sesion");
?>