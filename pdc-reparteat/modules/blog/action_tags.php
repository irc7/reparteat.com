<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	$V_PHP = explode(".", phpversion());
	if($V_PHP[0]>=5){
		date_default_timezone_set("Europe/Paris");
	}
	$connectBD = connectdb();
	
	if($_GET) {
		$mnu = trim($_GET["mnu"]);
		$action = trim($_GET["action"]);
		$id = intval($_GET["tag"]);
	}elseif($_POST){
		$mnu = trim($_POST["mnu"]);
		$action = trim($_POST["action"]);
		$id = intval($_POST["tag"]);
		$Title = trim($_POST["Title"]);
		$Slug = formatNameUrl(trim($_POST["Title"]));
	}else {
		disconnectdb($connectBD);
		$msg = "Se ha producido un error, si el problema persiste, póngase en contacto con el administrador.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
	
	if($mnu == "" || !allowed($mnu)) { 	
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
	if($action != "") {
		switch($action) {
			case "create":
				$q = "INSERT INTO `".preBD."blog_tags`(`ID`, `TITLE`, `SLUG`) 
						VALUES 
					(NULL,'".mysqli_real_escape_string($connectBD,$Title)."','".mysqli_real_escape_string($connectBD,$Slug)."')";
				checkingQuery($connectBD, $q);
				
				$msgAlert = "creada";
			break;
			case "edit":
				$qD2 = "UPDATE `".preBD."blog_tags` SET 
						`TITLE`='".mysqli_real_escape_string($connectBD,$Title)."',
						`SLUG`='".mysqli_real_escape_string($connectBD,$Slug)."'
						WHERE ID = " . $id;
				checkingQuery($connectBD, $qD2);
				
				$msgAlert = "modificada";
			break;
			case "delete":
				$q1 = "DELETE FROM `".preBD."blog_post_tags` WHERE ID_TAG = " . $id;
				checkingQuery($connectBD, $q1);
				
				$q2 = "DELETE FROM `".preBD."blog_tags` WHERE ID = " . $id;
				checkingQuery($connectBD, $q2);
				
				$msgAlert = "eliminada";
			break;
		}
	
		$msg = "Etiqueta <em>".$Title."</em> ".$msgAlert." correctamente.";
		$location = "Location: ../../index.php?mnu=".$mnu."&com=blog&tpl=option&opt=tags&msg=".utf8_decode($msg);
		
	}else{
		$msg = "Se ha producido un error, si el problema persiste, póngase en contacto con el administrador.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
	}	
	disconnectdb($connectBD);
	header($location);
	
?>