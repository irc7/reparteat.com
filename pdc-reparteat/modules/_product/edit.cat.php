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
	if (!allowed("design")) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}	
	if ($_POST) {
		
		$id = intval($_POST["cat"]);
		$title = mysqli_real_escape_string($connectBD, trim($_POST["title"]));
		$text = mysqli_real_escape_string($connectBD, trim($_POST["text"]));
			$q="UPDATE ".preBD."products_cat SET 
				TITLE = '" . $title . "', 
				TEXT = '". $text . "' 
				WHERE ID = '" . $id . "'";
			checkingQuery($connectBD, $q);
  			
			$slug = formatNameUrl($title);
			$che = true;
			while($che) {
				$q = "select count(*) as t from ".preBD."url_web where SLUG = '".$slug."' and ID_VIEW != " . $id . " and TYPE = 'cat'";
				$r = checkingQuery($connectBD, $q);
				$t = mysqli_fetch_object($r);
				if($t->t == 0){
					$che = false;
				}else {
					$slug = $slug."-r";
				}
			}
			
			
			$q = "UPDATE `".preBD."url_web` SET 
				`SLUG`='".$slug."',
				`TITLE`='".mysqli_real_escape_string($connectBD,$title)."' 
				WHERE ID_VIEW = '" . $id . "' and TYPE = 'product'";
			checkingQuery($connectBD, $q);
			
			
			$msg = "Categoría <em>".$title."</em> modificada correctamente.";
			
			
		disconnectdb($connectBD);
	}
	$location = "Location: ../../index.php?mnu=content&com=product&tpl=option&opt=cat&msg=".utf8_decode($msg);
	header($location);
?>