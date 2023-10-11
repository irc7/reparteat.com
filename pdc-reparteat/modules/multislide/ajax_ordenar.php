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
	
	require_once("../../includes/classes/Multislide/class.Multislide.php");
	
	$result = 0;
	$msg = "";
	if (isset($_POST['update'])) {
		$response = array();
		$hook = $_POST['hook'];
		foreach($_POST['positions'] as $position) {
			$id = $position[0];
			$newPosition = $position[1];
			$q = ("UPDATE ".preBD."multislide SET POSITION = '".$newPosition."' 
					WHERE IDHOOK = '".$hook."'
					and ID = '".$id."'");
			print_r(checkingQuery($connectBD,$q));
		}
	}
		
	
?>