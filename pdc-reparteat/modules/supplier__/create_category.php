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
	
	require_once("../../includes/classes/Supplier/class.Category.php");
	
	$mnu = trim($_POST["mnu"]);
	
	if (!allowed($mnu)) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
	
	$com = trim($_POST["com"]);
	$tpl = trim($_POST["tpl"]);
	$opt = trim($_POST["opt"]);
	
	if ($_POST) {
		
		$msg = "";
		$error = NULL;
		$catObj = new Category();
		
		$catObj->title = trim($_POST["Title"]);
		$catObj->text = trim($_POST["Text"]);
		
		$catObj->add();
		
		disconnectdb($connectBD);
		$msg .= "Categoría <em>".$catObj->title."</em> creada correctamente";
		$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=".$tpl."&opt=".$opt."&id=".$catObj->id."&msg=".utf8_decode($msg);
		header($location);
		
	}else {
		disconnectdb($connectBD);
		$msg .= "Error al crear la categoría.";
		$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=create&opt=".$opt."&msg=".utf8_decode($msg);
		header($location);
	}
?>