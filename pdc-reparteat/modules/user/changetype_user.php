<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	$mnu = trim($_POST["mnu"]);
	if (!allowed($mnu)) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}	
	if ($_POST) {
		
		$edituser = trim($_POST["user"]);
		$Type = trim($_POST["Type"]);
		
		$q = "SELECT * FROM ".preBD."users WHERE Login='".$edituser."'";
		$result = checkingQuery($connectBD, $q);
		$row = mysqli_fetch_object($result);
		
		$usertype = $row->Type;
		
		if ($mydegree > getdegree($usertype)) {
			$msg = "No tiene permisos para realizar esta operación. Usuario desconectado";
		}else if (getdegree($usertype) == 0) {
			$msg = "No puede cambiar los permisos del usuario Propietario";			
		}else {
			$q="UPDATE ".preBD."users SET Type='".$Type."' WHERE Login='".$edituser."'";
			checkingQuery($connectBD, $q);
			if ($edituser == $_SESSION[PDCLOG]['Login']) {
				$msg = "El tipo de usuario de <em>".$edituser."</em> se ha modificado correctamente. Usuario desconectado.";
			}else {
				$msg = "El tipo de usuario de <em>".$edituser."</em> se ha modificado correctamente.";
			}
		}
	}else {
		$msg = "Se ha producido un error inesperado, si el problema persiste, póngase en contacto con el administrador del sistema.";		
	}
	disconnectdb($connectBD);
	$location = "Location: ../../index.php?mnu=configuration&com=user&tpl=option&msg=".utf8_decode($msg);
	header($location);
?>