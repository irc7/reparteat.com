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
	require_once("../../includes/classes/Multislide/class.Multislide.php");
	require_once("../../includes/classes/Multislide/class.MultislideHook.php");
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

		$item = new Multislide();
		$hookObj = new MultislideHook();
		$allHook = $hookObj->listHook(); 

		$id = intval($_POST["id"]);
		$itemBD = $item->infoMultislideById($id);

		$item->status = intval($_POST["Status"]);
		$item->title = trim($_POST["Title"]);
		$item->subtitle = trim($_POST["Subtitle"]);
		$item->idhook = intval($_POST["idHook"]);
		$item->video = trim($_POST["Video"]);
		$item->type = trim($_POST["Type"]);
	
		
		$imgs = new Image();
		$imgs->postName = "Image";
		$imgs->path = "multislide";
		$imgs->pathoriginal = "original";
		$imgs->pathresize = "";
		$imgs->paththumb = "image";
		$imgs->files = $_FILES;
		$sizes = array();
		for($i=0;$i< count($allHook);$i++) {
			$sizes[$i]['width'] = $allHook[$i]->WIDTH;
			$sizes[$i]['height'] = $allHook[$i]->HEIGHT;
		}
		$imgs->sizes = $sizes;
		$item->image = $itemBD->IMAGE;
		$actImage = intval($_POST["action-image"]);
		if($actImage == 1) {
			if($itemBD->IMAGE != "") {
				$url = $imgs->dirbase.$imgs->path."/".$imgs->pathoriginal."/".$itemBD->IMAGE;
				deleteFile($url);
				//$url = $imgs->dirbase.$imgs->path."/".$imgs->pathresize."/".$itemBD->IMAGE;
				//deleteFile($url);
				for($i=0;$i<count($imgs->sizes);$i++) {
					$url = $imgs->dirbase.$imgs->path."/".$imgs->paththumb."/".($i+1)."-".$itemBD->IMAGE;
					deleteFile($url);
				}
				$item->image = "";
			}
		}
		
		if($_FILES["Image"]["error"] == 0) {
			if($itemBD->IMAGE != "") {
				$url = $imgs->dirbase.$imgs->path."/".$imgs->pathoriginal."/".$itemBD->IMAGE;
				deleteFile($url);
				//$url = $imgs->dirbase.$imgs->path."/".$imgs->pathresize."/".$itemBD->IMAGE;
				//deleteFile($url);
				for($i=0;$i<count($imgs->sizes);$i++) {
					$url = $imgs->dirbase.$imgs->path."/".$imgs->paththumb."/".($i+1)."-".$itemBD->IMAGE;
					deleteFile($url);
				}
				$item->image = "";
			}
			$image = $imgs->uploadMultislide($allHook);
			
			if($image['image'] != "") {
				$item->image = $image["image"];
			}else {
				$msg.= $image["msg"];
			}
		}

		$imgM = new Image();
		$imgM->postName = "ImageMobile";
		$imgM->files = $_FILES;
		$sizes = array();
		for($i=0;$i< count($allHook);$i++) {
			$sizes[$i]['width'] = $allHook[$i]->WIDTH_MOBILE;
			$sizes[$i]['height'] = $allHook[$i]->HEIGHT_MOBILE;
		}
		$imgM->sizes = $sizes;
		$imgM->path = "multislide";
		$imgM->pathoriginal = "original";
		$imgM->pathresize = "";
		$imgM->paththumb = "mobile";
		
		$item->image_mobile = $itemBD->IMAGE_MOBILE;
		$actimgM = intval($_POST["action-image_mobile"]);
		if($actimgM == 1) {
			if($itemBD->IMAGE_MOBILE != "") {
				$url = $imgM->dirbase.$imgM->path."/".$imgM->pathoriginal."/".$itemBD->IMAGE_MOBILE;
				deleteFile($url);
				//$url = $imgM->dirbase.$imgM->path."/".$imgM->pathresize."/".$itemBD->IMAGE_MOBILE;
				//deleteFile($url);
				for($i=0;$i<count($imgM->sizes);$i++) {
					$url = $imgM->dirbase.$imgM->path."/".$imgM->paththumb."/".($i+1)."-".$itemBD->IMAGE_MOBILE;
					deleteFile($url);
				}
				$item->image_mobile = "";
			}
		}
		
		if($_FILES["ImageMobile"]["error"] == 0) {
			if($itemBD->IMAGE_MOBILE != "") {
				$url = $imgM->dirbase.$imgM->path."/".$imgM->pathoriginal."/".$itemBD->IMAGE_MOBILE;
				deleteFile($url);
				//$url = $imgM->dirbase.$imgM->path."/".$imgM->pathresize."/".$itemBD->IMAGE_MOBILE;
				//deleteFile($url);
				for($i=0;$i<count($imgM->sizes);$i++) {
					$url = $imgM->dirbase.$imgM->path."/".$imgM->paththumb."/".($i+1)."-".$itemBD->IMAGE_MOBILE;
					deleteFile($url);
				}
				$item->image_mobile = "";
			}
			$image_mobile = $imgM->uploadMultislide($allHook);
			
			if($image_mobile['image'] != "") {
				$item->image_mobile = $image_mobile["image"];
			}else {
				$msg.= $image_mobile["msg"];
			}
		}
		
		
		
		if($error == NULL) {

			$item->update($id);
			
			disconnectdb($connectBD);
			$msg .= "Slide <em>".$item->title."</em> guardado correctamente";
			$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=edit&opt=".$opt."&id=".$id."&msg=".utf8_decode($msg);
			header($location);
			
		} else {
			disconnectdb($connectBD);
			$msg .= "Error al registrar el Slide.";
			$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=edit&opt=".$opt."&msg=".utf8_decode($msg);
			header($location);
		}
		
	}else {
		disconnectdb($connectBD);
		$msg .= "Error al registrar el Slide.";
		$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=edit&opt=".$opt."&msg=".utf8_decode($msg);
		header($location);
	}
?>