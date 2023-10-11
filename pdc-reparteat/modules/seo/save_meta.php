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
	
	//pre($_POST);die();
	
	if ($_POST) {
		
		$msg = "";
		$Keywords = addslashes(trim($_POST["keywords"]));
		$Description = addslashes(trim($_POST["description"]));
		$Verification = addslashes(trim($_POST["verification"]));
		$Other = addslashes(trim($_POST["Other"]));
		
		$Keywords = str_replace("'", "\"", $Keywords);
		$Description = str_replace("'", "\"", $Description);
		$Verification = str_replace("'", "\"", $Verification);
		$Other = str_replace("'", "\"", $Other);
		
		//pre($_POST);
		//die();
		
			$q = "UPDATE ".preBD."configuration SET"; 
			$q .= " TEXT = '" . $Keywords;
			$q .= "' WHERE ID = 4";
					
			checkingQuery($connectBD, $q);
			
//////////////////			
			$q = "UPDATE ".preBD."configuration SET"; 
			$q .= " TEXT = '" . $Description;
			$q .= "' WHERE ID = 5";
					
			checkingQuery($connectBD, $q);
///////////////			
			$q = "UPDATE ".preBD."configuration SET"; 
			$q .= " TEXT = '" . $Other;
			$q .= "' WHERE ID = 8";
					
			checkingQuery($connectBD, $q);
			

///////////////				
			$q = "UPDATE ".preBD."configuration SET"; 
			$q .= " TEXT = '" . $Verification;
					
			$q .= "' WHERE ID = 6";
					
			checkingQuery($connectBD, $q);
			
			
		disconnectdb($connectBD);
		$msg .= "Meta-etiquetas modificadas correctamente";
		$location = "Location: ../../index.php?mnu=seo&com=seo&tpl=meta&msg=".utf8_decode($msg);
		header($location);

	}
?>