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

	if (!allowed("seo")) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}		
	
	if ($_POST) {
		$connectBD = connectdb();
		$msg = "";
		$Text = addslashes(trim($_POST["title_home"]));
		$Aux = addslashes(trim($_POST["title_home2"]));
		
		
			$q = "UPDATE ".preBD."configuration SET"; 
				$q .= " TEXT = '" . $Text;
				$q .= "', AUXILIARY = '" . $Aux;
			$q .= "' WHERE ID = 3";
					
			checkingQuery($connectBD, $q);
			
			disconnectdb($connectBD);
			$msg .= "Título modificado correctamente";
			$location = "Location: ../../index.php?mnu=seo&com=seo&tpl=titlehome&msg=".utf8_decode($msg);
			header($location);

	}
?>