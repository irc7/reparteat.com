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
		
	
	if (!allowed("statistics")) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}	
	if ($_POST) {
		$error = NULL;
		
		$msg = "";
		
		$idArticle =  trim($_POST["articleID"]);
		$idArticle = intval($idArticle);
		$Title =  addslashes(trim($_POST["Title"]));
		$Text =  addslashes(trim($_POST["Text"]));
		
		//CONSULTAS
		if ($error == NULL) {
			
			$q = "UPDATE ".preBD."configuration SET"; 
				$q .= " TITLE = '" . $Title;
				$q .= "', TEXT = '" . $Text;
				$q .= "', VALUE = '" . $idArticle;
			$q .= "' WHERE ID = 2";
			
			checkingQuery($connectBD, $q);
			
			disconnectdb($connectBD);
			$msg .= "Código de Google Analytics modificado correctamente.";
			$location = "Location: ../../index.php?mnu=statistics&com=statistics&tpl=google&msg=".utf8_decode($msg);
			header($location);
			
		}
		else {
			echo $error;
		}

	}
?>