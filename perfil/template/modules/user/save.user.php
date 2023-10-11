<?php
	session_start();
	header ('Content-type: text/html; charset=utf-8');
	$V_PHP = explode(".", phpversion());
	if($V_PHP[0]>=5){
		date_default_timezone_set("Europe/Paris");
	}
	require_once ("../../../../pdc-reparteat/includes/database.php");
	$connectBD = connectdb();
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	require_once ("../../../../pdc-reparteat/includes/config.inc.php");
	if(isset($_SESSION[nameSessionZP]) && $_SESSION[nameSessionZP]->ID > 0) {
		header("Location: inicio");
	}
	require_once("../../../../includes/functions.inc.php");
	require_once("../../../includes/functions.php");
	require_once("../../../../includes/class/class.System.php");
	require_once("../../../../includes/class/UserWeb/class.UserWeb.php");
	require_once("../../../../includes/class/Password/class.Password.php");
	require_once("../../../../includes/class/Address/class.Address.php");
	
	require_once ("../../../../includes/lib/Util/class.Util.php");
	require_once ("../../../../includes/lib/FileAccess/class.FileAccess.php");
	
	require_once("../../../../includes/class/class.phpmailer.php");
	require_once("../../../../includes/class/class.smtp.php");
	
	require_once("../../../../includes/class/personal_keys.php");
	$msg = "";
	$error = 0;
	if ($_POST) {
		$recaptcha_response = $_POST['recaptcha_response']; 
		$recaptcha = file_get_contents($recaptcha_url . '?secret=' . $passSecretv3 . '&response=' . $recaptcha_response); 
		$recaptcha = json_decode($recaptcha); 

		if($recaptcha->score >= 0.7){
			$now = time();
			
			unset($_SESSION[msgError]["result"]);
			unset($_SESSION[msgError]["msg"]);
			
			$Email = trim($_POST["Email"]);
			
			$user = new UserWeb();
			
			$enc = $user->infoUserWebByLogin($Email);
			
			if($enc && $enc->STATUS != 5) {
				$error = 1;
				$msg .= "Ya existe un usuario registrado con el correo electrónico que nos ha proporcionado.<br/>";
			}else {
				$user->login = $Email;
			}
			
			
			$user->pass = trim($_POST["Pass"]);
			$PassRepeat = trim($_POST["PassRepeat"]);
			
			if(strlen($user->pass) >= 8){		
				if($user->pass != $PassRepeat) {
					$error = 1;
					$msg .= "CONTRASEÑA INCORRECTA - Los campos de contraseñas no coinciden.<br/>";
				}
			}else{
				$error = 1;
				$msg .= "CONTRASEÑA INCORRECTA - La contraseña debe tener al menos 8 caracteres.<br/>";
			}
			
			if($error == 0) {
				
				$user->idtype = 4; //usuario normal
				
				$name = trim($_POST["Name"]);
				$user->name = $user->resetStringName($name);
				
				$surname = trim($_POST["Surname"]);
				$user->surname = $user->resetStringName($surname);
				
				
				if(isset($_POST["DNI"])) {
					$user->dni = converMayusc(trim($_POST["DNI"]));
					if($user->dni != "" && !$user->validateDNI()) {
						$user->dni = "";
						$msg .= "El DNI introducido no tiene un formato correcto.<br/>";
					}
				}else {
					$user->dni = "";
				}				
				
				$user->phone = mysqli_real_escape_string($connectBD, trim($_POST["Phone"]));
				
				if(isset($_POST["IDTelegram"])) {
					$user->idtelegram = mysqli_real_escape_string($connectBD, trim($_POST["IDTelegram"]));
				}else{
					$user->idtelegram = "";
				}
				$user->superadmin = 0;
				$user->status = 5;//estado espera confirmación
				if(!$enc) {
					$idNew = $user->add();
				}else{
					$user->update($enc->ID);
					$idNew = $enc->ID;
				}
			
				if($idNew > 0) {
					$address = new Address();
					
					$address->street = trim($_POST["Street"]);
					$address->type = "user";
					$address->idassoc = $idNew;
					$address->fav = 1;
					$address->idzone = intval($_POST["Zone"]);
					
					if($address->street != "" || $address->idzone == 0) {
						if($enc && $enc->STATUS == 5) {
							$address->deleteByUser($enc->ID);
						}
						$address->add();
					}
			
					$subject = "Confirmación cuenta de correo";
					$tpl = "confirm-user";
					$text = "Se esta intentando registrar con este correo <em>".$user->login."</em> en la web <strong>reparteat.com</strong>.";
					$text .= "<div style='width:100%;height:20px;display:block;clear:both;'>&nbsp;</div>";
					$text .= "<a class='btn btn-mail' href='".DOMAINZP."confirmacion?email=".$user->login."'>Confirmar email</a>";
					
					$msg .= "Se le ha enviado un correo electrónico de confirmación. Mire su bozón de correo para activar su cuenta.";
					$msg .= sendMailAlertZP($user, $subject, $text, $tpl);
			
				} else {
					$error = 1;
					$msg.= "Ya existe un usuario registrado con la cuenta de correo que ha intruducido.";
				}
			}
		}else{
			$error = 1;
			$msg .= "Se ha detectado un uso erroneo de su cuenta, vuelva a intentarlo, si el problema persiste consulte con el administrador.";
		}
					
	}else{
		$error = 1;
		$msg.= "Ha ocurrido un error inesperado, vuelva a intentarlo más tarde, si el problema persiste, consulte con el administrador.";
	}
	$_SESSION[msgError]["result"] = $error;
	$_SESSION[msgError]["msg"] = $msg;
	if($error == 0) {
		header("Location: " . DOMAINZP."iniciar-sesion");
	}else{
		header("Location: " . DOMAINZP . "crear-cuenta");
	}