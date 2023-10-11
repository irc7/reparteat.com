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
	
	if($_POST) {
		$error = "no-error";
		$msg = "";
		$now = new DateTime();
		$idUser = trim($_POST["idUser"]);
		$Login = trim($_POST["Login"]);
		$Code = trim($_POST["Code"]);
		$q = "select * from `" . preBD ."users_recover_pwd` where true
				and IDUSER = '" . $idUser . "' and LOGIN = '" . $Login . "' 
				and CODE = '".sha1($Code)."'
				and DATE_START <= '".$now->format("Y-m-d H:i:s")."' 
				and DATE_FINISH >= '".$now->format("Y-m-d H:i:s")."'
				order by DATE_START desc";
		
		$result = checkingQuery($connectBD, $q);
		$t = mysqli_num_rows($result);
		if ($t > 0) {
			$recover = mysqli_fetch_object($result);
		}else {
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

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" dir="ltr">
<head>
<?php 
		require_once ("components/head/head.admin.php");
?>
<script src="<?php echo DOMAIN; ?>pdc-ihp/js/class.Validation.js"></script>
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
						<?php if($msgAlert != null && $msgAlert != ""): ?>
							<div class="col-md-12 textBox alert">
								<?php echo $msgAlert; ?>
							</div>
						<?php endif; ?>
								<div class="col-md-12 info-recover-pwd">
									<form role="form" id="mainform" method="post" action="<?php echo DOMAIN; ?>pdc-ihp/modules/recuperar/save-pwd.php">
										<input type="hidden" class="form-control textBox" id="idUser" name="idUser" value="<?php echo $recover->IDUSER; ?>">
										<input type="hidden" class="form-control textBox" id="Login" name="Login" value="<?php echo $recover->LOGIN; ?>">
										<input type="hidden" class="form-control textBox" id="Code" name="Code" value="<?php echo $Code; ?>">
										<div class="textBoxFItalic grayStrong">
											<label for="Pass">Nueva contraseña:</label>
										</div>
										<div class="form-group">
											<input type="password" name="Pass" id="Pass" class="form-control" title="Contraseña" placeholder="Contraseña (min. 8 caracteres) *" />
											<p id="error-Pass"></p>
										</div>
										<div class="separator20"></div>
										<div class="textBoxFItalic grayStrong">
											<label for="PassRepeat">Repetir contraseña:</label>
										</div>
										<div class="form-group">
											<input type="password" name="PassRepeat" id="PassRepeat" class="form-control" title="Repetir contraseña" placeholder="Repetir contraseña *" />
											<p id="error-PassRepeat"></p>
										</div>
										<div class="separator20"></div>
											<button type="submit" class="btn btn-default textBoxBold transition bgGrayStrong white">ACTUALIZAR</button>
										
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
											id: "Pass",
											type: "password",
											min: 8,
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