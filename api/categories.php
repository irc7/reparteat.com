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
    //if($token) {
        if(!isset($_GET["cat_id"])) //no trae product_id es listado
        {
            $catObj = new CategoryPro();
		    $categories = array(); 
            $categories = $catObj->allCategories(); 
            http_response_code(200);
            echo json_encode($categories);
        } else { //consulta id
            
            http_response_code(200);
            echo json_encode($prod);
        }
        
    /*} else {
        api::sendError("Sin acceso");
    }*/
}

//POST METHOD: Zones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postdata = json_decode(file_get_contents("php://input"));
    $token = api::checkToken();
    if($token) {
        
        http_response_code(200);
        echo json_encode("{}");
    } else {
        api::sendError("Sin acceso");
    }
}

?>