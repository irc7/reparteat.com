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

	//pre($_POST);die();	
	if (allowed("mailing")) {
		if ($_POST) {
			$msg = "";
			$typeSend = trim($_POST["typeSend"]);
			
			$MailFrom = trim($_POST["MailFrom"]);
			$NameFrom = addslashes(trim($_POST["NameFrom"]));
			$MailSends = intval($_POST["MailSends"]);
			$MailTime = intval($_POST["MailTime"]);
			
			if($typeSend == "smtp" || $typeSend == "mandrill") {
				$Mail = $MailFrom;
				$User = trim($_POST["User"]);
				$Host = trim($_POST["Host"]);
				$Port = trim($_POST["Port"]);
				$Pass = trim($_POST["Pass"]);
				$Confirm_Pass = trim($_POST["Confirm_Pass"]);
				$Security = intval($_POST["Security"]);
			}else{
				$Mail = "";
				$User = "";
				$Host = "";
				$Port = "";
				$Pass = "";
				$Confirm_Pass = "";
				$Security = 0;
			}
			
			if($Pass == $Confirm_Pass && preg_match("/^([a-zA-Z0-9._]+)@([a-zA-Z0-9.-]+).([a-zA-Z]{2,4})/",$MailFrom)){					
				$q = "INSERT INTO `".preBD."newsletter_mailer`(`TYPESEND`, `MAIL`, `MAILFROM`, `NAMEFROM`, `HOST`, `PORT`, `USER`, `PASS`, `MAILSENDS`, `MAILTIME`, `SECURITY`) 
						VALUES 
						('".$typeSend."', '".$Mail."', '".$MailFrom."', '".$NameFrom."', '".$Host."', '".$Port."', '".$User."', '".$Pass."', '".$MailSends."', '".$MailTime."', '".$Security."')";
				if (!checkingQuery($connectBD, $q)) {
					$msg .= 'Error: '.mysqli_error();
					$location = "Location: ../../index.php?mnu=mailing&com=newsletter&tpl=edit&opt=mailer&msg=".utf8_decode($msg);
				} else {
					$id = mysqli_insert_id($connectBD);
					$msg .= "Configuración del correo <em>".$Mail."</em> creada correctamente";
					$location = "Location: ../../index.php?mnu=mailing&com=newsletter&tpl=list&opt=mailer&msg=".utf8_decode($msg);
				}
			}else{
				if($Pass != $Confirm_Pass) {
					$msg = "Las contraseñas no coinciden.";
				}elseif(!preg_match("/^([a-zA-Z0-9._]+)@([a-zA-Z0-9.-]+).([a-zA-Z]{2,4})/",$MailFrom)) {
					$msg = "Debe introducir un correo electrónico válido.";
				
				}
				$location = "Location: ../../index.php?mnu=mailing&com=newsletter&tpl=create&opt=mailer&record=".$id."&msg=".utf8_decode($msg);
			}
			disconnectdb($connectBD);
			header($location);
		} else {
			$msg .= "Se ha producido un error, vuelva a intentarlo, gracias.";
			$location = "Location: ../../index.php?mnu=mailing&com=newsletter&tpl=edit&opt=mailer&msg=".utf8_decode($msg);
			header($location);
		}
	} else {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
	
?>