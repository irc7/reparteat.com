<?php if (allowed($mnu)) { ?>
		<div class='cp_mnu_title title_header_mod'>Editar t&iacute;tulo home</div>
	<?php	
		if (isset($_GET['msg'])) {
			$msg = utf8_encode($_GET['msg']);
			echo "<div class='cp_info'><img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' />".$msg."</div>\r\n";
		}
		$q = "select TEXT, AUXILIARY from ".preBD."configuration where ID = 3";
		
		$result = checkingQuery($connectBD, $q);
		$title = mysqli_fetch_object($result);		
	?>
	<script type="text/javascript">
		showloading(0);
	</script>

			<form method='post' action='modules/seo/save_title_home.php' id='mainform' name='mainform'>
				<div class='cp_box dotted cp_height40'>
					<div class='cp_table bold top' style='width: 70px;'>
						<label for='Text'>&lt;titleSEO&gt;</label>
					</div>
					<div class='cp_table'>
						<input type='text' name='title_home' id='title_home' value='<?php echo stripslashes($title->TEXT); ?>' size='105' />
						<div class='cp_table bold top' style='width: 70px;'>
							<label for='Text'>&lt;/titleSEO&gt;</label>
						</div>
					</div>
				</div>
				
				<div class='cp_box dotted cp_height40'>
					<div class='cp_table bold top' style='width: 70px;'>
						<label for='Text'>&lt;title&gt;</label>
					</div>
					<div class='cp_table'>
						<input type='text' name='title_home2' id='title_home2' value='<?php echo stripslashes($title->AUXILIARY); ?>' size='105' />
						<div class='cp_table bold top' style='width: 70px;'>
							<label for='Text'>&lt;/title&gt;</label>
						</div>
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
}?>					