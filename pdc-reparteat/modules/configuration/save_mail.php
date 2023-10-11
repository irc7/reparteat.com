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
			$name = addslashes(trim($_POST["NameFrom"]));
			$mail = trim($_POST["Mail"]);
			$host = trim($_POST['Host']);
			$user = trim($_POST['User']);
			$pass = trim($_POST['Pass']);
			$port = trim($_POST['Port']);
			$security = $_POST['Security'];
			
			require_once("../../includes/class/class.phpmailer.php");
			require_once("../../includes/class/class.smtp.php");
			$sendmail = new PHPMailer();
			$body = utf8_decode("Test de funcionamiento de cuenta de correo.");
			$sendmail->IsSMTP();
			$sendmail->Host = $host;
			$sendmail->From = $mail;
			$sendmail->FromName = utf8_decode('Panel de Control Web');
			$sendmail->Subject = utf8_decode('Test');
			$sendmail->AltBody = '';
			$sendmail->MsgHTML($body);
			//$sendmail->AddAddress($mail, 'Usuario');
			$sendmail->AddAddress("ismaelrc7@gmail.com", 'Usuario');

			/*puerto diferente al 25*/			
			if($port  != 25){
				$sendmail->Port = $port;
			}
			/*tipo de seguridad*/			
			if($security  == 1){
				$sendmail->SMTPSecure = "ssl";
			}elseif($security  == 2) {
				$sendmail->SMTPSecure = "tls";
			}				
			
			$sendmail->SMTPAuth = true;
			$sendmail->Username = $user;
			$sendmail->Password = $pass;
			if(!$sendmail->Send()) {
				$msg = 'Error en la configuración de la cuenta: ' . $sendmail->ErrorInfo . "<br/>";
				$send = 0;
			} else {
				$msg = "Se le ha enviado un correo a esa misma cuenta de correo para comprobar los datos de configuración.<br/>";
				$send = 1;
				$sendmail->ClearAddresses();
				$sendmail->ClearAttachments();
			}
			
			if($send == 1) {
				$text = "#-HOST-#".$host."#-HOST-##-USER-#".$user."#-USER-##-PASS-#".$pass."#-PASS-##-PORT-#".$port."#-PORT-##-SECURITY-#".$security."#-SECURITY-#";
			
				$q = "UPDATE ".preBD."configuration SET"; 
					$q .= " TITLE = '" . $name;
					$q .= "', AUXILIARY = '" . $mail;
					$q .= "', TEXT = '" . $text;
						
				$q .= "' WHERE ID = 10";
						
				checkingQuery($connectBD, $q);
				$msg .= "Cuenta de correo modificada correctamente.";
				
			}else {
				$msg .= "Asegurese que sus datos son correctos.";
			}
			disconnectdb($connectBD);
			
			$location = "Location: ../../index.php?mnu=configuration&com=configuration&tpl=mail&msg=".utf8_decode($msg);
			header($location);
		}
	}else{
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
?>