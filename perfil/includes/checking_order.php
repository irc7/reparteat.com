<?php
	session_start();
	
	$V_PHP = explode(".", phpversion());
	if($V_PHP[0]>=5){
		date_default_timezone_set("Europe/Paris");
	}
	
	require_once ("../../pdc-reparteat/includes/database.php");
	$connectBD = connectdb();
	require_once ("../../pdc-reparteat/includes/config.inc.php");
	require_once ("../../includes/functions.inc.php");
	require_once ("functions.php");

	require_once ("../../lib/Util/class.Util.php");
	require_once ("../../lib/FileAccess/class.FileAccess.php");
	require_once("../../includes/class/class.phpmailer.php");
	require_once("../../includes/class/class.smtp.php");


	require_once "../../includes/class/class.System.php";
	require_once("../../includes/class/UserWeb/class.UserWeb.php");	
	require_once("../../includes/class/Supplier/class.Supplier.php");
	require_once("../../includes/class/Product/class.Product.php");	
	require_once("../../includes/class/Order/class.Order.php");	
	require_once("../../includes/class/Zone/class.Zone.php");
	require_once("../../includes/class/TelegramBot/class.TelegramBot.php");	
	require_once("../../api/helpers.php");


	$orderObj = new Order();
	$userObj = new UserWeb();
	$supObj = new Supplier();
	$zoneObj = new Zone();
	$orders = $orderObj->orderByStatus(2);
	$now = new DateTime();
	if(count($orders) > 0) {
		foreach($orders as $order) {
			$zone = $zoneObj->infoZone($order->IDZONE);
			$userOrder = $userObj->infoUserWebById($order->IDUSER);
			$supOrder = $supObj->infoSupplierById($order->IDSUPPLIER);
			$dateOrder = new DateTime($order->DATE_CREATE);
			
			$time = $zone->TIME_CHECK_ORDER * 60;//pasamos a segundos
			$segs = intval($dateOrder->getTimestamp()) + intval($time);
			$nowSegs = intval($now->getTimestamp());
			
			if($nowSegs > $segs) {
				$order->STATUS = $orderObj->updateStatus($order->ID, 12, 2);
				
				$userOrder = $userObj->infoUserWebById($order->IDUSER);
			
			//Enviar alertas Proveedor
				$botObj = new TelegramBot(TELEGRAMTOKEN, $supOrder->IDTELEGRAM);
				if($botObj->chatid !== false){
					$text ="Tiempo de espera agotago\nREF: " . $order->REF;
					$urlBoton = DOMAIN.'perfil/?view=order&mod=order&ref='.$order->REF;
					$boton = '[{"text":"Ir al pedido","url":"'.urlencode($urlBoton).'"}]';
					
					$botObj->sendMessage($text,$boton);
					//$content = "\n\n" . $now->format("d-m-Y H:i:s") . "\n" .$order->REF . "\n" .$text . "\n\n";
					//file_put_contents("webhook_ordersupplier.log", $content, FILE_APPEND);
				}else {
					$content = "\n\nERROR:" . $now->format("d-m-Y H:i:s") . "\n" .$order->REF . "\n" .$text . "\n\n";
					file_put_contents("webhook_ordersupplier.log", $content, FILE_APPEND);
				}
			
			//Correo usuario
				$template = "user-order.html";
				$dir = "../";
				$textButton = "IR AL PEDIDO";
				
				$userSend = array();

				$userSend[0]["name"] = $userOrder->NAME . " " . $userOrder->SURNAME;
				$userSend[0]["mail"] = $userOrder->LOGIN;
					
				
				$subject = "Su pedido ha agotado el tiempo de espera";
				$subjectApp = "Reparteat";
				$textMail = "<p style='color:#333333;font-size:14px;text-align:center;'>";
				$textMail .= "<strong>El resturante <em>".$supplierCart->TITLE."</em> no ha tramitado su pedido con Ref.:".$order->REF.".</strong>";
				$textApp = "El resturante ".$supplierCart->TITLE." no ha tramitado su pedido con Ref.:".$order->REF.".";
				$textMail .= "</p>";
				
				$textMail .= "<p style='color:#333333;font-size:14px;text-align:center;'>";
				$textMail .= "Vuelva a intentarlo o pruebe a pedir a otro de nuestros restaurantes";
				$textMail .= "</p>";
				
				$textMail .= "<p style='color:#333333;font-size:14px;text-align:center;'><strong>Disculpe las molestias</strong></p>";
				$textMail .= "<h4 style='color:#333333;font-size:15px;text-align:center;margin-bottom:10px;'>Motivo de la cancelación:</h4>";
				$textMail .= "<h5 style='color:#a11003;font-size:14px;text-align:center;margin-top:10px;'>";
				$textMail .= "Tiempo de espera agotado";
				$textMail .= "</h5>";
				$textApp .= "Motivo de la cancelación: Tiempo de espera agotado.";
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

				
				$link = DOMAINZP."?view=order&mod=order&ref=".$orderObj->ref;
				$cont = 0;
				
				if(count($userSend)>0) {
					sendMailAlert($userSend, $subject, $textMail, $link, $textButton, $template, $dir);
					//Envio alerta App
					for($u=0;$u<count($userSend);$u++) {
						$tokenApp = $userObj->checkingTokenApp($userSend[$u]["mail"]);
						if($tokenApp) {
							sendGCM($subjectApp, strip_tags($textApp), $tokenApp);
						}
					}
				}
			}
		}
	}

?>