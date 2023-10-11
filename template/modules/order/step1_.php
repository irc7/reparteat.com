<?php 
session_start();
$V_PHP = explode(".", phpversion());
if($V_PHP[0]>=5){
	date_default_timezone_set("Europe/Paris");
}
require_once ("../../../pdc-reparteat/includes/database.php");
$connectBD = connectdb();
require_once ("../../../pdc-reparteat/includes/config.inc.php");
require_once ("../../../includes/functions.inc.php");

if(!isset($_SESSION[nameSessionZP]) || intval($_SESSION[nameSessionZP]->ID) <= 0) {
	header("Location: ".DOMAINZP."iniciar-sesion");
}

require_once ("../../../lib/Util/class.Util.php");
require_once ("../../../lib/FileAccess/class.FileAccess.php");
	
require_once("../../../includes/class/class.phpmailer.php");
require_once("../../../includes/class/class.smtp.php");

require_once "../../../includes/class/class.System.php";
require_once("../../../includes/class/Zone/class.Zone.php");
require_once("../../../includes/class/Image/class.Image.php");

require_once("../../../includes/class/UserWeb/class.UserWeb.php");
require_once("../../../includes/class/Supplier/class.CategorySup.php");
require_once("../../../includes/class/Supplier/class.Supplier.php");
require_once("../../../includes/class/Supplier/class.TimeControl.php");
require_once("../../../includes/class/Product/class.CategoryPro.php");
require_once("../../../includes/class/Product/class.Product.php");
require_once("../../../includes/class/Order/class.Order.php");
require_once("../../../includes/class/TelegramBot/class.TelegramBot.php");
require_once("../../../api/helpers.php");

$idCart = intval($_POST["idSupplier"]);
$idZone = intval($_SESSION[sha1("zone")]);
$result = 0;
$msg = "";


