<div class='container'>
	<div class='row'>
		<h1 class="h3 mb-2 text-gray-800">Editar zona de reparto - <?php echo $supplier->TITLE; ?></h1>
		<div class="separator10">&nbsp;</div>
		<div class="separator1 bgYellow">&nbsp;</div>
		<div class="separator20">&nbsp;</div>
		<p class="mb-4"></p>
	</div>
</div>

	<form method='post' action='<?php echo DOMAINZP; ?>template/modules/supplier/edit.delivery.php' enctype='multipart/form-data' id='mainform' name='mainform'>
		<input type="hidden" name="view" value="<?php echo $view; ?>" />
		<input type="hidden" name="mod" value="<?php echo $mod; ?>" />
		<input type="hidden" name="tpl" value="<?php echo $tpl; ?>" />
		<input type="hidden" name="idsupplier" value="<?php echo $sup; ?>" />
		<div class='container bgWhite'>	
			<div class="separator20">&nbsp;</div>
			<label class="label-field green">Zona de reparto</label>
			<div class="separator1 bgGrayLight">&nbsp;</div>
			<div class="separator10">&nbsp;</div>
			<div class="form-group">
				<label class="label-field" for="Active">Estado:</label>
				<select class="form-control form-s" name="Active" id="Active" title="Estado para el reparto"> 
					<option value="1"<?php if($addressBD->ACTIVE == 1){echo " selected";} ?> style="color:green;">Activada</option>
					<option value="0"<?php if($addressBD->ACTIVE == 0){echo " selected";} ?> style="color:red;">Desactivada</option>
				</select>
			</div>
			<div class="separator1 bgGrayLight"></div>
			<div class="form-group">
				<label class="label-field" for="Street">Dirección:</label>
			</div>
			<div class="form-group">
			<input type="text" class="form-control form-m" name="Street" id="Street" value="<?php echo $addressBD->STREET; ?>" title="Dirección postal" placeholder="Dirección postal" style="margin-right:10px;" />
				<select class="form-control form-s" name="Zone" id="Zone" title="Zona de reparto"> 
					<option value="<?php echo $zoneBD->ID; ?>" selected>
						<?php echo $zoneBD->CITY." (".$zoneBD->CP.")"; ?>
					</option>
				<?php foreach($zones as $zone) { 
						$enc = false;
						foreach($address as $ad) {
							if($ad->IDZONE == $zone->ID) {
								$enc = true;
								break;
							}
						}
							
				?>
					<option value="<?php echo $zone->ID; ?>"<?php if($enc){echo " disabled title='Ya hay una zona de reparto creada para este código postal'";} ?>>
						<?php echo $zone->CITY." (".$zone->CP.")"; ?>
					</option>
				<?php } ?>
				</select>
			</div>
			<div class="separator20">&nbsp;</div>	
	
			<div class="separator20">&nbsp;</div>
			<div class='form-group bgGrayLight'>
				<label class="label-field textBoxBold grayStrong" style="padding-left:15px;">Horarios de reparto</label>
			</div>
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
			<div class="separator5">&nbsp;</div>
			<div class="separator1 bgGrayLight">&nbsp;</div>
			<div class="separator20">&nbsp;</div>
	
			
			<?php //$dateNow = new DateTime(); ?>
			<div id="wrap-time-frame">
			<?php foreach($timeControl as $time) { ?>
				<div id="boxtime-control-id-<?php echo $time->ID; ?>" class="form-group box-time-control-id" data="timeframe-on">
					<div class="col-xs-3 no-padding">
						<select name="day-id-<?php echo $time->ID; ?>" id="day-id-<?php echo $time->ID; ?>" class="form-control form-m timeframe-day">
						<?php for($j=1;$j<=count($days);$j++) { ?>
							<option value="<?php echo $j;?>"<?php if($j == $time->DAY){echo " selected";} ?>><?php echo $days[$j-1]; ?></option>
						<?php } ?>
						</select>	
					</div>
					<div class="col-xs-3 no-padding">
						<div class="col-xs-5 no-padding">
							<input type="number" class="form-control form-l time-control-hs" name="start-h-id-<?php echo $time->ID; ?>" id="start-h-id-<?php echo $time->ID; ?>" minlength="2" maxlength="2" required value="<?php echo $time->START_H; ?>" />
						</div>
						<div class="col-xs-2 textCenter no-padding" style="font-size:18px;">:</div>
						<div class="col-xs-5 no-padding">
							<input type="number" class="form-control form-l time-control-hs" name="start-m-id-<?php echo $time->ID; ?>" id="start-m-id-<?php echo $time->ID; ?>"  minlength="2" maxlength="2" required value="<?php echo $time->START_M; ?>" />
						</div>
					</div>
					<div class="col-xs-1">&nbsp;</div>
					<div class="col-xs-3 no-padding">
						<div class="col-xs-5 no-padding">
							<input type="number" class="form-control form-l time-control-hs" name="finish-h-id-<?php echo $time->ID; ?>" id="finish-h-id-<?php echo $time->ID; ?>" minlength="2" maxlength="2" required value="<?php echo $time->FINISH_H; ?>" />
						</div>
						<div class="col-xs-2 textCenter no-padding" style="font-size:18px;">:</div>
						<div class="col-xs-5 no-padding">
							<input type="number" class="form-control form-l time-control-hs" name="finish-m-id-<?php echo $time->ID; ?>" id="finish-m-id-<?php echo $time->ID; ?>" minlength="2" maxlength="2" required value="<?php echo $time->FINISH_M; ?>" />
						</div>
					</div>
					<div class="col-xs-2">
						<i id="delete-time-control-id-<?php echo $time->ID; ?>" class="fa fa-trash grayStrong pointer deleteTimeFrameID" title="Eliminar" style="font-size:18px;"></i>
					</div>
					<div class="separator">&nbsp;</div>
					<hr>
				</div>
			<?php } ?>
			<?php for($i=1;$i<=20;$i++) { ?>
				<div id="boxtime-control-<?php echo $i; ?>" class="form-group box-time-control" data="timeframe-off" style='display:none;'>
					<div class="col-xs-3 no-padding">
						<select name="day-<?php echo $i; ?>" id="day-<?php echo $i; ?>" class="form-control form-m timeframe-day" disabled>
						<?php for($j=1;$j<=count($days);$j++) { ?>
							<option value="<?php echo $j;?>"><?php echo $days[$j-1]; ?></option>
						<?php } ?>
						</select>	
					</div>
					<div class="col-xs-3 no-padding">
						<div class="col-xs-5 no-padding">
							<input type="number" class="form-control form-l time-control-hs" name="start-h-<?php echo $i; ?>" id="start-h-<?php echo $i; ?>" disabled minlength="2" maxlength="2" required />
						</div>
						<div class="col-xs-2 textCenter no-padding" style="font-size:18px;">:</div>
						<div class="col-xs-5 no-padding">
							<input type="number" class="form-control form-l time-control-hs" name="start-m-<?php echo $i; ?>" id="start-m-<?php echo $i; ?>" disabled minlength="2" maxlength="2" required />
						</div>
					</div>
					<div class="col-xs-1">&nbsp;</div>
					<div class="col-xs-3 no-padding">
						<div class="col-xs-5 no-padding">
							<input type="number" class="form-control form-l time-control-hs" name="finish-h-<?php echo $i; ?>" id="finish-h-<?php echo $i; ?>" disabled minlength="2" maxlength="2" required />
						</div>
						<div class="col-xs-2 textCenter no-padding" style="font-size:18px;">:</div>
						<div class="col-xs-5 no-padding">
							<input type="number" class="form-control form-l time-control-hs" name="finish-m-<?php echo $i; ?>" id="finish-m-<?php echo $i; ?>" disabled minlength="2" maxlength="2" required />
						</div>
					</div>
					<div class="col-xs-2">
						<i id="delete-time-control-<?php echo $i; ?>" class="fa fa-trash grayStrong pointer deleteTimeFrame" title="Eliminar" style="font-size:18px;"></i>
					</div>
					<div class="separator">&nbsp;</div>
					<hr>
				</div>
			<?php } ?>
			</div>
			
			<div class="separator20">&nbsp;</div>
			<div class="col-xs-10">&nbsp;</div>
			<div class="col-xs-2">
				<i id="add-time-control" class="fa fa-plus-circle grayStyrong floatRight pointer" title="Agregar franja horaria" style="font-size:18px;"></i>
			</div>
			<div class="separator30">&nbsp;</div>
		</div>	
		<div class="separator50">&nbsp;</div>
		<div class='container bgWhite'>	
			<div class="separator20">&nbsp;</div>
			<label class="label-field green">REPARTIDORES</label>
			<div class="separator1 bgGrayLight">&nbsp;</div>
			<div class="separator10">&nbsp;</div>
			<div class="form-group">
				<?php foreach($userRep as $us) { 
						$enc = false;
						$ind = 0;
						for($i=0;$i<count($idRep);$i++){
							if($idRep[$i]->IDUSER == $us->ID){
								$enc = true;
								$ind = $i;
								break;
							}
						}
				?>
						<div class="col-sm-9">
							<input type="checkbox" name="Repartidor[]" title="Repartidor" value="<?php echo $us->ID; ?>" <?php if($enc){echo " checked";} ?> style="margin-right:15px;" />
							<label for="Category"><?php echo $us->SURNAME.", ".$us->NAME; ?></label>
						</div>
						<div class="col-sm-3">
							<input type="number" name="PosRep-<?php echo $us->ID; ?>" id="PosRep-<?php echo $us->ID; ?>" value="<?php if($enc){echo $idRep[$ind]->POSITION;}else{ echo "0";} ?>" style="float:right;text-align:right;" class="form-control"/>
						</div>
						<div class="separator10"></div>
						<div class="separator1 bgGrayLight"></div>
						<div class="separator10"></div>
				<?php } ?>	
			</div>
		</div>
		<div class="separator50">&nbsp;</div>
		<div class='container bgWhite'>	
			<div class="separator20">&nbsp;</div>
			<div class="separator1 bgGrayNormal">&nbsp;</div>
			<div class="separator20">&nbsp;</div>
			<div class='container'>
				<div class='col-md-6'>
					<?php $urlback = "index.php?view=supplier&mod=supplier&tpl=profile&sup=".$sup; ?>
					<a href="<?php echo  $urlback; ?>" class="btn btn-primary transition floatLeft bgGreen yellow"><i class="fa fa-arrow-circle-left iconBotton"></i> Volver</a>
				</div>
				<div class='col-md-6'>
					<button class="btn btn-primary transition floatRight bgGreen yellow" type='submit'>GUARDAR ZONA DE REPARTO</button>
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
				}
			]
		};
		var v2 = new Validation(validation_options);

	</script>
