<?php if (allowed ($mnu)) { ?>
	<div class='cp_mnu_title title_header_mod'>Nuevo zona</div>
	<?php
		if (isset($_GET['msg'])) {
			$msg = utf8_encode($_GET['msg']);
			echo "<div class='cp_info'><img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' /><p>".$msg."</p></div>\r\n";
		}
		require_once("includes/classes/Zone/class.Zone.php");
		$zone = new Zone();
		require_once("includes/classes/UserWeb/class.UserWeb.php");
		
		$userObj = new UserWeb();
		$userRes = array(); 
		$userRes = $userObj->listUserWebByType(5); 
	?>		
	<br/>
	<script src="<?php echo DOMAIN; ?>pdc-reparteat/js/checking.validation.js"></script>
	<script src="<?php echo DOMAIN; ?>pdc-reparteat/js/class.Validation.js"></script>
	<form method='post' action='modules/zone/create.php' enctype='multipart/form-data' id='mainform' name='mainform'>
		<input type="hidden" name="mnu" value="<?php echo $mnu; ?>" />
		<input type="hidden" name="com" value="<?php echo $com; ?>" />
		<input type="hidden" name="tpl" value="<?php echo $tpl; ?>" />
		<input type="hidden" name="opt" value="<?php echo $opt; ?>" />
		<div class='container container-admin darkshaded-space bgGrayNormal white'>
			<div class="separator10">&nbsp;</div>
			<div class="col-sm-6 col-xs-12">
				<div class="form-group">
					<label class="label-field white" for="status">Estado:</label>
					<select class="form-control form-s" name="status" id="status" title="Estado">
						<option value='1'>Activado</option>
						<option value='0' selected="selected">Desactivado</option>
					</select>
				</div>
			</div>
			<div class="col-sm-6 col-xs-12">
				<?php $typeZones = $zone->listTypeZone(); ?>
				<div class="form-group">
					<label class="label-field white" for="status">Tipo:</label>
					<select class="form-control form-s" name="Type" id="Type" title="Tipo">
					<?php foreach($typeZones as $t) { ?>}	
							<option value='<?php echo $t; ?>'><?php echo $t; ?></option>
					<?php } ?>
					</select>
				</div>
			</div>
		</div>
		<div class="separator20">&nbsp;</div>
		<div class='container container-admin'>
			<div class='row'>	
				<div class="form-group">
					<label class="label-field" for="City">Ciudad *:</label>
					<input type="text" name="City" id="City" class="form-control form-s" title="Ciudad" placeholder="Ciudad *" required />
					<p id="error-City"></p>
				</div>
				<div class="form-group">
					<label class="label-field" for="CP">Código postal *:</label>
					<input type="text" name="CP" id="CP" class="form-control form-xs" title="Código postal" placeholder="Código postal *" required />
					<p id="error-CP"></p>
				</div>
				<div class="form-group">
					<label class="label-field" for="Province">Provincia *:</label>
					<select class="form-control form-s" name="Province" id="Province" title="Provincia" required> 
					<?php 
						$q = "SELECT * FROM ".preBD."provinces";
						$rP = checkingQuery($connectBD, $q);
						while($row = mysqli_fetch_object($rP)) {
					?>
						<option value="<?php echo $row->PROVINCE; ?>"<?php if($row->ID == 6){echo " selected";}//ID = 6->Badajoz ?>>
							<?php echo $row->PROVINCE; ?>
						</option>
					<?php } ?>
					</select>
					<p id="error-Province"></p>
				</div>
				<div class="form-group">
					<label class="label-field" for="OrderLimit">Pedidos por repartidor *:</label>
					<input type="number" name="OrderLimit" id="OrderLimit" class="form-control form-xs" title="Pedidos por repartidor" placeholder="Pedidos por repartidor(min) *" step="1"  required />
					<p id="error-OrderLimit"></p>
				</div>
				<div class="form-group" style="display:none;">
					<label class="label-field" for="RepLimit">Límite repartidores *:</label>
					<input type="number" name="RepLimit" id="RepLimit" class="form-control form-xs" title="Límite repartidores" placeholder="Límite repartidores" step="1"  disabled />
					<p id="error-RepLimit"></p>
				</div>
				<div class="form-group">
					<label class="label-field" for="Shipping">Gastos de envío *:</label>
					<input type="number" name="Shipping" id="Shipping" class="form-control form-xs" title="Gastos de envio" placeholder="Gastos de envío *" step="0.01" value="0" />
					<p id="error-Shipping"></p>
				</div>
				<div class="form-group">
					<label class="label-field" for="time_delivery">Tiempo estimado para el reparto *:</label>
					<input type="number" name="time_delivery" id="time_delivery" class="form-control textRight form-xs" title="Tiempo estimado para el reparto" placeholder="20 *" required step="1" />
					<label class="label-field" for="time_delivery" style="margin-left:10px;"><em>minutos</em></label>
					<p id="error-time_delivery"></p>
				</div>	
				<div class="separator5">&nbsp;</div>
				<div class="form-group">
					<label class="label-field" for="time_check_order">Tiempo de chequeo de pedidos *:</label>
					<input type="number" name="time_check_order" id="time_check_order" class="form-control textRight form-xs" title="Tiempo de chequeo de pedidos" placeholder="7 *" required step="1" />
					<label class="label-field" for="time_check_order" style="margin-left:10px;"><em>minutos</em></label>
					<p id="error-time_delivery"></p>
				</div>	
				<div class="separator5">&nbsp;</div>
				<div class="form-group">
					<label class="label-field" for="time_orders_zones">Franjas horarias *:</label>
					<input type="number" name="time_orders_zones" id="time_orders_zones" class="form-control textRight form-xs" title="Franjas horarias" placeholder="1200 *" required step="1" />
					<label class="label-field" for="time_orders_zones" style="margin-left:10px;"><em>segundos</em></label>
					<p id="error-time_delivery"></p>
				</div>
			</div>
			<div class="separator30">&nbsp;</div>
			<div class='row dotted padding-space'>	
				<div class='cp_mnu_subtitle title_block'><span class="textBox grayStrong">Responsable/s de zona</span></div>
			<?php foreach($userRes as $us) { ?>
				<div class="col-md-4 col-xs-6 col-sm-12">
					<input type="checkbox" name="Responsable[]" title="Responsable" value="<?php echo $us->ID; ?>" />
					<label for="Responsable"><?php echo $us->SURNAME.", ".$us->NAME; ?></label>
				</div>
			<?php } ?>	
			</div>
			<div class="separator30">&nbsp;</div>
		</div>
		<div class='container container-admin'>
			<div class='row'>	
				<div class='col-md-5'>&nbsp;</div>
					<div class='col-md-2'>
					<img class='image middle' src='images/loading.gif' style='visibility: hidden; padding: 10px 20px 0px 0px;' id='loading'>
				</div>
				<div class='col-md-5'>
					<input class="btn tf-btn btn-default transition floatRight bgGreen white bold" type='submit' name='save' value='CREAR ZONA' />
				</div>
			</div>
		</div>
	</form>
	<div class="separator50">&nbsp;</div>
	<script type="text/javascript">
	/*/Validacion del formulario		
		var validation_options = {
			form: document.getElementById("mainform"),
			fields: [
				{
					id: "City",
					type: "string",
					min: 2,
					max: 150
				},
				{
					id: "CP",
					type: "string",
					min: 1,
					max: 5
				},
				{
					id: "OrderLimit",
					type: "number",
					min: 0,
					max: 99999
				}
			]
		};
		var v2 = new Validation(validation_options);
*/
	</script>
<?php 
}else{
	echo "<p>No tiene permiso para acceder a esta sección.</p>";
}?>	