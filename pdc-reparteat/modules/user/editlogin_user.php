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
		
		$edituser = trim($_POST["user"]);
		$newuser1 = trim($_POST["newuser1"]);
		$newuser2 = trim($_POST["newuser2"]);

		$q1 = "SELECT * FROM ".preBD."users WHERE Login='".$_SESSION[PDCLOG]['Login']."'";
		$result1 = checkingQuery($connectBD, $q1);
		
		$row1 = mysqli_fetch_array($result1);

		$q = "SELECT * FROM ".preBD."users WHERE Login='".$edituser."'";
		$result = checkingQuery($connectBD, $q);
		
		$row = mysqli_fetch_array($result);
		$usertype = $row['Type'];
		if ($mydegree > getdegree($usertype)) {
			$msg = "No tiene permisos para realizar esta operación. Usuario desconectado";
		}
		else if ($newuser1 == $newuser2){
			
			$q="UPDATE ".preBD."users SET Login='".$newuser1."' WHERE Login='".$edituser."'";
			checkingQuery($connectBD, $q);
			
			if ($edituser == $_SESSION[PDCLOG]['Login']) {
				$msg = "Usuario ".$edituser." modificado. Usuario desconectado";
			}
			else {
				$msg = "Usuario ".$edituser." modificado";
			}
		}
		else {
			$msg = "Los e-mails no coinciden";
		}
		disconnectdb($connectBD);
	}
	$location = "Location: ../../index.php?mnu=configuration&com=user&tpl=option&msg=".utf8_decode($msg);
	header($location);
?>