<?php if (allowed($mnu)) { ?>
	<div class='cp_mnu_title title_header_mod'>Imagen de sección</div>
	<div class="cp_box">
		<?php 
		if (isset($_GET['msg'])) {
			$msg = utf8_encode($_GET['msg']); ?>
			<div class='cp_info' style="margin-bottom:20px;">
				<img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' />
				<p><?php echo $msg; ?></p>
			</div>
		<?php } ?>
		<p>Establezca aquí los valores predeterminados con los que se crearán las imágenes de las secciones.</p>
      	<br/>
		<?php 
		//$q = "describe spl_slider_gallery where Field = 'HEIGHT' or Field = 'WIDTH'"; 
		$q = "show columns from ".preBD."articles_sections where Field = 'HEIGHT_IMAGE' or Field = 'WIDTH_IMAGE'";
		
		$result = checkingQuery($connectBD, $q);
		
		$i=0;
		while($row = mysqli_fetch_array($result)) {
			if($i == 0){
				$ancho = $row['Default'];
			}else{
				$alto = $row['Default'];
			}
			$i++;
		}
	?>
		<div class='cp_alert noerror' id='info-alto'></div>
		<div class='cp_alert noerror' id='info-ancho'></div>
		<form method='post' action='modules/configuration/change_style_section.php' enctype='multipart/form-data' id='mainform' name='mainform'>
			<div class='cp_formfield bold'>
				<label for='alto'>Alto de la imagen:</label>
			</div>
			<div class='cp_table350'>
				<input type='text' value="<?php echo $alto; ?>" name='alto' id='alto' title='Alto' size='10' /> px
			</div>
			<div class='cp_table350'>
				<input type='hidden' value="<?php echo $alto; ?>" name='alto_2' id='alto_2' title='alto_2' />
			</div>
			<div style="clear:both;"></div>
			
			<div class='cp_formfield bold'>
				<label for='ancho'>Ancho de la imagen:</label>
			</div>
			<div class='cp_table350'>
				<input type='text' value="<?php echo $ancho; ?>" name='ancho' id='ancho' title='Ancho' size='10' /> px
			</div>
			<div class='cp_table350'>
				<input type='hidden' value="<?php echo $ancho; ?>" name='ancho_2' id='ancho_2' title='ancho_2' />
			</div>
			<div style="clear:both;"></div>
		
			<div class='cp_formfield bold'>&nbsp;</div>
			<input type='button' name='save' value='Guardar' onclick='showloading(1);validate(this); return false;' />
			<img class='image middle' src='images/loading.gif' style='visibility: hidden; padding: 10px 0px 0px 20px;' id='loading' />
		</form>
    </div>
	<script type='text/javascript'>
		includeField('alto','number');
		includeField('ancho','number');
	</script>
	<?php
}else{
	echo "<p>No tiene permiso para acceder a esta sección.</p>";
}?>		