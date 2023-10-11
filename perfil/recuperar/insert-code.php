<?php
	session_start();
	header ('Content-type: text/html; charset=utf-8');
	$V_PHP = explode(".", phpversion());
	if($V_PHP[0]>=5){
		date_default_timezone_set("Europe/Paris");
	}
	require_once ("../../pdc-reparteat/includes/database.php");
	$connectBD = connectdb();
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	require_once ("../../pdc-reparteat/includes/config.inc.php");
	require_once ("../../includes/functions.inc.php");
	
	require_once("../includes/functions.php");
	require_once("../template/modules/head/strings.php");
	require_once("../../includes/class/personal_keys.php");
	require_once "../../includes/class/class.System.php";
	require_once "../../includes/class/Password/class.Password.php";
	require_once "../../includes/class/UserWeb/class.UserWeb.php";
	require_once "../../includes/class/Login/class.Login.php";
	require_once ("../../includes/lib/Util/class.Util.php");
	require_once ("../../includes/lib/FileAccess/class.FileAccess.php");
	
	require_once("../../includes/class/class.phpmailer.php");
	require_once("../../includes/class/class.smtp.php");
	
	$error = 0;
	$msg = "";
	if ($_POST) {
		$recaptcha_response = $_POST['recaptcha_response']; 
		$recaptcha = file_get_contents($recaptcha_url . '?secret=' . $passSecretv3 . '&response=' . $recaptcha_response); 
		$recaptcha = json_decode($recaptcha); 

		if($recaptcha->score >= 0.7){

			$now = time();
			$date_joker = "0000-00-00 00:00:00";
			$msgAlert = null;
			
			$Login = trim($_POST["Login"]);
			$Pass = "";
			$logueo = new Login($Login, $Pass);
			$user = $logueo->checkUser();
			
			if ($user) {
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
				
				$q = "DELETE FROM `" . preBD ."user_web_recover_pwd` WHERE IDUSER = '".$user->ID."'";
				checkingQuery($connectBD,$q);
				
				$q = "INSERT INTO `" . preBD ."user_web_recover_pwd`(`IDUSER`, `LOGIN`, `CODE`, `DATE_START`, `DATE_FINISH`) 
						VALUES 
						(".$user->ID.",'".sha1($Login)."','".sha1($randomString)."','".$dateStart->format("Y-m-d h:i:s")."','".$dateFinish->format("Y-m-d h:i:s")."')";
				checkingQuery($connectBD,$q);
				
				$userSend[0]["name"] = $user->NAME . " " . $user->SURNAME;
				$userSend[0]["mail"] = $user->LOGIN;
				
				
				
				
				$subject = "Verificación correo electrónico";
				$textMail = "Código de verificación";
				$link = DOMAINZP . "recuperar/insertar-codigo.php?&c=".sha1($Login);
				
				$msg .= "Se le ha enviado un correo electrónico con el código de validación. Mire su bozón de correo e inserte el código que se le ha enviado.";
				$msg .= sendMailVerificationZP($userSend, $subject, $textMail, $randomString, $link);
				
			} else {
				$msg .= "No existe ningún usuario activo con ese correo electrónico.";
				$error = 1;
			}
		}else{
			$msg .= "Se ha detectado un uso erroneo de su cuenta, vuelva a intentarlo, si el problema persiste consulte con el administrador.";
			$error = 1;
		}
	}else{
		$msg .= NOPOST;
		$error = 1;
	}
	$_SESSION[msgError]["result"] = $error;
	$_SESSION[msgError]["msg"] = $msg;
	if($error == 1) {
		$location = "Location: " . DOMAINZP . "recuperar-contrasena";
	}else {
		$location = "Location: " . DOMAINZP . "recuperar/insertar-codigo.php?&c=".sha1($Login);
	}
	header($location);
	disconnectdb($connectBD);
	
?>