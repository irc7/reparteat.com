<?php if (allowed($mnu)) { ?>	
	<div class='cp_mnu_title title_header_mod'>Google Analytics</div>

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
		showloading(0);
	</script>

		<form method='post' action='modules/statistics/save_google_analytics.php' id='mainform' name='mainform'>
			<div class='cp_box dotted'>
			<?php
				$q = "select TITLE, TEXT, VALUE from ".preBD."configuration where ID = 2;";
				$result = checkingQuery($connectBD, $q);
				$google = mysqli_fetch_object($result);	
			?>		
				<ul>
					<li>
						<label for='articleID'>ID Art. Cookies:</label>
						<input type='text' name='articleID' id='articleID' value="<?php echo intval($google->VALUE); ?>" style='width:40px;height:15px;text-align:right;padding-right:5px;' />
					</li>
					<li>
						<label for='Text'>Código parte 1:</label>
						<textarea name='Title' id='Title' style='width:500px;height:50px;'><?php echo stripslashes($google->TITLE); ?></textarea>
					</li>
					
					<li>
						<label for='Text'>Código parte 2:</label>
						<textarea name='Text' id='Text' style='width:500px;height:100px;'><?php echo stripslashes($google->TEXT); ?></textarea>
					</li>
					
				</ul>
			</div>
			<style type="text/css">
				ul {
					list-style:none;
					margin:0px;
				}
				li {
					display:block;
					clear:both;
					margin-bottom:15px;
					min-height:20px;
				}
				label {
					width:100px;
				}
			</style>
			
			<div class='cp_table'>
				<div class='cp_table400 right top'>
					<img class='image middle' src='images/loading.gif' style='visibility: hidden; padding: 10px 20px 0px 0px;' id='loading'>
				</div>
				<div class='cp_table230 align_ie'>&nbsp;</div>
				<input type='submit' value='Guardar' onclick='showloading(1); validate(this);' />
			</div>
			
		</form>
		<script type='text/javascript'>
			includeField('Title','string');
			includeField('Text','string');
		</script>
		<?php
}else{
	echo "<p>No tiene permiso para acceder a esta sección.</p>";
}?>					