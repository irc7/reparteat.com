<?php
	ini_set("session.cache_expire", "private");
	session_start();
	require_once ("includes/database.php");	
	$connectBD = connectdb();
	require_once ("includes/config.inc.php");
	require_once ("includes/loadFunctions.php");
	require_once ("includes/checked.php");	
	if (isset($_SESSION[PDCLOG]["Login"]) && $_SESSION[PDCLOG]["Login"] != NULL) {
		Header("Location: index.php");
	}
	require_once "includes/classes/Password/class.Password.php";
	require_once "includes/classes/Login/class.Login.php";
	if($V_PHP[0] >= 5){
		date_default_timezone_set("Europe/Paris");
	}
	$msgAlert = "";
	
	if ($_POST) {
		$now = time();
		$date_joker = "0000-00-00 00:00:00";
		
		$login = trim($_POST["login"]);
		$pwd = trim($_POST["pwd"]);
		
		$logueo = new Login($login, $pwd);
		$user = $logueo->searchUser();
		if ($user) {
			$_SESSION[PDCLOG]["idUserLog"] = $user->ID;
			$_SESSION[PDCLOG]["Login"] = $user->Login;
			$_SESSION[PDCLOG]["Type"] = $user->Type;
			$_SESSION[PDCLOG]["Name"] = $user->Name;
			Header("Location: index.php");
		}else {
			$msgAlert .= "Usuario o contraseña incorrecta.";	
		}
		disconnectdb($connectBD);
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" dir="ltr">
<head>
	<?php require_once("css/styles.php"); ?>
	<link rel="stylesheet" href="css/admin.css" type="text/css" />
   	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  	<link rel="shortcut icon" href="<?php echo DOMAIN; ?>template/images/favicon.ico">
	<title><?php echo TITLEWEB; ?> - Panel de Control</title>
    <!--[if IE]>
    <link type="text/css" rel="stylesheet" href="css/ie_only.css" />
    <![endif]-->
</head>
<body class="cp_plantilla1">
	<div class="cp_fondo-log">
		<center>
			<div id="box-log">
				<div id="box-logo-log">
					<img src="<?php echo DOMAIN; ?>pdc-reparteat/images/logo.png" />
				</div>
				<div class="separator20">&nbsp;</div>
				<div id="wrapper-login">
				<form id="form_login" method="post" action="login.php">
				<?php if($msgAlert != ""): ?>
					<div class='cp_alert error' id='info-'><span style="color:#f9bf73;font-family:'Helvetica', Arial, sans-serif;"><?php echo $msgAlert; ?></span></div>
				<?php endif; ?>
					<br/>
					<div class="cp_table350">
						<div class="cp_formfield"><label for="login">Usuario:</label></div>
						<input type="text" name="login" id="login" size="30" />
					</div>
					<br/>
					<div class="cp_table350">
					<div class="cp_formfield"><label for="pwd">Contrase&ntilde;a:</label></div>
						<input type="password" name="pwd" id="pwd" size="30" />
						<div class="separator10">&nbsp;</div>
						<input id="access" class="corporativeButton" type="submit" value="Acceder" style="float:right;" />				
					</div>
					<div id="recover_password" style="">
						<a href="<?php echo DOMAIN;?>pdc-reparteat/recover.php" alt="Recuperar contraseña" title="Recuperar contrase&ntilde;a">
							Recuperar contrase&ntilde;a
						</a>					
					</div>
				</form>	
				</div>
			</div>
		</center>
	</div>
</body>
</html>