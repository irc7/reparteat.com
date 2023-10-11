<?php
	session_start();
	if ($_SESSION["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	require_once ("../../includes/include.modules.php");
	$V_PHP = explode(".", phpversion());
	if($V_PHP[0]>=5){
		date_default_timezone_set("Europe/Paris");
	}
	$error = NULL;
	
	//pre($_POST);die();
	
	if($_POST) {	
		$sections = $_POST["destino"];

		/*Ponemos todas las secciones a 0*/
		$q_up = "UPDATE ".preBD."articles_sections SET SEARCH = 0";
		checkingQuery($connectBD, $q_up);
		
		/*Ponemos a 1 las secciones seleccionadas*/
		for($i = 0; $i < count($sections); $i++){
			$q_up2 = "UPDATE ".preBD."articles_sections SET SEARCH = 1 where ID = ".$sections[$i];
			checkingQuery($connectBD, $q_up2)
		}
		$msg = "Secciones configuradas correctamente para la búsqueda";
	} else {
		$msg = "Se ha producido un error al crear el rss, inténtelo de nuevo";
	}
	disconnectdb($connectBD);
	$location = "Location: ../../index.php?mnu=configuration&com=configuration&tpl=search&msg=".utf8_decode($msg);
	header($location);
?>