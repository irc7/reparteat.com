<?php	
	session_start();
	header ('Content-type: text/html; charset=utf-8');
	$V_PHP = explode(".", phpversion());
	if($V_PHP[0]>=5){
		date_default_timezone_set("Europe/Paris");
	}
	require_once ("../../../../pdc-reparteat/includes/database.php");
	$connectBD = connectdb();
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	require_once ("../../../../pdc-reparteat/includes/config.inc.php");
	require_once("../../../../includes/functions.inc.php");
	require_once("../../../includes/functions.php");
	require_once("../head/strings.php");
	
	if(!isset($_SESSION[nameSessionZP]) || $_SESSION[nameSessionZP]->ID == 0) {
		header("Location: iniciar-sesion");
	}
	
	require_once("../../../../includes/class/class.System.php");
	
	require_once("../../../../includes/class/Image/class.Image.php");
	require_once("../../../../includes/class/Supplier/class.Supplier.php");
	require_once("../../../../includes/class/Supplier/class.CategorySup.php");
	require_once("../../../../includes/class/Supplier/class.TimeControl.php");
	require_once("../../../../includes/class/Address/class.Address.php");
	require_once("../../../../includes/class/Zone/class.Zone.php");
	
	require_once ("../../../../includes/lib/Util/class.Util.php");
	require_once ("../../../../includes/lib/FileAccess/class.FileAccess.php");
	
	$msg = "";
	$error = 0;
	$idZone = intval($_POST["idZone"]);
	$zObj = new Zone();
	
	if($zObj->isUserWebZone($idZone, $_SESSION[nameSessionZP])) {
		if($_POST) {
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

			$idNew = $supplier->add();
			if($idNew > 0) {
				$msg .= "Proveedor o restaurante <em>".$supplier->title."</em> registrado correctamente";
			} else {
				$error = 1;
				$msg .= "Error al registrar el proveedor <em>".$supplier->title."</em>.";
			}
			
		}else{
			$error = 1;
			$msg.= NOPOST;
		}
	}else{
		$error = 1;
		$msg.= NOACCESS;
	}
	$_SESSION[msgError]["result"] = $error;
	$_SESSION[msgError]["msg"] = $msg;
	
	disconnectdb($connectBD);
	if($error == 1) {
		$location = "Location: " . DOMAINZP . "?view=supplier&mod=supplier&tpl=create&z=".$idZone;
	}else{
		$location = "Location: " . DOMAINZP . "?view=supplier&mod=supplier&tpl=profile&z=".$idZone."&sup=".$idNew;
	}
	header($location);

?>