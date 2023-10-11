<?php if (allowed($mnu)) { ?>
		<div class='cp_mnu_title'>Gestión de usuarios</div>
<?php 	
		$mydegree = getdegree($_SESSION[PDCLOG]['Type']);
		include ("components/user/user.list.php");		
		if (isset($_GET['action'])) {
			$action = $_GET['action'];

// DELETE USER
			if ($action == 'Deleteuser') {
?>
				<div class='cp_alert'>
					<img class='cp_msgicon' src='images/alert.png' alt='¡INFORMACIÓN!' />
					¡ATENCIÓN! Va a eliminar el usuario <strong><?php echo trim($_GET['login']); ?></strong>
				</div>
				<br/>
				<form method='post' action='modules/user/delete_user.php'>
					<input type='hidden' name='mnu' value='<?php echo $mnu; ?>' />
					<div class='cp_table500'>
						<div class='cp_formfield'></div>
					</div>
					<input type='hidden' name='user' value='<?php echo $_GET['login']; ?>' />
					<div class='cp_formfield'>&nbsp;</div>
					<div class='cp_table'>
						<input type='submit' value='Eliminar usuario' />
					</div>
				</form>
<?php 
			}

// CREATE USER
			else if ($action == 'Createuser') {
?>
				<div class='cp_info'>
					<img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' />
					¡ATENCIÓN! Va a crear un nuevo usuario
				</div>
				<div class='cp_alert noerror' id='info-Login'></div>
				<div class='cp_alert noerror' id='info-Name'></div>
				<div class='cp_alert noerror' id='info-Pwd'></div>
				<br/>
				<form method='post' action='modules/user/create_user.php' name='mainform' id='mainform' enctype='multipart/form-data'>
					<input type='hidden' name='mnu' value='<?php echo $mnu; ?>' />
					
					<ul class="list-record-fields">
						<li class="complete">
							<label class="label-field" for='Name'>Nombre y apellidos: </label>
							<input class="input-field-100" type='text' name='Name' id='Name' title='Nombre' />
						</li>
						<li class="complete">
							<label class="label-field" for='Login'>Login (e-mail): </label>
							<input class="input-field-50" type='text' name='Login' id='Login' title='Login' />
						</li>
						<li class="complete">
							<label class="label-field" for='pwd1'>Contrase&ntilde;a: </label>
							<input class="input-field-small" type='password' name='Pwd' id='Pwd' title='Contraseña' />
						</li>
						<li class="complete">
							<label class="label-field" for='pwd2'>Repetir contrase&ntilde;a: </label>
							<input class="input-field-small" type='password' name='CPwd' id='CPwd' title='Repitir contraseña' />
						</li>
						<li class="complete">
							<label class="label-field" for='type'>Tipo: </label>
							<select name='Type' id='Type' class="input-field-50">
							<?php 
									$r = "SELECT * FROM ".preBD."users_permissions";
									$result2 = checkingQuery($connectBD, $r);	
									
									while($row2 = mysqli_fetch_object($result2)) {
										if(($row2->Id_user != 4 && $row2->Id_user == 1 && $mydegree <= $row2->Id_user) || ($row2->Id_user != 4 && $mydegree < $row2->Id_user)){ ?>
											<option value='<?php echo $row2->Id_user; ?>'>
												<?php echo $row2->Type; ?>
											</option>
							<?php	 	} elseif($row2->Id_user != 4) { ?>
											<option value='' disabled><?php echo $row2->Type; ?></option>
							<?php
										}
									} 
							?>
							</select>
						</li>
						<li class="complete">
							<label class="label-field" for='Text'>Descripción: </label>
							<input class="input-field-100" type='text' name='Text' id='Text' title='Descripción' />
						</li>
						<li class="complete">
							<label class="label-field" for='Image'>Imagen: </label>
							<input class="input-field-100" type='file' name='Image' id='Image' title='' />
						</li>
						<li class="complete">
							<label class="label-field">&nbsp;</label>
							<div class="cp_table"><em>Tamaño recomendado para la imagen 90 x 110 px.</em></div>
						</li>
						<li class='complete'>
							<img class='image middle' src='images/loading.gif' style='visibility: hidden; padding: 10px 20px 0px 0px;margin-left:250px;' id='loading'>
							<input type='button' value='Crear usuario' style="float:right;margin-right:40px;" onclick='showloading(1);validate(this);return false;'/>
						</li>
					</ul>
					
				</form>
				<script type='text/javascript'>
					includeField('Login','email');
					includeField('Name','string');
					includeField('Pwd','Pwd');
				</script>
<?php 			
			}
// CHANGE PWD
			else if ($action == 'Changepwd') {
?>
				<div class='cp_alert'>
					<img class='cp_msgicon' src='images/alert.png' alt='¡INFORMACIÓN!' />
					¡ATENCIÓN! Va a cambiar la contraseña del usuario <strong><?php echo trim($_GET['login']); ?></strong>
				</div>
				<br/>
				<div class='cp_alert noerror' id='info-Pwd'></div>
				<form method='post' action='modules/user/changepwd_user.php' name="mainform" id="mainform">
					<input type='hidden' name='user' value='<?php echo trim($_GET['login']); ?>' />
					<input type='hidden' name='mnu' value='<?php echo $mnu; ?>' />
					<ul class="list-record-fields">
						<li class="complete">
							<label class="label-field" for='pwd1'>Contrase&ntilde;a: </label>
							<input class="input-field-small" type='password' name='Pwd' id='Pwd' title='Contraseña' />
						</li>
						<li class="complete">
							<label class="label-field" for='pwd2'>Repetir contrase&ntilde;a: </label>
							<input class="input-field-small" type='password' name='CPwd' id='CPwd' title='Repitir contraseña' />
						</li>
						<li class='complete'>
							<img class='image middle' src='images/loading.gif' style='visibility: hidden; padding: 10px 20px 0px 0px;margin-left:250px;' id='loading'>
							<input type='button' value='Cambiar contraseña' style="float:right;margin-right:40px;" onclick='showloading(1);validate(this);return false;'/>
						</li>
					</ul>
				</form>
				<script type='text/javascript'>
					includeField('Pwd','Pwd');
				</script>	
<?php 			
			}
// CHANGE TYPE
			else if ($action == 'Changetype') {
				$q = "select ID, Type from ".preBD."users where Login = '" . trim($_GET['login'])."'";
				$r =checkingQuery($connectBD, $q);
				if($userBD = mysqli_fetch_object($r)) {
?>
					<div class='cp_info'>
						<img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' />
						¡ATENCIÓN! Va a cambiar el tipo de usuario para <strong><?php echo trim($_GET['login']); ?></strong>
					</div>
					<br/>
					<form method='post' action='modules/user/changetype_user.php' id="mainform" name="mainform">
						<ul class="list-record-fields">
							<input type='hidden' name='mnu' value='<?php echo $mnu; ?>' />
							<input type='hidden' name='user' value='<?php echo trim($_GET['login']); ?>' />
							<li class="complete">
								<label class="label-field" for='type'>Tipo: </label>
								<select name='Type' id='Type' class="input-field-50">
								<?php 
									$r = "SELECT * FROM ".preBD."users_permissions";
									$result2 = checkingQuery($connectBD, $r);	
									
									while($row2 = mysqli_fetch_object($result2)) {
										if(($row2->Id_user != 4 && $row2->Id_user == 1 && $mydegree <= $row2->Id_user) || ($row2->Id_user != 4 && $mydegree < $row2->Id_user)){ ?>
											<option value='<?php echo $row2->Id_user; ?>'<?php if($userBD->Type == $row2->Id_user){echo " selected='selected'";} ?>>
												<?php echo $row2->Type; ?>
											</option>
							<?php	 	} elseif($row2->Id_user != 4) { ?>
											<option value='' disabled><?php echo $row2->Type; ?></option>
							<?php
										}
									} 
							?>
								</select>
							</li>
							<li class='complete'>
								<img class='image middle' src='images/loading.gif' style='visibility: hidden; padding: 10px 20px 0px 0px;margin-left:250px;' id='loading'>
								<input type='button' value='Cambiar tipo' style="float:right;margin-right:40px;" onclick='showloading(1);validate(this);return false;'/>
							</li>
						</ul>
					</form>
<?php			}else{ ?>
					<div class='cp_alert'>
						<img class='cp_msgicon' src='images/alert.png' alt='¡INFORMACIÓN!' />
						¡ATENCIÓN! El usuario <strong><?php echo trim($_GET['login']); ?></strong> ha sido eliminado o no se encuentra en nuestra base de datos.
					</div>
<?php
				}
			}
			
	// CHANGE NAME
			else if ($action == 'Changeprofile') {
				$q = "select ID, Name, Text, Image from ".preBD."users where Login = '" . trim($_GET['login'])."'";
				$r =checkingQuery($connectBD, $q);
				if($userBD = mysqli_fetch_object($r)) {
?>
					<div class='cp_alert'>
						<img class='cp_msgicon' src='images/alert.png' alt='¡INFORMACIÓN!' />
						¡ATENCIÓN! Va a cambiar el nombre del usuario <strong><?php echo trim($_GET['login']); ?></strong>
					</div>
					<br/>
					<div class='cp_alert noerror' id='info-Name'></div>
					<form method='post' action='modules/user/changeprofile_user.php' name='mainform' id='mainform' enctype='multipart/form-data'>
						<input type='hidden' name='mnu' value='<?php echo $mnu; ?>' />
						<input type='hidden' name='user' value='<?php echo trim($_GET['login']); ?>' />
						<ul class="list-record-fields">
							<li class="complete">
								<label class="label-field" for='Name'>Nombre y apellidos: </label>
								<input class="input-field-100" type='text' name='Name' id='Name' title='Nombre' value="<?php echo $userBD->Name; ?>" />
							</li>
							<li class="complete">
								<label class="label-field" for='Text'>Descripción: </label>
								<input class="input-field-100" type='text' name='Text' id='Text' title='Descripción' value="<?php echo $userBD->Text; ?>" />
							</li>
							<?php if($userBD->Image != ""): ?>
								<li class="complete">
									<label class="label-field" for="Image">Imagen: </label>
									<img src="images/user/<?php echo $userBD->Image; ?>" style="max-width:150px;margin-bottom:10px;" />
									<input type="hidden" value="0" name="optImg" id="optImg" />
								</li>
								<li class="complete">
									<label class="label-field" for="Image">&nbsp;</label>
									<input class="corporativeButton" type="button" name="changeImage" id="changeImage" value="Modificar" style="margin-right:30px;" />
									<input class="corporativeButton" type="button" name="deleteImage" id="deleteImage" value="Eliminar" />
								</li>
								<li class="box-edit-img complete" style='display:none;'>
									<label class="label-field" for="Image">Modificar: </label>
									<input class="" type="file" name="Image" id="Image" title="Imagen" disabled="disabled"/>
								</li>
							<?php else: ?>
								<li class="complete">
									<label class="label-field" for="Image">Imagen: </label>
									<input class="" type="file" name="Image" id="Image" title="Imagen" />
									<input type="hidden" value="2" name="optImg" id="optImg" />
								</li>
							<?php endif; ?>
							<li class="complete">
								<label class="label-field">&nbsp;</label>
								<div class="cp_table"><em>Tamaño recomendado para la imagen 90 x 110 px.</em></div>
							</li>
							<li class='complete'>
								<img class='image middle' src='images/loading.gif' style='visibility: hidden; padding: 10px 20px 0px 0px;margin-left:250px;' id='loading'>
								<input type='button' value='Crear usuario' style="float:right;margin-right:40px;" onclick='showloading(1);validate(this);return false;'/>
							</li>
						</ul>
					</form>
					<script type='text/javascript'>
						includeField('Name','string');
					</script>
<?php			}else{ ?>
				<div class='cp_alert'>
					<img class='cp_msgicon' src='images/alert.png' alt='¡INFORMACIÓN!' />
					¡ATENCIÓN! El usuario <strong><?php echo $_GET['login']; ?></strong> ha sido eliminado o no se encuentra en nuestra base de datos.
				</div>
<?php 			} ?>
				
<?php 
			}
		}else {
			$msg = NULL;
		}
		if (isset($_GET['msg'])) {
			$msg = utf8_encode($_GET['msg']);
			echo "<div class='cp_info'><img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' />".$msg."</div>";
		}
		else {
			$msg = NULL;
		}
		if (strpos($msg,"desconectado")) {
			session_destroy();
		}
}else{
	echo "<p>No tiene permisos para acceder a esta sección.</p>";
}
?>	
