<?php
	session_start();
	header ('Content-type: text/html; charset=utf-8');
	$V_PHP = explode(".", phpversion());
	
	if($V_PHP[0]>=5){
		date_default_timezone_set("Europe/Paris");
	}
	
	require_once ("../../includes/include.modules.php");

	require_once "../../includes/classes/Password/class.Password.php";
	require_once "../../includes/classes/User/class.User.php";
	
	require_once("../../../lib/FileAccess/class.FileAccess.php");
	$error = "no-error";
	$msg = "";
	if ($_POST) {
		
		$now = time();
		$date_joker = "0000-00-00 00:00:00";
		$msgAlert = null;
		
		$Login = trim($_POST["Login"]);
			$q = "SELECT 
					".preBD."users.ID,
					".preBD."users.Name,
					".preBD."users.Login as EMAIL,
					".preBD."users.Type
					FROM " . preBD ."users 
					WHERE " . preBD ."users.Login = '" . $Login . "'";
					$result = checkingQuery($connectBD, $q);
					$user = mysqli_fetch_object($result);
					
			if ($user != NULL) {
				$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ%&!?¿[]_';
				$charactersLength = strlen($characters);
				$randomString = '';
				$length = 8;
				for ($i = 0; $i < $length; $i++) {
					$randomString .= $characters[rand(0, $charactersLength - 1)];
				}
				
				$dateStart = new DateTime();
				$timeStampS = $dateStart->getTimestamp();
				$timeStampF = $dateStart->getTimestamp() + 3600;
				$dateFinish = new DateTime();
				$dateFinish->setTimestamp($timeStampF);
				
				$q = "DELETE FROM `" . preBD ."users_recover_pwd` WHERE IDUSER = '".$user->ID."'";
				checkingQuery($connectBD,$q);
				
				$q = "INSERT INTO `" . preBD ."users_recover_pwd`(`IDUSER`, `LOGIN`, `CODE`, `DATE_START`, `DATE_FINISH`) 
						VALUES 
						(".$user->ID.",'".sha1($Login)."','".sha1($randomString)."','".$dateStart->format("Y-m-d H:i:s")."','".$dateFinish->format("Y-m-d H:i:s")."')";
				checkingQuery($connectBD,$q);
				
				$userSend[0]["name"] = $user->Name;
				$userSend[0]["mail"] = $user->EMAIL;
				
				$subject = "Verificación correo electrónico";
				$textMail = "Código de verificación";
				$link = DOMAIN . "pdc-reparteat/insertar-codigo.php?&c=".sha1($Login);
				$cont = 0;
				include("../../../includes/class/class.phpmailer.php");
				include("../../../includes/class/class.smtp.php");
				
				$msg .= sendMailVerificationPwd($userSend, $subject, $textMail, $randomString, $link);
			} else {
				$msg .= "No existe ningún usuario activo con ese correo electrónico.";
				$error = "error";
			}
		
	}else{
		$msg .= "Ha ocurrido un error inesperado, por favor vuelva a intentarlo";
		$error = "error";
	}
	$_SESSION["resultzp"]["class"] = $error;
	$_SESSION["resultzp"]["msg"] = $msg;
	
	if($error == "error") {
		$location = "Location: " . DOMAIN . "pdc-reparteat/recover.php";
	}else {
		$location = "Location: " . DOMAIN . "pdc-reparteat/insertar-codigo.php?&c=".sha1($Login);
	}
	header($location);
	disconnectdb($connectBD);
	
?>