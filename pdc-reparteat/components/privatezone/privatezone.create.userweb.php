<?php if (allowed ($mnu)) { ?>
	<div class='cp_mnu_title title_header_mod'>Nuevo usuario</div>
	<?php
		if (isset($_GET['msg'])) {
			$msg = utf8_encode($_GET['msg']);
			echo "<div class='cp_info'><img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' /><p>".$msg."</p></div>\r\n";
		}
		require_once("includes/classes/Zone/class.Zone.php");
		$zone = new Zone();
		$zones = array(); 
		$zones = $zone->listZones(); 
	?>		
	<br/>
	<script src="<?php echo DOMAIN; ?>pdc-reparteat/js/checking.validation.js"></script>
	<script src="<?php echo DOMAIN; ?>pdc-reparteat/js/class.Validation.js"></script>
	<form method='post' action='modules/privatezone/create_userweb.php' enctype='multipart/form-data' id='mainform' name='mainform'>
		<input type="hidden" name="mnu" value="<?php echo $mnu; ?>" />
		<input type="hidden" name="com" value="<?php echo $com; ?>" />
		<input type="hidden" name="tpl" value="<?php echo $tpl; ?>" />
		<input type="hidden" name="opt" value="<?php echo $opt; ?>" />
		<div class='container container-admin darkshaded-space bgGrayNormal white'>
			<div class="separator10">&nbsp;</div>
			<div class="col-sm-6">
				<div class="row">
					<div class="form-group">
						<label class="label-field-30 white" for="Type">Tipo de usuario:</label>
						<select class="form-control form-m" name="Type" id="Type" title="Tipo de usuario">
						<?php 
							$q = "select * from ".preBD."user_web_typeuser order by ID desc";
							$r = checkingQuery($connectBD, $q);
							while($row = mysqli_fetch_object($r)) {
						?>
								<option value='<?php echo $row->ID; ?>'><?php echo $row->NAME; ?></option>
						<?php } ?>	
						</select>
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="row">
					<div class="form-group">
						<label class="label-field white" for="status">Estado:</label>
						<select class="form-control form-m" name="status" id="status" title="Estado">
							<option value='1' selected="selected">Activado</option>
							<option value='0'>Desactivado</option>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="separator10">&nbsp;</div>
		<div class='container container-admin darkshaded-space bgGrayNormal white'>
			<div class="separator10">&nbsp;</div>
			<div class="form-group">
				<label class="label-field white" for="Email">Email/Login *:</label>
				<input type="email" class="form-control form-s" name="Email" id="Email" title="Correo electrónico" placeholder="Correo electrónico *" />
				<p id="error-Email"></p>
			</div>
			<div class="form-group">
				<label class="label-field white" for="Pass">
					Contraseña:
					<br/>
					<span style="font-style:italic;color:#ededed;font-size:11px;">
						Mínimo 8 caracteres
					</span>
				</label>
				<input type="password" name="Pass" id="Pass" class="form-control form-s" title="Contraseña" placeholder="Contraseña *" />
				<p id="error-Pass"></p>
			</div>
			<div class="form-group">
				<label class="label-field white" for="PassRepeat">Repetir contraseña:</label>
				<input type="password" name="PassRepeat" id="PassRepeat" class="form-control form-s" title="Repetir contraseña" placeholder="Repetir contraseña *" />
				<p id="error-PassRepeat"></p>
			</div>
		</div>
		
		<div class="separator50">&nbsp;</div>
		<div class='container container-admin'>
			<div class='row dotted padding-space'>	
				<div class='cp_mnu_subtitle title_block'><span class="textBox grayStrong">DATOS PERSONALES</span></div>
				<div class="form-group">
					<label class="label-field" for="Name">Nombre *:</label>
					<input type="text" name="Name" id="Name" class="form-control form-s" title="Nombre" placeholder="Nombre *" />
					<p id="error-Name"></p>
				</div>
				<div class="form-group">
					<label class="label-field" for="Surame">Apellidos *:</label>
					<input type="text" name="Surname" id="Surname" class="form-control form-m" title="Apellidos" placeholder="Apellidos *" />
					<p id="error-Surname"></p>
				</div>
				<div class="form-group">
					<label class="label-field" for="Email">DNI/NIF:
						<br/>
						<span style="font-style:italic;color:#666;font-size:11px;">
							Sin espacios ni guiones
						</span>
					</label>
					<input type="strin" class="form-control form-xs" name="DNI" id="DNI" title="DNI/NIF" placeholder="DNI/NIF" />
				</div>
				<div class="separator5">&nbsp;</div>
				<div class="form-group">
					<label class="label-field" for="Saldo">Saldo *:</label>
					<input type="number" name="Saldo" id="Saldo" class="form-control form-xs" title="Saldo" value="0.00" step="0.01" />
					<p id="error-Saldo"></p>
				</div>
			</div>
			<div class='row dotted padding-space'>	
				<div class='cp_mnu_subtitle title_block'><span class="textBox grayStrong">DATOS DE CONTACTO</span></div>
				<div id="box-idtelegram" class="form-group" style="display:none;">
					<label class="label-field" for="Title">ID Telegram *:</label>
					<input type="text" name="IDTelegram" id="IDTelegram" class="form-control form-xs" title="ID Telegram" placeholder="ID Telegram *" disabled />
					<p id="error-IDTelegram"></p>
				</div>
				<div class="form-group">
					<label class="label-field" for="Phone">Teléfono:</label>
					<input type="phone" class="form-control form-xs" name="Phone" id="Phone" title="Teléfono" placeholder="Teléfono" />
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
		</div>
		<div class='container container-admin'>
			<div class='row'>	
				<div class='col-md-5'>&nbsp;</div>
					<div class='col-md-2'>
					<img class='image middle' src='images/loading.gif' style='visibility: hidden; padding: 10px 20px 0px 0px;' id='loading'>
				</div>
				<div class='col-md-5'>
					<input class="btn tf-btn btn-default transition floatRight bgGreen white bold" type='submit' name='save' value='CREAR USUARIO' />
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
				},
				{
					id: "Email",
					type: "email",
					min: 5,
					max: 256
				},
				{
					id: "Pass",
					type: "password",
					min: 8,
					max: 10
				}
				
			]
		};
		var v2 = new Validation(validation_options);

	</script>
<?php 
}else{
	echo "<p>No tiene permiso para acceder a esta sección.</p>";
}?>	