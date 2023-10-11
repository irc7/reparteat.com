<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	header ('Content-type: text/html; charset=utf-8');
	$V_PHP = explode(".", phpversion());
	if($V_PHP[0]>=5){
		date_default_timezone_set("Europe/Paris");
	}
	
	if (!allowed("blog")) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
	
	$id = abs(intval($_GET["id"]));
	if($id > 0) {
		$q = "delete from ".preBD."blog_subscriptors_trail where IDPOST = " . $id;
		checkingQuery($connectBD, $q);
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<link rel="shortcut icon" href="../../../favicon.ico">
	<link rel="stylesheet" href="../../css/admin.css" type="text/css" />
	<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script> 
	<title>Panel de Control - Nueva entrada de blog</title>
</head>	
<body>
	<script type="text/javascript">
		window.close();
	</script>
</body>