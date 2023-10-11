	<nav id="footer" class="bgGrayStrong grayLight">
		<div class="container footer-top">
			<div class="row">
				<div class="col-md-12 no-padding">
					<div class="footer-left textCenter">	
						<img class="img-responsive" src="<?php echo DOMAIN; ?>template/images/logo_green.png" />
					</div>
				</div>
				<div class="col-md-12 no-padding">
					<div class="footer-right col-md-4 col-sm-6 col-xs-12 no-padding textCenter">	
						<h4 class="arial white">Escríbenos</h4>
						<h5 class="textBox graySemiLight">A través de este formulario, podrás realizarnos cualquier duda o consulta, o solicitar información si quieres unirte a nosotros.</h5>
						<div class="separator15">&nbsp;</div>
						<a class="btn btn-primary arial" href="<?php echo DOMAIN; ?>escribenos">Ir al formulario</a>
						<div class="separator15">&nbsp;</div>
					</div>
					<div class="footer-center col-md-4 col-sm-6 textCenter">	
						<h4 class="arial">Sobre nosotros</h4>
						<ul class="mnu-footer textCenter">
						<?php
							$q = "select * from ".preBD."menu_item where IDMENU = 1 order by POSITION asc";
							$r = checkingQuery($connectBD,$q);
							$t = mysqli_num_rows($r);
							$i = 1;
							while($item = mysqli_fetch_object($r)){
								echo "<li>";
								$TitleItem = stripslashes($item->TITLE);
								$classLink = "transition ";
								constructItemMenu($TitleItem, $item->TYPE, $item->IDVIEW, $item->TARGET, $item->DISPLAY, $itemP->THUMBNAIL, $classLink, $view);
								echo "</li>";
								$i++;
							}
						?>
						</ul>
					</div>	
					<div class="footer-right col-md-4 col-sm-12 col-xs-12 no-padding textCenter">	
						<h4 class="arial white">Atención al cliente</h4>
						<h5 class="textBox graySemiLight">Llamanos a este número de teléfono para cualquier consulta o incidencia con su pedido.</h5>
						<a class="btn btn-primary arial" href="tel:+34681949316"><i class="fa fa-phone icon-phone"></i> 681 949 316</a>
					</div>
					
				</div>
			</div>
		</div>
		
		<div class="separator1 bgYellow">&nbsp;</div>
		<div class="separator10">&nbsp;</div>
		
		<div class="container footer-bottom">
			<div class="col-sm-9 col-xs-12">
				<p class="graySemiLight">Reparteat | Todos los derechos reservados. COPYRIGHT © <?php echo date("Y"); ?></p>
			</div>
			<div class="col-sm-3 col-xs-12 grayNormal textRight no-padding">
				Desing & Developer&nbsp;&nbsp;<a href="https://www.ismaelrc.com" alt="IRC - Diseño gráfico y desarrollo web" title="IRC - Diseño gráfico y desarrollo web" target="_blank">
				<img src="<?php echo DOMAIN; ?>template/images/logoirc.png" style="margin-top:-6px;" /></a>
			</div>
        </div>
    </nav>


