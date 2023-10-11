<?php if (allowed($mnu)) { ?>
		<div class='cp_mnu_title title_header_mod'>Archivo robots.txt</div>
	<?php
		if (isset($_GET['msg'])) {
			$msg = utf8_encode($_GET['msg']); ?>
			<div class='cp_info'><img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' /><?php echo $msg; ?></div>
		<?php }
	?>
	<script type="text/javascript">
		showloading(0);
	</script>
	<form method='post' action='modules/seo/save_robots.php' id='mainform' name='mainform'>
		<div class='cp_box dotted cp_height120'>
			<?php 		
				$q = "select TEXT from ".preBD."configuration where ID = 7;";
				
				$result = checkingQuery($connectBD, $q);
				$row_text = mysqli_fetch_assoc($result);	
			?>
			<div class='cp_table bold top' style='width: 70px;'>
				<label for='Text'>Código:</label>
			</div>
			<div class='cp_table'>
				<textarea name='Text' id='Text' style='width:570px;height:100px;'><?php echo $row_text["TEXT"]; ?></textarea>
			</div>
		</div>
		
		
		<div class='cp_table'>
			<div class='cp_table400 right top'>
				<img class='image middle' src='images/loading.gif' style='visibility: hidden; padding: 10px 20px 0px 0px;' id='loading'>
			</div>
			<div class='cp_table230 align_ie'>&nbsp;</div>
			<input type='submit' value='Guardar' onclick='showloading(1); validate(this);' />
		</div>
	
	</form>
	<script type='text/javascript'>
		includeField('Text','string');
	</script>
		<?php
}else{
	echo "<p>No tiene permiso para acceder a esta sección.</p>";
}
?>	