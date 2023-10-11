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
	include("../../../includes/tpv-redsys/apiRedsys.php");
	

	if(isset($_POST) || isset($_GET)) {
		// Se crea Objeto
		$miObj = new RedsysAPI;
		
		if (!empty( $_POST ) ) {//URL DE RESP. ONLINE
			$version = $_POST["Ds_SignatureVersion"];
			$datos = $_POST["Ds_MerchantParameters"];
			$signatureRecibida = $_POST["Ds_Signature"];
		}else if (!empty( $_GET ) ) {//URL DE RESP. ONLINE
			$version = $_GET["Ds_SignatureVersion"];
			$datos = $_GET["Ds_MerchantParameters"];
			$signatureRecibida = $_GET["Ds_Signature"];
		}
		
		/*Clave*/
		$q = "select * from ".preBD."tpv_configuration where ID = 2";
		$result = checkingQuery($connectBD, $q);
		$pass_encript = mysqli_fetch_object($result);
		$key = trim($pass_encript->CODE);
		
		$decodec = $miObj->decodeMerchantParameters($datos);	
		$firma = $miObj->createMerchantSignatureNotif($key,$datos);	

		if ($firma === $signatureRecibida){
			if (!empty( $_POST ) ) {//URL DE RESP. ONLINE
				$date_post = $miObj->getParameter("Ds_Date");
				$hour = $miObj->getParameter("Ds_Hour");
			}else if (!empty( $_GET ) ) {//URL DE RESP. ONLINE
				$date_post = urldecode($miObj->getParameter("Ds_Date"));
				$hour = urldecode($miObj->getParameter("Ds_Hour"));
			}
			
			$order_date = explode("/",$date_post);
			$date = $order_date[2] . "-" . $order_date[1] . "-" . $order_date[0] . " " . $hour . ":00";

			$amount = $miObj->getParameter("Ds_Amount");//coste del pedido
			$amount = number_format(($amount / 100), 2, '.', '');
			$order = $miObj->getParameter("Ds_Order"); //id del pedido
			$reference = $order; //referencia del pedido, la q nosotros generamos
			
			$code_response = $miObj->getParameter("Ds_Response"); // Con esta función es posible - recoger cualquier variable de notificación
			
			$payment = $miObj->getParameter("Ds_SecurePayment"); //0->Si el pago no es seguro; 1->Pago seguro
			$country_card = $miObj->getParameter("Ds_Card_Country");//Pais de origen de la tarjeta
			$authorisationCode = $miObj->getParameter("Ds_AuthorisationCode");//código de autorización asignado a la aprobación de la transacción
			
			$errorCode = $miObj->getParameter("Ds_ErrorCode");
			
			if(($errorCode == "") || ($errorCode == NULL) || (!isset($errorCode))) {
				$errorCode = "SIS0000";	
			}
			
			/*caso de que no venga definida la tarjeta del país	*/
			if(($country_card == "") || ($country_card == NULL) || (!isset($country_card))) {
				$country_card = "000";	
			}
			
			$q = "INSERT INTO `".preBD."tpv_record`(`DATE`, `AMOUNT`, `IDSALE`, `RESPONSE`, `REFERENCE`, `PAYMENT`, `CARD_COUNTRY`, `AUTHORISATION_CODE`, `SIGNATURE`, `ERROR_CODE`) VALUES";
			$q .= " (NOW(),'".$amount."','".$order."','".$code_response."','".$reference."',".$payment.",'".$country_card."','".$authorisationCode."', '".$signatureRecibida."', '".$errorCode."')";			
			checkingQuery($connectBD, $q);
			
			//info del pedido en bd
			$ordObj = new Order();
			$userObj = new UserWeb();
			$orderBD = $ordObj->infoOrderByRef($reference);
			$address = $ordObj->orderAddress($orderBD->IDADDRESS);
			$methodPay = $ordObj->orderMethodPay($orderBD->IDMETHODPAY);
			
			$supObj = new Supplier();
			$supplierCart = $supObj->infoSupplierById($orderBD->IDSUPPLIER);
		
			$proObj = new Product();
			
			$products = $ordObj->listProductOrder($orderBD->ID);
			if($code_response < 100 || $code_response == 400 || $code_response == 900) {
				
			//actualizar estado del pedido
				$ordObj->updateStatus($orderBD->ID, 2, $orderBD->STATUS);
				
				//Enviar alertas
				$usersSupplierID = $supObj->infoSupplierUser($supplierCart->ID, "proveedor");
				
				$botObj = new TelegramBot(TELEGRAMTOKEN, $supplierCart->IDTELEGRAM);
				if($botObj->chatid !== false){
					$text ="Ha recibido un nuevo pedido en RepartEat. REF: " . $orderBD->REF;
					$urlBoton = DOMAIN.'perfil/?view=order&mod=order&ref='.$orderBD->REF;
					$boton = '[{"text":"Ver pedido","url":"'.urlencode($urlBoton).'"}]';
					
					$botObj->sendMessage($text,$boton);
					//$content = "\n\n" . $now->format("d-m-Y H:i:s") . "\n" .$ref . "\n" .$text . "\n\n";
					//file_put_contents("webhook_ordersupplier.log", $content, FILE_APPEND);
				}else {
					$content = "\n\nERROR:" . $now->format("d-m-Y H:i:s") . "\n" .$ref . "\n" .$text . "\n\n";
					file_put_contents("webhook_ordersuppliertpv.log", $content, FILE_APPEND);
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
				$link = DOMAINZP."?view=order&mod=order&ref=".$orderBD->REF;
				
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
				
			}else{
			//ERROR paso el pedido a error en el pago
				$ordObj->updateStatus($orderBD->ID, 7, $orderBD->STATUS);			
				$q = "select DESCRIPTION from `".preBD."tpv_error` where CODE = "	. $code_response . " or CODE_AUX = '" . $errorCode. "'";
				$r = checkingQuery($connectBD, $q);
				$eTPV = mysqli_fetch_object($r);
				if($eTPV) {
					$msgTPV = $eTPV->DESCRIPTION;	
				} else {
					$msgTPV = "Error desconocido";
				}
			}
			
		}
	}
?>