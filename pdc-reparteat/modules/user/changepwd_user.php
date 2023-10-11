<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	require_once "../../includes/classes/Password/class.Password.php";
	require_once "../../includes/classes/User/class.User.php";
	
	$mnu = trim($_POST["mnu"]);
	
	if (!allowed($mnu)) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}	
	
	if ($_POST) {
		
		$user = new User(); 
		
		$edituser = trim($_POST["user"]);
		$pwd = trim($_POST["Pwd"]);
		$newpwd2 = trim($_POST["CPwd"]);
		
		$row = $user->infoUserByLogin($edituser);
		
		$usertype = $row->Type;
		
		if ($pwd == $newpwd2){
			
			$update = $user->updatePwd($row->ID, $pwd);
  			
			if ($edituser == $_SESSION[PDCLOG]['Login']) {
				$msg = "Contraseña del usuario <em>".$edituser."</em> modificada correctamente. Usuario desconectado";
			}else {
				if($update) {
					$msg = "Contraseña del usuario ".$edituser." modificada correctamente.";
				}else {
					$msg = "Se ha producido un error inesperado, si el problema persiste, póngase en contacto con el administrador del sistema.";		
				}
			}
		}else {
			$msg = "Las contraseñas no coinciden.";
		}
	}else {
		$msg = "Se ha producido un error inesperado, si el problema persiste, póngase en contacto con el administrador del sistema.";		
	}
	disconnectdb($connectBD);

	$location = "Location: ../../index.php?mnu=".$mnu."&com=user&tpl=option&msg=".utf8_decode($msg);
	header($location);
?>