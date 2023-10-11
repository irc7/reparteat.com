<?php if (allowed ($mnu)){ ?>
<div class='cp_mnu_title title_header_mod'>Editar zona de reparto</div>
<?php
	require_once("includes/classes/Zone/class.Zone.php");
	$zoneObj = new Zone();
	$zones = array(); 
	$zones = $zoneObj->listZones(); 
	require_once("includes/classes/UserWeb/class.UserWeb.php");
	$userObj = new UserWeb();
	
	if (isset($_GET['msg'])) {
		$msg = utf8_encode($_GET['msg']);
		echo "<div class='container container-admin'><div class='cp_info'><img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' /><p>".$msg."</p></div></div>";
	}
	require_once("includes/classes/Supplier/class.Supplier.php");
	if((isset($_GET["supplier"]) && intval($_GET["supplier"]) > 0) ||(isset($_GET["zone"]) && intval($_GET["zone"]) > 0)) {
		$idsupplier = $_GET["supplier"];	
		$idzone = $_GET["zone"];	
	}else {
		$location = "index.php?mnu=".$mnu."&com=".$com."&tpl=option&opt=".$opt."&msg=".utf8_decode("No se ha seleccionado ningún proveedor o zona");
?>
		<script type="text/javascript">
			window.location.href = "<?php echo $location; ?>";
		</script>
<?php
	}
	$supObj = new Supplier();
	$supplier = $supObj->infoSupplierById($idsupplier);
	$address = $supObj->allSupplierAddress($idsupplier);
	$addressBD = $supObj->supplierAddressZone($idsupplier, $idzone);
	$zoneBD = $zoneObj->infoById($idzone);
	
	$userRep = array(); 
	$userRep = $userObj->listUserWebByType(3); 

	$timeControl = $supObj->supplierTimeControlZone($idsupplier, $idzone);

	$idRep = $supObj->infoSupplierUserPositionZone($idsupplier, $idzone, 'repartidor');
?>	
	<script src="<?php echo DOMAIN; ?>pdc-reparteat/js/checking.validation.js"></script>
	<script src="<?php echo DOMAIN; ?>pdc-reparteat/js/class.Validation.js"></script>
	<form method='post' action='modules/supplier/edit_delivery.php' enctype='multipart/form-data' id='mainform' name='mainform'>
		<input type="hidden" name="mnu" value="<?php echo $mnu; ?>" />
		<input type="hidden" name="com" value="<?php echo $com; ?>" />
		<input type="hidden" name="tpl" value="<?php echo $tpl; ?>" />
		<input type="hidden" name="opt" value="<?php echo $opt; ?>" />
		<input type="hidden" name="supplier" value="<?php echo $idsupplier; ?>" />
		<div class='container container-admin darkshaded-space bgGrayNormal white'>
			<div class="separator10">&nbsp;</div>
			<div class="form-group">
				<label class="label-field white" style="width:100%;">Editar zona de reparto para <?php echo $supplier->TITLE; ?></label>
			</div>
		</div>
		<div class="separator20">&nbsp;</div>		
		<div class='container container-admin'>	
			<div class='row dotted padding-space'>	
				<div class='cp_mnu_subtitle title_block'><span class="textBox grayStrong">Zona de reparto</span></div>
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
					<?php foreach($zones as $zone) { 
							$enc = false;
							foreach($address as $ad) {
								if($ad->IDZONE == $zone->ID) {
									$enc = true;
									break;
								}
							}
								
					?>
						<option value="<?php echo $zone->ID; ?>"
						<?php if($enc && $zone->ID != $idzone){
								echo " disabled title='Ya hay una zona de reparto creada para este código postal' style='color:#999;'";
							} else if($zone->ID == $idzone) {
								echo " selected";
							}
						?>>
							<?php echo $zone->CITY." (".$zone->CP.")"; ?>
						</option>
					<?php } ?>
					</select>
				</div>
				<div class="separator20">&nbsp;</div>
				<div class='form-group bgGrayLight'>
					<label class="label-field textBoxBold grayStrong" style="padding-left:15px;">Horarios de reparto</label>
				</div>
				<div class='row'>
					<div class='col-xs-3 cp_title top'>
						<div class='bold textLeft'>Día de la semana</div>
					</div>
					<div class='col-xs-3 cp_title top'>
						<div class='bold textCenter'>Hora de inicio</div>
					</div>
					<div class='col-xs-1 cp_title top'>
						<div class='bold textLeft'>&nbsp;</div>
					</div>
					<div class='col-xs-3 cp_title top'>
						<div class='bold textCenter'>Hora final</div>
					</div>
					<div class='col-xs-2 cp_title top'>
						<div class='bold textLeft'>&nbsp;</div>
					</div>
				</div>
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
				<div class="col-xs-10">&nbsp;</div>
				<div class="col-xs-2">
					<i id="add-time-control" class="fa fa-plus-circle grayStyrong floatRight pointer" title="Agregar franja horaria" style="font-size:18px;"></i>
				</div>
			</div>
			<div class="separator30">&nbsp;</div>
			<div class='row dotted padding-space'>	
				<div class='cp_mnu_subtitle title_block'><span class="textBox grayStrong">Repartidores</span><span class="textBox grayStrong" style="float:right;">Prioridad en pedidos</span></div>
				<div class="col-md-12 col-xs-12 col-sm-12">
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
		</div>
		<div class='container container-admin'>
			<div class='row'>	
				<div class='col-md-5'>
					<?php $urlback = "index.php?mnu=".$mnu."&com=".$com."&tpl=edit&id=".$idsupplier; ?>
					<a href="<?php echo  $urlback; ?>" class="btn tf-btn btn-default transition floatLeft bgGreen white bold"><i class="fa fa-arrow-circle-left white iconBotton"></i> Volver</a>
				</div>
					<div class='col-md-2'>
					<img class='image middle' src='images/loading.gif' style='visibility: hidden; padding: 10px 20px 0px 0px;' id='loading'>
				</div>
				<div class='col-md-5'>
					<input class="btn tf-btn btn-default transition floatRight bgGreen white bold" type='submit' name='save' value='GUARDAR ZONA DE REPARTO' />
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
				}
			]
		};
		var v2 = new Validation(validation_options);

	</script>
<?php 
}else{
	echo "<p>No tiene permiso para acceder a esta sección.</p>";
}?>	