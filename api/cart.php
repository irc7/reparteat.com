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
require_once("../includes/class/TelegramBot/class.TelegramBot.php");
require_once("../includes/class/class.phpmailer.php");
require_once("../includes/class/class.smtp.php");
require_once ("../lib/Util/class.Util.php");
require_once ("../lib/FileAccess/class.FileAccess.php");

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
        if(!isset($_GET["id"])) //no trae supplier 
        {
            
            http_response_code(200);
            echo json_encode(array($prod));
        } else { //consulta id
            //var_dump($_GET);
            
            $user = UserWeb::infoUserWebByLogin($token->data->email);
            $saldoNew = UserWeb::checkingSaldo($user->ID);
            $supplierCart = Supplier::infoSupplierById($_GET["id"]);
		    $addressSup = Supplier::supplierAddress($supplierCart->ID); 
            $zoneInfo = Zone::infoZone($_GET["zone"]);
            $usersRep = Supplier::infoSupplierUserZone($_GET["id"], 'repartidor',$_GET["zone"]);
            //$maxOrderZone = $zoneInfo->ORDER_LIMIT * $zoneInfo->REP_LIMIT;
            ////$maxOrderZone = $zoneInfo->ORDER_LIMIT * count($usersRep);
            $maxOrderZone = ($zoneInfo->ORDER_LIMIT * count($usersRep)) + $supplierCart->EXTRA_ORDER;
            $address = UserWeb::userWebAddressZone($user->ID, $_GET["zone"]);
            $points = Address::listPointsAddress($zoneInfo->ID);
            $methodPay = Order::listMethodPay();

			$dateNow = new DateTime();
			$timeStimed = (($supplierCart->TIME+timeRe) * 60)+$dateNow->getTimestamp();
			
			$startTime = new DateTime();
			$startTime->setTimestamp($timeStimed);
			
			$startTimeHour = new DateTime($dateNow->format('Y-m-d H:00:00'));
			$timeInitSeg = $startTimeHour->getTimestamp();
			
            if($zoneInfo->TYPE == 'central'){
                $startTime->setTimestamp($timeStimed);
                $startTimeHour = new DateTime($dateNow->format('Y-m-d H:00:00'));
                $timeInitSeg = $startTimeHour->getTimestamp();
                $finishSup = Supplier::checkingOpenFranja($supplierCart->ID, $zoneInfo->ID, (($supplierCart->TIME + timeRe) * 60));
                $totalFranjas = 0;
                $finishTimeHour = new DateTime($dateNow->format('Y-m-d '.$finishSup["time"]->FINISH_H.':'.$finishSup["time"]->FINISH_M.':00'));
                $textInfoHorario1 = 'Seleccione la franja horaria deseada para recibir su pedido';
                $timeFinishSeg = (($supplierCart->TIME + timeRe) * 60)+ $finishTimeHour->getTimestamp();
                //$timeFinishSeg = $finishTimeHour->getTimestamp();

                $franjaStart = new DateTime();
                $franjaStart->setTimestamp($timeInitSeg);
                $franjaFinish = new DateTime();
                $franjaFinish->setTimestamp($timeFinishSeg);
            
                if($finishSup["status"] == 1){
                    $franjas = array();
                    while(($timeInitSeg - timeFranjas) < $timeFinishSeg) {
                        if($timeInitSeg > $timeStimed) {	
                            $franjaStart = new DateTime();
                            $franjaStart->setTimestamp($timeInitSeg - timeFranjas);
                            $franjaFinish = new DateTime();
                            $franjaFinish->setTimestamp($timeInitSeg);
                            $activeFranja = false;
                            if(Order::chekingOrderFranja($franjaStart,$franjaFinish,$maxOrderZone,$zoneInfo->ID)) {
                                $activeFranja = true;
                                $totalFranjas++;
                                $franjas[] = (object)array("value" => $franjaStart->format("Y-m-d H:i")."#-#".$franjaFinish->format("Y-m-d H:i"), "label" => "De ".$franjaStart->format("H:i")." a ".$franjaFinish->format("H:i"));
                            }
                            
                        }
                        $timeInitSeg = $timeInitSeg + timeFranjas;
                    }
                }
                
            }else if($zoneInfo->TYPE == 'pedania'){
                
                $finishSup = Supplier::checkingOpenFranjaPedania($supplierCart->ID, $zoneInfo->ID, (($supplierCart->TIME + timeRe + timeRePedanias) * 60));
                $startTime->setTimestamp($timeStimed);
                $startTimeHour = new DateTime($dateNow->format('Y-m-d '.$finishSup['time']->START_H.':'.$finishSup['time']->START_M.':00'));
                $finishTimeHour = new DateTime($dateNow->format('Y-m-d '.$finishSup["time"]->FINISH_H.':'.$finishSup["time"]->FINISH_M.':00'));
                
                $timeInitSeg = $finishTimeHour->getTimestamp()+(timeRePedanias * 60);
                $totalFranjas = 0;
                $timeFinishSeg = (($supplierCart->TIME + timeRe + timeRePedanias) * 60)+ $finishTimeHour->getTimestamp();
                $textInfoHorario1 = 'Horarios disponibles de entrega.';
                $totalFranjas = 0;
            
                if($finishSup["status"] == 1){
                    $franjas = array();
                    $franjaStart = new DateTime();
                    $franjaStart->setTimestamp($timeInitSeg - timeFranjas);
                    $franjaFinish = new DateTime();
                    $franjaFinish->setTimestamp($timeInitSeg);
                    $activeFranja = false;
                    
                    $franjas[] = (object)array("value" => $franjaStart->format("Y-m-d H:i")."#-#".$franjaFinish->format("Y-m-d H:i"), "label" => "De ".$franjaStart->format("H:i")." a ".$franjaFinish->format("H:i"));
                    $timeInitSeg = $timeInitSeg + timeFranjas;
                }
            }
            

            $res->SALDO = $saldoNew;
            $res->SUPPLIER = $supplierCart;
            $res->SUPPLIER->ADDRESS = $addressSup;
            $res->ZONE = $zoneInfo;
            $res->MAXORDERZONE = $maxOrderZone;
            $res->ADDRESS = $address;
            $res->POINTS = $points;
            $res->METHODS = $methodPay;
            $res->FRANJAS = $franjas;

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
        $supplierCart = Supplier::infoSupplierById($postdata->id);
        $supplierUserID = Supplier::infoSupplierUser($postdata->id, "proveedor"); 
        $supplierUser = UserWeb::infoUserWebById($supplierUserID[0]); //para obtener el token del movil
        $addressSup = Supplier::supplierAddress($supplierCart->ID); 
        $timeSup = Supplier::checkingOpen($supplierCart->ID);
        if(isset($postdata->zone))
            $zoneInfo = Zone::infoZone($postdata->zone);
        else
            $zoneInfo = Zone::infoZone($addressSup->idZone);
        $usersRep = Supplier::infoSupplierUserZone($postdata->id, 'repartidor',$addressSup->idZone);
        //$maxOrderZone = $zoneInfo->ORDER_LIMIT * $zoneInfo->REP_LIMIT;
        ////$maxOrderZone = $zoneInfo->ORDER_LIMIT * count($usersRep);
        $maxOrderZone = ($zoneInfo->ORDER_LIMIT * count($usersRep)) + $supplierCart->EXTRA_ORDER;
					
        $user = UserWeb::infoUserWebByLogin($token->data->email);
		$usersSupplierID = Supplier::infoSupplierUser($postdata->id, "proveedor");
		$methodpay = intval($postdata->methodpay);
		$comment = trim($postdata->comment);
		$idAddress = intval($postdata->address);
		$franjaAux = explode("#-#",trim($postdata->franja));
		$franjaStart = new DateTime($franjaAux[0]);
		$franjaFinish = new DateTime($franjaAux[1]);
        
        $orderObj = new Order();
        if($orderObj->chekingOrderFranja($franjaStart,$franjaFinish,$maxOrderZone,$zoneInfo->ID)) {
            $checkDiscount = 0;
            if(isset($postdata->useDiscount) && $postdata->useDiscount){
                $checkDiscount = 1;
            }
            
            if($methodpay > 0 && $idAddress > 0) {
                
                $timeSup = Supplier::checkingOpen($supplierCart->ID); 
                if($timeSup["status"] == 1) {
                    $classTimeSup = "green";
                    $iconTimeSup = "unlock";
                    $textTime = "Abierto hasta las " . $timeSup["time"]->FINISH_H .":";
                    if(strlen($timeSup["time"]->FINISH_M) == 1) {
                        $textTime .= "0";
                    }
                    $textTime .= $timeSup["time"]->FINISH_M;
                } else {
                    if($timeSup["time"] == null) {
                        $textTime = "Cerrado";
                        $classTimeSup = "danger";
                        $iconTimeSup = "lock";
                    }else {
                        $classTimeSup = "orange";
                        $iconTimeSup = "clock-o";
                        $textTime = "Abierto a partir de " . $timeSup["time"]->START_H .":";
                        if(strlen($timeSup["time"]->START_M) == 1) {
                            $textTime .= "0";
                        }
                        $textTime .= $timeSup["time"]->START_M;
                    }
                }
                
                $ref = $orderObj->newRef();
                $orderObj->ref = $ref;
                $orderObj->idsupplier = $postdata->id;
                $orderObj->iduser = $user->ID;
                $orderObj->idrepartidor = 0; //se determina cuando lo acepte
                $orderObj->idaddress = $idAddress;
                $orderObj->idmethodpay = $methodpay;
                
                $method = $orderObj->orderMethodPay($methodpay);
                
                
                $now = new DateTime();
                $orderObj->date_create = $now->format("Y-m-d H:i:s");
                $orderObj->date_start = "0000-00-00 00:00:00";
                $orderObj->comment = $comment;
                $orderObj->timesupplier = $supplierCart->TIME;
                $orderObj->timerepartidor = timeRe;
                
                $orderObj->send_start = $franjaStart->format("Y-m-d H:i:s");
                $orderObj->send_finish = $franjaFinish->format("Y-m-d H:i:s");
                
                $subTotalOrder = 0;
                foreach($postdata->dataCart as $item) {
                    $subTotalOrder = $subTotalOrder + $item->cost;
                }
                $orderObj->subtotal = $subTotalOrder;
                $orderObj->shipping = $supplierCart->COST;
                
                $orderObj->cost = $subTotalOrder + $supplierCart->COST;
                
                $orderObj->discount = 0;
                if($checkDiscount == 1) {
                    $saldo = UserWeb::checkingSaldo($user->ID);
                    if($saldo >= $orderObj->cost) {
                        $saldo = $saldo - $orderObj->cost;
                        $orderObj->discount = $orderObj->cost;
                        $orderObj->cost = 0;
                    }else if($saldo < $orderObj->cost) {
                        $orderObj->cost = $orderObj->cost - $saldo;
                        $orderObj->discount = $saldo;
                        $saldo = 0;
                    }
                    UserWeb::updateSaldo($user->ID, $saldo);
                }
                if($orderObj->cost == 0 && ($method->ID == 2 || $method->ID == 3)) {//tpv virtual si el descuento es mayor o igual que el coste lo pasamos a pagado
                    $orderObj->status = 2;
                }else{
                    $orderObj->status = $method->STATUS1;
                }	
                
                $idOrder = $orderObj->add();
                $typeProduct = "user";
                foreach($postdata->dataCart as $item) {
                    $comString = "";
                    for($i=0;$i<count($item->compsArray);$i++) {
                        $comString .= $item->compsArray[$i];
                        if($i < count($item->compsArray)-1) {
                            $comString .= "#-#";
                        }
                    }
                    $orderObj->addProduct($item->id, $item->ud, $item->cost, $comString, $typeProduct);
                }
                //sendGCM("test", "test cart pedido", "eXi-EOlaRCGbrau77aiUW5:APA91bGq_n03L--AkpxClhB_4-giTUAFtBnxFN29_EeX03SoKrDA5cZGLa9sls466Jv-3HL0UUQsMRw-TY9V0QQpSIeFA3GeSPdAaZL1T3GZh78XjyUsGd6ntg4qWangdOVS_qNXieYO");
                if($method->ID == 1) {
                    sendGCM("Tiene un nuevo pedido", "Ha recibido un nuevo pedido en RepartEat REF: " . $ref, $supplierUser->LAST_TOKEN);
                }
                ////Hay que eliminar la variable del carrito en el front

                if($method->ID == 1) {//si es contrareembolso mandamos las alertas
                    //Enviar alertas
                    $botObj = new TelegramBot(TELEGRAMTOKEN, $supplierCart->IDTELEGRAM);
                    if($botObj->chatid !== false){
                        $text ="Ha recibido un nuevo pedido en RepartEat. REF: " . $orderObj->ref;
                        $urlBoton = DOMAIN.'perfil/?view=order&mod=order&ref='.$orderObj->ref;
                        $boton = '[{"text":"Ver pedido","url":"'.urlencode($urlBoton).'"}]';
                        
                        $botObj->sendMessage($text,$boton);
                        //$content = "\n\n" . $now->format("d-m-Y H:i:s") . "\n" .$ref . "\n" .$text . "\n\n";
                        //file_put_contents("webhook_ordersupplier.log", $content, FILE_APPEND);
                    }else {
                        $content = "\n\nERROR:" . $now->format("d-m-Y H:i:s") . "\n" .$ref . "\n" .$text . "\n\n";
                        file_put_contents("webhook_ordersupplier.log", $content, FILE_APPEND);
                    }
                    
                    
                    $template = "start-order.html";
                    $dir = "../perfil/";
                    $textButton = "IR AL PEDIDO";
                    
                    $cont = 0;
                    for($i=0;$i<count($usersSupplierID);$i++) {
                        $u = UserWeb::infoUserWebById($usersSupplierID[$i]);
                        $userSend[$cont]["name"] = $u->NAME . " " . $u->SURNAME;
                        $userSend[$cont]["mail"] = $u->LOGIN;
                        $cont++;
                    }
                    
                    $subject = "Nuevo pedido Reparteat";
                    $textMail = "";
                    $textMail = "<strong>Tiene un nuevo pedido.</strong>";
                    $link = DOMAINZP."?view=order&mod=order&ref=".$orderObj->ref;
                    
                    $textMail .= "<br/>".$text;
                    $cont = 0;
                    if(count($userSend)>0) {
                        $msg .= "<h4 class='arial green'>Pedido realizado correctamente</h4><br/><h5>Espere confirmación en su correo electrónico.</h5><br/><h5>El tiempo de entrega de su pedido es aproximado y dependerá de cocinas.</h5><br/><h5>Ante cualquier incidencia en su pedido contacte con el establecimiento <b>".$supplierCart->TITLE."</b> a través del teléfono <a href='tel:".$supplierCart->PHONE."'>".$supplierCart->PHONE."</a>.</h5>";
                    //	No lo metemos en el mensage
                        sendMailAlert($userSend, $subject, $textMail, $link, $textButton, $template, $dir);
                    } else {
                        $msg .= NOUSERMAIL;
                    }
                }
                $msg="Pedido ".$ref." creado.";
                $res->ref = $ref;
                //modifico el peiddo marcándolo como pedido desde app
                $q = "UPDATE `".preBD."order` SET FROM_APP=1, IDZONE=".$zoneInfo->ID." wHERE ref='".$ref."'";
                checkingQuery($connectBD, $q);
                
            }else{	
                $result = 1;
                if($methodpay == 0){
                    $msg = "No ha seleccionado ningún método de pago, por favor vuelva a intentarlo.";
                }else if($idAddress > 0) {
                    $msg = "No ha seleccionado ninguna dirección de envio, por favor vuelva a intentarlo.";
                }
            }
        }else{
            $result = 2;
            $msg = date("d/m/Y H:i:s")." La franja horaria seleccionada se ha completado mientras se tramitaba su pedido, por favor seleccione otra franja horario y vuelva a intentarlo.";
        }
        $res->msg = $msg;

        http_response_code(200);
        echo json_encode($res);
    } else {
        api::sendError("Sin acceso");
    }
}

function sendGCM($title, $message, $token) {


    $url = 'https://fcm.googleapis.com/fcm/send';

    $fields = '{
        "to": "'.$token.'",
        "notification": {
            "sound": "default",
            "body": "'.$message.'",
            "content_available": true,
            "title": "'.$title.'" ,
            "imageUrl": "https://reparteat.com/template/images/logo_green.png"
            }
    }';

    $headers = array (
            'Authorization: key=' . "AAAAvCDXxG4:APA91bERaoHDyaqfE83CVLshesHp5ZiDMNpZ5EX0_1QhNlEb_Rso-1YgoKVI--QPNTwEskGoURrAYJIfzP0fVjhGNtmIbhs0LTvwOiaPxD217L2sebZlPzBgrV-dvFRe40v84cVdGzw7",
            'Content-Type: application/json'
    );

    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_POST, true );
    curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );

    $result = curl_exec ( $ch );
    //echo $result;
    curl_close ( $ch );
}

?>