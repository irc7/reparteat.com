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
require_once("../../../includes/class/Zone/class.Zone.php");
require_once("../../../includes/class/Supplier/class.Supplier.php");
require_once("../../../includes/class/Product/class.Product.php");	
	
	if($_POST) {		
		$action = trim($_POST["action"]);
		if(isset($_SESSION[sha1("zone")]) && intval($_SESSION[sha1("zone")])>0){
			$zoneObj = new Zone();
			$zoneAct = $zoneObj->infoZone($_SESSION[sha1("zone")]);
		}
		if($action == "add") {
			$cartProObj = new Product();
			$idSupplier = trim($_POST["idSupplier"]);
			$cartSupObj = new Supplier();
			$supplierCart = $cartSupObj->infoSupplierById($idSupplier);
			$timeSup = $cartSupObj->checkingOpen($idSupplier,intval($_SESSION[sha1("zone")])); 
			
			if(isset($_SESSION[nameCartReparteat][$idSupplier])){
				$carrito = $_SESSION[nameCartReparteat][$idSupplier];
				$carrito["inTime"] = $timeSup["status"];
			}else{
				$carrito = array();
				$carrito["id"] = $supplierCart->ID;
				$carrito["min"] = $supplierCart->MIN;
				$carrito["status"] = $supplierCart->STATUS;
				$carrito["inTime"] = $timeSup["status"];
				$carrito["shipping"] = $supplierCart->COST+$zoneAct->SHIPPING;
				$carrito["data"] = array();
			}
			$carrito["discount"] = 0;
			$item = array();
			$comps = array();
			$item['compsArray'] = array();
			$item['comp'] = "";
			$costComp = 0;
			if(isset($_POST["addCom"]) && trim($_POST["addCom"]) != "") {
				
				$comps = explode("-",$_POST["addCom"]);
				$costComps = explode("-",$_POST["costCom"]);
				sort($comps,SORT_NUMERIC);
				$item['compsArray'] = $comps;
				for($c=0;$c<count($comps);$c++) {
					
					$costComp = $costComp + floatval($costComps[$c]);
					$com = $cartProObj->productComsByIdCom($comps[$c]);
					$item['comp'] .=  $com->TITLE;
					if($c >= 0 && $c < count($comps)-1){
						$item['comp'] .=  " + ";
					}
				}
			}
			$item['id'] = trim($_POST["idProduct"]);
			
			$item['ud'] = intval($_POST["totalPro"]);
			
			$product = $cartProObj->infoProductByIdNoStatus($item["id"]);
			
			$item['title'] = $product->TITLE; 
			
			$item['cost'] = number_format(($item['ud'] * ($product->COST+$costComp)),2,".","");
			
			$enc = false;
			$indIncrement = 0;
			foreach($carrito["data"] as $ind => $elem) {
				if($elem['id'] == $item['id']) {
					if($elem['compsArray'] === $comps) {
						$enc = true;
						$indIncrement = $ind;
						break;
					}
				}
			}
			if($enc) {
				$carrito["data"][$indIncrement]['ud'] = $carrito["data"][$indIncrement]['ud'] + $item["ud"];	
				$carrito["data"][$indIncrement]['cost'] = number_format(($carrito["data"][$indIncrement]['ud'] * ($product->COST+$costComp)),2,".","");	
			}else{
				$carrito["data"][] = $item;	
			}
			$_SESSION[nameCartReparteat][$idSupplier] = $carrito;
			
			sleep(1);
			echo json_encode($carrito);
		}
	}
