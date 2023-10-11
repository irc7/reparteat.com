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
	$error = NULL;
	$msg == "";
	
	if (!allowed("mailing")) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
	$q = "select count(*) as total from ".preBD."subscriptions_error";
	$Eresult = checkingQuery($connectBD, $q);
	$old_errors = mysqli_fetch_assoc($Eresult);
	if($old_errors["total"] > 0) {
		$qD = "TRUNCATE TABLE  `".preBD."subscriptions_error`";
		checkingQuery($connectBD, $qD);	
		
	}
	
	if($_POST) {
		$group = intval($_POST["group"]);
		
		if($_FILES["csv"]["error"] == 0 && (($_FILES["csv"]["type"] == "application/vnd.ms-excel") || ($_FILES["csv"]["type"] == "text/comma-separated-values"))) {
			preg_match("|\.([a-z0-9]{2,4})$|i", $_FILES["csv"]["name"], $ext);
			$ext[0] = str_replace(".", "", $ext[0]);
			$file = checkingExtFile($ext[0]);
			//pre($file);die();
			if($file["upload"] == 1){
				$file_name = formatNameFile($_FILES["csv"]["name"]);
				$temp_url = "../../../temp/".$file_name;
				move_uploaded_file($_FILES["csv"]["tmp_name"],$temp_url);
				$mails = array();
				$cont = 0;
				$mails_error = 0; 
				$r = 0;
				if (($gestor = fopen($temp_url, "r")) !== FALSE) {
					while (($data = fgetcsv($gestor, 1000, ";")) !== FALSE) {
						$num = count($data);
						for ($c=0; $c<$num; $c++) {
							if($c<2){
								$rows[$r][$c] = $data[$c];						
							}
						}
						$r++;
					}
					
				}
				fclose ($gestor);
				unlink($temp_url);
			}else{
				$error = "Image";
				$msg .= $file["msg"];
			}
		} else {
			$error = "Archive";
			$msg .= "El archivo seleccionado no tiene un formato válido.";
		}
		$cont = 0;
		$Mcont = 0;
		for($i=0;$i<count($rows);$i++) {
		
			if(!mb_check_encoding($rows[$i][1], 'UTF-8')){
				$rows[$i][1] = utf8_encode($rows[$i][1]); 
			}
			
			if(ereg("^([a-zA-Z0-9._]+)@([a-zA-Z0-9.-]+).([a-zA-Z]{2,4})$", $rows[$i][0])) {
				$mails[$cont] = $rows[$i];
				$cont++;
			}else {
				$Merror[$Mcont] = $rows[$i];
				$Merror[$Mcont]["row"] = $i+1;
				$Mcont++;
				$mails_error++;		
			}
		}
		
		if(count($mails) > 0) {
			$mails_ok = 0;
			$repeat = 0;
			for($i = 0;$i<count($mails);$i++) {
				$qC = "select count(*) as total from ".preBD."subscriptions where MAIL = '".$mails[$i][0]."' and IDGROUP = " . $group;
				
				$result = checkingQuery($connectBD, $qC);
				$rep = mysqli_fetch_assoc($result);
				
				if($rep["total"] > 0) {
					$repeat++;	
				} else {
					$q = "INSERT INTO ".preBD."subscriptions(`NAME`, `MAIL`, `IDGROUP`, `STATUS`) VALUES ";
					$q .= "('".$mails[$i][1]."','".$mails[$i][0]."','".$group."','1')";
					
					checkingQuery($connectBD, $q)
					$mails_ok++;
					
				}
			}
			$msg .= "<p>Registros insertados correctamente: ".$mails_ok."<br/>";
			$msg .= "Correos ya registrados: " . $repeat."<br/>";
			$msg .= "E-mails incorrectos: ".$mails_error."</p>";
		}
		
		if($mails_error > 0) {
			for($j = 0;$j<count($Merror);$j++) {
				$q = "INSERT INTO `".preBD."subscriptions_error`(`NAME`, `MAIL`, `ROW`) VALUES ('".$Merror[$j][1]."', '".$Merror[$j][0]."', '".$Merror[$j]["row"]."')";
				checkingQuery($connectBD, $q);
			}
		}
		disconnectdb($connectBD);
		$location = "Location: ../../index.php?mnu=mailing&com=newsletter&tpl=option&opt=suscription&msg=".utf8_decode($msg)."&filtergroup=".$group;
		header($location);
	}else {
		disconnectdb($connectBD);
		$msg = "se ha producidor un error en la importación, vuelva a intentarlo gracias";
		$location = "Location: ../../index.php?mnu=mailing&com=newsletter&tpl=option&opt=suscription&msg=".utf8_decode($msg);
		header($location);	
	}
	
?>