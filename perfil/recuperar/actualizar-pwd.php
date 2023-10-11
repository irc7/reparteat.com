<?php 
	session_start();
	header ('Content-type: text/html; charset=utf-8');
	$V_PHP = explode(".", phpversion());
	if($V_PHP[0]>=5){
		date_default_timezone_set("Europe/Paris");
	}
	require_once ("../../pdc-reparteat/includes/database.php");
	$connectBD = connectdb();
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	require_once ("../../pdc-reparteat/includes/config.inc.php");
	require_once ("../../includes/functions.inc.php");
	
	require_once("../includes/functions.php");
	require_once("../template/modules/head/strings.php");
	require_once("../../includes/class/personal_keys.php");
	require_once "../../includes/class/class.System.php";
	require_once "../../includes/class/Password/class.Password.php";
	require_once "../../includes/class/UserWeb/class.UserWeb.php";
	require_once "../../includes/class/Login/class.Login.php";
	
	$error = 0;
	$msg = "";
	if($_POST) {
		$recaptcha_response = $_POST['recaptcha_response']; 
		$recaptcha = file_get_contents($recaptcha_url . '?secret=' . $passSecretv3 . '&response=' . $recaptcha_response); 
		$recaptcha = json_decode($recaptcha); 

		if($recaptcha->score >= 0.7){
		
			$now = new DateTime();
			$idUser = trim($_POST["idUser"]);
			$Login = trim($_POST["Login"]);
			$Code = trim($_POST["Code"]);
			$q = "select * from `" . preBD ."user_web_recover_pwd` where true
					and IDUSER = '" . $idUser . "' and LOGIN = '" . $Login . "' 
					and CODE = '".sha1($Code)."'
					and DATE_START <= '".$now->format("Y-m-d h:i:s")."' 
					and DATE_FINISH >= '".$now->format("Y-m-d h:i:s")."'
					order by DATE_START desc";
			
			$result = checkingQuery($connectBD, $q);
			$t = mysqli_num_rows($result);
			if ($t > 0) {
				$recover = mysqli_fetch_object($result);
			}else {
				$msg .= "La solicitud no existe o ha caducado, vuelva a solicitar el código.";
				$error = 1;
			}
		
		}else{
			$msg .= "Se ha detectado un uso erroneo de su cuenta, vuelva a intentarlo, si el problema persiste consulte con el administrador.";
			$error = 1;
		}
	}else {
		$msg .= NOPOST;
		$error = 1;
	}
	if($error == 1) {
		$_SESSION[msgError]["result"] = $error;
		$_SESSION[msgError]["msg"] = $msg;
	
		$location = "Location: " . DOMAINZP . "recuperar-contrasena";
		header($location);
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" dir="ltr">
<head>
<?php require_once("../template/modules/head/head.private.php"); ?>
</head>
<body class="bg-gradient-primary">
<?php
	if (isset($_SESSION[msgError]["result"]) && isset($_SESSION[msgError]["msg"])) {
		require_once("template/modules/alert/msg.alert.php"); 
	}
?>
	<div class="container">

	<!-- Outer Row -->
		<div class="row justify-content-center">

			<div class="col-xl-10 col-lg-12 col-md-9">

				<div class="content-login card o-hidden border-0 shadow-lg my-2">
					<div class="card-body p-0">
					<!-- Nested Row within Card Body -->
						<div class="row">
							<div class="col-lg-6 d-none d-lg-block bg-password-image"></div>
							<div class="col-lg-6">
								<div class="p-5">
									<div class="return-to-web text-right transition">
										<a class="transition" href="<?php echo DOMAINZP; ?>" title="Volver a inicio"><i class="fa fa-arrow-circle-left green transition"></i></a>
									</div>
									<div class="text-center">
										<h1 class="h4 text-gray-900 mb-4">
											<img class="img-responsive" src="<?php echo DOMAIN; ?>template/images/logo_green.png" style="max-width:200px;margin:0px auto;"/>
										</h1>
									</div>
									<div class="mb-3 textBox small">Pegue aquí el código de verificación que se le ha enviado a su correo electrónico.</div>
									<hr>
									<form class="user" role="form" id="mainform" method="post" action="save-pwd.php">
										<input type="hidden" name="recaptcha_response" id="recaptchaResponse">
										<input type="hidden" id="idUser" name="idUser" value="<?php echo $recover->IDUSER; ?>">
										<input type="hidden" id="Login" name="Login" value="<?php echo $recover->LOGIN; ?>">
										<input type="hidden" id="Code" name="Code" value="<?php echo $Code; ?>">
										<div class="textBoxFItalic grayStrong">
											<label for="Pass">Nueva contraseña:</label>
										</div>
										<div class="form-group">
											<input type="password" name="Pass" id="Pass" class="form-control form-control-user" title="Contraseña" placeholder="Contraseña (min. 8 caracteres) *" />
											<p id="error-Pass"></p>
										</div>
										<div class="textBoxFItalic grayStrong">
											<label for="PassRepeat">Repetir contraseña:</label>
										</div>
										<div class="form-group">
											<input type="password" name="PassRepeat" id="PassRepeat" class="form-control form-control-user" title="Repetir contraseña" placeholder="Repetir contraseña *" />
											<p id="error-PassRepeat"></p>
										</div>
										<button type="submit" class="btn btn-primary btn-user btn-block">ACTUALIZAR</button>
									</form>
								</div>
								
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
				<div id="footer-login" class="col-md-12 textBox grayLight text-center">
					RepartEat. Todos los derechos reservados. COPYRIGHT © <?php echo date("Y"); ?>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
<?php
	disconnectdb($connectBD);
?>