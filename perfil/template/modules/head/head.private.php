	<title>Área de cliente - <?php echo TITLEWEB; ?></title>


<meta http-equiv="Expires" content="0">
<meta http-equiv="Last-Modified" content="0">
<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
<meta http-equiv="Pragma" content="no-cache">	
	
	
	
	<meta http-equiv="content-type" content="text/html" charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	
<?php if($S_O == "linux"): //cabecera para android?>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<?php else: ?>
	<meta name="viewport" content="width=device-width, initial-scale=1">
<?php endif; ?>
	<meta name="title" content="<?php echo $titleWEB; ?>"/>

	<?php 
	$q = "select TEXT from ".preBD."configuration where ID = 4";

	$result_meta1 = checkingQuery($connectBD,$q);
	$row_meta1 = mysqli_fetch_assoc($result_meta1);

?>
    <meta name="keywords" content="<?php echo stripslashes($row_meta1["TEXT"]); ?>"/>
	
<?php 
	$q = "select TEXT from ".preBD."configuration where ID = 5";

	$result_meta2 = checkingQuery($connectBD,$q);
	$row_meta2 = mysqli_fetch_assoc($result_meta2);

?>
    <meta name="description" content="<?php echo stripslashes($row_meta2["TEXT"]); ?>"/>
	
<?php 
	$q3 = "select TEXT from ".preBD."configuration where ID = 6";
	
	$result_meta3 = checkingQuery($connectBD,$q3);
	$row_meta3 = mysqli_fetch_assoc($result_meta3);
	echo stripslashes($row_meta3["TEXT"]);

?>
	<meta property="og:type" content="website"> 
	
<?php 
	$q4 = "select TEXT from ".preBD."configuration where ID = 8";
	
	$result_meta4 = checkingQuery($connectBD,$q4);
	$row_meta4 = mysqli_fetch_assoc($result_meta4);
	
	echo nl2br(stripslashes($row_meta4["TEXT"])); 
 
	//CONSULTAS FACEBOOK 
	//require_once("template/modules/head/facebook.php");

?>
<!-- FUENTE  -->
	<link href='https://fonts.googleapis.com/css?family=Ubuntu:400,300,500,700' rel='stylesheet' type='text/css'>
	
	<!-- Favicons
    ================================================== -->
    <link rel="shortcut icon" href="<?php echo DOMAIN; ?>template/images/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="<?php echo DOMAIN; ?>template/images/favicon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo DOMAIN; ?>template/images/favicon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo DOMAIN; ?>template/images/img/favicon-114x114.png">

	
<?php //CSS STYLE ?>
    <!-- Font Awesome 
	-->
<?php if(strpos($_SERVER["SCRIPT_FILENAME"], "index") !== false){ ?>
	
	<link href="<?php echo DOMAINZP; ?>template/vendor/fontawesome/css/all.min.css" rel="stylesheet" type="text/css">
<?php }else{ ?>
	<link href="<?php echo DOMAIN; ?>template/fonts/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<?php } ?>
    <!-- Bootstrap -->
    <link href="<?php echo DOMAIN; ?>template/css/bootstrap.css" rel="stylesheet">
    
    <link href="<?php echo DOMAIN; ?>template/css/animate.css" rel="stylesheet">




<!-- JAVASCRIPT --> 
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<script src="<?php echo DOMAIN; ?>includes/js/checking.validation.js"></script>
	<script src="<?php echo DOMAIN; ?>includes/js/class.Validation.js"></script>
	
<!-- JAVASCRIPT --> 
<!--Recapcha v3-->
	
	<script src='https://www.google.com/recaptcha/api.js?render=<?php echo $passSitev3; ?>'></script>
	<script>
		grecaptcha.ready(function() {
			grecaptcha.execute('<?php echo $passSitev3; ?>', {action: 'form_login'})
			.then(function(token) {
				var recaptchaResponse = document.getElementById('recaptchaResponse');
				recaptchaResponse.value = token;
			});
		});
	</script>

	<script src='https://www.google.com/recaptcha/api.js?hl=es'></script>
<!--CALENDARIO-->		
	<script src="<?php echo DOMAIN; ?>pdc-reparteat/js/jquery-ui/jquery-ui.min.js"></script>
	<link rel="stylesheet" href="<?php echo DOMAIN; ?>pdc-reparteat/css/jquery-ui/redmond/jquery-ui.min.css" media="screen" />
	<noscript>
		<link rel="stylesheet" type="text/css" href="css/styleNoJS.css" />
	</noscript>
    <script type="text/javascript">
		var V = "<?php echo $view; ?>";
		var isMobile = {
			Android: function(){return navigator.userAgent.match(/Android/i);},
			BlackBerry: function(){return navigator.userAgent.match(/BlackBerry/i);},
			iOS: function(){return navigator.userAgent.match(/iPhone|iPad|iPod/i);},
			Opera: function(){return navigator.userAgent.match(/Opera Mini/i);},
			Windows: function() {return navigator.userAgent.match(/IEMobile/i);},
		};
		
	</script>
	<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="<?php echo DOMAIN; ?>template/css/global.css?v=<?php echo time(); ?>" rel="stylesheet">
	<link href="<?php echo DOMAIN; ?>template/css/global-tablet.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link href="<?php echo DOMAIN; ?>template/css/global-m.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link href="<?php echo DOMAIN; ?>template/css/template.css?v=<?php echo time(); ?>" rel="stylesheet">
	<!-- Custom styles for this template-->
	<link href="<?php echo DOMAINZP; ?>template/css/perfil.template.css?v=<?php echo time(); ?>" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?php echo DOMAINZP; ?>template/css/print.css" media="print" />
	<link href="<?php echo DOMAIN; ?>template/images/icons/css/ionicons.css" rel="stylesheet" type="text/css" />
<!--	
	<script type="text/javascript" src="<?php echo DOMAIN; ?>template/js/modernizr.custom.js"></script>
-->
	 <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
 <?php if($view == "order" || $view == "product" || $view == "zone" || $view == "statistics") { ?>
	<link href="<?php echo DOMAINZP; ?>template/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
<?php } ?>
<?php if(($view == "order" && $mod == "order" && $tpl == "supplier" && trim($_GET["filter"]) == "pending" && $_SESSION[nameSessionZP]->IDTYPE == 2) || ($_SESSION[nameSessionZP]->IDTYPE == 3 && $view == "order" && $mod == "order" && $tpl == "delivery" && (trim($_GET["filter"]) == "to-deliver" || trim($_GET["filter"]) == "no-shipping"))) { ?>
	<meta http-equiv="refresh" content="60">
<?php } ?>