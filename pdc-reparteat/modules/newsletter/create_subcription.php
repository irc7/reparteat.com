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
	
	if (allowed("mailing")) {
		if ($_POST) {
			$Name = trim($_POST["Name"]);
			$email = trim($_POST["email"]);
			$group = $_POST["group"];
			
				$q = "select count(*) as total from ".preBD."subscriptions where IDGROUP = '" . $group. "' and MAIL = '".$email."'"; 
				
				$result = checkingQuery($connectBD, $q);
				$row = mysqli_fetch_assoc($result);
				
				if($row["total"] == 0) {
					$q = "INSERT INTO ".preBD."subscriptions (NAME, MAIL, IDGROUP, STATUS) VALUES ('" . $Name. "', '" . $email. "', '" . $group . "', '1')";
					checkingQuery($connectBD, $q);
					$msg = "Destinatario <em>".$email."</em> creado correctamente";
				}else{
					$msg = "El e-mail ya está incluido en el grupo seleccionado";
				}
				disconnectdb($connectBD);
				$location = "Location: ../../index.php?mnu=mailing&com=newsletter&tpl=option&opt=suscription&msg=".utf8_decode($msg);
				header($location);
			
			disconnectdb($connectBD);
		}
	} else {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
	
?>