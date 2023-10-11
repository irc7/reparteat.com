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
	if (allowed("blog")) {
		if ($_POST) {
			$section = trim($_POST["section"]);

			$records = trim($_POST["records"]);
			if (allowed("blog") != 1) {
				$msg = "No tiene permisos para realizar esta operación. Usuario desconectado";
			}else if (!is_numeric($records)) {
				$msg = "Posts debe ser un número";
			}else {
				$q = "UPDATE ".preBD."articles_sections SET VIEW_ARTICLES = '" . $records . "' WHERE ID ='" . $section . "'";
				checkingQuery($connectBD, $q);

				$msg = "Sección modificada correctamente";
			}
		}
		disconnectdb($connectBD);
		$location = "Location: ../../index.php?mnu=blog&com=blog&tpl=option&opt=section&msg=".utf8_decode($msg);
		header($location);
	} else {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
?>