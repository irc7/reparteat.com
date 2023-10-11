<!-- Page Heading -->
<div class='container'>
	<div class='row'>
		<h1 class="h3 mb-2 text-gray-800">Editar Mis Direcciones</h1>
		<div class="separator10">&nbsp;</div>
		<div class="separator1 bgYellow">&nbsp;</div>
		<div class="separator20">&nbsp;</div>
		<p class="mb-4"></p>
	</div>
</div>
	
	<form method='post' action='<?php echo DOMAINZP; ?>template/modules/user/edit.address.php' id='mainform' name='mainform'>
		<input type="hidden" name="id" value="<?php echo $_SESSION[nameSessionZP]->ID; ?>" />
		<input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
		<div class="separator20">&nbsp;</div>
		<div class='container'>
			<button id="addAddress" class="btn btn-primary transition floatRight bgGreen yellow" type='button'><i class="fa fa-plus-circle"></i></button>
			<div class="separator10">&nbsp;</div>
			<div id="user-address-0" class="form-group user-address bgGrayLight">
				<label class="label-field green arial" for="Street">Agregar nueva dirección:</label>
				<div class="separator10">&nbsp;</div>
				<input type="text" class="form-control form-m" name="Street-0" id="Street-0" title="Calle o Avenida" placeholder="Calle o Avenida" disabled style="margin-right:10px;" />
				<select class="form-control form-s" name="Zone-0" id="Zone-0" title="Zona de reparto" disabled> 
				<?php foreach($zones as $zone) { ?>
					<option value="<?php echo $zone->ID; ?>"><?php echo $zone->CITY." (".$zone->CP.")"; ?></option>
				<?php } ?>
				</select>
			</div>
			<div class="separator20">&nbsp;</div>
			<h4 class="arial grayStrong">Mis direcciones</h4>
			<div class='row'>
				<?php 
					foreach($zones as $zl) { 
						$address = array();
						$address = $userObj->userWebAddressZone($_SESSION[nameSessionZP]->ID, $zl->ID);
						if(count($address)>0) {
				?>
							<div class="wrap-zone-user-address bgGrayLight">
								<h5 class="arial bgGreen white"><?php echo $zl->CITY; ?></h5>
						
						<?php
								for($i=0;$i<count($address);$i++) { 
									
						?>
									<div id="user-address-<?php echo $address[$i]->ID; ?>" class="user-address form-group<?php if($address[$i]->FAV == 1){echo " bgGrayStrong white";} ?>">
										<label class="label-field" for="Street"><?php echo $i+1; ?>.- Dirección:<?php if($address[$i]->FAV == 1){echo "(<em>predeterminada</em>)";} ?></label>
										<div class="col-xs-1">
											<input type="radio" class="form-control" name="fav-<?php echo $zl->ID; ?>" value="<?php echo $address[$i]->ID; ?>" <?php if($address[$i]->FAV == 1){echo " checked";} ?> title="Marcar como predeterminada" />
										</div>
										<div class="col-xs-10">
											<input type="text" class="form-control form-m" name="Street-<?php echo $address[$i]->ID; ?>" id="Street-<?php echo $address[$i]->ID; ?>" title="Dirección postal" placeholder="Dirección postal" value="<?php echo $address[$i]->STREET; ?>" style="margin-right:10px;" />
											<select class="form-control form-s" name="Zone-<?php echo $address[$i]->ID; ?>" id="Zone-<?php echo $address[$i]->ID; ?>" title="Zona de reparto"> 
											<?php foreach($zones as $zS) { ?>
												<option value="<?php echo $zS->ID; ?>"<?php if($address[$i]->IDZONE == $zS->ID){echo " selected";} ?>>
													<?php echo $zS->CITY." (".$zS->CP.")"; ?>
												</option>
											<?php } ?>
											</select>
										</div>
										<div class="col-xs-1">
										<?php 
											$identificador = "addressuser_".$address[$i]->ID;
											$urlAlert = DOMAINZP."template/modules/user/delete.address.php?view=".$view."&mod=".$mod."&tpl=".$tpl."&address=".$address[$i]->ID."&zone=".$address[$i]->IDZONE."&user=".$_SESSION[nameSessionZP]->ID;
											$textAlert = "¡ATENCI&Oacute;N!";
											$textAlert2 = "Va a eliminar la dirección<br/><strong><em>".$address[$i]->STREET." - ".$zl->CITY." (".$zl->CP.")</em></strong><br/>&iquest;Est&aacute; seguro?";
										?>
											<i id="btn-action-open-<?php echo $identificador; ?>" class="fa fa-trash <?php if($address[$i]->FAV == 1){echo "white";}else{echo "grayStrong";} ?> pointer iconBotton transition btn-action-open" title='Eliminar dirección'></i>
											<?php include("template/modules/alert/btn.action.tpl.php"); ?>
										</div>
										<?php if($address[$i]->FAV == 0){ ?>
											<div class="separator20">&nbsp;</div>
											<div class="separator1 bgGrayLight">&nbsp;</div>
											<div class="separator20">&nbsp;</div>
										<?php }else{ ?>
											<div class="separator10">&nbsp;</div>
										<?php } ?>

									</div>
							<?php } ?>
						</div>
					<?php } ?>
				<?php }	?>
			</div>
		</div>
		
			
		<div class="separator20">&nbsp;</div>
		<div class="separator1 bgGrayNormal">&nbsp;</div>
		<div class="separator20">&nbsp;</div>
		<div class='container'>
			<button class="btn btn-primary transition floatRight bgGreen yellow" type='submit'>GUARDAR DIRECCIONES</button>
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