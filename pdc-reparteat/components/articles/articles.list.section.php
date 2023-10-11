<div class="cp_box">
	<div class='cp_table250 cp_title'>Sección</div>
	<div class='cp_table60 cp_title'>Art/P&aacute;g</div>
	<div class='cp_table110 cp_title'>Ancho imágenes</div>
	<div class='cp_table120 cp_title'>Tama&ntilde;o miniatura</div>
	<div class='cp_table110 cp_title'>Imagen sección</div>
	<?php	
	$i=0;
	while($i<2) { ?>
		<div class='cp_table25 cp_title'>&nbsp;</div>
		<?php 
		$i++;
	} ?>
	<br/>
	<br/>
	<?php 
	$q = "SELECT * FROM ".preBD."articles_sections where TYPE = 'article' ORDER BY TITLE ASC";
	$result = checkingQuery($connectBD, $q);
	while($row = mysqli_fetch_array($result)) {
		$section = $row['ID']; ?>
		<div class='cp_table700'>
			<div class='cp_table250'>
				<a href='index.php?mnu=content&com=articles&tpl=option&opt=section&section=<?php echo $section; ?>&action=Editsection#Editsection' title='<?php echo $row['DESCRIPTION']; ?>'>
					<?php echo $row['TITLE']; ?>
				</a>
			</div>
			<?php 
				if (allowed($mnu) != 1) { ?>
					<div class='cp_table60'><?php echo $row['VIEW_ARTICLES']; ?></div>
					<div class='cp_table60'>i/d: <?php echo $row['IMAGE_LR']; ?></div>
					<div class='cp_table60'>c: <?php echo $row['IMAGE_C']; ?></div>
					<div class='cp_table60'>a: <?php echo $row['THUMB_WIDTH']; ?></div>
					<div class='cp_table60'>h: <?php echo $row['THUMB_HEIGHT']; ?></div>
				<?php }	else { ?>
					<div class='cp_table60'><a href='index.php?mnu=content&com=articles&tpl=option&opt=section&section=<?php echo $section; ?>&action=Changerecords#Changerecords'><?php echo $row['VIEW_ARTICLES']; ?></a></div>
					<div class='cp_table50'><a href='index.php?mnu=content&com=articles&tpl=option&opt=section&section=<?php echo $section; ?>&action=Changeimage#Changeimage'>i/d: <?php echo $row['IMAGE_LR']*ESCALE_IMG; ?></a></div>
					<div class='cp_table70'><a href='index.php?mnu=content&com=articles&tpl=option&opt=section&section=<?php echo $section; ?>&action=Changeimage#Changeimage'>c: <?php echo $row['IMAGE_C']*ESCALE_IMG; ?></a></div>
					<div class='cp_table50'><a href='index.php?mnu=content&com=articles&tpl=option&opt=section&section=<?php echo $section; ?>&action=Changethumb#Changethumb'>a: <?php echo $row['THUMB_WIDTH']*ESCALE_THUMB; ?></a></div>
					<div class='cp_table60'><a href='index.php?mnu=content&com=articles&tpl=option&opt=section&section=<?php echo $section; ?>&action=Changethumb#Changethumb'>h: <?php echo $row['THUMB_HEIGHT']*ESCALE_THUMB; ?></a></div>
			<?php } ?>
			<div class='cp_table155'>
				<?php 
				if (allowed($mnu) != 1) { ?>
					<div class='cp_table25'>
						<img class='image' src='images/icon_img_off.png' alt='' title='No tiene permisos para realizar esta acción' />
					</div>
				<?php }else { ?>
					<div class='cp_table25'>
						<a href='index.php?mnu=content&com=articles&tpl=option&opt=section&section=<?php echo $section; ?>&action=Editimagesection#Editimagesection'>
							<img class='image' src='images/icon_img.png' alt='Editar Imagen' title='Editar Imagen' />
						</a>
					</div>
					<div class='cp_table40'><a href='index.php?mnu=content&com=articles&tpl=option&opt=section&section=<?php echo $section; ?>&action=Changesizeimage#Changesizeimage'>a: <?php echo $row['WIDTH_IMAGE']; ?></a></div>
					<div class='cp_table50'><a href='index.php?mnu=content&com=articles&tpl=option&opt=section&section=<?php echo $section; ?>&action=Changesizeimage#Changesizeimage'>h: <?php echo $row['HEIGHT_IMAGE']; ?></a></div>
					
				<?php }	?>
				<div class='cp_table20'><a href="<?php echo DOMAIN . formatNameUrl(stripslashes($row["TITLE"])); ?>_as<?php echo $row['ID']; ?>.html" target="_blank"><img class='image' src='images/link.png'  alt='Abrir en nueva ventana' title='Abrir en nueva ventana' /></a></div>				
				<?php
				$totalArt = checkingSection("articles", "IDSECTION", $section);
				if (allowed($mnu) != 1) { ?>
					<div class='cp_table20'>
						<img class='image' src='images/delete_off.png' alt='' title='' />
					</div>
				<?php }elseif ($totalArt > 0) { ?>
					<div class='cp_table20'>
						<img class='image' src='images/delete_off.png' alt='' title='Debe vaciar la sección para poder eliminarla' />
					</div>
				<?php }	else { 
					$urlAlert = "modules/articles/delete_section.php?section=".$section;
					$msgAlert = "¡ATENCIÓN! Va a eliminar la sección: ". stripslashes($row['TITLE']);
				?>
					<div class='cp_table20'>
						<img class='image pointer' src='images/delete.png' alt='Eliminar sección' title='Eliminar sección' onclick="alertConfirm('<?php echo $msgAlert; ?>', '<?php echo $urlAlert; ?>')" />
					</div>
				<?php } ?>
			</div>
		</div>
		<br/>
		
	<?php } ?>
	<br/>
	<div class='cp_table300'>&nbsp;</div>
	<div class='cp_table70'>&nbsp;</div>
	<div class='cp_table140'>&nbsp;</div>
	<div class='cp_table140'>&nbsp;</div>
	<div class='cp_table25'>&nbsp;</div>
	<?php 
		if (allowed($mnu) == 1) { ?>
		<div class='cp_table25 new_section_ie'><a href='index.php?mnu=content&com=articles&tpl=option&opt=section&action=Createsection#Createsection'><img class='image' src='images/add.png' alt='Crear sección' title='Crear sección' /></a></div>
	<?php }
	else { ?>
		<div class='cp_table25 new_section_ie'><img class='image' src='images/add_off.png' alt='' title='' /></div>
	<?php } ?>
	<br/>
	<br/>
</div>