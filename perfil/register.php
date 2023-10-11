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
	if(isset($_SESSION[nameSessionZP]) && intval($_SESSION[nameSessionZP]->ID) > 0) {
		header("Location: inicio");
	}
	require_once ("../includes/functions.inc.php");
	require_once("template/modules/head/load.private.php");
	require_once("../template/modules/head/strings.php");
	require_once("../includes/class/personal_keys.php");
	require_once "../includes/class/class.System.php";
	require_once("../includes/class/Zone/class.Zone.php");
	require_once("../includes/class/Image/class.Image.php");
	require_once("../includes/class/Article/class.Article.php");
	
	$zoneObj = new Zone();
	$zones = array(); 
	$zones = $zoneObj->listZones(); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" dir="ltr">

<head>
<?php 
	require_once("template/modules/head/head.private.php"); 
?>
</head>

<body class="bg-gradient-primary">
<?php
	if (isset($_SESSION[msgError]["result"]) && isset($_SESSION[msgError]["msg"])) {
		require_once("template/modules/alert/msg.alert.php"); 
	}
?>
	<div class="container">

		<div class="content-login card o-hidden border-0 shadow-lg my-2">
			<div class="card-body p-0">
	
				<div class="row">
					<div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
					<div class="col-lg-7">
						<div class="p-5">
							<div class="return-to-web text-right transition">
								<a class="transition" href="<?php echo DOMAIN; ?>" title="Volver a inicio"><i class="fa fa-arrow-circle-left green transition"></i></a>
							</div>
							<div class="text-center">
								<h1 class="h4 text-gray-900 mb-4 text-center">
									<center>
										<img class="img-responsive" src="<?php echo DOMAIN; ?>template/images/logo_green.png" style="max-width:250px;"/>
									</center>
								</h1>
							</div>
							<div class="text-center">
								<h1 class="h4 text-gray-900 mb-4 arial green">Crear cuenta</h1>
							</div>
							<hr>
							<form id="mainform" name="mainform" class="user" method="post" action="<?php echo DOMAINZP; ?>template/modules/user/save.user.php">
								<input type="hidden" name="recaptcha_response" id="recaptchaResponse">
								<div class="form-group ">
									<input type="text" class="form-control form-control-user" name="Name" id="Name" placeholder="Nombre *">
									<p id="error-Name"></p>
								</div>
								<div class="form-group">
									<input type="text" class="form-control form-control-user" name="Surname" id="Surname" placeholder="Apellidos *">
									<p id="error-Surname"></p>
								</div>
								<div class="form-group ">
									<input type="email" class="form-control form-control-user" name="Email" id="Email" title="Correo electrónico" placeholder="Correo electrónico *" pattern="^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$" required />
									<p id="error-Email"></p>
								</div>
								<div class="form-group row">
									<div class="col-sm-6 mb-3 mb-sm-0">
										<input type="password" class="form-control form-control-user" name="Pass" id="Pass" placeholder="Contraseña *">
										<p id="error-Pass"></p>
									</div>
									<div class="col-sm-6">
										<input type="password" class="form-control form-control-user" name="PassRepeat" id="PassRepeat" placeholder="Repetir contraseña *">
										<p id="error-PassRepeat"></p>
									</div>
								</div>
								<div class="form-group ">
									<input type="text" class="form-control form-control-user" name="Phone" id="Phone" placeholder="Teléfono de contacto" required>
									<p id="error-Phone"></p>
								</div>
								<div class="form-group ">
									<input type="text" class="form-control form-control-user" name="Street" id="Street" title="Calle o Avenida" placeholder="Calle o Avenida" required />
									<p id="error-Street"></p>
								</div>
								<div class="form-group ">
									<select class="form-control form-control-user grayNormal" name="Zone" id="Zone" title="Zona de reparto"> 
									<?php foreach($zones as $zone) { ?>
										<option value="<?php echo $zone->ID; ?>"><?php echo $zone->CITY." (".$zone->CP.")"; ?></option>
									<?php } ?>
									</select>
								</div>
								<div class="form-group legacy-policy">
									<input class="form-control transition black" type="checkbox" name="policy" id="policy-contact" title="Política de privacidad" required> 
									<label for="policy" class="policy grayStrong">
										<?php
											$artObj = new Article();
											$artPolity = infoArticleById(1);
											$artTerm = infoArticleById(3);
										?>
										<span class="textBox grayNormal">
											He leído y doy mi consentimiento a la
											<a class="textBoxBold transition" href="<?php echo DOMAIN.$artPolity->slug; ?>" alt="<?php echo $artPolity->tA; ?>" title="<?php echo $artPolity->tA; ?>" target="_blank">
												<?php echo $artPolity->tA; ?>	 					
											</a>
											y acepto los 
											<a class="textBoxBold transition" href="<?php echo DOMAIN.$artTerm->slug; ?>" alt="<?php echo $artTerm->tA; ?>" title="<?php echo $artTerm->tA; ?>" target="_blank">
												<?php echo $artTerm->tA; ?>	 					
											</a>
										</span>
									</label>
									<p id="error-policy-contact"></p>
								</div>
								<button type="submit" class="btn btn-primary btn-user btn-block">
									Crear cuenta
								</button>
							</form>
							<script type="text/javascript">
							//Validacion del formulario		
								var validation_options = {
									form: document.getElementById("mainform"),
									fields: [
										{
											id: "Name",
											type: "string",
											min: 2,
											max: 256
										},
										{
											id: "Surname",
											type: "string",
											min: 2,
											max: 256
										},
										{
											id: "Email",
											type: "email",
											min: 5,
											max: 256
										},
										{
											id: "Phone",
											type: "string",
											min: 5,
											max: 20
										},
										{
											id: "Pass",
											type: "password",
											min: 8,
											max: 10
										},
										{
											id: "Street",
											type: "string",
											min: 1,
											max: 256
										},
										{
											id: "policy-contact",
											type: "boolean"
										}
										
									]
								};
								var v2 = new Validation(validation_options);
							</script>
							<hr>
							<div class="text-center">
								<a class="small" href="<?php echo DOMAINZP; ?>iniciar-sesion">¿Ya tienes una cuenta? Iniciar sesión</a>
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

  <!-- Bootstrap core JavaScript-->
  <script src="<?php echo DOMAINZP; ?>template/vendor/jquery/jquery.min.js"></script>
  <script src="<?php echo DOMAINZP; ?>template/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="<?php echo DOMAINZP; ?>template/vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="<?php echo DOMAINZP; ?>template/js/custom.js"></script>

</body>

</html>
