	
<!-- Page Heading -->
	<div class='container'>
		<div class='row'>
			<h1 class="h3 mb-2 text-gray-800">Nuevo proveedor</h1>
			<div class="separator10">&nbsp;</div>
			<div class="separator1 bgYellow">&nbsp;</div>
			<div class="separator20">&nbsp;</div>
			<p class="mb-4"></p>
		</div>
	</div>
	
	<form method='post' action='<?php echo DOMAINZP; ?>template/modules/supplier/create.php' id='userform' name='userform' enctype='multipart/form-data'>
		<input type="hidden" name="idZone" value="<?php echo $idZone; ?>" />
		<div class='container bgGrayNormal white'>
			<div class="separator10">&nbsp;</div>
			<div class="form-group">
				<label class="label-field white" for="status">Estado:</label>
				<select class="form-control form-s" name="status" id="status" title="Estado">
					<option value='1'>Activado</option>
					<option value='2' selected="selected">No disponible</option>
				</select>
			</div>
		</div>
		
		<div class="separator50">&nbsp;</div>
		<div class='container'>	
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
				<label class="label-field" for="Min">Tiempo para pedidos *:</label>
				<input type="number" name="Time" id="Time" class="form-control form-xs" title="Tiempo para pedidos" placeholder="Tiempo para pedidos *" required />
				<p id="error-Time"></p>
			</div>
			<div class="form-group">
				<label class="label-field" for="Extra">Extra pedidos por franja *:</label>
				<input type="number" name="Extra" id="Extra" class="form-control form-xs" title="Extra pedidos por franja" placeholder="Extra pedidos por franja *" value="0" step="1" required />
				<p id="error-Extra"></p>
			</div>
			<div class="form-group">
				<label class="label-field" for="Title">ID Telegram *:</label>
				<input type="text" name="IDTelegram" id="IDTelegram" class="form-control form-xs" title="ID Telegram" placeholder="ID Telegram *" required />
				<p id="error-IDTelegram"></p>
			</div>
		</div>
		<div class="separator30">&nbsp;</div>
		<div class='container bgWhite'>	
			<div class="separator20">&nbsp;</div>
			<label class="label-field green">ZONAS DE REPARTO</label>
			<div class="separator1 bgGrayLight">&nbsp;</div>
			<div class="separator10">&nbsp;</div>
			<div class="form-group">
				<label class="label-field orange" style="width:100%;"><i class="fa fa-exclamation-triangle orange iconBotton"></i> Para crear una zona de reparto primero tienes que guardar el proveedor.</label>
			</div>
<?php /*
			<div class="form-group">
				<label class="label-field" for="Street">Dirección:</label>
			</div>
			<div class="form-group">
				<input type="text" class="form-control form-m" name="Street" id="Street" title="Dirección postal" placeholder="Dirección postal" style="margin-right:10px;" />
				<select class="form-control form-s" name="Zone" id="Zone" title="Zona de reparto" disabled> 
				<?php foreach($zones as $zone) { ?>
					<option value="<?php echo $zone->ID; ?>"<?php if($zone->ID == $idZone){echo " selected";} ?>><?php echo $zone->CITY." (".$zone->CP.")"; ?></option>
					<?php } ?>
				</select>
			</div>
*/ ?>
		</div>
		
		<div class="separator30">&nbsp;</div>
		<div class='container bgWhite'>	
			<div class="separator20">&nbsp;</div>
			<label class="label-field green">Categorias</label>
			<div class="separator1 bgGrayLight">&nbsp;</div>
			<div class="separator10">&nbsp;</div>
			<?php foreach($categories as $cat) { ?>	
			<div class="col-md-4 col-xs-6 col-sm-12">
				<input type="checkbox" name="Category[]" title="Category" value="<?php echo $cat->ID; ?>" />
				<label for="Category"><?php echo $cat->TITLE; ?></label>
			</div>
			<?php } ?>	
		</div>
		<div class="separator30">&nbsp;</div>
<?php /*
		<div class='container bgWhite'>	
			<div class="separator20">&nbsp;</div>
			<label class="label-field green">Horario de reparto</label>
			<div class="separator1 bgGrayLight">&nbsp;</div>
			<div class="separator10">&nbsp;</div>	
			<div class='row'>
				<div class='col-xs-3 cp_title top'>
					<div class='textBoxBold textLeft'>Día de la semana</div>
				</div>
				<div class='col-xs-3 cp_title top'>
					<div class='textBoxBold textCenter'>Hora de inicio</div>
				</div>
				<div class='col-xs-1 cp_title top'>
					<div class='textBoxBold textLeft'>&nbsp;</div>
				</div>
				<div class='col-xs-3 cp_title top'>
					<div class='textBoxBold textCenter'>Hora final</div>
				</div>
				<div class='col-xs-2 cp_title top'>
					<div class='textBoxBold textLeft'>&nbsp;</div>
				</div>
			</div>
			<div class="separator20">&nbsp;</div>
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
*/ ?>		
		<div class="separator30">&nbsp;</div>
		<div class='container bgWhite'>	
			<div class="separator20">&nbsp;</div>
			<label class="label-field green">DISEÑO</label>
			<div class="separator1 bgGrayLight">&nbsp;</div>
			<div class="separator10">&nbsp;</div>
			<div class="form-group">
				<label class="label-field" for="Name">Eslogan:</label>
				<input type="text" name="Eslogan" id="Eslogan" class="form-control form-m" title="Eslogan" placeholder="Eslogan" />
				<p id="error-Name"></p>
			</div>
			<div class="form-group">
				<label class="label-field" for="View_img">Ver imágenes de productos:</label>
				<select class="form-control form-xs" name="View_img" id="View_img" title="Ver imágenes de productos en los listados"> 
					<option value="0">No</option>
					<option value="1">Si</option>
				</select>
				<p id="error-View_img"></p>
			</div>
			<div class="form-group">
				<label class="label-field" for="Text">Descripción:</label>
				<?php require_once("template/vendor/ckeditor/ckeditor.php"); ?>
				<div class='cp_table cp_height300' style='width:100%'>
					<textarea name='Text' id='Text'></textarea>
					<script>
						CKEDITOR.replace( 'Text' );
					</script>
				</div>
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
		<div class="separator20">&nbsp;</div>
		<div class="separator1 bgGrayNormal">&nbsp;</div>
		<div class="separator20">&nbsp;</div>
		<div class='container'>
			<button class="btn btn-primary transition floatRight bgGreen yellow" type='submit'>GUARDAR</button>
		</div>
	</form>
	<div class="separator50">&nbsp;</div>
	<script type="text/javascript">
	//Validacion del formulario		
		var validation_options = {
			form: document.getElementById("userform"),
			fields: [
				{
					id: "Name",
					type: "string",
					min: 2,
					max: 256
				},
				{
					id: "Surname",
					type: "string",
					min: 2,
					max: 256
				}
			]
		};
		var v2 = new Validation(validation_options);

	</script>