<?php  
	
	$directoryComA = explode("/", $_SERVER["PHP_SELF"]);
	$totaldirectory = count($directoryComA)-1;
	$scriptPHP = $directoryComA[$totaldirectory];
	
	$urlFunctions = "includes/";
	//nota: el nombre del archivo funciones del nombre tiene que ser igual que el del archivo de las funciones
	if($scriptPHP != "index.php" && $scriptPHP != "login.php" && $scriptPHP != "logout.php" && $scriptPHP != "recover.php" && $scriptPHP != "insertar-codigo.php" && $scriptPHP != "actualizar-pwd.php") {
		$directory_mod = $directoryComA[$totaldirectory-1];
	//funciones en carpeta modules	
		require_once("../../includes/functions/global.functions.php");
		$urlModFunction = "../../includes/functions/".$directory_mod.".functions.php";
		if(file_exists($urlModFunction)) {
			require_once($urlModFunction);
		}
		
	} else {
		require_once("includes/functions/global.functions.php");
		$urlModFunction = "includes/functions/".$com.".functions.php";
		if(file_exists($urlModFunction)) {
			require_once($urlModFunction);
		}
		
	}
?>