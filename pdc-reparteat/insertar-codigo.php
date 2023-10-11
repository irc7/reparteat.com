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
	require_once("../lib/FileAccess/class.FileAccess.php");
	if(isset($_GET["c"]) && trim($_GET["c"]) != "") {
		$error = "no-error";
		$msg = "";
		$LoginCode = trim($_GET["c"]);
		$now = new DateTime();
		$q = "select * from `" . preBD ."users_recover_pwd` where true
		and LOGIN = '" . $LoginCode . "'
		and DATE_START <= '".$now->format("Y-m-d H:i:s")."' 
		and DATE_FINISH >= '".$now->format("Y-m-d H:i:s")."'";
		$result = checkingQuery($connectBD, $q);
		$t = mysqli_num_rows($result);
		
		if($t>0) {
			$recover = mysqli_fetch_object($result);
		}else{
			$error = "error";
			$msg .= "La solicitud no existe o ha caducado, vuelva a solicitar el código.";
			$_SESSION["resultzp"]["class"] = $error;
			$_SESSION["resultzp"]["msg"] = $msg;
			$location = "Location: " . DOMAIN . "pdc-ihp/recover.php";
			header($location);
		}
	}else {
		$location = "Location: " . DOMAIN . "pdc-ihp/recover.php";
		header($location);
	}
	if (isset($_SESSION[PDCLOG]["Login"])) {
		header("Location: index.php");
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" dir="ltr">
<head>
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
									Panel de Control - IHP PEDIATRÍA
								</div>
						<?php if($_SESSION["resultzp"]["msg"] != null && $_SESSION["resultzp"]["msg"] != ""): ?>
							<div class="col-md-12 textBox alert">
								<?php echo $_SESSION["resultzp"]["msg"]; ?>
							</div>
						<?php unset($_SESSION["resultzp"]); 
							endif; ?>
								<div class="col-md-12 info-recover-pwd">
									<form role="form" id="mainform" method="post" action="<?php echo DOMAIN; ?>pdc-ihp/actualizar-pwd.php">
										<input type="hidden" class="form-control textBox" id="idUser" name="idUser" value="<?php echo $recover->IDUSER; ?>">
										<input type="hidden" class="form-control textBox" id="Login" name="Login" value="<?php echo $recover->LOGIN; ?>">
										<div class="form-group">
											<input type="text" class="form-control textBox" id="Code" name="Code" title="Código de verificación" placeholder="Código de verificación">
											<p id="error-Code"></p>
										</div>
										<div class="textBoxFItalic grayStrong">
											<h6>Pegue aquí el código de verificación que se le ha enviado a su correo electrónico.</h6>
										</div>
										<button type="submit" class="btn btn-default textBoxBold transition bgGrayStrong white">VERIFICAR</button>
									</form>
								</div>
								<div class="separator20">&nbsp;</div>
								<div class="col-md-12 textRight">
									<a id="recover-pass" class="textBox grayNormal" href="<?php echo DOMAIN; ?>pdc-ihp" title="Salir">
										<< Salir
									</a>
								</div>
							</div>
							<div id="footer-login" class="col-md-12 textBox grayNormal">
								IHP | Centro de Especialidades Pediátricas. Todos los derechos reservados. COPYRIGHT © <?php echo date("Y"); ?>
							</div>
							<script type="text/javascript">
							//Validacion del formulario		
								var validation_options = {
									form: document.getElementById("mainform"),
									fields: [
										{
											id: "Code",
											type: "string",
											min: 1,
											max: 10
										}
									]
								};
								var v2 = new Validation(validation_options);

							</script>
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