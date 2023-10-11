<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	header ('Content-type: text/html; charset=utf-8');
	$V_PHP = explode(".", phpversion());
	if($V_PHP[0]>=5){
		date_default_timezone_set("Europe/Paris");
	}
/*	
	require("class/phpmailer/src/exception.php");
	require("class/phpmailer/src/phpmailer.php");
	require("class/phpmailer/src/smtp.php");
	require("class/phpmailer/src/oauth.php");
	require("class/phpmailer/src/pop3.php");
*/	
	require("class/class.phpmailer.php");
	require("class/class.smtp.php");

	
//Al abrir en nueva vantana no me manda nada por GET cuando viiene de send_config
	
	if(isset($_POST["mnu"])) {
		$mnu = trim($_POST["mnu"]);
	}else{
		$mnu = trim($_GET["mnu"]);
	}
	
	if (!allowed($mnu)) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
//	pre($_GET);
//	pre($_POST);
//	die();
	if(isset($_GET["record"]) || isset($_POST["record"])) {
		if(isset($_POST["record"])) {
			$id = intval($_POST["record"]);
		}else{
			$id = intval($_GET["record"]);
		}
	
		if(isset($_GET["action"])) {
			$action = trim($_GET["action"]);
		}elseif(isset($_POST["action"])) {
			$action = trim($_POST["action"]);
		} else {
			$action = "default";
		}
		
		$q = "select * from ".preBD."newsletter where ID = " . $id; 
		$r = checkingQuery($connectBD, $q);
		$newsletter = mysqli_fetch_object($r);
		
//actualizar toda la cola RESULT = INDEXTRAIL y probar		
		$q = "update ".preBD."newsletter_trail set RESULT = " . $newsletter->INDEX_TRAIL; 
		if($action == "start" || $action == "forward") { //activamos los resultados de envio de fallo del servidor
			$q .= ", STATUS = 1";
		}
		$q .= " where IDNEWSLETTER = ". $id;
		$r = checkingQuery($connectBD, $q);
		
		
		$q = "select * from ".preBD."newsletter_mailer where ID = " . $newsletter->IDMAILER;
		$r = checkingQuery($connectBD, $q);
		$mailer = mysqli_fetch_object($r);	
	
	
		if(isset($_POST["MailTime"])){
			$time = intval($_POST["MailTime"]);
		}else{
			$time = intval($_GET["MailTime"]);
		}
		if($time <= 0) {
			$time = intval($mailer->MAILTIME);
		}
		
		if(isset($_POST["MailSends"])) {
			$mailing = intval($_POST["MailSends"]);
		}else{
			$mailing = intval($_GET["MailSends"]);
		}
		
		if($mailing <= 0) {
			$mailing = intval($mailer->MAILSENDS);
		}
	
		$result = $newsletter->INDEX_TRAIL;
		
//Control para saber si se esta enviando o no		
		if($action == "start" || $action == "forward") {
			
			$q = "select count(*) as TOTAL from ".preBD."newsletter_trail where IDNEWSLETTER = " . $id;
			$q.= " and STATUS = 1 and RESULT = " . $result;
			$r = checkingQuery($connectBD, $q);
			$sendT = mysqli_fetch_object($r);
			$totalBlockSend = ceil($sendT->TOTAL / $mailing);
			$_SESSION["newsletter"][$id]["totalBlockSend"] = $totalBlockSend;
			$_SESSION["newsletter"][$id]["lastBlockSend"] = 1;
			$_SESSION["newsletter"][$id]["timeLimit"] = time()+$time;
			$_SESSION["newsletter"][$id]["now"] = time();
			$_SESSION["newsletter"][$id]["indextrail"] = $newsletter->INDEX_TRAIL;
			//Total tiempo estimado para el envio		
			$_SESSION["newsletter"][$id]["estimatedTime"] = $_SESSION["newsletter"][$id]["totalBlockSend"] * ($time + 5); 
			
		//Lo usamos como contador de numero de intentos de envio al igual que el campo RESULT de la tabla de la cola	
			$q = "update ".preBD."newsletter set INDEX_TRAIL = INDEX_TRAIL + 1 where ID = " . $id;
			checkingQuery($connectBD, $q);
			
		} else {
			$_SESSION["newsletter"][$id]["lastBlockSend"]++;		
			$_SESSION["newsletter"][$id]["timeLimit"] = $_SESSION["newsletter"][$id]["timeLimit"] + $time;
		}
		
		
	
	//pre($_SESSION);
	
		$q = "select * from ".preBD."newsletter_trail where IDNEWSLETTER = " . $id;
		$q.= " and STATUS = 1 and RESULT = " . $result;
		$q.= " order by ID asc limit 0, " . $mailing;
		$r = checkingQuery($connectBD, $q);
		
		$totalSendOK = mysqli_num_rows($r);
		
		$mails = array();
		$cont = 0;

		while($row = mysqli_fetch_object($r)) {
			$statisticsCode = '<img src="'.DOMAIN.'/template/modules/statistics/statistics_newsletter_open.php?idn='.intval($row->IDNEWSLETTER).'&subs='.intval($row->IDSUBSCRIPTION).'" height="1" width="1" style="border:none;" />';
		
			$mails[$cont]["object"] = $row;			
			$Subject_send = "=?ISO-8859-1?B?".base64_encode(utf8_decode(stripslashes($row->SUBJECT)))."=?=";
			$body = stripslashes($row->BODY) . $statisticsCode;
			$body = utf8_decode($body);
			if($row->STATUS == 1 && checkingSuscription($id, $row->IDSUBSCRIPTION, $row->ID)) {//checkingSuscription->Comprobamos que el suscriptor este en STATUS = 1 en el momento del envio
				$sendmail = new PHPMailer();
				if(trim($mailer->TYPESEND) == "smtp" || trim($mailer->TYPESEND) == "mandrill") {
					$sendmail->IsMail();
					$sendmail->Host = $mailer->HOST;
					$sendmail->From = $mailer->MAIL;
					$sendmail->Username = $mailer->USER;
					$sendmail->Password = $mailer->PASS;
					
				}else{
					$sendmail->IsSendmail();
					$sendmail->From = $mailer->MAILFROM;
				}
				$sendmail->CharSet = 'UTF-8';
				$sendmail->Port = $mailer->PORT;
				$sendmail->IsHTML(true);
				$sendmail->SMTPAuth = false;
				$sendmail->SMTPDebug = 0;
				$sendmail->SMTPSecure = securitySMTP($mailer->SECURITY);
				$sendmail->FromName = utf8_decode($mailer->NAMEFROM);
				$sendmail->Subject = $Subject_send;
				$sendmail->AltBody = utf8_decode(stripslashes($row->BODYAUX));
				$sendmail->MsgHTML($body);
				$sendmail->SetFrom($mailer->MAIL, utf8_decode($mailer->NAMEFROM));
				$sendmail->AddAddress($row->MAIL);
				
				if(!$sendmail->Send()) {
					$mails[$cont]["color"] = "#c00";
					$q = "select ERROR from ".preBD."subscriptions where ID = ".$row->IDSUBSCRIPTION;
					$rS = checkingQuery($connectBD, $q);
					$errorS = mysqli_fetch_object($rS);
					
					if(strpos($sendmail->ErrorInfo, "Could not authenticate") !== false) {
						//Lo ponemos a 1 para que lo intente de nuevo al reenviarlo
						$qE = "UPDATE `".preBD."newsletter_trail` SET RESULT = RESULT + 1, STATUS = 0 where ID = " . $row->ID;
						checkingQuery($connectBD, $qE);
					}else{
						//Registro de error pq el email no es correcto	
						$typeError = "errorSend";
						$textError = $row->IDSUBSCRIPTION . ".- " . $row->MAIL . " - <span style=\"color:#c00\">" . addslashes($sendmail->ErrorInfo) . "</span>";
						registerError($id, $row->IDSUBSCRIPTION,$row->MAIL, $errorS->ERROR, $typeError, $textError);
						//eliminamos de la cola
						$q_error = "DELETE FROM `".preBD."newsletter_trail` WHERE ID = " . $row->ID;
						checkingQuery($connectBD, $q_error);
					}
				} else {
					$q = "INSERT INTO `".preBD."newsletter_mailsend`(`IDNEWSLETTER`, `IDSUBSCRIPTION`, `MAIL`, `RESULT`, `ERROR`) 
							VALUES 
						('".$id."','".$row->IDSUBSCRIPTION."','".$row->MAIL."','1','')";
					checkingQuery($connectBD, $q);
					$mails[$cont]["color"] = "#028e08";
				//eliminamos de la cola
					$q_error = "DELETE FROM `".preBD."newsletter_trail` WHERE ID = " . $row->ID;
					checkingQuery($connectBD, $q_error);
					
				//Para estadisticas	
					$existSt = mysqli_num_rows(checkingQuery($connectBD, "select ID from ".preBD."statistics_subscription where IDSUBSCRIPTION = " . $row->IDSUBSCRIPTION));
					if($existSt > 0) {
						$q = "UPDATE `".preBD."statistics_subscription` SET `SEND`= SEND + 1, `DATE` = NOW() WHERE IDSUBSCRIPTION = " . $row->IDSUBSCRIPTION;
					} else {
						$q = "INSERT INTO `".preBD."statistics_subscription`(`DATE`, `IDSUBSCRIPTION`, `SEND`, `CONT`) VALUES (NOW(),".$row->IDSUBSCRIPTION.",1,0)";
					}
					checkingQuery($connectBD, $q);
				}
			}
			$cont++;
		}
		
	} else {//cierre del if si esta definido el id
		$msg = "Ha ocurrido un error de conexión al enviar el newsletter, vuelva a intentarlo gracias."; 
		$location = "Location: ../../index.php?mnu=mailing&com=newsletter&tpl=option&msg=".utf8_decode($msg);
		header($location);	
	}

	
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<link rel="shortcut icon" href="../../../favicon.png">
	<link rel="stylesheet" href="../../css/admin.css" type="text/css" />
	<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script> 
	<title>Panel de Control - <?php echo TITLEWEB; ?></title>
	<?php
		if($totalSendOK > 0): 
			$urlRefresh = DOMAIN. "pdc-reparteat/modules/newsletter/send_newsletter.php?mnu=".$mnu."&record=".$id."&MailSends=".$mailing."&MailTime=".$time;
		?>
			<meta http-equiv="refresh" content="<?php echo $time; ?>;url=<?php echo $urlRefresh; ?>">
		<?php endif; ?>
