<?php
setlocale(LC_TIME, "es_ES");
date_default_timezone_set("Europe/Paris");
require_once ("api.php");
require_once ("helpers.php");
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
        $user = UserWeb::infoUserWebByLogin($token->data->email);
        $orderObj = new Order();
        $supObj = new Supplier();
        $userObj = new UserWeb();
        //var_dump($_SESSION[nameSessionZP]);exit();
        if(!isset($_GET["ref"])) //si no trae ref es listado
        {
            $orders = array();
            if($user->IDTYPE == 5 && $_GET["type"] == "zone") {
                $zObj = new Zone();
                $idZone = $_GET["zone"];
                if($idZone > 0 && $zObj->isUserWebZone($idZone, $user)) { 
                    $orders = array();
                    $filter = "";
                    $msgError = NULL;
                    $Suppliers = $zObj->listSupplierZone($idZone);		
                    if(isset($_GET['filter']) && trim($_GET['filter']) != "") {
                        $filter = trim($_GET['filter']);
                    }else{
                        $filter = "";
                    }
                    if(count($Suppliers) > 0) {
                        if($filter == "follow") {
                            $orders = $orderObj->followOrderZone($Suppliers);
                        }else {
							if(!empty($filter))
								$orders = orderByZoneAPI($Suppliers, $filter);
							else
								$orders = orderByZoneAPI($Suppliers, "");
                        }
                    }else {
                        api::sendError("No existen restaurantes activos en la zona");
                    }	
                }else {
                    api::sendError("Sin acceso");	
                }
            }else if($user->IDTYPE == 2 && $_GET["type"] == "supplier"){ 
                $Suppliers_list = UserWeb::getUserWebInfoSupplier($user->ID);
                if(count($Suppliers_list)>0)
                {
                    //$idSupplier=$Suppliers_list[0]->ID;
                    $idSupplier = intval($_GET["sup"]);
                    if($supObj->isUserWebSupplier($idSupplier, $user)) { 
                        $orders = array();
                        $filter = "";
                        if(isset($_GET["filter"]) && trim($_GET["filter"]) != "") {
                            $filter = trim($_GET["filter"]);
                            if($filter == "pending") {
                                $filter = "2-3";
                            }
                        }
                        $orders = orderBySupplier($idSupplier, $filter);
                    }else {
                        api::sendError("Sin acceso");
                    }
                }else{
                    api::sendError("Sin acceso");
                }
                
            }else if($user->IDTYPE == 3 && $_GET["type"] == "delivery"){
                $filter = "";
                $orders = array();
                if(isset($_GET["filter"]) && trim($_GET["filter"]) != "") {
                    $filter = trim($_GET["filter"]);
                    if($filter == "to-deliver") { 
                        $filter = "3-4-5";
                        $orders = orderByRep($user->ID, $filter);
                    }elseif($filter == "no-shipping") {
                        $filter = -1;
                        $orders = array();
                        $ind = 0;
                        $dateNow = new DateTime();
                        $startTime = new DateTime();
                        $timeStimed = $startTime->getTimestamp();
                        $startTimeHour = new DateTime($startTime->format('Y-m-d H:00:00'));
                        $timeInitSeg = $startTimeHour->getTimestamp();
                        $totalFranjas = 0;
                        $finishTimeHour = new DateTime($startTime->format('Y-m-d 23:59:59'));
                        $timeFinishSeg = 3600+$finishTimeHour->getTimestamp();//+ una hora
                        $cont = 0;
                        while(($timeInitSeg - timeFranjas) < $timeFinishSeg) {
                            if($timeInitSeg > $timeStimed) {	
                                $franjaStart = new DateTime();
                                $franjaStart->setTimestamp($timeInitSeg - timeFranjas);
                                $franjaFinish = new DateTime();
                                $franjaFinish->setTimestamp($timeInitSeg);
                                $orders[$ind]["start"] = $franjaStart;
                                $orders[$ind]["finish"] = $franjaFinish;
                                $orders[$ind]["orders"] = $orderObj->orderByRepGroupByFranjas($user->ID, $franjaStart->format("Y-m-d H:i:s"), $franjaFinish->format("Y-m-d H:i:s"));
                                $ind++;
                            }
                            $timeInitSeg = $timeInitSeg + timeFranjas;
                            $cont++;
                        }
                    }else if($filter == "sumary") { 
                        /*if (isset($_GET['dateStart']) && isset($_GET['dateEnd'])) {
                            $dateStartG = trim($_GET['dateStart'].' 09:00:00');
                            $dateStart = new DateTime($dateStartG);
                            $dateEndG = trim($_GET['dateEnd']).' 23:59:59';
                            $dateEnd = new DateTime($dateEndG);
                        }else {
                            $now = new DateTime();
                            $dateEnd = new DateTime($now->format("Y-m-d 23:59:59"));
                            $daysSeg = 30*24*60*60;//tres dias en seg //LO CAMBIO A 30 DIAS
                            $startSeg = $dateEnd->getTimestamp()-$daysSeg;
                            $dateStart_aux = new DateTime();
                            $dateStart_aux->setTimestamp($startSeg);
                            $dateStart = new DateTime($dateStart_aux->format('Y-m-d 09:00:00'));
                        }
                        $dateStartString = $dateStart->format("Y-m-d");
                        $dateEndString = $dateEnd->format("Y-m-d");
                        $orders = $orderObj->infoOrderByRepThreeDay($dateStart, $dateEnd, $user->ID);*/
                        $filter = "6-7-8-9-10-11-12-13-14";
                    //echo $filter;
                        $orders = orderByRep($user->ID, $filter);
                    }
                }else{
                    $orders = orderByRep($user->ID, $filter);
                }
            }else if($user->IDTYPE == 4 && $_GET["type"] == "user"){
                
                $filter = "";
                if(isset($_GET["filter"]) && trim($_GET["filter"]) != "") {
                    $filter = trim($_GET["filter"]);
                    if($filter == "pending") {
                        $filter = "2-3-4-5";
                    }elseif($filter == "finish") {
                        $filter = "6-7-8-9-10-11-12-13-14";
                    }
                }
                $orders = array();
                $orders = $orderObj->orderByUser($user->ID, $filter);
            
            }else if($user->IDTYPE == 4 && $_GET["type"] == "order-follow"){
                $q = "select * from ".preBD."order_status where true order by ID asc";
                $r = checkingQuery($connectBD,$q);
                $statusList = array();
                $statusList[] = "";
                while($row = mysqli_fetch_object($r)) {
                    $statusList[] = $row;
                }
                $orders = array();
                $orders = $orderObj->orderFollow($user->ID);
            }else{
                api::sendError("Sin acceso");
            }
            //recorro cada pedido para añadirle info adicional
            $ar_orders = array();
            $i=0;
            if(is_array($orders)) {
                foreach($orders as $item) {
                    
                    if(is_array($item) && isset($item["data"]))
                        $item = $item["data"];
                    $ar_orders[$i] = $item;
                    if(isset($item->pedido))
                    {
                        $order_info = Order::infoOrderByRef($item->pedido);
                        //var_dump($order_info);
                        $ar_orders[$i]->order_info = $order_info;

                    }
                    else
                        $order_info = Order::infoOrderByRef($item->REF);
                    $supplier = $supObj->infoSupplierById($item->IDSUPPLIER);
                    $userOrder = $userObj->infoUserWebById($order_info->IDUSER);
                    if(!isset($item->IDREPARTIDOR)) 
                    {
                        
                        $item->IDREPARTIDOR = $order_info->IDREPARTIDOR;
                    }
                    if($item->IDREPARTIDOR > 0) {
                        $repOrder = $userObj->infoUserWebById($item->IDREPARTIDOR);
                    }
                    if($user->IDTYPE == 2 && $_GET["type"] == "supplier" && isset($_GET["sup"]) && intval($_GET["sup"]) > 0) {
                        //$userOrder = $userObj->infoUserWebById($item->IDUSER);
                        if($item->IDREPARTIDOR > 0) {
                            //$repOrder = $userObj->infoUserWebById($item->IDREPARTIDOR);
                            $repartidor = $repOrder->NAME." ".$repOrder->SURNAME; 
                        } else {
                            $repartidor = "Sin asignar"; 
                        }
                        $name = $userOrder->NAME ." ".$userOrder->SURNAME; 
                    }else{
                        $name = $supplier->TITLE; 
                    }
                    if(isset($item->IDMETHODPAY))
                        $method = $orderObj->orderMethodPay($item->IDMETHODPAY);
                    $date = new DateTime($item->DATE_CREATE);
                    $formatDate = $date->format("d-m-Y H:i:s"); 
                    if($item->SEND_START != null && $item->SEND_FINISH != NULL) 
                    {
                        $txt_delivery = "Entrega:". $orderObj->franjaInfo($item);
                    }
                    if(isset($item->STATUS))
                        $statusOrder = $orderObj->infoStatusOrder($item->STATUS);
                    else
                        $statusOrder = $orderObj->infoStatusOrder($item->idStatus);
                    $ar_orders[$i]->supplier = $supplier;
                    $ar_orders[$i]->deliverer = $repartidor;
                    $ar_orders[$i]->customer_data = $userOrder;
                    $ar_orders[$i]->deliver_data = $repOrder;
                    if(is_null($name))
                    {
                        $order_info = Order::infoOrderByRef($item->pedido);
                        $userOrder = $userObj->infoUserWebById($order_info->IDUSER);
                        $name = $userOrder->NAME." ".$userOrder->SURNAME;
                    }
                        
                    $ar_orders[$i]->name = $name;
                    $ar_orders[$i]->method = $method;
                    $ar_orders[$i]->formatDate = $formatDate;
                    $ar_orders[$i]->txt_delivery = $txt_delivery;
                    $ar_orders[$i]->statusOrder = $statusOrder;

                    //ajustes zone follow
                    if(isset($item->queda_cocina))
                    {
                        $ar_orders[$i]->queda_cocina = conversorSegundosHoras($item->queda_cocina*(-1));
                        $ar_orders[$i]->lleva_terminado = conversorSegundosHoras($item->lleva_terminado);
                        $ar_orders[$i]->queda_estimacion = conversorSegundosHoras($item->queda_estimacion*(-1));
                        $ar_orders[$i]->franja_txt = $orderObj->franjaInfoMin($item);
                    
                    }
                    //ajustes de color status
                    if($ar_orders[$i]->statusOrder->COLOR == 'yellow'){
                        $ar_orders[$i]->statusOrder->COLOR = '#e8b400';
                    }elseif($ar_orders[$i]->statusOrder->COLOR == 'orange'){
                        $ar_orders[$i]->statusOrder->COLOR = '#ee7d0c';
                    }elseif($ar_orders[$i]->statusOrder->COLOR == 'green'){
                        $ar_orders[$i]->statusOrder->COLOR = '#009975';
                    }elseif($ar_orders[$i]->statusOrder->COLOR == 'danger'){
                        $ar_orders[$i]->statusOrder->COLOR = '#e74a3b';
                    }
                     
                    $i++;
                }
            }
            http_response_code(200);
            echo json_encode($ar_orders);


        } else { //consulta ref pedido concreto
            // token test e5aYxQijRJqLyw3ZvyyF09:APA91bGYENjo1cLIWXCtnLK71_sBVcnjudpnFUe1UZmE_SNCDZrXCEFlRRVc6BPbXrFo_3w1WntdBb5EHz2oKHuRBblDxf69pl-hIB00wmcuP42HYf7DkUeFeH_p8ZLHOllqApXvrhST
//sendGCM("test", "test get pedido", "eXi-EOlaRCGbrau77aiUW5:APA91bGq_n03L--AkpxClhB_4-giTUAFtBnxFN29_EeX03SoKrDA5cZGLa9sls466Jv-3HL0UUQsMRw-TY9V0QQpSIeFA3GeSPdAaZL1T3GZh78XjyUsGd6ntg4qWangdOVS_qNXieYO");
//eXi-EOlaRCGbrau77aiUW5:APA91bGq_n03L--AkpxClhB_4-giTUAFtBnxFN29_EeX03SoKrDA5cZGLa9sls466Jv-3HL0UUQsMRw-TY9V0QQpSIeFA3GeSPdAaZL1T3GZh78XjyUsGd6ntg4qWangdOVS_qNXieYO
                                    //eFUqaj1SS1qvRx1LI1jHSe:APA91bHYuHpPwuphz6wzl952E4pU8yg5T88f7wt6S9Wg6dvDZKqfhv1cOrXm_y9vPG-Nztvw4P5tYLO_KPMnh2VGAc4F8Q9NSf2wRi-0ogSTUO1_Mo0QPrg3k1MgcNSxjXhgMaI9P1SK
            $ref = intval($_GET["ref"]);
			$ordObj = new Order();
			$order = $ordObj->infoOrderByRef($ref);
			$address = $ordObj->orderAddress($order->IDADDRESS);
			$methodPay = $ordObj->orderMethodPay($order->IDMETHODPAY);
			
			$supObj = new Supplier();
			$supplierCart = $supObj->infoSupplierById($order->IDSUPPLIER);
            $addressSup = $supObj->supplierAddress($supplierCart->ID);

            $franja = $ordObj->franjaInfo($order);

            $statusOrder = $ordObj->infoStatusOrder($order->STATUS);

            $proObj = new Product();
            $products = $ordObj->listProductOrder($order->ID);
            $subTotalOrder = 0;
            $res_products = array();
            $i=0;
            foreach($products as $item) {
                
                $product = $proObj->infoProductByIdNoStatus($item->IDPRODUCT);
                $icons = $proObj->productIcon($item->IDPRODUCT);
                $comps = $item->IDCOM;
                $compsArray = explode("#-#", $comps);
                            
                $subTotalOrder = $subTotalOrder + $item->COST;
                $ar_products[$i] = $item;
                $ar_products[$i]->TITLE = $product->TITLE;
                $txt_com = '';
                for($c=0;$c<count($compsArray);$c++) {
                    if($compsArray[$c]>0) {
                        $com = $proObj->productComsByIdCom($compsArray[$c]);
                        $txt_com .= $com->TITLE." ";
                    }
                }
                $ar_products[$i]->txt_com = $txt_com;
                $ar_icons = array();
                $ic=0;
                foreach($icons as $icon) 
                {
                    $ar_icons[$ic]->icon = "files/product/icon/1-".$icon->ICON;
                    $ar_icons[$ic]->title= $icon->TITLE;
                    $ic++;
                }
                $ar_products[$i]->icons = $ar_icons;
                $i++;
            }
            $res->customer_data = $userObj->infoUserWebById($order->IDUSER);
            if($order->IDREPARTIDOR > 0) {
                $res->deliver_data = $userObj->infoUserWebById($order->IDREPARTIDOR);
            }
            
            $res->order = $order;
            $res->address = $address;
            $res->metohdPay = $methodPay;
            $res->supplierCart = $supplierCart;
            $res->addressSup = $addressSup;
            $res->franja = $franja;
            $res->products = $ar_products;
            $res->statusOrder = $statusOrder;

            http_response_code(200);
            echo json_encode($res);
        }
        
    } else {
        api::sendError("Sin acceso");
    }
}

