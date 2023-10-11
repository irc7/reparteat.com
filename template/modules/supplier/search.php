<?php	
	session_start();
	$V_PHP = explode(".", phpversion());
	if($V_PHP[0]>=5){
		date_default_timezone_set("Europe/Paris");
	}
	require_once ("../../../pdc-reparteat/includes/database.php");
	$connectBD = connectdb();
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	require_once ("../../../pdc-reparteat/includes/config.inc.php");
	require_once ("../../../includes/functions.inc.php");
	
	$_SESSION[sha1("zone")] = "";
	if(isset($_POST["search"])) {
	//if(isset($_GET["search"])) {
		$_SESSION[sha1("zone")] = intval($_POST["search"]);
		//$_SESSION[sha1("zone")] = intval($_GET["search"]);
	}
	
	header("Location: " . DOMAIN.SLUGSUP);
?>
