<?php if (allowed ($mnu)) { ?>
	<div class='cp_mnu_title title_header_mod'>Nuevo proveedor</div>
	<?php
		if (isset($_GET['msg'])) {
			$msg = utf8_encode($_GET['msg']);
			echo "<div class='cp_info'><img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' /><p>".$msg."</p></div>\r\n";
		}
		require_once("includes/classes/Zone/class.Zone.php");
		$zone = new Zone();
		$zones = array(); 
		$zones = $zone->listZones(); 
		require_once("includes/classes/UserWeb/class.UserWeb.php");
		require_once("includes/classes/Supplier/class.Category.php");
		$catObj = new Category();
		$categories = array(); 
		$categories = $catObj->allCategories(); 
		$userObj = new UserWeb();
		$userSup = array(); 
		$userSup = $userObj->listUserWebByType(2); 
		$userRep = array(); 
		$userRep = $userObj->listUserWebByType(3); 
	?>		
	<br/>
	<script src="<?php echo DOMAIN; ?>pdc-reparteat/js/checking.validation.js"></script>
	<script src="<?php echo DOMAIN; ?>pdc-reparteat/js/class.Validation.js"></script>
	<form method='post' action='modules/supplier/create.php' enctype='multipart/form-data' id='mainform' name='mainform'>
		<input type="hidden" name="mnu" value="<?php echo $mnu; ?>" />
		<input type="hidden" name="com" value="<?php echo $com; ?>" />
		<input type="hidden" name="tpl" value="<?php echo $tpl; ?>" />
		<input type="hidden" name="opt" value="<?php echo $opt; ?>" />
		<div class='container container-admin darkshaded-space bgGrayNormal white'>
			<div class="separator10">&nbsp;</div>
			
				<div class="form-group">
					<label class="label-field white" for="status">Estado:</label>
					<select class="form-control form-s" name="status" id="status" title="Estado">
						<option value='1'>Activado</option>
						<option value='2'>No disponible</option>
						<option value='0' selected="selected">Desactivado</option>
					</select>
				</div>
			
		</div>
		<div class="separator20">&nbsp;</div>
		<div class='container container-admin'>
			<div class='row'>	
				<div class="form-group">
					<label class="label-field" for="Title">Nombre *:</label>
					<input type="text" name="Title" id="Title" class="form-control form-s" title="Nombre" placeholder="Nombre *" required />
					<p id="error-Title"></p>
				</div>
				<div class="form-group">
					<label class="label-field" for="Proveedor">Proveedor *:</label>
					<select class="form-control form-s" name="Proveedor" id="Proveedor" title="Proveedor" required> 
					<?php foreach($userSup as $us) { ?>
						<option value="<?php echo $us->ID; ?>">
							<?php echo $us->SURNAME.", ".$us->NAME; ?>
						</option>
					<?php } ?>
					</select>
					<p id="error-Proveedor"></p>
				</div>
				<div class="form-group">
					<label class="label-field" for="Cost">Gastos de envío *:</label>
					<input type="number" name="Cost" id="Cost" class="form-control form-xs" title="Gastos de envio" placeholder="Gastos de envío *" step="0.01" required />
					<p id="error-Cost"></p>
				</div>
				<div class="form-group">
					<label class="label-field" for="Min">Pedido mínimo *:</label>
					<input type="number" name="Min" id="Min" class="form-control form-xs" title="Pedido mínimo" placeholder="Pedido mínimo *" step="0.01" required />
					<p id="error-Cost"></p>
				</div>
				<div class="form-group">
					<label class="label-field" for="Min">Tiempo para pedidos(min) *:</label>
					<input type="number" name="Time" id="Time" class="form-control form-xs" title="Tiempo para pedidos" placeholder="Tiempo para pedidos(min) *" required />
					<p id="error-Time"></p>
				</div>
				<div class="form-group">
					<label class="label-field" for="Title">ID Telegram *:</label>
					<input type="text" name="IDTelegram" id="IDTelegram" class="form-control form-xs" title="ID Telegram" placeholder="ID Telegram *" required />
					<p id="error-IDTelegram"></p>
				</div>
			</div>
			<div class="separator30">&nbsp;</div>
			<div class='row dotted padding-space'>	
				<div class='cp_mnu_subtitle title_block'><span class="textBox grayStrong">Repartidores</span></div>
			<?php foreach($userRep as $us) { ?>
				<div class="col-md-4 col-xs-6 col-sm-12">
					<input type="checkbox" name="Repartidor[]" title="Repartidor" value="<?php echo $us->ID; ?>" />
					<label for="Category"><?php echo $us->SURNAME.", ".$us->NAME; ?></label>
				</div>
			<?php } ?>	
			</div>
			<div class="separator30">&nbsp;</div>
			<div class='row dotted padding-space'>	
				<div class='cp_mnu_subtitle title_block'><span class="textBox grayStrong">Categorias</span></div>
			<?php foreach($categories as $cat) { ?>	
				<div class="col-md-4 col-xs-6 col-sm-12">
					<input type="checkbox" name="Category[]" title="Category" value="<?php echo $cat->ID; ?>" />
					<label for="Category"><?php echo $cat->TITLE; ?></label>
				</div>
			<?php } ?>	
			</div>
			<div class="separator30">&nbsp;</div>
			<div class='row dotted padding-space'>	
				<div class='cp_mnu_subtitle title_block'><span class="textBox grayStrong">Horario de reparto</span></div>
				<div class="separator20">&nbsp;</div>
				<div class='row'>
					<div class='col-xs-3 cp_title top'>
						<div class='bold textLeft'>Día de la semana</div>
					</div>
					<div class='col-xs-3 cp_title top'>
						<div class='bold textCenter'>Hora de inicio (24h)</div>
					</div>
					<div class='col-xs-1 cp_title top'>
						<div class='bold textLeft'>&nbsp;</div>
					</div>
					<div class='col-xs-3 cp_title top'>
						<div class='bold textCenter'>Hora final (24h)</div>
					</div>
					<div class='col-xs-2 cp_title top'>
						<div class='bold textLeft'>&nbsp;</div>
					</div>
				</div>
				<div class="separator20">&nbsp;</div>
				<?php //$dateNow = new DateTime(); ?>
				<div id="wrap-time-control">
				<?php for($i=1;$i<=20;$i++) { ?>
					<div id="boxtime-control-<?php echo $i; ?>" class="form-group box-time-control" data="<?php if($i==1){echo "timeframe-on";}else{echo "timeframe-off";} ?>"<?php if($i>1){echo " style='display:none;'";} ?>>
						<div class="col-xs-3 no-padding">
							<select name="day-<?php echo $i; ?>" id="day-<?php echo $i; ?>" class="form-control form-m timeframe-day"<?php if($i>1){echo " disabled";} ?>>
							<?php for($j=1;$j<=count($days);$j++) { ?>
								<option value="<?php echo $j;?>"><?php echo $days[$j-1]; ?></option>
							<?php } ?>
							</select>	
						</div>
						<div class="col-xs-3 no-padding">
							<div class="col-xs-5 no-padding">
								<input type="number" class="form-control form-l time-control-hs" name="start-h-<?php echo $i; ?>" id="start-h-<?php echo $i; ?>"<?php if($i>1){echo " disabled";} ?> minlength="2" maxlength="2" required />
							</div>
							<div class="col-xs-2 textCenter no-padding" style="font-size:18px;">:</div>
							<div class="col-xs-5 no-padding">
								<input type="number" class="form-control form-l time-control-hs" name="start-m-<?php echo $i; ?>" id="start-m-<?php echo $i; ?>" <?php if($i>1){echo " disabled";} ?>  minlength="2" maxlength="2" required />
							</div>
						</div>
						<div class="col-xs-1">&nbsp;</div>
						<div class="col-xs-3 no-padding">
							<div class="col-xs-5 no-padding">
								<input type="number" class="form-control form-l time-control-hs" name="finish-h-<?php echo $i; ?>" id="finish-h-<?php echo $i; ?>" <?php if($i>1){echo " disabled";} ?>  minlength="2" maxlength="2" required />
							</div>
							<div class="col-xs-2 textCenter no-padding" style="font-size:18px;">:</div>
							<div class="col-xs-5 no-padding">
								<input type="number" class="form-control form-l time-control-hs" name="finish-m-<?php echo $i; ?>" id="finish-m-<?php echo $i; ?>" <?php if($i>1){echo " disabled";} ?>  minlength="2" maxlength="2" required />
							</div>
						</div>
						<div class="col-xs-2">
						<?php if($i > 1) { ?>
							<i id="delete-time-control-<?php echo $i; ?>" class="fa fa-trash grayStrong pointer deleteTimeFrame" title="Eliminar" style="font-size:18px;"></i>
						<?php } ?>
						</div>
						<div class="separator">&nbsp;</div>
						<hr>
					</div>
				<?php } ?>
				</div>
				<div class="col-xs-10">&nbsp;</div>
				<div class="col-xs-2">
					<i id="add-time-control" class="fa fa-plus-circle grayStyrong floatRight pointer" title="Agregar franja horaria" style="font-size:18px;"></i>
				</div>
			</div>
			<div class="separator30">&nbsp;</div>
			<div class='row dotted padding-space'>	
				<div class='cp_mnu_subtitle title_block'><span class="textBox grayStrong">DATOS DE CONTACTO</span></div>
				<div class="form-group">
					<label class="label-field" for="Phone">Teléfono:</label>
					<input type="phone" class="form-control form-xs" name="Phone" id="Phone" title="Teléfono" placeholder="Teléfono" />
					<p id="error-Phone"></p>
				</div>
				<div class="form-group">
					<label class="label-field" for="Movil">Móvil:</label>
					<input type="phone" class="form-control form-xs" name="Movil" id="Movil" title="Móvil" placeholder="Móvil" />
					<p id="error-Phone"></p>
				</div>
				<div class="form-group">
					<label class="label-field" for="Street">Dirección:</label>
				</div>
				<div class="form-group">
					<input type="text" class="form-control form-m" name="Street" id="Street" title="Dirección postal" placeholder="Dirección postal" style="margin-right:10px;" />
					<select class="form-control form-s" name="Zone" id="Zone" title="Zona de reparto"> 
					<?php foreach($zones as $zone) { ?>
						<option value="<?php echo $zone->ID; ?>"><?php echo $zone->CITY." (".$zone->CP.")"; ?></option>
					<?php } ?>
					</select>
				</div>
			</div>
			<div class='row dotted padding-space'>	
				<div class='cp_mnu_subtitle title_block'><span class="textBox grayStrong">DISEÑO</span></div>
				<div class="form-group">
					<label class="label-field" for="Name">Eslogan:</label>
					<input type="text" name="Eslogan" id="Eslogan" class="form-control form-m" title="Eslogan" placeholder="Eslogan" />
					<p id="error-Name"></p>
				</div>
				<div class="form-group">
					<label class="label-field" for="Image">Descripción:</label>
					<?php require_once("js/jscripts/tiny_mce/tiny_mce.php"); ?>
					<textarea name='Text' id='Text' class='spl_editable' style="width:100%;"></textarea>
				</div>
				<div class="form-group">
					<label class="label-field" for="Image">Logo:</label>
					<input class="form-control form-l" type="file" name="Logo" id="Logo">
				</div>
				<div class="form-group">
					<div style="font-style:italic;color:#c00;font-size:11px;margin-left:20%;">
						Dimensiones optimas della logo 300 x 300px
					</div>
				</div>
				<div class="form-group">
					<label class="label-field" for="Image">Imagen:</label>
					<input class="form-control form-l" type="file" name="Image" id="Image">
				</div>
			</div>
			
		</div>
		<div class='container container-admin'>
			<div class='row'>	
				<div class='col-md-5'>&nbsp;</div>
					<div class='col-md-2'>
					<img class='image middle' src='images/loading.gif' style='visibility: hidden; padding: 10px 20px 0px 0px;' id='loading'>
				</div>
				<div class='col-md-5'>
					<input class="btn tf-btn btn-default transition floatRight bgGreen white bold" type='submit' name='save' value='CREAR PROVEEDOR' />
				</div>
			</div>
		</div>
	</form>
	<div class="separator50">&nbsp;</div>
	<script type="text/javascript">
	//Validacion del formulario		
		var validation_options = {
			form: document.getElementById("mainform"),
			fields: [
				{
					id: "Title",
					type: "string",
					min: 2,
					max: 256
				},
				{
					id: "Proveedor",
					type: "selectNumber",
					min: 1,
					max: 9999
				},
				{
					id: "IDTelegram",
					type: "string",
					min: 2,
					max: 50
				},
				{
					id: "Cost",
					type: "number",
					min: 0,
					max: 99999
				},
				{
					id: "Min",
					type: "number",
					min: 1,
					max: 120
				},
				{
					id: "Time",
					type: "number",
					min: 0,
					max: 99999
				}
			]
		};
		var v2 = new Validation(validation_options);

	</script>
<?php 
}else{
	echo "<p>No tiene permiso para acceder a esta sección.</p>";
}?>	