<?php
	session_start();
	header ('Content-type: text/html; charset=utf-8');
	$V_PHP = explode(".", phpversion());
	if($V_PHP[0]>=5){
		date_default_timezone_set("Europe/Paris");
	}
	require_once ("../pdc-reparteat/includes/database.php");
	$connectBD = connectdb();
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	require_once ("../pdc-reparteat/includes/config.inc.php");
	require_once ("../includes/functions.inc.php");
	
	require_once ("../includes/checkSession.php");
	if(isset($_SESSION[nameSessionZP]) && $_SESSION[nameSessionZP]->ID > 0) {
		header("Location: inicio");
	}
	require_once("template/modules/head/load.private.php");
	require_once("template/modules/head/strings.php");
	require_once("includes/functions.php");
	require_once("../includes/class/personal_keys.php");
	require_once "../includes/class/class.System.php";
	require_once "../includes/class/Password/class.Password.php";
	require_once "../includes/class/UserWeb/class.UserWeb.php";
	require_once "../includes/class/Login/class.Login.php";
	
	if ($_POST) {
		$recaptcha_response = $_POST['recaptcha_response']; 
		$recaptcha = file_get_contents($recaptcha_url . '?secret=' . $passSecretv3 . '&response=' . $recaptcha_response); 
		$recaptcha = json_decode($recaptcha); 

		if($recaptcha->score >= 0.5){
			$now = time();
			$date_joker = "0000-00-00 00:00:00";
			$msgAlert = null;
			
			$Login = trim($_POST["Login"]);
			$Pass = trim($_POST["Pass"]);
			$logueo = new Login($Login, $Pass);
			$user = $logueo->searchUser();
			if ($user) {
				$q = "select MAX(DATE_LOG) as last from `".preBD."user_web_log` where IDUSER = " . $user->ID;
				$r = checkingQuery($connectBD, $q);
				$Date = mysqli_fetch_object($r);
				$ql = "INSERT INTO `".preBD."user_web_log`(`IDUSER`, `IP`, `DATE_LOG`, `LOG`) 
						VALUES 
					('".$user->ID."','".$_SERVER["REMOTE_ADDR"]."',NOW(),1)";
					
				checkingQuery($connectBD, $ql);
				
				if(isset($_POST["customCheck"]) && $_POST["customCheck"] == 1) {
					$codeCookie = $user->PASS . "%#-#%" . $user->ID;
					$dateEnd = new DateTime();
					$dateEndSeg = $dateEnd->getTimestamp() + timeCookie;
					setcookie(nameCookie, $codeCookie, $dateEndSeg, "/");
				}
				
				$_SESSION[nameSessionZP] = $user;
				$_SESSION[nameSessionZP]->startLog = 1;
				$_SESSION[nameSessionZP]->timelogin = new DateTime();
				$_SESSION[nameSessionZP]->timeline = new DateTime($Date->last);
				if(isset($_POST["returnUrl"]) && trim($_POST["returnUrl"]) != "") {
					header("Location: ".trim($_POST["returnUrl"]));
				}else{
					header("Location: inicio");	
				}
			} else {
				$_SESSION[msgError]["result"] = 1;
				$_SESSION[msgError]["msg"] = "Usuario o contraseña incorrecta.";
	
			}
		}else{
			$_SESSION[msgError]["result"] = 1;
			$_SESSION[msgError]["msg"] = "Se ha detectado un uso erroneo de su cuenta, vuelva a intentarlo, si el problema persiste consulte con el administrador.";
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" dir="ltr">
<head>
	<?php require_once("template/modules/head/head.private.php"); ?>	
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
							<div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
							<div class="col-lg-6">
								<div class="p-5">
									<div class="return-to-web text-right transition">
										<a class="transition" href="<?php echo DOMAIN; ?>" title="Volver a inicio"><i class="fa fa-arrow-circle-left green transition"></i></a>
									</div>
									<div class="text-center">
										<h1 class="h4 text-gray-900 mb-4">
											<img class="img-responsive" src="<?php echo DOMAIN; ?>template/images/logo_green.png" />
										</h1>
									</div>
									<form id="mainform" class="user" method="post" action="<?php echo DOMAINZP; ?>iniciar-sesion">
										<input type="hidden" name="recaptcha_response" id="recaptchaResponse">
										<input type="hidden" name="returnUrl" value="<?php echo $_SERVER["HTTP_REFERER"]; ?>" />
										<div class="form-group">
											<input type="email" class="form-control form-control-user" name="Login" id="Login" title="Correo electrónico" placeholder="Correo electrónico">
											<p id="error-Login"></p>
										</div>
										<div class="form-group">
											<input type="password" class="form-control form-control-user" name="Pass" id="Pass" placeholder="Contraseña" title="Contraseña">
											<p id="error-Pass"></p>
										</div>
										<div class="form-group">
											<div class="custom-control custom-checkbox small">
												<input type="checkbox" class="form-control custom-control-input" name="customCheck" id="customCheck" value="1" />
												<label class="custom-control-label" for="customCheck">Recuerdame</label>
											</div>
										</div>
										<button type="submit" class="btn btn-primary btn-user btn-block">
											Iniciar sesión
										</button>
									</form>
									<script type="text/javascript">
									//Validacion del formulario		
										var validation_options = {
											form: document.getElementById("mainform"),
											fields: [
												{
													id: "Login",
													type: "email",
													min: 5,
													max: 256
												},
												{
													id: "Pass",
													type: "string",
													min: 8,
													max: 50
												}
											]
										};
										var v2 = new Validation(validation_options);
									</script>
									<hr>
									
									<div class="text-center">
										<a class="small" href="<?php echo DOMAINZP; ?>crear-cuenta" title="Crear una cuenta nueva">¡Registrate aquí!</a>
									</div>
									
									<hr>
									<div class="text-center">
										<a class="small" href="<?php echo DOMAINZP; ?>recuperar-contrasena" title="Recuperar contraseña">¿Olvidaste tu contraseña?</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="footer-login" class="col-md-12 textBox grayLight text-center">
					RepartEat. Todos los derechos reservados. COPYRIGHT © <?php echo date("Y"); ?>
				</div>
			</div>

		</div>

	</div>

	<!-- Bootstrap core JavaScript-->
	<script src="<?php echo DOMAINZP; ?>template/vendor/jquery/jquery.min.js"></script>
	<script src="<?php echo DOMAINZP; ?>template/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

	<!-- Core plugin JavaScript-->
	<script src="<?php echo DOMAINZP; ?>template/vendor/jquery-easing/jquery.easing.min.js"></script>

	<!-- Custom scripts for all pages-->
	<script src="<?php echo DOMAINZP; ?>template/js/custom.js"></script>

</body>

</html>
