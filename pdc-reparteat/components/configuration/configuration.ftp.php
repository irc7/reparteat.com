<?php if (allowed ("Configuration")) { ?>
	<div class='cp_mnu_title title_header_mod'>Editar configuración FTP</div>
	<?php
		if (isset($_GET['msg']) && isset($_GET["error"])) {
			$msg = utf8_encode(trim($_GET['msg']));
			$error = abs(intval($_GET['error']));
			if($error == 0){
				echo "<div class='cp_info'>
						<img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' />".$msg."</div>\r\n";
			}else {
				echo "<div class='cp_alert error'>
						<img class='cp_msgicon' src='images/alert.png' alt='¡INFORMACIÓN!' />".$msg."</div>\r\n";
			}
			echo "<br/>\r\n";
		}
		$q = "select * from ".preBD."configuration where ID = 11";
		$result = checkingQuery($connectBD, $q);
		$configFTP = mysqli_fetch_object($result);
		
		$hostFTP = explode("#-HOST-#", $configFTP->TEXT);
		$userFTP = explode("#-USER-#", $configFTP->TEXT);
		$passFTP = explode("#-PASS-#", $configFTP->TEXT);
	?>
		<div class='cp_alert noerror' id='info-Host'></div>
		<div class='cp_alert noerror' id='info-User'></div>
		<div class='cp_alert noerror' id='info-Pass'></div>
		
	<form method='post' action='modules/configuration/save_ftp.php'  id='mainform' name='mainform'>
		<div id='boxSMPT' style='clear:both;padding-top:10px;'>
			<div class='cp_table' style='width:100%'>
				<div class='cp_formfield_l'>Servidor FTP:</div>
				<input type='text' id='Host' name='Host' title='Servidor FTP' value='<?php echo $hostFTP[1]; ?>' size='40' />
			</div>
			<div class='cp_table' style='width:100%'>
				<div class='cp_formfield_l'>Usuario: </div>
				<input type='text' id='User' name='User' title='Usuario' value='<?php echo $userFTP[1]; ?>' size='40' />
			</div>
			<div class='cp_table' style='width:100%'>
				<div class='cp_formfield_l'>Password: </div>
				<input type='text' name='Pass' id='Pass' title="Contraseña" value='<?php echo $passFTP[1]; ?>' size='40' />
			</div>
			<div class='cp_table' style='width:100%'>
				<div class='cp_formfield_l'>Directorio WEB: </div>
				<input type='text' name='Directory' id='Directory' title="Directorio WEB" value='<?php echo $configFTP->AUXILIARY; ?>' size='40' />
			</div>
		</div>
		<div class='cp_table'>
			<div class='cp_table150'>&nbsp;</div>
			<div class='cp_table'>
				<img class='image middle' src='images/loading.gif' style='visibility: hidden; padding: 10px 20px 0px 0px;' id='loading'>
			</div>
			<div class='cp_table' style="width:40px;">&nbsp;</div>
			<input type='button' name='save' value='Guardar' onclick='showloading(1);validate(this); return false;' />
		</div>
		<script type="text/javascript">
			includeField("Host", "string");
			includeField("User", "string");
			includeField("Pass", "string");
			
		</script>
	</form>
<?php
}else{
	echo "<p>No tiene permiso para acceder a esta sección.</p>";
}?>			