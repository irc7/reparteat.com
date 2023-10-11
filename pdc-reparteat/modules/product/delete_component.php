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

	if (!allowed("privatezone")) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
	if (isset($_GET["id"])) {
		$connectBD = connectdb();
		$id = intval($_GET["id"]);
		$filtergroup = intval($_GET["filtergroup"]);
		$q = "select LOGIN from ".preBDzp."user_web where ID = '".$id."'";
		$result = checkingQuery($connectBD, $q);
		
		if($row = mysqli_fetch_assoc($result)) {
			$q = "DELETE FROM `".preBDzp."user_web` WHERE ID = " . $id;
			checkingQuery($connectBD, $q);
			
			$msg = "Usuario <em>".$row["LOGIN"]."</em> eliminado correctamente.";
		} else {
			$msg = "No existe ningun usuario que coincida en nuestra base de datos.";
		}
	}
	disconnectdb($connectBD);
	$location = "Location: ../../index.php?mnu=privatezone&com=privatezone&tpl=option&opt=userweb&filtergroup=".$filtergroup."&msg=".utf8_decode($msg);
	header($location);
?>