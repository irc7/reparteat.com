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
		
	if (!allowed("configuration")) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}	
	
		if ($_POST) {
		
			$msg = "";
			$host = trim($_POST['Host']);
			$user = trim($_POST['User']);
			$pass = trim($_POST['Pass']);
			$directory = trim($_POST['Directory']);
			
	# conexión con el servidor FTP
			$error = 1;
			if($idFTP = ftp_connect($host, 21)) {
				$msg .= "Servidor encontrado. ";	
				if(ftp_login($idFTP, $user, $pass)){
					$msg .= "El login y la password han sido aceptados.<br>";
					$error = 0;
				}else{
					$msg .= "Error en login o password.<br>";
				}
	#desconexión
				ftp_quit($idFTP);
			}else{
				$msg .= "Servidor desconocido.<br/>";
			}
			
			if($error == 0) {
				$text = "#-HOST-#".$host."#-HOST-##-USER-#".$user."#-USER-##-PASS-#".$pass."#-PASS-#";
				$q = "UPDATE ".preBD."configuration SET"; 
				$q .= " TEXT = '" . $text;
				$q .= "', AUXILIARY = '" . $directory;
				$q .= "' WHERE ID = 11";
				checkingQuery($connectBD, $q);
				$msg .= "Configuración FTP guardada correctamente";
				
			} else {
				$msg .= "No se ha podido establecer conexión, error al guardar la configuración FTP.";
			}
			disconnectdb($connectBD);
			$location = "Location: ../../index.php?mnu=configuration&com=configuration&tpl=ftp&error=".$error."&msg=".utf8_decode($msg);
			header($location);
		}
	
?>