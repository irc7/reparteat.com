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
	
	require_once("../../includes/classes/Product/class.Product.php");
	
	if($_GET) {
		$mnu = trim($_GET["mnu"]);
		$com = trim($_GET["com"]);
		$opt = trim($_GET["opt"]);
		if(!isset($_GET["mnu"]) || !allowed($_GET["mnu"])) { 	
			disconnectdb($connectBD);
			$msg = "No tiene permisos para realizar esta acción";
			$location = "Location: ../../index.php?msg=".utf8_decode($msg);
			header($location);
		} else {
			
			$id = intval($_GET["id"]);
			$urlAux = "";
			
			if(isset($_GET["recodsperpage"]) && intval($_GET["recodsperpage"])>0){
				$recodsperpage = intval($_GET["recodsperpage"]);
				$urlAux .= "&recodsperpage=".$recodsperpage;
			}
			if(isset($_GET["search"]) && intval($_GET["search"])>0){
				$search = intval($_GET["search"]);
				$urlAux .= "&search=".$search;
			}
			if(isset($_GET["page"]) && intval($_GET["page"])>0){
				$page = intval($_GET["page"]);
				$urlAux .= "&page=".$page;
			}
			
			$action = trim($_GET["action"]);
			
	
			$proObj = new Product();
			$proBD = $proObj->infoProductById($id);
			
			if($id >= 0) {
				switch($action) {
					case "publish":
						$proObj->upStatus($id, 1);
						$msgAlert = "Producto <em>".$proBD->TITLE."</em> publicado correctamente.";
					break;
					case "unpublish":
						$proObj->upStatus($id, 0);
						$msgAlert = "Producto <em>".$proBD->TITLE."</em> desactivado correctamente.";
					break;
					case "delete":
						$proObj->delete($id);
						$msgAlert = "Producto <em>".$proBD->TITLE."</em> eliminado correctamente.";
					break;
				}
			}
			disconnectdb($connectBD);
			$msg = $msgAlert;
			$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=option&opt=".$opt."&msg=".utf8_decode($msg).$urlAux;
			header($location);
		}	
	}else {
		disconnectdb($connectBD);
		$msg = "Se ha producido un error, si el problema persiste, póngase en contacto con el administrador.";
		$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&opt=".$opt."&tpl=option&msg=".utf8_decode($msg);
		header($location);
	}
?>