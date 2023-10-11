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
					<label class="label-field" for="Time">Tiempo para pedidos(min) *:</label>
					<input type="number" name="Time" id="Time" class="form-control form-xs" title="Tiempo para pedidos" placeholder="Tiempo para pedidos(min) *" required />
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
			<div class='row dotted padding-space'>	
				<div class='cp_mnu_subtitle title_block'><span class="textBox grayStrong">ZONA DE REPARTO</span></div>
				<div class="form-group">
					<label class="label-field orange" style="width:100%;"><i class="fa fa-exclamation-triangle orange iconBotton"></i> Para crear una zona de reparto primero tienes que guardar el proveedor.</label>
				</div>
			</div>
			
			<div class="separator30">&nbsp;</div>
			<div class='row dotted padding-space'>	
				<div class='cp_mnu_subtitle title_block'><span class="textBox grayStrong">Categorias</span></div>
			<?php foreach($categories as $cat) { ?>	
				<div class="col-md-4 col-xs-6 col-sm-12">
					<input type="checkbox" name="Category[]" title="Category" value="<?php echo $cat->ID; ?>" style="margin-right:15px;" />
					<label for="Category"><?php echo $cat->TITLE; ?></label>
				</div>
			<?php } ?>	
			</div>
			<div class='row dotted padding-space'>	
				<div class='cp_mnu_subtitle title_block'><span class="textBox grayStrong">DISEÑO</span></div>
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