<?php
session_start();
header ('Content-type: text/html; charset=utf-8');
$V_PHP = explode(".", phpversion());
if($V_PHP[0]>=5){
	date_default_timezone_set("Europe/Paris");
}
require_once ("../../../pdc-reparteat/includes/database.php");
$connectBD = connectdb();
require_once ("../../../pdc-reparteat/includes/config.inc.php");
require_once ("../../../includes/functions.inc.php");

require_once ("../../../includes/lib/Util/class.Util.php");
require_once "../../../includes/class/class.System.php";
require_once("../../../includes/class/Supplier/class.Supplier.php");
require_once("../../../includes/class/Product/class.Product.php");	
	
	
	if($_POST) {		
		$action = trim($_POST["action"]);
		$action = "delete";
		if($action == "delete") {
			$idSupplier = trim($_POST["idSupplier"]);
			$carrito = array();
			
			$carrito = $_SESSION[nameCartReparteat][$idSupplier];
			$indice = trim($_POST["idProduct"]);
			unset($carrito["data"][$indice]);
			
			$carrito["data"] = array_values($carrito["data"]);
			
			if(count($carrito["data"]) > 0) {
				$_SESSION[nameCartReparteat][$idSupplier] = $carrito;
			}else{
				unset($_SESSION[nameCartReparteat][$idSupplier]);
			}
			sleep(1);
			echo json_encode($carrito);
		}
	}
