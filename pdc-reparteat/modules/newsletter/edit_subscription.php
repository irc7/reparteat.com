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
	
	if (!allowed("mailing")) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
	if ($_POST) {
		connectdb();
		
		$Name = trim($_POST["Name"]);
		$email = trim($_POST["email"]);
		$group = $_POST["group"];
		$id = $_POST["id"];
		
			$q = "select count(*) as total from ".preBD."subscriptions where IDGROUP = " . $group;
			$q .= " and MAIL = '" . $email;
			$q .= "' and ID != " . $id;
			
			$result = checkingQuery($connectBD, $q);
			$exits =mysqli_fetch_assoc($result);
			
			if($exits["total"] != 0) {
				$msg = "El e-mail ya esta suscrito en el grupo seleccionado";
			} else {
				$q = "select * from ".preBD."subscriptions where ID = " . $id;
				
				$result = checkingQuery($connectBD, $q);
				$subs = mysqli_fetch_assoc($result);
				
				if(checking_email($email) == 0) {
					$msg = "El e-mail no es un correo electrónico válido";
				}else {
					$q = "UPDATE ".preBD."subscriptions SET"; 
						$q .= " STATUS = '" . $subs["STATUS"];
						if($subs["MAIL"] != $email) {
							$q .= "', MAIL = '" . $email;
						}
						if($subs["IDGROUP"] != $group) {
							$q .= "', IDGROUP = '" . $group;
						}
						if($subs["NAME"] != $Name) {
							$q .= "', NAME = '" . $Name;
						}
					$q .= "' WHERE ID = " . $id;
					
					checkingQuery($connectBD, $q);
					$msg = "Destinatario <em>".$email."</em> modificado correctamente.";	
				}
			}
		
			$location = "Location: ../../index.php?mnu=mailing&com=newsletter&tpl=option&opt=suscription&msg=".utf8_decode($msg);
			header($location);
		
		disconnectdb($connectBD);
	}
	
?>