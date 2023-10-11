<?php if (allowed($mnu)) { ?>
	<div class='cp_mnu_title title_header_mod'>Cambiar favicon</div>
	<?php 
	if (isset($_GET['msg'])) {
		$msg = utf8_encode($_GET['msg']); ?>
		<div class='cp_info'>
			<img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' />
			<p><?php echo $msg; ?></p>
		</div>
	<?php } ?>
	<div class='cp_formfield bold'>
		<label for='Image'>Icono actual:</label>
	</div>
	<div>
		<img src='../favicon.ico' height='16'/>
	</div>
	<form method='post' action='modules/configuration/change_favicon.php' enctype='multipart/form-data' id='mainform' name='mainform'>
		<div class='cp_table600'>
			<div class='cp_formfield bold'>
				<label for='Image'>Nuevo icono:</label>
			</div>
			<div class='cp_table350'>
				<input type='file' name='favicon' id='favicon' title='imagen' size='20' />
			</div>
			<div class='cp_table'>
				<img class='image middle' src='images/loading.gif' style='visibility: hidden; padding: 10px 20px 0px 0px;' id='loading'>
			</div>
		</div>
		
		<div class='cp_table'>
			<div class='cp_table150'>&nbsp;</div>
			<input type='button' name='save' value='Guardar' onclick='showloading(1);validate(this); return false;' />
		</div>
	</form>
	<?php
}else{
	echo "<p>No tiene permiso para acceder a esta sección.</p>";
}?>		