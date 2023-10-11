<?php if (allowed ($mnu)) { ?>
	<div class='cp_mnu_title title_header_mod'>Nueva zona de reparto</div>
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
		require_once("includes/classes/Supplier/class.Supplier.php");
		require_once("includes/classes/Supplier/class.Category.php");
		
		if(isset($_GET["supplier"]) && intval($_GET["supplier"]) > 0) {
			$idsupplier = $_GET["supplier"];	
		}else {
			$location = "index.php?mnu=".$mnu."&com=".$com."&tpl=option&opt=".$opt."&msg=".utf8_decode("No se ha seleccionado ningún proveedor");
			?>
			<script type="text/javascript">
				window.location.href = "<?php echo $location; ?>";
			</script>
	<?php
		}
		$supObj = new Supplier();
		$supplier = $supObj->infoSupplierById($idsupplier);
		$address = $supObj->allSupplierAddress($idsupplier);
		//$timeControl = $supObj->supplierTimeControl($id);
		$userObj = new UserWeb();
		$userRep = array(); 
		$userRep = $userObj->listUserWebByType(3); 
	?>		
	<br/>
	<script src="<?php echo DOMAIN; ?>pdc-reparteat/js/checking.validation.js"></script>
	<script src="<?php echo DOMAIN; ?>pdc-reparteat/js/class.Validation.js"></script>
	<form method='post' action='modules/supplier/create_delivery.php' enctype='multipart/form-data' id='mainform' name='mainform'>
		<input type="hidden" name="mnu" value="<?php echo $mnu; ?>" />
		<input type="hidden" name="com" value="<?php echo $com; ?>" />
		<input type="hidden" name="tpl" value="<?php echo $tpl; ?>" />
		<input type="hidden" name="opt" value="<?php echo $opt; ?>" />
		<input type="hidden" name="idsupplier" value="<?php echo $idsupplier; ?>" />
		<div class='container container-admin darkshaded-space bgGrayNormal white'>
			<div class="separator10">&nbsp;</div>
			<div class="form-group">
				<label class="label-field white" style="width:100%;">Crear zona de reparto para <?php echo $supplier->TITLE; ?></label>
			</div>
		</div>
		<div class="separator20">&nbsp;</div>
		<div class='container container-admin'>
			<div class='row dotted padding-space'>	
				<div class='cp_mnu_subtitle title_block'><span class="textBox grayStrong">ZONA DE REPARTO</span></div>
				<div class="form-group">
					<label class="label-field" for="Active">Estado:</label>
					<select class="form-control form-s" name="Active" id="Active" title="Estado para el reparto"> 
						<option value="1" style="color:green;">Activada</option>
						<option value="0" style="color:red;">Desactivada</option>
					</select>
				</div>
				<div class="separator1 bgGrayLight"></div>
				<div class="form-group">
					<label class="label-field" for="Street">Dirección:</label>
				</div>
				<div class="form-group">
					<input type="text" class="form-control form-m" name="Street" id="Street" title="Dirección postal" placeholder="Dirección postal" style="margin-right:10px;" />
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
						<option value="<?php echo $zone->ID; ?>"<?php if($enc){echo " disabled title='Ya hay una zona de reparto creada para este código postal'";} ?>>
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
			<div class='cp_mnu_subtitle title_block'><span class="textBox grayStrong">Repartidores</span><span class="textBox grayStrong" style="float:right;">Prioridad en pedidos</span></div>
			
			<div class="col-md-12 col-xs-12 col-sm-12">
				<?php foreach($userRep as $us) { ?>
					<div class="col-sm-9">
						<input type="checkbox" name="Repartidor[]" title="Repartidor" value="<?php echo $us->ID; ?>" style="margin-right:15px;" />
						<label for="Category"><?php echo $us->SURNAME.", ".$us->NAME; ?></label>
					</div>
					<div class="col-sm-3">
						<input type="number" name="PosRep-<?php echo $us->ID; ?>" id="PosRep-<?php echo $us->ID; ?>" value="0" style="float:right;text-align:right;" class="form-control"/>
					</div>
					<div class="separator10"></div>
					<div class="separator1 bgGrayLight"></div>
					<div class="separator10"></div>
					<?php } ?>	
				</div>
			</div>
			<div class="separator30">&nbsp;</div>
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
					<input class="btn tf-btn btn-default transition floatRight bgGreen white bold" type='submit' name='save' value='CREAR ZONA DE REPARTO' />
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
					id: "Street",
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