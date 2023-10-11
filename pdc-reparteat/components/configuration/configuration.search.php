<?php if (allowed($mnu)) { ?>
<div class='cp_mnu_title title_header_mod'>Configuración Búsqueda en Secciones</div>
	<?php 
	if (isset($_GET['msg'])) {
		$msg = utf8_encode($_GET['msg']); ?>
		<div class='cp_info'>
			<img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' />
			<p><?php echo $msg; ?></p>
		</div>
	<?php } ?>
	
	<form method="post" action='modules/configuration/save_section_search.php' >
		<div class="bordes_punteados" style="height:330px; margin-bottom:20px;">
			<!-- Para mostrar las diferentes secciones-->
			<div class="cp_table_2" style="margin-top:20px;">
				<p><strong>Selección de secciones de contenido en las que se buscarán los términos introducidos en la búsqueda de la Web.</strong></p>	
				<br/>			
				<?php 
					$u = "select * from ".preBD."articles_sections where true and SEARCH = 0 and TYPE = 'article' order by ID asc";
					$result4 = checkingQuery($connectBD, $u);
					
				?>
				<select id="origen" name="origen[]" multiple="multiple" style="width:300px; height:200px; float:left;">
				<?php while($row4 = mysqli_fetch_object($result4)){ pre($row4); ?>
					<option value="<?php echo $row4->ID; ?>"> <?php echo $row4->TITLE;  ?></option>
				<?php } ?>
				</select>

				<div class="botones">
					<input type="button" class="pasar" value="Agregar »">
					<br /><br />
					<input type="button" class="quitar" value="« Quitar">
				</div>
				
				<?php 
					$u2 = "select * from ".preBD."articles_sections where true and SEARCH = 1 and TYPE = 'article' order by ID asc";
					$result42 = checkingQuery($connectBD, $u2);
					
				?>				
				<div class="">
					<select name="destino[]" id="destino" multiple="multiple" style="width:300px; height:200px; float:left;">
						<?php while($row = mysqli_fetch_object($result42)){ ?>
							<option value="<?php echo $row->ID ?>" selected="selected"> <?php echo $row->TITLE;  ?></option>
						<?php } ?>
					</select>
				</div>	
				
				<p style="clear:both; float:left;text-align:justify;font-weight:bold;font-style:italic;width:679px;position:relative;top:10px;">Para seleccionar más de un elemento a la vez de la lista, mantenga pulsada la tecla Ctrl (en Windows) o Cmd (en Macintosh).</p>																					
				
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