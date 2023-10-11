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
	require_once("../../includes/classes/Publicity/class.Publicity.php");
	require_once("../../includes/classes/Publicity/class.PublicityHook.php");
	
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
		$item = new Publicity();
		$hookObj = new PublicityHook();

		$allHook = $hookObj->listHook(); 

		$item->status = intval($_POST["Status"]);
		$item->title = trim($_POST["Title"]);
		$item->subtitle = trim($_POST["Subtitle"]);
		$item->hook = array();
		$item->hook = $_POST["Hook"];
		$item->zone = array();
		$item->zone = $_POST["Zone"];
		$item->text = mysqli_real_escape_string($connectBD, trim($_POST["Text"]));
		$item->link = trim($_POST["Link"]);
		$item->target = trim($_POST["Target"]);
		$item->type = trim($_POST["Type"]);
						
		$imgs = new Image();
		$imgs->postName = "Image";
		$imgs->path = "publicity";
		$imgs->pathoriginal = "original";
		$imgs->paththumb = "image";
		$imgs->pathresize = "";
		$imgs->files = $_FILES;
		$sizes = array();
		for($i=0;$i< count($allHook);$i++) {
			$sizes[$i]['width'] = $allHook[$i]->WIDTH;
			$sizes[$i]['height'] = $allHook[$i]->HEIGHT;
		}
		$imgs->sizes = $sizes;
		$image = $imgs->uploadPublicity($allHook);
		
		if($image['image'] != "") {
			$item->image = $image["image"];
		}else {
			$msg.= $image["msg"];
		}

		$imgs = new Image();
		$imgs->postName = "ImageMobile";
		$imgs->path = "publicity";
		$imgs->pathoriginal = "original";
		$imgs->paththumb = "mobile";
		$imgs->pathresize = "";
		$imgs->files = $_FILES;
		$sizes = array();
		for($i=0;$i< count($allHook);$i++) {
			$sizes[$i]['width'] = $allHook[$i]->WIDTH_MOBILE;
			$sizes[$i]['height'] = $allHook[$i]->HEIGHT_MOBILE;
		}
		$imgs->sizes = $sizes;
		$image_m = $imgs->uploadPublicity($allHook);
		
		if($image['image'] != "") {
			$item->image_mobile = $image_m["image"];
		}else {
			$msg.= $image_m["msg"];
		}
		
		
		if($error == NULL) {

			$idNew = $item->add();

			if($idNew > 0) {
				disconnectdb($connectBD);
				$msg .= "Banner de publicidad <em>".$item->title."</em> guardado correctamente";
				$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=edit&opt=".$opt."&id=".$idNew."&msg=".utf8_decode($msg);
				header($location);
			} else {
				disconnectdb($connectBD);
				$msg .= "Error al registrar el Banner de publicidad <em>".$supplier->title."</em>.";
				$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=create&opt=".$opt."&msg=".utf8_decode($msg);
				header($location);
			}
		} else {
			disconnectdb($connectBD);
			$msg .= "Error al registrar el Banner de publicidad.";
			$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=create&opt=".$opt."&msg=".utf8_decode($msg);
			header($location);
		}
		
	}else {
		disconnectdb($connectBD);
		$msg .= "Error al registrar el Banner de publicidad.";
		$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=create&opt=".$opt."&msg=".utf8_decode($msg);
		header($location);
	}
?>