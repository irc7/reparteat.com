<?php
	session_start();
	header ('Content-type: text/html; charset=utf-8');
	$V_PHP = explode(".", phpversion());
	if($V_PHP[0]>=5){
		date_default_timezone_set("Europe/Paris");
	}
	require_once ("../../../../pdc-reparteat/includes/database.php");
	$connectBD = connectdb();
	require_once ("../../../../pdc-reparteat/includes/config.inc.php");
	require_once ("../head/strings.php");
	require_once ("../../../../includes/functions.inc.php");
	require_once ("../../../includes/functions.php");

	require_once ("../../../../lib/Util/class.Util.php");
	require_once ("../../../../lib/FileAccess/class.FileAccess.php");
	require_once("../../../../includes/class/class.phpmailer.php");
	require_once("../../../../includes/class/class.smtp.php");


	require_once "../../../../includes/class/class.System.php";
	require_once("../../../../includes/class/UserWeb/class.UserWeb.php");	
	require_once("../../../../includes/class/Supplier/class.Supplier.php");
	require_once("../../../../includes/class/Product/class.Product.php");	
	require_once("../../../../includes/class/Order/class.Order.php");	
	require_once("../../../../includes/class/TelegramBot/class.TelegramBot.php");	
	require_once("../../../../api/helpers.php");
		
	$result = 0;
	$msg = "";
	//pre($_POST);die();
	if($_POST) {
		$newRep = intval($_POST["newRep"]);
		$ref = intval($_POST["ref"]);
		$userObj = new UserWeb();
		$ordObj = new Order();
		$order = $ordObj->infoOrderByRef($ref);
		if($order) {
			if($newRep != $order->IDREPARTIDOR) {
				if(($ordObj->checkViewOrderZone($_SESSION[nameSessionZP], $order) && $_SESSION[nameSessionZP]->IDTYPE == 5) || ($_SESSION[nameSessionZP]->IDTYPE == 3 && $_SESSION[nameSessionZP]->ID == $order->IDREPARTIDOR && ($order->STATUS == 3 || $order->STATUS == 4))) {
					
					if($ordObj->updateRep($order->ID,$newRep)) {

						$usersSendBD=array();
						$usersSendBD[] = $userObj->infoUserWebById($newRep);
						//Enviar alertas
						$template = "accept-order.html";
						//Correo 
						$dir = "../../../";
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
						}
						
						$subject = "Nuevo pedido RepartEat";
						$textMail = "<strong>Tiene un nuevo pedido para repartir.</strong>";
						$textMail .= "<br/>Restaurante: " . $supplierCart->TITLE;
						
						
						$link = DOMAINZP."?view=order&mod=order&tpl=delivery&filter=no-shipping";
						
						$cont = 0;
						if(count($userSend)>0) {
							$msg .= sendMailAlert($userSend, $subject, $textMail, $link, $textButton, $template, $dir);
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
						$msg = "El pedido con referencia <strong>".$ref."</strong> ha sido asignado a " . $usersSendBD[0]->NAME . " " . $usersSendBD[0]->SURNAME;	
					}else{
						$result = 1;
						$msg = "Ha ocurrido un error inesperado, vuelva a intentarlo, si el problema persiste consulte con el administrador";	
					}
				
				} else {
					$result = 1;
					$msg = "No tiene permisos para realizar esta acción";	
				}		
			}else {
				$result = 1;
				$msg = "El pedido ya se encuentra asignado a este repartidor.";	
			}
		}else{
			$result = 1;
			$msg = "No se encuentra ningún pedido con referencia: ".$ref;
		}
	}else{
		$result = 1;
		$msg = "Ha ocurrido un error inesperado, vuelva a intentarlo, si el error persiste consulte con el administrador.";
	}

	$_SESSION[msgError]["result"] = $result;
	$_SESSION[msgError]["msg"] = $msg;
	if($result == 1) {
		header("Location: " . DOMAINZP);
	}else {
		if($_SESSION[nameSessionZP]->IDTYPE == 3){
			header("Location: " . DOMAINZP . "?view=order&mod=order&tpl=delivery&filter=to-deliver");
		}else{
			header("Location: " . DOMAINZP . "?view=order&mod=order&ref=" . $order->REF);
		}	
	}

?>
