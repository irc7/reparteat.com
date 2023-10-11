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
	
	require_once("../../includes/classes/Publicity/class.Publicity.php");
	
	$result = 0;
	$msg = "";
	if (isset($_POST['update'])) {
		$response = array();
		$hook = $_POST['hook'];
		$zone = $_POST['zone'];
		foreach($_POST['positions'] as $position) {
			$id = $position[0];
			$newPosition = $position[1];
			$q = ("UPDATE ".preBD."publicity_hook_zone SET POSITION = '".$newPosition."' 
					WHERE HOOK = '".$hook."'
					and IDZONE = '".$zone."' 
					and IDITEM = '".$id."'");
			print_r(checkingQuery($connectBD,$q));
		}
	}
		
	
?>