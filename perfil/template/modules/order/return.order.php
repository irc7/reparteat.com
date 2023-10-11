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
	if($_POST) {
		$next = intval($_POST["next"]);
		$ref = intval($_POST["ref"]);
		$ordObj = new Order();
		$order = $ordObj->infoOrderByRef($ref);
		if($order) {
			$address = $ordObj->orderAddress($order->IDADDRESS);
			$methodPay = $ordObj->orderMethodPay($order->IDMETHODPAY);
			
			$supObj = new Supplier();
			$supplierCart = $supObj->infoSupplierById($order->IDSUPPLIER);
			$proObj = new Product();
			$userObj = new UserWeb();
			$userOrder = $userObj->infoUserWebById($order->IDUSER);
			
			$statusOld = $order->STATUS;
			
			if($next != $statusOld) {
			
				if($_SESSION[nameSessionZP]->IDTYPE == 5 && $next > 0 && $next != $statusOld) {
					if($ordObj->checkViewOrderZone($_SESSION[nameSessionZP], $order)) {
						$order->STATUS = $ordObj->updateStatus($order->ID, $next, $statusOld);
						$msg .= "Cambio de estado del pedido modificado correctamente";
					}else {
						$result = 1;
						$msg .= "No tiene permisos para cambiar el estado de este pedido";
					}
				}else {
					if($order->STATUS != 12) {
						//actualiza el estado del pedido e inserto registro de cambio de estado
						if($next == 4 && $_SESSION[nameSessionZP]->IDTYPE == 3 && $order->IDREPARTIDOR == 0) {
							//actualizo tiempo de repartido y lo asocio al pedido
							$time = intval($_POST["aux"]);
							$ordObj->updateTimeRep($order->ID, $_SESSION[nameSessionZP]->ID, $time);
						}else {
							$order->STATUS = $ordObj->updateStatus($order->ID, $next, $statusOld);
						}
						$now = new DateTime();
						switch($next) {
							case '3':
								if($ordObj->checkViewOrder($_SESSION[nameSessionZP]->ID, $order->ID)) {
									//Pedido aceptado por el restaurante
									//mando mail a usuario pedido aceptado y repartidor
									
									//$time = intval($_POST["aux"]);Anulamos el valor del post y ponemos el de la base de datos
									$time = $supplierCart->TIME;
									$ordObj->updateTimeSup($order->ID, $time);//Actualiza el tiempo de cocina y da valor a DATE_START
									$order->TIMESUPPLIER = $time;
									
									//Enviar alertas
									//repartidores
									$usersSendBD = $supObj->assignSupplierRepartidor($order);
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
									
									//Correo usuario
									
									$template = "user-order.html";
									$dir = "../../../";
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
									
									$link = DOMAINZP."?view=order&mod=order&ref=".$orderObj->ref;
									
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
									
									$msg .= "Pedido aceptado";
								}else {
									$result = 1;
									$msg .= "No tiene permisos para cambiar el estado de este pedido";
								}
							break;
							case '4':
								//Pedido aceptado por el repartidor
								if($order->STATUS == 4 && $_SESSION[nameSessionZP]->IDTYPE == 2) {
									$statusBD = $ordObj->infoStatusOrder($order->STATUS);
									
									//Correo repartidores
									
									$template = "accept-order.html";
									$dir = "../../../";
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
											$cont++;
										}
										$subject = "Pedido listo sin repartidor asignado";
										$textMail = "<strong>Restaurante:</strong> " . $supplierCart->TITLE;
										$textMail .= "<br/><strong>El pedido <em>".$order->REF."</em> y no tiene asignado ningún repartidor.</strong>";
									}
									
									$link = DOMAINZP."?view=order&mod=order&ref=".$order->REF;
									
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
									
									$msg .= "Pasado a " . $statusBD->TITLE;
									
								} else if(($order->STATUS == 3 || $order->STATUS == 4) && $_SESSION[nameSessionZP]->IDTYPE == 3 && $order->IDREPARTIDOR == 0) {
									$msg .= "El pedido le ha sido asignado para el reparto";
								} else if($order->STATUS == 3 && $_SESSION[nameSessionZP]->IDTYPE == 3 && $order->IDREPARTIDOR > 0) {
									$error = 1;
									$msg .= "El pedido ya ha sido asignado a otro repartidor";
								}else{
									$error = 1;
									$msg .= "No tiene permisos para cambiar el estado de este pedido";
								}
							
							break;
							case '5':
									//Pedido enviado
									if($ordObj->checkingRepartidorOrder($_SESSION[nameSessionZP]->ID, $supplierCart->ID)) {
										if($order->IDREPARTIDOR == $_SESSION[nameSessionZP]->ID) {
											$statusBD = $ordObj->infoStatusOrder($order->STATUS);
											$msg .= "Pasado a " . $statusBD->TITLE;
											$msg .= "<br/><br/>Observaciones del pedido:<br/><em>" .$order->COMMENT."</em>";
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
								if($ordObj->checkingRepartidorOrder($_SESSION[nameSessionZP]->ID, $supplierCart->ID)) {
									if($order->IDREPARTIDOR == $_SESSION[nameSessionZP]->ID) {
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
								//Error en el pago, para cuando haya TPV
							break;
							case '8':
								//Pedido cancelado por el restaurante
								if($ordObj->checkViewOrder($_SESSION[nameSessionZP]->ID, $order->ID)) {
									
									//actualizo a 8 y mando mail a usuario con pedido cancelado 
									$text = trim($_POST["aux"]);
									//actualizo el motivo de cancelación
									$q = "UPDATE `".preBD."order_staus_time` SET `TEXT`='".$text."' 
												WHERE IDORDER = " . $order->ID . " 
												and IDSTATUS = 8";
												checkingQuery($connectBD, $q);
												
												
												//Correo usuario
										$template = "user-order.html";
										$dir = "../../../";
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
										
										$link = DOMAINZP."?view=order&mod=order&ref=".$orderObj->ref;
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
								
							break;
							case '10':
								//Pedido cancelado por el repartidor
								if($ordObj->checkViewOrder($_SESSION[nameSessionZP]->ID, $order->ID)) {
									//actualizo a 10 y mando mail a usuario con pedido cancelado 
									$text = trim($_POST["aux"]);
									$q = "UPDATE `".preBD."order_staus_time` SET `TEXT`='".$text."' 
												WHERE IDORDER = " . $order->ID . " 
												and IDSTATUS = 10";
												checkingQuery($connectBD, $q);
												
												
										//Correo usuario
										$template = "user-order.html";
										$dir = "../../../";
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
										
										$link = DOMAINZP."?view=order&mod=order&ref=".$orderObj->ref;
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

	$_SESSION[msgError]["result"] = $result;
	$_SESSION[msgError]["msg"] = $msg;
	if($result == 1) {
		header("Location: " . DOMAINZP);
	}else {
		if(($next == 5 || $next == 6) && $order->IDREPARTIDOR == $_SESSION[nameSessionZP]->ID) {
			header("Location: " . DOMAINZP . "perfil/?view=order&mod=order&tpl=delivery&filter=to-deliver");
		}else {
			header("Location: " . DOMAINZP . "?view=order&mod=order&ref=" . $order->REF);
		}
	}

?>
