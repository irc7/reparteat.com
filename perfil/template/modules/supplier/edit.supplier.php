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
	
	require_once("../../../../includes/class/Supplier/class.Supplier.php");
	require_once("../../../../includes/class/Supplier/class.CategorySup.php");
	require_once("../../../../includes/class/Supplier/class.TimeControl.php");
	require_once("../../../../includes/class/Address/class.Address.php");
	
	require_once ("../../../../includes/lib/Util/class.Util.php");
	require_once ("../../../../includes/lib/FileAccess/class.FileAccess.php");
	
	require_once("../../../../includes/class/class.phpmailer.php");
	require_once("../../../../includes/class/class.smtp.php");
	
	require_once("../../../../includes/class/personal_keys.php");
	$msg = "";
	$error = 0;
	$id = intval($_POST["id"]);
	$supplier = new Supplier();
	//pre($_POST);die();
	if($supplier->isUserWebSupplier($id, $_SESSION[nameSessionZP])) {
		if($_POST) {
			$supBD = $supplier->infoSupplierById($id);
			
			$supplier->status = intval($_POST["status"]);
			$supplier->title = trim($_POST["Title"]);
			$supplier->minimo = floatval($_POST["Min"]);
			$supplier->time = intval($_POST["Time"]);
			$supplier->phone = trim($_POST["Phone"]);
			$supplier->movil = trim($_POST["Movil"]);
			$supplier->categories = $_POST["Category"];
			
			$supplier->update($id);
			
			$msg .= "Datos actualizados correctamente";
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
	$location = "Location: " . DOMAINZP . "?view=supplier&mod=supplier&tpl=profile&sup=".$id;
	header($location);

?>