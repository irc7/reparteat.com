<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" dir="ltr">
<head>
	<link rel="stylesheet" href="../../css/admin.css" type="text/css" />
   	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  	<link rel="shortcut icon" href="../../../favicon.ico">
	<title>Recuperar contraseña</title>
    <!--[if IE]>
    <link type="text/css" rel="stylesheet" href="css/ie_only.css" />
    <![endif]-->
	<script type="text/javascript" src="../../js/validation.js"></script>	
	<script type="text/javascript" src="../../js/functions.js"></script>
	<style type="text/javascript">
		.active_process {
			font-weight:bold;
		}
	</style>
</head>

<body class="cp_plantilla1">
	<div class="cp_fondo">
		<table class="cp_lienzo">
			<tbody>
				<tr>
					<td colspan="2" class="cp_cabecera">
						<div class="cp_header_table" style="border-bottom:1px solid #999; height:140px;">
							<div class="cp_header_top">
									<div class="cp_header_cel0">
									<?php if (isset($_SESSION[PDCLOG]["Login"])) {
										echo "<img src='images/castellano.png' style='border:none;' />
												&nbsp;·&nbsp;
												<!--<a href='http://en.dcc-ciudades.com/pdc-reparteat/index.php' target='_self'>
														<img src='images/ingles_off.png' style='border:none;' />
													</a> &nbsp;·&nbsp;-->
													<a href='index.php?mnu=configuration&opt=601' target='_self'>".$_SESSION[PDCLOG]["Name"]." (".$_SESSION[PDCLOG]["Login"].")</a> 
													&nbsp;·&nbsp;
													<a href='logout.php' target='_self'>Desconectar</a>
													&nbsp;·&nbsp;";
												}?>
												<a href="../../index.php" target="_blank">Ver sitio web</a>
									</div>
								</div>
						   
						</div>
					</td>
				</tr>
				<tr>
					<td class="cp_col1_2" width="500" valign="top">
						<div id="wrapper_center_user" style="margin-left:50px;">
							<div id="header_article">
								<h2 id="article_title" class="content_page_title">
									<p class="page_title">Recuperar contrase&ntilde;a</p>
								</h2>
							</div>
							<div class="separator10">&nbsp;</div>
						<?php if($action == "insertLog"): ?>
							<p class="info_form">
								Indique la dirección de correo electr&oacute;nico que utiliza como usuario:
							</p>
							<div class="separator10">&nbsp;</div>
							<form method="post" action="recover.php?action=send-mail" name="editReLog" id="editReLog">
								<div id="reLogUser">
									<div class="form_pack">
										<div class="box_label"><label for="reMail">Email*:</label></div>
										<input type="text" name="reMail" id="reMail" title="Usuario / email" value="" style="width:200px;" />
									</div>
									<div class='msg_alert' id='info-reMail'></div>
								 </div>       
								 <div id="wrapper_footer_form">
									<div class="form_pack">
										<em>(*) Los campos marcados con asterisco son obligatorios.</em>
										<div class="box_button">
											<input type="button" name="return_button" id="return_button" value="Siguiente >" onclick="validate_editReLog(this);return false;"/>
										</div>
									</div>
								 </div>
							 </form>
						 <?php elseif($action == "send-mail"): ?>
							<p class="confirm_new_user">
								<?php echo $msg; ?>
							</p>
							<?php if($code_error == 0): ?>
								<br/>
								<p class="info_form">
									En dicho correo encontrar&aacute; el c&oacute;digo necesario para insertar en el paso "<em>Insertar código</em>". Este c&oacute;digo tendr&aacute; validez hasta las <?php echo $partHour[0] . ":" . $partHour[1]; ?>h., una hora después de la solicitud.
									<br/><br/>
									Consulte su correo y haga clic en "<em>Siguiente</em>".
									<br/><br/>
									Gracias por su confianza.
								</p>
								<div class="separator195">&nbsp;</div>
								<div id="wrapper_footer_form">
									<div class="form_pack">
										<div class="box_button"><input type="button" value="Siguiente >" onclick="location.href='<?php echo DOMAIN; ?>pdc-reparteat/components/recover/recover.php?action=insertar-codigo';" /></div>
									</div>
								</div>
							<?php else: ?>
								<div class="separator290">&nbsp;</div>
								<div id="wrapper_footer_form">
									<div class="form_pack">
										<div class="box_button"><input type="button" value="< Volver" onclick="history.back(-1);"/></div>
									</div>
								</div>
							<?php endif ?>
						 <?php elseif($action == "insert-code"): ?>
							<p class="info_form">
								Rellene los campos y haga click en siguiente.
							</p>
							<div class="separator10">&nbsp;</div>
							<form method="post" action="recover.php?action=edit-password" name="editReCode" id="editReCode">
								<div id="reLogUser">
									<div class="form_pack">
										<div class="box_label"><label for="reMail">Email*:</label></div>
										<input type="text" name="reMail" id="reMail" title="Usuario / email" value="" style="width:200px;" />
									</div>
									<div class="form_pack">
										<div class="box_label"><label for="reCode">C&oacute;digo*:</label></div>
										<input type="text" name="reCode" id="reCode" title="C&oacute;digo" value="" style="width:200px;" />
									</div>
									<div class='msg_alert' id='info-reMail'></div>
									<div class='msg_alert' id='info-reCode'></div>
								 </div>       
								 <div id="wrapper_footer_form">
									<div class="form_pack">
										<em>(*) Los campos marcados con asterisco son obligatorios.</em>
										<div class="box_button"><input type="button" name="return_button" id="return_button" value="Siguiente >" onclick="validate_editReCode(this);return false;"/></div>
									</div>
								 </div>
							 </form>
						 <?php elseif($action == "edit-password"): ?>
							<?php if($changePass == 1): ?>
								<p class="info_form">
									Actualice su contrase&ntilde;a:<br />
								</p>
								<div class="separator10">&nbsp;</div>
								<form method="post" action="recover.php?action=change-password" name="editPass" id="editPass">
									<input type="hidden" name="idUser" value="<?php echo $row_recover->IDUSER; ?>" />
									<div id="wrapper_header_form_edit"  style="height:180px;">
										<div class="form_pack">
											<div class="box_label"><label for="password">Nueva contrase&ntilde;a*:</label></div>
											<input class="small_input" type="password" name="passwordEdit" id="passwordEdit" title="Contrase&ntilde;a" />
										</div>
										<div class="form_pack">
											<div class="box_label"><label for="passwordEdit">Confirme contrase&ntilde;a*:</label></div>
											<input class="small_input" type="password" name="passwordCopyEdit" id="passwordCopyEdit" title="Repita contrase&ntilde;a" />               
										</div>
										<div class='msg_alert' id='info-passwordEdit'></div>
									</div>
									<div id="wrapper_footer_form">
										<div class="form_pack">
											<em>(*) Los campos marcados con asterisco son obligatorios.</em>
											<div class="box_button"><input type="button" name="EditPass" id="EditPass" value="Siguiente >" onclick="validate_newPass(this);return false;"/></div>
										</div>
									</div>
								</form>
							<?php else: ?>
								<p class="confirm_new_user">
									<?php echo $msg; ?>
								</p>
								<div class="separator290">&nbsp;</div>
								<div id="wrapper_footer_form">
									<div class="form_pack">
										<div class="box_button"><input type="button" value="< Volver" onclick="history.back(-1);"/></div>
									</div>
								</div>
							<?php endif; ?>
						 <?php elseif($action == "change-password"): ?>
							<p class="confirm_new_user">
								<?php echo $msg; ?>
							</p>
							 <p class="info_form">
								<br/>
								Si tiene algún problema, por favor, no dude en contactar con nosotros a través del correo <a href="javascript:protected_mail('info!ARROBA!ismaelrc.es')">info@ismaelrc.es</a>
								<br/>
								<br/>
								<?php echo $msg_mail; ?>
								<br/>
								<br/>
								Muchas gracias por su confianza.
							</p>
							<div class="separator160">&nbsp;</div>
							<div id="wrapper_footer_form">
								<div class="form_pack">
									<div class="box_button"><input type="button" value="Finalizar" onclick="location.href='<?php echo DOMAIN; ?>pdc-reparteat/';"/></div>
								</div>
							</div>
						 <?php endif; ?>  
						</div>
					</td>
				   <td class="cp_col2_2" width="306" valign="top">
						<div id="wrapper_right_user">
							<div id="header_menu_right">
									Recuperar contrase&ntilde;a
							</div>
							<div class="menu_process<?php if($action == "insertLog" || $action == "send-mail"){echo " active_process";} ?>">
								<a href="recover.php?action=insertLog" alt="Recuperar contrase&ntilde;a" title="Recuperar contrase&ntilde;a">
									Identificaci&oacute;n de usuario
								</a>
							</div>
							<div class="menu_process<?php if($action == "insert-code"){echo " active_process";} ?>">
								<a href="recover.php?action=insert-code.html" alt="Recuperar contrase&ntilde;a" title="Recuperar contrase&ntilde;a">    
									Insertar c&oacute;digo
								</a>
							</div>
							<div class="menu_process<?php if($action == "edit-password"){echo " active_process";} ?>">
								Actualizar contrase&ntilde;a
							</div>
							<div class="menu_process<?php if($action == "change-password"){echo " active_process";} ?>">
								Finalizar
							</div>
						</div> 
					</td>
				</tr>
				<tr>
			<td colspan="2" class="cp_pie">
				<div style="font-family:Helvetica , Arial, 'Trebuchet MS', sans-serif;color:#fff;text-align:center;font-size:10px;margin-top:12px;">
					<a id='powered' href='http://www.ismaelrc.es' target='_blank'>Powered by Ismael Rodríguez</a>
				</div>
			</td>
		</tr>
			</tbody>
		</table>
	</div>
</body>
</html>	