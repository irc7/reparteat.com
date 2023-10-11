<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	
	if (allowed("configuration")) {	
		if (isset($_POST["codeColor"]) && trim($_POST["codeColor"]) != "") {
			$codeColor = "#".trim($_POST["codeColor"]);
			connectdb();
			$q = "UPDATE `".preBD."configuration` SET `AUXILIARY`='".$codeColor."' WHERE ID = 16";
			
			if(!checkingQuery($connectBD, $q)) {
				$msg = "Se ha producido un error, por favor, vuelva a intentarlo, si el problema persiste consultelo con su webmaster.";		
			}else{
				$msg = "Color corporativo guardado correctamente.";		
			}
			disconnectdb($connectBD);
		}
		$location = "Location: ../../index.php?mnu=configuration&com=configuration&tpl=color&msg=".utf8_decode($msg);
		header($location);
	}else{
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
?>