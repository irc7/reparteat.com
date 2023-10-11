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
			$now = new DateTime();
			
			$idUser = trim($_POST["idUser"]);
			$Login = trim($_POST["Login"]);
			$Code = trim($_POST["Code"]);
			
			$q = "select * from `" . preBD ."user_web_recover_pwd` where true
					and IDUSER = '" . $idUser . "' and LOGIN = '" . $Login . "' 
					and CODE = '".sha1($Code)."'
					and DATE_START <= '".$now->format("Y-m-d h:i:s")."' 
					and DATE_FINISH >= '".$now->format("Y-m-d h:i:s")."'
					order by DATE_START desc";
			$result = checkingQuery($connectBD, $q);
			$t = mysqli_num_rows($result);
			if ($t > 0) {
				$recover = mysqli_fetch_object($result);
				
				$userObj = new UserWeb();
				$user = $userObj->infoUserWebById($idUser);
				$userObj->pass = trim($_POST["Pass"]);
				$PassRepeat = trim($_POST["PassRepeat"]);
				if(strlen($userObj->pass) >= 8){		
					if($userObj->pass != $PassRepeat) {
						$error = 1;
						$msg .= "CONTRASEÑA INCORRECTA - Los campos de contraseñas no coinciden.<br/>";
					}
				}else{
					$error = 1;
					$msg .= "CONTRASEÑA INCORRECTA - La contraseña debe tener al menos 8 caracteres.<br/>";
				}
				
				if($error == 0) {
					$opciones = ['cost' => 15,];
					$pass_encript = password_hash($userObj->pass, PASSWORD_BCRYPT, $opciones);
				
					$q = "UPDATE `".preBD."user_web` SET
						`PASS`= '" .$pass_encript. "'
						WHERE ID = " . $idUser;
					checkingQuery($connectBD, $q);
				
					$msg .= "Contraseña modificada correctamente.";
				}
			}else {
				$error = 1;
				$msg .= "La solicitud no existe o ha caducado, vuelva a solicitar el código.";
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
		$location = "Location: " . DOMAINZP."iniciar-sesion";
	}
	header($location);
	disconnectdb($connectBD);
	
?>