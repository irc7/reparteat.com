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
require "../vendor/autoload.php";
use \Firebase\JWT\JWT;
use Zone;
use Supplier;
use api;

//GET METHOD: Zones
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    //$token = api::checkToken();
    //if($token) {
    if(!isset($_GET["id"]))
    {
        $zones = Zone::listZones();
        http_response_code(200);
        echo json_encode($zones);
    }else{
        $id = $_GET["id"];
        $zone = Zone::infoZone($id);
        http_response_code(200);
        echo json_encode($zone);
    }
    //} else {
    //    api::sendError("Sin acceso");
    //}
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
            $timeSup = Supplier::checkingOpen($sup->ID,$postdata->zone);
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
            //comprimo y envío en base64
            $file = $dirImg = "../files/supplier/thumb/1-".$suppliers[$p]->IMAGE;
            if(strpos($suppliers[$p]->IMAGE,"png"))
                $image = imagecreatefrompng($file);
            else
                $image = imagecreatefromjpeg($file);
            $image = imagescale($image , 500);
            ob_start();
            imagejpeg($image);
            $contents = ob_get_contents();
            ob_end_clean();
            $suppliers[$p]->IMAGEB64 = "data:image/jpeg;base64,".base64_encode($contents);
            //LogoB64
            $file = $dirImg = "../files/supplier/thumb/1-".$suppliers[$p]->LOGO;
            if(strpos($suppliers[$p]->LOGO,"png"))
                $image = imagecreatefrompng($file);
            else
                $image = imagecreatefromjpeg($file);
            $image = imagescale($image , 250);
            ob_start();
            imagejpeg($image);
            $contents = ob_get_contents();
            ob_end_clean();
            $suppliers[$p]->LOGOB64 = "data:image/jpeg;base64,".base64_encode($contents);
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
