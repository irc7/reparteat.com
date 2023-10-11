<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	
	if (!allowed("design")) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}	
	if(isset($_GET["record"]) && intval($_GET["record"]) != 0) {
		
		$record = $_GET["record"];
		
		
		$q = "SELECT * FROM ".preBD."slider WHERE ID='".$record."'";
		$result = checkingQuery($connectBD, $q);
		
		$row = mysqli_fetch_array($result);
		
		$Image_url = "../../../files/slide/image/".$row['IMAGE'];
		if($row['IMAGE'] != "") {
			deleteFile($Image_url);
		}
		$position = $row['POSITION'];
		
		$q = "DELETE FROM ".preBD."slider WHERE ID='".$record."'";
		checkingQuery($connectBD, $q);
		
		
		$q_up = "UPDATE ".preBD."slider SET POSITION = POSITION - 1 WHERE IDALBUM = ".$row['IDALBUM']." and POSITION > ".$position;
		checkingQuery($connectBD, $q_up);
		
		$msg = "Imagen <em>".$row['TITLE']."</em> eliminada definitivamente";
			
  		disconnectdb($connectBD);
		$location = "Location: ../../index.php?mnu=design&com=slide&tpl=option&filteralbum=".$row['IDALBUM']."&msg=".utf8_decode($msg);
		
	} else {
		$location = "Location: ../../index.php?mnu=design&com=slide&tpl=option&msg=".utf8_decode($msg);
	}
	header($location);
?>