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
	
	require_once("../../includes/classes/Product/class.Icon.php");
	require_once("../../includes/classes/Image/class.Image.php");
	
	
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
		$iconObj = new Icon();
		
		$id = trim($_POST["idIcon"]);
		
		$iconBD = $iconObj->infoIconById($id);
		$iconObj->title = trim($_POST["Title"]);
		$imgIcon = new Image();
		$imgIcon->postName = "Icon";
		$imgIcon->files = $_FILES;
		$imgIcon->sizes = array(
					'0' => array ('width' => 30,
									'height' => 30
					));
		$imgIcon->path = "product";
		$imgIcon->pathoriginal = "original";
		
		$imgIcon->paththumb = "icon";
		
		$iconObj->icon = $iconBD->ICON;
		$actIcon = intval($_POST["action-icon"]);
		if($actIcon == 1) {
			if($iconBD->ICON != "") {
				$url = $imgIcon->dirbase.$imgIcon->path."/".$imgIcon->pathoriginal."/".$iconBD->ICON;
				deleteFile($url);
				for($i=0;$i<count($imgIcon->sizes);$i++) {
					$url = $imgIcon->dirbase.$imgIcon->path."/".$imgIcon->paththumb."/".($i+1)."-".$iconBD->ICON;
					deleteFile($url);
				}
				$iconObj->icon = "";
			}
		}
		
		if($_FILES["Icon"]["error"] == 0) {
			if($iconBD->ICON != "") {
				$url = $imgIcon->dirbase.$imgIcon->path."/".$imgIcon->pathoriginal."/".$iconBD->ICON;
				deleteFile($url);
				for($i=0;$i<count($imgIcon->sizes);$i++) {
					$url = $imgIcon->dirbase.$imgIcon->path."/".$imgIcon->paththumb."/".($i+1)."-".$iconBD->ICON;
					deleteFile($url);
				}
				$iconObj->icon = "";
			}
			$icon = $imgIcon->uploadThumb();
			
			if($icon['image'] != "") {
				$iconObj->icon = $icon["image"];
			}else {
				$msg.= $icon["msg"];
			}
		}
		
		$iconObj->update($id);
		
		disconnectdb($connectBD);
		$msg .= "Categoría <em>".$iconObj->title."</em> creada correctamente";
		$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=".$tpl."&opt=".$opt."&id=".$iconObj->id."&msg=".utf8_decode($msg);
		header($location);
		
	}else {
		disconnectdb($connectBD);
		$msg .= "Error al crear la categoría.";
		$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=create&opt=".$opt."&msg=".utf8_decode($msg);
		header($location);
	}
?>