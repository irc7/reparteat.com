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
	require_once("../../includes/classes/Password/class.Password.php");
	require_once("../../includes/classes/Address/class.Address.php");
	$mnu = trim($_POST["mnu"]);
	if (!allowed($mnu)) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
	$com = trim($_POST["com"]);
	$tpl = trim($_POST["tpl"]);
	$opt = trim($_POST["opt"]);
	if ($_POST) {
		
		$msg = "";
		$error = NULL;
		
		$Email = trim($_POST["Email"]);
		
		$user = new UserWeb();
		
		$enc = $user->infoUserWebByLogin($Email);
		if($enc) {
			$error = "Login";
			$msg .= "El e-mail ya está siendo utilizado por otro usuario.<br/>";
		}else {
			$user->login = $Email;
		}
		
		
		$user->pass = trim($_POST["Pass"]);
		$PassRepeat = trim($_POST["PassRepeat"]);
		
		if(strlen($user->pass) >= 8){		
			if($user->pass != $PassRepeat) {
				$error = "Password";
				$msg .= "Los campos de contraseñas no coinciden.<br/>";
			}
		}else{
			$error = "Password";
			$msg .= "La contraseña debe tener al menos 8 caracteres.<br/>";
		}
		
		if($error == NULL) {
			
			$user->status = intval($_POST["status"]);
			$user->idtype = intval($_POST["Type"]);
			
			$name = trim($_POST["Name"]);
			$user->name = $user->resetStringName($name);
			
			$surname = trim($_POST["Surname"]);
			$user->surname = $user->resetStringName($surname);
			
			$user->dni = converMayusc(trim($_POST["DNI"]));
			$user->saldo = floatval($_POST["Saldo"]);
			if($Dni != "" && !$user->validateDNI()) {
				$Dni = "";
				$msg .= "El DNI introducido no tiene un formato correcto.<br/>";
			}
			
			$user->phone = mysqli_real_escape_string($connectBD, trim($_POST["Phone"]));
			if(isset($_POST["IDTelegram"])) {
				$user->idtelegram = mysqli_real_escape_string($connectBD, trim($_POST["IDTelegram"]));
			}else{
				$user->idtelegram = "";
			}
			$user->superadmin = 0;
			
			$idNew = $user->add();
			
			if($idNew > 0) {
				$address = new Address();
				
				$address->street = trim($_POST["Street"]);
				$address->type = "user";
				$address->idassoc = $idNew;
				$address->fav = 1;
				$address->idzone = intval($_POST["Zone"]);
				
				if($address->street != "" || $address->idzone == 0) {
					$address->add();
				}
				
				disconnectdb($connectBD);
				$msg .= "Usuario <em>".$user->name." ".$user->surname."</em> registrado correctamente";
				$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=edit&opt=".$opt."&id=".$idNew."&msg=".utf8_decode($msg);
				header($location);
			} else {
				disconnectdb($connectBD);
				$msg .= "Error al registrar al usuario <em>".$user->name." ".$user->surname."</em>.";
				$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=create&opt=".$opt."&msg=".utf8_decode($msg);
				header($location);
			}
		} else {
			disconnectdb($connectBD);
			$msg .= "Error al registrar al usuario.";
			$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=create&opt=".$opt."&msg=".utf8_decode($msg);
			header($location);
		}
		
	}else {
		disconnectdb($connectBD);
		$msg .= "Error al registrar al usuario.";
		$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=create&opt=".$opt."&msg=".utf8_decode($msg);
		header($location);
	}
?>