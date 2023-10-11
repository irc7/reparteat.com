<?php //CONSULTAS SEO POSICIONAMIENTO PARA FACEBOOK
	require_once("template/modules/head/titleweb.php");
	$titleWEB = strip_tags($titleWEB);
?>
	<title><?php echo $titleWEB; ?></title>
	
	
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
    <meta name="description" content="<?php echo cutting(stripslashes($row_meta2["TEXT"]),155); ?>"/>
	
<?php 
	$q3 = "select TEXT from ".preBD."configuration where ID = 6";
	
	$result_meta3 = checkingQuery($connectBD,$q3);
	$row_meta3 = mysqli_fetch_assoc($result_meta3);
	echo stripslashes($row_meta3["TEXT"]);

?>
	<meta property="og:type" content="website"> 
	<link rel="author" content="www.ismaelrc.com" />
	<link rel="publisher" href="www.ismaelrc.com" />
	
<?php 
	$q4 = "select TEXT from ".preBD."configuration where ID = 8";
	
	$result_meta4 = checkingQuery($connectBD,$q4);
	$row_meta4 = mysqli_fetch_assoc($result_meta4);
	
	echo nl2br(stripslashes($row_meta4["TEXT"])); 
?>
	<meta itemprop="name" content="Nombre de la página web">
	<meta itemprop="description" content="<?php echo cutting(stripslashes($row_meta2["TEXT"]),155); ?>">
	<meta itemprop="image" content="<?php echo DOMAIN; ?>template/images/img-rrss-default.jpg">
<?php 
	//CONSULTAS FACEBOOK 
	require_once("template/modules/head/facebook.php");
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
    <link href="<?php echo DOMAIN; ?>template/css/font-awesome.css" rel="stylesheet">
	-->
	<link href="<?php echo DOMAIN; ?>template/fonts/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- Bootstrap -->
    <link href="<?php echo DOMAIN; ?>template/css/bootstrap.css" rel="stylesheet">
    
    <link href="<?php echo DOMAIN; ?>template/css/magnific-popup.css" rel="stylesheet">
	
    <link href="<?php echo DOMAIN; ?>template/css/template.css?v=<?php echo time(); ?>" rel="stylesheet"> 
    <link href="<?php echo DOMAIN; ?>template/css/style.template.css?v=<?php echo time(); ?>" rel="stylesheet"> 
<!-- Template Style -->
	<link href="<?php echo DOMAIN; ?>template/css/animate.css" rel="stylesheet">
    <link href="<?php echo DOMAIN; ?>template/css/owl.carousel.css" rel="stylesheet">
    <link href="<?php echo DOMAIN; ?>template/css/owl.theme.css" rel="stylesheet">
    <link href="<?php echo DOMAIN; ?>template/css/flexslider.css.css" rel="stylesheet">
<!-- Main Style -->
    <link href="<?php echo DOMAIN; ?>template/css/responsive.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link href="<?php echo DOMAIN; ?>template/css/global.css?v=<?php echo time(); ?>" rel="stylesheet">
	
<!-- responsive -->	
    <link href="<?php echo DOMAIN; ?>template/css/global-tablet.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link href="<?php echo DOMAIN; ?>template/css/global-m.css?v=<?php echo time(); ?>" rel="stylesheet">
	

<!-- JAVASCRIPT --> 
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo DOMAIN; ?>template/js/jquery.validate.min.js"></script>
	 
<!-- JAVASCRIPT 
	<script src='https://www.google.com/recaptcha/api.js?hl=es'></script>
	<script type="text/javascript" src="<?php echo DOMAIN; ?>template/js/lytebox.js"></script>
    <link href="<?php echo DOMAIN; ?>template/css/lytebox.css" rel="stylesheet">
--> 
	
<?php 
	if($view == "contact") {
		require_once("includes/class/personal_keys.php"); 
	?>
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
	
<?php } ?>	
	<script type="text/javascript" src="<?php echo DOMAIN; ?>template/js/functions.global.js?v=<?php echo time(); ?>"></script>
	
	<noscript>
		<link rel="stylesheet" type="text/css" href="css/styleNoJS.css" />
	</noscript>
    <script type="text/javascript">
		var V = "<?php echo $view; ?>";
		var DomainWeb = "<?php echo DOMAIN; ?>";
		var isMobile = {
			Android: function(){return navigator.userAgent.match(/Android/i);},
			BlackBerry: function(){return navigator.userAgent.match(/BlackBerry/i);},
			iOS: function(){return navigator.userAgent.match(/iPhone|iPad|iPod/i);},
			Opera: function(){return navigator.userAgent.match(/Opera Mini/i);},
			Windows: function() {return navigator.userAgent.match(/IEMobile/i);},
		};
		
	</script>

	
	 <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<?php
		$q = "select TITLE, TEXT, VALUE, AUXILIARY from ".preBD."configuration where ID = 2;";
		$result = checkingQuery($connectBD,$q);
		$googleBDinfo = mysqli_fetch_object($result); 	
		echo stripslashes($googleBDinfo->TEXT); 
		?>
	<?php if($view == "order" && isset($_GET["supplier"] )&& intval($_GET["supplier"]) > 0) { ?>
		<meta http-equiv="refresh" content="120">
	<?php } ?>