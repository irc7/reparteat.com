<?php if (allowed($mnu)) { ?>
	<div class='cp_mnu_title title_header_mod'>Editar configuración de correo</div>
	<?php 
		if (isset($_GET['msg'])) {
			$msg = utf8_encode($_GET['msg']);
			echo "<div class='cp_info'><img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' />".$msg."</div>\r\n";
			echo "<br/>\r\n";
		}
	
	?>
		<div class='cp_alert noerror' id='info-NameFrom'></div>
		<div class='cp_alert noerror' id='info-Host'></div>
		<div class='cp_alert noerror' id='info-Mail'></div>
		<div class='cp_alert noerror' id='info-User'></div>
		<div class='cp_alert noerror' id='info-Pass'></div>
		<div class='cp_alert noerror' id='info-Port'></div>
		
	<form method='post' action='modules/configuration/save_mail.php'  id='mainform' name='mainform'>
		<div id='boxSMPT' style='clear:both;padding-top:10px;'>
			<div class='cp_table' style='width:100%'>
				<div class='cp_formfield_l'>Remitente:</div>
				<input type='text' id='NameFrom' name='NameFrom' title='Nombre del remitente' value='<?php echo NAMESEND; ?>' size='40' />
			</div>
			<div class='cp_table' style='width:100%'>
				<div class='cp_formfield_l'>Servidor SMTP:</div>
				<input type='text' id='Host' name='Host' title='Servidor SMTP' value='<?php echo MAILHOST; ?>' size='40' />
			</div>
			<div class='cp_table' style='width:100%'>
				<div class='cp_formfield_l'>E-mail:</div>
				<input type='text' id='Mail' name='Mail' title='E-mail' value='<?php echo MAILSEND; ?>' size='40' />
			</div>
			<div class='cp_table' style='width:100%'>
				<div class='cp_formfield_l'>Usuario: </div>
				<input type='text' id='User' name='User' title='Usuario' value='<?php echo USERHOST; ?>' size='40' />
			</div>
			<div class='cp_table' style='width:100%'>
				<div class='cp_formfield_l'>Password: </div>
				<input type='text' name='Pass' id='Pass' title="Contraseña" value='<?php echo PASSHOST; ?>' size='40' />
			</div>
			<div class='cp_table' style='width:100%'>
				<div class='cp_formfield_l'>Puerto: </div>
				<input type='text' name='Port' id='Port' title="Puerto" value='<?php echo PORTHOST; ?>' size='40' />
			</div>			
			<div class='cp_table' style='width:100%'>
				<div class='cp_formfield_l'>Seguridad: </div>
				<select id='Security' name='Security'>
					<option value="0" <?php if(SECURITYHOST == 0){ ?> selected <?php } ?>>Ninguna</option>
					<option value="1" <?php if(SECURITYHOST == "1"){ ?> selected <?php } ?>>SSL</option>
					<option value="2" <?php if(SECURITYHOST == "2"){ ?> selected <?php } ?>>TLS</option>
				</select>					
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
			includeField("NameFrom", "string");
			includeField("Host", "string");
			includeField("Mail", "email");
			includeField("User", "string");
			includeField("Pass", "string");
			includeField("Port", "string");			
		</script>
	</form>
<?php
}else{
	echo "<p>No tiene permiso para acceder a esta sección.</p>";
}?>		