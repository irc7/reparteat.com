<?php

	function connectdb() {
		$con = mysqli_connect("localhost", "reparteat", "To6s#n70", "reparteat_shop");
		//$con = mysqli_connect("localhost", "root", "", "reparteat");
		if (!$con) {
			die('Imposible conectarse al servidor: '.mysqli_connect_error());
		}
		mysqli_query($con,"SET lc_time_names = 'es_ES'");
		mysqli_query($con,"SET NAMES 'UTF8'");
		
		return $con;
	}
	function disconnectdb($con) {
		mysqli_close($con);
	}
	function checkingQuery($connectBD, $q) {
		if(!$r = mysqli_query($connectBD, $q)) {
			die("Error (".$q."): " . mysqli_error($connectBD));
		}else {
			return $r;
		}
	}
?>