<?php if (allowed($mnu)) { ?>
		<div class='cp_mnu_title title_header_mod'>Sitemaps</div>
	<?php	if (isset($_GET['msg'])) {
			$msg = utf8_encode($_GET['msg']); ?>
			<div class='cp_table' style='display:block;clear:both;'>
				<img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' />
			</div>
			<div class='cp_info' style='margin-left:40px;'><?php echo $msg; ?></div>
		<?php }
	?>
	<script type="text/javascript">
		showloading(0);
	</script>

	<?php
	//GENERAR SITEMAPS	
	?>
		<div class='cp_box cp_height40'>
			<div style='margin-bottom:10px;'>
				<strong>Url sitemaps: </strong>
				&nbsp;
				<a href='<?php echo DOMAIN; ?>sitemap_index.xml' target='_blank'>
					<?php echo DOMAIN; ?>sitemap_index.xml
				</a>
			</div>
			<form method='post' action='modules/seo/create_sitemap.php' id='create_sitemap' name='create_sitemap'>
				<input type='hidden' name='action' id='action' value='regenerate' />
				<div class='cp_table cp_height30' style='width:100%;'>
					<input type='submit' value='Regenerar Sitemaps' onclick='showloading(1);' />
					<img class='image middle' src='images/loading.gif' style='visibility: hidden; padding: 10px 20px 0px 0px;' id='loading'>
				</div>
			</form>
		</div>
		<?php
}else{
	echo "<p>No tiene permiso para acceder a esta sección.</p>";
}?>			