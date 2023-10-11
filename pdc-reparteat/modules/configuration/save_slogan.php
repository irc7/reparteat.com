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
	
	if (allowed("configuration")) {	
		if ($_POST) {
			$connectBD = connectdb();
			
			$msg = "";
			
			$Text =  addslashes(trim($_POST["Text"]));
			
			$q = "UPDATE ".preBD."configuration SET"; 
				$q .= " TEXT = '" . $Text;
					
			$q .= "' WHERE ID = 1";
					
			checkingQuery($connectBD, $q);
			
			disconnectdb($connectBD);
			$msg .= "Eslogan modificado correctamente";
			$location = "Location: ../../index.php?mnu=configuration&com=configuration&tpl=slogan&msg=".utf8_decode($msg);
			header($location);

		}
	}else{
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
?>