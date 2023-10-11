<?php
	session_start();
	header ('Content-type: text/html; charset=utf-8');
	$V_PHP = explode(".", phpversion());
	if($V_PHP[0]>=5){
		date_default_timezone_set("Europe/Paris");
	}
	require_once ("../../../pdc-reparteat/includes/database.php");
	$connectBD = connectdb();
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	require_once ("../../../pdc-reparteat/includes/config.inc.php");
	if(isset($_SESSION[nameSessionZP]) && $_SESSION[nameSessionZP]->ID > 0) {
		header("Location: inicio");
	}
	require_once("../../../includes/functions.inc.php");
	require_once ("../../../includes/lib/Util/class.Util.php");
	require_once ("../../../includes/lib/FileAccess/class.FileAccess.php");
	
	require_once("../../../includes/class/class.phpmailer.php");
	require_once("../../../includes/class/class.smtp.php");
	
	require_once("../../../includes/class/personal_keys.php");
	
	$recaptcha_response = $_POST['recaptcha_response']; 
	$recaptcha = file_get_contents($recaptcha_url . '?secret=' . $passSecretv3 . '&response=' . $recaptcha_response); 
	$recaptcha = json_decode($recaptcha); 
	if($recaptcha->score >= 0.7){
		if ($_POST) {
			/*
			flush the errors.
			*/
			unset($_SESSION[msgError]["result"]);
			unset($_SESSION[msgError]["msg"]);
			
			
			$name = trim($_POST["Name"]);
			$email = trim($_POST["Email"]);
			$Phone = intval($_POST["Phone"]);
			$text = trim($_POST["Text"]);
			
			if ( $name != "" && Util::isValidEmail($email) && $text != "") {

				/*
				here you can send the mail
				*/
				$mail = new PHPMailer();
				$mail->IsSMTP();
				$mail->Host = MAILHOST;
				$mail->From = MAILSEND;
				$mail->FromName = "=?ISO-8859-1?B?".base64_encode(utf8_decode("Formulario de contacto - RepartEat"))."=?=";
				$mail->Subject = "=?ISO-8859-1?B?".base64_encode(utf8_decode($subject))."=?=";	
				$mail->AltBody = utf8_encode("Se le está intentando enviar un e-mail con contenido que su gestor de correos no puede leer");
				$fileAccess = new FileAccess("../../../files/mail-templates/contact.html");
				$body = $fileAccess->read();
				
				$body = str_replace("#DOMAIN#", DOMAIN, $body);
				$body = str_replace("#PHONE#", $Phone, $body);
				$body = str_replace("#NAME#", $name, $body);
				$body = str_replace("#EMAIL#", $email, $body);
				$body = str_replace("#TEXT#", $text, $body);
				
				$mail->MsgHTML(utf8_decode($body)); // utf8 decode just BEFORE send.
				$mail->SMTPAuth = true;
				$mail->Username = USERHOST;
				$mail->Password = PASSHOST;
				$mail->Port = PORTHOST;
				
				if(SECURITYHOST == 1) {
					$mail->SMTPSecure = "ssl";
				}elseif(SECURITYHOST == 2) {
					$mail->SMTPSecure = "tls";
				}
				$mail->AddAddress("info@reparteat.com", utf8_decode("Formulario de contacto RepartEat"));
				$mail->AddAddress("ecarpintero@reparteat.com", utf8_decode("Formulario de contacto RepartEat"));
				$mail->AddAddress("fjvenegas@reparteat.com", utf8_decode("Formulario de contacto RepartEat"));
				
				if (!$mail->Send()) {
					$error = 0;
					$msg = "Ha ocurrido un error al enviar la sugerencia - ". $mail->ErrorInfo;
				}else {
					$error = 0;
					$msg = "Su consulta ha sido enviada correctamente.";
				}
			} else {
				$error = 1;
				$msg = "Debe completar los campos obligatorios";
			}
		}
	}else{
		$error = 1;
		$msg = "Se ha detectado un uso indevido del formulario, vuelva a intentarlo, si el problema persiste consulte con el administrador.";
	}
	$_SESSION[msgError]["result"] = $error;
	$_SESSION[msgError]["msg"] = $msg;

	header("Location: " . DOMAIN . "escribenos");