<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	
	$mnu = $_POST["mnu"];
	if (allowed($mnu)) {
		if ($_POST) {
			$section = trim($_POST["section"]);
			$Title = trim($_POST["Title"]);
			$Title_seo = trim($_POST["Title_seo"]);
			if($Title_seo == ""){
				$Title_seo = $Title;
			}

			$Description = trim($_POST["Description"]);
			
			$q1 = "SELECT * FROM ".preBD."users WHERE Login='".$_SESSION[PDCLOG]['Login']."'";
			
			$result1 = checkingQuery($connectBD, $q1);
			$row1 = mysqli_fetch_array($result1);
			
			$pwdhash1 = $row1['Pwd'];
			
				$q="UPDATE ".preBD."images_gallery_sections SET TITLE = '" . $Title . "', TITLE_SEO = '". $Title_seo ."' WHERE ID = '" . $section . "'";
				checkingQuery($connectBD, $q);
				$msg = $Title." modificado correctamente";
				
			
			disconnectdb($connectBD);
		}
		$location = "Location: ../../index.php?mnu=".$mnu."&com=gallery&tpl=option&opt=album&msg=".utf8_decode($msg);
		header($location);
	} else {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
?>
