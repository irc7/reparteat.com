<?php
session_start();
require_once ("../../includes/include.modules.php");

if ($_SESSION[PDCLOG]["Login"] == NULL) {
	Header("Location: ../../login.php");
}
// CONNECT
$mnu = trim($_GET["mnu"]);
if (!allowed($mnu)) {
	disconnectdb($connectBD);
	$msg = "No tiene permisos para realizar esta acción.";
	$location = "Location: ../../index.php?msg=".utf8_decode($msg);
	header($location);
}
if(isset($_GET["record"])) {
	checkingQuery($connectBD, "SET lc_time_names = 'es_ES'");
	checkingQuery($connectBD, "SET NAMES 'UTF8'");

	$error = NULL;

	
	$id = intval(abs($_GET["record"]));
	
	$q = "SELECT * FROM ".preBD."newsletter where ID = " . $id;
	$result = checkingQuery($connectBD, $q);
	$row = mysqli_fetch_object($result); 
	
	
	$st = "?idn=".$row->ID;
	$bodyBD = '<html>
				<head>
					<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
					<link rel="shortcut icon" href="'.DOMAIN.'favicon.ico">
					<title>'.$row->SUBJECT.'</title>
				</head>
				<body>
					<div style="padding-bottom:10px;width:100%;display:block;background-color:#fff;font-size:11px;">
						<center>
							<font face="Arial" style="color:#333;">Si no visualiza correctamente este bolet&iacute;n, haga click </font>
							<a href="'.DOMAIN.'public/modules/newsletter/newsletter.online.php?idn='.$row->ID.'" style="color:#92b53d;">
								<font face="Arial">aqu&iacute;</font>
							</a>
						</center>
					</div>
					<div id="wrapper_newsletter_online">'.$row->HTML.'</div>
					<img src="'.DOMAIN.'public/modules/statistics/statistics_newsletter_open.php?idn='.$row->ID.'" height="1" width="1" style="border:none;" />
				</body>
			</html>';

	$bodyBD = str_replace("#STATISTICS_PARAM#",$st, $bodyBD);
	//$bodyBD = htmlspecialchars($bodyBD);
	
	
	$path = "../../../temp/";
	
	$file = formatNameUrl($row->SUBJECT).".html";
	
	$urlTemp = $path.$file; 
 
    if(file_exists($urlTemp)){
        unlink($urlTemp);
    }
	
	$mode = "w+";
	if($fp = fopen($urlTemp,$mode)) {
	   fwrite($fp,$bodyBD);
	   fclose($fp);
	} else { 
	   $msg .= "Ha habido un problema con el archivo ".$file." no ha sido creado correctamente.</br>";
	}
	
	header("Content-type: text/html; charset=utf-8");
	header("Content-Disposition: attachment; filename=".$file);
	header("Content-Transfer-Encoding: binary");

 // Descargar archivo
	readfile($urlTemp);
	
	unlink($urlTemp);
	disconnectdb($connectBD);
	
}else {
	$msg = "Se ha producido un error inesperado al descargar el código del boletín. por favor vuelva a intentarlos más tarde";
	$location = "Location: ../../index.php?msg=".utf8_decode($msg);
	header($location);
	disconnectdb($connectBD);
}
?>