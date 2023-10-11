<?php
	session_start();
	header ('Content-type: text/html; charset=utf-8');
	$V_PHP = explode(".", phpversion());
	if($V_PHP[0]>=5){
		date_default_timezone_set("Europe/Paris");
	}
	require_once ("../../../../pdc-reparteat/includes/database.php");
	$connectBD = connectdb();
	require_once ("../../../../pdc-reparteat/includes/config.inc.php");
	require_once ("../head/strings.php");
	require_once ("../../../../includes/functions.inc.php");
	require_once ("../../../includes/functions.php");

	

	require_once "../../../../includes/class/class.System.php";
	require_once("../../../../includes/class/UserWeb/class.UserWeb.php");	
	require_once("../../../../includes/class/Supplier/class.Supplier.php");
	require_once("../../../../includes/class/Product/class.Product.php");	
	
	$result = 0;
	$msg = "";
	if (isset($_POST['update'])) {
		$response = array();
		foreach($_POST['positions'] as $position) {
			$id = $position[0];
			$newPosition = $position[1];
			$q = ("UPDATE ".preBD."products SET POSITION = '".$newPosition."' WHERE ID = '".$id."'");
			print_r(checkingQuery($connectBD,$q));
		}
	}
?>