</head>	
<body>
	<center style="margin-top:20px;">
		<table cellpadding="2" cellspacing="2" border="0" width="600" style="background-color:#fff;border:2px solid #222;">
			<tbody>
				<?php if($totalSendOK > 0): ?>
				<tr>
					<td height="50" valign="middle" style="text-align:center;border-bottom:1px solid #ddd;">
						<img src="../../images/alert.png" />
						<span style="color:#c50016;">El cierre de esta ventana interrumpirá el proceso de envío.</span>
					</td>
				</tr>
				<?php endif; ?>
				<tr>
					<td style="border-bottom:1px solid #ddd;">
						<div style="float:left; width:48%">
							<?php 
								$q = "select count(*) as t from ".preBD."newsletter_trail where IDNEWSLETTER = " . $id. " and RESULT = " . $newsletter->INDEX_TRAIL;
								$r = checkingQuery($connectBD, $q);
								$trailBD = mysqli_fetch_object($r);
								$totalTrail = $trailBD->t;
								
 								$q = "select RESULT from ".preBD."newsletter_mailsend where IDNEWSLETTER = " . $id;
								$rTotal = checkingQuery($connectBD, $q);
								
								$totalSend = 0;
								$totalError = 0;
								
								$q = "select count(*) as t from ".preBD."newsletter_trail where IDNEWSLETTER = " . $id. " and STATUS = 0";
								$r = checkingQuery($connectBD, $q);
								$trailErrorBD = mysqli_fetch_object($r);
								
								$total = mysqli_num_rows($rTotal) + $totalTrail + $trailErrorBD->t;
								
								while($row = mysqli_fetch_object($rTotal)) {
									if($row->RESULT != 1) {
										$totalError++;
									}else{
										$totalSend++;
									}								
								}
								echo "Enviados <b>" . $totalSend . "</b> de <b>" . $total ."</b><br/>";
								echo "En cola: <b>" . $totalTrail . "</b><br/>";
								echo "Fallos de servidor: <b>" . $trailErrorBD->t . "</b><br/>";
								echo "Envíos erróneos: <b>" . $totalError . "</b>";
							?>
						</div>
						<?php if($totalSendOK > 0): ?>
							<div style="float:right; width:50%">
								<div style="display:block;clear:both;">
									<div style="float:left;">Tiempo estimado:&nbsp;</div>
									<div style="float:left;font-size:bold;"><?php echo transformInSecond($_SESSION["newsletter"][$id]["estimatedTime"]); ?></div>
								</div>
								<div style="display:block;clear:both;">
									<div style="float:left;">Próximo bloque de envíos:&nbsp;</div>
									<div id="box_cuaentaAtras" style="float:left;font-size:bold;"></div>&nbsp;segundos.
								</div>
								<script>
									var currentsecond=<?php echo $time; ?>;
									var box = document.getElementById("box_cuaentaAtras");
									box.innerHTML = "<?php echo $time; ?>";
									function countredirect(){
										if (parseInt(box.innerHTML)!=0){
											currentsecond-=1;
											box.innerHTML=currentsecond;
										}else{
											
											return;
										}
										setTimeout("countredirect()",1000);
									}
									countredirect();
								</script>
							</div>
						<?php endif; ?>
					</td>
				</tr>
			<?php if($totalSendOK > 0):  
					for($i=0;$i<count($mails);$i++): ?>
					<tr>
						<td style="border-bottom:1px solid #ddd;">
							->&nbsp <span style="color:<?php echo $mails[$i]["color"]; ?>"><?php echo $mails[$i]["object"]->MAIL; ?></span>
						</td>
					</tr>
				<?php endfor; ?>
					<tr>
						<td style="text-align:center"><img src="../../images/loading.gif" /></td>
					</tr>
			<?php else: 
					unset($_SESSION["newsletter"]);
			?>
					<tr>
						<td>
							<img src="../../images/info.png" />
							<span style="color:#028e08;">El proceso de envío ha finalizado correctamente.</span>
							<input type="button" value="Cerrar" onclick="window.close();" style="float:right;" />
						</td>
					</tr>
			<?php endif; ?>	
			<?php disconnectdb($connectBD); ?>
			</tbody>
		</table>
	</center>
</body>
</html>

