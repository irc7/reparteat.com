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
	require_once("../../../../includes/functions.inc.php");
	require_once("../../../includes/functions.php");
	
	if(!isset($_SESSION[nameSessionZP]) || $_SESSION[nameSessionZP]->ID == 0) {
		header("Location: iniciar-sesion");
	}
	
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
	$id = intval($_POST["id"]);
	if($_SESSION[nameSessionZP]->ID == $id) {
		if($_POST) {
			
			$userObj = new UserWeb();
			$userObj->id = $id;
			
			$userBD = $userObj->infoUserWebById($id);
			$changeLog = false;
			$changePass = false;
			if(isset($_POST["Email"]) && isset($_POST["Pass"]) && isset($_POST["PassRepeat"])) {
				$Email = trim($_POST["Email"]);
				if ($Email != $userBD->LOGIN) {
					$enc = $userObj->infoUserWebByLogin($Email);
					$userObj->login = $Email;
					if($enc!= false) {
						$error = 1;
						$msg .= "El e-mail ya está siendo utilizado por otro usuario.<br/>";
					}else {
						$changeLog = true;
					}
				}
				$userObj->pass = trim($_POST["Pass"]);
				$PassRepeat = trim($_POST["PassRepeat"]);
				if(strlen($userObj->pass) >= 8){		
					if($userObj->pass != $PassRepeat) {
						$error = 1;
						$msg .= "Los campos de contraseñas no coinciden.<br/>";
					}else {
						$changePass = true;
					}
				}else{
					$error = 1;
					$msg .= "La contraseña debe tener al menos 8 caracteres.<br/>";
				}
			}
			
			if($error == 0) {
				$userObj->status = $userBD->STATUS;
				
				$name = trim($_POST["Name"]);
				$userObj->name = $userObj->resetStringName($name);
				
				$surname = trim($_POST["Surname"]);
				$userObj->surname = $userObj->resetStringName($surname);
				
				
				$userObj->dni = converMayusc(trim($_POST["DNI"]));
				if($Dni != "" && !$userObj->validateDNI()) {
					$Dni = "";
					$msg .= "El DNI introducido no tiene un formato correcto.<br/>";
				}
				
				$userObj->phone = mysqli_real_escape_string($connectBD, trim($_POST["Phone"]));
				if(isset($_POST["IDTelegram"])) {
					$userObj->idtelegram = mysqli_real_escape_string($connectBD, trim($_POST["IDTelegram"]));
				}else{
					$userObj->idtelegram = "";
				}
				
				$userObj->superadmin = $userBD->SUPERADMIN;
				
				
				if($changeLog) {
					$userObj->updateLog($id);
				}
				if($changePass) {
					$userObj->updatePass($id);
				}
				
				$userObj->update($id);
				
				$_SESSION[nameSessionZP]->NAME = $userObj->name;
				$_SESSION[nameSessionZP]->SURNAME = $userObj->surname;
				$_SESSION[nameSessionZP]->DNI = $userObj->dni;
				$_SESSION[nameSessionZP]->SUPERADMIN = $userObj->superadmin;
				$_SESSION[nameSessionZP]->PHONE = $userObj->phone;
				$_SESSION[nameSessionZP]->IDTELEGRAM = $userObj->idtelegram;
				$_SESSION[nameSessionZP]->STATUS = $userObj->status;
				
				$msg.= "Datos actualizados correctamente";
			} else {
				$error = 1;
				$msg.= "<br/>Error al actualizar los datos";
			}
		}else{
			$error = 1;
			$msg.= NOPOST;
		}
	}else{
		$error = 1;
		$msg.= NOACCESS;
	}
	$_SESSION[msgError]["result"] = $error;
	$_SESSION[msgError]["msg"] = $msg;
	
	disconnectdb($connectBD);
	
	if($changeLog || $changePass) {
		$location = "Location: " . DOMAINZP . "cerrar-sesion";
	}else {
		$location = "Location: " . DOMAINZP . "?view=user&mod=user&tpl=profile";
	}
	header($location);

?>