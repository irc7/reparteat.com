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
	
	if(!isset($_SESSION[nameSessionZP]) || $_SESSION[nameSessionZP]->ID == 0) {
		header("Location: iniciar-sesion");
	}
	
	require_once("../../../../includes/class/class.System.php");
	
	require_once("../../../../includes/class/Product/class.Product.php");
	require_once("../../../../includes/class/Supplier/class.Supplier.php");
	require_once("../../../../includes/class/Order/class.Order.php");
	require_once("../../../../includes/class/Zone/class.Zone.php");
	require_once("../../../../includes/class/UserWeb/class.UserWeb.php");
	require_once("../../../../includes/class/Report/class.Report.php");
	
	require_once ("../../../../includes/lib/Util/class.Util.php");
	require_once ("../../../../includes/lib/FileAccess/class.FileAccess.php");
	
	$msg = "";
	$error = 0;
	
	if($_POST) {
		$view = trim($_POST['view']);
		$mod = trim($_POST['mod']);
		$tpl = trim($_POST['tpl']);
		$idZone = intval($_POST['zone']);
		$supObj = new Supplier();
		$orderObj = new Order();
		$userObj = new UserWeb();
		$zObj = new Zone();
		$itemObj = new Report();
		
		$now = new DateTime();
		if($_SESSION[nameSessionZP]->IDTYPE == 3 || ($_SESSION[nameSessionZP]->IDTYPE == 5 && $idZone > 0 && $zObj->isUserWebZone($idZone, $_SESSION[nameSessionZP])) || $_SESSION[nameSessionZP]->IDTYPE == 1) {	
			$action = trim($_POST['action']);
			
			$itemObj->id = intval($_POST['idReport']);
			$itemObj->idRep = intval($_POST['idRep']);
			$itemObj->dateCreate = new DateTime($_POST['dateCreate']);
			$dateString = $itemObj->dateCreate->format('Y-m-d');
			$itemObj->name = trim($_POST['name']);
			if(isset($_POST['day']) && $_POST['day'] == "on") {
				$itemObj->day = 1;
				$itemObj->orderDay = intval($_POST['orderDay']);
				$itemObj->salaryDay = floatval($_POST['salaryDay']);
			}else {
				$itemObj->day = 0;
				$itemObj->orderDay = 0;
				$itemObj->salaryDay = 0;
			}
			if(isset($_POST['night']) && $_POST['night'] == "on") {
				$itemObj->night = 1;
				$itemObj->orderNight = intval($_POST['orderNight']);
				$itemObj->salaryNight = floatval($_POST['salaryNight']);
			}else {
				$itemObj->night = 0;
				$itemObj->orderNight = 0;
				$itemObj->salaryNight = 0;
			}
			$itemObj->payCash = floatval($_POST['payCash']);
			$itemObj->payTPV = floatval($_POST['payTPV']);
			$itemObj->cost = floatval($_POST['cost']);
			$itemObj->total = floatval($_POST['total']);
			
			$itemObj->text = trim($_POST['text']);
			//pre($_POST);
			//pre($itemObj);
			//die();
			 
			if($error == 0) {
				if($action == "create") {
					$id = $itemObj->add();
				}else if($action == "edit") {
					$itemObj->update();
				}
				$msg .= "Formulario guardado correctamente";
			} else {
				$error = 1;
				$msg .= "Error al guardar el formulario.";
			}
			
		}else{
			$error = 1;
			$msg.= NOACCESS;
		}
	}else{
		$error = 1;
		$msg.= NOPOST;
	}
	$_SESSION[msgError]["result"] = $error;
	$_SESSION[msgError]["msg"] = $msg;
	
	disconnectdb($connectBD);
	if($error == 0) {
		$location = "Location: " . DOMAINZP . "?view=report&mod=report&tpl=sumary&z=".$idZone."&filter=".$dateString;
	}else{
		$location = "Location: " . DOMAINZP . "?view=report&mod=report&tpl=sumary&z=".$idZone."&filter=".$dateString;
	}
	header($location);

?>