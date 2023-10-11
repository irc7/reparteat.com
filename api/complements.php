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
//require "../includes/class/Supplier/class.CategorySup.php";
//require "../includes/class/Supplier/class.TimeControl.php";
require "../includes/class/Product/class.CategoryPro.php";
require "../includes/class/Product/class.Product.php";
require "../includes/class/Product/class.Component.php";
require "../vendor/autoload.php";
use \Firebase\JWT\JWT;
use Zone;
use Supplier;
use api;
use Product;
use CategoryPro;

//GET METHOD: Zones
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $token = api::checkToken();
    if($token) {
        if(!isset($_GET["com_id"])) //no trae product_id es listado
        {
            $comObj = new Component();
            $coms = array(); 
            $coms = $comObj->allComponent(); 
            http_response_code(200);
            echo json_encode($coms);
        } else { //consulta id
            
            http_response_code(200);
            echo json_encode($prod);
        }
        
    } else {
        api::sendError("Sin acceso");
    }
}

//POST METHOD: complements Uso el post para guardar la lista de ingredientes del producto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postdata = json_decode(file_get_contents("php://input"));
    $token = api::checkToken();
    if($token) {
        $product = new Product();
        addComponent($postdata->id, $postdata->coms);
        http_response_code(200);
        echo json_encode(array());
    } else {
        api::sendError("Sin acceso");
    }
}
function addComponent($id = null, $coms) {
    global $connectBD;
    $product = new Product();
    for($i=0;$i<count($coms);$i++) {
        if($product->checkingComponent($id, $coms[$i]->id) == 0) {
            $q = "INSERT INTO `".preBD."products_com_assoc`(`IDCOM`, `IDPRODUCT`, `TYPE`, `COST`) 
                    VALUES 
                    (".$coms[$i]->id.",".$id.",'".$coms[$i]->type."','".number_format($coms[$i]->cost,2,'.','')."')";
            checkingQuery($connectBD, $q);			
        }
    }
}

//PUT METHOD: complements Uso el put para guardar la lista de ingredientes del producto
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $postdata = json_decode(file_get_contents("php://input"));
    $token = api::checkToken();
    if($token) {
        $product = new Product();
        updateComponents($postdata->id, $postdata->coms);
        http_response_code(200);
        echo json_encode(array());
    } else {
        api::sendError("Sin acceso");
    }
}
function updateComponents($id = null, $coms) {
    global $connectBD;
    $product = new Product();
    $q = "DELETE FROM `".preBD."products_com_assoc` WHERE IDPRODUCT = " . $id;
    checkingQuery($connectBD, $q);
    for($i=0;$i<count($coms);$i++) {
        if($product->checkingComponent($id, $coms[$i]->ID) == 0) {
            $q = "INSERT INTO `".preBD."products_com_assoc`(`IDCOM`, `IDPRODUCT`, `TYPE`, `COST`) 
                    VALUES 
                    (".$coms[$i]->ID.",".$id.",'".$coms[$i]->TYPE."','".number_format($coms[$i]->COST,2,'.','')."')";
            checkingQuery($connectBD, $q);			
        }
    }
}

?>