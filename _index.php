<?php
	session_start();
	header ('Content-type: text/html; charset=utf-8');
	$V_PHP = explode(".", phpversion());
	if($V_PHP[0]>=5){
		date_default_timezone_set("Europe/Paris");
	}
	require_once ("pdc-reparteat/includes/database.php");
	$connectBD = connectdb();
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	require_once ("pdc-reparteat/includes/config.inc.php");
	require_once ("includes/functions.inc.php");
	require_once ("includes/checkSession.php");
	require_once("template/modules/head/load.public.php");
	if($view == "order" && (!isset($_SESSION[nameSessionZP]) || intval($_SESSION[nameSessionZP]->ID) <= 0)) {
		header("Location: ".DOMAIN."perfil/iniciar-sesion");
	}
	require_once("template/modules/head/strings.php");
	
	require_once "includes/class/class.System.php";
	require_once("includes/class/UserWeb/class.UserWeb.php");
	require_once("includes/class/Zone/class.Zone.php");
	require_once("includes/class/Image/class.Image.php");
	require_once("includes/class/Address/class.Address.php");
	
	require_once("includes/class/Supplier/class.CategorySup.php");
	require_once("includes/class/Supplier/class.Supplier.php");
	require_once("includes/class/Supplier/class.TimeControl.php");
	require_once("includes/class/Product/class.CategoryPro.php");
	require_once("includes/class/Product/class.Product.php");
	require_once("includes/class/Order/class.Order.php");
	require_once("includes/class/Article/class.Article.php");
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php if($view == "article"): ?>
<html itemscope itemtype="http://schema.org/Article">
<?php else: ?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" dir="ltr">
<?php endif; ?>
<head>
	<?php 
	
	require_once("template/modules/head/head.public.php"); 
	?>

</head>
<body id="body-<?php echo $view; ?>">
	<?php require_once("template/modules/google/google.php"); 
	if (isset($_SESSION[msgError]["result"]) && isset($_SESSION[msgError]["msg"])) {
		require_once("template/modules/alert/msg.alert.php"); 
	}
	//if (isset($_SESSION[PDCLOG]["Login"]) && $_SESSION[PDCLOG]["Login"] != NULL) {
		if($view == "home") {
			require_once("template/modules/popup/popup.php"); 
		}
	
	?>
	<?php require_once("template/modules/comun/menu.php"); ?>
			<?php
				if($view == "home") {
					require_once("template/modules/home/header.php"); 
					echo "<div class='separator5 bgYellow'>&nbsp;</div>";
				}else{
?>
			
<?php
					$module = "template/modules/" . $view . "/" . $view . ".php";
					if (file_exists($module)) {
						require_once ($module);
					}
				}
			?>
			
<?php 
				require("template/modules/comun/footer.php");
	/*}else{	
?>		
	<div class="pre-home bgGreen" style="width:100%;height:100%;display:block;">
		<center>
		<div style="width:100%;max-width:500px;padding:100px 0px 50px;margin:0 auto;">
			<img class="img-responsive" src="<?php echo DOMAIN; ?>template/images/logo_white.png" />
		</div>
		<h1 class="arial white">PROXIMAMENTE...</h1>
		<div class="separator30">&nbsp;</div>
		<p class="textBox white">Para cualquier información escríbenos a <a class="yellow" href="mailto:info@reparteat.com">info@reparteat.com</a></p>
		</center>
	</div>
<?php
	}*/
		require("template/modules/comun/jquery.start.php"); 
?>
	
</body>
</html>
<?php
	disconnectdb($connectBD);
?>
