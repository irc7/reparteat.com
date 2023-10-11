<?php 
	
	require_once ("includes/database.php");
	$connectBD = connectdb();
	require_once ("includes/config.inc.php");
	
	$V_PHP = explode(".", phpversion());
	if($V_PHP[0] >= 5){
		date_default_timezone_set("Europe/Paris");
	}
	$now = date('Y').date('m').date('d').date('H').date('i').date('s');
	$date_joker = "0000-00-00 00:00:00";
	
	
	if (!isset ($_GET["mnu"])) {
		$mnu = "default";
	}else{ 
		$mnu = $_GET["mnu"];
	}
	if (!isset ($_GET["com"])){
		$com = "home";
	}else {
		$com = $_GET["com"];
	}
	if (!isset ($_GET["tpl"])){
		$tpl = "default";
	}else {
		$tpl = $_GET["tpl"];
	}
	
	if (!isset($_GET["opt"])){
		$opt = NULL;
	}else {
		$opt = $_GET["opt"];
	}
	
	require_once ("includes/loadFunctions.php");
	
?>