//POST METHOD: order modificar pedido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postdata = json_decode(file_get_contents("php://input"));
    $token = api::checkToken();
    if($token) {
        
        http_response_code(200);
        echo json_encode("");
    } else {
        api::sendError("Sin acceso");
    }
}



function orderByZoneAPI($Suppliers = null, $status = null) {
    global $connectBD;
    
    $statusArray = array();
    $statusArray = explode("-", $status);
    
    $data = array();
    if(count($Suppliers) > 0) {
        $q = "select * from ".preBD."order where true";
        for($i=0;$i<count($Suppliers);$i++) {
            if($i == 0) {
                $q .= " and(";
            }else {
                $q .= " or";
            }
            $q.= " IDSUPPLIER = '" . $Suppliers[$i]->ID . "'";
            if($i == count($Suppliers) - 1) {
                $q.=")";
            }
        }
        
        if(count($statusArray) > 0 && $status != "") {
            $q .= " and (";
            for($i=0;$i<count($statusArray);$i++) {
                $q .= "STATUS = '".$statusArray[$i]."'"; 
                if($i<count($statusArray)-1) {
                    $q .= " or ";
                }
            }
            $q .= ")";
        }
        $q .= " order by SEND_START desc LIMIT 100";	
        $r = checkingQuery($connectBD, $q);
        
        while($row = mysqli_fetch_object($r)) {
            $data[]=$row;
        } 
    }
    return $data;
        
}
function orderBySupplier($id = null, $status = null) {
    global $connectBD;
    
    $statusArray = array();
    $statusArray = explode("-", $status);
    
    $q = "select * from ".preBD."order where true and IDSUPPLIER = '" . $id . "'";
    if(count($statusArray) > 0 && $status != "") {
        $q .= " and (";
        for($i=0;$i<count($statusArray);$i++) {
            $q .= "STATUS = '".$statusArray[$i]."'"; 
            if($i<count($statusArray)-1) {
                $q .= " or ";
            }
        }
        $q .= ")";
    }
    $q .= " order by SEND_START desc LIMIT 100";

    $r = checkingQuery($connectBD, $q);
    
    $data = array();
    while($row = mysqli_fetch_object($r)) {
        $data[]=$row;
    } 
    return $data;
}
function orderByRep($id = null, $status = null) {
    global $connectBD;
    
    $statusArray = array();
    $statusArray = explode("-", $status);
    $dataAux = array();
    $data = array();
    if($status < 3 && $status != -1 && $status != "") {
        return $data;
    }
    $q = "select ".preBD."user_web_supplier_assoc.IDSUPPLIER 
            from ".preBD."user_web_supplier_assoc 
            inner join ".preBD."suppliers on ".preBD."user_web_supplier_assoc.IDSUPPLIER = ".preBD."suppliers.ID
            where true 
            and (".preBD."suppliers.STATUS = 1 or ".preBD."suppliers.STATUS = 2)
            and ".preBD."user_web_supplier_assoc.IDUSER = " . $id;
    $r = checkingQuery($connectBD, $q);
    $sup = array();
    while($row = mysqli_fetch_object($r)) {
        $sup[]=$row->IDSUPPLIER;
    }
    if(count($sup) == 0) {
        return $data;
    }
    $q = "select o.*, ".preBD."user_web.NAME, ".preBD."user_web.SURNAME, ".preBD."user_web.PHONE, ".preBD."user_sup_web_address.STREET, ".preBD."zone.CITY
        from ".preBD."order o INNER JOIN ".preBD."user_web ON o.IDUSER=".preBD."user_web.ID 
        INNER JOIN ".preBD."user_sup_web_address ON ".preBD."user_sup_web_address.ID=o.IDADDRESS 
        INNER JOIN ".preBD."zone ON ".preBD."zone.ID=".preBD."user_sup_web_address.IDZONE
        where true";
    if($status == -1) {
        $q .= " and o.IDREPARTIDOR = '0' and o.STATUS = 3";
    }else{
        $q .= " and o.IDREPARTIDOR = '" . $id . "'";
        if(count($statusArray) > 0 && $status != "") {
            $q .= " and (";
            for($i=0;$i<count($statusArray);$i++) {
                $q .= "o.STATUS = '".$statusArray[$i]."'"; 
                if($i<count($statusArray)-1) {
                    $q .= " or ";
                }
            }
            $q .= ")";
        }
    }
    if($status != "") {
        $q .= " and (";
        for($i=0;$i<count($sup);$i++) {
            $q .= "o.IDSUPPLIER = " . $sup[$i];
            if($i<count($sup)-1) {
                $q .= " or ";
            }
        }
        $q .= ")";
    }
    $q.=" order by o.SEND_START desc LIMIT 100";
    $r = checkingQuery($connectBD, $q);
    
    while($row = mysqli_fetch_object($r)) {
        $dataAux[]=$row;
    } 
    for($i=0;$i< count($dataAux);$i++) {
        if($dataAux[$i]->DATE_START != "0000-00-00 00:00:00") {
            $date = new DateTime($dataAux[$i]->DATE_START);
        }else {
            $date = new DateTime($dataAux[$i]->DATE_CREATE);
        }
        $timeHome = ($item->TIMESUPPLIER + $item->TIMEREPARTIDOR) * 60;//pasamos a segundos
        $segs = intval($date->getTimestamp()) + intval($timeHome);
        $dataAux[$i]->timeline = intval($date->setTimestamp($segs));
        
    }
    foreach($dataAux as $item) {
        $enc = false;
        $ind = 0;
        for($i=0;$i<count($data);$i++) {
            if($item->timeline < $data[$i]->timeline) {
                $enc = true;
                $ind = $i;
                break;
            }
        }
        if($enc) {
            for($j=count($data)-1;$j>=$ind;$j--) {
                $data[$j+1] = $data[$j];
            }
            $data[$ind] = $item;
        }else{
            $data[count($data)] = $item;
        }
    }
    return $data;
        
}

function conversorSegundosHoras($tiempo_en_segundos) {
    $horas = floor($tiempo_en_segundos / 3600);
    $minutos = floor(($tiempo_en_segundos - ($horas * 3600)) / 60);
    $segundos = $tiempo_en_segundos - ($horas * 3600) - ($minutos * 60);

    return $horas . ':' . $minutos . ":" . $segundos;
}

?>