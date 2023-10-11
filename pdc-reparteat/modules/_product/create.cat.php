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
		
		$title = mysqli_real_escape_string($connectBD, trim($_POST["title"]));
		$text = mysqli_real_escape_string($connectBD, trim($_POST["text"]));
		
		$q = "INSERT INTO ".preBD."products_cat (TITLE, TEXT) VALUES ('" . $title . "', '" . $text . "')";
		checkingQuery($connectBD, $q);
		$msg = "categoria <em>".$title."</em> creada.";
		
		$idNew = mysqli_insert_id($connectBD); 
		
		$slug = formatNameUrl($title);
		$che = true;
		while($che) {
			$q = "select count(*) as t from ".preBD."url_web where SLUG = '".$slug."' and SEC_VIEW != " . $idNew . " and TYPE = 'cat'";
			$r = checkingQuery($connectBD, $q);
			$t = mysqli_fetch_object($r);
			if($t->t == 0){
				$che = false;
			}else {
				$slug = $slug."-r";
			}
		}
		$q = "INSERT INTO `".preBD."url_web` (`SLUG`, `VIEW`, `SEC_VIEW`, `ID_VIEW`, `TYPE`, `TITLE`) 
					VALUES ('".$slug."','product','".$idNew."',0,'cat','".$title."')";
		checkingQuery($connectBD, $q);	
		
		disconnectdb($connectBD);
	}
	$location = "Location: ../../index.php?mnu=content&&com=product&tpl=option&opt=cat&msg=".utf8_decode($msg);
	header($location);
?>