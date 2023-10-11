<?php
	session_start();
	header ('Content-type: text/html; charset=utf-8');
	$V_PHP = explode(".", phpversion());
	
	
	if($V_PHP[0]>=5){
		date_default_timezone_set("Europe/Paris");
	}
	require_once ("components/head/load.admin.php");
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	if (isset($_SESSION[PDCLOG]["Login"])) {
		header("Location: index.php");
	}
	
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" dir="ltr">
	<head>
		<?php 
		require_once ("components/head/head.admin.php");
?>
</head>

<body>
	<div id="wrap-recover-pwd">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="wrap-form-pwd col-md-6 col-sm-8 col-xs-12 col-md-offset-3 col-sm-offset-2 no-padding">
						<div id="wrap-login">
							<div id="wrap-form-login">
								<div class="col-md-12">
									<img id="logo-login" class="img-responsive" src="<?php echo DOMAIN; ?>template/images/favicon-114x114.png" />
								</div>
								<div id="title-login" class="col-md-12 titleBold graySyrong">
									Panel de Control - REPARTEAT
								</div>
								<?php 
									if(isset($_SESSION["resultzp"])) {
										echo "<div class='".$_SESSION["resultzp"]["class"]."'>".$_SESSION["resultzp"]["msg"]."</div>";
										unset($_SESSION["resultzp"]);
										echo "<div class='separator30'>&nbsp;</div>";
									}
								?>
								<div class="info-recover-pwd col-md-12">
									<div class="textBoxFItalic grayStrong">
										<h6>Se le enviará un código de verificación al correo que proporcione, para comprobar que es usted el usuario registrado.</h6>
										<h6>Por motivos de seguridad, el código de verificación será valido durante <span class="textBoxFBoldItalic">1 h</span>.</h6>
									</div>
									<div class="textBox grayStrong">
										<h5>Correo electrónico del usuario:</h5>
									</div>
									<form role="form" id="form_login" method="post" action="<?php echo DOMAIN; ?>pdc-reparteat/modules/recuperar/insert-code.php">
										<?php /*<input type="hidden" name="recaptcha_response" id="recaptchaResponse">*/ ?>
										<div class="form-group">
											<input type="email" class="form-control textBox" id="Login" name="Login" placeholder="Correo electrónico">
										</div>
										<button type="submit" class="btn btn-default textBoxBold transition bgGrayStrong white">ENVIAR</button>
									</form>
								</div>
								<div class="separator20">&nbsp;</div>
								<div class="col-md-12 textRight">
									<a id="recover-pass" class="textBox grayNormal" href="<?php echo DOMAIN; ?>pdc-ihp" title="Volver">
										<< Volver
									</a>
								</div>
							</div>
							<div id="footer-login" class="col-md-12 textBox grayNormal">
								RepartEat. Todos los derechos reservados. COPYRIGHT © <?php echo date("Y"); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
<?php
	disconnectdb($connectBD);
?>