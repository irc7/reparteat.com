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
	require_once("../../includes/classes/Image/class.Image.php");
	require_once("../../includes/classes/Address/class.Address.php");
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
		
		$address = new Address();
		
		$address->street = trim($_POST["Street"]);
		$address->type = "points";
		$address->idassoc = 0;
		$address->fav = intval($_POST["status"]);
		$address->active = intval($_POST["status"]);
		$address->idzone = intval($_POST["Zone"]);
		
		$imgs = new Image();
		$imgs->postName = "Image";
		$imgs->path = "points";
		$imgs->pathoriginal = "original";
		$imgs->paththumb = "icon";
		$imgs->pathresize = "";
		$imgs->files = $_FILES;
		$sizes = array();
		$sizes[0]['width'] = 400;
		$sizes[0]['height'] = 400;
		$imgs->sizes = $sizes;
		$image = $imgs->uploadPoints();
		
		if($image['image'] != "") {
			$address->image = $image["image"];
		}else {
			$msg.= $image["msg"];
		}

		if($address->street != "" && $address->idzone > 0) {
			$idNew = $address->add();
			
			disconnectdb($connectBD);
			if($idNew != false) {
				$msg .= "Punto de recogida guardado correctamente.";
				$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=edit&opt=".$opt."&id=".$idNew."&msg=".utf8_decode($msg);
			}else{
				$msg .= "Error al registrar el punto de recogida, vuelva a intentarlo, si el problema persiste consulte con el administrador.";
				$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=create&opt=".$opt."&msg=".utf8_decode($msg);
			}
			header($location);	
		} else {
			disconnectdb($connectBD);
			$msg .= "Error al registrar el punto de recogida, los datos no son correctos.";
			$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=create&opt=".$opt."&msg=".utf8_decode($msg);
			header($location);
		}
		
	}else {
		disconnectdb($connectBD);
		$msg .= "Error al registrar el punto de recogida.";
		$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=create&opt=".$opt."&msg=".utf8_decode($msg);
		header($location);
	}
?>