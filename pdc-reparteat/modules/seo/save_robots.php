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
		$error = NULL;
		$connectBD = connectdb();
		
		$msg = "";
		
		$Text = trim($_POST["Text"]);
		$Text = addslashes($Text);
		
			$q = "UPDATE ".preBD."configuration SET"; 
				$q .= " TEXT = '" . $Text;
					
			$q .= "' WHERE ID = 7";
					
			checkingQuery($connectBD, $q);
			
			$path = "../../../robots.txt";
			$mode = "w+";
			
			if($fp = fopen($path,$mode)) {
			   fwrite($fp,$Text);
			   fclose($fp);
			   $msg .= "Archivo robots.txt creado correctamente.";
			} else { 
			   $msg = "Ha habido un problema y el archivo sitemap_index.xml no ha sido creado correctamente.";
			}

			disconnectdb($connectBD);
			$location = "Location: ../../index.php?mnu=seo&com=seo&tpl=robots&msg=".utf8_decode($msg);
			header($location);
	
	}
?>