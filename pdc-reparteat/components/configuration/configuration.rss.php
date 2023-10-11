<?php if (allowed($mnu)) { ?>
<div class='cp_mnu_title title_header_mod'>Configuración RSS</div>
	<?php 
	if (isset($_GET['msg'])) {
		$msg = utf8_encode($_GET['msg']); ?>
		<div class='cp_info'>
			<img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' />
			<p><?php echo $msg; ?></p>
		</div>
		<br/>
	<?php } ?>
	<div class='cp_alert noerror' id='info-rss_elements'></div>
	<div class='cp_alert noerror' id='info-caesura'></div>
	<form method='post' action='modules/configuration/save_rss_elements.php' id='mainform' name='mainform' enctype='multipart/form-data'>
		<?php
			$q = "select * from ".preBD."configuration where ID = 9 or ID = 12 order by ID asc";
			$result = checkingQuery($connectBD, $q);
			$rss = mysqli_fetch_object($result);	
			$imageRSS = mysqli_fetch_object($result);	
			$auxW = explode("#-width-#", $imageRSS->AUXILIARY);
			$width = $auxW[1]; 
			
			$auxH = explode("#-height-#", $imageRSS->AUXILIARY);
			$height = $auxH[1];
			
		?>		
		<div id="box_form" style="border:1px dotted #2F81BB;padding:10px;<?php if($imageRSS->TEXT == ""){echo "height:160px;";}else{echo "height:185px;";} ?>">
			<label for='rss_elements' style="margin-right:5px;">Nº elementos por RSS:</label>
			<input type='text' name='rss_elements' id='rss_elements' title="Número de elementos" value="<?php echo abs(intval($rss->VALUE)); ?>" style='width:40px;height:15px;text-align:right;padding-right:5px;' />
			<div style="clear:both">&nbsp;</div>
			<label for='caesura' style="margin-right:5px;">Cesura en entradas RSS:</label>
			<input type='text' name='caesura' id='caesura' title="Cesura del texto" value="<?php echo abs(intval($rss->AUXILIARY)); ?>" style='width:40px;height:15px;text-align:right;padding-right:5px;' />
			<div style="clear:both">&nbsp;</div>
			<input type="hidden" name="maxwidth" id="maxwidth" value="<?php echo $width; ?>" />
			<input type="hidden" name="maxheight" id="maxheight" value="<?php echo $height; ?>" />
			<?php if($imageRSS->TEXT == ""): ?>
				<label for="imageRSS" style="width:130px;margin-right:5px;">Imagen:</label>
				<input type="hidden" name="optImg" id="optImg" value="2" />
				<input type="file" name="imageRSS" id="imageRSS" title="Imagen RSS" />
				<p style="clear:both;padding-left:140px !important;font-size:10px;color:#c50016;font-style:italic;">
					Ancho máximo: <?php echo $width; ?> px.
					<br/>
					Alto máximo: <?php echo $height; ?> px.
				</p>
			<?php else: 
				$url = "../files/rss/image/".$imageRSS->TEXT;
				$size = getimagesize($url);
				$pro = $size[0]/$size[1];
				if($pro > 1) {
					$stlImg = "max-width:100px;";
				}else {
					$stlImg = "max-height:80px;";
				}
			?>
				<div class="cp_table" style="width:35%;padding-right:10%;">
					<label for="optImg" style="width:130px;margin-right:5px;">Mantener Imagen:</label>
					<input type="radio" name="optImg" id="optImg1" value="1" checked />
					<div style="clear:both">&nbsp;</div>
					<label for="optImg" style="width:130px;margin-right:5px;">Eliminar Imagen:</label>
					<input type="radio" name="optImg" id="optImg0" value="0" />
					<div style="clear:both">&nbsp;</div>
					<label for="imageRSS" style="width:130px;margin-right:5px;">Cambiar Imagen:</label>
					<input type="radio" name="optImg" id="optImg2" value="2" />
				</div>
				<div class="cp_table" style="width:50%;padding-left:5%;">
					<a href='<?php echo $url; ?>' class='lytebox' data-lyte-options='group:RSS'>
						<img src="<?php echo $url; ?>" style="<?php echo $stlImg; ?>" />
					</a>
				</div>
				<div style="clear:both">&nbsp;</div>
				<div id="box_img" style="display:none;clear:both">
					<input type="file" name="imageRSS" id="imageRSS" disabled title="Imagen RSS" style="margin-left:135px;" />
					<p style="clear:both;padding-left:140px !important;font-size:10px;color:#c50016;font-style:italic;">
						Ancho máximo: <?php echo $width; ?> px.
						<br/>
						Alto máximo: <?php echo $height; ?> px.
					</p>
				</div>
				<script type='text/javascript'>
					var $im = jQuery.noConflict();
					$im('#optImg2').click(function(){
					   $im('#box_img').show();
					   $im('#imageRSS').attr('disabled', false);
					   $im('#box_form').height(230);
					});
					$im('#optImg1').click(function(){
					   $im('#box_img').hide();
					   $im('#imageRSS').attr('disabled', true);
					   $im('#box_form').height(185);
					});
					$im('#optImg0').click(function(){
					   $im('#box_img').hide();
					   $im('#imageRSS').attr('disabled', true);
					   $im('#box_form').height(185);
					});

				</script>
			<?php endif; ?>	
			<div class='cp_table' style="width:100%;">
				<input type='button' value='Guardar' onclick='showloading(1);saveConfiguration(this);' style="float:right;"/>
				<div class='cp_table400 right top'>
					<img class='image middle' src='images/loading.gif' style='visibility: hidden; padding: 10px 20px 0px 0px;' id='loading'>
				</div>
			</div>
			<script type='text/javascript'>
				function saveConfiguration(form){
					includeField('rss_elements','number');
					includeField('caesura','number');					
					
					validate(form);
				}
			</script>
		</div>
	</form>		
	
	<form method="post" action='modules/configuration/create_rss.php' >
		<div class="bordes_punteados" style="height:330px; margin-bottom:20px;">
			<!-- Para mostrar las diferentes secciones-->
			<div class="cp_table_2" style="margin-top:20px;">
				<p><strong>Selección de secciones de contenido para el rss global.</strong></p>	
				<br/>			
				<?php 
					$secs = explode("#-RSS-#", $rss->TEXT);
					if(count($secs) == 1 && ($secs[0] != "" || $secs[0] == NULL)) {
						unset($secs[0]);
					}
					$u = "select * from ".preBD."articles_sections where true";
					
					for($i=0;$i<count($secs);$i++) {
						$u .= " and ID != " . $secs[$i];
					}
					$u .= " order by ID asc";
					$result4 = checkingQuery($connectBD, $u);
					
				?>
				<select id="origen" name="origen[]" multiple="multiple" style="width:300px; height:200px; float:left;">
				<?php while($row4 = mysqli_fetch_object($result4)){ ?>
					<option value="<?php echo $row4->ID; ?>"> <?php echo $row4->TITLE;  ?></option>
				<?php } ?>
				</select>

				<div class="botones">
					<input type="button" class="pasar" value="Agregar »">
					<br /><br />
					<input type="button" class="quitar" value="« Quitar">
				</div>
				<div class="">
					<?php
						if(count($secs) > 0) {
							$u2 = "select * from ".preBD."articles_sections where true";
							for($i=0; $i<count($secs);$i++) {
								if($i == 0 && count($secs) > 1) {
									$u2.= " and (ID = " . $secs[$i];
								}elseif($i == 0 && count($secs) == 1) {
									$u2.= " and ID = " . $secs[$i];
								}elseif($i > 0 && $i < count($secs)-1) {
									$u2.= " or ID = " . $secs[$i];
								}elseif($i > 0 && $i == count($secs)-1) {
									$u2.= " or ID = " . $secs[$i] . ")";
								}
							}
							$u2 .= " order by ID asc";
							$r = checkingQuery($connectBD, $u2);
							
						}
					?>
					<select name="destino[]" id="destino" multiple="multiple" style="width:300px; height:200px; float:left;">
						<?php while($row = mysqli_fetch_object($r)){ ?>
							<option value="<?php echo $row->ID ?>" selected="selected"> <?php echo $row->TITLE;  ?></option>
						<?php } ?>
					</select>
				</div>	
				
				<p style="float:left;clear:both;text-align:justify;font-weight:bold;font-style:italic;width:679px;position:relative;top:10px;">Para seleccionar más de un elemento a la vez de la lista, mantenga pulsada la tecla Ctrl (en Windows) o Cmd (en Macintosh).</p>																					
				
				<div class='cp_table200 right' style="width:620px;">
					<img class='image middle' src='images/loading.gif' style='visibility: hidden; padding: 10px 20px 0px 0px;' id='loading'>
				</div>
				<div class='cp_table20 save_ie2'>&nbsp;</div>
				<input type='submit' value='Guardar' />						
			</div>	
		</div>
	</form>		

	<script type="text/javascript">
		var $s = jQuery.noConflict();
		$s(document).ready(function(){
			$s('.pasar').click(function() { return !$s('#origen option:selected').remove().appendTo('#destino'); });
			$s('.quitar').click(function() { return !$s('#destino option:selected').remove().appendTo('#origen'); });
		});
	</script>
	
	<?php
}else{
	echo "<p>No tiene permiso para acceder a esta sección.</p>";
}?>		