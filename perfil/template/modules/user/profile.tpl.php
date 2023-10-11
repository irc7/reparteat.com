	
<!-- Page Heading -->
<div class='container'>
	<div class='row'>
		<h1 class="h3 mb-2 text-gray-800">Editar Mis datos personales</h1>
		<div class="separator10">&nbsp;</div>
		<div class="separator1 bgYellow">&nbsp;</div>
		<div class="separator20">&nbsp;</div>
		<p class="mb-4"></p>
	</div>
</div>
	
	<form method='post' action='<?php echo DOMAINZP; ?>template/modules/user/edit.user.php' id='userform' name='userform'>
		<input type="hidden" name="id" value="<?php echo $_SESSION[nameSessionZP]->ID; ?>" />
		<div class='container bgGrayNormal white'>
			<div class="separator10">&nbsp;</div>
			<div class="form-group">
				<label class="label-field white" for="Email">Email/Login *:</label>
				<input type="email" class="form-control form-s" name="Email" id="Email" title="Correo electrónico" value="<?php echo $user->LOGIN; ?>" disabled />
				<button id="changePass" class="btn btn-primary transition floatRight bgGreen yellow" type='button'>Modificar datos de logueo</button>
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
				<div class="separator15"></div>
			</div>
		</div>
		
		<div class="separator50">&nbsp;</div>
		<div class='container'>	
			<h4 class="arial grayStrong">DATOS PERSONALES</h4>
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
					<em style="font-style:italic;color:#666;font-size:11px;">
						Sin espacios ni guiones
					</em>
				</label>
				<input type="strin" class="form-control form-xs" name="DNI" id="DNI" title="DNI/NIF" placeholder="DNI/NIF" value="<?php echo $user->DNI; ?>" />
			</div>
			<?php if($user->IDTYPE == 3){ ?>
			<div class="separator10">&nbsp;</div>
			<div id="box-idtelegram" class="form-group">
				<label class="label-field" for="Title">ID Telegram *:</label>
				<input type="text" name="IDTelegram" id="IDTelegram" class="form-control form-xs" title="ID Telegram" value="<?php echo $user->IDTELEGRAM; ?>"<?php if($user->IDTYPE != 3){echo " disabled";} ?> />
				<p id="error-IDTelegram"></p>
			</div>
			<?php } ?>
			<div class="separator10">&nbsp;</div>
			<div class="form-group">
				<label class="label-field" for="Phone">Teléfono:</label>
				<input type="phone" class="form-control form-xs" name="Phone" id="Phone" title="Teléfono" placeholder="Teléfono" value="<?php echo $user->PHONE; ?>" />
				<p id="error-Phone"></p>
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