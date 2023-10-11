<?php if (allowed ($mnu)){ ?>
<div class='cp_mnu_title title_header_mod'>Editar usuario</div>
<?php
	require_once("includes/classes/UserWeb/class.UserWeb.php");
	require_once("includes/classes/Password/class.Password.php");
	require_once("includes/classes/Address/class.Address.php");
	require_once("includes/classes/Zone/class.Zone.php");
	if (isset($_GET['msg'])) {
		$msg = utf8_encode($_GET['msg']);
		echo "<div class='container container-admin'><div class='cp_info'><img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' /><p>".$msg."</p></div></div>";
	}

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
	$userObj = new UserWeb();
	$user = $userObj->infoUserWebById($id);
	$address = $userObj->userWebAddress($id);
	$zone = new Zone();
	$zones = array(); 
	$zones = $zone->listZones();
	
?>	
	<script src="<?php echo DOMAIN; ?>pdc-reparteat/js/checking.validation.js"></script>
	<script src="<?php echo DOMAIN; ?>pdc-reparteat/js/class.Validation.js"></script>
	<br/>
		<script src="<?php echo DOMAIN; ?>pdc-reparteat/js/checking.validation.js"></script>
	<script src="<?php echo DOMAIN; ?>pdc-reparteat/js/class.Validation.js"></script>
	<form method='post' action='modules/privatezone/edit_userweb.php' enctype='multipart/form-data' id='mainform' name='mainform'>
		<input type="hidden" name="mnu" value="<?php echo $mnu; ?>" />
		<input type="hidden" name="com" value="<?php echo $com; ?>" />
		<input type="hidden" name="tpl" value="<?php echo $tpl; ?>" />
		<input type="hidden" name="opt" value="<?php echo $opt; ?>" />
		<input type="hidden" name="id" value="<?php echo $id; ?>" />
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
									<option value='<?php echo $row->ID; ?>'<?php if($row->ID == $user->IDTYPE){echo " selected";}; ?>><?php echo $row->NAME; ?></option>
							<?php } ?>	
							</select>
						</select>
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="row">
					<div class="form-group">
						<label class="label-field white" for="status">Estado:</label>
						<select class="form-control form-m" name="status" id="status" title="Estado">
							<option value='1'<?php if($user->STATUS == 1){ echo ' selected="selected"';} ?>>Activado</option>
							<option value='0'<?php if($user->STATUS == 0){ echo ' selected="selected"';} ?>>Desactivado</option>
							<option value='0'<?php if($user->STATUS == 5){ echo ' selected="selected"';} ?> disabled>En espera de confirmación</option>
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
				<input type="email" class="form-control form-s" name="Email" id="Email" title="Correo electrónico" value="<?php echo $user->LOGIN; ?>" disabled />
				<input id="changePass" class="btn tf-btn btn-default transition floatRight bgGreen white bold" type='button' name='save' value='Modificar datos de logueo' />
				<p id="error-Email"></p>
			</div>
			<div class="form-group edit-password">
				<label class="label-field white" for="Pass">
					Contraseña:
					<br/>
					<span style="font-style:italic;color:#ededed;font-size:11px;">
						Mínimo 8 caracteres
					</span>
				</label>
				<input type="password" name="Pass" id="Pass" class="form-control form-s" title="Contraseña" placeholder="Contraseña" value="<?php echo $user->PASS; ?>" disabled />
				<p id="error-Pass"></p>
			</div>
			<div class="form-group edit-password">
				<label class="label-field white" for="PassRepeat">Repetir contraseña:</label>
				<input type="password" name="PassRepeat" id="PassRepeat" class="form-control form-s" title="Repetir contraseña" placeholder="Repetir contraseña" value="<?php echo $user->PASS; ?>" disabled />
				<p id="error-PassRepeat"></p>
			</div>
		</div>
		
		<div class="separator50">&nbsp;</div>
		<div class='container container-admin'>
			<div class='row dotted padding-space'>	
				<div class='cp_mnu_subtitle title_block'><span class="textBox grayStrong">DATOS PERSONALES</span></div>
				<div class="form-group">
					<label class="label-field" for="Name">Nombre *:</label>
					<input type="text" name="Name" id="Name" class="form-control form-s" title="Nombre" placeholder="Nombre *" value="<?php echo $user->NAME; ?>" />
					<p id="error-Name"></p>
				</div>
				<div class="form-group">
					<label class="label-field" for="Surame">Apellidos *:</label>
					<input type="text" name="Surname" id="Surname" class="form-control form-m" title="Apellidos" placeholder="Apellidos *" value="<?php echo $user->SURNAME; ?>" />
					<p id="error-Surname"></p>
				</div>
				<div class="form-group">
					<label class="label-field" for="Email">DNI/NIF:
						<br/>
						<span style="font-style:italic;color:#666;font-size:11px;">
							Sin espacios ni guiones
						</span>
					</label>
					<input type="strin" class="form-control form-xs" name="DNI" id="DNI" title="DNI/NIF" placeholder="DNI/NIF" value="<?php echo $user->DNI; ?>" />
				</div>
				<div class="separator5">&nbsp;</div>
				<div class="form-group">
					<label class="label-field" for="Saldo">Saldo *:</label>
					<input type="number" name="Saldo" id="Saldo" class="form-control form-xs" title="Saldo" value="<?php echo $user->SALDO; ?>" step="0.01" />
					<p id="error-Saldo"></p>
				</div>
			</div>
			<div class='row dotted padding-space'>	
				<div class='cp_mnu_subtitle title_block'><span class="textBox grayStrong">DATOS DE CONTACTO</span></div>
				<div id="box-idtelegram" class="form-group"<?php if($user->IDTYPE != 3){echo " style='display:none;'";} ?>>
					<label class="label-field" for="Title">ID Telegram *:</label>
					<input type="text" name="IDTelegram" id="IDTelegram" class="form-control form-xs" title="ID Telegram" value="<?php echo $user->IDTELEGRAM; ?>"<?php if($user->IDTYPE != 3){echo " disabled";} ?> />
					<p id="error-IDTelegram"></p>
				</div>
				<div class="form-group">
					<label class="label-field" for="Phone">Teléfono:</label>
					<input type="phone" class="form-control form-xs" name="Phone" id="Phone" title="Teléfono" placeholder="Teléfono" value="<?php echo $user->PHONE; ?>" />
					<p id="error-Phone"></p>
				</div>
				<?php 
					for($i=0;$i<count($address);$i++) { 
				?>
					<div class="form-group">
						<label class="label-field" for="Street"><?php echo $i+1; ?>.- Dirección<?php if($address[$i]->FAV == 1){echo " <em>(Predeterminada)</em>";} ?>:</label>
					</div>
					<div class="form-group">
						<input type="text" class="form-control form-m" name="Street-<?php echo $address[$i]->ID; ?>" id="Street-<?php echo $address[$i]->ID; ?>" title="Dirección postal" placeholder="Dirección postal" value="<?php echo $address[$i]->STREET; ?>" style="margin-right:10px;" />
						<select class="form-control form-s" name="Zone-<?php echo $address[$i]->ID; ?>" id="Zone-<?php echo $address[$i]->ID; ?>" title="Zona de reparto"> 
						<?php foreach($zones as $zone) { ?>
							<option value="<?php echo $zone->ID; ?>"<?php if($address[$i]->IDZONE == $zone->ID){echo " selected";} ?>>
								<?php echo $zone->CITY." (".$zone->CP.")"; ?>
							</option>
						<?php } ?>
						</select>
					</div>
				<?php } ?>
			<?php /*	
				<div class="form-group">
					<label class="label-field" for="Street">Agregar dirección:</label>
				</div>
				<div class="form-group">
					<input type="text" class="form-control form-m" name="Street" id="Street" title="Dirección postal" placeholder="Dirección postal" style="margin-right:10px;" />
					<select class="form-control form-s" name="Zone" id="Zone" title="Zona de reparto"> 
						<option value="0" selected>Seleccione zona de reparto</option>
					<?php foreach($zones as $zone) { ?>
						<option value="<?php echo $zone->ID; ?>"><?php echo $zone->CITY." (".$zone->CP.")"; ?></option>
					<?php } ?>
					</select>
				</div>
			*/ ?>
			</div>
		</div>
		
			
		<div class='container container-admin'>
			<div class='row'>	
				<div class='col-md-5'>&nbsp;</div>
					<div class='col-md-2'>
					<img class='image middle' src='images/loading.gif' style='visibility: hidden; padding: 10px 20px 0px 0px;' id='loading'>
				</div>
				<div class='col-md-5'>
					<input class="btn tf-btn btn-default transition floatRight bgGreen white bold" type='submit' name='save' value='GUARDAR USUARIO' />
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