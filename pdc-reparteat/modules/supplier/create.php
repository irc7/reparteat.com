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
	require_once("../../includes/classes/Supplier/class.Supplier.php");
	require_once("../../includes/classes/Supplier/class.Category.php");
	require_once("../../includes/classes/Supplier/class.TimeControl.php");
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
		$supplier = new Supplier();
			
		$supplier->status = intval($_POST["status"]);
		$supplier->title = trim($_POST["Title"]);
		$supplier->eslogan = trim($_POST["Eslogan"]);
		$supplier->idproveedor = intval($_POST["Proveedor"]);
		$supplier->cost = floatval($_POST["Cost"]);
		$supplier->minimo = floatval($_POST["Min"]);
		$supplier->time = intval($_POST["Time"]);
		$supplier->extra = intval($_POST["Extra"]);
		$supplier->phone = trim($_POST["Phone"]);
		$supplier->movil = trim($_POST["Movil"]);
		$supplier->idtelegram = trim($_POST["IDTelegram"]);
		$supplier->categories = $_POST["Category"];
		$supplier->view_img = intval($_POST["View_img"]);
		
		$supplier->text = mysqli_real_escape_string($connectBD, trim($_POST["Text"]));
		
		
		$imgLogo = new Image();
		$imgLogo->postName = "Logo";
		$imgLogo->files = $_FILES;
		$imgLogo->sizes = array(
					'0' => array ('width' => 400,
									'height' => 400
					));
		$imgLogo->path = "supplier";
		$imgLogo->pathoriginal = "original";
		$imgLogo->pathresize = "logo";
		$imgLogo->paththumb = "thumb";
		
		$logo = $imgLogo->upload();
		
		if($logo['image'] != "") {
			$supplier->logo = $logo["image"];
		}else {
			$msg.= $logo["msg"];
		}
		
		$imgs = new Image();
		$imgs->postName = "Image";
		$imgs->path = "supplier";
		$imgs->pathoriginal = "original";
		$imgs->pathresize = "image";
		$imgs->paththumb = "thumb";
		$imgs->files = $_FILES;
		$imgs->sizes = array(
					'0' => array ('width' => 900,
									'height' => 500
					),
					'1' => array ('width' => 500,
									'height' => 400
					),
					'2' => array ('width' => 400,
									'height' => 400
					));
		$image = $imgs->upload();
		
		if($image['image'] != "") {
			$supplier->image = $image["image"];
		}else {
			$msg.= $image["msg"];
		}
		
		
		if($error == NULL) {

			$idNew = $supplier->add();

			if($idNew > 0) {
				disconnectdb($connectBD);
				$msg .= "Proveedor o restaurante <em>".$supplier->title."</em> registrado correctamente";
				$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=edit&opt=".$opt."&id=".$idNew."&msg=".utf8_decode($msg);
				header($location);
			} else {
				disconnectdb($connectBD);
				$msg .= "Error al registrar el proveedor <em>".$supplier->title."</em>.";
				$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=create&opt=".$opt."&msg=".utf8_decode($msg);
				header($location);
			}
		} else {
			disconnectdb($connectBD);
			$msg .= "Error al registrar el proveedor.";
			$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=create&opt=".$opt."&msg=".utf8_decode($msg);
			header($location);
		}
		
	}else {
		disconnectdb($connectBD);
		$msg .= "Error al registrar el proveedor.";
		$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=create&opt=".$opt."&msg=".utf8_decode($msg);
		header($location);
	}
?>