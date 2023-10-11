	
<!-- Page Heading -->
<div class='container'>
	<div class='row'>
		<h1 class="h3 mb-2 text-gray-800">Editar <?php echo $supplier->TITLE; ?></h1>
		<div class="separator10">&nbsp;</div>
		<div class="separator1 bgYellow">&nbsp;</div>
		<div class="separator20">&nbsp;</div>
		<p class="mb-4"></p>
	</div>
</div>
	
	<form method='post' action='<?php echo DOMAINZP; ?>template/modules/supplier/edit.supplier.php' id='userform' name='userform'>
		<input type="hidden" name="id" value="<?php echo $sup; ?>" />
		<div class='container bgGrayNormal white'>
			<div class="separator10">&nbsp;</div>
			<div class="form-group">
				<label class="label-field white" for="status">Estado:</label>
				<select class="form-control form-s" name="status" id="status" title="Estado">
					<option value='1'<?php if($supplier->STATUS == 1){ echo ' selected="selected"';} ?>>Activado</option>
					<option value='2'<?php if($supplier->STATUS == 2){ echo ' selected="selected"';} ?>>No disponible</option>
				</select>
			</div>
		</div>
		
		<div class="separator50">&nbsp;</div>
		<div class='container'>	
			<div class="form-group">
				<label class="label-field" for="Title">Nombre *:</label>
				<input type="text" name="Title" id="Title" class="form-control form-s" title="Nombre" value="<?php echo $supplier->TITLE; ?>" value="Nombre *" />
				<p id="error-Title"></p>
			</div>
			<?php /*
			<div class="form-group">
				<label class="label-field" for="Cost">Gastos de envío *:</label>
				<input type="number" name="Cost" id="Cost" class="form-control form-xs" title="Gastos de envio" value="<?php echo $supplier->COST; ?>" step="0.01" required />
				<p id="error-Cost"></p>
			</div>
			*/ ?>
			<div class="form-group">
				<label class="label-field" for="Phone">Teléfono:</label>
				<input type="phone" class="form-control form-xs" name="Phone" id="Phone" title="Teléfono" value="<?php echo $supplier->PHONE; ?>" placeholder="Teléfono" />
				<p id="error-Phone"></p>
			</div>
			<div class="form-group">
				<label class="label-field" for="Movil">Móvil:</label>
				<input type="phone" class="form-control form-xs" name="MovilAux" id="MovilAux" title="Móvil" value="<?php echo $supplier->MOVIL; ?>" placeholder="Móvil" />
				<input type="hidden" name="Movil" id="Movil" value="<?php echo $supplier->MOVIL; ?>" />
				<p id="error-Phone"></p>
			</div>
			
			<div class="form-group">
				<label class="label-field" for="Min">Pedido mínimo *:</label>
				<input type="number" name="MinAux" id="MinAux" class="form-control form-xs" title="Pedido mínimo" value="<?php echo $supplier->MIN; ?>" step="0.01" required disabled />
				<input type="hidden" name="Min" id="Min" value="<?php echo $supplier->MIN; ?>" />
				<p id="error-Cost"></p>
			</div>
			<div class="form-group">
				<label class="label-field" for="Min">Tiempo para pedidos *:</label>
				<input type="number" name="Time" id="Time" class="form-control form-xs" title="Tiempo para pedidos" value="<?php echo $supplier->TIME; ?>" required />
				<p id="error-Time"></p>
			</div>
			<div class="separator20">&nbsp;</div>
			<div class='container bgWhite'>	
				<div class="separator20">&nbsp;</div>
				<label class="label-field green">ZONAS DE REPARTO</label>
				<div class="separator1 bgGrayLight">&nbsp;</div>
				<div class="separator10">&nbsp;</div>
				<?php if(count($address) == 0) { ?>
					<div class="form-group">
						<label class="label-field orange" style="width:100%;"><i class="fa fa-exclamation-triangle orange iconBotton"></i> Para crear una zona de reparto primero tienes que guardar el proveedor.</label>
					</div>
					<?php }else{ ?>
						<div class='row'>
							<div class='col-xs-3 cp_title top'>
								<div class='bold textLeft'><h5 class='textLeft'>Zona</h5></div>
							</div>
							<div class='col-xs-3 cp_title top'>
								<div class='bold textLeft'><h5 class='textLeft'>Día de la semana</h5></div>
							</div>
							<div class='col-xs-2 cp_title top'>
								<div class='bold textCenter'><h5 class='textCenter'>Inicio</h5></div>
							</div>
							<div class='col-xs-2 cp_title top'>
								<div class='bold textCenter'><h5 class='textCenter'>Fin</h5></div>
							</div>
							<div class='col-xs-2 cp_title top'>
								<div class='bold textCenter'><h5 class='textCenter'>Estado</h5></div>
							</div>
						</div>
						<div class="separator5">&nbsp;</div>
						<div class="separator1 bgGrayNormal">&nbsp;</div>
						<div class="separator10">&nbsp;</div>
						<?php foreach($address as $item){ 
							
								$zoneItem = $zObj->infoById($item->IDZONE);
								$timeControl = $supObj->supplierTimeControlZone($sup, $item->IDZONE);
						?>
								<div class='row'>
									<div class='col-xs-3'>
										<p class='textLeft <?php if($item->ACTIVE == 0){echo "danger";}else{echo "green";} ?>'><?php echo $zoneItem->CITY; ?></p>
									</div>
									<div class='col-xs-3'>
										<?php foreach($timeControl as $time){ ?>
											<p class='textLeft <?php if($item->ACTIVE == 0){echo "danger";}else{echo "green";} ?>'><?php echo $days[$time->DAY-1]; ?></p>
										<?php } ?>
									</div>
									<div class='col-xs-2 textCenter'>
										<?php foreach($timeControl as $time){ 
											if(strlen($time->START_H) == 1) {
												$time->START_H = "0". $time->START_H;
											}
											if(strlen($time->START_M) == 1) {
												$time->START_M = "0". $time->START_M;
											}	
										?>
											<p class='textCenter <?php if($item->ACTIVE == 0){echo "danger";}else{echo "green";} ?>'><?php echo $time->START_H . ":" . $time->START_M; ?></p>
										<?php } ?>
									</div>
									<div class='col-xs-2 textCenter'>
										<?php foreach($timeControl as $time){ 
											if(strlen($time->FINISH_H) == 1) {
												$time->FINISH_H = "0". $time->FINISH_H;
											}
											if(strlen($time->FINISH_M) == 1) {
												$time->FINISH_M = "0". $time->FINISH_M;
											}
											?>
											<p class='textCenter <?php if($item->ACTIVE == 0){echo "danger";}else{echo "green";} ?>'><?php echo $time->FINISH_H . ":" . $time->FINISH_M; ?></p>
										<?php } ?>
									</div>
									<div class='col-xs-2 textCenter'>
									<?php 
									
										if($item->ACTIVE == 1) {
											$colorIcon="green";
											$iconClass = "lock-open";
											$identificador = "zonareparto_".$item->ID;
											$urlAlert = DOMAINZP."template/modules/supplier/active.delivery.php?view=".$view."&mod=".$mod."&tpl=".$tpl."&supplier=".$sup."&zone=".$item->IDZONE."&address=".$item->ID."&action=close";
											$textAlert = "¡ATENCI&Oacute;N!";
											$textAlert2 = "Va a ponerse como <em style='color:var(--danger);'>NO DISPONIBLE</em> en la zona de reparto ".$zoneItem->CITY."-".$zoneItem->CP.". &iquest;Est&aacute; seguro?";
										?>
										<?php }else{
											$colorIcon="danger";
											$iconClass = "lock";
											$identificador = "zonareparto_".$item->ID;
											$urlAlert = DOMAINZP."template/modules/supplier/active.delivery.php?view=".$view."&mod=".$mod."&tpl=".$tpl."&supplier=".$sup."&zone=".$item->IDZONE."&address=".$item->ID."&action=open";
											$textAlert = "¡ATENCI&Oacute;N!";
											$textAlert2 = "Va a ponerse como <em style='color:var(--disponible);'>DISPONIBLE</em> en la zona de reparto ".$zoneItem->CITY."-".$zoneItem->CP.". &iquest;Est&aacute; seguro?";
										}
										?>
										<i id="btn-action-open-<?php echo $identificador; ?>" class="fa fa-<?php echo $iconClass ." ". $colorIcon; ?> pointer iconBotton transition btn-action-open" title='Eliminar zona de reparto'></i>
										<?php include("template/modules/alert/btn.action.tpl.php"); ?>
									</div>
								</div>
								<div class="separator1 bgGrayLight">&nbsp;</div>
								<div class="separator10">&nbsp;</div>
					<?php 	
							} 
						} 
				?>
				
			</div>
			<div class="separator30">&nbsp;</div>
			<div class='container bgWhite'>	
				<div class="separator20">&nbsp;</div>
				<label class="label-field green">CATEGORIAS</label>
				<div class="separator1 bgGrayLight">&nbsp;</div>
				<div class="separator10">&nbsp;</div>
				<div class="form-group">
				<?php foreach($categories as $cat) { 
						$catSelected = false;
						for($i=0;$i<count($cats);$i++){
							if($cats[$i]->IDCAT == $cat->ID){
								$catSelected = true;
								break;
							}
						}
				?>	
					<div class="col-md-4 col-xs-6 col-sm-12">
						<input type="checkbox" name="Category[]" title="Category" value="<?php echo $cat->ID; ?>"<?php if($catSelected){echo " checked";} ?> />
						<label for="Category" class="<?php if($catSelected){echo "green";} ?>"><?php echo $cat->TITLE; ?></label>
					</div>
				<?php } ?>	
				</div>
			</div>
			<div class="separator20">&nbsp;</div>
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