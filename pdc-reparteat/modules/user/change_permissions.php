<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}

	if (!allowed("configuration")) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}	
	if ($_POST) {
		
		$usertype = $_POST["user"];
		$permission = $_POST["permission"];
		$typeUser = $_POST["type"];
		$pwd = sha1($_POST["pwd"]);
		
		if ($_POST["action"] != 'on'){
			$action = 0;
		}else {
			$action = 1;
		}
		$q1 = "SELECT * FROM ".preBD."users WHERE Login='".$_SESSION[PDCLOG]['Login']."'";
		$result1 = checkingQuery($connectBD, $q1);
		
		$row1 = mysqli_fetch_array($result1);
		$pwdhash1 = $row1['Pwd'];
		if ($mydegree >= getdegree($usertype)) {
			$msg = "No tiene permisos para realizar esta operación. Usuario desconectado";
		}
		else if ($pwdhash1 == $pwd) {
			
			$q = "UPDATE ".preBD."users_permissions SET ".$permission." = '".$action."' WHERE Id_user='".$usertype."'";
			checkingQuery($connectBD, $q);
			
			if ($usertype == $_SESSION[PDCLOG]['Type']) {
				$msg = "Permisos del usuario ".$typeUser." modificados.  Usuario desconectado";
			}
			else {
				$msg = "Permisos del usuario ".$typeUser." modificados correctamente";
			}
		} else {
			$msg = "Contraseña incorrecta. Usuario desconectado";
		}
		disconnectdb($connectBD);
	}
	$location = "Location: ../../index.php?mnu=configuration&com=user&tpl=option&opt=permission&msg=".utf8_decode($msg);
	header($location);
?>