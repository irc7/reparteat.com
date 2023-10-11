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
	
	$mnu = $_POST["mnu"];
	
	if (!allowed($mnu)) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}

	
	if($_POST): //se cerrará despues del body

		$idN = abs(intval($_POST["record"]));
		$typeSend = trim($_POST["typeSend"]);
		$mailerSend = abs(intval($_POST["mailerSend"])); 
		$q = "select * from ".preBD."newsletter_mailer where ID = " . $mailerSend;
		$r = checkingQuery($connectBD, $q);
		$mailerBD = mysqli_fetch_object($r);	
		
		$q = "select * from ".preBD."newsletter where ID = " . $idN;
		$rN = checkingQuery($connectBD, $q);
		$newsletter = mysqli_fetch_object($rN);
		
		
		//Suscriptores
		$group = array();
		$group = $_POST["groups"];
		
		
		
		if($typeSend == "smtp" || $typeSend == "mandrill") {
		//GRUPOS 
			$count_OK = 0;
			$mails = array();
			$cont = 0;
			if(count($group) > 0){
				$q_groups = "SELECT * FROM ".preBD."groups_subscriptions";
				for($i=0;$i<count($group);$i++) {
					if($i == 0) {
						$q_groups .= " where (ID = " . $group[$i];
					}else{
						$q_groups .= " or ID = " . $group[$i];
					}
				}
				$q_groups.= ")";
				checkingQuery($connectBD, $q_groups);
				$result_groups = checkingQuery($connectBD, $q_groups);
				while($row_groups=mysqli_fetch_object($result_groups)) {
					$q = "SELECT * FROM ".preBD."subscriptions where STATUS = 1 and IDGROUP = ".$row_groups->ID." order by ID asc";
					$result = checkingQuery($connectBD, $q);
					while($row = mysqli_fetch_object($result)) {
						
						if(preg_match("/^([a-zA-Z0-9._]+)@([a-zA-Z0-9.-]+).([a-zA-Z]{2,4})/",$row->MAIL)) {
							if($row->ERROR < ERRORMAIL) {
								$mails[$cont]["mail"] = $row->MAIL;
								$mails[$cont]["id"] = $row->ID;
								$mails[$cont]["statistics"] = "?&idn=".$idN."&subs=".$row->ID;
								$mails[$cont]["NOPOP"] = DOMAIN."suscripcion-boletin-digital/baja/".$row->ID."-".sha1($row->MAIL);
								//link view online	
								$linkViewOnline = "<div style='padding-bottom:10px;width:100%;display:block;background-color:#fff;font-size:11px;'>
														<center>
															<div class='separatorNews'>&nbsp;</div>
															<font face='Arial' style='color:#333;'>Si no visualiza correctamente este boletín digital, haga click </font>
															<a href='".DOMAIN."template/modules/newsletter/newsletter.online.php?idn=".$idN."&subs=".$row->ID."' style='color:#666666;'>
																<font face='Arial' style='color:#666666;'>aqu&iacute;</font>
															</a>
														</center>
													</div>\r\n";
								$Unsuscriber = '<center>
													<table cellpadding="0" cellspacing="0" border="0" width="750"><tbody>
														<tr><td>
															<div class="separatorNews">&nbsp;</div>
															<div id="text_footer_newsletter" style="font-size:11px;font-family: Arial;text-align:center !important;color:#666666;">
																Haga click <a href="#NOPOP#" style="color:#666666;font-size:11px;">aquí</a> si desea cancelar la suscripción del correo <em>'.$row->MAIL.'</em> a este boletín.
															</div>
														</td></tr></tbody></table>
												</center>';
								$mails[$cont]["header_newsletter"] = $linkViewOnline;
								$mails[$cont]["footer_newsletter"] = $Unsuscriber;
								$cont++;
							} else{
							//Registro de error pq el suscriptor esta anulado	
								$typeError = "errorSend";
								$textError = $row->ID . ".- " . $row->MAIL . " - <span style=\"color:#B4505A\">(Error: Control de errores sobrepasado.)</span><br />";
								registerError($idN, $row->ID, $row->MAIL, $row->ERROR, $typeError, $textError);
							}
						} else {
							//Registro de error pq el email no es correcto	
							$typeError = "errorAddress";
							$textError = $row->ID . ".- " . $row->MAIL . " - <span style=\"color:#B4505A\">(Error: Direcci&oacute;n de correo no v&aacute;lida.)</span><br />";
							registerError($idN, $row->ID,$row->MAIL, $row->ERROR, $typeError, $textError);
						}
					}
				}
				$newsletterAux = "Se le esta intentando enviar información que no puede ser interpretada, por favor abra este correo en un lector que soporte HTML";
				for($i=0;$i<count($mails);$i++) {
					$q = "select count(*) as total from ".preBD."newsletter_trail where IDSUBSCRIPTION = " . $mails[$i]["id"]." and IDNEWSLETTER = " . $idN;
					$r = checkingQuery($connectBD, $q);
					$aux = mysqli_fetch_object($r);
					if($aux->total == 0) {
						
						$body = $mails[$i]["header_newsletter"] . $newsletter->HTML . $mails[$i]["footer_newsletter"];
						$body = str_replace("#NOPOP#", $mails[$i]["NOPOP"], $body);
						$body = str_replace("#STATISTICS_PARAM#", $mails[$i]["statistics"], $body);					
						$body = str_replace("#MAILER#", $mailerBD->MAIL, $body);
						
						$q = "INSERT INTO `".preBD."newsletter_trail`
								(`IDSUBSCRIPTION`, `MAIL`, `SUBJECT`, `BODY`, `BODYAUX`, `DATE`, `IDNEWSLETTER`, `IDMAILER`, `INFOERROR`, `RESULT`, `STATUS`) 
								VALUES
								('".$mails[$i]["id"]."', '".$mails[$i]["mail"]."', '".$newsletter->SUBJECT."', '".addslashes($body)."', '".addslashes($newsletterAux)."', NOW(), '".$idN."', '".$mailerSend."','','0', '1')";
						
						checkingQuery($connectBD, $q);
						
					}
				}
			}
			$q = "select * from ".preBD."newsletter_trail where IDNEWSLETTER = " . $idN. " and STATUS = 1 and RESULT = 0";
			$rnt = checkingQuery($connectBD, $q);
			$totalMailSend = mysqli_num_rows($rnt);
			
		}elseif($typeSend == "code") { 

		//GRUPOS 
			$count_OK = 0;
			$mails = array();
			$cont = 0;
			if(count($group) > 0){
				$q_groups = "SELECT * FROM ".preBD."groups_subscriptions";
				for($i=0;$i<count($group);$i++) {
					if($i == 0) {
						$q_groups .= " where (ID = " . $group[$i];
					}else{
						$q_groups .= " or ID = " . $group[$i];
					}
				}
				$q_groups.= ")";
				checkingQuery($connectBD, $q_groups);
				$result_groups = checkingQuery($connectBD, $q_groups);
				$mails = array();
				while($row_groups=mysqli_fetch_object($result_groups)) {
					$q = "SELECT * FROM ".preBD."subscriptions where STATUS = 1 and IDGROUP = ".$row_groups->ID." order by ID asc";
					$result = checkingQuery($connectBD, $q);
					while($row = mysqli_fetch_object($result)) {
					
						if(preg_match("/^([a-zA-Z0-9._]+)@([a-zA-Z0-9.-]+).([a-zA-Z]{2,4})/",$row->MAIL)) {
							if($row->ERROR < ERRORMAIL) {
							//registramos error en el correo
								$mails[$cont]["mail"] = $row->MAIL;
								$q = "INSERT INTO `".preBD."newsletter_mailsend`(`IDNEWSLETTER`, `IDSUBSCRIPTION`, `MAIL`, `RESULT`, `ERROR`) 
										VALUES 
									('".$idN."','".$row->ID."','".$row->MAIL."',1,'')";
								checkingQuery($connectBD, $q);	
								$cont++;
							}else {
								//Registro de error pq el suscriptor esta anulado	
								$typeError = "errorSend";
								$textError = $row->ID . ".- " . $row->MAIL . " - <span style=\"color:#B4505A\">(Error: Control de errores sobrepasado.)</span><br />";
								registerError($idN, $row->ID, $row->MAIL, $row->ERROR, $typeError, $textError);
							}
						
						}else {
							//Registro de error pq el email no es correcto	
								$typeError = "errorAddress";
								$textError = $row->ID . ".- " . $row->MAIL . " - <span style=\"color:#B4505A\">(Error: Direcci&oacute;n de correo inv&aacute;lida.)</span><br />";
								registerError($idN, $row->ID, $row->MAIL, $row->ERROR, $typeError, $textError);
							
						}
					}
				}
				$totalMailSend = count($mails);
			}
		
		}
		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<link rel="shortcut icon" href="../../../favicon.png">
	<link rel="stylesheet" href="../../css/admin.css" type="text/css" />
	<?php require_once("../../css/styles.php"); ?>
	
	<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
	<script src="http://code.jquery.com/jquery-1.9.0.js"></script>
	<script src="http://code.jquery.com/jquery-migrate-1.0.0.js"></script>

	<title>Confirmación de envio - <?php echo TITLEWEB; ?></title>
	<script type="text/javascript">
		var $sn = jQuery.noConflict();
		$sn(document).ready(function(){
			
		}); 
	</script>
