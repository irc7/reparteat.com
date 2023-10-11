	
<!-- Page Heading -->
	<div class='container'>
		<div class='row'>
			<h1 class="h3 mb-2 text-gray-800">Control límite de pedidos</h1>
			<div class="separator10">&nbsp;</div>
			<div class="separator1 bgYellow">&nbsp;</div>
			<div class="separator20">&nbsp;</div>
			<p class="mb-4"></p>
		</div>
	</div>
	
	<form method='post' action='<?php echo DOMAINZP; ?>template/modules/zone/edit.php' id='userform' name='userform' enctype='multipart/form-data'>
		<input type="hidden" name="idZone" value="<?php echo $idZone; ?>" />
		
		<div class='container'>
			
			<div class="form-group">
				<label class="label-field" for="OrderLimit">Pedidos por repartidor *:</label>
				<input type="number" name="OrderLimit" id="OrderLimit" class="form-control form-xs" title="Pedidos por repartidor" placeholder="Pedidos por repartidor(min) *" step="1"  required value="<?php echo $zone->ORDER_LIMIT; ?>" />
				<p id="error-OrderLimit"></p>
			</div>
			<div class="form-group">
				<label class="label-field" for="RepLimit">Nº repartidores *:</label>
				<input type="number" name="RepLimit" id="RepLimit" class="form-control form-xs" title="Límite repartidores" placeholder="Límite repartidores" step="1"  required value="<?php echo $zone->REP_LIMIT; ?>"/>
				<p id="error-RepLimit"></p>
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