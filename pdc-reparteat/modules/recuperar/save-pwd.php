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


	$error = "no-error";
	$msg = "";
	if ($_POST) {
		$now = time();
		$date_joker = "0000-00-00 00:00:00";
		$msgAlert = null;
		$now = new DateTime();
		
		$idUser = trim($_POST["idUser"]);
		$Login = trim($_POST["Login"]);
		$Code = trim($_POST["Code"]);
		
		$q = "select * from `" . preBD ."users_recover_pwd` where true
				and IDUSER = '" . $idUser . "' and LOGIN = '" . $Login . "' 
				and CODE = '".sha1($Code)."'
				and DATE_START <= '".$now->format("Y-m-d H:i:s")."' 
				and DATE_FINISH >= '".$now->format("Y-m-d H:i:s")."'
				order by DATE_START desc";
		$result = checkingQuery($connectBD, $q);
		$t = mysqli_num_rows($result);
		if ($t > 0) {
			$recover = mysqli_fetch_object($result);
			
			$userObj = 	new User(); 
			$user = $userObj->infoUserById($idUser);
		
			$PassSend = trim($_POST["Pass"]);
			$Pass = $PassSend;
			$PassRepeat = trim($_POST["PassRepeat"]);
			
			if(strlen($Pass) >= 8){		
				if($Pass == $PassRepeat) {
					$update = $userObj->updatePwd($user->ID, $Pass);
				}else {
					$error = "Password";
					$msg .= "Los campos de contraseñas no coinciden.<br/>";
				}
			}else{
				$error = "Password";
				$msg .= "La contraseña debe tener al menos 8 caracteres.<br/>";
			}
			
			if($error == "no-error") {
			
				$userSend[0]["name"] = $user->Name;
				$userSend[0]["mail"] = $user->Login;
				
				$subject = "Actualización de contraseña | RepartEat";
				$textMail = "Su contraseña del Panel de Control de RepartEat se ha modificado.<br/><strong>Nueva contraseña:";
				$link = DOMAIN."pdc-reparteat";
				$cont = 0;
				include("../../../includes/class/class.phpmailer.php");
				include("../../../includes/class/class.smtp.php");
				
				$msg .= sendMailVerificationPwd($userSend, $subject, $textMail, $PassSend, $link);
				$msg .= "<br/>Se le ha enviado un correo con su nueva contraseña.";
			}
		}else {
			$error = "error";
			$msg .= "La solicitud no existe o ha caducado, vuelva a solicitar el código.";
		}
	}else{
		$msg .= "Ha ocurrido un error inesperado, por favor vuelva a intentarlo";
		$error = "error";
	}
	$_SESSION["resultzp"]["class"] = $error;
	$_SESSION["resultzp"]["msg"] = $msg;
	if($error == "error") {
		$location = "Location: " . DOMAIN . "pdc-reparteat/actualizar-pwd.php";
	}else {
		$location = "Location: " . DOMAIN."pdc-reparteat";
	}
	header($location);
	disconnectdb($connectBD);
	
?>