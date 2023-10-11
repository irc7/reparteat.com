<?php if (allowed($mnu)) { ?>
	<div class='cp_mnu_title title_header_mod'>Galer&iacute;as de im&aacute;genes</div>
    <div class="cp_box">
		<?php 
		if (isset($_GET['msg'])) {
			$msg = utf8_encode($_GET['msg']); ?>
			<div class='cp_info' style="margin-bottom:20px;">
				<img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' />
				<p><?php echo $msg; ?></p>
			</div>
		<?php } ?>
		<p>Estos valores afectan al modo en que se visualizarán las galerías de imágenes en los artículos.</p>
        <br/>
		<?php 
		//$q = "describe spl_slider_gallery where Field = 'HEIGHT' or Field = 'WIDTH'"; 
		$q = "show columns from ".preBD."images_gallery_style where Field = 'WIDTH_BOX' or Field = 'HEIGHT_BOX' or Field = 'WIDTH_IMAGE' or Field = 'WIDTH_THUMB' ";
		$q .= "or Field = 'HEIGHT_THUMB' or Field = 'PADDING' or Field = 'MARGIN' or Field = 'BORDER' or Field = 'BORDER_RADIUS' or Field = 'BACKGROUND'";

		$result = checkingQuery($connectBD, $q);
		
		$i=0;
		while($row = mysqli_fetch_array($result)) {
			if($i == 0){
				$width_box = $row['Default'];
			}
			if($i == 1){
				$height_box = $row['Default'];
			}
			if($i == 2){
				$width_image = $row['Default'];
			}
			if($i == 3){
				$width_thumb = $row['Default'];
			}			
			if($i == 4){
				$height_thumb = $row['Default'];
			}
			if($i == 5){
				$padding = $row['Default'];
			}
			if($i == 6){
				$margin = $row['Default'];
			}
			if($i == 7){
				$border = $row['Default'];
			}
			if($i == 8){
				$border_radius = $row['Default'];
			}
			if($i == 9){
				$background = $row['Default'];
			}			
			$i++;
		}		
		?>		
		<div class='cp_alert noerror' id='info-ancho_gal'></div>
		<div class='cp_alert noerror' id='info-alto_gal'></div>
		<div class='cp_alert noerror' id='info-ancho_image'></div>
		<div class='cp_alert noerror' id='info-ancho_min_image'></div>
		<div class='cp_alert noerror' id='info-alto_min_image'></div>
		<div class='cp_alert noerror' id='info-paddingg'></div>
		<div class='cp_alert noerror' id='info-marging'></div>
		<div class='cp_alert noerror' id='info-bordes'></div>
		<div class='cp_alert noerror' id='info-bordes_rad'></div>
		<div class='cp_alert noerror' id='info-fondo'></div>
		<form method='post' action='modules/configuration/change_style_gallery.php' enctype='multipart/form-data' id='mainform' name='mainform'>
			<div style="clear:both;">
				<div class='cp_formfield bold'>
					<label for='ancho_gal'>Ancho de galería:</label>
				</div>
				<div class='cp_table350'>
					<input type='text' value="<?php echo $width_box; ?>" name='ancho_gal' id='ancho_gal' title='Ancho de la galería' size='10' /> px
				</div>
				<div class='cp_table350'>
					<input type='hidden' value="<?php echo $width_box; ?>" name='ancho_gal_2' id='ancho_gal_2' title='ancho_gal_2' />
				</div>
			</div>
			
			<div style="clear:both;">
				<div class='cp_formfield bold'>
					<label for='alto_gal'>Alto de galería:</label>
				</div>
				<div class='cp_table350'>
					<input type='text' value="<?php echo $height_box; ?>" name='alto_gal' id='alto_gal' title='Alto de la galería' size='10' /> px
				</div>
				<div class='cp_table350'>
					<input type='hidden' value="<?php echo $height_box; ?>" name='alto_gal_2' id='alto_gal_2' title='alto_gal_2' />
				</div>
			</div>
			
			<div style="clear:both;">
				<div class='cp_formfield bold'>
					<label for='ancho_image'>Ancho máx. de imagen:</label>
				</div>
				<div class='cp_table350'>
					<input type='text' value="<?php echo $width_image; ?>" name='ancho_image' id='ancho_image' title='Ancho de la imagen' size='10' /> px
				</div>
				<div class='cp_table350'>
					<input type='hidden' value="<?php echo $width_image; ?>" name='ancho_image_2' id='ancho_image_2' title='ancho_image_2' />
				</div>
			</div>	

			<div style="clear:both;">
				<div class='cp_formfield bold'>
					<label for='ancho_min_image'>Ancho de miniatura:</label>
				</div>
				<div class='cp_table350'>
					<input type='text' value="<?php echo $width_thumb; ?>" name='ancho_min_image' id='ancho_min_image' title='Alto de la imagen' size='10' /> px
				</div>
				<div class='cp_table350'>
					<input type='hidden' value="<?php echo $width_thumb; ?>" name='ancho_min_image_2' id='ancho_min_image_2' title='ancho_min_image_2' />
				</div>
			</div>

			<div style="clear:both;">	
				<div class='cp_formfield bold'>
					<label for='alto_min_image'>Alto de miniatura:</label>
				</div>
				<div class='cp_table350'>
					<input type='text' value="<?php echo $height_thumb; ?>" name='alto_min_image' id='alto_min_image' title='Alto miniatura de la imagen' size='10' /> px
				</div>
				<div class='cp_table350'>
					<input type='hidden' value="<?php echo $height_thumb; ?>" name='alto_min_image_2' id='alto_min_image_2' title='alto_min_image_2' />
				</div>
			</div>	

			<div style="clear:both;">	
				<div class='cp_formfield bold'>
					<label for='paddingg'>Espaciado (padding):</label>
				</div>
				<div class='cp_table350'>
					<input type='text' value="<?php echo $padding; ?>" name='paddingg' id='paddingg' title='Padding' size='10' /> px
				</div>
				<div class='cp_table350'>
					<input type='hidden' value="<?php echo $padding; ?>" name='paddingg_2' id='paddingg_2' title='paddingg_2' />
				</div>
			</div>	

			<div style="clear:both;">
				<div class='cp_formfield bold'>
					<label for='marging'>Margen (margin):</label>
				</div>
				<div class='cp_table350'>
					<input type='text' value="<?php echo $margin; ?>" name='marging' id='marging' title='Márgenes' size='10' /> px
				</div>
				<div class='cp_table350'>
					<input type='hidden' value="<?php echo $margin; ?>" name='marging_2' id='marging_2' title='marging_2' />
				</div>
			</div>	

			<div style="clear:both;">
				<div class='cp_formfield bold'>
					<label for='bordes'>Bordes:</label>
				</div>
				<div class='cp_table350'>
					<input type='text' value="<?php echo $border; ?>" name='bordes' id='bordes' title='Bordes' size='10' /> px
				</div>
				<div class='cp_table350'>
					<input type='hidden' value="<?php echo $border; ?>" name='bordes_2' id='bordes_2' title='bordes_2' />
				</div>
			</div>	
			
			<div style="clear:both;">
				<div class='cp_formfield bold'>
					<label for='bordes_rad'>Curvatura (radius):</label>
				</div>
				<div class='cp_table350'>
					<input type='text' value="<?php echo $border_radius; ?>" name='bordes_rad' id='bordes_rad' title='Bordes de las esquinas' size='10' /> px
				</div>
				<div class='cp_table350'>
					<input type='hidden' value="<?php echo $border_radius; ?>" name='bordes_rad_2' id='bordes_rad_2' title='bordes_rad_2' />
				</div>
			</div>	

			<div style="clear:both;">
				<div class='cp_formfield bold'>
					<label for='fondo'>Color de fondo:</label>
				</div>
				<div class='cp_table350'>
					<input type='text' value="<?php echo $background; ?>" name='fondo' id='fondo' title='Color fondo' size='10' />#Hex
				</div>
				<div class='cp_table350'>
					<input type='hidden' value="<?php echo $background; ?>" name='fondo_2' id='fondo_2' title='fondo_2' />
				</div>
			</div>	
			
			<div style="clear:both;">
				<div class='cp_formfield bold'>&nbsp;</div>
				<input type='button' name='save' value='Guardar' onclick='showloading(1);validate(this); return false;' />
				<img class='image middle' src='images/loading.gif' style='visibility: hidden; padding: 10px 0px 0px 20px;' id='loading'>
			</div>
		</form>
	</div>
	<script type='text/javascript'>
		includeField('ancho_gal','number');
		includeField('alto_gal','number');
		includeField('ancho_image','number');
		includeField('ancho_min_image','number');		
		includeField('alto_min_image','number');
		includeField('paddingg','number');
		includeField('marging','number');
		includeField('bordes','number');		
		includeField('bordes_rad','number');
		includeField('fondo','string');		
	</script>
	<?php
}else{
	echo "<p>No tiene permiso para acceder a esta sección.</p>";
}?>		