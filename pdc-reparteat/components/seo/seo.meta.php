<?php if (allowed($mnu)) { ?>
		<div class='cp_mnu_title title_header_mod'>Meta-etiquetas</div>
	<?php	
		if (isset($_GET['msg'])) {
			$msg = utf8_encode($_GET['msg']); ?>
			<div class='cp_info'>
				<img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' />
				<?php echo $msg; ?>
			</div>
		<?php }
	?>
	<script type="text/javascript">
		//showloading(0);
	</script>
		<div class='cp_alert noerror' id='info-verification'></div>	
			<form method='post' action='modules/seo/save_meta.php' id='mainform' name='mainform'>
				<div class='cp_box dotted cp_height70'>
					<?php
					$q = "select TEXT from ".preBD."configuration where ID = 4;";
					
					$result = checkingQuery($connectBD, $q);
					$row_text = mysqli_fetch_assoc($result);	
					?>
					<div class='cp_table bold top' style='width: 100px;'>
						<label for='keywords'>Keywords:</label>
					</div>
					<div class='cp_table'>
						<textarea name='keywords' id='keywords' style='width:570px;height:60px;'><?php echo $row_text["TEXT"]; ?></textarea>
					</div>
				</div>
			
				<div class='cp_box dotted cp_height70'>
					<?php
						$q = "select TEXT from ".preBD."configuration where ID = 5;";
						
						$result = checkingQuery($connectBD, $q);
						$row_text = mysqli_fetch_assoc($result);	
					?>
					<div class='cp_table bold top' style='width: 100px;'>
						<label for='description'>Description:</label>
					</div>
					<div class='cp_table'>
						<textarea name='description' id='description' style='width:570px;height:60px;'><?php echo $row_text["TEXT"]; ?></textarea>
					</div>
				</div>
				
				
			<!-- META-ETIQUETA GOOGLE VERIFICATION -->
				<div class='cp_box dotted cp_height50'>
					<?php 
					$q = "select TEXT from ".preBD."configuration where ID = 6;";
					
					$result = checkingQuery($connectBD, $q);
					$row_text = mysqli_fetch_assoc($result);	
					$meta_etiqueta = stripslashes($row_text["TEXT"]);
					?>
					<div class='cp_table bold top' style='width: 100px;'>
						<label for='verification'>Etiqueta verificación:</label>
					</div>
					<div class='cp_table'>
						<input type='text' name='verification' id='verification' title='Etiqueta de verificacion' value='<?php echo $meta_etiqueta; ?>' size='112' />
					</div>
				</div>									
			
				<div class='cp_box dotted cp_height70'>
					<?php 
						$q = "select TEXT from ".preBD."configuration where ID = 8;";
						
						$result = checkingQuery($connectBD, $q);
						$row_text = mysqli_fetch_assoc($result);	
					?>
					<div class='cp_table bold top' style='width: 100px;'>
						<label for='Other'>Otras:</label>
					</div>
					<div class='cp_table'>
						<textarea name='Other' id='Other' style='width:570px;height:60px;'><?php echo $row_text["TEXT"]; ?></textarea>
					</div>
				</div>
			
			
				<div class='cp_table' style="float:right !important">
					<div class='cp_table230 align_ie'>&nbsp;</div>
					<input type='button' value='Guardar' onclick='validate(this);' />
				</div>
			</form>
			<script type='text/javascript'>
				includeField('verification','string');
				//includeField('Text','string');
			</script>
		<?php
}else{
	echo "<p>No tiene permiso para acceder a esta sección.</p>";
}?>				