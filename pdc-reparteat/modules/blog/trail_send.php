<?php
	session_start();
	header ('Content-type: text/html; charset=utf-8');
	$V_PHP = explode(".", phpversion());
	if($V_PHP[0]>=5){
		date_default_timezone_set("Europe/Paris");
	}
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	require("../../includes/class/class.phpmailer.php");
	require("../../includes/class/class.smtp.php");
	
	if (!allowed("blog")) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
	if(isset($_GET["action"])) {
		$action = trim($_GET["action"]);
	} else {
		$action = "default";
	}
	if(isset($_GET["id"]) && $action != "none") {
		$id = abs(intval($_GET["id"]));
	
		
		
		if(isset($_GET["ind"])) {
			$ind = abs(intval($_GET["ind"]));
		} else {
			if($action == "start") {
				$ind = 0;
			}elseif($action == "forward"){
				$i = 0;
				$q = "select * from ".preBD."blog_subscriptors_trail where IDPOST = " . $id . " order by ID asc";
				$r = checkingQuery($connectBD, $q);
				while($col = mysqli_fetch_object($r)) {
					if($col->RESULT == 0) {
						$ind = $i;
						break;
					} else {
						$i++;
					}
				}
			}
		}
		
		$time = 60;//tiempo de envio de cada bloque
		$mailing = 10;//numero de correos por bloque

//Control para saber si se esta enviando o no		
		if($action == "start" || $action == "forward") {
			$q = "select count(*) as TOTAL from ".preBD."blog_subscriptors_trail  where IDPOST = " . $id;
			$q.= " and STATUS = 1 and RESULT = 0";
			$result = checkingQuery($connectBD, $q);
			$sendT = mysqli_fetch_object($result);
			$totalBlockSend = ceil($sendT->TOTAL / $mailing);
			$_SESSION["blog"][$id]["totalBlockSend"] = $totalBlockSend;
			$_SESSION["blog"][$id]["lastBlockSend"] = 1;
			$_SESSION["blog"][$id]["timeLimit"] = time()+$time;
			$_SESSION["blog"][$id]["now"] = time();
			//Total tiempo estimado para el envio		
			$_SESSION["blog"][$id]["estimatedTime"] = $_SESSION["blog"][$id]["totalBlockSend"] * ($time + 5); 
		} else {
			$_SESSION["blog"][$id]["lastBlockSend"]++;		
			$_SESSION["blog"][$id]["timeLimit"] = $_SESSION["blog"][$id]["timeLimit"] + $time;
		}

		$q = "select * from ".preBD."blog_subscriptors_trail where IDPOST = " . $id;
		$q.= " and STATUS = 1";
		$q.= " order by ID asc";
		$q.= " limit " . $ind . ", " . $mailing;
		
		$result = checkingQuery($connectBD, $q);
		$totalSendOK = mysqli_num_rows($result);

		$ind = $ind + $mailing;
				
		$mails = array();
		$cont = 0;
		$error2 = "<span style=\"color:#B4505A;font-size:14px;\">ERRORES DE ENVÍOS</span><br/>";
		while($row = mysqli_fetch_object($result)) {
			$mails[$cont]["object"] = $row;			
			$Subject_send = utf8_decode(stripslashes($row->SUBJECT));
			$bodyAux = "Se le esta intentando enviar información que no puede ser interpretada, por favor abra este correo en un lector que soporte HTML";
			$body = stripslashes($row->BODY);
			$body = utf8_decode($body);
			if($row->STATUS == 1 && $row->RESULT == 0) {
				$sendmail = new PHPMailer();
				$sendmail->IsSMTP();
				$sendmail->Host = MAILHOST;
				$sendmail->From = MAILSEND;
				$sendmail->SMTPAuth = true;
				$sendmail->Username = USERHOST;
				$sendmail->Password = PASSHOST;
				$sendmail->FromName = utf8_decode(NAMESEND);
				$sendmail->Subject = $Subject_send;
				$sendmail->AltBody = utf8_decode($bodyAux);
				$sendmail->MsgHTML($body);
				$sendmail->AddAddress($row->MAIL);
				
				if(!$sendmail->Send()) {
					$errorTrail = $row->IDSUBSCRIPTION . ".- " . $row->MAIL . " - <span style=\"color:#c00\">" . addslashes($sendmail->ErrorInfo) . "</span>";
					$mails[$cont]["color"] = "#c00";
					$q_error = "update ".preBD."blog_subscriptors_trail set RESULT = 2, INFOERROR = '" . $errorTrail . "' where ID = ". $row->ID;
					checkingQuery($connectBD, $q_error);
					
					$mails[$cont]["color"] = "#c00";
				} else {
					$q_ok = "update ".preBD."blog_subscriptors_trail set RESULT = 1 where ID = ". $row->ID;
					checkingQuery($connectBD, $q_ok);				
					
					$mails[$cont]["color"] = "#028e08";
				}
			}
			$cont++;
		}
		if($action == "start") { 
		//	pongo que el articulo ya ha generado la cola
			$qUp = "UPDATE `".preBD."articles` SET `SEND` = 1 WHERE ID = " . $id;
			checkingQuery($connectBD, $qUp);
		}
	}elseif(isset($_GET["id"]) && $action == "none"){//Si la alerta ya se ha enviado
		$totalSendOK = 0;
		$msg = "La alerta ya ha sido o esta siendo enviada en otra ventana.";
	} else {//cierre del if si esta definido el id
		
		$totalSendOK = 0;
		$msg = "Ha ocurrido un error de conexión en el proceso de envio, vuelva a intentarlo, gracias."; 
		
	}

	
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<link rel="shortcut icon" href="../../../favicon.ico">
	<link rel="stylesheet" href="../../css/admin.css" type="text/css" />
	<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script> 
	<title>Panel de Control - <?php echo TITLEWEB; ?></title>
	<?php
		//pre($totalSendOK);
		if($totalSendOK > 0): ?>
			<meta http-equiv="refresh" content="<?php echo $time; ?>;url=<?php echo DOMAIN; ?>pdc-reparteat/modules/blog/trail_send.php?id=<?php echo $id; ?>&ind=<?php echo $ind; ?>">
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
								$q = "select RESULT, STATUS from ".preBD."blog_subscriptors_trail where IDPOST = " . $id;
								$result = checkingQuery($connectBD, $q);
								$total = mysqli_num_rows($result);
								$totalSend = 0;
								$totalTrail = 0;
								$totalError = 0;
								while($row = mysqli_fetch_object($result)) {
									if($row->STATUS == 1) {
										if($row->RESULT == 0) {
											$totalTrail++;
										}elseif($row->RESULT == 1){
											$totalSend++;
										}elseif($row->RESULT == 2){
											$totalError++;
										}
									}else{
										$totalError++;
									}
								}
								echo "Enviados <b>" . $totalSend . "</b> de <b>" . $total ."</b><br/>";
								echo "En cola: <b>" . $totalTrail . "</b><br/>";
								echo "Envíos erroneos: <b>" . $totalError . "</b>";
							?>
						</div>
						<?php if($totalSendOK > 0): ?>
							<div style="float:right; width:50%">
								<div style="display:block;clear:both;">
									<div style="float:left;">Tiempo estimado:&nbsp;</div>
									<div style="float:left;font-size:bold;"><?php echo transformInSecond($_SESSION["blog"][$id]["estimatedTime"]); ?></div>
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
					if($action != "none"):
						$q = "select * from ".preBD."blog_subscriptors_trail where RESULT = 2";
						$r = checkingQuery($connectBD, $q);
						unset($_SESSION["blog"]);
						while($fail = mysqli_fetch_object($r)):
			?>
						<tr>
							<td>
								->&nbsp <span style="color:#c00;"><em><?php echo $fail->INFOERROR; ?></em></span>
							</td>
						</tr>
			<?php 		endwhile; 
							$q = "delete from ".preBD."blog_subscriptors_trail where IDPOST = " . $id;
							checkingQuery($connectBD, $q);
			?>
						<tr>
							<td>
								<img src="../../images/info.png" />
								<span style="color:#028e08;">El proceso de envío ha finalizado correctamente.</span>
								<input type="button" value="Cerrar" onclick="window.close();" style="float:right;" />
							</td>
						</tr>
			<?php	else: ?>
						<tr>
							<td>
								<img src="../../images/info.png" />
								<span style="color:#028e08;"><?php echo $msg; ?></span>
								<input type="button" value="Cerrar" onclick="window.close();" style="float:right;" />
							</td>
						</tr>
			<?php 	endif; ?>	
			<?php endif; ?>	
			<?php disconnectdb($connectBD); ?>
			</tbody>
		</table>
	</center>
</body>
</html>

