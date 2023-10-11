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
	require_once ("includes/functions.php");
	require_once ("../includes/checkSession.php");
	if(!isset($_SESSION[nameSessionZP]) || intval($_SESSION[nameSessionZP]->ID) <= 0) {
		header("Location: iniciar-sesion");
	}
	require_once("template/modules/head/load.private.php");
	require_once("../template/modules/head/strings.php");
	
	require_once "../includes/class/class.System.php";
	require_once("../includes/class/Zone/class.Zone.php");
	require_once("../includes/class/Image/class.Image.php");
	
	require_once("../includes/class/UserWeb/class.UserWeb.php");
	require_once("../includes/class/Supplier/class.CategorySup.php");
	require_once("../includes/class/Supplier/class.Supplier.php");
	require_once("../includes/class/Supplier/class.TimeControl.php");
	require_once("../includes/class/Product/class.CategoryPro.php");
	require_once("../includes/class/Product/class.Product.php");
	require_once("../includes/class/Order/class.Order.php");
	require_once("../includes/class/Report/class.Report.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" dir="ltr">

<head>
<?php 
	require_once("template/modules/head/head.private.php"); 
?>

</head>

<body id="page-top">
<?php
	if(isset($_SESSION[msgError]["result"]) && isset($_SESSION[msgError]["msg"])) {
		require_once("template/modules/alert/msg.alert.php"); 
	}
?>
	<!-- Page Wrapper -->
	<div id="wrapper">

		<?php require("template/modules/menu/mnu.php"); ?>

		<!-- Content Wrapper -->
		<div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
			<div id="content">
				<?php require("template/modules/menu/mnu.header.php"); ?>

				<!-- Begin Page Content -->
				<div class="container-fluid">
					<?php 
						if($view == "home") {
							require("template/modules/home/home.php");
						}else {
							require("template/modules/".$view."/".$mod.".php");
						}
					?>

				</div>
				<!-- /.container-fluid -->

		  </div>
      <!-- End of Main Content -->

      <!-- Footer -->
		<?php require("template/modules/footer/footer.tpl.php"); ?>  
      <!-- End of Footer -->

		</div>
    <!-- End of Content Wrapper -->

	</div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
	<a class="scroll-to-top rounded" href="#page-top">
		<i class="fas fa-angle-up"></i>
	</a>

<!-- Confirmar logout-->
	<div id="confirmLogout" class="msg-alert">
		<div class="container">
			<div class="row">
				<div class="wrap-msg-alert text-center">
					<button id="btn-close-logout"><i class="fa fa-times grayStrong"></i></button>
					<div class="separator5"></div>
					<h4 class="arial danger">Cerrar sesión</h4>
					<div class="separator5"></div>
					<h5 class="textBox grayStrong">Vas a cerrar la sesión en este navedador. ¿Estas seguro?</h5>
					<div class="separator15"></div>
					<a class="btn btn-primary floatLeft" href="<?php echo DOMAINZP; ?>cerrar-sesion">Aceptar</a>
					<button class="btn btn-secondary floatRight arial" type="button" data-dismiss="modal">Cancelar</button>
				</div>
			</div>
		</div>
	</div>

  <!-- Bootstrap core JavaScript-->
  <script src="<?php echo DOMAINZP; ?>template/vendor/jquery/jquery.min.js"></script>
  <script src="<?php echo DOMAINZP; ?>template/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- Core plugin JavaScript-->
  <script src="<?php echo DOMAINZP; ?>template/vendor/jquery-easing/jquery.easing.min.js"></script>


	<?php if($view == "order" || $view == "product" || $view == "zone" || $view == "statistics") { ?>
		<!-- Page level plugins -->
		<script src="<?php echo DOMAINZP; ?>template/vendor/datatables/jquery.dataTables.js"></script>
		<script src="<?php echo DOMAINZP; ?>template/vendor/datatables/dataTables.bootstrap4.js"></script>

		<!-- Page level custom scripts -->
		<script src="<?php echo DOMAINZP; ?>template/js/datatables-custom.js?v=2"></script>
	<?php }else if($view == "ordenar") { ?>
		<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
		<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>  
		<script src="<?php echo DOMAINZP; ?>template/vendor/jquery/jquery-touch.js"></script>  

	<?php } ?>

  <!-- Custom scripts for all pages-->
  <script src="<?php echo DOMAINZP; ?>template/js/custom.js"></script>

  <!-- Page level plugins 
  <script src="<?php echo DOMAINZP; ?>template/vendor/chart.js/Chart.min.js"></script>
  -->

  <!-- Page level custom scripts 
  <script src="<?php echo DOMAINZP; ?>template/js/demo/chart-area-demo.js"></script>
  <script src="<?php echo DOMAINZP; ?>template/js/demo/chart-pie-demo.js"></script>
  -->

</body>

</html>
