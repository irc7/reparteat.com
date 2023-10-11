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
		
		$id = intval($_POST["id"]);
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
					$error = "Login";
					$msg .= "El e-mail ya está siendo utilizado por otro usuario.<br/>";
				}else {
					$changeLog = true;
				}
			}
			$userObj->pass = trim($_POST["Pass"]);
			$PassRepeat = trim($_POST["PassRepeat"]);
			if(strlen($userObj->pass) >= 8){		
				if($userObj->pass != $PassRepeat) {
					$error = "Password";
					$msg .= "Los campos de contraseñas no coinciden.<br/>";
				}else {
					$changePass = true;
				}
			}else{
				$error = "Password";
				$msg .= "La contraseña debe tener al menos 8 caracteres.<br/>";
			}
		}
		
		if($error == NULL) {
			$userObj->status = intval($_POST["status"]);
			$userObj->idtype = intval($_POST["Type"]);
			
			$name = trim($_POST["Name"]);
			$userObj->name = $userObj->resetStringName($name);
			
			$surname = trim($_POST["Surname"]);
			$userObj->surname = $userObj->resetStringName($surname);
			
			$userObj->saldo = floatval($_POST["Saldo"]);
			
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
			$userObj->superadmin = 0;
			if($changeLog) {
				$userObj->updateLog($id);
			}
			if($changePass) {
				$userObj->updatePass($id);
			}
			
			$userObj->update($id);
			
			$addressBD = array();
			$addressBD = $userObj->userWebAddress($id);
			
			
			for($i=0;$i<count($addressBD);$i++) {
				$addressObj = new Address();
				
				
				$addressObj->street = trim($_POST["Street-".$addressBD[$i]->ID]);
				$addressObj->idzone = trim($_POST["Zone-".$addressBD[$i]->ID]);
				
				$addressObj->update($addressBD[$i]->ID);
				
			}
			
		
			disconnectdb($connectBD);
			$msg .= "Usuario <em>".$userBD->NAME." ".$userBD->SURNAME."</em> modificado correctamente";
			$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=edit&opt=".$opt."&id=".$id."&msg=".utf8_decode($msg);
			header($location);
		} else {
			disconnectdb($connectBD);
			$msg .= "Error al modificar al usuario <em>".$userBD->NAME." ".$userBD->SURNAME."</em>.";
			$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=edit&opt=".$opt."&id=".$id."&msg=".utf8_decode($msg);
			header($location);
		}
		
	}else {
		disconnectdb($connectBD);
		$msg .= "Error al modificar al usuario <em>".$userBD->NAME." ".$userBD->SURNAME."</em>.";
		$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=edit&opt=".$opt."&id=".$id."&msg=".utf8_decode($msg);
		header($location);
	}
	
?>