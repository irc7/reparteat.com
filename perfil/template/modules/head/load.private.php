<?php
	
	if(isset($_GET["view"]) && $_GET["view"] != NULL && $_GET["view"] != ""){
		$view = trim($_GET["view"]);
	}else{
		$view = "home";
	}
	if(isset($_GET["mod"])){$mod = trim($_GET["mod"]);}else{$module = "";}
	if(isset($_GET["tpl"])){$tpl = trim($_GET["tpl"]);}else{$tpl = "";}
	
	if(isset($_GET["id"])){$id = $_GET["id"];}else{$id = 0;}
	if(isset($_GET["idview"])){$idview = $_GET["idview"];}else{$idview = 0;}
	$moduleView = $view;
	
	$now = date('Y').date('m').date('d').date('H').date('i').date('s');
	$date_joker = "0000-00-00 00:00:00";

	$V_PHP = explode(".", phpversion());
	if($V_PHP>=5){
		date_default_timezone_set("Europe/Paris");
	}
	
	$navigation = ObtenerNavegador();
	$S_O = ObtenerSistemaOperativo();
	
	
//	pre($navigation);
//	pre($S_O);
?>



















