<!-- La función icon_header comprueba que existan módulos activos de cada "sección" de menú -->

<div class="cp_header_table">
	<div class="row">
		<div class="cp_header_top grayLight">
			<div class="cp_header_cel0">
				<?php if (isset($_SESSION[PDCLOG]["Login"])) {
						echo "<img src='images/castellano.png' style='border:none;' />&nbsp;·&nbsp;
								<a href='index.php?mnu=configuration&com=user&tpl=option' target='_self'>
									".$_SESSION[PDCLOG]["Name"]." (".$_SESSION[PDCLOG]["Login"].")
								</a> &nbsp;·&nbsp;
								<a href='logout.php' target='_self'>Desconectar</a>&nbsp;·&nbsp;";}?>
								<a href="../index.php" target="_blank">Ver sitio web</a>
            </div>
        </div>
	</div>
    <div class="cp_header_logo">
		<a href="index.php">
			<img class="image" src="images/logo.png" alt="Panel de Control" title="Panel de Control" style="height:100px;"/>
		</a>
	</div>
	<div class="cp_header_options">
    	<?php if (isset($_SESSION[PDCLOG]["Login"])): ?>
        <div class="cp_header_buttons"> 
		
<!-- Configuración, usuarios permitidos-->
			<?php if (icon_header(8)) {
				if (allowed("configuration")) { ?> 
					<a href="index.php?mnu=configuration&com=user&tpl=option" target="_self">
						<div class="cp_header_cel1">
							<img class="image" src="images/header6<?php if ($mnu == "configuration") echo "_on";?>.png" alt="Configuración" title="Configuración" />
							<div class="cp_header_cel2_txt<?php if ($mnu == "configuration") echo "_on";?>">Configuraci&oacute;n</div>
						</div>
					</a>
				<?php }else{ ?> 
					<div class="cp_header_cel1">
						<img class="image" src="images/header6_off.png" alt="Configuración" title="Configuración" />
						<div class="cp_header_cel2_txt<?php if ($mnu == "configuration") echo "_on";?>">Configuraci&oacute;n</div>
					</div>
				<?php }
			} ?>
				
				
<!-- SEO, usuarios permitidos-->
			<?php if (icon_header(7)) {
				if (allowed ("seo")) {?> 			
					<a href="index.php?mnu=seo&com=seo&tpl=titlehome" target="_self">
						<div class="cp_header_cel1">
							<img class="image" src="images/header7<?php if ($mnu == "seo") echo "_on";?>.png" alt="SEO" title="SEO" />
							<div class="cp_header_cel2_txt<?php if ($mnu == "seo") echo "_on";?>">SEO</div>
						</div>
					</a>
				<?php }else{?> 
					<div class="cp_header_cel1">
						<img class="image" src="images/header7_off.png" alt="SEO" title="SEO" />
						<div class="cp_header_cel2_txt<?php if ($mnu == "seo") echo "_on";?>">SEO</div>
					</div> 
				<?php } 
			} ?> 
				
				
<!-- Estadísticas, usuarios permitidos-->
			<?php if (icon_header(6)) {
				if (allowed ("statistics")) {?> 	
					<a href="index.php?mnu=statistics&com=statistics&tpl=general&year=<?php echo date('Y'); ?>&month=<?php echo date('n'); ?>" target="_self">
						<div class="cp_header_cel1">
							<img class="image" src="images/header9<?php if ($mnu == "statistics") echo "_on";?>.png" alt="Estadísticas" title="Estadísticas" />
							<div class="cp_header_cel2_txt<?php if ($mnu == "statistics") echo "_on";?>">Estad&iacute;sticas</div>
						</div>
					</a>
				<?php }else{?> 
					<div class="cp_header_cel1">
						<img class="image" src="images/header9_off.png" alt="Estadísticas" title="Estadísticas" />
						<div class="cp_header_cel2_txt<?php if ($mnu == "statistics") echo "_on";?>">Estad&iacute;sticas</div>
					</div>
				<?php } 
			} ?> 	
			
			
<!-- Zona privada, usuarios permitidos-->
			<?php if (icon_header(5)) {
				if (allowed ("privatezone")) {?> 	
					<a href="index.php?mnu=privatezone&com=privatezone&tpl=option&opt=userweb" target="_self">
						<div class="cp_header_cel1">
							<img class="image" src="images/header2<?php if ($mnu == "privatezone") echo "_on";?>.png" alt="Zona Privada" title="Zona Privada" />
							<div class="cp_header_cel2_txt<?php if ($mnu == "privatezone") echo "_on";?>">Inscripciones</div>
						</div>
					</a>
				<?php }else{?> 
					<div class="cp_header_cel1">
						<img class="image" src="images/header2_off.png" alt="Zona Privada" title="Zona Privada" />
						<div class="cp_header_cel2_txt<?php if ($mnu == "privatezone") echo "_on";?>">Inscripciones</div>
					</div>
				<?php } 
			} ?> 	
 
 
<!-- Newsletter, usuarios permitidos-->
			<?php if (icon_header(4)) {
				if (allowed("mailing")) {?> 	
					<a href="index.php?mnu=mailing&com=newsletter&tpl=option" target="_self">
						<div class="cp_header_cel1">
							<img class="image" src="images/header8<?php if ($mnu == "mailing") echo "_on";?>.png" alt="mailing" title="mailing" />
							<div class="cp_header_cel2_txt<?php if ($mnu == "mailing") echo "_on";?>">E-mailing</div>
						</div>
					</a>
				<?php }else{?>
					<div class="cp_header_cel1">
						<img class="image" src="images/header8_off.png" alt="mailing" title="mailing" />
						<div class="cp_header_cel2_txt<?php if ($mnu == "mailing") echo "_on";?>">E-mailing</div>
					</div>	
				<?php } 
			} ?> 	

				
<!-- Diseño, usuarios permitidos-->
			<?php if (icon_header(3)) {
				if (allowed ("design")) {?>  			
					<a href="index.php?mnu=design&com=slide&tpl=option" target="_self">
						<div class="cp_header_cel1">
							<img class="image" src="images/header2<?php if ($mnu == "design") echo "_on";?>.png" style="margin-left:10px;" alt="Dise&ntilde;o" title="Dise&ntilde;o" />
							<div class="cp_header_cel2_txt<?php if ($mnu == "design") echo "_on";?>">Dise&ntilde;o</div>  
						</div>
					</a>
				<?php }else{?> 	
					<div class="cp_header_cel1">
						<img class="image" src="images/header2_off.png" style="margin-left:10px;" alt="Dise&ntilde;o" title="Dise&ntilde;o" />
						<div class="cp_header_cel2_txt<?php if ($mnu == "design") echo "_on";?>">Dise&ntilde;o</div>  
					</div>
				<?php } 
			} ?> 
			
			
<!-- Blogs, usuarios permitidos-->
			<?php if (icon_header(2)) {
				if (allowed ("blog")) { ?>							
					<a href="index.php?mnu=blog&com=blog&tpl=option" target="_self">
						<div class="cp_header_cel1">
							<img class="image" src="images/header10<?php if ($mnu == "blog") echo "_on";?>.png" alt="Blogs" title="Blogs" />
							<div class="cp_header_cel2_txt<?php if ($mnu == "blog") echo "_on";?>">Blog</div>
						</div>
					</a>
				<?php }else{?> 	
					<div class="cp_header_cel1">
						<img class="image" src="images/header10_off.png" alt="Blogs" title="Blogs" />
						<div class="cp_header_cel2_txt<?php if ($mnu == "blog") echo "_on";?>">Blog</div>
					</div>
				<?php } 
			} ?> 
<!-- Empresas-->
			<?php if (icon_header(3)) {
				if (allowed ("company")) {?>  							
					<a href="index.php?mnu=company&com=company&tpl=option" target="_self">
						<div class="cp_header_cel1">
							<img class="image" src="images/header3<?php if ($mnu == "company") echo "_on";?>.png" alt="Empresas" title="Empresas" />
							<div class="cp_header_cel2_txt<?php if ($mnu == "company") echo "_on";?>">Empresas</div>
						</div>
					</a>
				<?php }else{?> 	
					<div class="cp_header_cel1">
						<img class="image" src="images/header3_off.png" alt="Empresas" title="Empresas" />
						<div class="cp_header_cel2_txt<?php if ($mnu == "company") echo "_on";?>">Empresas</div>
					</div>
				<?php } 
			} ?> 				
			
<!-- Contenidos, usuarios permitidos-->
			<?php if (icon_header(1)) {
				if (allowed ("content")) {?>  							
					<a href="index.php?mnu=content&com=articles&tpl=option" target="_self">
						<div class="cp_header_cel1">
							<img class="image" src="images/header1<?php if ($mnu == "content") echo "_on";?>.png" alt="Contenidos" title="Contenidos" />
							<div class="cp_header_cel2_txt<?php if ($mnu == "content") echo "_on";?>">Contenidos</div>
						</div>
					</a>
				<?php }else{?> 	
					<div class="cp_header_cel1">
						<img class="image" src="images/header1_off.png" alt="Contenidos" title="Contenidos" />
						<div class="cp_header_cel2_txt<?php if ($mnu == "content") echo "_on";?>">Contenidos</div>
					</div>
				<?php } 
			} ?> 		   
        </div>    
        <?php endif; ?>
	</div>   
</div>