</head>	
<body>	
	<center>
		<center  style="background-color:#ededed;padding:10px 0px;">
	<div id="boxMenutype">
				<ul class="list-record-fields">
					<li class="complete" style="padding-bottom:10px;border-bottom:1px solid #fff;">
						<p class="titlePage">Confirmación de envio</p>					
					</li>
<?php				if($typeSend == "smtp" || $typeSend == "mandrill") {
						$q = "update ".preBD."newsletter set IDMAILER = " . $mailerSend. " where ID = " . $idN;
						checkingQuery($connectBD, $q);
						$q = "update ".preBD."newsletter_trail set IDMAILER = " . $mailerSend. " where IDNEWSLETTER= " . $idN;
						checkingQuery($connectBD, $q);
						$q = "select * from ".preBD."newsletter_mailer where ID = " .$mailerSend;
						$r = checkingQuery($connectBD, $q);
						$mailerBD = mysqli_fetch_object($r);
						
?>
					<form action="send_newsletter.php" name="mainform" id="mainform" method="get" target="_blank">
						<input type="hidden" name="mnu" value="<?php echo $mnu; ?>" />
						<input type="hidden" name="action" value="start" />
						<input type="hidden" name="record" value="<?php echo $newsletter->ID; ?>" />
						<li class="complete">
							<p class="titlePage">Remitente:</p>	
						</li>
						<li class="complete">
							Nombre: <span style="font-weight:normal;"><?php echo $mailerBD->NAMEFROM; ?></span>
						</li>
						<li class="complete">
							Email: <span style="font-weight:normal;"><?php echo $mailerBD->MAILFROM; ?></span>
						</li>
						<li class="complete">
							<label for="MailSends" style="padding-top:5px;">Enviar&nbsp;</label>
							<input type="number" id="MailSendsAux" name="MailSendsAux" title="Número de correos" value="<?php echo $mailerBD->MAILSENDS; ?>" maxlength="3" style="width:50px;text-align:right;" disabled="disabled" />
							<input type="hidden" id="MailSends" name="MailSends" value="<?php echo $mailerBD->MAILSENDS; ?>" />
							<label for="MailTime" style="padding-top:5px;">&nbsp;correos cada&nbsp;</label>
							<input type="number" id="MailTimeAux" name="MailTimeAux" title="Tiempo por bloque" value="<?php echo $mailerBD->MAILTIME; ?>" maxlength="4" style="width:50px;text-align:right;" disabled="disabled" />
							<input type="hidden" id="MailTime" name="MailTime" value="<?php echo $mailerBD->MAILTIME; ?>" />
							<label for="MailTime" style="padding-top:5px;">&nbsp;segundos</label>
						</li>
						<li class="complete">
							<p class="titlePage">Listado de correos:  <em>(<?php echo $totalMailSend; ?> envíos seleccionados)</em></p>	
						</li>
						<li class="complete" style="max-height:350px;overflow-y:scroll;">
<?php 					while($row = mysqli_fetch_object($rnt)): ?>
								<?php echo $row->MAIL; ?>;<br/>
<?php					endwhile; ?>
						</li>
						<li class="complete" style="border-top:1px solid #fff;padding-top:10px;">
							<div id="menuArticle" style="float:left">
								<a href="../../index.php?mnu=mailing&com=newsletter&tpl=create&action=deleteTemp&record=<?php echo $newsletter->ID; ?>" target="_self">Volver</a>
							</div> 
							<div id="menuFree" style="float:right;">
								Enviar
							</div>
						</li>
						<script type="text/javascript">
							var $send = jQuery.noConflict();
							$send(document).ready(function(){
								$send("#menuFree").click(function(){
									var sends = $send("#MailSends").val();
									var time = $send("#MailTime").val();
									$send(location).attr("href", "<?php echo DOMAIN; ?>pdc-reparteat/index.php?mnu=<?php echo $mnu; ?>&com=newsletter&tpl=option&record=<?php echo $newsletter->ID; ?>&action=start&MailSends="+sends+"&MailTime="+time);
								});
							});
						</script>
					</form>	
<?php				}elseif($typeSend == "code") { ?>
						
						<li class="complete">
							<p class="titlePage">Código HTML del newsletter</p>	
						</li>
						<li class="complete">
							<p style="clear: both;display: block;font-style: italic;font-weight: bold;margin-top: 10px;text-align: justify;width: 100%;">Pulse Ctrl+A para seleccionar todo el texto.</p>
							<textarea id="source_newsletter" style="width:100%;height:600px;margin-top: 10px;" readonly><?php echo htmlspecialchars(str_replace("#STATISTICS_PARAM#", "", $newsletter->HTML)); ?></textarea>
						</li>
						<li class="complete">
							<p class="titlePage">Listado de correos seleccionado</p>	
						</li>
						<li class="complete">
							<textarea id="source_newsletter" style="width:100%;height:600px;margin-top: 10px;line-height:10px;">
<?php 							for($i=0;$i<count($mails);$i++){
									echo $mails[$i]["mail"].";\n";
								} 
?>							</textarea>
						</li>
						<li class="complete" style="border-top:1px solid #fff;padding-top:10px;">
							<a href="../../index.php?mnu=mailing&com=newsletter&tpl=option" style="float:right">
								<div id="menuFree">
									Finalizar
								</div>
							</a>
						</li>
<?php 				} ?>
				
				
					
			</ul>
		</div>
	</center>
	<div id="content_newsletter_view" style="width:100%;border-top:10px solid #000000;padding-top:20px;padding-bottom:20px;">
		<center><?php echo stripslashes($bodyBD); ?></center> 
	</div>
	<style type="text/css">
			#boxMenutype {
				display:block;
				width:700px;
				font-family:Arial, sans-serif;
			}
			#boxMenutype * {
				font-family: Arial, sans-serif;
			}
			#boxMenutype .titlePage {
				font-size:16px;
				font-weight:bold;
				color:#000000;
			}
			#boxMenutype ul {
				list-style:none;
				
			}
			#boxMenutype li {
				font-size: 13px;
				font-weight:bold;
				text-align:left;
				margin-bottom:10px;
			}
			#boxMenutype li select {
				width:160px;
			}
			#boxMenutype li.type-send-level2 {
				margin-left:0px;
				display:none;
				width: 97%;
				background-color:#ffffff;
				padding:2%;
			}
			#boxMenutype li label {
				width:auto;
				display:block;
				float:left;
			}
			#boxMenutype li.type-send-level2 label{
				width:170px;
				display:block;
				float:left;
			}
			#boxMenutype ul li div {
				border-radius: 4px;
				-moz-border-radius: 4px;
				-webkit-border-radius: 4px;
				background-color: #000000;
				color:#fff;
				cursor:pointer;
				width:55px;
				height:25px;
				line-height:25px;
				text-align:center;
			}
			#boxMenutype ul li div a{
				color:#fff;
				width:55px;
				height:25px;
				line-height:25px;
				text-align:center;
			}
			#menuArticle {
				opacity:1;
				filter:alpha(opacity=100);
			}
			#boxMenutype ul li div:hover {
				opacity:1;
				filter:alpha(opacity=100);
				background-color:#666666;
				color:#fff;
			}
			#boxMenutype ul li input[type="button"] {
				border-radius: 4px;
				-moz-border-radius: 4px;
				-webkit-border-radius: 4px;
				background-color: #000000;
				color:#fff;
				cursor:pointer;
				width:auto;
				height:25px;
				float:right;
				line-height:25px;
				text-align:center;
				border:none;
				font-weight: bold;
				-webkit-transition: all 0.3s ease;
				-moz-transition: all 0.3s ease;
				-o-transition: all 0.3s ease;
				transition: all 0.3s ease;
			}
			#boxMenutype ul li input[type="button"]:hover {
				background-color: #666666;
				color:#fff;
			}
		</style>
	</center>
</body>


<?php endif; ?>
</html>	