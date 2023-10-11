<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	$V_PHP = explode(".", phpversion());
	if($V_PHP[0]>=5){
		date_default_timezone_set("Europe/Paris");
	}
	
	require_once("../../includes/classes/UserWeb/class.UserWeb.php");
	require_once("../../includes/classes/Order/class.Order.php");

	require_once ("../../../lib/Util/class.Util.php");
	require_once ("../../../lib/FileAccess/class.FileAccess.php");
	require_once("../../../includes/class/class.phpmailer.php");
	require_once("../../../includes/class/class.smtp.php");
	require_once("../../../api/helpers.php");
	if($_POST) {
		$mnu = trim($_POST["mnu"]);
		$com = trim($_POST["com"]);
		$opt = trim($_POST["opt"]);
		if(!isset($_POST["mnu"]) || !allowed($_POST["mnu"])) { 	
			disconnectdb($connectBD);
			$msg = "No tiene permisos para realizar esta acción";
			$location = "Location: ../../index.php?msg=".utf8_decode($msg);
			header($location);
		} else {
			
			$ref = intval($_POST["ref"]);
			$id = intval($_POST["id"]);
			$status = intval($_POST["status"]);
			$statusold = intval($_POST["statusold"]);
			
			$userObj = new UserWeb();
			$orderObj = new Order();
			$order = $orderObj->infoOrderByRef($ref);
			$userOrder = $userObj->infoUserWebById($order->IDUSER);
			$statusInfo = $orderObj->infoStatusOrder($status);
			$orderObj->updateStatus($id, $status, $statusold);
			if($status == 14) {//enviar correo al usuario
				//3. Generamos las alertas
				//Correo usuario
				$template = "user-order.html";
				$dir = "../../../perfil/";
				$textButton = "IR AL PEDIDO";
				
				$userSend = array();
				
				$userSend[0]["name"] = $userOrder->NAME . " " . $userOrder->SURNAME;
				$userSend[0]["mail"] = $userOrder->LOGIN;
				
				$subject = "Devolución pedido ".$order->REF . " realizada";
				$textMail = "<p>Se ha generado una devolución en su cuenta bancaria.</p>";
				$textMail .= "<p>Importe devuelto: <strong>".$order->COST." &euro;</strong></p>";
				
				$link = DOMAINZP."?view=order&mod=order&ref=".$order->REF;
				
				if(count($userSend)>0) {
					$msgAlert .= sendMailAlertClient($userSend, $subject, $textMail, $link, $textButton, $template, $dir);
					//Envio alerta App
					for($u=0;$u<count($userSend);$u++) {
						$tokenApp = $userObj->checkingTokenApp($userSend[$u]["mail"]);
						if($tokenApp) {
							sendGCM($subject, strip_tags($textMail), $tokenApp);
						}
					}
				} else {
					$msgAlert .= NOUSERMAIL;
				}
			}
			$msgAlert .= "Pedido pasado a <em>".$statusInfo->TITLE."</em> correctamente.";

			disconnectdb($connectBD);
			$msg = $msgAlert;
			$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=view&ref=".$ref."&msg=".utf8_decode($msg);
			header($location);
		}	
	}else {
		disconnectdb($connectBD);
		$msg = "Se ha producido un error, si el problema persiste, póngase en contacto con el administrador.";
		$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&opt=".$opt."&tpl=option&msg=".utf8_decode($msg);
		header($location);
	}
?>