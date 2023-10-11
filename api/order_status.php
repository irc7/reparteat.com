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
require_once ("../perfil/includes/functions.php");
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

/*echo "Sending test";
sendGCM("TEST", "order_status", "eFUqaj1SS1qvRx1LI1jHSe:APA91bHYuHpPwuphz6wzl952E4pU8yg5T88f7wt6S9Wg6dvDZKqfhv1cOrXm_y9vPG-Nztvw4P5tYLO_KPMnh2VGAc4F8Q9NSf2wRi-0ogSTUO1_Mo0QPrg3k1MgcNSxjXhgMaI9P1SK");
exit();*/

//GET METHOD: Zones
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $token = api::checkToken();
    if($token) {
        $user = UserWeb::infoUserWebByLogin($token->data->email);
        $zoneObj = new Zone();
        $zonesUser = $zoneObj->zonesByUser($user->ID);
        http_response_code(200);
        echo json_encode($zonesUser);
    } else {
        //api::sendError("Sin acceso");
    }
}

//POST METHOD: order modificar pedido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postdata = json_decode(file_get_contents("php://input"));
    $token = api::checkToken();
    if($token) {
        $user = UserWeb::infoUserWebByLogin($token->data->email);
        $result = 0;
        $msg = "";
        if($postdata) {
            $next = intval($postdata->next);
            $ref = intval($postdata->ref);
            $ordObj = new Order();
            $order = $ordObj->infoOrderByRef($ref);
            if($order) {
                $address = $ordObj->orderAddress($order->IDADDRESS);
                $methodPay = $ordObj->orderMethodPay($order->IDMETHODPAY);
                $supObj = new Supplier();
                $proObj = new Product();
                $userObj = new UserWeb();
                $supplierCart = $supObj->infoSupplierById($order->IDSUPPLIER);
                $supplierUserID = $supObj->infoSupplierUser($order->IDSUPPLIER, "proveedor"); 
                $supplierUser = $userObj->infoUserWebById($supplierUserID[0]); //para obtener el token del movil
                $userOrder = $userObj->infoUserWebById($order->IDUSER);
                $statusOld = $order->STATUS;
                if($next != $statusOld) {
                    if($user->IDTYPE == 5 && $next > 0 && $next != $statusOld) {
                        if($ordObj->checkViewOrderZone($user, $order)) {
                            $order->STATUS = $ordObj->updateStatus($order->ID, $next, $statusOld);
                            $msg .= "Cambio de estado del pedido modificado correctamente";
                        }else {
                            $result = 1;
                            $msg .= "No tiene permisos para cambiar el estado de este pedido";
                        }
                    }else {
                        if($order->STATUS != 12 || ($order->STATUS == 12 && ($order->IDMETHODPAY == 2 || $order->IDMETHODPAY == 3))) {
                            //actualiza el estado del pedido e inserto registro de cambio de estado
                            if($next == 4 && $user->IDTYPE == 3 && $order->IDREPARTIDOR == 0) {
                                //actualizo tiempo de repartido y lo asocio al pedido
                                $time = intval($postdata->aux);
                                $ordObj->updateTimeRep($order->ID, $user->ID, $time);
                            }else {
                                $order->STATUS = $ordObj->updateStatus($order->ID, $next, $statusOld);
                            }
                            $now = new DateTime();
                            switch($next) {
                                case '3':
                                    if($ordObj->checkViewOrder($user->ID, $order->ID)) {
                                        //Pedido aceptado por el restaurante
                                        //mando mail a usuario pedido aceptado y repartidor
                                        
                                        //$time = intval($postdata->aux);Anulamos el valor del post y ponemos el de la base de datos
                                        $time = $supplierCart->TIME;
                                        $ordObj->updateTimeSup($order->ID, $time);//Actualiza el tiempo de cocina y da valor a DATE_START
                                        $order->TIMESUPPLIER = $time;
                                        
                                        //Enviar alertas
                                        //repartidores
                                        $usersSendBD = $supObj->assignSupplierRepartidor($order);
                                        //Enviar alertas
                                        $template = "accept-order.html";
                                        //Correo 
                                        $dir = "../perfil/";
                                        $textButton = "IR A PEDIDOS PENDIENTES";
                                        
                                        $cont = 0;
                                        $userSend = array();
                                        for($i=0;$i<count($usersSendBD);$i++) {
                                            $botObj = new TelegramBot(TELEGRAMTOKEN, $usersSendBD[$i]->IDTELEGRAM);
                                            if($botObj->chatid !== false){
                                                $text ="Nuevo pedido en RepartEat. Restaurante: ".$supplierCart->TITLE.".";
                                                $text .= "\nREF: " . $order->REF.".";
                                                $urlBoton = DOMAINZP.'?view=order&mod=order&tpl=delivery&rep='.$usersSendBD[$i]->ID.'&filter=to-deliver';
                                                $boton = '[{"text":"Ir a pedidos pendientes","url":"'.urlencode($urlBoton).'"}]';
                                                
                                                $botObj->sendMessage($text,$boton);
                                                //$content = "\n\n" . $now->format("d-m-Y H:i:s") . "\n" .$ref . "\n" .$text . "\n\n";
                                                //file_put_contents("webhook_ordersupplier.log", $content, FILE_APPEND);
                                            }else {
                                                $content = "\n\nERROR (idChat Repartidor incorrecta o nula):" . $now->format("d-m-Y H:i:s") . "\n" .$ref . "\n" .$text . "\n\n";
                                                file_put_contents("webhook_ordersupplier.log", $content, FILE_APPEND);
                                            }
                                            
                                            $userSend[$cont]["name"] = $usersSendBD[$i]->NAME . " " . $usersSendBD[$i]->SURNAME;
                                            $userSend[$cont]["mail"] = $usersSendBD[$i]->LOGIN;
                                            $cont++;
                                            //Notificaciones
                                            sendGCM("Nuevo pedido en RepartEat.", "Nuevo pedido en RepartEat Restaurante: ".$supplierCart->TITLE." REF ".$order->REF, $usersSendBD[$i]->LAST_TOKEN);
                                        }
                                        
                                        $subject = "Nuevo pedido RepartEat";
                                        $textMail = "<strong>Tiene un nuevo pedido para repartir.</strong>";
                                        $textMail .= "<br/>Restaurante: " . $supplierCart->TITLE;
                                        
                                        
                                        $link = DOMAINZP."?view=order&mod=order&tpl=delivery&filter=no-shipping";
                                        
                                        $cont = 0;
                                        if(count($userSend)>0) {
                                            @$msg .= sendMailAlert($userSend, $subject, $textMail, $link, $textButton, $template, $dir);
                                        } else {
                                            $msg .= NOUSERMAIL;
                                        }
                                        
                                        //Correo usuario
                                        
                                        $template = "user-order.html";
                                        $dir = "../perfil/";
                                        $textButton = "IR AL PEDIDO";
                                        
                                        $userSend = array();
                                        
                                        $userSend[0]["name"] = $userOrder->NAME . " " . $userOrder->SURNAME;
                                        $userSend[0]["mail"] = $userOrder->LOGIN;
                                        
                                        
                                        $subject = "Pedido confirmado RepartEat";
                                        $textMail = "<strong>El resturante <em>".$supplierCart->TITLE."</em> ha aceptado su pedido.</strong>";
                                        $textMail .= "<br/>";
                                        $textMail .= "<h4 style='color:#009975;font-size:15px;text-align:left;'>Dirección de entrega:";
                                        $textMail .= $address->STREET . " - " . $address->CITY . " - " .$address->CP." - ".$address->PROVINCE;
                                        $textMail .= "</h4><br/>";
                                        $textMail .= sumaryOrderMail($order);
                                        $textMail .= "<br/>";
                                        $textMail .= "<h4 style='color:#009975;font-size:15px;text-align:left;'>Recibirá su pedido ";
                                        $textMail .= $ordObj->franjaInfo($order) . "</h4><br/>";
                                        
                                        $link = DOMAINZP."?view=order&mod=order&ref=".$order->REF;
                                        
                                        $cont = 0;
                                        if(count($userSend)>0) {
                                            @$msg .= sendMailAlert($userSend, $subject, $textMail, $link, $textButton, $template, $dir);
                                        } else {
                                            $msg .= NOUSERMAIL;
                                        }	
                                        //Notificaciones
                                        sendGCM("Pedido confirmado RepartEat", "El resturante ".$supplierCart->TITLE." ha aceptado su pedido ".$order->REF, $userOrder->LAST_TOKEN);

                                        $msg .= "Pedido aceptado";
                                    }else {
                                        $result = 1;
                                        $msg .= "No tiene permisos para cambiar el estado de este pedido";
                                    }
                                break;
                                case '4':
                                    //Pedido aceptado por el repartidor
                                    if($order->STATUS == 4 && $user->IDTYPE == 2) {
                                        $statusBD = $ordObj->infoStatusOrder($order->STATUS);
                                        
                                        //Correo repartidores
                                        
                                        $template = "accept-order.html";
                                        $dir = "../perfil/";
                                        $textButton = "IR AL PEDIDO";
                                        
                                        $cont = 0;
                                        $userSend = array();
                                        if($order->IDREPARTIDOR > 0) { 
                                            $usersSendBD = $userObj->infoUserWebById($order->IDREPARTIDOR);
                                            
                                            $botObj = new TelegramBot(TELEGRAMTOKEN, $usersSendBD->IDTELEGRAM);
                                            if($botObj->chatid !== false){
                                                $text ="Pedido listo para recoger.\nRestaurante: ".$supplierCart->TITLE.".";
                                                $text .= "\nREF: " . $order->REF;
                                                $text .= "\nDirección de entrega:\n" . $address->STREET . " - " . $address->CITY . " - " .$address->CP." - ".$address->PROVINCE;
                                                $text .= "\nPRECIO: " . $order->COST;
                                                $urlBoton = DOMAINZP."?view=order&mod=order&ref=".$order->REF;
                                                $boton = '[{"text":"Ver pedido","url":"'.urlencode($urlBoton).'"}]';
                                                
                                                $botObj->sendMessage($text,$boton);
                                                //$content = "\n\n" . $now->format("d-m-Y H:i:s") . "\n" .$ref . "\n" .$text . "\n\n";
                                                //file_put_contents("webhook_ordersupplier.log", $content, FILE_APPEND);
                                            }else {
                                                $content = "\n\nERROR (idChat Repartidor incorrecta o nula):" . $now->format("d-m-Y H:i:s") . "\n" .$ref . "\n" .$text . "\n\n";
                                                file_put_contents("webhook_ordersupplier.log", $content, FILE_APPEND);
                                            }
                                            
                                            $userSend[$cont]["name"] = $usersSendBD->NAME . " " . $usersSendBD->SURNAME;
                                            $userSend[$cont]["mail"] = $usersSendBD->LOGIN;
                                            $subject = "Pedido listo para recoger";
                                            $textMail = "<strong>Restaurante:</strong> " . $supplierCart->TITLE;
                                            $textMail .= "<br/><strong>El pedido <em>".$order->REF."</em> esta listo para recoger.</strong>";
                                            //Notificaciones
                                            sendGCM("Pedido listo para recoger", "Resturante ".$supplierCart->TITLE." Pedido ".$order->REF."", $usersSendBD->LAST_TOKEN);
                                        }else {
                                            $usersSendBD = $userObj->infoUserWebSuperadmin();
                                            for($i=0;$i<count($usersSendBD);$i++) {
                                                $botObj = new TelegramBot(TELEGRAMTOKEN, $usersSendBD[$i]->IDTELEGRAM);
                                                if($botObj->chatid !== false){
                                                    $text ="Pedido listo para recoger. Restaurante: ".$supplierCart->TITLE.". REF: " . $order->REF;
                                                    $urlBoton = DOMAIN."pdc-reparteat";
                                                    $boton = '[{"text":"Ver pedido","url":"'.urlencode($urlBoton).'"}]';
                                                    
                                                    $botObj->sendMessage($text,$boton);
                                                    //$content = "\n\n" . $now->format("d-m-Y H:i:s") . "\n" .$ref . "\n" .$text . "\n\n";
                                                    //file_put_contents("webhook_ordersupplier.log", $content, FILE_APPEND);
                                                }else {
                                                    $content = "\n\nERROR (idChat Repartidor incorrecta o nula):" . $now->format("d-m-Y H:i:s") . "\n" .$ref . "\n" .$text . "\n\n";
                                                    file_put_contents("webhook_ordersupplier.log", $content, FILE_APPEND);
                                                }
                                                $userSend[$cont]["name"] = $usersSendBD[$i]->NAME . " " . $usersSendBD[$i]->SURNAME;
                                                $userSend[$cont]["mail"] = $usersSendBD[$i]->LOGIN;

                                                //Notificaciones
                                                sendGCM("Pedido listo sin repartidor asignado", "Resturante ".$supplierCart->TITLE." Pedido ".$ref."", $usersSendBD[$i]->LAST_TOKEN);

                                                $cont++;
                                            }
                                            $subject = "Pedido listo sin repartidor asignado";
                                            $textMail = "<strong>Restaurante:</strong> " . $supplierCart->TITLE;
                                            $textMail .= "<br/><strong>El pedido <em>".$order->REF."</em> y no tiene asignado ningún repartidor.</strong>";
                                        }
                                        
                                        $link = DOMAINZP."?view=order&mod=order&ref=".$order->REF;
                                        
                                        $cont = 0;
                                        if(count($userSend)>0) {
                                            $msg .= @sendMailAlert($userSend, $subject, $textMail, $link, $textButton, $template, $dir);
                                        } else {
                                            $msg .= NOUSERMAIL;
                                        }
                                        
                                        $msg .= "Pasado a " . $statusBD->TITLE;
                                        
                                    } else if(($order->STATUS == 3 || $order->STATUS == 4) && $user->IDTYPE == 3 && $order->IDREPARTIDOR == 0) {
                                        $msg .= "El pedido le ha sido asignado para el reparto";
                                    } else if($order->STATUS == 3 && $user->IDTYPE == 3 && $order->IDREPARTIDOR > 0) {
                                        $error = 1;
                                        $msg .= "El pedido ya ha sido asignado a otro repartidor";
                                    }else{
                                        $error = 1;
                                        $msg .= "No tiene permisos para cambiar el estado de este pedido";
                                    }
                                
                                break;
                                case '5':
                                        //Pedido enviado
                                        if($ordObj->checkingRepartidorOrder($user->ID, $supplierCart->ID)) {
                                            if($order->IDREPARTIDOR == $user->ID) {
                                                $statusBD = $ordObj->infoStatusOrder($order->STATUS);
                                                $msg .= "Pasado a " . $statusBD->TITLE;
                                                $msg .= "<br/><br/>Observaciones del pedido:<br/><em>" .$order->COMMENT."</em>";
                                                //Notificaciones
                                                sendGCM("Pedido en camino", "Tu pedido ".$ref." está en camino", $userOrder->LAST_TOKEN);
                                            } else{
                                                $error = 1;
                                                $msg .= "No es el repartidor asignado a este pedido";
                                            } 
                                        }else{
                                            $error = 1;
                                            $msg .= "No tiene permisos para cambiar el estado de este pedido";
                                        }
                                break;
                                case '6':
                                    //Pedido entregado
                                    if($ordObj->checkingRepartidorOrder($user->ID, $supplierCart->ID)) {
                                        if($order->IDREPARTIDOR == $user->ID) {
                                            $statusBD = $ordObj->infoStatusOrder($order->STATUS);
                                            $msg .= "Pasado a " . $statusBD->TITLE;
                                        } else{
                                            $error = 1;
                                            $msg .= "No es el repartidor asignado a este pedido";
                                        } 
                                    }else{
                                        $error = 1;
                                        $msg .= "No tiene permisos para cambiar el estado de este pedido";
                                    }
                                    
                                break;
                                case '7':
                                    //Error en el pago, se encarga el tpv
                                break;
                                case '8':
                                    //Pedido cancelado por el restaurante
                                    if($ordObj->checkViewOrder($user->ID, $order->ID)) {
                                        //1.actualizamos el saldo
                                        $saldo = $userObj->checkingSaldo($order->IDUSER);
                                        $saldo = $saldo - $order->COST;
                                        $userObj->updateSaldo($order->IDUSER, $saldo);
                                        //actualizo a 8 y mando mail a usuario con pedido cancelado 
                                        $text = trim($postdata->aux);
                                        //actualizo el motivo de cancelación
                                        $q = "UPDATE `".preBD."order_staus_time` SET `TEXT`='".$text."' 
                                                    WHERE IDORDER = " . $order->ID . " 
                                                    and IDSTATUS = 8";
                                                    checkingQuery($connectBD, $q);
                                                    
                                                    
                                                    //Correo usuario
                                            $template = "user-order.html";
                                            $dir = "../perfil/";
                                            $textButton = "IR AL PEDIDO";
                                            
                                            $userSend = array();
                                            
                                            $userSend[0]["name"] = $userOrder->NAME . " " . $userOrder->SURNAME;
                                            $userSend[0]["mail"] = $userOrder->LOGIN;
                                            
                                            
                                            $subject = "Pedido cancelado por el restaurante";
                                            $textMail = "<strong>El resturante <em>".$supplierCart->TITLE."</em> ha cancelado su pedido con Ref.:".$order->REF.".</strong>";
                                            $textMail .= "<br/>";
                                            $textMail .= "<h4 style='color:#333333;font-size:15px;text-align:left;'>Motivo de la cancelación:</h4><br/>";
                                            $textMail .= "<h5 style='color:#333333;font-size:12px;text-align:left;'>";
                                            if($text != "") {
                                                $textMail .= $text;
                                            }else{
                                                $textMail .= "El restaurante no puede atender su pedido.";
                                            }
                                            $textMail .= "</h5><br/>";
                                            $textMail .= "<br/>";
                                            $textMail .= sumaryOrderMail($order);

                                            if($order->IDMETHODPAY == 2 || $order->IDMETHODPAY == 3) {
                                                //Insertar coste en el saldo del usuario e informar en el correo
                                                $saldo = $userObj->checkingSaldo($order->IDUSER);
                                                $saldo = $saldo + $order->COST;
                                                $userObj->updateSaldo($order->IDUSER, $saldo);
                            
                                                $textMail .= "<p style='color:#333333;font-size:16px;text-align:center;font-weight:bold;'>";
                                                $textMail .= "Se ha generado un saldo a su favor de ".$order->COST." para usar en sus próximos pedidos.";
                                                $textMail .= "</p>";
                                                $textMail .= "<p style='color:#333333;font-size:16px;text-align:center;font-weight:bold;'>";
                                                $textMail .= "Si quiere solicitar la devolución del importe, dirijase a su perfil de usuario y en los detalles del pedido, 
                                                en el apartado método de pago seleccionado, encontrará un botón para la solicitud de devolución.";
                                                $textMail .= "</p>";
                                            }
                                            
                                            $link = DOMAINZP."?view=order&mod=order&ref=".$order->REF;
                                            $cont = 0;
                                            //Notificaciones
                                            //TODO: si está pagado por tarjeta o bizum que diga que el saldo lo tiene en su cuenta Reparteat
                                            sendGCM("Pedido cancelado", "El restaurante ha cancelado tu pedido ".$ref." ".$text." Revísalo en Mi Perfil", $userOrder->LAST_TOKEN);
                                            if(count($userSend)>0) {
                                                @$msg .= sendMailAlert($userSend, $subject, $textMail, $link, $textButton, $template, $dir);
                                            } else {
                                                $msg .= NOUSERMAIL;
                                            }
                                            $statusBD = $ordObj->infoStatusOrder($order->STATUS);
                                            $msg .= "Pasado a " . $statusBD->TITLE;
                                        }else {
                                            $result = 1;
                                            $msg .= "No tiene permisos para cambiar el estado de este pedido";
                                    }
                                break;
                                case '9':
                                    //Cancelado por el usuario
                                    //Se le envia mail al restaurante
                                    //Notificaciones
                                    sendGCM("Pedido cancelado", "El cliente ha cancelado el pedido ".$ref." Revísalo en Mi Perfil", $supplierUser->LAST_TOKEN);
                                break;
                                case '10':
                                    //Pedido cancelado por el repartidor
                                    if($ordObj->checkViewOrder($user->ID, $order->ID)) {
                                        //actualizo a 10 y mando mail a usuario con pedido cancelado 
                                        $text = trim($postdata->aux);
                                        $q = "UPDATE `".preBD."order_staus_time` SET `TEXT`='".$text."' 
                                                    WHERE IDORDER = " . $order->ID . " 
                                                    and IDSTATUS = 10";
                                                    checkingQuery($connectBD, $q);
                                                    
                                                    
                                        //Correo usuario
                                        $template = "user-order.html";
                                        $dir = "../perfil/";
                                        $textButton = "IR AL PEDIDO";
                                        
                                        $userSend = array();
                                        
                                        $userSend[0]["name"] = $userOrder->NAME . " " . $userOrder->SURNAME;
                                        $userSend[0]["mail"] = $userOrder->LOGIN;
                                        
                                        
                                        $subject = "Pedido cancelado por el repartidor";
                                        $textMail = "<strong>El repartidor ha cancelado su pedido con Ref.:".$order->REF.".</strong>";
                                        $textMail .= "<br/>";
                                        $textMail .= "<h4 style='color:#333333;font-size:15px;text-align:left;'>Motivo:</h4><br/>";
                                        $textMail .= "<h5 style='color:#333333;font-size:12px;text-align:left;'>";
                                        if($text != "") {
                                            $textMail .= $text;
                                        }else{
                                            $textMail .= "No ha sido posible la entrega de su pedido";
                                        }
                                        $textMail .= "</h5><br/>";
                                        $textMail .= "<br/>";
                                        $textMail .= sumaryOrderMail($order);
                                        
                                        $link = DOMAINZP."?view=order&mod=order&ref=".$order->REF;
                                        $cont = 0;
                                        //Notificaciones
                                        sendGCM("Pedido cancelado", "El repartidor ha cancelado tu pedido ".$ref." ".$text." Revísalo en Mi Perfil", $userOrder->LAST_TOKEN);
                                        if(count($userSend)>0) {
                                            @$msg .= sendMailAlert($userSend, $subject, $textMail, $link, $textButton, $template, $dir);
                                        } else {
                                            $msg .= NOUSERMAIL;
                                        }
                                        $statusBD = $ordObj->infoStatusOrder($order->STATUS);
                                        $msg .= "Pasado a " . $statusBD->TITLE;
                                    }else {
                                        $result = 1;
                                        $msg .= "No tiene permisos para cambiar el estado de este pedido";
                                    }
                                break;
                                case '11':
                                    //Cancelado
                                    //Se le envia mail al restaurante y al usuario
                                    //Notificaciones
                                    sendGCM("Pedido cancelado", "Se ha cancelado tu pedido ".$ref." Revísalo en Mi Perfil", $userOrder->LAST_TOKEN);
                                    sendGCM("Pedido cancelado", "Se ha cancelado el pedido ".$ref." Revísalo en Mi Perfil", $supplierUser->LAST_TOKEN);
                                break;
                                case '13':
                                    if($statusOld >= 8 && $statusOld <= 12 && $user->ID == $order->IDUSER && $userOrder->SALDO >= $order->COST) {
                                        //Solicitud de devolucion del importe
                                        //1.actualizamos el saldo
                                        $saldo = $userObj->checkingSaldo($order->IDUSER);
                                        $saldo = $saldo - $order->COST;
                                        $userObj->updateSaldo($order->IDUSER, $saldo);
                                        //2. insertamos registro de devolucion
                                        $q = "INSERT INTO `".preBD."order_return_tpv`(`IDORDER`, `IDUSER`, `STATUS`, `DATERETURN`) 
                                                VALUES ('".$order->ID."','".$order->IDUSER."',0,NOW())";
                                        checkingQuery($connectBD, $q);

                                        //3. Generamos las alertas
                                        //Correo usuario
                                        $template = "user-order.html";
                                        $dir = "../perfil/";
                                        $textButton = "IR AL PEDIDO";
                                        
                                        $userSend = array();
                                        
                                        $userSend[0]["name"] = $userOrder->NAME . " " . $userOrder->SURNAME;
                                        $userSend[0]["mail"] = $userOrder->LOGIN;
                                        
                                        $userSend[1]["name"] = "Enrique Carpintero";
                                        $userSend[1]["mail"] = 'ecarpintero@reparteat.com';
                                        
                                        $userSend[2]["name"] = "Francisco José Venegas";
                                        $userSend[2]["mail"] = 'fjvenegas@reparteat.com';
                                        
                                        $userSend[3]["name"] = "Gestión de devoluciones";
                                        $userSend[3]["mail"] = 'devoluciones@reparteat.com';
                                                                    
                                        
                                        $subject = "Solicitud de devolución - ".$order->REF;
                                        $textMail = "Su solicitud de devolución ha sido enviada correctamente.";
                                        $textMail .= "<br/>";
                                        $textMail = "Su devolución de realizará en un plazo máximo de 72h, si en ese plazo no le ha sido devuelto el coste del pedido por favor póngase en contacto con nosotros y se la tramitaremos de manera manual.";
                                        $textMail .= "<br/>";
                                        $textMail .= "<br/>";
                                        $textMail .= sumaryOrderMail($order);
                                        
                                        $link = DOMAINZP."?view=order&mod=order&ref=".$order->REF;
                                        $cont = 0;
                                        //Notificaciones
                                        sendGCM("Solicitud de devolución", "Su solicitud de devolución del pedido ".$ref." ha sido enviada correctamente", $userOrder->LAST_TOKEN);
                                        if(count($userSend)>0) {
                                            @$msg .= sendMailAlert($userSend, $subject, $textMail, $link, $textButton, $template, $dir);
                                        } else {
                                            $msg .= NOUSERMAIL;
                                        }
                                        $msg .= "Devolución del pedido " . $order->REF. " generada correctamente.";
                                    }else {
                                        $result = 1;
                                        $msg .= "No tiene permisos para realizar esta acción o la devolución del pedido ya ha sigo generada";
                                    }
                                break;
                            }
                        } else {
                            $result = 1;
                            $msg = "El pedido ha caducado";	
                        }
                    }		
                }else {
                    $result = 1;
                    $msg = "El pedido ya se encuentra en el estado al que quiere cambiar.";	
                }
            }else{
                $result = 1;
                $msg = "No se encuentra ningún pedido con referencia: ".$ref;
            }
        }else{
            $result = 1;
            $msg = "Ha ocurrido un error inesperado, vuelva a intentarlo, si el error persiste consulte con el administrador.";
        }

        $ar_result["result"] = $result;
        $ar_result["msg"] = $msg;
        http_response_code(200);
        echo json_encode($ar_result);
    } else {
        api::sendError("Sin acceso");
    }
}


?>