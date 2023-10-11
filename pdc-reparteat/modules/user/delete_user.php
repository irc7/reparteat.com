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
		$deleteuser = trim($_POST["user"]);

		$q1 = "SELECT * FROM ".preBD."users WHERE Login='".$_SESSION[PDCLOG]['Login']."'";
		$result1 = checkingQuery($connectBD, $q1);
		
		$row1 = mysqli_fetch_array($result1);
		$pwdhash1 = $row1['Pwd'];
		
		$q2 = "SELECT Type FROM ".preBD."users WHERE Login='".$deleteuser."'";
		$result2 = checkingQuery($connectBD, $q2);
		
		$row2 = mysqli_fetch_array($result2);
		$usertype = $row2['Type'];
		if ($mydegree > getdegree($usertype)) {
			$msg = "No tiene permisos para realizar esta operación.";
		}
		else if (getdegree($usertype) == 0) {
			$msg = "No puede eliminar el usuario Propietario";			
		}
		else {
			$q = "DELETE FROM ".preBD."users WHERE Login='".$deleteuser."'";
			checkingQuery($connectBD, $q);
			
			if ($row2['Login'] == $_SESSION[PDCLOG]['Login']) {
				$msg = "Usuario ".$edituser." eliminado.";
			}
			else {
			$msg = "Usuario ".$deleteuser." eliminado";
			}
		}
		disconnectdb($connectBD);
	}
	$location = "Location: ../../index.php?mnu=".$mnu."&com=user&tpl=option&msg=".utf8_decode($msg);
	header($location);
?>