if($_POST){
	if(isset($_SESSION[nameCartReparteat][$idCart]) && count($_SESSION[nameCartReparteat][$idCart]["data"]) > 0) {
		$supObj = new Supplier();
		$supplierCart = $supObj->infoSupplierById($idCart);
		$addressSup = $supObj->supplierAddress($supplierCart->ID); 
		$timeSup = $supObj->checkingOpen($supplierCart->ID,$idZone);
		if($_SESSION[nameSessionZP]->IDTYPE == 4 && $supplierCart->STATUS == 1 && $timeSup["status"] == 1) {
			$proObj = new Product();
			$zoneObj = new Zone();
			$zoneInfo = $zoneObj->infoZone($idZone);
			
			$usersRep = $supObj->infoSupplierUserZone($idCart, 'repartidor',$zoneInfo->ID);
			$maxOrderZone = $zoneInfo->ORDER_LIMIT * count($usersRep);
					
			$userObj = new UserWeb();
			$user = $_SESSION[nameSessionZP];
			$usersSupplierID = $supObj->infoSupplierUser($idCart, "proveedor");
			
			
			$methodpay = intval($_POST["methodpay"]);
			$comment = trim($_POST["comment"]);
			$idAddress = intval($_POST["address"]);
			$franjaAux = explode("#-#",trim($_POST["franja"]));
			$franjaStart = new DateTime($franjaAux[0].":00");
			$franjaFinish = new DateTime($franjaAux[1].":00");
			
			$orderObj = new Order();
			if($orderObj->chekingOrderFranja($franjaStart,$franjaFinish,$maxOrderZone,$zoneInfo->ID)) {
				
				$checkDiscount = 0;
				if(isset($_POST["Discount"]) && $_POST["Discount"] == "on"){
					$checkDiscount = 1;
				}
				
				if($methodpay > 0 && $idAddress > 0) {
					
					$timeSup = $supObj->checkingOpen($supplierCart->ID,$idZone); 
					if($timeSup["status"] == 1) {
						$classTimeSup = "green";
						$iconTimeSup = "unlock";
						$textTime = "Disponible hasta las " . $timeSup["time"]->FINISH_H .":";
						if(strlen($timeSup["time"]->FINISH_M) == 1) {
							$textTime .= "0";
						}
						$textTime .= $timeSup["time"]->FINISH_M;
					} else {
						if($timeSup["time"] == null) {
							$textTime = "No disponible";
							$classTimeSup = "danger";
							$iconTimeSup = "lock";
						}else {
							$classTimeSup = "orange";
							$iconTimeSup = "clock-o";
							$textTime = "Disponible a partir de " . $timeSup["time"]->START_H .":";
							if(strlen($timeSup["time"]->START_M) == 1) {
								$textTime .= "0";
							}
							$textTime .= $timeSup["time"]->START_M;
						}
					}
					
					
					$ref = $orderObj->newRef();
					$orderObj->ref = $ref;
					$orderObj->idsupplier = $idCart;
					$orderObj->iduser = $_SESSION[nameSessionZP]->ID;
					$orderObj->idrepartidor = 0; //se determina cuando lo acepte
					$orderObj->idaddress = $idAddress;
					$orderObj->idzone = $idZone;
					$orderObj->idmethodpay = $methodpay;
					
					$method = $orderObj->orderMethodPay($methodpay);
					
					
					$now = new DateTime();
					$orderObj->date_create = $now->format("Y-m-d H:i:s");
					$orderObj->date_start = "0000-00-00 00:00:00";
					$orderObj->comment = $comment;
					$orderObj->timesupplier = $supplierCart->TIME;
					if($zoneInfo->TYPE == "pedania") {
						$orderObj->timerepartidor = timeRe + timeRePedanias;
					}else{
						$orderObj->timerepartidor = timeRe;
					}
					
					$orderObj->send_start = $franjaStart->format("Y-m-d H:i:s");
					$orderObj->send_finish = $franjaFinish->format("Y-m-d H:i:s");
					
					$subTotalOrder = 0;
					foreach($_SESSION[nameCartReparteat][$idCart]["data"] as $item) {
						$subTotalOrder = $subTotalOrder + $item["cost"];
					}
					$orderObj->subtotal = $subTotalOrder;
					$orderObj->shipping = $supplierCart->COST + $zoneInfo->SHIPPING;
					
					$orderObj->cost = $subTotalOrder + $orderObj->shipping;
					
					$orderObj->discount = 0;
					if($checkDiscount == 1) {
						$saldo = $userObj->checkingSaldo($_SESSION[nameSessionZP]->ID);
						if($saldo >= $orderObj->cost) {
							$saldo = $saldo - $orderObj->cost;
							$orderObj->discount = $orderObj->cost;
							$orderObj->cost = 0;
						}else if($saldo < $orderObj->cost) {
							$orderObj->cost = $orderObj->cost - $saldo;
							$orderObj->discount = $saldo;
							$saldo = 0;
						}
						$userObj->updateSaldo($_SESSION[nameSessionZP]->ID, $saldo);
					}
					if($orderObj->cost == 0 && ($method->ID == 2 || $method->ID == 3)) {//tpv virtual si el descuento es mayor o igual que el coste lo pasamos a pagado
						$orderObj->status = 2;
					}else{
						$orderObj->status = $method->STATUS1;
					}	
					
					$idOrder = $orderObj->add();
					$typeProduct = "user";
					foreach($_SESSION[nameCartReparteat][$idCart]["data"] as $item) {
						$comString = "";
						for($i=0;$i<count($item["compsArray"]);$i++) {
							$comString .= $item["compsArray"][$i];
							if($i < count($item["compsArray"])-1) {
								$comString .= "#-#";
							}
						}
						$orderObj->addProduct($item["id"], $item["ud"], $item["cost"], $comString, $typeProduct);
					}
					
					unset($_SESSION[nameCartReparteat][$idCart]);
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
						$dir = "../../../perfil/";
						$textButton = "IR AL PEDIDO";
						
						$cont = 0;
						for($i=0;$i<count($usersSupplierID);$i++) {
							$u = $userObj->infoUserWebById($usersSupplierID[$i]);
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
							//Envio alerta App
							for($u=0;$u<count($userSend);$u++) {
								$tokenApp = $userObj->checkingTokenApp($userSend[$u]["mail"]);
								if($tokenApp) {
									sendGCM($subject, strip_tags($textMail), $tokenApp);
								}
							}
						} else {
							$msg .= NOUSERMAIL;
						}
					}	
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
				$msg = "La franja horaria seleccionada se ha completado mientras se tramitaba su pedido, por favor seleccione otra franja horario y vuelva a intentarlo.";
			}
		}else {
			$result = 1;
			$msg = "El restaurante ya no se encuentra disponible para pedidos. Perdone las molestias";
		}
	}else {
		$result = 1;
		$msg = "No se encuentran nigun producto asociado a este repartidor, por favor vuelva a intentarlo.";
	}
}else{
	$result = 1;
	$msg = "Ha ocurrido un error inesperado, vuelva a intentarlo, si el error persiste consulte con el administrador.";
}
$_SESSION[msgError]["result"] = $result;
$_SESSION[msgError]["msg"] = $msg;

if($result == 1) {
	header("Location: " . DOMAIN);
}else if($result == 2) {
	header("Location: " . DOMAIN . "resumen-pedido/" . $idCart);
}else {
	if($method->ID == 2 && $orderObj->status == 1) {
		header("Location: " . DOMAIN . "tpv-virtual/" . $ref);
	}else if($method->ID == 3 && $orderObj->status == 1) {
		header("Location: " . DOMAIN . "bizum/" . $ref);
	}else {
		header("Location: " . DOMAIN . "pedido-realizado/" . $ref);
	}
}
	
?>