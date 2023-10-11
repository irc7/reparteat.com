<?php
	session_start();
	header ('Content-type: text/html; charset=utf-8');
	require_once ("components/head/load.admin.php");
	
	if (!isset($_SESSION[PDCLOG]["Login"]) || $_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: login.php");
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php require_once("components/head/head.admin.php"); ?>
	
</head>
<body class="cp_plantilla1">
<div class="container-fluid">
	<div class="row">
    	<div class="cp_cabecera">
    		<?php include ("components/header/header.php"); ?>
		</div>
		<div class="wrap-home-pdc">
			<div class="col-md-3 col-sm-4 col-xs-12">
				<div class="container-fluid">
					<?php
						
						$include_mnu = "includes/menu/menu.".$mnu.".php";
						include ($include_mnu);
					?>
				</div>
			</div>
			<div class="col-md-9 col-sm-8">
				<?php
					$include_tpl = "components/".$com."/".$com.".".$tpl;
					if($opt != NULL) {
						$include_tpl .= ".".$opt;
					}
					$include_tpl .= ".php";
					//pre($include_tpl);
					include ($include_tpl);
				?>
					
			</div>
		</div>
	</div>
	<div class="separator50">&nbsp;</div>	
	<div class="cp_pie row center">
			<a id='powered' class="textBox" href='http://www.aomcomunicacion.com' target='_blank'>
				Powered by <img src="<?php echo DOMAIN; ?>template/images/logoirc.png" style="margin-top:-3px;margin-left:5px;" />
			</a>
	</div>
	
</div>

<!-- API RECAPTCHA-->

  
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <!-- Bootstrap -->
  <script src="<?php echo DOMAIN_GLOBAL; ?>js/bootstrap.js"></script>

  <script type="text/javascript" src="<?php echo DOMAIN_GLOBAL; ?>js/owl.carousel.js"></script> 
	<script src="<?php echo DOMAIN_GLOBAL; ?>js/jquery.tagify.js"></script> 
</body>
</html>
<?php 
	disconnectdb($connectBD);
?>