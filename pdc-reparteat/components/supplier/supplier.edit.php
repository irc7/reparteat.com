<?php if (allowed ($mnu)){ ?>
<div class='cp_mnu_title title_header_mod'>Editar proveedor</div>
<?php
	require_once("includes/classes/Image/class.Image.php");
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
	
	if (isset($_GET['msg'])) {
		$msg = utf8_encode($_GET['msg']);
		echo "<div class='container container-admin'><div class='cp_info'><img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' /><p>".$msg."</p></div></div>";
	}

	require_once("includes/classes/Supplier/class.Supplier.php");
	if(isset($_GET["id"]) && intval($_GET["id"]) > 0) {
		$id = $_GET["id"];	
	}else {
		$location = "index.php?mnu=".$mnu."&com=".$com."&tpl=option&opt=".$opt."&msg=".utf8_decode("Usuario desconocido");
?>
		<script type="text/javascript">
			window.location.href = "<?php echo $location; ?>";
		</script>
<?php
	}
	$supObj = new Supplier();
	$supplier = $supObj->infoSupplierById($id);
	$address = $supObj->allSupplierAddress($id);
	$idPro = $supObj->infoSupplierUser($id ,'proveedor');
	
	$cats = $supObj->infoCategories($id);
	//$timeControl = $supObj->supplierTimeControl($id);
?>	
	<script src="<?php echo DOMAIN; ?>pdc-reparteat/js/checking.validation.js"></script>
	<script src="<?php echo DOMAIN; ?>pdc-reparteat/js/class.Validation.js"></script>
	<form method='post' action='modules/supplier/edit.php' enctype='multipart/form-data' id='mainform' name='mainform'>
		<input type="hidden" name="mnu" value="<?php echo $mnu; ?>" />
		<input type="hidden" name="com" value="<?php echo $com; ?>" />
		<input type="hidden" name="tpl" value="<?php echo $tpl; ?>" />
		<input type="hidden" name="opt" value="<?php echo $opt; ?>" />
		<input type="hidden" name="id" value="<?php echo $id; ?>" />
		<div class='container container-admin darkshaded-space bgGrayNormal white'>
			<div class="separator10">&nbsp;</div>
			<div class="form-group">
				<label class="label-field white" for="status">Estado:</label>
				<select class="form-control form-s" name="status" id="status" title="Estado">
					<option value='1'<?php if($supplier->STATUS == 1){ echo ' selected="selected"';} ?>>Activado</option>
					<option value='2'<?php if($supplier->STATUS == 2){ echo ' selected="selected"';} ?>>No disponible</option>
					<option value='0'<?php if($supplier->STATUS == 0){ echo ' selected="selected"';} ?>>Desactivado</option>
				</select>
			</div>
		</div>
		<div class="separator20">&nbsp;</div>
		<div class='container container-admin'>
			<div class='row'>	
				<div class="form-group">
					<label class="label-field" for="Title">Nombre *:</label>
					<input type="text" name="Title" id="Title" class="form-control form-s" title="Nombre" value="<?php echo $supplier->TITLE; ?>" value="Nombre *" />
					<p id="error-Title"></p>
				</div>
				<div class="form-group">
					<label class="label-field" for="Proveedor">Proveedor *:</label>
					<select class="form-control form-s" name="Proveedor" id="Proveedor" title="Proveedor"> 
					<?php foreach($userSup as $us) { ?>
						<option value="<?php echo $us->ID; ?>"<?php if($us->ID == $idPro[0]){ echo ' selected';} ?>>
							<?php echo $us->SURNAME.", ".$us->NAME; ?>
						</option>
					<?php } ?>
					</select>
				</div>
				<div class="form-group">
					<label class="label-field" for="Phone">Teléfono:</label>
					<input type="phone" class="form-control form-xs" name="Phone" id="Phone" title="Teléfono" value="<?php echo $supplier->PHONE; ?>" placeholder="Teléfono" />
					<p id="error-Phone"></p>
				</div>
				<div class="form-group">
					<label class="label-field" for="Movil">Móvil:</label>
					<input type="phone" class="form-control form-xs" name="Movil" id="Movil" title="Móvil" value="<?php echo $supplier->MOVIL; ?>" placeholder="Móvil" />
					<p id="error-Phone"></p>
				</div>
				<div class="form-group">
					<label class="label-field" for="Cost">Gastos de envío *:</label>
					<input type="number" name="Cost" id="Cost" class="form-control form-xs" title="Gastos de envio" value="<?php echo $supplier->COST; ?>" step="0.01" required />
					<p id="error-Cost"></p>
				</div>
				<div class="form-group">
					<label class="label-field" for="Min">Pedido mínimo *:</label>
					<input type="number" name="Min" id="Min" class="form-control form-xs" title="Pedido mínimo" value="<?php echo $supplier->MIN; ?>" step="0.01" required />
					<p id="error-Cost"></p>
				</div>
				<div class="form-group">
					<label class="label-field" for="Min">Tiempo para pedidos(min) *:</label>
					<input type="number" name="Time" id="Time" class="form-control form-xs" title="Tiempo para pedidos" value="<?php echo $supplier->TIME; ?>" required />
					<p id="error-Time"></p>
				</div>
				<div class="form-group">
					<label class="label-field" for="Extra">Extra pedidos por franja *:</label>
					<input type="number" name="Extra" id="Extra" class="form-control form-xs" title="Extra pedidos por franja" placeholder="Extra pedidos por franja *" value="<?php echo $supplier->EXTRA_ORDER; ?>" step="1" required />
					<p id="error-Extra"></p>
				</div>
				<div class="form-group">
					<label class="label-field" for="Title">ID Telegram *:</label>
					<input type="text" name="IDTelegram" id="IDTelegram" class="form-control form-xs" title="ID Telegram" value="<?php echo $supplier->IDTELEGRAM; ?>" required />
					<p id="error-IDTelegram"></p>
				</div>
			</div>
			<div class="separator30">&nbsp;</div>
			<div class='row dotted padding-space'>	
				<div class='cp_mnu_subtitle title_block'><span class="textBox grayStrong">ZONAS DE REPARTO</span></div>
				<?php if(count($address) == 0) { ?>
				<div class="form-group">
					<label class="label-field orange" style="width:100%;"><i class="fa fa-exclamation-triangle orange iconBotton"></i> No existe ninguna zona de reparto asignada a este proveedor.</label>
				</div>
				<?php }else{ ?>
					<div class='row'>
						<div class='col-xs-3 cp_title top'>
							<div class='bold textLeft'>Zona</div>
						</div>
						<div class='col-xs-2 cp_title top'>
							<div class='bold textLeft'>Día de la semana</div>
						</div>
						<div class='col-xs-2 cp_title top'>
							<div class='bold textCenter'>Hora de inicio</div>
						</div>
						<div class='col-xs-1 cp_title top'>
							<div class='bold textLeft'>&nbsp;</div>
						</div>
						<div class='col-xs-2 cp_title top'>
							<div class='bold textCenter'>Hora final</div>
						</div>
						<div class='col-xs-2 cp_title top'>
							<div class='bold textLeft'>&nbsp;</div>
						</div>
					</div>
					<?php foreach($address as $item){ 
							$zoneItem = $zone->infoById($item->IDZONE);
							$timeControl = $supObj->supplierTimeControlZone($id, $item->IDZONE);
						
					?>
						<div class='row'>
							<div class='col-xs-3'>
								<h5 class='textLeft <?php if($item->ACTIVE == 0){echo "red";}else{echo "green";} ?>'><?php echo $zoneItem->CITY; ?></h5>
							</div>
							<div class='col-xs-2'>
								<?php foreach($timeControl as $time){ ?>
									<h5 class='textLeft <?php if($item->ACTIVE == 0){echo "red";}else{echo "green";} ?>'><?php echo $days[$time->DAY-1]; ?></h5>
								<?php } ?>
							</div>
							<div class='col-xs-2'>
								<?php foreach($timeControl as $time){ 
									if(strlen($time->START_H) == 1) {
										$time->START_H = "0". $time->START_H;
									}
									if(strlen($time->START_M) == 1) {
										$time->START_M = "0". $time->START_M;
									}	
								?>
									<h5 class='textCenter <?php if($item->ACTIVE == 0){echo "red";}else{echo "green";} ?>'><?php echo $time->START_H . ":" . $time->START_M; ?></h5>
								<?php } ?>
							</div>
							<div class='col-xs-1'>
								<div class='textLeft'>&nbsp;</div>
							</div>
							<div class='col-xs-2'>
								<?php foreach($timeControl as $time){ 
									if(strlen($time->FINISH_H) == 1) {
										$time->FINISH_H = "0". $time->FINISH_H;
									}
									if(strlen($time->FINISH_M) == 1) {
										$time->FINISH_M = "0". $time->FINISH_M;
									}
								?>
									<h5 class='textCenter <?php if($item->ACTIVE == 0){echo "red";}else{echo "green";} ?>'><?php echo $time->FINISH_H . ":" . $time->FINISH_M; ?></h5>
								<?php } ?>
							</div>
							<div class='col-xs-2 top'>
								<div class='col-xs-6'>
									<div class="separator10">&nbsp;</div>
									<?php $urledit = "index.php?mnu=".$mnu."&com=".$com."&tpl=edit&opt=delivery&supplier=".$id."&zone=".$item->IDZONE; ?>
									<a href="<?php echo $urledit; ?>" title="Editar zona de reparto">
										<i class="fa fa-edit pointer iconBotton transition grayStrong"></i>
									</a>
								</div>
								<div class='col-xs-6'>
									<div class="separator10">&nbsp;</div>
									<?php 
										$urlAlert = "modules/supplier/delete_delivery.php?mnu=".$mnu."&com=".$com."&tpl=edit&supplier=".$id."&zone=".$item->IDZONE;
										$msgAlert = "¡ATENCI&Oacute;N! Va a eliminar la zona de reparto ".$zoneItem->CITY."-".$zoneItem->CP.". &iquest;Est&aacute; seguro?";
									?>
									<div class='col-xs-6'>
										<i class="fa fa-trash pointer iconBotton transition white grayStrong" title='Eliminar zona de reparto' onclick='alertConfirm("<?php echo $msgAlert; ?>", "<?php echo $urlAlert; ?>");'></i>
									</div>
									
								</div>
							</div>
						</div>
						<div class="separator1 bgGrayLight">&nbsp;</div>
			<?php 		} 
				} 
				$urlCreate = "mnu=".$mnu."&com=".$com."&tpl=create&opt=delivery&supplier=".$id;
				?>
				<div class="separator30">&nbsp;</div>
				<a href='index.php?<?php echo $urlCreate; ?>' class="btn tf-btn btn-default transition floatRight bgGreen white bold" type="button" id="">
					Crear zona de reparto
				</a>
			</div>
			

<?php /*			
			<div class='row dotted padding-space'>	
				<div class='cp_mnu_subtitle title_block'><span class="textBox grayStrong">Horario de reparto</span></div>
				<div class="separator20">&nbsp;</div>
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
*/ 
?>			
			
			<div class="separator30">&nbsp;</div>
			<div class='row dotted padding-space'>	
				<div class='cp_mnu_subtitle title_block'><span class="textBox grayStrong">Categorias</span></div>
			<?php foreach($categories as $cat) { ?>	
				<div class="col-md-4 col-xs-6 col-sm-12">
					<input type="checkbox" name="Category[]" title="Category" value="<?php echo $cat->ID; ?>" <?php for($i=0;$i<count($cats);$i++){if($cats[$i] == $cat->ID){echo " checked";}} ?> style="margin-right:15px;" />
					<label for="Category"><?php echo $cat->TITLE; ?></label>
				</div>
			<?php } ?>	
			</div>
			<div class="separator30">&nbsp;</div>
			
			<div class='row dotted padding-space'>	
				<div class='cp_mnu_subtitle title_block'><span class="textBox grayStrong">DISEÑO</span></div>
				<div class="form-group">
					<label class="label-field" for="Name">Eslogan:</label>
					<input type="text" name="Eslogan" id="Eslogan" class="form-control form-m" title="Eslogan" value="<?php echo $supplier->ESLOGAN; ?>" placeholder="Eslogan" />
					<p id="error-Name"></p>
				</div>
				<div class="form-group">
					<label class="label-field" for="View_img">Ver imágenes de productos:</label>
					<select class="form-control form-xs" name="View_img" id="View_img" title="Ver imágenes de productos en los listados"> 
						<option value="0"<?php if($supplier->VIEW_IMG == 0){echo " selected";} ?>>No</option>
						<option value="1"<?php if($supplier->VIEW_IMG == 1){echo " selected";} ?>>Si</option>
					</select>
					<p id="error-View_img"></p>
				</div>
				<div class="form-group">
					<label class="label-field" for="Image">Descripción:</label>
					<?php require_once("js/jscripts/tiny_mce/tiny_mce.php"); ?>
					<textarea name='Text' id='Text' class='spl_editable' style="width:100%;"><?php echo $supplier->TEXT; ?> </textarea>
				</div>
				<div class="form-group">
					<label class="label-field" for="Logo">Logo:</label>
				<?php if($supplier->LOGO != ""): 
						$imgLogo = new Image();
						$imgLogo->path = "supplier";
						$imgLogo->pathoriginal = "original";
						$imgLogo->paththumb = "thumb";
				?>
					<div id="wrap-logo" class="col-sm-3">
						<a href='<?php echo DOMAIN.$imgLogo->dirbasename.$imgLogo->path."/".$imgLogo->pathoriginal."/".$supplier->LOGO; ?>' class='lytebox' data-lyte-options='group:<?php echo $supplier->ID; ?>'>
							<img src="<?php echo $imgLogo->dirView.$imgLogo->path."/".$imgLogo->paththumb."/1-".$supplier->LOGO; ?>" style="max-width:100px;margin-bottom:10px;" />
						</a>
					</div>
					<div class="col-sm-5">
						<label for="Logo" style="float:none;">Modificar: </label>
				<?php else: ?>
					<div class="col-sm-8">
				<?php endif; ?>
						<br/>
						<input class="form-control form-l" type="file" name="Logo" id="Logo" style="float:none;" />
						<div class="form-group" style="margin:0px;">
							<div style="font-style:italic;color:#c00;font-size:11px;">
								Dimensiones optimas del logo 300 x 300px
							</div>
						</div>
						<div class="separator10">&nbsp;</div>
						<input type="hidden" name="action-logo" id="action-logo" value="0" />
				<?php if($supplier->LOGO != ""): ?>
						<button class="btn tf-btn btn-default transition floatLeft bgGreen white bold delete-img" type="button" id="delete-logo">Eliminar logo</button>
				<?php endif; ?>
					</div>
				</div>
				<div class="separator20" style="border-bottom:1px solid #999;">&nbsp;</div>
				<div class="separator20">&nbsp;</div>
				<div class="form-group">
					<label class="label-field" for="Image">Imagen:</label>
				<?php if($supplier->IMAGE != ""): 
						$image = new Image();
						$image->path = "supplier";
						$image->pathoriginal = "original";
						$image->paththumb = "thumb";
				?>
					<div id="wrap-image" class="col-sm-3">
						<a href='<?php echo DOMAIN.$image->dirbasename.$image->path."/".$image->pathoriginal."/".$supplier->IMAGE; ?>' class='lytebox' data-lyte-options='group:<?php echo $supplier->ID; ?>'>
							<img src="<?php echo $image->dirView.$image->path."/".$image->paththumb."/1-".$supplier->IMAGE; ?>" style="max-width:100px;margin-bottom:10px;" />
						</a>
					</div>
					<div class="col-sm-6">
						<label for="Image" style="float:none;">Modificar: </label>
				<?php else: ?>
					<div class="col-sm-8">
				<?php endif; ?>
						<br/>
						<input class="form-control form-l" type="file" name="Image" id="Image" style="float:none;" />
						<input type="hidden" name="action-image" id="action-image" value="0" />
						<div class="separator10">&nbsp;</div>
				<?php if($supplier->IMAGE != ""): ?>
						<button class="btn btn-default transition floatLeft bgGreen white bold delete-img" type="button" id="delete-image">Eliminar imagen</button>
				<?php endif; ?>
					</div>
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
					<input class="btn tf-btn btn-default transition floatRight bgGreen white bold" type='submit' name='save' value='GUARDAR PROVEEDOR' />
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