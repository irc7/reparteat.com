 <!-- Contact Section
    ==========================================-->
 <?php require_once("template/modules/contact/header_contact.php"); ?>
	<div class="separator5 bgYellow">&nbsp;</div>
	<div class="separator50">&nbsp;</div>
	<div class="container">
		<div class="row">
			
			<div class="col-lg-9 col-sm-8 col-xs-12">
				<div id="reparteat-contact" class="text-center">
					<div class="title-block-home titleBold">
						<h4 class="textBox grayStrong text-left" style="margin-top:0px;">Si tienes cualquier duda o consulta, o quieres unirte a nosotros, escríbenos a través de este formulario y nos pondremos en contacto contigo en la mayor brevedad posible.</h4>
					</div>
					<div class="separator30"></div>
					<form action="<?php echo DOMAIN; ?>template/modules/contact/send.php" class="contact-form" method="post" role="form" id="form-contact">
						<input type="hidden" name="recaptcha_response" id="recaptchaResponse">
						
							<div class="form-group">
								<input type="text" class="form-control" name="Name" id="Name" class="textBox" placeholder="Nombre *" required>
								<p id="error-Name"></p>
							</div>
							<div class="form-group">
								<input type="email" class="form-control" name="Email" id="Email" placeholder="Correo electrónico *" required>
								<p id="error-Email"></p>
							</div>
							<div class="form-group">
								<input type="text" class="form-control" name="Phone" id="Phone" placeholder="Teléfono de contacto">
							</div>
						
							<div class="form-group">
								<textarea class="form-control" name="Text" id="Text" rows="5" placeholder="Escriba aquí su consulta *" required></textarea>
								<p id="error-Text"></p>
							</div>
							<div class="form-group legacy-policy">
								<input class="form-control transition black" type="checkbox" name="policy" id="policy-contact" title="Política de privacidad" required> 
								<label for="policy" class="policy grayStrong text-left">
									<?php
										$artObj = new Article();
										$artPolity = infoArticleById(1);
										$artTerm = infoArticleById(3);
									?>
									<span class="textBox grayNormal">
										He leído y doy mi consentimiento a la
										<a class="textBoxBold transition" href="<?php echo DOMAIN.$artPolity->slug; ?>" alt="<?php echo $artPolity->tA; ?>" title="<?php echo $artPolity->tA; ?>" target="_blank">
											<?php echo $artPolity->tA; ?>	 					
										</a>
										y acepto los 
										<a class="textBoxBold transition" href="<?php echo DOMAIN.$artTerm->slug; ?>" alt="<?php echo $artTerm->tA; ?>" title="<?php echo $artTerm->tA; ?>" target="_blank">
											<?php echo $artTerm->tA; ?>	 					
										</a>
									</span>
								</label>
								<p id="error-policy-contact"></p>
							</div>
							<div class="form-group text-right">
								<button type="submit" class="btn transition bgGreen yellow">Enviar</button>
							</div>
						
					</form>
				</div>
			</div>
			<div class="col-lg-3 col-sm-4 col-xs-12">
				<ul class="data-contact">
					<li>
						<div class="col-lg-1 col-xs-2 no-padding"><i class="fa fa-map-marker green"></i></div>
						<div class="col-lg-11 col-xs-10 arial">Travesía Plaza de Toros, nº 2<br/>06380 Jerez de los Caballeros</br>BADAJOZ</div>
						<div class="separator">&nbsp;</div>
					</li>
					<li>
						<div class="col-lg-1 col-xs-2  no-padding"><i class="fa fa-envelope green"></i></div>
						<div class="col-lg-11 col-xs-10 arial">info@reparteat.com</div>
						<div class="separator">&nbsp;</div>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="separator20">&nbsp;</div>
			
	<div class="separator5 bgYellow">&nbsp;</div>
<?php /*	
	<script type="text/javascript">
	//Validacion del formulario		
		var validation_options = {
			form: document.getElementById("form-contact"),
			fields: [
				{
					id: "Name",
					type: "string",
					min: 2,
					max: 256
				},
				{
					id: "Text",
					type: "string",
					min: 1,
					max: 1000
				},
				{
					id: "Email",
					type: "email",
					min: 5,
					max: 256
				},
				{
					id: "policy-contact",
					type: "boolean"
				}
			]
		};
		var v2 = new Validation(validation_options);
	
	</script>
*/ ?>