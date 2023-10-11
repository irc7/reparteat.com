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
require "../includes/class/UserWeb/class.UserWeb.php";
require "../includes/class/Address/class.Address.php";
require "../includes/class/Order/class.Order.php";
include("../includes/tpv-redsys/apiRedsys.php");
require "../vendor/autoload.php";
use \Firebase\JWT\JWT;
use Zone;
use Supplier;
use api;
use Product;
use CategoryPro;
use UserWeb;
use Address;
use Order;

//GET METHOD: Zones
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $token = api::checkToken();
    if($token) {
        if(!isset($_GET["ref"])) //no trae product_id es listado
        {
            
            http_response_code(200);
            echo json_encode(array());
        } else { //consulta id
            //var_dump($_GET);
            
            //$q = "select * from ".preBD."tpv_configuration_prueba where ID > 0 and ID <=8";
            $q = "select * from ".preBD."tpv_configuration where ID > 0 and ID <=8";
            $r = checkingQuery($connectBD, $q);
            
            $config = array();
            while($row = mysqli_fetch_object($r)) {
                $config[$row->ID] = $row->CODE;
            }

            $miObj = new RedsysAPI;
            $ordObj = new Order();
            $ref = intval($_GET["ref"]);
            $res = (object)array();
            $order = $ordObj->infoOrderByRef($ref);
	        if($order) {
		
                $supObj = new Supplier();
                $supplierCart = $supObj->infoSupplierById($order->IDSUPPLIER);
            
                $proObj = new Product();
                
                $products = $ordObj->listProductOrder($order->ID);

                // Valores de entrada
                $fuc = $config[1];
                $terminal=$config[4];
                $moneda=$config[3];
                $trans=$config[5];
                $url=DOMAIN."template/modules/tpv/tpv.returnAPI.php";
                $urlOKKO = DOMAIN ;//. "pedido-realizado/" . $ref;
                
                $reference=$order->REF;//."_".rand(0,20);
                $amount=number_format($order->COST, 2, '', '');
                $description = "Pedido " . $order->REF . " en " . $supplierCart->TITLE;
                
                // Se Rellenan los campos
                //bizum
                if(isset($_GET["bizum"]))
                    $miObj->setParameter("DS_MERCHANT_PAYMETHODS","z");
                $miObj->setParameter("DS_MERCHANT_AMOUNT",strval($amount));
                $miObj->setParameter("DS_MERCHANT_ORDER",strval($reference));
                $miObj->setParameter("DS_MERCHANT_MERCHANTCODE",$fuc);
                $miObj->setParameter("DS_MERCHANT_CURRENCY",$moneda);
                $miObj->setParameter("DS_MERCHANT_TRANSACTIONTYPE",$trans);
                $miObj->setParameter("DS_MERCHANT_TERMINAL",$terminal);
                $miObj->setParameter("DS_MERCHANT_MERCHANTURL",$url);
                $miObj->setParameter("DS_MERCHANT_URLOK",$urlOKKO);		
                $miObj->setParameter("DS_MERCHANT_URLKO",$urlOKKO);
                $miObj->setParameter("DS_MERCHANT_PRODUCTDESCRIPTION",substr($description,0,124));//max 125 caracteres

                //Datos de configuración
                $version="HMAC_SHA256_V1";
                $kc = $config[2];//Clave recuperada de CANALES
                // Se generan los parámetros de la petición
                $request = "";
                $params = $miObj->createMerchantParameters();
                $signature = $miObj->createMerchantSignature($kc);

                $res->action = $config[8];
                $res->Ds_SignatureVersion = $version;
                $res->Ds_MerchantParameters = $params;
                $res->Ds_Signature = $signature;
            }
            
            http_response_code(200);
            echo json_encode($res);
        }        
    } else {
        api::sendError("Sin acceso");
    }
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