<?php
	session_start();
	header ('Content-type: text/html; charset=utf-8');
	$V_PHP = explode(".", phpversion());
	if($V_PHP[0]>=5){
		date_default_timezone_set("Europe/Paris");
	}
	require_once ("../../../../pdc-reparteat/includes/database.php");
	$connectBD = connectdb();
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	require_once ("../../../../pdc-reparteat/includes/config.inc.php");
	if(isset($_SESSION[nameSessionZP]) && $_SESSION[nameSessionZP]->ID > 0) {
		header("Location: inicio");
	}
	require_once("../../../../includes/functions.inc.php");
	require_once("../../../includes/functions.php");
	
	require_once("../../../../includes/class/class.System.php");
	require_once("../../../../includes/class/UserWeb/class.UserWeb.php");
	
	$msg = "";
	$error = 0;
	if ($_GET) {
		$now = time();
		
		unset($_SESSION[msgError]["result"]);
		unset($_SESSION[msgError]["msg"]);
		
		$Email = trim($_GET["email"]);
		
		$user = new UserWeb();
		
		$msg .= $user->confirmUser($Email);
						
	}else{
		$error = 1;
		$msg.= "Ha ocurrido un error inesperado, vuelva a intentarlo más tarde, si el problema persiste, consulte con el administrador.";
	}
	$_SESSION[msgError]["result"] = $error;
	$_SESSION[msgError]["msg"] = $msg;
	
	header("Location: " . DOMAINZP . "iniciar-sesion");
?>	