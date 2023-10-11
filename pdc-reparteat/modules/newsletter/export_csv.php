<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	// CONNECT

	header("Content-type: text/plain; charset=utf-8");
	header("Content-Disposition: attachment; filename=".preBD."BD-" . date("d-m-Y") . "-destinatarios.csv");
	header("Content-Transfer-Encoding: binary");
	// header("Content-Length: ". $size);


	if (!allowed("mailing")) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
	checkingQuery($connectBD, "SET lc_time_names = 'es_ES'");
	checkingQuery($connectBD, "SET NAMES 'UTF8'");

	$error = NULL;

	$group = $_POST["destino"];

	$q = "select MAIL, NAME from ".preBD."subscriptions where STATUS = 1";
	$q.= " and (IDGROUP = '" . $group[0] . "'";
	for($i=1;$i<count($group);$i++) {
		$q.= " or IDGROUP = '" . $group[$i] . "'";
	}

	$q.= ") order by IDGROUP asc";
	//echo $q ."\n";
	$results = checkingQuery($connectBD, $q);

	$total = mysqli_num_rows($results);
	$i=0;
	$susc = array();
	while ($row = mysqli_fetch_object($results)) {
		$susc[$i] = $row;
		$i++;
	}

	$j = 0;
	$aux = array();
	if ($total > 0) {
		$rows = "";
		
		for ($i = 0; $i < count($susc); $i++) {
			$enc = false;
			for($j=0;$j<count($aux);$j++) {
				if($susc[$i]->MAIL == $aux[$j]->MAIL) {
					$enc = true;
				}
			}
			if(!$enc) {
				$rows .= $susc[$i]->MAIL . ";" . utf8_decode($susc[$i]->NAME) . "\n";
				$totalaux = count($aux); 
				$aux[$totalaux] = $susc[$i]; 
			}
		}
		$csv = $rows;
		
		echo $csv;
	}
	disconnectdb($connectBD);
?>