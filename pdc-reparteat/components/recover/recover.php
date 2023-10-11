<?php
	require_once ("../../includes/include.modules.php");
	
	$now = date('Y').date('m').date('d').date('H').date('i').date('s');
	
	if(isset($_GET["action"])) {
		$action = $_GET["action"];		
	} else {
		$action = "insertLog";	
		
		$dateTest = time() - 3600;
		$dateDelete = date('Ymdhis', $dateTest);
		$q = "DELETE FROM ".preBD."users_recover WHERE DATE < " . $dateDelete;

		checkingQuery($connectBD, $q);	
		
	}
	
	$code_error = 0;
	
	switch($action) {
		case "send-mail":
		
			if($_POST && isset($_POST["reMail"])) {
				$reMail = trim($_POST["reMail"]);
				
				$q = "select * from ".preBD."users where Login = '" . $reMail . "'";
				$result = checkingQuery($connectBD, $q);
				
				if($row_reUser = mysqli_fetch_object($result)) {
					$q = "DELETE FROM ".preBD."users_recover WHERE IDUSER = '" . $row_reUser->Login."'";
					checkingQuery($connectBD, $q);	
					
					
					$dateSend = time() + 3600;//añadimos una hora de caducidad	
					$codeLog = sha1($dateSend); 
					$codeSend = substr($codeLog, -10, 10);
					$date = date('Y-m-d H:i:s', $dateSend);
					$q = "INSERT INTO ".preBD."users_recover (`IDUSER`, `CODE`, `DATE`)";
					$q .= " VALUES ('".$row_reUser->Login."', '".$codeSend."', '".$date."')";	
					
					checkingQuery($connectBD, $q);
					
					$recover_id = mysqli_insert_id($connectBD);
					
					$partDate = explode(" ", $date);
					$partHour = explode(":", $partDate[1]);
					
					$body = "<center>";
					$body .= "<div style='display:block;width:700px;'>";
					$body .= "<div style='width:100%;height:100px;'>";
					$body .= "<img src='".DOMAIN."css/images/header_mail.jpg' border='0' width='700' height='100' />";
					$body .= "</div>";
					$body .= "<div style='font-size:12px;margin-bottom:20px;'>";
					$body .= "<br/>";					
					$body .= "El c&oacute;digo para regenerar su contrase&ntilde;a es: <span style='color:#2F81BB;font-weight:bold;'>". $codeSend."</span><br/>";
					$body .= "<br/>";
					$body .= "Tendr&aacute; validez durante la hora siguiente a la solicitud de recuperaci&oacute;n, es decir, hasta las " . $partHour[0] . ":" . $partHour[1] . "h. " ;
					$body .= "Haga click <a href='".DOMAIN."pdc-reparteat/components/recover/recover.php?action=insert-code' style='color:#2F81BB;font-style:italic;'>aqu&iacute;</a> para hacerlo ahora.";
					$body .= "</div>";
					$body .= "<div style='font-size:11px;'>";
					$body .= "<br/>";					
					$body .= "Para resolver cualquier duda, puede contactar con nosotros a trav&eacute;s del correo <a href='mailto:info@ismaelrc.es'>info@ismaelrc.es</a>.";
					$body .= "</div>";
					$body .= "</center>";
					$subject = 'Recuperar contraseña';
					$msg_mail = sendMailAlert($subject, $body, $row_reUser->Login);
				} else {
					$msg = "No existe ning&uacute;n usuario registrado con ese e-mail.";
					$code_error = 1;
				}
			}
		break;
		case "edit-password":
			if($_POST && isset($_POST["reCode"])) {
				$codeUser = trim($_POST["reCode"]);
				$logUser = trim($_POST["reMail"]);
				
				$q = "select IDUSER from ".preBD."users_recover where CODE = '" . $codeUser;
				$q .= "' and IDUSER = '" . $logUser;
				$q .= "' and DATE > " . $now;
				
				$result = checkingQuery($connectBD, $q);
				
				if($row_recover = mysqli_fetch_object($result)) {
					$changePass = 1;
					$msg = "";
				} else {
					$changePass = 0;
					$msg = "Los datos introducidos no son correctos o el c&oacute;digo ha caducado."; 
				}
			}
		break;
		case "change-password":
			if($_POST && isset($_POST["passwordEdit"])) {
				$pass = trim($_POST["passwordEdit"]);
				$passCopy = trim($_POST["passwordCopyEdit"]);
				$idUser = $_POST["idUser"];
				
				$q_info = "select * from ".preBD."users where Login = '" . $idUser."'";
				$result_info = checkingQuery($connectBD, $q_info);
				$userUp = mysqli_fetch_object($result_info);
				
				if($pass == $passCopy) {
					$q = "update ".preBD."users set `Pwd` = '" . sha1($pass);
					$q .= "' where Login = '" . $idUser."'";
					if(!checkingQuery($connectBD, $q)) {
						$msg = "Se ha producido un error en la base de datos. Por favor, vuelva a intentarlo m&aacute;s tarde.";
					} else {
						
						$msg = "La contrase&ntilde;a se ha regenerado correctamente.";
						$body = "<center>";
						$body .= "<div style='display:block;width:700px;'>";
						$body .= "<div style='width:100%;height:100px;'>";
						$body .= "<img src='".DOMAIN."/css/images/header_mail.jpg' border='0' width='700' height='100' />";
						$body .= "</div>";
						$body .= "<br/>";						
						$body .= "<div style='font-size:12px;margin-bottom:20px;'>";
						$body .= "<b>" . stripslashes($userUp->Name) . "</b>, su contrase&ntilde;a ha sido regenerada correctamente.";
						$body .= "</div>";
						$body .= "<div style='font-size:11px;'>";
						$body .= "<br/>";						
						$body .= "Para resolver cualquier duda, puede contactar con nosotros a trav&eacute;s del correo <a href='mailto:info@ismaelrc.es'>info@ismaelrc.es</a>.";
						$body .= "</div>";
						$body .= "</center>";
						$subject = 'Recuperar contraseña';
						$msg_mail = sendMailAlert($subject, $body, $userUp->Login);
					}
				} else {
					$msg = "Las contrase&ntilde;as no coinciden. Deben de ser iguales.";	
					$update_pass = 0;
				}
			}else {
				$update_pass = 0;
				$msg = "";
			}
		break;
	}

	require_once("recover.view.php");
?>