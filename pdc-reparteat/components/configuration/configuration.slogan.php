	<div class='cp_mnu_title title_header_mod'>Editar Eslogan</div>
	<?php
		if (isset($_GET['msg'])) {
			$msg = utf8_encode($_GET['msg']);
			echo "<div class='cp_info'><img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' />".$msg."</div>\r\n";
		}
	?>
	<script type="text/javascript">
		showloading(0);
	</script>
	
	<form method='post' action='modules/configuration/save_slogan.php' id='mainform' name='mainform'>
		<div class='cp_box dotted cp_height120'>
			<?php	
				$q = "select TEXT from ".preBD."configuration where ID = 1;";
				
				$result = checkingQuery($connectBD, $q);
				$row_text = mysqli_fetch_assoc($result);	
			?>	
			<div class='cp_table bold top' style='width: 70px;'>
				<label for='Text'>Texto:</label>
			</div>
			<div class='cp_table'>
				<textarea name='Text' id='Text' style='width:570px;height:100px;'><?php echo stripslashes($row_text["TEXT"]); ?></textarea>
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
		