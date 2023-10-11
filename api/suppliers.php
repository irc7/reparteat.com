<?php
setlocale(LC_TIME, "es_ES");
date_default_timezone_set("Europe/Paris");
require_once ("api.php");
require_once ("../pdc-reparteat/includes/database.php");
$connectBD = connectdb();
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
require_once ("../pdc-reparteat/includes/config.inc.php");
require_once ("../includes/functions.inc.php");
require_once ("../includes/checkSession.php");
require_once "../includes/class/class.System.php";
require "../includes/class/Zone/class.Zone.php";
require "../includes/class/Supplier/class.Supplier.php";
require "../includes/class/Supplier/class.CategorySup.php";
//require "../includes/class/Supplier/class.CategorySup.php";
//require "../includes/class/Supplier/class.TimeControl.php";
require "../includes/class/Product/class.CategoryPro.php";
require "../includes/class/Product/class.Product.php";
require "../vendor/autoload.php";
use \Firebase\JWT\JWT;
use Zone;
use Supplier;
use api;

//GET METHOD: Zones
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    /*$token = api::checkToken();
    if($token) {*/
        if(empty($_GET)) //no trae id
        {
            $cats = CategorySup::allCategories();
            http_response_code(200);
            echo json_encode($cats);
        } else { //consulta id
            $id = $_GET["id"];
            $supplier = Supplier::infoSupplierById($id);
			$supplier->ADDRESS = Supplier::supplierAddress($id);
			$supplier->CATSUP = Supplier::infoCategories($id);
            
            if(isset($_GET["zone"]))
            {
                $zone = $_GET["zone"];
                $tcs = Supplier::supplierTimeControlZone($id, $zone);
                foreach($tcs as $tc){
                    if($sw_first==0)
                        $zone = $tc->IDZONE;
                    $tc->ZONE = Zone::infoZone($tc->IDZONE);
                    $tc->TXT = $days[$tc->DAY - 1]." de ".str_pad($tc->START_H, 2,"0", STR_PAD_LEFT).":".str_pad($tc->START_M, 2,"0", STR_PAD_LEFT)." a ".str_pad($tc->FINISH_H, 2,"0", STR_PAD_LEFT).":".str_pad($tc->FINISH_M, 2,"0", STR_PAD_LEFT);
                    $supplier->TIMECONTROL[] = $tc;
                }
            }else{
                $sw_first=0;
                $zones = Zone::listZones();
                foreach($zones as $z)
                {
                    $tcs = Supplier::supplierTimeControlZone($id, $z->ID);
                    foreach($tcs as $tc){
                        if($sw_first==0)
                            $zone = $tc->IDZONE;
                        $tc->ZONE = Zone::infoZone($tc->IDZONE);
                        $tc->TXT = $days[$tc->DAY - 1]." de ".str_pad($tc->START_H, 2,"0", STR_PAD_LEFT).":".str_pad($tc->START_M, 2,"0", STR_PAD_LEFT)." a ".str_pad($tc->FINISH_H, 2,"0", STR_PAD_LEFT).":".str_pad($tc->FINISH_M, 2,"0", STR_PAD_LEFT);
                        $supplier->TIMECONTROL[] = $tc;
                    }
                }
            }
            
			$supplier->CATFILTER = CategoryPro::allCategoriesSupplier($id);
			
            $supplier->PRODUCTS = Product::listProductBySupplier($id);
            $timeSup = Supplier::checkingOpen($id, $zone); 
            if($supplier->STATUS == 1) {
                if($timeSup["status"] == 1) {
                    $color = "#009975";
                    $lock = false;
                    $textTime = "Abierto hasta las " . $timeSup["time"]->FINISH_H .":";
                    if(strlen($timeSup["time"]->FINISH_M) == 1) {
                        $textTime .= "0";
                    }
                    $textTime .= $timeSup["time"]->FINISH_M;
                    $textInfoTime = "Abierto";
                } else {
                    if($timeSup["time"] == null) {
                        $textTime = "Cerrado";
                        $color = "#F05F40";
                        $lock = true;
                    }else {
                        $color = "#e8b400";
                        $lock = true;
                        $textTime = "Abierto a partir de " . $timeSup["time"]->START_H .":";
                        if(strlen($timeSup["time"]->START_M) == 1) {
                            $textTime .= "0";
                        }
                        $textTime .= $timeSup["time"]->START_M;
                    }
                    $textInfoTime = "No se pueden tramitar pedidos hasta que el restaurante esté disponible para pedidos.";
                }
            }else{
                $textTime = "Cerrado";
                $color = "#F05F40";
                $lock = true;
                $textInfoTime = "El restaurante no se encuentra disponible para pedidos.";
            }
            $supplier->CARTTIME = (object)array("TXTTIME"=>$textTime, "TXTINFOTIME"=>$textInfoTime, "COLORTIME"=>$color, "LOCK"=>$lock);
            http_response_code(200);
            echo json_encode($supplier);
        }
        
    /*} else {
        api::sendError("Sin acceso");
    }*/
}

//POST METHOD: Zones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postdata = json_decode(file_get_contents("php://input"));
    /*$token = api::checkToken();
    if($token) {*/
        $suppliers = Supplier::listSupplier($postdata->id, "");
//        $suppliers = Zone::listSupplierZone($postdata->id);
        $p = 0;
        foreach($suppliers as $sup)
        {
            //disponibilidad
            $timeSup = Supplier::checkingOpen($sup->ID);
            if($sup->STATUS == 1) {
                if($timeSup["status"] == 1) {
                    $textTime = "Abierto";
                    $class="#009975";
                } else {
                    if($timeSup["time"] == null) {
                        $textTime = "Cerrado";
                        $class="#ff0000";
                    }else {
                        $textTime = "Abierto a partir de las " . $timeSup["time"]->START_H .":";
                        if(strlen($timeSup["time"]->START_M) == 1) {
                            $textTime .= "0";
                        }
                        $textTime .= $timeSup["time"]->START_M;
                        $class="#e8b400";
                    }
                }
            }else{
                $textTime = "Cerrado";
                $class="#ff0000";
            }
            $suppliers[$p]->TEXTTIME = $textTime;
            $suppliers[$p]->COLORTIME = $class;
            //categorias
            $catSup = Supplier::infoCategories($sup->ID);
            $catTxt = "";
            for($i=0;$i<count($catSup);$i++) { 
                $catTxt .= $catSup[$i]->TITLE;
                if($i < count($catSup)-1) {
                    $catTxt .= " • ";
                }
            }
            $suppliers[$p]->CATS = $catTxt;
            $p++;
        }
        http_response_code(200);
        echo json_encode($suppliers);
    /*} else {
        api::sendError("Sin acceso");
    }*/
}

?>